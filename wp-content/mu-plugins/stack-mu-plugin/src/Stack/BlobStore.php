<?php

namespace Stack;

interface BlobStore
{
    public function get(string $key);
    public function getMeta(string $key);
    public function set(string $key, string $content);
    public function remove(string $key);
}
