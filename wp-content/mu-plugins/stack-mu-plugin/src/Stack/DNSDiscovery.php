<?php
namespace Stack;

class DNSDiscovery
{
    public static $cachePrefix = "Stack\DNSDiscovery";
    public static $cacheDefaultTTL = 10;

    public static function resolve(string $host)
    {
        if (filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false) {
            return $host;
        }
        $hosts = array();
        foreach (dns_get_record($host, DNS_A) as $_ => $record) {
            $hosts[] = $record["ip"];
        }
        if (count($hosts) > 0) {
            shuffle($hosts);
            return $hosts[0];
        }
        return "";
    }

    public static function cachedDiscoverSRV(string $host, string $service = "", string $protocol = "tcp", $ttl = -1)
    {
        if (php_sapi_name() == 'cli') {
            return DNSDiscovery::discoverSRV($host, $service, $protocol);
        }

        $_host = $host;
        if ($service) {
            $_host = sprintf("_%s._%s.%s", $service, $protocol, $host);
        }
        $key = sprintf("%s:%s", DNSDiscovery::$cachePrefix, $_host);

        if ($ttl == -1) {
            $ttl = DNSDiscovery::$cacheDefaultTTL;
        }

        return apcu_entry($key, function ($key) {
            list($_, $host) = explode(':', $key, 2);
            return DNSDiscovery::discoverSRV($host);
        }, $ttl);
    }

    public static function cachedDiscover(string $host, $ttl = -1)
    {
        if (php_sapi_name() == 'cli') {
            return DNSDiscovery::discover($host);
        }

        $key = sprintf("%s:%s", DNSDiscovery::$cachePrefix, $host);

        if ($ttl == -1) {
            $ttl = DNSDiscovery::$cacheDefaultTTL;
        }

        return apcu_entry($key, function ($key) {
            list($_, $host) = explode(':', $key, 2);
            return DNSDiscovery::discover($host);
        }, $ttl);
    }

    public static function discoverSRV(string $host, string $service = "", string $protocol = "tcp")
    {
        if ($service) {
            $host = sprintf("_%s._%s.%s", $service, $protocol, $host);
        }
        $hosts = array();
        foreach (dns_get_record($host, DNS_SRV) as $_ => $record) {
            $target = DNSDiscovery::resolve($record["target"]);
            if ($target) {
                $hosts[] = array("priority" => $record["pri"], "host" => $target, "port" => $record["port"]);
            }
        }
        return $hosts;
    }

    public static function discover(string $host)
    {
        $hosts = array();
        foreach (dns_get_record($host, DNS_A) as $_ => $record) {
            $hosts[] = array("priority" => 10, "host" => $record["ip"], "port" => 0);
        }
        return $hosts;
    }
}
