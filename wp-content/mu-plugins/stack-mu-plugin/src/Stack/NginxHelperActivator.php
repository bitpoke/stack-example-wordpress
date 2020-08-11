<?php
namespace Stack;

class NginxHelperActivator
{
    public function __construct()
    {
        if (STACK_PAGE_CACHE_AUTOMATIC_PLUGIN_ON_OFF === true && STACK_PAGE_CACHE_ENABLED === true) {
            $nginxHelperShouldBeActive = STACK_PAGE_CACHE_BACKEND == "redis" || STACK_PAGE_CACHE_BACKEND == "memcached";

            if ($nginxHelperShouldBeActive) {
                require_once(__DIR__.'/NginxHelper/nginx-helper.php');
            }
        }
    }
}
