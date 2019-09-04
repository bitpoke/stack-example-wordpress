<?php
namespace Stack;

class Config
{
    public static function loadDefaults()
    {
        static $runCount = 0;

        if ($runCount > 0) {
            return;
        }
        /*
         * Expose global env() function from oscarotero/env
         */
        \Env::init();

        $uploads = wp_upload_dir(null, false, false);
        $homeURL = home_url();

        /*
         * uploads dir relative to webroot
         * this takes into account CONTENT_DIR (defined by bedrock setups)
         * and defaults to `wp-content/uploads`
         */
        if ($homeURL === substr($uploads['baseurl'], 0, strlen($homeURL))) {
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

        self::definePath("GIT_DIR", env("SRC_DIR") ?: "/var/run/presslabs.org/code/src");
        self::definePath("GIT_KEY_FILE", "/var/run/secrets/presslabs.org/instance/id_rsa");
        self::definePath("GIT_KEY_FILE", (rtrim(env("HOME"), '/') ?: "/var/www") . "/.ssh/id_rsa");

        $runCount++;
    }

    public static function defineFromEnv(string $name, $defaultValue, string $envName = "")
    {
        $envName = $envName ?: $name;
        $value = env($envName) ?: $defaultValue;
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
