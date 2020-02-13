<?php
/**
 * Astra Updates
 *
 * Functions for updating data, used by the background updater.
 *
 * @package Astra
 * @version 2.1.3
 */

defined( 'ABSPATH' ) || exit;

/**
 * Open Submenu just below menu for existing users.
 *
 * @since 2.1.3
 *
 * @return void
 */
function astra_submenu_below_header() {
	$theme_options = get_option( 'astra-settings' );

	// Set flag to use flex align center css to open submenu just below menu.
	if ( ! isset( $theme_options['submenu-open-below-header'] ) ) {
		$theme_options['submenu-open-below-header'] = false;
		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * Do not apply new default colors to the Elementor & Gutenberg Buttons for existing users.
 *
 * @since 2.2.0
 *
 * @return void
 */
function astra_page_builder_button_color_compatibility() {
	$theme_options = get_option( 'astra-settings', array() );

	// Set flag to not load button specific CSS.
	if ( ! isset( $theme_options['pb-button-color-compatibility'] ) ) {
		$theme_options['pb-button-color-compatibility'] = false;
		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * Migrate option data from button vertical & horizontal padding to the new responsive padding param.
 *
 * @since 2.2.0
 *
 * @return void
 */
function astra_vertical_horizontal_padding_migration() {
	$theme_options = get_option( 'astra-settings', array() );

	$btn_vertical_padding   = isset( $theme_options['button-v-padding'] ) ? $theme_options['button-v-padding'] : 10;
	$btn_horizontal_padding = isset( $theme_options['button-h-padding'] ) ? $theme_options['button-h-padding'] : 40;

	if ( false === astra_get_db_option( 'theme-button-padding', false ) ) {

		error_log( sprintf( 'Astra: Migrating vertical Padding - %s', $btn_vertical_padding ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
		error_log( sprintf( 'Astra: Migrating horizontal Padding - %s', $btn_horizontal_padding ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
		// Migrate button vertical padding to the new padding param for button.
		$theme_options['theme-button-padding'] = array(
			'desktop'      => array(
				'top'    => $btn_vertical_padding,
				'right'  => $btn_horizontal_padding,
				'bottom' => $btn_vertical_padding,
				'left'   => $btn_horizontal_padding,
			),
			'tablet'       => array(
				'top'    => '',
				'right'  => '',
				'bottom' => '',
				'left'   => '',
			),
			'mobile'       => array(
				'top'    => '',
				'right'  => '',
				'bottom' => '',
				'left'   => '',
			),
			'desktop-unit' => 'px',
			'tablet-unit'  => 'px',
			'mobile-unit'  => 'px',
		);

		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * Migrate option data from button url to the new link param.
 *
 * @since 2.3.0
 *
 * @return void
 */
function astra_header_button_new_options() {

	$theme_options = get_option( 'astra-settings', array() );

	$btn_url = isset( $theme_options['header-main-rt-section-button-link'] ) ? $theme_options['header-main-rt-section-button-link'] : 'https://www.wpastra.com';
	error_log( 'Astra: Migrating button url - ' . $btn_url ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
	$theme_options['header-main-rt-section-button-link-option'] = array(
		'url'      => $btn_url,
		'new_tab'  => false,
		'link_rel' => '',
	);

	update_option( 'astra-settings', $theme_options );

}
