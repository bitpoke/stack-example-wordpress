<?php
/*
Plugin Name: WP Test Email
description: WP Test Email is allows you to test if your WordPress installation is sending mail or not.
Version: 1.1.6
Author: Boopathi Rajan
Text Domain: wp-test-email
Author URI: https://www.boopathirajan.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

function register_wp_test_email_page() {
	add_submenu_page( 'tools.php', "Test Email", "Test Email", 'manage_options', 'wp-test-email', 'wp_test_email' );
}
add_action('admin_menu', 'register_wp_test_email_page');

function wp_test_email() 
{	
?>
<div class="wrap">
	<h1><?php _e( 'Test Mail', 'wp-test-email' ); ?></h1>
	<form method="post">		
		<?php
		if(isset($_POST['mail_to']))
		{
			 
			 if(wp_verify_nonce($_POST['wp_test_email_nonce_field'], 'wp_test_email_nonce_action'))
			 {
				if(!empty($_POST['mail_to']))
				{
					$to=sanitize_email($_POST['mail_to']);
					$subject=sanitize_text_field($_POST['mail_subject']);
					$body=" This is the test mail from ".get_bloginfo('name');
					$headers = array('Content-Type: text/html; charset=UTF-8'); 
					$test_email=wp_mail( $to, $subject, $body );
					if($test_email)
					{
						?>
						<div class="notice notice-success is-dismissible">
							<p><?php _e( 'Email has been sent!', 'wp-test-email' ); ?></p>
						</div>
						<?php
					}
					else
					{
						?>
						<div class="notice notice-error is-dismissible">
							<p><?php _e( 'Email not sent!', 'wp-test-email' ); ?></p>
						</div>
						<?php
					}
				}
			 }
		}
		?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php _e( 'To', 'wp-test-email' ); ?></th>
				<td>
					<input type="email" name="mail_to" value=""/>
					<p class="description"><i><?php _e( 'Enter "To address" here.', 'wp-test-email' ); ?></i></p>
				</td>
			</tr> 
			<tr valign="top">
				<th scope="row"><?php _e( 'Subject', 'wp-test-email' ); ?></th>
				<td>
					<input type="text" name="mail_subject" value="Test Mail"/>
					<p class="description"><i><?php _e( 'Enter mail subject here', 'wp-test-email' ); ?></i></p> 
				</td>
			</tr> 			
		</table>    
		<?php wp_nonce_field( 'wp_test_email_nonce_action', 'wp_test_email_nonce_field' ); ?>
		<?php submit_button(); ?>
	</form>
</div>
<?php 
}
?>