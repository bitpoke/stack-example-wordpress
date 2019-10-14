<?php

namespace Stack;

/**
 * This code is a modified version of https://github.com/roots/multisite-url-fixer.
 * It is included here for convenience for running multi-site setups on Presslabs Stack.
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
