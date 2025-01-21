<?php
declare( strict_types = 1 );

// @codingStandardsIgnoreLine.
/**
 * WooCommerce Checkout Settings
 *
 * @package WooCommerce\Admin
 */

use Automattic\WooCommerce\Internal\Admin\Loader;

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'WC_Settings_Payment_Gateways_React', false ) ) {
	return new WC_Settings_Payment_Gateways_React();
}

/**
 * WC_Settings_Payment_Gateways_React.
 */
class WC_Settings_Payment_Gateways_React extends WC_Settings_Page {

	/**
	 * Get the whitelist of sections to render using React.
	 *
	 * @return array List of section identifiers.
	 */
	private function get_reactify_render_sections() {
		// Add 'woocommerce_payments' when WooPayments reactified settings page is done.
		$sections = array(
			'offline',
			'main',
		);

		/**
		 * Filters the list of payment settings sections to be rendered using React.
		 *
		 * @since 9.3.0
		 *
		 * @param array $sections List of section identifiers.
		 */
		return apply_filters( 'experimental_woocommerce_admin_payment_reactify_render_sections', $sections );
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'checkout';
		$this->label = esc_html_x( 'Payments', 'Settings tab label', 'woocommerce' );

		// Add filters and actions.
		add_action( 'admin_head', array( $this, 'hide_help_tabs' ) );
		// Hook in as late as possible - `in_admin_header` is the last action before the `admin_notices` action is fired.
		// It is too risky to hook into `admin_notices` with a low priority because the callbacks might be cached.
		add_action( 'in_admin_header', array( $this, 'suppress_admin_notices' ), PHP_INT_MAX );

		parent::__construct();
	}

	/**
	 * Output the settings.
	 */
	public function output() {
		//phpcs:disable WordPress.Security.NonceVerification.Recommended
		global $current_section;

		// We don't want to output anything from the action for now. So we buffer it and discard it.
		ob_start();
		/**
		 * Fires before the payment gateways settings fields are rendered.
		 *
		 * @since 1.5.7
		 */
		do_action( 'woocommerce_admin_field_payment_gateways' );
		ob_end_clean();

		if ( $this->should_render_react_section( $current_section ) ) {
			$this->render_react_section( $current_section );
		} elseif ( $current_section ) {
			// Load gateways so we can show any global options they may have.
			$payment_gateways = WC()->payment_gateways()->payment_gateways;
			$this->render_classic_gateway_settings_page( $payment_gateways, $current_section );
		} else {
			$this->render_react_section( 'main' );
		}

		parent::output();
		//phpcs:enable
	}

	/**
	 * Check if the given section should be rendered using React.
	 *
	 * @param string $section The section to check.
	 * @return bool Whether the section should be rendered using React.
	 */
	private function should_render_react_section( $section ) {
		return in_array( $section, $this->get_reactify_render_sections(), true );
	}

	/**
	 * Render the React section.
	 *
	 * @param string $section The section to render.
	 */
	private function render_react_section( string $section ) {
		global $hide_save_button;
		$hide_save_button = true;
		echo '<div id="experimental_wc_settings_payments_' . esc_attr( $section ) . '"></div>';
	}

