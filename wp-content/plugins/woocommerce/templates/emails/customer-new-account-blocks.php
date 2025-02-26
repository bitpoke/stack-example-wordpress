<?php
/**
 * Customer new account email - html.
 *
 * This is intended as a replacement to WC_Email_Customer_New_Account(),
 * with a set password link instead of including the new password in email
 * content.
 *
 * @package  WooCommerce/Blocks
 * @version 9.7.0
 */

use Automattic\WooCommerce\Utilities\FeaturesUtil;

defined( 'ABSPATH' ) || exit;

$email_improvements_enabled = FeaturesUtil::feature_is_enabled( 'email_improvements' );

/**
 * Fires to output the email header.
 *
 * @hooked WC_Emails::email_header()
 *
 * @since 3.7.0
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<?php echo $email_improvements_enabled ? '<div class="email-introduction">' : ''; ?>
<?php /* translators: %s: Customer username */ ?>
<p><?php printf( esc_html__( 'Hello %s,', 'woocommerce' ), esc_html( $user_login ) ); ?></p>
<?php /* translators: %1$s: Site title, %2$s: Username, %3$s: My account link */ ?>
<p><?php printf( esc_html__( 'Thanks for creating an account on %1$s. Your username is %2$s. You can access your account area to view orders, change your password, and more at: %3$s', 'woocommerce' ), esc_html( $blogname ), '<strong>' . esc_html( $user_login ) . '</strong>', make_clickable( esc_url( wc_get_page_permalink( 'myaccount' ) ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
<?php if ( $set_password_url ) : ?>
	<p><a href="<?php echo esc_attr( $set_password_url ); ?>"><?php printf( esc_html__( 'Click here to set your new password.', 'woocommerce' ) ); ?></a></p>
<?php endif; ?>
<?php echo $email_improvements_enabled ? '</div>' : ''; ?>

<?php
/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
	echo $email_improvements_enabled ? '<div class="email-additional-content">' : '';
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
	echo $email_improvements_enabled ? '</div>' : '';
}
/**
 * Fires to output the email footer.
 *
 * @hooked WC_Emails::email_footer()
 *
 * @since 3.7.0
 */
do_action( 'woocommerce_email_footer', $email );
