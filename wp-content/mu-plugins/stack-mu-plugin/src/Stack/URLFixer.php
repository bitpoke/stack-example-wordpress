<?php

namespace Stack;

/**
 * This code is a modified version of https://github.com/roots/multisite-url-fixer.
 * It is included here for convenience for running multi-site setups on Bitpoke Stack.
 */

/**
 * Class URLFixer
 * @package Roots\Bedrock
 * @author Roots
 * @link https://roots.io/
 */
class URLFixer
{
    private $coreDirectory = '/wp';

    public function __construct()
    {
        if (!is_multisite()) {
            return;
        }

        if (defined('WP_CORE_DIRECTORY')) {
            $this->coreDirectory = '/' . trim(WP_CORE_DIRECTORY, '/');
        }

        $this->addFilters();
    }

    /**
     * Add filters to verify / fix URLs.
     */
    public function addFilters()
    {
        add_filter('option_home', [$this, 'fixHomeURL']);
        add_filter('option_siteurl', [$this, 'fixSiteURL']);
        add_filter('network_site_url', [$this, 'fixNetworkSiteURL'], 10, 3);
        if (is_subdomain_install()) {
                add_filter('content_url', [$this, 'fixContentURL']);
                add_filter('plugins_url', [$this, 'fixContentURL']);
                add_filter('upload_dir', [$this, 'fixUploadsURL']);
        }
    }

    private function hasSuffix(string $s, string $suffix)
    {
        return substr($s, -1 * strlen($suffix)) === $suffix;
    }

    private function removeSuffix(string $s, string $suffix)
    {
        if ($this->hasSuffix($s, $suffix)) {
            $s = substr($s, 0, -1 * strlen($suffix));
        }
        return $s;
    }

    private function hasPrefix(string $s, string $prefix)
    {
        return (substr($s, 0, strlen($prefix)) === $prefix);
    }

    private function removePrefix(string $s, string $prefix)
    {
        if ($this->hasPrefix($s, $prefix)) {
            $s = substr($s, strlen($prefix));
        }
        return $s;
    }

    /**
     * Ensure that content URLs point to the site's domain
     *
     * We need to do this because we define WP_CONTENT_URL to be the main site's one,
     * in order we have a separate wordpress and wp-content directories. For multisites
     * on subdomains, having the content server on the main domain may cause cross-origin
     * issues for various requests (eg. fonts).
     *
     * @param string $url the original content URL
     * @return string the rewritten URL
     */
    public function fixContentURL($url)
    {
        $contentDir = rtrim(defined('CONTENT_DIR') ? CONTENT_DIR : '/wp-content', '/');
        $contentURL = rtrim(set_url_scheme(WP_CONTENT_URL), '/');
        return str_replace($contentURL, home_url($contentDir), $url);
    }

    /**
     * Ensure that uploads URLs point to the site's domain
     *
     * @param array $dir the wp_uploads_dir array
     * @return array the array with filtered URLs
     */
    public function fixUploadsURL($dir)
    {
        $dir["url"] = $this->fixContentURL($dir["url"]);
        $dir["baseurl"] = $this->fixContentURL($dir["baseurl"]);
        return $dir;
    }

    /**
     * Ensure that home URL does not contain the /wp subdirectory.
     *
     * @param string $value the unchecked home URL
     * @return string the verified home URL
     */
    public function fixHomeURL($value)
    {
        return $this->removeSuffix($value, $this->coreDirectory);
    }

    /**
     * Ensure that site URL contains the /wp subdirectory.
     *
     * @param string $url the unchecked site URL
     * @return string the verified site URL
     */
    public function fixSiteURL($url)
    {
        if (!$this->hasSuffix($url, $this->coreDirectory) && (is_main_site() || is_subdomain_install())) {
            $url .= $this->coreDirectory;
        }
        return $url;
    }

    /**
     * Ensure that the network site URL contains the /wp subdirectory.
     *
     * @param string $url    the unchecked network site URL with path appended
     * @param string $path   the path for the URL
     * @param string $scheme the URL scheme
     * @return string the verified network site URL
     */
    public function fixNetworkSiteURL($url, $path, $scheme)
    {
        $path = ltrim($path, '/');
        $url = substr($url, 0, strlen($url) - strlen($path));
        $coreDirectory = trim($this->coreDirectory, '/') . '/'; // wp core directory w/ trailing slash

        if (!$this->hasSuffix($url, $coreDirectory)) {
            $url .= $coreDirectory;
        }

        return $url . $path;
    }
}
