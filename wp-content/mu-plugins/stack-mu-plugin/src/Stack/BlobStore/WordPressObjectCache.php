<?php

namespace Stack\BlobStore;

use \wp_cache_get;
use \wp_cache_set;
use \wp_cache_delete;
use \Stack\BlobStore;

class WordPressObjectCache implements BlobStore
{
    /**
     * Cache group for storing files
     *
     * @var string
     */
    private $cacheGroup = "media-files";

    public function __construct(string $cacheGroup = "media-files")
    {
        $this->cacheGroup = $cacheGroup;
    }

    public function get(string $key) : string
    {
        $found = false;
        $result = wp_cache_get($key, $this->cacheGroup, false, $found);
        if (false === $found) {
            throw new \Stack\BlobStore\Exceptions\NotFound(sprintf("%s not found", $key));
        }
        return $result;
    }

    public function getMeta(string $key)
    {
        $content = $this->get($key);
        return [
            "size" => strlen($content),
            "atime" => time(),
            "ctime" => time(),
            "mtime" => time(),
        ];
    }

    public function set(string $key, string $content)
    {
        if (!wp_cache_set($key, $content, $this->cacheGroup, 0)) {
            throw new \Exception(sprintf("Could not write blob to key '%s'", $key));
        }
    }

    public function remove(string $key)
    {
        if (!wp_cache_delete($key, $this->cacheGroup)) {
            throw new \Exception(sprintf("Could not remove blob at key '%s'", $key));
        }
    }
}
