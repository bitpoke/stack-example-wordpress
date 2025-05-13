<?php
declare( strict_types=1 );

namespace Automattic\WooCommerce\Internal\Admin\Settings;

use Automattic\WooCommerce\Internal\Features\FeaturesController;
use Automattic\WooCommerce\Utilities\FeaturesUtil;
use Exception;
use WC_Gateway_BACS;
use WC_Gateway_Cheque;
use WC_Gateway_COD;

defined( 'ABSPATH' ) || exit;
/**
 * Payments settings controller class.
 *
 * Use this class for hooks and actions related to the Payments settings page.
 */
class PaymentsController {

	/**
	 * The payment service.
	 *
	 * @var Payments
	 */
	private Payments $payments;

	/**
	 * Register hooks.
	 */
	public function register() {
		// Because we gate the hooking based on a feature flag,
		// we need to delay the registration until the 'woocommerce_init' hook.
		// Otherwise, we end up in an infinite loop.
		add_action( 'woocommerce_init', array( $this, 'delayed_register' ) );
	}

	/**
	 * Delayed hook registration.
	 */
	public function delayed_register() {
		// Don't do anything if the feature is not enabled.
		if ( ! FeaturesUtil::feature_is_enabled( 'reactify-classic-payments-settings' ) ) {
			return;
		}

		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_filter( 'woocommerce_admin_shared_settings', array( $this, 'preload_settings' ) );
		add_filter( 'woocommerce_admin_allowed_promo_notes', array( $this, 'add_allowed_promo_notes' ) );
		add_filter( 'woocommerce_get_sections_checkout', array( $this, 'handle_sections' ), 20 );
	}

	/**
	 * Initialize the class instance.
	 *
	 * @param Payments $payments The payments service.
	 *
	 * @internal
	 */
	final public function init( Payments $payments ): void {
		$this->payments = $payments;
	}

	/**
	 * Adds the Payments top-level menu item.
	 */
	public function add_menu() {
		global $menu;

		// When WooPayments account is onboarded, WooPayments will own the Payments menu item since it is the native Woo payments solution.
		if ( $this->is_woopayments_account_onboarded() ) {
			return;
		} else {
			// Otherwise, remove Payments menu item linking to the connect page to avoid Payments menu item duplication.
			remove_menu_page( 'wc-admin&path=/payments/connect' );
		}

		$menu_title = esc_html__( 'Payments', 'woocommerce' );
		$menu_icon  = 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI4NTIiIGhlaWdodD0iNjg0Ij48cGF0aCBmaWxsPSIjYTJhYWIyIiBkPSJNODIgODZ2NTEyaDY4NFY4NlptMCA1OThjLTQ4IDAtODQtMzgtODQtODZWODZDLTIgMzggMzQgMCA4MiAwaDY4NGM0OCAwIDg0IDM4IDg0IDg2djUxMmMwIDQ4LTM2IDg2LTg0IDg2em0zODQtNTU2djQ0aDg2djg0SDM4MnY0NGgxMjhjMjQgMCA0MiAxOCA0MiA0MnYxMjhjMCAyNC0xOCA0Mi00MiA0MmgtNDR2NDRoLTg0di00NGgtODZ2LTg0aDE3MHYtNDRIMzM4Yy0yNCAwLTQyLTE4LTQyLTQyVjIxNGMwLTI0IDE4LTQyIDQyLTQyaDQ0di00NHoiLz48L3N2Zz4=';
		// Link to the Payments settings page.
		$menu_path = 'admin.php?page=wc-settings&tab=checkout';

		add_menu_page(
			$menu_title,
			$menu_title,
			'manage_woocommerce', // Capability required to see the menu item.
			$menu_path,
			null,
			$menu_icon,
			56, // Position after WooCommerce Product menu item.
		);

		// If there are providers with active incentive, add a notice badge to the Payments menu item.
		if ( $this->store_has_providers_with_incentive() ) {
			$badge = ' <span class="wcpay-menu-badge awaiting-mod count-1"><span class="plugin-count">1</span></span>';
			foreach ( $menu as $index => $menu_item ) {
				// Only add the badge markup if not already present and the menu item is the Payments menu item.
				if ( 0 === strpos( $menu_item[0], $menu_title )
					&& $menu_path === $menu_item[2]
					&& false === strpos( $menu_item[0], $badge ) ) {

					$menu[ $index ][0] .= $badge; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

					// One menu item with a badge is more than enough.
					break;
				}
			}
		}
	}

	/**
	 * Preload settings to make them available to the Payments settings page frontend logic.
	 *
	 * Added keys will be available in the window.wcSettings.admin object.
	 *
	 * @param array $settings The settings array.
	 *
	 * @return array Settings array with additional settings added.
	 */
	public function preload_settings( array $settings ): array {
		// We only preload settings in the WP admin.
		if ( ! is_admin() ) {
			return $settings;
		}

		// Add the business location country to the settings.
		if ( ! isset( $settings[ Payments::PAYMENTS_NOX_PROFILE_KEY ] ) ) {
			$settings[ Payments::PAYMENTS_NOX_PROFILE_KEY ] = array();
		}
		$settings[ Payments::PAYMENTS_NOX_PROFILE_KEY ]['business_country_code'] = $this->payments->get_country();

		return $settings;
	}

