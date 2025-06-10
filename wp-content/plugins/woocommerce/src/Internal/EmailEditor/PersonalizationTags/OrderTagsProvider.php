<?php

declare( strict_types=1 );

namespace Automattic\WooCommerce\Internal\EmailEditor\PersonalizationTags;

use Automattic\WooCommerce\EmailEditor\Engine\PersonalizationTags\Personalization_Tag;
use Automattic\WooCommerce\EmailEditor\Engine\PersonalizationTags\Personalization_Tags_Registry;

/**
 * Provider for order-related personalization tags.
 *
 * @internal
 */
class OrderTagsProvider extends AbstractTagProvider {
	/**
	 * Register order tags with the registry.
	 *
	 * @param Personalization_Tags_Registry $registry The personalization tags registry.
	 * @return void
	 */
	public function register_tags( Personalization_Tags_Registry $registry ): void {
		$registry->register(
			new Personalization_Tag(
				__( 'Order Number', 'woocommerce' ),
				'woocommerce/order-number',
				__( 'Order', 'woocommerce' ),
				function ( array $context ): string {
					if ( ! isset( $context['order'] ) ) {
						return '';
					}
					return $context['order']->get_order_number() ?? '';
				},
			)
		);

		$registry->register(
			new Personalization_Tag(
				__( 'Order Date', 'woocommerce' ),
				'woocommerce/order-date',
				__( 'Order', 'woocommerce' ),
				function ( array $context, array $parameters = array() ): string {
					if ( ! isset( $context['order'] ) ) {
						return '';
					}
					$format       = isset( $parameters['format'] ) && is_string( $parameters['format'] ) ? $parameters['format'] : wc_date_format();
					$date_created = $context['order']->get_date_created();
					if ( ! $date_created ) {
						return '';
					}
					return wc_format_datetime( $date_created, $format );
				},
				array(
					'format' => wc_date_format(),
				),
			)
		);

		$registry->register(
			new Personalization_Tag(
				__( 'Order Items', 'woocommerce' ),
				'woocommerce/order-items',
				__( 'Order', 'woocommerce' ),
				function ( array $context ): string {
					if ( ! isset( $context['order'] ) ) {
						return '';
					}
					$items = array();
					foreach ( $context['order']->get_items() as $item ) {
						$items[] = $item->get_name();
					}
					return implode( ', ', $items );
				},
			)
		);

		$registry->register(
			new Personalization_Tag(
				__( 'Order Subtotal', 'woocommerce' ),
				'woocommerce/order-subtotal',
				__( 'Order', 'woocommerce' ),
				function ( array $context ): string {
					if ( ! isset( $context['order'] ) ) {
						return '';
					}
					return (string) $context['order']->get_subtotal() ?? '';
				},
			)
		);

		$registry->register(
			new Personalization_Tag(
				__( 'Order Tax', 'woocommerce' ),
				'woocommerce/order-tax',
				__( 'Order', 'woocommerce' ),
				function ( array $context ): string {
					if ( ! isset( $context['order'] ) ) {
						return '';
					}
					return (string) $context['order']->get_total_tax() ?? '';
				},
			)
		);

		$registry->register(
			new Personalization_Tag(
				__( 'Order Total', 'woocommerce' ),
				'woocommerce/order-total',
				__( 'Order', 'woocommerce' ),
				function ( array $context ): string {
					if ( ! isset( $context['order'] ) ) {
						return '';
					}
					return (string) $context['order']->get_total() ?? '';
				},
			)
		);

		$registry->register(
			new Personalization_Tag(
				__( 'Payment Method', 'woocommerce' ),
				'woocommerce/order-payment-method',
				__( 'Order', 'woocommerce' ),
				function ( array $context ): string {
					if ( ! isset( $context['order'] ) ) {
						return '';
					}
					return $context['order']->get_payment_method_title() ?? '';
				},
			)
		);

		$registry->register(
			new Personalization_Tag(
				__( 'Payment URL', 'woocommerce' ),
				'woocommerce/order-payment-url',
				__( 'Order', 'woocommerce' ),
				function ( array $context ): string {
					if ( ! isset( $context['order'] ) ) {
						return '';
					}
					return $context['order']->get_checkout_payment_url() ?? '';
				},
			)
		);
	}
}
