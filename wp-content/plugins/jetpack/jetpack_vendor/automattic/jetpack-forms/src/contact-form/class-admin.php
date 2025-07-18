<?php
/**
 * Admin class.
 *
 * @package automattic/jetpack-forms
 */

namespace Automattic\Jetpack\Forms\ContactForm;

use Automattic\Jetpack\Assets;
use Automattic\Jetpack\Connection\Manager as Connection_Manager;
use Automattic\Jetpack\Forms\Service\Google_Drive;
use Automattic\Jetpack\Redirect;
use Automattic\Jetpack\Tracking;
use Jetpack_Tracks_Client;

/**
 * Class Admin
 *
 * Singleton for Grunion admin area support.
 */
class Admin {
	/**
	 * CSV export nonce field name
	 *
	 * @var string The nonce field name for CSV export.
	 */
	private $export_nonce_field_csv = 'feedback_export_nonce_csv';

	/**
	 * GDrive export nonce field name
	 *
	 * @var string The nonce field name for GDrive export.
	 */
	private $export_nonce_field_gdrive = 'feedback_export_nonce_gdrive';

	/**
	 * Instantiates this singleton class
	 *
	 * @return Admin The Admin class instance.
	 */
	public static function init() {
		static $instance = false;

		if ( ! $instance ) {
			$instance = new Admin();
		}

		return $instance;
	}

