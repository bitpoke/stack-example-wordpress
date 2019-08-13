<?php
namespace Stack;

interface ObjectCache
{
    public function add_global_groups($groups);
    public function add_non_persistent_groups($groups);
    public function switch_to_blog($blog_id);
    public function get($key, $group = 'default', $force = false, &$found = null);
    public function set($key, $value, $group = 'default', $expiration = 0);
    public function add($key, $value, $group = 'default', $expiration = 0);
    public function replace($key, $value, $group = 'default', $expiration = 0);
    public function delete($key, $group = 'default');
    public function flush();
    public function close();
    public function increment($key, $offset = 1, $group = 'default');
    public function decrement($key, $offset = 1, $group = 'default');
    public function stats();
}
