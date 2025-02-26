<?php
/**
 * WooCommerce Settings.
 */

declare( strict_types = 1 );

namespace Automattic\WooCommerce\Admin\Features\Settings;

use Automattic\WooCommerce\Internal\Admin\WCAdminAssets;

/**
 * Contains backend logic for the Settings feature.
 */
class Init {
	/**
	 * Class instance.
	 *
	 * @var Init instance
	 */
	protected static $instance = null;

	/**
	 * Get class instance.
	 */
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Hook into WooCommerce.
	 */
	public function __construct() {
		if ( ! is_admin() ) {
			return;
		}

		add_filter( 'woocommerce_admin_shared_settings', array( __CLASS__, 'add_component_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_settings_editor_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_settings_editor_styles' ) );
	}

	/**
	 * Check if the current screen is the WooCommerce settings page.
	 *
	 * @return bool
	 */
	public function is_settings_page() {
		$screen = get_current_screen();
		return $screen && 'woocommerce_page_wc-settings' === $screen->id;
	}

	/**
	 * Enqueue styles for the settings editor.
	 */
	public function enqueue_settings_editor_styles() {
		if ( ! self::get_instance()->is_settings_page() ) {
			return;
		}

		$style_name            = 'wc-admin-edit-settings';
		$style_path_name       = 'settings';
		$style_assets_filename = WCAdminAssets::get_script_asset_filename( $style_path_name, 'style' );
		$style_assets          = require WC_ADMIN_ABSPATH . WC_ADMIN_DIST_JS_FOLDER . $style_path_name . '/' . $style_assets_filename;

		// Settings Editor styles.
		wp_register_style(
			$style_name,
			WCAdminAssets::get_url( $style_path_name . '/style', 'css' ),
			isset( $style_assets['dependencies'] ) ? $style_assets['dependencies'] : array(),
			WCAdminAssets::get_file_version( 'css', $style_assets['version'] ),
		);

		wp_enqueue_style( $style_name );

		// Global presets styles.
		wp_register_style( 'wc-global-presets', false ); // phpcs:ignore
		wp_add_inline_style( 'wc-global-presets', wp_get_global_stylesheet( array( 'presets' ) ) );
		wp_enqueue_style( 'wc-global-presets' );

		// Gutenberg posts editor styles.
		if ( function_exists( 'gutenberg_url' ) ) {
			// phpcs:disable WordPress.WP.EnqueuedResourceParameters.MissingVersion
			wp_register_style(
				'wp-gutenberg-posts-dashboard',
				gutenberg_url( 'build/edit-site/posts.css', __FILE__ ),
				array( 'wp-components' ),
			);
			// phpcs:enable WordPress.WP.EnqueuedResourceParameters.MissingVersion
			wp_enqueue_style( 'wp-gutenberg-posts-dashboard' );

			// phpcs:disable WordPress.WP.EnqueuedResourceParameters.MissingVersion
			wp_register_style(
				'wp-gutenberg-edit-site',
				gutenberg_url( 'build/edit-site/style.css', __FILE__ ),
				array( 'wp-components' ),
			);
			// phpcs:enable WordPress.WP.EnqueuedResourceParameters.MissingVersion
			wp_enqueue_style( 'wp-gutenberg-edit-site' );
		}
	}

	/**
	 * Enqueue scripts for the settings editor.
	 */
	public function enqueue_settings_editor_scripts() {
		if ( ! self::get_instance()->is_settings_page() ) {
			return;
		}

		// Make sure the Settings Editor package is loaded.
		wp_enqueue_script( 'wc-settings-editor' );
		wp_enqueue_style( 'wc-settings-editor' );

		$script_name            = 'wc-admin-edit-settings';
		$script_path_name       = 'settings';
		$script_assets_filename = WCAdminAssets::get_script_asset_filename( $script_path_name, 'index' );
		$script_assets          = require WC_ADMIN_ABSPATH . WC_ADMIN_DIST_JS_FOLDER . $script_path_name . '/' . $script_assets_filename;

		wp_enqueue_script(
			$script_name,
			WCAdminAssets::get_url( $script_path_name . '/index', 'js' ),
			array_merge( array( 'wp-edit-site' ), $script_assets['dependencies'] ),
			WCAdminAssets::get_file_version( 'js', $script_assets['version'] ),
			true
		);

		wp_set_script_translations( 'wc-admin-' . $script_name, 'woocommerce' );
	}

	/**
	 * Add the necessary data to initially load the WooCommerce Settings pages.
	 *
	 * @param array $settings Array of component settings.
	 * @return array Array of component settings.
	 */
	public static function add_component_settings( $settings ) {
		if ( ! self::get_instance()->is_settings_page() ) {
			return $settings;
		}

		global $wp_scripts;

		// Set the scripts that all settings pages should have.
		$ignored_settings_scripts                = array(
			'wc-admin-app',
			'woocommerce_admin',
			'wc-settings-editor',
			'wc-admin-edit-settings',
			'woo-tracks',
			'woocommerce-admin-test-helper',
			'woocommerce-beta-tester-live-branches',
			'WCPAY_DASH_APP',
		);
		$default_scripts_handles                 = array_diff(
			$wp_scripts->queue,
			$ignored_settings_scripts,
		);
		$settings['settingsScripts']['_default'] = self::get_script_urls( $default_scripts_handles );

		// Add the settings data to the settings array.
		$setting_pages = \WC_Admin_Settings::get_settings_pages();
		$pages         = array();
		foreach ( $setting_pages as $setting_page ) {
			$scripts_before_adding_settings = $wp_scripts->queue;
			$pages                          = $setting_page->add_settings_page_data( $pages );

			$settings_scripts_handles                               = array_diff( $wp_scripts->queue, $scripts_before_adding_settings );
			$settings['settingsScripts'][ $setting_page->get_id() ] = self::get_script_urls( $settings_scripts_handles );
		}

		$transformer              = new Transformer();
		$settings['settingsData'] = $transformer->transform( $pages );

		return $settings;
	}

	/**
	 * Retrieve the script URLs from the provided script handles.
	 * This will also filter out scripts from WordPress core since they only need to be loaded once.
	 *
	 * @param array $script_handles Array of script handles.
	 * @return array Array of script URLs.
	 */
	private static function get_script_urls( $script_handles ) {
		global $wp_scripts;
		$script_urls = array();
		foreach ( $script_handles as $script ) {
			$registered_script = $wp_scripts->registered[ $script ];
			if ( ! isset( $registered_script->src ) ) {
				continue;
			}

			// Skip scripts from WordPress core since they only need to be loaded once.
			if ( strpos( $registered_script->src, '/' . WPINC . '/js' ) === 0 || strpos( $registered_script->src, '/wp-admin/js' ) === 0 ) {
				continue;
			}

			if ( strpos( $registered_script->src, '/' ) === 0 ) {
				$script_urls[] = home_url( $registered_script->src );
			} else {
				$script_urls[] = $registered_script->src;
			}
		}
		return $script_urls;
	}
}
