<?php
declare( strict_types=1 );

namespace Automattic\WooCommerce\Internal\Admin\Settings\PaymentProviders;

use Automattic\WooCommerce\Admin\WCAdminHelper;
use Automattic\WooCommerce\Enums\OrderInternalStatus;
use Automattic\WooCommerce\Internal\Admin\Onboarding\OnboardingProfile;
use Automattic\WooCommerce\Internal\Admin\Settings\PaymentProviders;
use WC_Abstract_Order;
use WC_Payment_Gateway;

defined( 'ABSPATH' ) || exit;

/**
 * WooPayments payment gateway provider class.
 *
 * This class handles all the custom logic for the WooPayments payment gateway provider.
 */
class WooPayments extends PaymentGateway {

	const PREFIX = 'woocommerce_admin_settings_payments__woopayments__';

	/**
	 * Check if the payment gateway needs setup.
	 *
	 * @param WC_Payment_Gateway $payment_gateway The payment gateway object.
	 *
	 * @return bool True if the payment gateway needs setup, false otherwise.
	 */
	public function needs_setup( WC_Payment_Gateway $payment_gateway ): bool {
		// No account means we need setup.
		if ( ! $this->is_account_connected( $payment_gateway ) ) {
			return true;
		}

		if ( function_exists( '\wcpay_get_container' ) && class_exists( '\WC_Payments_Account' ) ) {
			$account = \wcpay_get_container()->get( \WC_Payments_Account::class );
			if ( is_callable( array( $account, 'get_account_status_data' ) ) ) {
				// Test-drive accounts don't need setup.
				$account_status = $account->get_account_status_data();
				if ( ! empty( $account_status['testDrive'] ) ) {
					return false;
				}
			}
		}

		return parent::needs_setup( $payment_gateway );
	}

	/**
	 * Try to determine if the payment gateway is in test mode.
	 *
	 * This is a best-effort attempt, as there is no standard way to determine this.
	 * Trust the true value, but don't consider a false value as definitive.
	 *
	 * @param WC_Payment_Gateway $payment_gateway The payment gateway object.
	 *
	 * @return bool True if the payment gateway is in test mode, false otherwise.
	 */
	public function is_in_test_mode( WC_Payment_Gateway $payment_gateway ): bool {
		if ( class_exists( '\WC_Payments' ) &&
			is_callable( '\WC_Payments::mode' ) ) {

			$woopayments_mode = \WC_Payments::mode();
			if ( is_callable( array( $woopayments_mode, 'is_test' ) ) ) {
				return $woopayments_mode->is_test();
			}
		}

		return parent::is_in_test_mode( $payment_gateway );
	}

	/**
	 * Try to determine if the payment gateway is in dev mode.
	 *
	 * This is a best-effort attempt, as there is no standard way to determine this.
	 * Trust the true value, but don't consider a false value as definitive.
	 *
	 * @param WC_Payment_Gateway $payment_gateway The payment gateway object.
	 *
	 * @return bool True if the payment gateway is in dev mode, false otherwise.
	 */
	public function is_in_dev_mode( WC_Payment_Gateway $payment_gateway ): bool {
		if ( class_exists( '\WC_Payments' ) &&
			is_callable( '\WC_Payments::mode' ) ) {

			$woopayments_mode = \WC_Payments::mode();
			if ( is_callable( array( $woopayments_mode, 'is_dev' ) ) ) {
				return $woopayments_mode->is_dev();
			}
		}

		return parent::is_in_dev_mode( $payment_gateway );
	}

	/**
	 * Try to determine if the payment gateway is in test mode onboarding (aka sandbox or test-drive).
	 *
	 * This is a best-effort attempt, as there is no standard way to determine this.
	 * Trust the true value, but don't consider a false value as definitive.
	 *
	 * @param WC_Payment_Gateway $payment_gateway The payment gateway object.
	 *
	 * @return bool True if the payment gateway is in test mode onboarding, false otherwise.
	 */
	public function is_in_test_mode_onboarding( WC_Payment_Gateway $payment_gateway ): bool {
		if ( class_exists( '\WC_Payments' ) &&
			is_callable( '\WC_Payments::mode' ) ) {

			$woopayments_mode = \WC_Payments::mode();
			if ( is_callable( array( $woopayments_mode, 'is_test_mode_onboarding' ) ) ) {
				return $woopayments_mode->is_test_mode_onboarding();
			}
		}

		return parent::is_in_test_mode_onboarding( $payment_gateway );
	}

