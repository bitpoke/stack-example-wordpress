<?php
/*
Plugin Name: WP Test Email
Description: WP Test Email allows you to test if your WordPress installation is sending mail or not and logs all outgoing emails.
Version: 1.1.8
Author: Boopathi Rajan
Text Domain: wp-test-email
Author URI: https://www.boopathirajan.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

function register_wp_test_email_page() {
    add_submenu_page('tools.php', "Test Email", "Test Email", 'manage_options', 'wp-test-email', 'wp_test_email');
}
add_action('admin_menu', 'register_wp_test_email_page');

function wp_test_email() {
?>
<div class="wrap">
    <h1><?php _e('Test Mail', 'wp-test-email'); ?></h1>
    <form method="post">
        <?php
        if (isset($_POST['mail_to'])) {
            if (wp_verify_nonce($_POST['wp_test_email_nonce_field'], 'wp_test_email_nonce_action')) {
                if (!empty($_POST['mail_to'])) {
                    $to = sanitize_email($_POST['mail_to']);
                    $subject = sanitize_text_field($_POST['mail_subject']);
                    $body = "This is the test mail from " . get_bloginfo('name');
                    $headers = array('Content-Type: text/html; charset=UTF-8');
                    $test_email = wp_mail($to, $subject, $body);
                    if ($test_email) {
                        ?>
                        <div class="notice notice-success is-dismissible">
                            <p><?php _e('Email has been sent!', 'wp-test-email'); ?></p>
                        </div>
                        <?php
                    } else {
                        ?>
                        <div class="notice notice-error is-dismissible">
                            <p><?php _e('Email not sent!', 'wp-test-email'); ?></p>
                        </div>
                        <?php
                    }
                }
            }
        }
        ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><?php _e('To', 'wp-test-email'); ?></th>
                <td>
                    <input type="email" name="mail_to" value=""/>
                    <p class="description"><i><?php _e('Enter "To address" here.', 'wp-test-email'); ?></i></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Subject', 'wp-test-email'); ?></th>
                <td>
                    <input type="text" name="mail_subject" value="Test Mail"/>
                    <p class="description"><i><?php _e('Enter mail subject here', 'wp-test-email'); ?></i></p>
                </td>
            </tr>
        </table>
        <?php wp_nonce_field('wp_test_email_nonce_action', 'wp_test_email_nonce_field'); ?>
        <?php submit_button(); ?>
    </form>
</div>
<?php 
}

// Hook into wp_mail to log all outgoing emails
add_action('phpmailer_init', 'log_outgoing_emails');

function log_outgoing_emails($phpmailer) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'test_email_logs';

    $to = implode(', ', array_column($phpmailer->getToAddresses(), 0));
    $subject = $phpmailer->Subject;
    $body = $phpmailer->Body;
    $status = 'Sent'; // Assuming it's sent unless there's an error

    $wpdb->insert(
        $table_name,
        array(
            'time' => current_time('mysql'),
            'to_email' => $to,
            'subject' => $subject,
            'body' => $body,
            'status' => $status,
        )
    );
}

// Create table to store email logs on plugin activation
register_activation_hook(__FILE__, 'wp_test_email_create_table');
function wp_test_email_create_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'test_email_logs';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        to_email varchar(100) NOT NULL,
        subject varchar(255) NOT NULL,
        body text NOT NULL,
        status varchar(20) NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Register the Email Logs page
function register_wp_test_email_logs_page() {
    add_submenu_page('tools.php', "Email Logs", "Email Logs", 'manage_options', 'wp-test-email-logs', 'wp_test_email_logs');
}
add_action('admin_menu', 'register_wp_test_email_logs_page');

function wp_test_email_logs() {
    global $wpdb;

    // Pagination and search variables
    $table_name = $wpdb->prefix . 'test_email_logs';
    $items_per_page = 10;
    $current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
    $offset = ($current_page - 1) * $items_per_page;

    // Sorting variables
    $sort_by = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'time';
    $order = isset($_GET['order']) ? strtoupper(sanitize_text_field($_GET['order'])) : 'DESC';
    $allowed_sort_columns = ['time', 'to_email', 'subject', 'status'];

    if (!in_array($sort_by, $allowed_sort_columns)) {
        $sort_by = 'time'; // Default to time if invalid
    }
    if ($order !== 'ASC' && $order !== 'DESC') {
        $order = 'DESC'; // Default to DESC if invalid
    }

    $search_query = '';
    if (isset($_GET['s'])) {
        $search_query = sanitize_text_field($_GET['s']);
    }

    // Query for email logs with search, sorting, and pagination
    $query = "SELECT * FROM $table_name";
    if (!empty($search_query)) {
        $search_query_escaped = '%' . $wpdb->esc_like($search_query) . '%';
        $query .= $wpdb->prepare(
            " WHERE to_email LIKE %s OR subject LIKE %s OR body LIKE %s",
            $search_query_escaped, $search_query_escaped, $search_query_escaped
        );
    }
    $query .= " ORDER BY $sort_by $order LIMIT $offset, $items_per_page";

    $results = $wpdb->get_results($query);

    // Total items for pagination
    $total_items_query = "SELECT COUNT(*) FROM $table_name";
    if (!empty($search_query)) {
        $total_items_query .= $wpdb->prepare(
            " WHERE to_email LIKE %s OR subject LIKE %s OR body LIKE %s",
            $search_query_escaped, $search_query_escaped, $search_query_escaped
        );
    }
    $total_items = $wpdb->get_var($total_items_query);

    $total_pages = ceil($total_items / $items_per_page);
    ?>

    <div class="wrap">
        <h1><?php _e('Email Logs', 'wp-test-email'); ?></h1>

        <div class="search-wrapper">
            <form method="get">
                <input type="hidden" name="page" value="wp-test-email-logs" />
                <input type="text" name="s" value="<?php echo esc_attr($search_query); ?>" placeholder="<?php _e('Search emails...', 'wp-test-email'); ?>" />
                <input type="submit" class="button" value="<?php _e('Search', 'wp-test-email'); ?>" />
            </form>
        </div>

        <table class="widefat fixed" cellspacing="0">
            <thead>
                <tr>
                    <th><a href="<?php echo esc_url(add_query_arg(['orderby' => 'time', 'order' => ($sort_by == 'time' && $order == 'ASC') ? 'DESC' : 'ASC'])); ?>"><?php _e('Time', 'wp-test-email'); ?></a></th>
                    <th><a href="<?php echo esc_url(add_query_arg(['orderby' => 'to_email', 'order' => ($sort_by == 'to_email' && $order == 'ASC') ? 'DESC' : 'ASC'])); ?>"><?php _e('To', 'wp-test-email'); ?></a></th>
                    <th><a href="<?php echo esc_url(add_query_arg(['orderby' => 'subject', 'order' => ($sort_by == 'subject' && $order == 'ASC') ? 'DESC' : 'ASC'])); ?>"><?php _e('Subject', 'wp-test-email'); ?></a></th>
                    <th><?php _e('Body', 'wp-test-email'); ?></th>
                    <th><a href="<?php echo esc_url(add_query_arg(['orderby' => 'status', 'order' => ($sort_by == 'status' && $order == 'ASC') ? 'DESC' : 'ASC'])); ?>"><?php _e('Status', 'wp-test-email'); ?></a></th>
                </tr>
            </thead>
            <tbody>
                <?php if ($results) { ?>
                    <?php foreach($results as $log) { ?>
                        <tr>
                            <td><?php echo esc_html($log->time); ?></td>
                            <td><?php echo esc_html($log->to_email); ?></td>
                            <td><?php echo esc_html($log->subject); ?></td>
                            <td><a href="#" class="view-email-body" data-body="<?php echo esc_attr($log->body); ?>"><?php _e('View', 'wp-test-email'); ?></a></td>
                            <td><?php echo esc_html($log->status); ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="5"><?php _e('No logs found.', 'wp-test-email'); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="tablenav">
            <div class="tablenav-pages">
                <?php
                $paginate_links = paginate_links(array(
                    'base' => add_query_arg('paged', '%#%'),
                    'format' => '',
                    'prev_text' => __('&laquo;', 'wp-test-email'),
                    'next_text' => __('&raquo;', 'wp-test-email'),
                    'total' => $total_pages,
                    'current' => $current_page
                ));
                echo $paginate_links;
                ?>
            </div>
        </div>

        <!-- Popup HTML structure -->
        <div id="email-popup" style="display:none;">
            <div class="popup-content">
                <div class="popup-header">
                    <h2><?php _e('Email Body', 'wp-test-email'); ?></h2>
                    <a href="#" id="close-popup" class="close-popup"><?php _e('Close', 'wp-test-email'); ?></a>
                </div>
                <div class="popup-body"></div>
            </div>
        </div>
    </div>

    <style>
	/* Search Box Styles */
	.search-wrapper {
		margin-bottom: 20px; /* Margin bottom */
		text-align: right; /* Align search box to the right */
	}
	.search-wrapper form {
		display: flex;
		justify-content: flex-end; /* Align items to the end */
	}
	.search-wrapper input[type="text"] {
		margin-right: 10px; /* Space between search box and button */
	}

	/* Popup Styles */
	#email-popup {
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background-color: rgba(0, 0, 0, 0.8);
		z-index: 10000;
		display: none; /* Hidden by default */
		justify-content: center;
		align-items: center;
	}
	.popup-content {
		background: #fff;
		padding: 20px;
		border-radius: 10px;
		max-width: 600px; /* Fixed maximum width */
		width: 90%; /* Responsive width */
		max-height: 80%; /* Set maximum height */
		overflow-y: auto; /* Enable scrolling for overflow content */
		overflow-x: hidden; /* Hide horizontal overflow */
		margin: 40px auto;
	}
	.popup-header {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 10px;
	}
	.close-popup {
		cursor: pointer;
		color: #000;
		text-decoration: none;
	}
	.popup-body {
		word-wrap: break-word; /* Ensure long text breaks to fit container */
	}

	/* Pagination Styles */
	.tablenav .tablenav-pages {
		display: flex;
		justify-content: flex-end; /* Align pagination to the right */
		margin-top: 20px; /* Space above pagination */
	}

	.tablenav .tablenav-pages a,
	.tablenav .tablenav-pages span {
		display: inline-block;
		padding: 6px 12px;
		margin: 0 2px;
		border: 1px solid #ddd;
		border-radius: 3px;
		color: #0073aa;
		text-decoration: none;
		font-size: 14px;
	}

	.tablenav .tablenav-pages a:hover {
		background-color: #f1f1f1;
		border-color: #ccc;
	}

	.tablenav .tablenav-pages .current {
		background-color: #0073aa;
		color: #fff;
		border-color: #0073aa;
		cursor: default;
	}
	</style>


    <script>
    jQuery(document).ready(function($) {
        // Show the popup and set its body content
        $('.view-email-body').on('click', function(e) {
            e.preventDefault();
            var emailBody = $(this).data('body');
            $('#email-popup .popup-body').html(emailBody);
            $('#email-popup').show();
        });

        // Close the popup when clicking on the close button
        $('#close-popup').on('click', function(e) {
            e.preventDefault();
            $('#email-popup').hide();
        });

        // Close the popup when clicking outside of the popup content
        $(document).on('click', function(e) {
            if ($(e.target).is('#email-popup')) {
                $('#email-popup').hide();
            }
        });
    });
    </script>
<?php 
}


// Schedule a daily cron event if it's not already scheduled
if (!wp_next_scheduled('wp_test_email_clear_logs')) {
    wp_schedule_event(time(), 'daily', 'wp_test_email_clear_logs');
}

// Hook the cron event to a custom function
add_action('wp_test_email_clear_logs', 'wp_test_email_clear_old_logs');

// Function to clear logs older than 30 days
function wp_test_email_clear_old_logs() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'test_email_logs';
    $date_threshold = date('Y-m-d', strtotime('-30 days'));

    $wpdb->delete(
        $table_name,
        array('time <' => $date_threshold),
        array('%s')
    );
}
?>