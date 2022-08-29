<?php

namespace Stack\MediaFilesystem;

use \is_wp_error;
use \Stack\BlobStore;

/*
 * PHP stream wrapper for dealing with WordPress media files. This is and adaptation of
 * https://github.com/Automattic/vip-go-mu-plugins/blob/master/files/class-vip-filesystem-stream-wrapper.php
 */

class StreamWrapper
{

    /**
     * Default protocol
     */
    const DEFAULT_PROTOCOL = 'media';

    const FILE_WRITABLE_MODE = 33206; // 100666 in octal
    const DIRECTORY_WRITABLE_MODE = 16895; // 40777 in octal

    /**
     * @var BlobStore[] $clients The default clients to use if using
     *      global methods such as fopen on a stream wrapper. Keyed by protocol.
     */
    private static $clients = [];

    /**
     * The Stream context. Set by PHP
     *
     * @since   1.0.0
     * @access  public
     * @var     resource|nul    Stream context
     */
    public $context;

    /**
     * The blob store client
     *
     * @since   1.0.0
     * @access  public
     * @var     BlobStore Blob store client
     */
    public $client;

    /**
     * The file resource fetched through the blob store
     *
     * @since   1.0.0
     * @access  protected
     * @var     resource    The file resource
     */
    protected $file;

    /**
     * The path to the opened file
     *
     * @since   1.0.0
     * @access  protected
     * @var     string      Opened path
     */
    protected $path;

    /**
     * The temp file URI
     *
     * @since   1.0.0
     * @access  protected
     * @var     string      The file URI
     */
    protected $uri;

    /**
     * Is file seekable
     *
     * @since   1.0.0
     * @access  protected
     * @var     bool        Is seekable
     */
    protected $seekable;

    /**
     * Protocol for the stream to register to
     *
     * @since   1.0.0
     * @access  private
     * @var string  The defined protocol.
     */
    private $protocol;

    /**
     * Protocol prefix for stream wrapper, eg. media://
     *
     * @since   1.0.0
     * @access  private
     * @var string  The protocol prefix
     */
    private $protocol_prefix;

    /**
     * StreamWrapper constructor
     *
     * @param BlobStore $client
     * @param string $protocol
     */
    public function __construct(string $protocol = null)
    {
        $this->protocol = $protocol ?: self::DEFAULT_PROTOCOL;
        $this->protocol_prefix = $this->protocol . '://';
        $this->client = self::$clients[$this->protocol];
    }

    /**
     *  Register the Stream.
     *
     * Will unregister stream first if it's already registered
     *
     * @since   1.0.0
     * @access  public
     *
     * @return  bool    true if success, false if failure
     */
    public static function register(BlobStore $client, string $protocol = null)
    {
        $protocol = $protocol ?: self::DEFAULT_PROTOCOL;

        if (!in_array($protocol, stream_get_wrappers(), true)) {
            if (!stream_wrapper_register($protocol, StreamWrapper::class, STREAM_IS_URL)) {
                throw new \RuntimeException("Failed to register '$protocol://' protocol");
            }
            self::$clients[$protocol] = $client;
            return true;
        }

        return false;
    }

    /**
     * Unregisters the SteamWrapper
     *
     * @param string $protocol The name of the protocol to unregister. **Defaults
     *        to** `gs`.
     */
    public static function unregister($protocol = null)
    {
        $protocol = $protocol ?: self::DEFAULT_PROTOCOL;
        stream_wrapper_unregister($protocol);
        unset(self::$clients[$protocol]);
    }

