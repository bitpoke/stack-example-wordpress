<?php
/**
 * Plugin Name: Bitpoke Stack Object Cache
 * Plugin URI: https://www.bitpoke.io/stack/
 * Description: WordPress object cache backend for Bitpoke Stack. This backend is based on memcached.
 * Version: 0.8.0
 * Author: Bitpoke
 * Author URI: http://www.bitpoke.io/
 */

if (!defined('MEMCACHED_HOST')) {
    define('MEMCACHED_HOST', isset($_ENV['MEMCACHED_HOST']) ? $_ENV['MEMCACHED_HOST'] : '');
}

if (!defined('MEMCACHED_DISCOVERY_HOST')) {
    define('MEMCACHED_DISCOVERY_HOST', isset($_ENV['MEMCACHED_DISCOVERY_HOST']) ? $_ENV['MEMCACHED_DISCOVERY_HOST'] : '');
}

if (MEMCACHED_DISCOVERY_HOST != "" || MEMCACHED_HOST != ""):

/**
 * Adds a value to cache.
 *
 * If the specified key already exists, the value is not stored and the function
 * returns false.
 *
 * @param string    $key        The key under which to store the value.
 * @param mixed     $value      The value to store.
 * @param string    $group      The group value appended to the $key.
 * @param int       $expiration The expiration time, defaults to 0.
 * @return bool                 Returns TRUE on success or FALSE on failure.
 */
function wp_cache_add( $key, $value, $group = '', $expiration = 0 ) {
    global $wp_object_cache;
    return $wp_object_cache->add( $key, $value, $group, $expiration );
}

/**
 * Sets a value in cache.
 *
 * The value is set whether or not this key already exists.
 *
 * @param string    $key        The key under which to store the value.
 * @param mixed     $value      The value to store.
 * @param string    $group      The group value appended to the $key.
 * @param int       $expiration The expiration time, defaults to 0.
 * @return bool                 Returns TRUE on success or FALSE on failure.
 */
function wp_cache_set( $key, $value, $group = '', $expiration = 0 ) {
    global $wp_object_cache;
    return $wp_object_cache->set( $key, $value, $group, $expiration );
}

/**
 * Retrieve object from cache.
 *
 * Gets an object from cache based on $key and $group.
 *
 * @param string        $key        The key under which to store the value.
 * @param string        $group      The group value appended to the $key.
 * @param bool          $force      Whether or not to force a cache invalidation.
 * @param null|bool     $found      Variable passed by reference to determine if the value was found or not.
 * @return bool|mixed               Cached object value.
 */
function wp_cache_get( $key, $group = '', $force = false, &$found = null) {
    global $wp_object_cache;
    return $wp_object_cache->get( $key, $group, $force, $found );
}

/**
 * Remove the item from the cache.
 *
 * @param string    $key    The key under which to store the value.
 * @param string    $group  The group value appended to the $key.
 * @return bool             Returns TRUE on success or FALSE on failure.
 */
function wp_cache_delete( $key, $group = '' ) {
    global $wp_object_cache;
    return $wp_object_cache->delete( $key, $group );
}

/**
 * Replaces a value in cache.
 *
 * This method is similar to "add"; however, is does not successfully set a value if
 * the object's key is not already set in cache.
 *
 * @param string    $key        The key under which to store the value.
 * @param mixed     $value      The value to store.
 * @param string    $group      The group value appended to the $key.
 * @param int       $expiration The expiration time, defaults to 0.
 * @return bool                 Returns TRUE on success or FALSE on failure.
 */
function wp_cache_replace( $key, $value, $group = '', $expiration = 0 ) {
    global $wp_object_cache;
    return $wp_object_cache->replace( $key, $value, $group, $expiration );
}

/**
 * Increment a numeric item's value.
 *
 * @param string    $key    The key under which to store the value.
 * @param int       $offset The amount by which to increment the item's value.
 * @param string    $group  The group value appended to the $key.
 * @return int|bool         Returns item's new value on success or FALSE on failure.
 */
function wp_cache_increment( $key, $offset = 1, $group = '' ) {
    global $wp_object_cache;
    return $wp_object_cache->increment( $key, $offset, $group );
}

/**
 * Increment a numeric item's value.
 *
 * This is the same as wp_cache_increment, but kept for back compatibility. The original
 * WordPress caching backends use wp_cache_incr.
 *
 * @param string    $key    The key under which to store the value.
 * @param int       $offset The amount by which to increment the item's value.
 * @param string    $group  The group value appended to the $key.
 * @return int|bool         Returns item's new value on success or FALSE on failure.
 */
function wp_cache_incr( $key, $offset = 1, $group = '' ) {
    return wp_cache_increment( $key, $offset, $group );
}

/**
 * Decrement a numeric item's value.
 *
 * @param string    $key    The key under which to store the value.
 * @param int       $offset The amount by which to decrement the item's value.
 * @param string    $group  The group value appended to the $key.
 * @return int|bool         Returns item's new value on success or FALSE on failure.
 */
function wp_cache_decrement( $key, $offset = 1, $group = '' ) {
    global $wp_object_cache;
    return $wp_object_cache->decrement( $key, $offset, $group );
}

