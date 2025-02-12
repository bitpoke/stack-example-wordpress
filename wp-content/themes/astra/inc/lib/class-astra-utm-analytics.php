<?php
/**
 * Init
 *
 * Loads latest UTM Analytics library in environment.
 *
 * @since 4.8.11
 * @package UTM Analytics
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Astra_Utm_Analytics' ) ) {

	/**
	 * Admin
	 */
	class Astra_Utm_Analytics {
		/**
		 * Instance
		 *
		 * @since 1.0.0
		 * @var (Object) Astra_Nps_Survey
		 */
		private static $instance = null;

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		private function __construct() {
			$this->version_check();
			add_action( 'init', [ $this, 'load' ], 999 );
		}

		/**
		 * Get Instance
		 *
		 * @since 1.0.0
		 *
		 * @return object Class object.
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Version Check
		 *
		 * @return void
		 */
		public function version_check(): void {

			$file = realpath( dirname( __FILE__ ) . '/utm-analytics/version.json' );

			// Is file exist?
			if ( is_file( $file ) ) {
				// @codingStandardsIgnoreStart
				$file_data = json_decode( file_get_contents( $file ), true );
				// @codingStandardsIgnoreEnd
				global $utm_analytics_version, $utm_analytics_init;
				$path = realpath( dirname( __FILE__ ) . '/utm-analytics/bsf-utm-analytics.php' );
				$version = isset( $file_data['bsf-utm-analytics'] ) ? $file_data['bsf-utm-analytics'] : 0;

				if ( null === $utm_analytics_version ) {
					$utm_analytics_version = '0.0.1';
				}

				// Compare versions.
				if ( version_compare( $version, $utm_analytics_version, '>=' ) ) {
					$utm_analytics_version = $version;
					$utm_analytics_init    = $path;
				}
			}
		}

		/**
		 * Load latest plugin
		 *
		 * @return void
		 */
		public function load(): void {
			global $utm_analytics_version, $utm_analytics_init;
			if ( is_file( realpath( $utm_analytics_init ) ) ) {
				include_once realpath( $utm_analytics_init );
			}
		}
	}

	/**
	 * Kicking this off by calling 'get_instance()' method
	 */
	Astra_Utm_Analytics::get_instance();
}
