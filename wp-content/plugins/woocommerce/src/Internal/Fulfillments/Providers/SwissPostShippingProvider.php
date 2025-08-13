<?php declare(strict_types=1);

namespace Automattic\WooCommerce\Internal\Fulfillments\Providers;

/**
 * Swiss Post Shipping Provider class.
 */
class SwissPostShippingProvider extends AbstractShippingProvider {
	/**
	 * Get the key of the shipping provider.
	 *
	 * @return string
	 */
	public function get_key(): string {
		return 'swiss-post';
	}

	/**
	 * Get the name of the shipping provider.
	 *
	 * @return string
	 */
	public function get_name(): string {
		return 'Swiss Post';
	}

	/**
	 * Get the icon of the shipping provider.
	 *
	 * @return string
	 */
	public function get_icon(): string {
		return esc_url( WC()->plugin_url() ) . '/assets/images/shipping_providers/swiss-post.png';
	}

	/**
	 * Get the tracking URL for a given tracking number.
	 *
	 * @param string $tracking_number The tracking number.
	 * @return string The tracking URL.
	 */
	public function get_tracking_url( string $tracking_number ): string {
		return 'https://www.post.ch/en/parcel-tracking?itemId=' . $tracking_number;
	}
}
