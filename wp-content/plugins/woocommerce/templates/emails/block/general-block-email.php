<?php
/**
 * General block email
 *
 * Used to render information for the email editor WooCommerce content block.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails\Block
 * @version 9.9.0
 */

defined( 'ABSPATH' ) || exit;

use Automattic\WooCommerce\Enums\OrderStatus;

if ( 'customer_invoice' === $email->id ) :
	// Customer invoice email
	// We are keeping this here until we have a better way to handle conditional content in the email editor.
	?>
	<?php if ( $order->needs_payment() ) { ?>
		<p>
		<?php
		if ( $order->has_status( OrderStatus::FAILED ) ) {
			printf(
				wp_kses(
				/* translators: %1$s Site title, %2$s Order pay link */
					__( 'Sorry, your order on %1$s was unsuccessful. Your order details are below, with a link to try your payment again: %2$s', 'woocommerce' ),
					array(
						'a' => array(
							'href' => array(),
						),
					)
				),
				esc_html( get_bloginfo( 'name', 'display' ) ),
				'<a href="' . esc_url( $order->get_checkout_payment_url() ) . '">' . esc_html__( 'Pay for this order', 'woocommerce' ) . '</a>'
			);
		} else {
			printf(
				wp_kses(
				/* translators: %1$s Site title, %2$s Order pay link */
					__( 'An order has been created for you on %1$s. Your order details are below, with a link to make payment when you’re ready: %2$s', 'woocommerce' ),
					array(
						'a' => array(
							'href' => array(),
						),
					)
				),
				esc_html( get_bloginfo( 'name', 'display' ) ),
				'<a href="' . esc_url( $order->get_checkout_payment_url() ) . '">' . esc_html__( 'Pay for this order', 'woocommerce' ) . '</a>'
			);
		}
		?>
		</p>

	<?php } else { ?>
		<p>
		<?php
		/* translators: %s Order date */
		printf( esc_html__( 'Here are the details of your order placed on %s:', 'woocommerce' ), esc_html( wc_format_datetime( $order->get_date_created() ) ) );
		?>
		</p>
		<?php
	}
endif;

if ( 'customer_new_account' === $email->id ) :
	?>
	<?php if ( $set_password_url ) : ?>
		<p><a href="<?php echo esc_attr( $set_password_url ); ?>"><?php printf( esc_html__( 'Set your new password.', 'woocommerce' ) ); ?></a></p>
		<?php
	endif;
endif;

if ( 'customer_reset_password' === $email->id && isset( $reset_key, $user_id ) ) :
	// Customer reset password email.
	?>
<p>
	<a class="link" href="<?php echo esc_url( add_query_arg( array( 'key' => $reset_key, 'id' => $user_id, 'login' => rawurlencode( $user_login ) ), wc_get_endpoint_url( 'lost-password', '', wc_get_page_permalink( 'myaccount' ) ) ) ); ?>"><?php // phpcs:ignore WordPress.Arrays.ArrayDeclarationSpacing.AssociativeArrayFound ?>
		<?php esc_html_e( 'Reset your password', 'woocommerce' ); ?>
	</a>
</p>
	<?php
endif;

$accounts_related_emails = array(
	'customer_reset_password',
	'customer_new_account',
);

if ( isset( $order ) && ! in_array( $email->id, $accounts_related_emails, true ) ) {

	/**
	 * Woocommerce_email_order_details
	 *
	 * @hooked WC_Emails::order_details() Shows the order details table.
	 * @hooked WC_Structured_Data::generate_order_data() Generates structured data.
	 * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
	 * @since 2.5.0
	 */
	do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

	/**
	 * Woocommerce_email_order_meta
	 *
	 * @hooked WC_Emails::order_meta() Shows order meta data.
	 * @since 2.0.17
	 */
	do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

	/**
	 * Woocommerce_email_customer_details
	 *
	 * @hooked WC_Emails::customer_details() Shows customer details
	 * @hooked WC_Emails::email_address() Shows email address
	 * @since 2.5.0
	 */
	do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );
}
