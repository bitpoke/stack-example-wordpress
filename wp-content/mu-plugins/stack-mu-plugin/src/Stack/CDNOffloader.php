<?php
namespace Stack;

class CDNOffloader implements ContentFilterInterface
{
    private $enabled = false;
    private $paths = array();
    private $hosts = array();
    private $offload_extensions = array();
    private $cdn_host = '';
    private $re = '';

    public function __construct($cdn_host = '')
    {
        if (!empty($cdn_host)) {
            $this->cdn_host = $cdn_host;
        } elseif (!defined('CDN_HOST')) {
            define('CDN_HOST', getenv('CDN_HOST', true) ?: '');
        }
        $this->cdn_host = apply_filters('bpk_cdn_host', CDN_HOST);
        if (empty($this->cdn_host)) {
            $this->enabled = false;
            return;
        }

        $this->hosts = array_unique(apply_filters('bpk_cdn_offload_hosts', array(
            parse_url(content_url(), PHP_URL_HOST),
            parse_url(includes_url(), PHP_URL_HOST),
        )));
        if (empty($this->hosts)) {
            $this->enabled = false;
            return;
        }

        $paths = apply_filters('bpk_cdn_offload_paths', array(
            parse_url(content_url(), PHP_URL_PATH),
            parse_url(includes_url(), PHP_URL_PATH),
            parse_url(get_stylesheet_directory_uri(), PHP_URL_PATH),
        ));
        $paths = array_map(function ($path) {
            return trim($path, "/");
        }, $paths);
        $this->paths = array_unique($paths);

        $this->offload_extensions = array_unique(apply_filters('bpk_cdn_offload_extensions', array(
            // images
            'png', 'jpg', 'jpeg', 'tif', 'tiff', 'svg', 'svgz', 'gif', 'webp', 'avif', 'heic', 'bmp',

            // video
            'avi', 'mkv', 'webm', 'mp4', 'mov', 'mpeg', 'mpe', 'swf',

            // font files
            'ttf', 'otf', 'eot', 'woff', 'woff2',

            // media files
            'm3u', 'pls', 'midi', 'mp3', 'flac', 'ogg',

            // web files
            'js', 'css', 'ejs',

            // archives
            'zip', 'tar', 'gz', 'tgz', 'bz2', 'tbz2', 'zst', '7z', 'rar', 'tar.gz', 'tar.bz2',

            // office files
            'doc', 'docx', 'odt', 'csv', 'xls', 'xlsx', 'ods', 'ppt', 'pptx', 'odp', 'odg', 'odf', 'pdf', 'ps', 'eps',
            'pict', 'psd',

            // generic binary file
            'bin',  'iso', 'jar', 'class', 'apk', 'dmg', 'exe'
        )));
        usort($this->offload_extensions, function ($a, $b) {
            return strlen($b) - strlen($a);
        });

        $slash = '(?:/|\\\/)'; // match / as well as \/ for JS/JSON escaped paths
        # $slash = '/';

        $protocol = "(?:(?:(https?):)?($slash)$slash)";

        $hosts = join('|', array_map(function ($s) {
            return preg_quote($s, '#');
        }, $this->hosts));

        $paths = join('|', array_map(function ($s) {
            return preg_quote($s, '#');
        }, $this->paths));
        $paths = str_replace('/', $slash, $paths);

        $exts = join('|', array_map(function ($s) {
            return preg_quote($s, '#');
        }, $this->offload_extensions));

        $path = "$slash(?:$paths)$slash" . '[\\\\/%\w\p{S}\.-]+?' . "\.($exts)";

        $re = "(?<=\A|\s|\b|'|\"|\\\\n)$protocol($hosts)($path)\b";
        /* var_dump($re); */
        $this->re = "#$re#u";


        $this->enabled = true;

        add_filter('script_loader_src', array($this, 'filter'));
        add_filter('style_loader_src', array($this, 'filter'));
    }

    public function enabled(): bool
    {
        if (!$this->enabled) {
            return false;
        }
        return !apply_filters('cdn_offloader_bypass', false);
    }

    public static function offload(string $content = '')
    {
        $offloader = new static();
        return $offloader->filter($content);
    }

    public function filter(string $content): string
    {
        if (!$this->enabled) {
            return $content;
        }

        $_content = preg_replace_callback($this->re, array($this, 'replace'), $content);

        if (null === $_content) {
            trigger_error('CDN offloader preg error: ' . preg_last_error_msg(), E_USER_WARNING);
            return $content;
        }

        return $_content;
    }

    private function replace($matches)
    {
        $scheme      = $matches[1];
        $slash       = $matches[2];
        $request_uri = $matches[4];
        $extension   = $matches[5];

        // avoid mixed content by always enforcing the proper scheme
        $offloaded_scheme = ($scheme == 'https' || is_ssl() ? 'https' : 'http');

        $offloaded =  $offloaded_scheme . ":$slash$slash" . $this->cdn_host . $request_uri;

        return apply_filters('bpk_cdn_offload', $offloaded, $matches);
    }
}