    /**
     * Opens a file
     *
     * @since   1.0.0
     * @access  public
     *
     * @param   string $path URL that was passed to the original function
     * @param   string $mode Type of access. See `fopen` docs
     * @param   $options
     * @param   string $opened_path
     *
     * @return  bool    True on success or false on failure
     */
    public function stream_open($path, $mode, $options, &$opened_path)
    {
        $path = $this->trim_path($path);

        try {
            try {
                $result = $this->client->get($path);
            } catch (\Stack\BlobStore\Exceptions\NotFound $e) {
                // File doesn't exist on File service so create new file
                $result = '';
            } catch (\Exception $e) {
                trigger_error(
                    sprintf('stream_open failed for %s with error: %s', $path, $e->getMessage()),
                    E_USER_WARNING
                );
                return false;
            }

            // Converts file contents into stream resource
            $file = $this->string_to_resource($result);

            // Get meta data
            $meta           = stream_get_meta_data($file);
            $this->seekable = $meta['seekable'];
            $this->uri      = $meta['uri'];

            $this->file = $file;
            $this->path = $path;

            return true;
        } catch (\Exception $e) {
            trigger_error(
                sprintf('stream_open failed for %s with error: %s', $path, $e->getMessage()),
                E_USER_WARNING
            );

            return false;
        }
    }

    /**
     * Close a file
     *
     * @since   1.0.0
     * @access  public
     */
    public function stream_close()
    {
        return $this->close_handler($this->file);
    }

    /**
     * Check for end of file
     *
     * @since   1.0.0
     * @access  public
     *
     * @return bool
     */
    public function stream_eof()
    {
        return feof($this->file);
    }

    /**
     * Read the contents of the file
     *
     * @since   1.0.0
     * @access  public
     *
     * @param   $count  Number of bytes to read
     *
     * @return  string  The file contents
     */
    public function stream_read($count)
    {
        $string = fread($this->file, $count);
        if (false === $string) {
            trigger_error(
                sprintf('Error reading from file: %s', $this->path),
                E_USER_WARNING
            );
            return '';
        }

        return $string;
    }