	/**
	 * Render the classic gateway settings page.
	 *
	 * @param array  $payment_gateways The payment gateways.
	 * @param string $current_section The current section.
	 */
	private function render_classic_gateway_settings_page( $payment_gateways, $current_section ) {
		foreach ( $payment_gateways as $gateway ) {
			if ( in_array( $current_section, array( $gateway->id, sanitize_title( get_class( $gateway ) ) ), true ) ) {
				if ( isset( $_GET['toggle_enabled'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
					$enabled = $gateway->get_option( 'enabled' );

					if ( $enabled ) {
						$gateway->settings['enabled'] = wc_string_to_bool( $enabled ) ? 'no' : 'yes';
					}
				}
				$this->run_gateway_admin_options( $gateway );
				break;
			}
		}
	}

	/**
	 * Run the 'admin_options' method on a given gateway.
	 * This method exists to help with unit testing.
	 *
	 * @param object $gateway The gateway object to run the method on.
	 */
	protected function run_gateway_admin_options( $gateway ) {
		$gateway->admin_options();
	}

	/**
	 * Don't show any section links.
	 *
	 * @return array
	 */
	public function get_sections() {
		return array();
	}

	/**
	 * Save settings.
	 */
	public function save() {
		global $current_section;

		$wc_payment_gateways = WC_Payment_Gateways::instance();

		$this->save_settings_for_current_section();

		if ( ! $current_section ) {
			// If section is empty, we're on the main settings page. This makes sure 'gateway ordering' is saved.
			$wc_payment_gateways->process_admin_options();
			$wc_payment_gateways->init();
		} else {
			// There is a section - this may be a gateway or custom section.
			foreach ( $wc_payment_gateways->payment_gateways() as $gateway ) {
				if ( in_array( $current_section, array( $gateway->id, sanitize_title( get_class( $gateway ) ) ), true ) ) {
					/**
					 * Fires update actions for payment gateways.
					 *
					 * @since 3.4.0
					 *
					 * @param int $gateway->id Gateway ID.
					 */
					do_action( 'woocommerce_update_options_payment_gateways_' . $gateway->id );
					$wc_payment_gateways->init();
				}
			}

			$this->do_update_options_action();
		}
	}

	/**
	 * Hide the help tabs.
	 */
	public function hide_help_tabs() {
		$screen = get_current_screen();

		if ( ! $screen instanceof WP_Screen || 'woocommerce_page_wc-settings' !== $screen->id ) {
			return;
		}

		global $current_tab;
		if ( 'checkout' !== $current_tab ) {
			return;
		}

		$screen->remove_help_tabs();
	}

	/**
	 * Suppress WP admin notices on the WooCommerce Payments settings page.
	 */
	public function suppress_admin_notices() {
		global $wp_filter;

		$screen = get_current_screen();

		if ( ! $screen instanceof WP_Screen || 'woocommerce_page_wc-settings' !== $screen->id ) {
			return;
		}

		global $current_tab;
		if ( 'checkout' !== $current_tab ) {
			return;
		}

		// Generic admin notices are definitely not needed.
		remove_all_actions( 'all_admin_notices' );

		// WooCommerce uses the 'admin_notices' hook for its own notices.
		// We will only allow WooCommerce core notices to be displayed.
		$wp_admin_notices_hook = $wp_filter['admin_notices'] ?? null;
		if ( ! $wp_admin_notices_hook || ! $wp_admin_notices_hook->has_filters() ) {
			// Nothing to do if there are no actions hooked into `admin_notices`.
			return;
		}

		$wc_admin_notices = WC_Admin_Notices::get_notices();
		if ( empty( $wc_admin_notices ) ) {
			// If there are no WooCommerce core notices, we can remove all actions hooked into `admin_notices`.
			remove_all_actions( 'admin_notices' );
			return;
		}

		// Go through the callbacks hooked into `admin_notices` and
		// remove any that are NOT from the WooCommerce core (i.e. from the `WC_Admin_Notices` class).
		foreach ( $wp_admin_notices_hook->callbacks as $priority => $callbacks ) {
			if ( ! is_array( $callbacks ) ) {
				continue;
			}

			foreach ( $callbacks as $callback ) {
				// Ignore malformed callbacks.
				if ( ! is_array( $callback ) ) {
					continue;
				}
				// WooCommerce doesn't use closures to handle notices.
				// WooCommerce core notices are handled by `WC_Admin_Notices` class methods.
				// Remove plain functions or closures.
				if ( ! is_array( $callback['function'] ) ) {
					remove_action( 'admin_notices', $callback['function'], $priority );
					continue;
				}

				$class_or_object = $callback['function'][0] ?? null;
				// We need to allow Automattic\WooCommerce\Internal\Admin\Loader methods callbacks
				// because they are used to wrap notices.
				// @see Automattic\WooCommerce\Internal\Admin\Loader::inject_before_notices().
				// @see Automattic\WooCommerce\Internal\Admin\Loader::inject_after_notices().
				if (
					(
						// We have a class name.
						is_string( $class_or_object ) &&
						! ( WC_Admin_Notices::class === $class_or_object || Loader::class === $class_or_object )
					) ||
					(
						// We have a class instance.
						is_object( $class_or_object ) &&
						! ( $class_or_object instanceof WC_Admin_Notices || $class_or_object instanceof Loader )
					)
				) {
					remove_action( 'admin_notices', $callback['function'], $priority );
				}
			}
		}
	}
}

return new WC_Settings_Payment_Gateways_React();
