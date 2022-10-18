<?php
namespace Stack;

use \add_filter;
use \remove_filter;
use \path_is_absolute;

class MediaStorage
{
    /**
     * @var string
     */
    private $relUploadsDir = null;

    public function __construct()
    {
        $this->relUploadsDir = trim(defined('STACK_MEDIA_PATH') ? STACK_MEDIA_PATH : 'wp-content/uploads', '/');

        $parts = parse_url(STACK_MEDIA_BUCKET);

        switch ($parts['scheme']) {
            case 'objcache':
                $blobStore = new \Stack\BlobStore\WordPressObjectCache();
                break;
            case 'gs':
            case 'gcs':
                $blobStore = new \Stack\BlobStore\GoogleCloudStorage($parts['host'], $parts['path'] ?: '');
                break;
            case 'file':
            case '':
                $blobStore = $this->getLocalFilesystemBlobStore($parts['path']);
                break;
            default:
                wp_die('Invalid protocol <code>' . $parts['scheme'] . '</code> for media storage.');
        }

        $fs = \Stack\MediaFilesystem\StreamWrapper::register($blobStore, "media");
        $this->register();
    }

    /*
     * Returns a LocalFilesystem BlobStore taking into account WordPress particularities
     */
    private function getLocalFilesystemBlobStore(string $path = '')
    {
        if ($this->endsWith($path, '/' . $this->relUploadsDir)) {
            $path = substr($path, 0, -strlen('/' . $this->relUploadsDir));
        }
        return new \Stack\BlobStore\LocalFilesystem($path);
    }

    public function register()
    {
        add_filter('upload_dir', [$this, 'filterUploadDir']);
        add_filter('wp_delete_file', [$this, 'filterDeleteFile'], PHP_INT_MIN);
        add_filter('wp_delete_file', [$this, 'deleteFile'], PHP_INT_MAX);
        add_filter('wp_image_editors', [$this, 'filterImageEditors']);
        add_action('init', [$this, 'serveImage']);
    }

    public function unregister()
    {
        remove_filter('upload_dir', [$this, 'filterUploadDir']);
        remove_filter('wp_delete_file', [$this, 'filterDeleteFile'], PHP_INT_MIN);
        remove_filter('wp_delete_file', [$this, 'deleteFile'], PHP_INT_MAX);
        remove_filter('wp_image_editors', [$this, 'filterImageEditors']);
        remove_action('init', [$this, 'serveImage']);
    }

    public function serveImage()
    {
        if (!isset($_SERVER['HTTP_HOST'])) {
            return;
        }

        $upload = wp_upload_dir();
        $request = (is_ssl() ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        if ($this->startsWith($request, $upload['baseurl'])) {
            $path = substr($request, strlen($upload['baseurl']));
            $filetype = wp_check_filetype($path);
            $fullPath = 'media://' . $this->relUploadsDir . $path;

            if (empty($filetype['ext'])) {
                wp_die("Directory listing disabled.", "UNAUTHORIZED", 403);
            } elseif (!file_exists($fullPath)) {
                wp_die("Not found.", "NOT FOUND", 404);
            } else {
                header('Content-Type: ' . $filetype['type']);
                readfile($fullPath);
                die();
            }
        }
    }

    public function filterImageEditors(array $image_editors) : array
    {
        $editors = array();
        foreach ($image_editors as $editor) {
            if ($editor != 'WP_Image_Editor_Imagick') {
                $editors []= $editor;
            }
        }
        return $editors;
    }

    /**
     * Filter used to set the uplaods directory.
     * We use it in order to append the ://<host>:<port> in front of file's path.
     */
    public function filterUploadDir(array $uploads) : array
    {
        $basedir = untrailingslashit($this->getUploadsDir());

        // taken from https://developer.wordpress.org/reference/functions/_wp_upload_dir/
        // If multisite (and if not the main site in a post-MU network)
        // NOTICE: we support only post-MU network setups
        if (is_multisite() && !(is_main_network() && is_main_site() && defined('MULTISITE'))) {
            $suffix = '/sites/' . get_current_blog_id();
            if (!$this->endsWith($basedir, $suffix)) {
                $basedir .= $suffix;
            }
        }

        $uploads['basedir'] = $basedir;
        $uploads['path'] = untrailingslashit($basedir . '/' . ltrim($uploads['subdir'], '/'));

        return $uploads;
    }

     /**
     * Unlink files starting with 'media://'
     *
     * This is needed because WordPress thinks a path starts with '://' is
     * not an absolute path and manipulate it in a wrong way before unlinking
     * intermediate files.
     *
     * TODO: Use `path_is_absolute` filter when a bug below is resolved:
     *       https://core.trac.wordpress.org/ticket/38907#ticket
     *
     * Because path_join() doesn't recognize :// as an absolute path, we need
     * to remove ://<host>:<port>/ since for thumbnail will multiply such prefixes
     * eg: ://<host>:<port>/wp-content/uploads/ftp://<host>:<port>/wp-content/uploads/...
     */
    public function filterDeleteFile(string $filePath) : string
    {
        $baseDir = $this->getUploadsDir();

        if ($this->startsWith($filePath, $baseDir)) {
            while ($this->startsWith($filePath, $baseDir)) {
                $filePath = $this->removePrefix($filePath, $baseDir);
            }
            $filePath = $baseDir . $filePath;
        }

        return $filePath;
    }

    // unlink() does not clear the cache if you are performing file_exists() on a remote file
    // http://php.net/manual/en/function.clearstatcache.php#105888
    public function deleteFile(string $filePath) : string
    {
        if (empty($filePath)) {
            return $filePath;
        }

        if ($this->startsWith($filePath, 'media://')) {
            @unlink($filePath);
            clearstatcache();
            return '';
        }

        return $filePath;
    }

    private function startsWith(string $haystack, string $needle) : bool
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    private function endsWith(string $haystack, string $needle) : bool
    {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        return (substr($haystack, -$length) === $needle);
    }


    private function removePrefix(string $path, string $prefix) : string
    {
        return str_replace($prefix, '', $path);
    }

    /**
     * Returns a remote  path, using the current prefix.
     */
    public function getPath(string $remotePath) : string
    {
        return sprintf("media://%s", $remotePath);
    }

    /**
     * Returns the remote  upload path, using the current prefix.
     */
    private function getUploadsDir() : string
    {
        return $this->getPath($this->relUploadsDir);
    }
}
