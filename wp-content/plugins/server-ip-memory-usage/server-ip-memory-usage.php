<?php
/*
Plugin Name: Server IP & Memory Usage Display
Plugin URI: http://apasionados.es/#utm_source=wpadmin&utm_medium=plugin&utm_campaign=server-ip-memory-usage-plugin
Description: Show the memory limit, current memory usage and IP address in the admin footer.
Version: 2.2.0
Author: Apasionados, Apasionados del Marketing
Author URI: http://apasionados.es
Text Domain: server-ip-memory-usage
Domain Path: /lang
*/

if ( is_admin() ) {

	class IP_Address_Memory_Usage {

		private $memory = array();

		public function __construct() {
			// Cargar traducciones a partir de init (o plugins_loaded)
			add_action( 'init', array( $this, 'load_textdomain' ) );

			add_action( 'init', array( $this, 'check_limit' ) );
			add_filter( 'admin_footer_text', array( $this, 'add_footer' ) );
		}

		public function load_textdomain() {
			load_plugin_textdomain(
				'server-ip-memory-usage',
				false,
				dirname( plugin_basename( __FILE__ ) ) . '/lang/'
			);
		}

		public function check_limit() {
			$this->memory['limit'] = (int) ini_get( 'memory_limit' );
		}

		private function check_memory_usage() {
			$this->memory['usage'] = function_exists( 'memory_get_peak_usage' )
				? round( memory_get_peak_usage( true ) / 1024 / 1024, 2 )
				: 0;

			if ( ! empty( $this->memory['usage'] ) && ! empty( $this->memory['limit'] ) ) {
				$this->memory['percent'] = round( $this->memory['usage'] / $this->memory['limit'] * 100, 0 );
				$this->memory['color']   = 'font-weight:normal;';

				if ( $this->memory['percent'] > 75 ) {
					$this->memory['color'] = 'font-weight:bold;color:#E66F00';
				}
				if ( $this->memory['percent'] > 90 ) {
					$this->memory['color'] = 'font-weight:bold;color:red';
				}
			}
		}

		private function format_wp_limit( $size ) {
			$value  = substr( $size, -1 );
			$return = substr( $size, 0, -1 );

			$return = (int) $return;

			switch ( strtoupper( $value ) ) {
				case 'P':
					$return *= 1024;
				case 'T':
					$return *= 1024;
				case 'G':
					$return *= 1024;
				case 'M':
					$return *= 1024;
				case 'K':
					$return *= 1024;
			}

			return $return;
		}

		private function check_wp_limit() {
			$memory = $this->format_wp_limit( WP_MEMORY_LIMIT );
			$memory = size_format( $memory );

			return ( $memory ) ? $memory : __( 'N/A', 'server-ip-memory-usage' );
		}

		public function add_footer( $content ) {
			$this->check_memory_usage();

			$server_ip_address = ! empty( $_SERVER['SERVER_ADDR'] ) ? $_SERVER['SERVER_ADDR'] : '';
			if ( $server_ip_address === '' ) {
				$server_ip_address = ! empty( $_SERVER['LOCAL_ADDR'] ) ? $_SERVER['LOCAL_ADDR'] : '';
			}

			$content .= ' | ' . __( 'Memory', 'server-ip-memory-usage' ) . ': ' . $this->memory['usage'] . ' ' .
				__( 'of', 'server-ip-memory-usage' ) . ' ' . $this->memory['limit'] .
				' MB (<span style="' . $this->memory['color'] . '">' . $this->memory['percent'] .
				'%</span>) | ' . __( 'WP LIMIT', 'server-ip-memory-usage' ) . ': ' . $this->check_wp_limit() .
				' | IP ' . $server_ip_address . ' (' . gethostname() . ') | PHP ' . PHP_VERSION .
				' @' . ( PHP_INT_SIZE * 8 ) . 'BitOS';

			return $content;
		}
	}

	add_action( 'plugins_loaded', function () {
		new IP_Address_Memory_Usage();
	} );
}

/**
 * Check on plugin activation
 */
function server_ip_memory_usage_activation() {
	if ( version_compare( PHP_VERSION, '5.3', '<' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );

		$plugin_data    = get_plugin_data( __FILE__ );
		$plugin_version = $plugin_data['Version'];
		$plugin_name    = $plugin_data['Name'];

		wp_die(
			'<h1>Could not activate plugin: PHP version error</h1>' .
			'<h2>PLUGIN: <i>' . esc_html( $plugin_name . ' ' . $plugin_version ) . '</i></h2>' .
			'<p><strong>You are using PHP version ' . esc_html( PHP_VERSION ) . '</strong>. ' .
			'This plugin has been tested with PHP versions 5.3 and greater.</p>' .
			'<p>WordPress itself recommends using PHP version 7 or greater. Please upgrade your PHP version or contact your Server administrator.</p>',
			'Could not activate plugin: PHP version error',
			array( 'back_link' => true )
		);
	}
}
register_activation_hook( __FILE__, 'server_ip_memory_usage_activation' );