    /**
     * Flush to a file
     *
     * @since   1.0.0
     * @access  public
     *
     * @return  bool    True on success. False on failure
     */
    public function stream_flush()
    {
        if (! $this->file) {
            return false;
        }

        try {
            // Upload to blob storage
            $this->client->set($this->path, file_get_contents($this->uri));
            return fflush($this->file);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Seek a pointer position on a file
     *
     * @since   1.0.0
     * @access  public
     *
     * @param   int   $offset
     * @param   int   $whence
     *
     * @return  bool  True if position was updated, False if not
     */
    public function stream_seek($offset, $whence)
    {
        if (! $this->seekable) {
            // File not seekable
            trigger_error(
                sprintf('File not seekable: %s', $this->path),
                E_USER_WARNING
            );
            return false;
        }

        $result = fseek($this->file, $offset, $whence);

        if (-1 === $result) {
            // Seek failed
            trigger_error(
                sprintf('Error seeking on file: %s', $this->path),
                E_USER_WARNING
            );
            return false;
        }

        return true;
    }

    /**
     * Write to a file
     *
     * @since   1.0.0
     * @accesss public
     *
     * @param   string      $data   The data to be written
     *
     * @return  int|bool    Number of bytes written or false on error
     */
    public function stream_write($data)
    {
        $length = fwrite($this->file, $data);

        if (false === $length) {
            trigger_error(
                sprintf('Error writing to file: %s', $this->path),
                E_USER_WARNING
            );
            return false;
        }

        return $length;
    }

    /**
     * Delete a file
     *
     * @since   1.0.0
     * @access  public
     * @param   string  $path
     *
     * @return  bool    True if success. False on failure
     */
    public function unlink($path)
    {
        $path = $this->trim_path($path);

        try {
            $this->client->remove($path);
            $this->close_handler();
            return true;
        } catch (\Exception $e) {
            trigger_error(
                sprintf('unlink failed for %s with error: %s', $path, $e->getMessage()),
                E_USER_WARNING
            );

            return false;
        }
    }

    /**
     * Get file stats
     *
     * @since   1.0.0
     * @access  public
     *
     * @return  array   The file statistics
     */
    public function stream_stat()
    {
        return fstat($this->file);
    }

    /**
     * Get file stats by path
     *
     * Use by functions like is_dir, file_exists etc.
     * See: http://php.net/manual/en/streamwrapper.url-stat.php
     *
     * @since   1.0.0
     * @access  public
     *
     * @param   string      $path
     * @param   int         $flags
     *
     * @return  array|bool  The file statistics or false if failed
     */
    public function url_stat($path, $flags)
    {
        $path = $this->trim_path($path);

        // Default stats
        $stats = array (
            'dev'     => 0,
            'ino'     => 0,
            'mode'    => self::DIRECTORY_WRITABLE_MODE,
            'nlink'   => 0,
            'uid'     => 0,
            'gid'     => 0,
            'rdev'    => 0,
            'size'    => 0,
            'atime'   => 0,
            'mtime'   => 0,
            'ctime'   => 0,
            'blksize' => 0,
            'blocks'  => 0,
        );

        if ($this->is_dir($path)) {
            return $stats;
        }

        try {
            $info = $this->client->getMeta($path);

            // Here we should parse the meta data into the statistics array
            // and then combine with data from `is_file` API
            // see: http://php.net/manual/en/function.stat.php
            $stats[2]  = $stats['mode'] = self::FILE_WRITABLE_MODE; // read+write permissions
            $stats[7]  = $stats['size'] = (int) $info['size'];
            $stats[8]  = $stats['atime'] = (int) $info['mtime'];
            $stats[9]  = $stats['mtime'] = (int) $info['mtime'];
            $stats[10] = $stats['ctime'] = (int) $info['mtime'];

            return $stats;
        } catch (\Stack\BlobStore\Exceptions\NotFound $e) {
            return false;
        } catch (\Exception $e) {
            trigger_error(
                sprintf('url_stat failed for %s with error: %s', $path, $e->getMessage()),
                E_USER_WARNING
            );
            return false;
        }
    }

    /**
     * This method is called in response to fseek() to determine the current position.
     *
     * @since   1.0.0
     * @access  public
     *
     * @return  bool|int    Returns current position or false on failure
     */
    public function stream_tell()
    {
        return $this->file ? ftell($this->file) : false;
    }

    /**
     * Called in response to rename() to rename a file or directory.
     *
     * @since   1.0.0
     * @access  public
     *
     * @param   string  $path_from  Path to file to rename
     * @param   string  $path_to    New path to the file
     *
     * @return  bool    True on successful rename
     */
    public function rename($path_from, $path_to)
    {
        if ($path_from === $path_to) {
            // from and to path are identical so do nothing
            return true;
        }

        $path_from = $this->trim_path($path_from);
        $path_to = $this->trim_path($path_to);

        try {
            // Get original file first
            // Note: Subooptimal. Should figure out a way to do this without downloading the file as this could
            //       get really inefficient with large files

            $result = $this->client->get($path_from);

            // Convert to actual file to upload to new path
            $file     = $this->string_to_resource($result);
            $meta     = stream_get_meta_data($file);
            $filePath = $meta['uri'];

            // Upload to file service
            $this->client->set($filePath, file_get_contents($file_to));

            // Delete old file
            $result = $this->client->remove($path_from);

            return true;
        } catch (\Exception $e) {
            trigger_error(
                sprintf('rename/delete_file/from failed for %s with error: %s', $path_from, $e->getMessage()),
                E_USER_WARNING
            );

            return false;
        }
    }

    /**
     * Called in response to mkdir()
     *
     * @since   1.0.0
     * @access  public
     *
     * @param   string  $path
     * @param   int     $mode
     * @param   int     $options
     *
     * @return  bool
     */
    public function mkdir($path, $mode, $options)
    {
        // Currently, it will always return true as directories are automatically created on the Filesystem API
        return true;
    }

    /**
     * Called in response to opendir()
     *
     * @since   1.0.0
     * @access  public
     *
     * @param   string  $path
     * @param   int     $options
     *
     * @return  bool
     */
    public function dir_opendir($path, $options)
    {
        // Currently, returns true whenever the path is a directory
        if ($this->is_dir($path)) {
            $this->file = null;
            $this->uri  = $path;
            $this->path = $this->trim_path($path);
            return true;
        }
        return false;
    }

    /**
     * Called in response to closedir()
     *
     * @since   1.0.0
     * @access  public
     *
     * @return  bool
     */
    public function dir_closedir()
    {
        // Currently, returns true whenever the path is a directory
        if ($this->is_dir($this->uri)) {
            $this->file = null;
            $this->uri = null;
            $this->path = null;
            return true;
        }
        return false;
    }

    /**
     * Called in response to rewinddir()
     *
     * @since   1.0.0
     * @access  public
     *
     * @return  bool
     */
    public function dir_rewinddir()
    {
        // Currently, returns true whenever the path is a directory
        return $this->is_dir($this->uri);
    }

    /**
     * Called in response to readdir()
     *
     * @since   1.0.0
     * @access  public
     *
     * @return  string|bool
     */
    public function dir_readdir()
    {
        // Currently, it will always return false as directories are automatically created on the Filesystem API
        return false;
    }

    /**
     * Set metadata on a stream
     *
     * @link http://php.net/manual/en/streamwrapper.stream-metadata.php
     *
     * @since   1.0.0
     * @access  public
     *
     * @param   string  $path
     * @param   int     $option
     * @param   mixed   $value
     *
     * @return  bool
     */
    public function stream_metadata($path, $option, $value)
    {
        // all meta operation are noop, for broader compaibility with media plugin ecosystem
        return true;
    }

    /**
     * Called in response to stream_select()
     *
     * @link http://php.net/manual/en/streamwrapper.stream-castt.php
     *
     * @since   1.0.0
     * @access  public
     *
     * @param   int             $cast_as
     *
     * @return  resource|bool
     */
    public function stream_cast($cast_as)
    {
        if (! is_null($this->file)) {
            return $this->file;
        }

        return false;
    }

    /**
     * Write file to a temporary resource handler
     *
     * @since   1.0.0
     * @access  protected
     *
     * @param   string     $data   The file content to be written
     *
     * @return  resource   Returns resource or false on write error
     */
    protected function string_to_resource($data)
    {
        // Create a temporary file
        $tmp_handler = tmpfile();
        if (false === fwrite($tmp_handler, $data)) {
            trigger_error(
                'Error creating temporary resource',
                E_USER_ERROR
            );
        }
        // Need to rewind file pointer as fwrite moves it to EOF
        rewind($tmp_handler);

        return $tmp_handler;
    }

    /**
     * Closes the open file handler
     *
     * @since   1.0.0
     * @access  protected
     *
     * @return  bool        True on success. False on failure.
     */
    protected function close_handler()
    {
        if (! $this->file) {
            return true;
        }

        $result = fclose($this->file);

        if ($result) {
            $this->file = null;
            $this->path = null;
            $this->uri  = null;
        }

        return $result;
    }

    /**
     * Converted the protocol file path into something the File Service
     * API client can use
     *
     * @since   1.0.0
     * @access  protected
     * @param   string      $path       Original protocol path
     *
     * @return  string      Modified path
     */
    protected function trim_path($path)
    {
        $len = strlen($this->protocol_prefix);
        while (substr($path, 0, $len) === $this->protocol_prefix) {
            $path = substr($path, $len);
        }
        return $path;
    }

    /**
     * Checks if the specified path is a directory
     *
     * All paths without extensions are considered directories.
     * This is to work around wp_upload_dir doing file_exists
     * checks on the uploads directory on every page load.
     *
     * Copied from humanmade's S3 plugin
     *     https://github.com/humanmade/S3-Uploads
     *
     * @since   1.0.0
     * @access  protected
     * @param   string      $path       Original protocol path
     *
     * @return  bool        Checks that the path is for a directory
     */
    protected function is_dir($path)
    {
        $path = $this->trim_path($path);
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        return empty($extension);
    }
}
