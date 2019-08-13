<?php

namespace Stack\BlobStore;

use \Stack\BlobStore;

use \path_join;

class LocalFilesystem implements BlobStore
{
    /**
     * Cache group for storing files
     *
     * @var string
     */
    private $uploadsDir = null;

    public function __construct(string $uploadsDir)
    {
        $this->uploadsDir = $uploadsDir;
    }

    public function get(string $key) : string
    {
        $path = path_join($this->uploadsDir, $key);
        if (!file_exists($path)) {
            throw new \Stack\BlobStore\Exceptions\NotFound(sprintf("%s not found", $key));
        }
        $result = file_get_contents($path);
        if (false === $result) {
            throw new \Exception(sprintf("%s get failed", $key));
        }
        return $result;
    }

    public function getMeta(string $key)
    {
        $path = path_join($this->uploadsDir, $key);
        if (!is_file($path)) {
            throw new \Stack\BlobStore\Exceptions\NotFound(sprintf("%s not found", $key));
        }
        $stat = stat($path);
        $content = $this->get($key);
        return $stat;
    }

    public function set(string $key, string $content)
    {
        $path = path_join($this->uploadsDir, $key);
        $dir = dirname($path);
        if (false === wp_mkdir_p($dir)) {
            throw new \Exception(sprintf("Could not create directory '%s'", $dir));
        }
        if (false === file_put_contents($path, $content)) {
            throw new \Exception(sprintf("Could not write blob to key '%s'", $key));
        }
    }

    public function remove(string $key)
    {
        $path = path_join($this->uploadsDir, $key);
        if (false === unlink($path)) {
            throw new \Exception(sprintf("Could not remove blob at key '%s'", $key));
        }
    }
}
