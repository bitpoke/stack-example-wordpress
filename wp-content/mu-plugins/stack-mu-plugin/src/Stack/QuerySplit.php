<?php

namespace Stack;

/**
 * This plugin is used together with ProxySQL and has the role of annotating queries as write in
 * special situations.
 *
 * The plugins hooks on Wordpress "query" hook and process the query and if necessary the read
 * queries are send to master.
 */

/**
 * Class QuerySplit
 */
class QuerySplit
{

    const MASTER_LABEL = "/*route:master*/";
    const SLAVE_LABEL = "/*route:slave*/";

    private $srtm = false;

    public function __construct()
    {
        $this->setup();
        $this->addFilters();
    }

    public function __destruct()
    {
        $this->removeFilters();
    }

    private function addFilters()
    {
        add_filter('query', [$this, 'processQuery']);
    }

    private function removeFilters()
    {
        remove_filter('query', [$this, 'processQuery']);
    }

    public function processQuery(string $query)
    {
        // don't need to analize the query if it's already write
        // NOTE: this can be optimized to send reads on master per table
        if (!$this->srtm) {
            $this->analizeQuery($query);
        }

        // set the write label on the query
        if ($this->srtm === true) {
            $query = $query . " " . self::MASTER_LABEL;
        }

        return $query;
    }

    private function setup()
    {
        // Send non-idempotent requests to master
        if (! in_array($_SERVER['REQUEST_METHOD'], array( 'GET', 'HEAD' ))) {
            $this->sendReadsToMaster();
            return;
        }

        // Cron jobs always go to master so that they can run SQL queries more easily
        if (isset($_GET['doing_wp_cron'])) {
            $this->sendReadsToMaster();
            return;
        }

        // Admin requests always go to master
        if ('/wp/wp-admin/' == substr($_SERVER['REQUEST_URI'], 0, 13)) {
            $this->sendReadsToMaster();
            return;
        }

        if ('/wp/wp-login.php' == substr($_SERVER['REQUEST_URI'], 0, 16)) {
            $this->sendReadsToMaster();
            return;
        }

        if ('/wp/wp-register.php' == substr($_SERVER['REQUEST_URI'], 0, 19)) {
            $this->sendReadsToMaster();
            return;
        }

        // XML-RPC go to master since they usually are modifying content
        if ('/wp/xmlrpc.php' == substr($_SERVER['REQUEST_URI'], 0, 14)) {
            $this->sendReadsToMaster();
            return;
        }
    }

    public function sendReadsToMaster()
    {
        $this->srtm = true;
    }

    private function analizeQuery($query)
    {
        if ($this->isWriteQuery($query)) {
            $this->sendReadsToMaster();
        }
    }

    private function isWriteQuery($q)
    {
        // Quick and dirty: only SELECT statements are considered read-only.
        $q = ltrim($q, "\r\n\t (");

        return ! preg_match('/^(?:SELECT(?!.*FOR UPDATE)|SHOW|DESCRIBE|DESC|EXPLAIN)\s/i', $q);
    }
}
