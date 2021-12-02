<?php
namespace Stack;

/**
 * Use env() from oscarotero/env
 */
use function Env\env;

class Config
{
    public static function loadDefaults()
    {
        static $runCount = 0;

        if ($runCount > 0) {
            return;
        }

        $uploads = wp_upload_dir(null, false, false);
        $homeURL = home_url();

        /*
         * uploads dir relative to webroot
         * this takes into account CONTENT_DIR (defined by bedrock setups)
         * and defaults to `wp-content/uploads`
         */
        if ($homeURL === substr($uploads['baseurl'], 0, strlen($homeURL)) &&
            !(substr($homeURL, -strlen("/wp")) === "/wp")) {
            $relUploadsDir = substr($uploads['baseurl'], strlen($homeURL));
        } else {
            $relUploadsDir = (defined('CONTENT_DIR') ? CONTENT_DIR : '/wp-content') . '/uploads';
        }
        $relUploadsDir = ltrim($relUploadsDir, '/');
        self::defineFromEnv("STACK_MEDIA_PATH", env('MEDIA_PATH') ?: $relUploadsDir, '/');
        self::defineFromEnv("STACK_MEDIA_BUCKET", env('MEDIA_BUCKET') ?: "file://" . $uploads['basedir']);

        self::defineFromEnv("DOBJECT_CACHE_PRELOAD", false);

        self::defineFromEnv("MEMCACHED_HOST", "");
        self::defineFromEnv("MEMCACHED_DISCOVERY_HOST", "");

        self::defineFromEnv("STACK_PAGE_CACHE_ENABLED", false);
        self::defineFromEnv("STACK_PAGE_CACHE_AUTOMATIC_PLUGIN_ON_OFF", true);
        self::defineFromEnv("STACK_PAGE_CACHE_BACKEND", "");
        self::defineFromEnv("STACK_PAGE_CACHE_KEY_PREFIX", "");

        if (STACK_PAGE_CACHE_BACKEND == "redis") {
            self::defineFromEnv("RT_WP_NGINX_HELPER_REDIS_HOSTNAME", "", "STACK_PAGE_CACHE_REDIS_HOST");
            self::defineFromEnv("RT_WP_NGINX_HELPER_REDIS_PORT", "", "STACK_PAGE_CACHE_REDIS_PORT");
            self::define("RT_WP_NGINX_HELPER_REDIS_PREFIX", STACK_PAGE_CACHE_KEY_PREFIX);
        } elseif (STACK_PAGE_CACHE_BACKEND == "memcached") {
            self::defineFromEnv("RT_WP_NGINX_HELPER_MEMCACHED_HOSTNAME", "", "STACK_PAGE_CACHE_MEMCACHED_HOST");
            self::defineFromEnv("RT_WP_NGINX_HELPER_MEMCACHED_PORT", "", "STACK_PAGE_CACHE_MEMCACHED_PORT");
            self::define("RT_WP_NGINX_HELPER_MEMCACHED_PREFIX", STACK_PAGE_CACHE_KEY_PREFIX);

            $versionedCacheKey = STACK_PAGE_CACHE_KEY_PREFIX . "version";
            self::define("RT_WP_NGINX_HELPER_MEMCACHED_VERSIONED_CACHE_KEY", $versionedCacheKey);
        }

        self::definePath("GIT_DIR", env("SRC_DIR") ?: "/var/run/presslabs.org/code/src");
        self::definePath("GIT_KEY_FILE", "/var/run/secrets/presslabs.org/instance/id_rsa");
        self::definePath("GIT_KEY_FILE", (rtrim(env("HOME"), '/') ?: "/var/www") . "/.ssh/id_rsa");

        self::defineFromEnv("STACK_METRICS_ENABLED", true);
        self::define('STACK_REST_API_VERSION', '1');

        $runCount++;
    }

    public static function defineFromEnv(string $name, $defaultValue, string $envName = "")
    {
        $envName = $envName ?: $name;
        $value = env($envName);
        if (false !== $value) {
            $value = $value ?: $defaultValue;
        }
        self::define($name, $value);
    }

    public static function definePath(string $name, string $path, string $defaultPath = "")
    {
        if (file_exists($path)) {
            self::define($name, $path);
        } elseif (!empty($defaultPath)) {
            self::define($name, $defaultPath);
        }
    }

    public static function define(string $name, $value)
    {
        defined($name) or define($name, $value);
    }
}
