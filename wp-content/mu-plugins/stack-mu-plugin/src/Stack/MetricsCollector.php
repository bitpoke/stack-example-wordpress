<?php
namespace Stack;

use WP_REST_Response;

class MetricsCollector
{
    private $registry;
    private $metrics;
    private $wpdbStats;

    public function __construct()
    {
        if (!defined('STACK_METRICS_ENABLED') || !STACK_METRICS_ENABLED) {
            return;
        }

        if (defined('WP_CLI') && WP_CLI) {
            return;
        }

        $this->metrics = new MetricsRegistry(
            array(
                'wp.requests' => array(
                    'counter',
                    'Number of requests',
                    ['request_type']
                ),
                'wp.page_generation_time' => array(
                    'histogram',
                    'Page generation time, in seconds',
                    ['request_type']
                ),
                'wp.peak_memory' => array(
                    'histogram',
                    'Peak memory per request, in bytes',
                    ['request_type']
                ),
                'wpdb.query_time' => array(
                    'histogram',
                    'Total MySQL query time per request, in seconds',
                    ['request_type']
                ),
                'wpdb.num_queries' => array(
                    'histogram',
                    'Total number of MySQL queries per request',
                    ['request_type']
                ),
                'wpdb.num_slow_queries' => array(
                    'histogram',
                    'Number of MySQL slow queries per request',
                    ['request_type']
                ),
                'wpdb.slow_query_treshold' => array(
                    'gauge',
                    'The treshold for counting slow queries, in seconds',
                    []
                ),
                'woocommerce.orders' => array(
                    'counter',
                    'Number of completed WooCommerce orders',
                    []
                ),
                'woocommerce.checkouts' => array(
                    'counter',
                    'Number of started WooCommerce checkouts',
                    []
                )
            )
        );

        $this->registerHooks();
        $this->initWpdbStats();
    }

    public function initWpdbStats()
    {
        $this->wpdbStats['slow_query_treshold'] = defined('SLOW_QUERY_THRESHOLD') ? SLOW_QUERY_THRESHOLD : 2000;
        $this->wpdbStats['query_time'] = 0;
        $this->wpdbStats['num_queries'] = 0;
        $this->wpdbStats['num_slow_queries'] = 0;
    }

    public function collectRequestMetrics()
    {
        $requestType = $this::getRequestType();
        $requestTime = $this::getRequestTime();
        $peakMemory  = memory_get_peak_usage();

        $this->metrics->getCounter('wp.requests')->incBy(
            1,
            [$requestType]
        );
        $this->metrics->getHistogram('wp.peak_memory')->observe(
            $peakMemory,
            [$requestType]
        );
        $this->metrics->getHistogram('wp.page_generation_time')->observe(
            $requestTime,
            [$requestType]
        );

        if ($this::canCollectWpdbMetrics()) {
            $this->metrics->getHistogram('wpdb.query_time')->observe(
                $this->wpdbStats['query_time'],
                [$requestType]
            );
            $this->metrics->getHistogram('wpdb.num_queries')->observe(
                $this->wpdbStats['num_queries'],
                [$requestType]
            );
            $this->metrics->getHistogram('wpdb.num_slow_queries')->observe(
                $this->wpdbStats['num_slow_queries'],
                [$requestType]
            );
            $this->metrics->getGauge('wpdb.slow_query_treshold')->set(
                $this->wpdbStats['slow_query_treshold']
            );
        }
    }

    public function registerEndpoint()
    {
        $namespace = 'stack/v' . STACK_REST_API_VERSION;
        $base      = 'metrics';

        register_rest_route($namespace, '/' . $base, array(
            array(
                'methods'  => \WP_REST_Server::READABLE,
                'callback' => [$this, 'render'],
                'permission_callback' => '__return_true',
            )
        ));

        add_filter('rest_pre_echo_response', [$this, 'preEchoResponse'], 9999, 3);
    }

    public function preEchoResponse($result, $server, $request)
    {
        if ($request->get_route() == "/stack/v" . STACK_REST_API_VERSION . "/metrics" && is_string($result)) {
            echo $result;
            return null;
        }

        return $result;
    }

    public function render()
    {
        $response = new WP_REST_Response($this->metrics->render());

        $response->header('Content-type', \Prometheus\RenderTextFormat::MIME_TYPE);
        $response->header('Cache-Control', 'no-cache,max-age=0');

        return $response;
    }

    public function collectWpdbStats($queryData, $query, $queryTime, $queryCallstack, $queryStart)
    {
        if (!$this::canCollectWpdbMetrics()) {
            return;
        }

        $this->wpdbStats['num_queries'] += 1;
        $this->wpdbStats['query_time'] += $queryTime;

        if ($queryTime > $this->wpdbStats['slow_query_treshold']) {
            $this->wpdbStats['num_slow_queries'] += 1;
        }

        return $queryData;
    }

    public function trackWoocomerceOrder($orderId, $oldStatus, $newStatus)
    {
        if ($newStatus == 'completed') {
            $this->metrics['woocommerce.orders']->incBy(1);
        }
    }

    public function trackWoocomerceCheckout()
    {
        $this->metrics['woocommerce.checkouts']->incBy(1);
    }

    private function registerHooks()
    {
        add_action('rest_api_init', [$this, 'registerEndpoint']);
        add_action('shutdown', [$this, 'collectRequestMetrics']);

        if ($this::canCollectWpdbMetrics()) {
            add_filter('log_query_custom_data', [$this, 'collectWpdbStats'], 10, 5);
        }

        if ($this::canCollectWoocommerceMetrics()) {
            add_action('woocommerce_checkout_billing', [$this, 'trackWoocomerceCheckout']);
            add_action('woocommerce_order_status_changed', [$this, 'trackWoocomerceOrder'], 10, 3);
        }
    }

    private function isWoocommerce()
    {
        return function_exists('is_woocommerce') && is_woocommerce();
    }

    private function canCollectWpdbMetrics()
    {
        return defined('SAVEQUERIES') && SAVEQUERIES;
    }

    private function canCollectWoocommerceMetrics()
    {
        return $this::isWoocommerce();
    }

    private function getRequestType()
    {
        if (defined('DOING_CRON') && DOING_CRON) {
            return 'cron';
        }
        if (defined('REST_REQUEST') && REST_REQUEST) {
            return 'api';
        }
        if (defined('DOING_AJAX') && DOING_AJAX) {
            return 'admin-ajax';
        }
        if ($this::isWoocommerce()) {
            if (function_exists('is_shop') && is_shop()) {
                return 'shop';
            }
            if (function_exists('is_product_category') && is_product_category()) {
                return 'product_category';
            }
            if (function_exists('is_product') && is_product()) {
                return 'product';
            }
            if (function_exists('is_cart') && is_cart()) {
                return 'checkout';
            }
            if (function_exists('is_checkout') && is_checkout()) {
                return 'checkout';
            }
            if (function_exists('is_account_page') && is_account_page()) {
                return 'is_account_page';
            }
        }
        if (is_admin()) {
            return 'admin';
        }
        if (is_search()) {
            return 'search';
        }
        if (is_front_page() || is_home()) {
            return 'frontpage';
        }
        if (is_singular()) {
            return 'singular';
        }
        if (is_archive()) {
            return 'archive';
        }

        return 'other';
    }

    private function getRequestTime()
    {
        global $timestart, $timeend;
        $precision = 12;
        $timeend   = microtime(true);
        $timetotal = $timeend - $timestart;
        return number_format($timetotal, $precision);
    }
}