	/**
	 * Admin constructor
	 */
	public function __construct() {
		add_action( 'media_buttons', array( $this, 'grunion_media_button' ), 999 );
		add_action( 'wp_ajax_grunion_form_builder', array( $this, 'grunion_display_form_view' ) );
		add_action( 'admin_print_styles', array( $this, 'grunion_admin_css' ) );
		add_action( 'admin_print_scripts', array( $this, 'grunion_admin_js' ) );
		add_action( 'admin_head', array( $this, 'grunion_add_bulk_edit_option' ) );
		add_action( 'admin_init', array( $this, 'grunion_handle_bulk_spam' ) );

		add_filter( 'bulk_actions-edit-feedback', array( $this, 'grunion_admin_bulk_actions' ) );
		add_filter( 'views_edit-feedback', array( $this, 'grunion_admin_view_tabs' ) );
		add_filter( 'manage_feedback_posts_columns', array( $this, 'grunion_post_type_columns_filter' ) );

		add_action( 'manage_posts_custom_column', array( $this, 'grunion_manage_post_columns' ), 10, 2 );
		add_action( 'restrict_manage_posts', array( $this, 'grunion_source_filter' ) );
		add_action( 'pre_get_posts', array( $this, 'grunion_source_filter_results' ) );

		add_filter( 'post_row_actions', array( $this, 'grunion_manage_post_row_actions' ), 10, 2 );

		add_action( 'wp_ajax_grunion_shortcode', array( $this, 'grunion_ajax_shortcode' ) );
		add_action( 'wp_ajax_grunion_shortcode_to_json', array( $this, 'grunion_ajax_shortcode_to_json' ) );
		add_action( 'wp_ajax_grunion_ajax_spam', array( $this, 'grunion_ajax_spam' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'grunion_enable_spam_recheck' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'grunion_add_admin_scripts' ) );
		add_action( 'wp_ajax_grunion_recheck_queue', array( $this, 'grunion_recheck_queue' ) );
		add_action( 'wp_ajax_jetpack_delete_spam_feedbacks', array( $this, 'grunion_delete_spam_feedbacks' ) );
		add_action( 'admin_notices', array( $this, 'grunion_feedback_admin_notice' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'admin_footer-edit.php', array( $this, 'print_export_modal' ) );

		add_action( 'wp_ajax_grunion_export_to_gdrive', array( $this, 'export_to_gdrive' ) );
		add_action( 'wp_ajax_grunion_gdrive_connection', array( $this, 'test_gdrive_connection' ) );
	}

	/**
	 * Hook handler for admin_enqueue_scripts hook
	 */
	public function admin_enqueue_scripts() {
		$current_screen = get_current_screen();
		if ( ! in_array( $current_screen->id, array( 'edit-feedback', 'feedback_page_feedback-export' ), true ) ) {
			return;
		}
		add_thickbox();
		$localized_strings = array(
			'exportError'       => esc_js( __( 'There was an error exporting your results', 'jetpack-forms' ) ),
			'waitingConnection' => esc_js( __( 'Waiting for connection...', 'jetpack-forms' ) ),
		);
		wp_localize_script( 'grunion-admin', 'exportParameters', $localized_strings );
	}

	/**
	 * Prints the modal markup with export buttons/content.
	 */
	public function print_export_modal() {
		if ( ! current_user_can( 'export' ) ) {
			return;
		}

		$current_screen = get_current_screen();
		if ( ! in_array( $current_screen->id, array( 'edit-feedback', 'feedback_page_feedback-export' ), true ) ) {
			return;
		}

		// if there aren't any feedbacks, bail out
		if ( ! (int) wp_count_posts( 'feedback' )->publish ) {
			return;
		}

		?>
		<div id="feedback-export-modal" style="display: none;">
			<div class="feedback-export-modal__wrapper">
				<div class="feedback-export-modal__header">
					<h1 class="feedback-export-modal__header-title"><?php esc_html_e( 'Export your Form Responses', 'jetpack-forms' ); ?></h1>
					<p class="feedback-export-modal__header-subtitle"><?php esc_html_e( 'Choose your favorite file format or export destination:', 'jetpack-forms' ); ?></p>
				</div>
				<div class="feedback-export-modal__content">
					<?php $this->get_csv_export_section(); ?>
					<?php $this->get_gdrive_export_section(); ?>
				</div>

			</div>
		</div>
		<?php
		$opener_label        = esc_html__( 'Export', 'jetpack-forms' );
		$export_modal_opener = wp_is_mobile()
			? "<a id='export-modal-opener' class='button button-primary' href='#TB_inline?&width=550&height=450&inlineId=feedback-export-modal'>{$opener_label}</a>"
			: "<a id='export-modal-opener' class='button button-primary' href='#TB_inline?&width=680&height=500&inlineId=feedback-export-modal'>{$opener_label}</a>";
		?>
		<script type="text/javascript">
			jQuery( function( $ ) {
				$( '#posts-filter #post-query-submit' ).after( <?php echo wp_json_encode( $export_modal_opener ); ?> );
			} );
		</script>
		<?php
	}

	/**
	 * Ajax handler for wp_ajax_grunion_export_to_gdrive.
	 * Exports data to Google Drive, based on POST data.
	 *
	 * @see Contact_Form_Plugin::get_feedback_entries_from_post
	 */
	public function export_to_gdrive() {
		$post_data = wp_unslash( $_POST );
		if (
			! current_user_can( 'export' )
			|| empty( sanitize_text_field( $post_data[ $this->export_nonce_field_gdrive ] ) )
			|| ! wp_verify_nonce( sanitize_text_field( $post_data[ $this->export_nonce_field_gdrive ] ), 'feedback_export' )
		) {
			wp_send_json_error(
				__( 'You aren\'t authorized to do that.', 'jetpack-forms' ),
				403
			);

			return;
		}

		$grunion     = Contact_Form_Plugin::init();
		$export_data = $grunion->get_feedback_entries_from_post();

		$fields    = is_array( $export_data ) ? array_keys( $export_data ) : array();
		$row_count = ! is_array( $export_data ) || empty( $export_data ) ? 0 : count( reset( $export_data ) );

		$sheet_data = array( $fields );

		for ( $i = 0; $i < $row_count; $i++ ) {

			$current_row = array();

			/**
			 * Put all the fields in `$current_row` array.
			 */
			foreach ( $fields as $single_field_name ) {
				$current_row[] = $export_data[ $single_field_name ][ $i ];
			}

			$sheet_data[] = $current_row;
		}

		$user_id = (int) get_current_user_id();

		if ( ! empty( $post_data['post'] ) && $post_data['post'] !== 'all' ) {
			$spreadsheet_title = sprintf(
				'%1$s - %2$s',
				$this->get_export_filename( get_the_title( (int) $post_data['post'] ) ),
				gmdate( 'Y-m-d H:i' )
			);
		} else {
			$spreadsheet_title = sprintf( '%s - %s', $this->get_export_filename(), gmdate( 'Y-m-d H:i' ) );
		}

		$sheet = Google_Drive::create_sheet( $user_id, $spreadsheet_title, $sheet_data );

		$grunion->record_tracks_event( 'forms_export_responses', array( 'format' => 'gsheets' ) );

		wp_send_json(
			array(
				'success' => ! is_wp_error( $sheet ),
				'data'    => $sheet,
			)
		);
	}

	/**
	 * Return HTML markup for the CSV download button.
	 */
	public function get_csv_export_section() {
		$button_csv_html = get_submit_button(
			esc_html__( 'Download', 'jetpack-forms' ),
			'primary export-button export-csv',
			'jetpack-export-feedback-csv',
			false,
			array( 'data-nonce-name' => $this->export_nonce_field_csv )
		);
		?>
		<div class="export-card">
			<div class="export-card__header">
				<svg width="22" height="20" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path fill-rule="evenodd" clip-rule="evenodd" d="M11.2309 5.04199L10.0797 2.73945C9.98086 2.54183 9.77887 2.41699 9.55792 2.41699H2.83333C2.51117 2.41699 2.25 2.67816 2.25 3.00033V16.7087C2.25 17.0308 2.51117 17.292 2.83333 17.292H19.1667C19.4888 17.292 19.75 17.0308 19.75 16.7087V5.62533C19.75 5.30316 19.4888 5.04199 19.1667 5.04199H11.2309ZM12.3125 3.29199L11.6449 1.95683C11.2497 1.16633 10.4417 0.666992 9.55792 0.666992H2.83333C1.54467 0.666992 0.5 1.71166 0.5 3.00033V16.7087C0.5 17.9973 1.54467 19.042 2.83333 19.042H19.1667C20.4553 19.042 21.5 17.9973 21.5 16.7087V5.62533C21.5 4.33666 20.4553 3.29199 19.1667 3.29199H12.3125Z" fill="#008710"/>
				</svg>
				<div class="export-card__header-title"><?php esc_html_e( 'CSV File', 'jetpack-forms' ); ?></div>
			</div>
			<div class="export-card__body">
				<div class="export-card__body-description">
					<?php esc_html_e( 'Download your form response data via CSV file.', 'jetpack-forms' ); ?>
				</div>
				<div class="export-card__body-cta">
					<?php
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- we're literally building all this html to output it
					echo $button_csv_html;
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- we're literally building all this html to output it
					echo wp_nonce_field( 'feedback_export', $this->export_nonce_field_csv, false, false );
					?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render/output HTML markup for the export to gdrive section.
	 * If the user doesn't hold a Google Drive connection a button to connect will render (See grunion-admin.js).
	 */
	public function get_gdrive_export_section() {
		$user_connected = ( defined( 'IS_WPCOM' ) && IS_WPCOM ) || ( new Connection_Manager( 'jetpack-forms' ) )->is_user_connected( get_current_user_id() );
		if ( ! $user_connected ) {
			return;
		}

		$user_id = (int) get_current_user_id();

		$has_valid_connection = Google_Drive::has_valid_connection( $user_id );

		if ( $has_valid_connection ) {
			$button_html = $this->get_gdrive_export_button_markup();
		} else {
			$slug        = 'jetpack-form-responses-connect';
			$button_html = sprintf(
				'<a href="%1$s" id="%4$s" data-nonce-name="%5$s" class="button button-primary export-button export-gdrive" title="%2$s" rel="noopener noreferer" target="_blank">%3$s</a>',
				esc_url( Redirect::get_url( $slug ) ),
				esc_attr__( 'connect to Google Drive', 'jetpack-forms' ),
				esc_html__( 'Connect Google Drive', 'jetpack-forms' ),
				$slug,
				$this->export_nonce_field_gdrive
			);
		}

		?>
		<div class="export-card">
			<div class="export-card__header">
				<svg width="18" height="24" viewBox="0 0 18 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M11.8387 1.16016H2C1.44772 1.16016 1 1.60787 1 2.16016V21.8053V21.8376C1 22.3899 1.44772 22.8376 2 22.8376H16C16.5523 22.8376 17 22.3899 17 21.8376V5.80532M11.8387 1.16016V5.80532H17M11.8387 1.16016L17 5.80532M4.6129 13.0311V16.1279H9.25806M4.6129 13.0311V9.93435H9.25806M4.6129 13.0311H13.9032M13.9032 13.0311V9.93435H9.25806M13.9032 13.0311V16.1279H9.25806M9.25806 9.93435V16.1279" stroke="#008710" stroke-width="1.5"/>
				</svg>
				<div class="export-card__header-title"><?php esc_html_e( 'Google Sheets', 'jetpack-forms' ); ?></div>
			</div>
			<div class="export-card__body">
				<div class="export-card__body-description">
					<div>
						<?php esc_html_e( 'Export your data into a Google Sheets file.', 'jetpack-forms' ); ?>
					</div>
				</div>
				<div class="export-card__body-cta">
					<?php
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- we're literally building all this html to output it
					echo $button_html;
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- we're literally building all this html to output it
					echo wp_nonce_field( 'feedback_export', $this->export_nonce_field_gdrive, false, false );
					?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Ajax handler. Sends a payload with connection status and html to replace
	 * the Connect button with the Export button using get_gdrive_export_button
	 */
	public function test_gdrive_connection() {
		$post_data = wp_unslash( $_POST );
		$user_id   = (int) get_current_user_id();

		if (
			! $user_id ||
			! current_user_can( 'export' ) ||
			empty( sanitize_text_field( $post_data[ $this->export_nonce_field_gdrive ] ) ) ||
			! wp_verify_nonce( sanitize_text_field( $post_data[ $this->export_nonce_field_gdrive ] ), 'feedback_export' )
		) {
			wp_send_json_error(
				__( 'You aren\'t authorized to do that.', 'jetpack-forms' ),
				403
			);

			return;
		}

		$has_valid_connection = Google_Drive::has_valid_connection( $user_id );

		$replacement_html = $has_valid_connection
			? $this->get_gdrive_export_button_markup()
			: '';

		wp_send_json(
			array(
				'connection' => $has_valid_connection,
				'html'       => $replacement_html,
			)
		);
	}

	/**
	 * Markup helper so we DRY, returns the button markup for the export to GDrive feature.
	 *
	 * @return string The HTML button markup
	 */
	public function get_gdrive_export_button_markup() {
		return get_submit_button(
			esc_html__( 'Export', 'jetpack-forms' ),
			'primary export-button export-gdrive',
			'jetpack-export-feedback-gdrive',
			false,
			array( 'data-nonce-name' => $this->export_nonce_field_gdrive )
		);
	}

	/**
	 * Get a filename for export tasks
	 *
	 * @param string $source The filtered source for exported data.
	 * @return string The filename without source nor date suffix.
	 */
	public function get_export_filename( $source = '' ) {
		return $source === ''
			? sprintf(
				/* translators: Site title, used to craft the export filename, eg "MySite - Jetpack Form Responses" */
				__( '%s - Jetpack Form Responses', 'jetpack-forms' ),
				sanitize_file_name( get_bloginfo( 'name' ) )
			)
			: sprintf(
				/* translators: 1: Site title; 2: post title. Used to craft the export filename, eg "MySite - Jetpack Form Responses - Contact" */
				__( '%1$s - Jetpack Form Responses - %2$s', 'jetpack-forms' ),
				sanitize_file_name( get_bloginfo( 'name' ) ),
				sanitize_file_name( $source )
			);
	}

	/**
	 * Build contact form button.
	 *
	 * @return void
	 */
	public function grunion_media_button() {
		global $post_ID, $temp_ID, $pagenow;// phpcs:ignore WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase

		if ( 'press-this.php' === $pagenow ) {
			return;
		}

		$iframe_post_id = (int) ( 0 === $post_ID ? $temp_ID : $post_ID );// phpcs:ignore WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase
		$title          = __( 'Add Contact Form', 'jetpack-forms' );
		$site_url       = esc_url( admin_url( "/admin-ajax.php?post_id={$iframe_post_id}&action=grunion_form_builder&TB_iframe=true&width=768" ) );
		?>

		<a id="insert-jetpack-contact-form" class="button thickbox" title="<?php echo esc_attr( $title ); ?>" data-editor="content" href="<?php echo esc_attr( $site_url ); ?>&id=add_form">
			<span class="jetpack-contact-form-icon"></span> <?php echo esc_html( $title ); ?>
		</a>

		<?php
	}

	/**
	 * Display edit form view.
	 *
	 * @return never
	 */
	public function grunion_display_form_view() {
		if ( current_user_can( 'edit_posts' ) ) {
			Form_View::display();
		}
		exit( 0 );
	}

	/**
	 * Enqueue styles.
	 *
	 * @return void
	 */
	public function grunion_admin_css() {
		global $current_screen;
		if (
			$current_screen === null
			|| 'edit-feedback' !== $current_screen->id
		) {
			return;
		}

		wp_enqueue_script( 'wp-lists' );

		wp_register_style( 'grunion-admin.css', plugin_dir_url( __FILE__ ) . '/../../../dist/contact-form/css/grunion-admin.css', array(), \JETPACK__VERSION );
		wp_style_add_data( 'grunion-admin.css', 'rtl', 'replace' );

		wp_enqueue_style( 'grunion-admin.css' );
	}

	/**
	 * Enqueue scripts.
	 *
	 * @return void
	 */
	public function grunion_admin_js() {
		global $current_screen;
		if (
			$current_screen === null
			|| 'edit-feedback' !== $current_screen->id
		) {
			return;
		}

		$script = 'var __grunionPostStatusNonce = ' . wp_json_encode( wp_create_nonce( 'grunion-post-status' ) ) . ';';
		wp_add_inline_script( 'grunion-admin', $script, 'before' );
	}

	/**
	 * Hack a 'Bulk Spam' option for bulk edit in other than spam view
	 * Hack a 'Bulk Delete' option for bulk edit in spam view
	 *
	 * There isn't a better way to do this until
	 * https://core.trac.wordpress.org/changeset/17297 is resolved
	 */
	public function grunion_add_bulk_edit_option() {

		$screen = get_current_screen();

		if ( $screen === null ) {
			return;
		}

		if ( 'edit-feedback' !== $screen->id ) {
			return;
		}

		// When viewing spam we want to be able to be able to bulk delete
		// When viewing anything we want to be able to bulk move to spam
		if ( isset( $_GET['post_status'] ) && 'spam' === $_GET['post_status'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- no changes to the site, we're only rendering the option to choose bulk delete/spam.
			// Create Delete Permanently bulk item
			$option_val      = 'delete';
			$option_txt      = __( 'Delete Permanently', 'jetpack-forms' );
			$pseudo_selector = 'last-child';

		} else {
			// Create Mark Spam bulk item
			$option_val      = 'spam';
			$option_txt      = __( 'Mark as Spam', 'jetpack-forms' );
			$pseudo_selector = 'first-child';
		}

		?>
			<script type="text/javascript">
				jQuery(document).ready(function($) {
					$('#posts-filter .actions select').filter('[name=action], [name=action2]').find('option:<?php echo esc_attr( $pseudo_selector ); ?>').after('<option value="<?php echo esc_attr( $option_val ); ?>"><?php echo esc_attr( $option_txt ); ?></option>' );
				})
			</script>
		<?php
	}

	/**
	 * Handle a bulk spam report
	 */
	public function grunion_handle_bulk_spam() {
		global $pagenow;

		if ( 'edit.php' !== $pagenow
		|| ( empty( $_REQUEST['post_type'] ) || 'feedback' !== $_REQUEST['post_type'] ) ) {
			return;
		}

		// Slip in a success message
		if ( ! empty( $_REQUEST['message'] ) && 'marked-spam' === $_REQUEST['message'] ) {
			add_action( 'admin_notices', array( $this, 'grunion_message_bulk_spam' ) );
		}

		if ( ( empty( $_REQUEST['action'] ) || 'spam' !== $_REQUEST['action'] ) && ( empty( $_REQUEST['action2'] ) || 'spam' !== $_REQUEST['action2'] ) ) {
			return;
		}

		check_admin_referer( 'bulk-posts' );

		if ( empty( $_REQUEST['post'] ) ) {
			wp_safe_redirect( wp_get_referer() );
			exit( 0 );
		}

		$post_ids = array_map( 'intval', $_REQUEST['post'] );

		foreach ( $post_ids as $post_id ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				wp_die( esc_html__( 'You are not allowed to manage this item.', 'jetpack-forms' ) );
			}

			$post           = array(
				'ID'          => $post_id,
				'post_status' => 'spam',
			);
			$akismet_values = get_post_meta( $post_id, '_feedback_akismet_values', true );
			wp_update_post( $post );

			/**
			 * Fires after a comment has been marked by Akismet.
			 *
			 * Typically this means the comment is spam.
			 *
			 * @module contact-form
			 *
			 * @since 2.2.0
			 *
			 * @param string $comment_status Usually is 'spam', otherwise 'ham'.
			 * @param array $akismet_values From '_feedback_akismet_values' in comment meta
			 */
			do_action( 'contact_form_akismet', 'spam', $akismet_values );
		}

		$redirect_url = add_query_arg( 'message', 'marked-spam', wp_get_referer() );
		wp_safe_redirect( $redirect_url );
		exit( 0 );
	}

	/**
	 * Display spam message.
	 *
	 * @return void
	 */
	public function grunion_message_bulk_spam() {
		echo '<div class="updated"><p>' . esc_html__( 'Feedback(s) marked as spam', 'jetpack-forms' ) . '</p></div>';
	}

	/**
	 * Unset edit option when bulk editing.
	 *
	 * @param array $actions List of actions available.
	 * @return array $actions
	 */
	public function grunion_admin_bulk_actions( $actions ) {
		global $current_screen;
		if (
			$current_screen === null
			|| 'edit-feedback' !== $current_screen->id
		) {
			return $actions;
		}

		unset( $actions['edit'] );
		return $actions;
	}

	/**
	 * Unset publish button when editing feedback.
	 *
	 * @param array $views List of post views.
	 * @return array $views
	 */
	public function grunion_admin_view_tabs( $views ) {
		global $current_screen;
		if (
			$current_screen === null
			|| 'edit-feedback' !== $current_screen->id
		) {
			return $views;
		}

		unset( $views['publish'] );

		preg_match( '|post_type=feedback\'( class="current")?\>(.*)\<span class=|', $views['all'], $match );
		if ( ! empty( $match[2] ) ) {
			$views['all'] = str_replace( $match[2], __( 'Messages', 'jetpack-forms' ) . ' ', $views['all'] );
		}

		return $views;
	}

	/**
	 * Build Feedback admin page columns.
	 *
	 * @param array $cols List of available columns.
	 * @return array
	 */
	public function grunion_post_type_columns_filter( $cols ) { // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
		return array(
			'cb'                => '<input type="checkbox" />',
			'feedback_from'     => __( 'From', 'jetpack-forms' ),
			'feedback_source'   => __( 'Source', 'jetpack-forms' ),
			'feedback_date'     => __( 'Date', 'jetpack-forms' ),
			'feedback_response' => __( 'Response Data', 'jetpack-forms' ),
		);
	}

	/**
	 * Displays the value for the source column. (This function runs within the loop.)
	 *
	 * @return void
	 */
	public function grunion_manage_post_column_date() {
		echo esc_html( date_i18n( 'Y/m/d', get_the_time( 'U' ) ) );
	}

	/**
	 * Displays the value for the from column.
	 *
	 * @param  \WP_Post $post Current post.
	 * @return void
	 */
	public function grunion_manage_post_column_from( $post ) {
		$content_fields = Contact_Form_Plugin::parse_fields_from_content( $post->ID );

		if ( ! empty( $content_fields['_feedback_author'] ) ) {
			echo esc_html( $content_fields['_feedback_author'] );
			return;
		}

		if ( ! empty( $content_fields['_feedback_author_email'] ) ) {
			printf(
				"<a href='%1\$s' target='_blank'>%2\$s</a><br />",
				esc_url( 'mailto:' . $content_fields['_feedback_author_email'] ),
				esc_html( $content_fields['_feedback_author_email'] )
			);
			return;
		}

		if ( ! empty( $content_fields['_feedback_ip'] ) ) {
			echo esc_html( $content_fields['_feedback_ip'] );
			return;
		}

		echo esc_html__( 'Unknown', 'jetpack-forms' );
	}

	/**
	 * Displays the value for the response column.
	 *
	 * @param  \WP_Post $post Current post.
	 * @return void
	 */
	public function grunion_manage_post_column_response( $post ) {

		$post_content = get_post_field( 'post_content', $post->ID );
		$content      = explode( '<!--more-->', $post_content );
		$content      = str_ireplace( array( '<br />', ')</p>' ), '', $content[1] );
		$chunks       = explode( "\nJSON_DATA", $content );
		// Get content fields.
		$content_fields = Contact_Form_Plugin::parse_fields_from_content( $post->ID );

		if ( empty( $content_fields ) ) {
			return;
		}

		$chunks = explode( "\nArray", $content );
		if ( ! empty( $chunks[1] ) ) {
			// re-construct the array string
			$array = 'Array' . $chunks[1];
			// re-construct the array
			$rearray         = Contact_Form_Plugin::reverse_that_print( $array, true );
			$response_fields = is_array( $rearray ) ? $rearray : array();
		} else {
			// couldn't reconstruct array, use the old method
			$content_fields  = Contact_Form_Plugin::parse_fields_from_content( $post->ID );
			$response_fields = isset( $content_fields['_feedback_all_fields'] ) ? $content_fields['_feedback_all_fields'] : array();
		}

		// Extract IP address if we still do not have it at this point.
		if (
			! isset( $content_fields['_feedback_ip'] )
			&& ! empty( $chunks[0] )
		) {
			preg_match( '/^IP: (.+)$/m', $chunks[0], $matches );
			if ( ! empty( $matches[1] ) ) {
				$content_fields['_feedback_ip'] = $matches[1];
			}
		}

		$url = get_permalink( $post->post_parent );
		if ( isset( $response_fields['entry_page'] ) ) {
			$url = add_query_arg( 'page', $response_fields['entry_page'], $url );
		}

		$response_fields = array_diff_key( $response_fields, array_flip( array_keys( Contact_Form_Plugin::NON_PRINTABLE_FIELDS ) ) );

		echo '<hr class="feedback_response__mobile-separator" />';
		echo '<div class="feedback_response__item">';

		foreach ( $response_fields as $key => $display_value ) {
			if ( Contact_Form::is_file_upload_field( $display_value ) ) {
				printf(
					'<div class="feedback_response__item-key">%s</div><div class="feedback_response__item-value"><div>',
					esc_html( preg_replace( '#^\d+_#', '', $key ) )
				);

				// Get the files array from the new structure
				$files = $display_value['files'];

				foreach ( $files as $file_data ) {
					// If we have a valid URL, show the file link with additional details
					$file_name = isset( $file_data['name'] ) ? $file_data['name'] : __( 'Attached file', 'jetpack-forms' );
					$file_size = isset( $file_data['size'] ) ? size_format( $file_data['size'] ) : '';
					$file_id   = absint( $file_data['file_id'] );
					$file_url  = \apply_filters( 'jetpack_unauth_file_download_url', '', $file_id );
					$file_info = empty( $file_size ) ? $file_name : $file_name . ' (' . $file_size . ')';

					printf(
						'<div><a href="%s" target="_blank">%s</a></div>',
						esc_url( $file_url ),
						esc_html( $file_info )
					);
				}

				echo '</div></div>';
				continue;
			} elseif ( is_array( $display_value ) ) {
				// Regular array, format it nicely for display
				$display_value = Contact_Form_Plugin::format_value_for_display( $display_value );
			}

			printf(
				'<div class="feedback_response__item-key">%s</div><div class="feedback_response__item-value">%s</div>',
				esc_html( preg_replace( '#^\d+_#', '', $key ) ),
				nl2br( esc_html( $display_value ) )
			);
		}

		echo '</div>';
		echo '<hr />';

		echo '<div class="feedback_response__item">';
		if ( ! empty( $content_fields['_feedback_ip'] ) ) {
			echo '<div class="feedback_response__item-key">' . esc_html__( 'IP', 'jetpack-forms' ) . '</div>';
			echo '<div class="feedback_response__item-value">' . esc_html( $content_fields['_feedback_ip'] ) . '</div>';
		}
		echo '<div class="feedback_response__item-key">' . esc_html__( 'Source', 'jetpack-forms' ) . '</div>';
		echo '<div class="feedback_response__item-value"><a href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer">' . esc_html( $url ) . '</a></div>';
		echo '</div>';
	}

	/**
	 * Displays the value for the source column.
	 *
	 * @param  \WP_Post $post Current post.
	 * @return void
	 */
	public function grunion_manage_post_column_source( $post ) {
		if ( ! isset( $post->post_parent ) ) {
			return;
		}

		$form_url   = get_permalink( $post->post_parent );
		$parsed_url = wp_parse_url( $form_url );

		printf(
			'<a href="%s" target="_blank" rel="noopener noreferrer">/%s</a>',
			esc_url( $form_url ),
			esc_html( basename( $parsed_url['path'] ) )
		);
	}

	/**
	 * Parse message content and display in appropriate columns.
	 *
	 * @param array $col List of columns available on admin page.
	 * @param int   $post_id The current post ID.
	 * @return void
	 */
	public function grunion_manage_post_columns( $col, $post_id ) { // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
		global $post;

		/**
		 * Only call parse_fields_from_content if we're dealing with a Grunion custom column.
		 */
		if ( ! in_array( $col, array( 'feedback_date', 'feedback_from', 'feedback_response', 'feedback_source' ), true ) ) {
			return;
		}

		switch ( $col ) {
			case 'feedback_date':
				$this->grunion_manage_post_column_date();
				return;
			case 'feedback_from':
				$this->grunion_manage_post_column_from( $post );
				return;
			case 'feedback_response':
				$this->grunion_manage_post_column_response( $post );
				return;
			case 'feedback_source':
				$this->grunion_manage_post_column_source( $post );
				return;
		}
	}

	/**
	 * Add a post filter dropdown at the top of the admin page.
	 *
	 * @return void
	 */
	public function grunion_source_filter() {
		$screen = get_current_screen();

		if ( 'edit-feedback' !== $screen->id ) {
			return;
		}

		$parent_id = intval( isset( $_GET['jetpack_form_parent_id'] ) ? $_GET['jetpack_form_parent_id'] : 0 ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		Contact_Form_Plugin::form_posts_dropdown( $parent_id );
	}

	/**
	 * Filter feedback posts by parent_id if present.
	 *
	 * @param \WP_Query $query Current query.
	 *
	 * @return void
	 */
	public function grunion_source_filter_results( $query ) {
		$parent_id = intval( isset( $_GET['jetpack_form_parent_id'] ) ? $_GET['jetpack_form_parent_id'] : 0 ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		if ( ! $parent_id || $query->query_vars['post_type'] !== 'feedback' ) {
			return;
		}

		/**
		 * In the wp-admin list we perform two queries that trigger the `pre_get_posts` hook.
		 * One is for the main list and the other is for the `source` dropdown filter.
		 * We need to explicitly check one unique parameter between the two queries to avoid
		 * filtering the dropdown query. The dropdown query is in `get_all_parent_post_ids`.
		 */
		if ( $query->query_vars['posts_per_page'] === 100000 ) {
			return;
		}

		$query->query_vars['post_parent'] = $parent_id;
	}

	/**
	 * Add actions to feedback response rows in WP Admin.
	 *
	 * @param string[] $actions Default actions.
	 * @return string[]
	 */
	public function grunion_manage_post_row_actions( $actions ) {
		global $post;

		if ( 'feedback' !== $post->post_type ) {
			return $actions;
		}

		$post_type_object = get_post_type_object( $post->post_type );
		$actions          = array();

		if ( $post->post_status === 'trash' ) {
			$actions['untrash'] = sprintf(
				'<a title="%s" href="%s">%s</a>',
				esc_attr__( 'Restore this item from the Trash', 'jetpack-forms' ),
				esc_url( wp_nonce_url( admin_url( sprintf( $post_type_object->_edit_link . '&action=untrash', rawurlencode( $post->ID ) ) ) ), 'untrash-' . $post->post_type . '_' . $post->ID ),
				esc_html__( 'Restore', 'jetpack-forms' )
			);
			$actions['delete']  = sprintf(
				'<a class="submitdelete" title="%s" href="%s">%s</a>',
				esc_attr( __( 'Delete this item permanently', 'jetpack-forms' ) ),
				get_delete_post_link( $post->ID, '', true ),
				esc_html__( 'Delete Permanently', 'jetpack-forms' )
			);
		} elseif ( $post->post_status === 'publish' ) {
			$actions['spam']  = sprintf(
				'<a title="%s" href="%s">%s</a>',
				esc_html__( 'Mark this message as spam', 'jetpack-forms' ),
				esc_url( wp_nonce_url( admin_url( 'admin-ajax.php?post_id=' . rawurlencode( $post->ID ) . '&action=spam' ) ), 'spam-feedback_' . $post->ID ),
				esc_html__( 'Spam', 'jetpack-forms' )
			);
			$actions['trash'] = sprintf(
				'<a class="submitdelete" title="%s" href="%s">%s</a>',
				esc_attr_x( 'Trash', 'verb', 'jetpack-forms' ),
				get_delete_post_link( $post->ID ),
				esc_html_x( 'Trash', 'verb', 'jetpack-forms' )
			);
		} elseif ( $post->post_status === 'spam' ) {
			$actions['unspam unapprove'] = sprintf(
				'<a title="%s" href="">%s</a>',
				esc_html__( 'Mark this message as NOT spam', 'jetpack-forms' ),
				esc_html__( 'Not Spam', 'jetpack-forms' )
			);
			$actions['delete']           = sprintf(
				'<a class="submitdelete" title="%s" href="%s">%s</a>',
				esc_attr( __( 'Delete this item permanently', 'jetpack-forms' ) ),
				get_delete_post_link( $post->ID, '', true ),
				esc_html__( 'Delete Permanently', 'jetpack-forms' )
			);
		}

		return $actions;
	}

	/**
	 * Escape grunion attributes.
	 *
	 * @param string $attr - the attribute we're escaping.
	 *
	 * @return string
	 */
	public function grunion_esc_attr( $attr ) {
		$out = esc_attr( $attr );
		// we also have to entity-encode square brackets so they don't interfere with the shortcode parser
		// FIXME: do this better - just stripping out square brackets for now since they mysteriously keep reappearing
		$out = str_replace( '[', '', $out );
		$out = str_replace( ']', '', $out );
		return $out;
	}

	/**
	 * Sort grunion items.
	 *
	 * @param array $a - the first item we're sorting.
	 * @param array $b - the second item we're sorting.
	 *
	 * @return string
	 */
	public function grunion_sort_objects( $a, $b ) {
		if ( isset( $a['order'] ) && isset( $b['order'] ) ) {
			return $a['order'] <=> $b['order'];
		}
		return 0;
	}

	/**
	 * Take an array of field types from the form builder, and construct a shortcode form.
	 * returns both the shortcode form, and HTML markup representing a preview of the form
	 *
	 * @return never
	 */
	public function grunion_ajax_shortcode() {
		check_ajax_referer( 'grunion_shortcode' );

		if ( ! current_user_can( 'edit_posts' ) ) {
			die( '-1' );
		}

		$attributes = array();

		foreach ( array( 'subject', 'to' ) as $attribute ) {
			if ( isset( $_POST[ $attribute ] ) && is_scalar( $_POST[ $attribute ] ) && (string) $_POST[ $attribute ] !== '' ) {
				$attributes[ $attribute ] = sanitize_text_field( wp_unslash( $_POST[ $attribute ] ) );
			}
		}

		$field_shortcodes = array();

		if ( isset( $_POST['fields'] ) && is_array( $_POST['fields'] ) ) {
			$fields = array_map(
				function ( $field ) {
					if ( is_array( $field ) ) {

						foreach ( array( 'label', 'type', 'required' ) as $key ) {
							if ( isset( $field[ $key ] ) ) {
								$field[ $key ] = sanitize_text_field( wp_unslash( $field[ $key ] ) );
							}
						}

						if ( isset( $field['options'] ) && is_array( $field['options'] ) ) {
							$field['options'] = array_map( 'sanitize_text_field', array_map( 'wp_unslash', $field['options'] ) );
						}
					}
					return $field;
				},
				$_POST['fields'] // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- each item sanitized above.
			);
			usort( $fields, array( $this, 'grunion_sort_objects' ) );

			foreach ( $fields as $field ) {
				$field_attributes = array();

				if ( isset( $field['required'] ) && 'true' === $field['required'] ) {
					$field_attributes['required'] = 'true';
				}

				foreach ( array( 'options', 'label', 'type' ) as $attribute ) {
					if ( isset( $field[ $attribute ] ) ) {
						$field_attributes[ $attribute ] = $field[ $attribute ];
					}
				}

				$field_shortcodes[] = new Contact_Form_Field( $field_attributes );
			}
		}

		$grunion = new Contact_Form( $attributes, $field_shortcodes );

		die( "\n$grunion\n" ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Takes a post_id, extracts the contact-form shortcode from that post (if there is one), parses it,
	 * and constructs a json object representing its contents and attributes.
	 *
	 * @return never
	 */
	public function grunion_ajax_shortcode_to_json() {
		global $post;

		check_ajax_referer( 'grunion_shortcode_to_json' );

		if ( ! empty( $_POST['post_id'] ) && ! current_user_can( 'edit_post', (int) $_POST['post_id'] ) ) {
			die( '-1' );
		} elseif ( ! current_user_can( 'edit_posts' ) ) {
			die( '-1' );
		}

		if ( ! isset( $_POST['content'] ) || ! is_numeric( $_POST['post_id'] ) ) {
			die( '-1' );
		}

		$content = sanitize_text_field( wp_unslash( $_POST['content'] ) );

		// doesn't look like a post with a [contact-form] already.
		if ( false === has_shortcode( $content, 'contact-form' ) ) {
			die( '' );
		}

		$post = get_post( (int) $_POST['post_id'] ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

		do_shortcode( $content );

		$grunion = Contact_Form::$last;

		$out = array(
			'to'      => '',
			'subject' => '',
			'fields'  => array(),
		);

		foreach ( $grunion->fields as $field ) {
			$out['fields'][ $field->get_attribute( 'id' ) ] = $field->attributes;
		}

		foreach ( array( 'to', 'subject' ) as $attribute ) {
			$value = $grunion->get_attribute( $attribute );
			if ( isset( $grunion->defaults[ $attribute ] ) && $value === $grunion->defaults[ $attribute ] ) {
				$value = '';
			}
			$out[ $attribute ] = $value;
		}

		die( wp_json_encode( $out ) );
	}

	/**
	 * Handle marking feedback as spam.
	 */
	public function grunion_ajax_spam() {
		global $wpdb;

		if ( empty( $_POST['make_it'] ) ) {
			return;
		}

		$post_id = isset( $_POST['post_id'] ) ? (int) $_POST['post_id'] : 0;
		check_ajax_referer( 'grunion-post-status' );
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			wp_die( esc_html__( 'You are not allowed to manage this item.', 'jetpack-forms' ) );
		}

		// init will construct/get the instance and make sure all the filters and actions
		// are in place for this process to go through
		Contact_Form_Plugin::init();

		$current_menu = '';
		if ( isset( $_POST['sub_menu'] ) && preg_match( '|post_type=feedback|', sanitize_text_field( wp_unslash( $_POST['sub_menu'] ) ) ) ) {
			if ( preg_match( '|post_status=spam|', sanitize_text_field( wp_unslash( $_POST['sub_menu'] ) ) ) ) {
				$current_menu = 'spam';
			} elseif ( preg_match( '|post_status=trash|', sanitize_text_field( wp_unslash( $_POST['sub_menu'] ) ) ) ) {
				$current_menu = 'trash';
			} else {
				$current_menu = 'messages';
			}
		}

		$post             = get_post( $post_id );
		$post_type_object = get_post_type_object( $post->post_type );
		$akismet_values   = get_post_meta( $post_id, '_feedback_akismet_values', true );
		if ( $_POST['make_it'] === 'spam' ) {

			$status = wp_update_post(
				array(
					'ID'          => $post_id,
					'post_status' => 'spam',
				)
			);

			/** This action is already documented in \Automattic\Jetpack\Forms\ContactForm\Admin */
			do_action( 'contact_form_akismet', 'spam', $akismet_values );
		} elseif ( $_POST['make_it'] === 'ham' ) {
			$status = wp_update_post(
				array(
					'ID'          => $post_id,
					'post_status' => 'publish',
				)
			);

			/** This action is already documented in \Automattic\Jetpack\Forms\ContactForm\Admin */
			do_action( 'contact_form_akismet', 'ham', $akismet_values );

			$comment_author_email = false;
			$reply_to_addr        = false;
			$message              = false;
			$to                   = false;
			$headers              = false;
			$blog_url             = wp_parse_url( site_url() );

			// resend the original email
			$email          = get_post_meta( $post_id, '_feedback_email', true );
			$content_fields = Contact_Form_Plugin::parse_fields_from_content( $post_id );

			if ( ! empty( $email ) && ! empty( $content_fields ) ) {
				if ( isset( $content_fields['_feedback_author_email'] ) ) {
					$comment_author_email = $content_fields['_feedback_author_email'];
				}

				if ( isset( $email['to'] ) ) {
					$to = $email['to'];
				}

				if ( isset( $email['message'] ) ) {
					$message = $email['message'];
				}

				if ( isset( $email['headers'] ) ) {
					$headers = $email['headers'];
				} else {
					$headers = 'From: "' . $content_fields['_feedback_author'] . '" <wordpress@' . $blog_url['host'] . ">\r\n";

					if ( ! empty( $comment_author_email ) ) {
						$reply_to_addr = $comment_author_email;
					} elseif ( is_array( $to ) ) {
						$reply_to_addr = $to[0];
					}

					if ( $reply_to_addr ) {
						$headers .= 'Reply-To: "' . $content_fields['_feedback_author'] . '" <' . $reply_to_addr . ">\r\n";
					}

					$headers .= 'Content-Type: text/plain; charset="' . get_option( 'blog_charset' ) . '"';
				}

				/**
				 * Filters the subject of the email sent after a contact form submission.
				 *
				 * @module contact-form
				 *
				 * @since 3.0.0
				 *
				 * @param string $content_fields['_feedback_subject'] Feedback's subject line.
				 * @param array $content_fields['_feedback_all_fields'] Feedback's data from old fields.
				 */
				$subject = apply_filters( 'contact_form_subject', $content_fields['_feedback_subject'], $content_fields['_feedback_all_fields'] );

				Contact_Form::wp_mail( $to, $subject, $message, $headers );
			}
		} elseif ( $_POST['make_it'] === 'publish' ) {
			if ( ! current_user_can( $post_type_object->cap->delete_post, $post_id ) ) {
				wp_die( esc_html__( 'You are not allowed to move this item out of the Trash.', 'jetpack-forms' ) );
			}

			if ( ! wp_untrash_post( $post_id ) ) {
				wp_die( esc_html__( 'Error in restoring from Trash.', 'jetpack-forms' ) );
			}
		} elseif ( $_POST['make_it'] === 'trash' ) {
			if ( ! current_user_can( $post_type_object->cap->delete_post, $post_id ) ) {
				wp_die( esc_html__( 'You are not allowed to move this item to the Trash.', 'jetpack-forms' ) );
			}

			if ( ! wp_trash_post( $post_id ) ) {
				wp_die( esc_html__( 'Error in moving to Trash.', 'jetpack-forms' ) );
			}
		} elseif ( $_POST['make_it'] === 'delete' ) {
			if ( ! current_user_can( $post_type_object->cap->delete_post, $post_id ) ) {
				wp_die( esc_html__( 'You are not allowed to move this item to the Trash.', 'jetpack-forms' ) );
			}

			if ( ! wp_delete_post( $post_id, true ) ) {
				wp_die( esc_html__( 'Error in deleting post.', 'jetpack-forms' ) );
			}
		}

		$sql          = "
			SELECT post_status,
				COUNT( * ) AS post_count
			FROM `{$wpdb->posts}`
			WHERE post_type =  'feedback'
			GROUP BY post_status
		";
		$status_count = (array) $wpdb->get_results( $sql, ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching

		$status      = array();
		$status_html = '';
		foreach ( $status_count as $row ) {
			$status[ $row['post_status'] ] = $row['post_count'];
		}

		if ( isset( $status['publish'] ) ) {
			$status_html .= '<li><a href="edit.php?post_type=feedback"';
			if ( $current_menu === 'messages' ) {
				$status_html .= ' class="current"';
			}

			$status_html .= '>' . __( 'Messages', 'jetpack-forms' ) . ' <span class="count">';
			$status_html .= '(' . number_format( $status['publish'] ) . ')';
			$status_html .= '</span></a> |</li>';
		}

		if ( isset( $status['trash'] ) ) {
			$status_html .= '<li><a href="edit.php?post_status=trash&amp;post_type=feedback"';
			if ( $current_menu === 'trash' ) {
				$status_html .= ' class="current"';
			}

			$status_html .= '>' . _x( 'Trash', 'noun', 'jetpack-forms' ) . ' <span class="count">';
			$status_html .= '(' . number_format( $status['trash'] ) . ')';
			$status_html .= '</span></a>';
			if ( isset( $status['spam'] ) ) {
				$status_html .= ' |';
			}
			$status_html .= '</li>';
		}

		if ( isset( $status['spam'] ) ) {
			$status_html .= '<li><a href="edit.php?post_status=spam&amp;post_type=feedback"';
			if ( $current_menu === 'spam' ) {
				$status_html .= ' class="current"';
			}

			$status_html .= '>' . __( 'Spam', 'jetpack-forms' ) . ' <span class="count">';
			$status_html .= '(' . number_format( $status['spam'] ) . ')';
			$status_html .= '</span></a></li>';
		}

		echo $status_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- we're building the html to echo.
		exit( 0 );
	}

	/**
	 * Add the scripts that will add the "Check for Spam" button to the Feedbacks dashboard page.
	 */
	public function grunion_enable_spam_recheck() {
		if ( ! defined( 'AKISMET_VERSION' ) ) {
			return;
		}

		$screen = get_current_screen();

		// Only add to feedback, only to non-spam view
		if ( 'edit-feedback' !== $screen->id || ( ! empty( $_GET['post_status'] ) && 'spam' === $_GET['post_status'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- not making site changes with this check.
			return;
		}

		// Add the actual "Check for Spam" button.
		add_action( 'admin_head', array( $this, 'grunion_check_for_spam_button' ) );
	}

	/**
	 * Add the JS and CSS necessary for the Feedback admin page to function.
	 */
	public function grunion_add_admin_scripts() {
		$screen = get_current_screen();

		if ( 'edit-feedback' !== $screen->id ) {
			return;
		}

		// Add the scripts that handle the spam check event.
		Assets::register_script(
			'grunion-admin',
			'../../dist/contact-form/js/grunion-admin.js',
			__FILE__,
			array(
				'enqueue'      => true,
				'dependencies' => array( 'jquery' ),
				'version'      => \JETPACK__VERSION,
				'in_footer'    => true,
			)
		);

		if ( Contact_Form_Plugin::can_use_analytics() ) {
			Tracking::register_tracks_functions_scripts( true );

			wp_localize_script(
				'grunion-admin',
				'jetpack_forms_tracking',
				array(
					'tracksUserData' => Jetpack_Tracks_Client::get_connected_user_tracks_identity(),
				)
			);
		}

		wp_enqueue_style( 'grunion.css' );

		// Only add to feedback, only to spam view.
		if ( empty( $_GET['post_status'] ) || 'spam' !== $_GET['post_status'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- not making site changes with this check
			return;
		}

		$feedbacks_count = wp_count_posts( 'feedback' );
		$nonce           = wp_create_nonce( 'jetpack_delete_spam_feedbacks' );
		$success_url     = remove_query_arg( array( 'jetpack_empty_feedback_spam_error', 'post_status' ) ); // Go to the "All Feedback" page.
		$failure_url     = add_query_arg( 'jetpack_empty_feedback_spam_error', '1' ); // Refresh the current page and show an error.
		$spam_count      = $feedbacks_count->spam;

		$button_parameters = array(
			/* translators: The placeholder is for showing how much of the process has completed, as a percent. e.g., "Emptying Spam (40%)" */
			'progress_label' => __( 'Emptying Spam (%1$s%%)', 'jetpack-forms' ),
			'success_url'    => $success_url,
			'failure_url'    => $failure_url,
			'spam_count'     => $spam_count,
			'nonce'          => $nonce,
			'label'          => __( 'Empty Spam', 'jetpack-forms' ),
		);

		wp_localize_script( 'grunion-admin', 'jetpack_empty_spam_button_parameters', $button_parameters );
	}

	/**
	 * Adds the 'Export' button to the feedback dashboard page.
	 *
	 * @return void
	 */
	public function grunion_export_button() {
		$current_screen = get_current_screen();
		if ( ! in_array( $current_screen->id, array( 'edit-feedback', 'feedback_page_feedback-export' ), true ) ) {
			return;
		}

		if ( ! current_user_can( 'export' ) ) {
			return;
		}

		// if there aren't any feedbacks, bail out
		if ( ! (int) wp_count_posts( 'feedback' )->publish ) {
			return;
		}

		$nonce_name = 'feedback_export_nonce';

		$button_html = get_submit_button(
			__( 'Export', 'jetpack-forms' ),
			'primary',
			'jetpack-export-feedback',
			false,
			array(
				'data-nonce-name' => $nonce_name,
			)
		);

		$button_html .= wp_nonce_field( 'feedback_export', $nonce_name, false, false );
		?>
		<script type="text/javascript">
			jQuery( function ( $ ) {
				$( '#posts-filter #post-query-submit' ).after( <?php echo wp_json_encode( $button_html ); ?> );
			} );
		</script>
		<?php
	}

	/**
	 * Add the "Check for Spam" button to the Feedbacks dashboard page.
	 */
	public function grunion_check_for_spam_button() {
		// Nonce name.
		$nonce_name = 'jetpack_check_feedback_spam_' . (string) get_current_blog_id();
		// Get HTML for the button.
		$button_html  = get_submit_button(
			__( 'Check for Spam', 'jetpack-forms' ),
			'secondary',
			'jetpack-check-feedback-spam',
			false,
			array(
				'data-failure-url' => add_query_arg( 'jetpack_check_feedback_spam_error', '1' ), // Refresh the current page and show an error.
				'data-nonce-name'  => $nonce_name,
			)
		);
		$button_html .= '<span class="jetpack-check-feedback-spam-spinner"></span>';
		$button_html .= wp_nonce_field( 'grunion_recheck_queue', $nonce_name, false, false );

		// Add the button next to the filter button via js.
		?>
		<script type="text/javascript">
			jQuery( function( $ ) {
				$( '.tablenav.bottom .bulkactions' ).append( <?php echo wp_json_encode( $button_html ); ?> );
			} );
		</script>
		<?php
	}

	/**
	 * Recheck all approved feedbacks for spam.
	 */
	public function grunion_recheck_queue() {
		$blog_id = get_current_blog_id();

		if (
			empty( $_POST[ 'jetpack_check_feedback_spam_' . (string) $blog_id ] )
			|| ! wp_verify_nonce( sanitize_key( $_POST[ 'jetpack_check_feedback_spam_' . (string) $blog_id ] ), 'grunion_recheck_queue' )
		) {
			wp_send_json_error(
				__( 'You aren\'t authorized to do that.', 'jetpack-forms' ),
				403
			);

			return;
		}

		if ( ! current_user_can( 'delete_others_posts' ) ) {
			wp_send_json_error(
				__( 'You don\'t have permission to do that.', 'jetpack-forms' ),
				403
			);

			return;
		}

		$query = 'post_type=feedback&post_status=publish';

		if ( isset( $_POST['limit'] ) && isset( $_POST['offset'] ) ) {
			$query .= '&posts_per_page=' . (int) $_POST['limit'] . '&offset=' . (int) $_POST['offset'];
		}

		$approved_feedbacks = get_posts( $query );

		foreach ( $approved_feedbacks as $feedback ) {
			$meta = get_post_meta( $feedback->ID, '_feedback_akismet_values', true );

			if ( ! $meta ) {
				// _feedback_akismet_values is eventually deleted when it's no longer
				// within a reasonable time period to check the feedback for spam, so
				// if it's gone, don't attempt a spam recheck.
				continue;
			}

			$meta['recheck_reason'] = 'recheck_queue';

			/**
			 * Filter whether the submitted feedback is considered as spam.
			 *
			 * @module contact-form
			 *
			 * @since 3.4.0
			 *
			 * @param bool false Is the submitted feedback spam? Default to false.
			 * @param array $meta Feedack values returned by the Akismet plugin.
			 */
			$is_spam = apply_filters( 'jetpack_contact_form_is_spam', false, $meta );

			if ( $is_spam ) {
				wp_update_post(
					array(
						'ID'          => $feedback->ID,
						'post_status' => 'spam',
					)
				);
				/** This action is already documented in \Automattic\Jetpack\Forms\ContactForm\Admin */
				do_action( 'contact_form_akismet', 'spam', $meta );
			}
		}

		wp_send_json(
			array(
				'processed' => count( $approved_feedbacks ),
			)
		);
	}

	/**
	 * Delete a number of spam feedbacks via an AJAX request.
	 */
	public function grunion_delete_spam_feedbacks() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'jetpack_delete_spam_feedbacks' ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- core doesn't sanitize nonce checks either.
			wp_send_json_error(
				__( 'You aren\'t authorized to do that.', 'jetpack-forms' ),
				403
			);

			return;
		}

		if ( ! current_user_can( 'delete_others_posts' ) ) {
			wp_send_json_error(
				__( 'You don\'t have permission to do that.', 'jetpack-forms' ),
				403
			);

			return;
		}

		$deleted_feedbacks = 0;

		$delete_limit = 25;
		/**
		 * Filter the amount of Spam feedback one can delete at once.
		 *
		 * @module contact-form
		 *
		 * @since 8.7.0
		 *
		 * @param int $delete_limit Number of spam to process at once. Default to 25.
		 */
		$delete_limit = apply_filters( 'jetpack_delete_spam_feedbacks_limit', $delete_limit );
		$delete_limit = (int) $delete_limit;
		$delete_limit = max( 1, min( 100, $delete_limit ) ); // Allow a range of 1-100 for the delete limit.

		$query_args = array(
			'post_type'      => 'feedback',
			'post_status'    => 'spam',
			'posts_per_page' => $delete_limit,
		);

		$query          = new \WP_Query( $query_args );
		$spam_feedbacks = $query->get_posts();

		foreach ( $spam_feedbacks as $feedback ) {
			wp_delete_post( $feedback->ID, true );

			++$deleted_feedbacks;
		}

		wp_send_json(
			array(
				'success' => true,
				'data'    => array(
					'counts' => array(
						'deleted' => $deleted_feedbacks,
						'limit'   => $delete_limit,
					),
				),
			)
		);
	}

	/**
	 * Show an admin notice if the "Empty Spam" or "Check Spam" process was unable to complete, probably due to a permissions error.
	 */
	public function grunion_feedback_admin_notice() {
		$message = '';

		if ( isset( $_GET['jetpack_empty_feedback_spam_error'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$message = esc_html__( 'An error occurred while trying to empty the Feedback spam folder.', 'jetpack-forms' );
		} elseif ( isset( $_GET['jetpack_check_feedback_spam_error'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$message = esc_html__( 'An error occurred while trying to check for spam among the feedback you received.', 'jetpack-forms' );
		}

		if ( empty( $message ) ) {
			return;
		}

		wp_admin_notice(
			$message,
			array(
				'type' => 'error',
			)
		);
	}
}