	/**
	 * Adds promo note IDs to the list of allowed ones.
	 *
	 * @param array $promo_notes Allowed promo note IDs.
	 *
	 * @return array The updated list of allowed promo note IDs.
	 */
	public function add_allowed_promo_notes( array $promo_notes = array() ): array {
		try {
			$providers = $this->payments->get_payment_providers( $this->payments->get_country() );
		} catch ( Exception $e ) {
			// In case of an error, bail.
			return $promo_notes;
		}

		// Add all incentive promo IDs to the allowed promo notes list.
		foreach ( $providers as $provider ) {
			if ( ! empty( $provider['_incentive']['promo_id'] ) ) {
				$promo_notes[] = $provider['_incentive']['promo_id'];
			}
		}

		return $promo_notes;
	}

	/**
	 * Alter the Payments tab sections under certain conditions.
	 *
	 * @param array $sections The payments/checkout tab sections.
	 *
	 * @return array The filtered sections.
	 */
	public function handle_sections( array $sections ): array {
		global $current_section;

		// For WooPayments and offline payment methods settings pages, we don't want any section navigation.
		if ( in_array( $current_section, array( 'woocommerce_payments', WC_Gateway_BACS::ID, WC_Gateway_Cheque::ID, WC_Gateway_COD::ID  ), true ) ) {
			return array();
		}

		return $sections;
	}

	/**
	 * Check if the store has any enabled gateways (including offline payment methods).
	 *
	 * @return bool True if the store has any enabled gateways, false otherwise.
	 */
	private function store_has_enabled_gateways(): bool {
		$gateways         = WC()->payment_gateways->get_available_payment_gateways();
		$enabled_gateways = array_filter(
			$gateways,
			function ( $gateway ) {
				return 'yes' === $gateway->enabled;
			}
		);

		return ! empty( $enabled_gateways );
	}

	/**
	 * Check if the store has any payment providers that have an active incentive.
	 *
	 * @return bool True if the store has providers with an active incentive.
	 */
	private function store_has_providers_with_incentive(): bool {
		try {
			$providers = $this->payments->get_payment_providers( $this->payments->get_country() );
		} catch ( Exception $e ) {
			// In case of an error, just return false.
			return false;
		}

		// Go through the providers and check if any of them have a "prominently" visible incentive (i.e., modal or banner).
		foreach ( $providers as $provider ) {
			if ( empty( $provider['_incentive'] ) ) {
				continue;
			}

			$dismissals = $provider['_incentive']['_dismissals'] ?? array();

			// If there are no dismissals at all, the incentive is prominently visible.
			if ( empty( $dismissals ) ) {
				return true;
			}

			// First, we check to see if the incentive was dismissed in the banner context.
			// The banner context has the lowest priority, so if it was dismissed, we don't need to check the modal context.
			// If the banner is dismissed, there is no prominent incentive.
			$is_dismissed_banner = ! empty(
				array_filter(
					$dismissals,
					function ( $dismissal ) {
						return isset( $dismissal['context'] ) && 'wc_settings_payments__banner' === $dismissal['context'];
					}
				)
			);
			if ( $is_dismissed_banner ) {
				continue;
			}

			// In case an incentive uses the modal surface also (like the WooPayments Switch incentive),
			// we rely on the fact that the modal falls back to the banner, once dismissed, after 30 days.
			// @see here's its frontend "brother" in client/admin/client/settings-payments/settings-payments-main.tsx.
			$is_dismissed_modal = ! empty(
				array_filter(
					$dismissals,
					function ( $dismissal ) {
						return isset( $dismissal['context'] ) && 'wc_settings_payments__modal' === $dismissal['context'];
					}
				)
			);
			// If there are no modal dismissals, the incentive is still visible.
			if ( ! $is_dismissed_modal ) {
				return true;
			}

			$is_dismissed_modal_more_than_30_days_ago = ! empty(
				array_filter(
					$dismissals,
					function ( $dismissal ) {
						return isset( $dismissal['context'], $dismissal['timestamp'] ) &&
							'wc_settings_payments__modal' === $dismissal['context'] &&
							$dismissal['timestamp'] < strtotime( '-30 days' );
					}
				)
			);
			// If the modal was dismissed less than 30 days ago, there is no prominent incentive (aka the banner is not shown).
			if ( ! $is_dismissed_modal_more_than_30_days_ago ) {
				continue;
			}

			// The modal was dismissed more than 30 days ago, so the banner is visible.
			return true;
		}

		return false;
	}

	/**
	 * Check if the WooPayments account is onboarded.
	 *
	 * @return boolean
	 */
	private function is_woopayments_account_onboarded(): bool {
		// If WooPayments is active right now, we will not get to this point since the plugin is active check is done first.
		if ( ! class_exists( '\WC_Payments' ) ) {
			return false;
		}

		$account_data = get_option( 'wcpay_account_data', array() );
		if ( empty( $account_data['data']['account_id'] ) ) {
			return false;
		}

		if ( empty( $account_data['data']['details_submitted'] ) ) {
			return false;
		}
		// We consider the store to have WooPayments account connected if account data in the WooPayments account cache
		// contains details_submitted = true entry. This implies that WooPayments was connected.
		return $account_data['data']['details_submitted'];
	}
}