	/**
	 * Get the onboarding URL for the payment gateway.
	 *
	 * This URL should start or continue the onboarding process.
	 *
	 * @param WC_Payment_Gateway $payment_gateway The payment gateway object.
	 * @param string             $return_url      Optional. The URL to return to after onboarding.
	 *                                            This will likely get attached to the onboarding URL.
	 *
	 * @return string The onboarding URL for the payment gateway.
	 */
	public function get_onboarding_url( WC_Payment_Gateway $payment_gateway, string $return_url = '' ): string {
		if ( class_exists( '\WC_Payments_Account' ) && is_callable( '\WC_Payments_Account::get_connect_url' ) ) {
			$connect_url = \WC_Payments_Account::get_connect_url();
		} else {
			$connect_url = parent::get_onboarding_url( $payment_gateway, $return_url );
		}

		$query = wp_parse_url( $connect_url, PHP_URL_QUERY );
		// We expect the URL to have a query string. Bail if it doesn't.
		if ( empty( $query ) ) {
			return $connect_url;
		}

		// Default URL params to set, regardless if they exist.
		$params = array(
			'from'                      => defined( '\WC_Payments_Onboarding_Service::FROM_WCADMIN_PAYMENTS_SETTINGS' ) ? \WC_Payments_Onboarding_Service::FROM_WCADMIN_PAYMENTS_SETTINGS : 'WCADMIN_PAYMENT_SETTINGS',
			'source'                    => defined( '\WC_Payments_Onboarding_Service::SOURCE_WCADMIN_SETTINGS_PAGE' ) ? \WC_Payments_Onboarding_Service::SOURCE_WCADMIN_SETTINGS_PAGE : 'wcadmin-settings-page',
			'redirect_to_settings_page' => 'true',
		);

		// First, sanity check to handle existing accounts.
		// Such accounts should keep their current onboarding mode.
		// Do not force things either way.
		if ( $this->is_account_connected( $payment_gateway ) ) {
			return add_query_arg( $params, $connect_url );
		}

		// We don't have an account yet, so the onboarding link is to kickstart the process.

		// Apply our routing logic to determine if we should do a live onboarding/account.
		$live_onboarding = false;

		$onboarding_profile = get_option( OnboardingProfile::DATA_OPTION, array() );

		/*
		 * For answers provided in the onboarding profile, we will only consider live onboarding if:
		 * Merchant selected “I’m already selling” and answered either:
		 * - Yes, I’m selling online.
		 * - I’m selling both online and offline.
		 *
		 * For existing stores, we will only consider live onboarding if all are true:
		 * - Store is at least 90 days old.
		 * - Store has an active payments gateway (other than WooPayments).
		 * - Store has processed a live electronic payment in the past 90 days (any gateway).
		 *
		 * @see plugins/woocommerce/client/admin/client/core-profiler/pages/UserProfile.tsx for the values.
		 */
		if (
			isset( $onboarding_profile['business_choice'] ) && 'im_already_selling' === $onboarding_profile['business_choice'] &&
			isset( $onboarding_profile['selling_online_answer'] ) && (
				'yes_im_selling_online' === $onboarding_profile['selling_online_answer'] ||
				'im_selling_both_online_and_offline' === $onboarding_profile['selling_online_answer']
			)
		) {
			$live_onboarding = true;
		} elseif ( WCAdminHelper::is_wc_admin_active_for( 90 * DAY_IN_SECONDS ) &&
			$this->has_enabled_other_ecommerce_gateways() &&
			$this->has_orders() ) {

			$live_onboarding = true;
		}

		// If we are doing live onboarding, we don't need to add more to the URL.
		// But for test-drive/sandbox mode, we have work to do.
		if ( ! $live_onboarding ) {
			$params['test_drive']                       = 'true';
			$params['auto_start_test_drive_onboarding'] = 'true';
		}

		return add_query_arg( $params, $connect_url );
	}

	/**
	 * Check if the store has any paid orders.
	 *
	 * Currently, we look at the past 90 days and only consider orders
	 * with status `wc-completed`, `wc-processing`, or `wc-refunded`.
	 *
	 * @return boolean Whether the store has any paid orders.
	 */
	private function has_orders(): bool {
		$store_has_orders_transient_name = self::PREFIX . 'store_has_orders';

		// First, get the stored value, if it exists.
		// This way we avoid costly DB queries and API calls.
		$has_orders = get_transient( $store_has_orders_transient_name );
		if ( false !== $has_orders ) {
			return filter_var( $has_orders, FILTER_VALIDATE_BOOLEAN );
		}

		// We need to determine the value.
		// Start with the assumption that the store doesn't have orders in the timeframe we look at.
		$has_orders = false;
		// By default, we will check for new orders every 6 hours.
		$expiration = 6 * HOUR_IN_SECONDS;

		// Get the latest completed, processing, or refunded order.
		$latest_order = wc_get_orders(
			array(
				'status'  => array( OrderInternalStatus::COMPLETED, OrderInternalStatus::PROCESSING, OrderInternalStatus::REFUNDED ),
				'limit'   => 1,
				'orderby' => 'date',
				'order'   => 'DESC',
			)
		);
		if ( ! empty( $latest_order ) ) {
			$latest_order = reset( $latest_order );
			// If the latest order is within the timeframe we look at, we consider the store to have orders.
			// Otherwise, it clearly doesn't have orders.
			if ( $latest_order instanceof WC_Abstract_Order
				&& strtotime( (string) $latest_order->get_date_created() ) >= strtotime( '-90 days' ) ) {

				$has_orders = true;

				// For ultimate efficiency, we will check again after 90 days from the latest order
				// because in all that time we will consider the store to have orders regardless of new orders.
				$expiration = strtotime( (string) $latest_order->get_date_created() ) + 90 * DAY_IN_SECONDS - time();
			}
		}

		// Store the value for future use.
		set_transient( $store_has_orders_transient_name, $has_orders ? 'yes' : 'no', $expiration );

		return $has_orders;
	}

	/**
	 * Check if the store has any other enabled ecommerce gateways.
	 *
	 * We exclude offline payment methods from this check.
	 *
	 * @return bool True if the store has any enabled ecommerce gateways, false otherwise.
	 */
	private function has_enabled_other_ecommerce_gateways(): bool {
		$gateways                 = WC()->payment_gateways()->payment_gateways;
		$other_ecommerce_gateways = array_filter(
			$gateways,
			function ( $gateway ) {
				// Filter out offline gateways and WooPayments.
				return 'yes' === $gateway->enabled &&
					! in_array(
						$gateway->id,
						array( 'woocommerce_payments', ...PaymentProviders::OFFLINE_METHODS ),
						true
					);
			}
		);

		return ! empty( $other_ecommerce_gateways );
	}
}