/**
 * Decrement a numeric item's value.
 *
 * Same as wp_cache_decrement. Original WordPress caching backends use wp_cache_decr.
 *
 * @param string    $key    The key under which to store the value.
 * @param int       $offset The amount by which to decrement the item's value.
 * @param string    $group  The group value appended to the $key.
 * @return int|bool         Returns item's new value on success or FALSE on failure.
 */
function wp_cache_decr( $key, $offset = 1, $group = '' ) {
    return wp_cache_decrement( $key, $offset, $group );
}


/**
 * Sets up Object Cache Global and assigns it.
 *
 * @global  Stack_Object_Cache     $wp_object_cache    WordPress Object Cache
 * @return  void
 */
function wp_cache_init() {
    global $wp_object_cache;
    $wp_object_cache = new WP_Object_Cache();
}

/**
 * Invalidate all items in the cache.
 *
 * @param int       $delay  Number of seconds to wait before invalidating the items.
 * @return bool             Returns TRUE on success or FALSE on failure.
 */
function wp_cache_flush() {
    global $wp_object_cache;
    return $wp_object_cache->flush();
}

/**
 * Adds a group or set of groups to the list of non-persistent groups.
 *
 * @param   string|array    $groups     A group or an array of groups to add.
 * @return  void
 */
function wp_cache_add_non_persistent_groups( $groups ) {
    global $wp_object_cache;
    $wp_object_cache->add_non_persistent_groups( $groups );
}

/**
 * Adds a group or set of groups to the list of non-persistent groups.
 *
 * @param   string|array    $groups     A group or an array of groups to add.
 * @return  void
 */
function wp_cache_add_global_groups( $groups ) {
    global $wp_object_cache;
    $wp_object_cache->add_global_groups( $groups );
}

/**
 * Closes the cache.
 *
 * This function has ceased to do anything since WordPress 2.5. The
 * functionality was removed along with the rest of the persistent cache. This
 * does not mean that plugins can't implement this function when they need to
 * make sure that the cache is cleaned up after WordPress no longer needs it.
 *
 * @since 2.0.0
 *
 * @return  bool    Always returns True
 */
function wp_cache_close() {
    global $wp_object_cache;
    $wp_object_cache->close();
}

/**
 * Returns cache stats for the current request.
 *
 * @return  array
 */
function wp_cache_get_stats() {
    global $wp_object_cache;
    $wp_object_cache->getStats();
}

class WP_Object_Cache {
    /** @var \Stack\ObjectCache */
    private $backend;

    public function __construct() {
        // load Composer autoloader if bundled
        if ( file_exists( __DIR__ . '/mu-plugins/stack-mu-plugin/vendor/autoload.php' ) ) {
            // we are copied into WP_CONTENT_DIR
            require_once __DIR__ . '/mu-plugins/stack-mu-plugin/vendor/autoload.php';
        } elseif ( file_exists( dirname( __DIR__ ) . '/vendor/autoload.php' ) ) {
            // we are symlinked into WP_CONTENT_DIR
            require_once dirname( __DIR__ ) . '/vendor/autoload.php';
        }

        if ( ! class_exists( '\Stack\ObjectCache\Memcached' ) ) {
            wp_die( 'Bitpoke Stack WordPress mu-plugin is not fully installed! Please install with Composer or download full release archive.');
        }

        $backend = new \Stack\ObjectCache\Memcached();
        $this->backend = $backend;
    }

    public function getBackendClass() {
        return get_class( $this->backend );
    }

    public function add_global_groups( $groups ) {
        return $this->backend->add_global_groups( $groups );
    }

    public function add_non_persistent_groups( $groups ) {
        return $this->backend->add_non_persistent_groups( $groups );
    }

    public function switch_to_blog( $blog_id ) {
        return $this->backend->switch_to_blog( $blog_id );
    }

    public function get( $key, $group = 'default', $force = false, &$found = null ) {
        return $this->backend->get( $key, $group, $force, $found );
    }

    public function set( $key, $value, $group = 'default', $expiration = 0 ) {
        return $this->backend->set( $key, $value, $group, $expiration );
    }

    public function add( $key, $value, $group = 'default', $expiration = 0 ) {
        return $this->backend->add( $key, $value, $group, $expiration );
    }

    public function replace( $key, $value, $group = 'default', $expiration = 0 ) {
        return $this->backend->replace( $key, $value, $group, $expiration );
    }

    public function delete( $key, $group = 'default' ) {
        return $this->backend->delete( $key, $group );
    }

    public function flush() {
        return $this->backend->flush();
    }

    public function increment( $key, $offset = 1, $group = 'default' ) {
        return $this->backend->increment( $key, $offset, $group );
    }

    public function incr( $key, $offset = 1, $group = 'default' ) {
        return $this->backend->increment( $key, $offset, $group );
    }

    public function decrement( $key, $offset = 1, $group = 'default' ) {
        return $this->backend->decrement( $key, $offset, $group );
    }

    public function decr( $key, $offset = 1, $group = 'default' ) {
        return $this->backend->decrement( $key, $offset, $group );
    }

    public function close() {
        return $this->backend->close();
    }

    public function stats() {
        return $this->backend->stats();
    }

    public function getStats() {
        return $this->backend->getStats();
    }
}

endif;
