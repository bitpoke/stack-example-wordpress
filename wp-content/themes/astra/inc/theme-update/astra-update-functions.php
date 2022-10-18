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
	/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
	if ( false === astra_get_db_option( 'theme-button-padding', false ) ) {

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
	$theme_options['header-main-rt-section-button-link-option'] = array(
		'url'      => $btn_url,
		'new_tab'  => false,
		'link_rel' => '',
	);

	update_option( 'astra-settings', $theme_options );
}

/**
 * For existing users, do not provide Elementor Default Color Typo settings compatibility by default.
 *
 * @since 2.3.3
 *
 * @return void
 */
function astra_elementor_default_color_typo_comp() {

	$theme_options = get_option( 'astra-settings', array() );

	// Set flag to not load button specific CSS.
	if ( ! isset( $theme_options['ele-default-color-typo-setting-comp'] ) ) {
		$theme_options['ele-default-color-typo-setting-comp'] = false;
		update_option( 'astra-settings', $theme_options );
	}

}

/**
 * For existing users, change the separator from html entity to css entity.
 *
 * @since 2.3.4
 *
 * @return void
 */
function astra_breadcrumb_separator_fix() {

	$theme_options = get_option( 'astra-settings', array() );

	// Check if the saved database value for Breadcrumb Separator is "&#187;", then change it to '\00bb'.
	if ( isset( $theme_options['breadcrumb-separator'] ) && '&#187;' === $theme_options['breadcrumb-separator'] ) {
		$theme_options['breadcrumb-separator'] = '\00bb';
		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * Check if we need to change the default value for tablet breakpoint.
 *
 * @since 2.4.0
 * @return void
 */
function astra_update_theme_tablet_breakpoint() {

	$theme_options = get_option( 'astra-settings' );

	if ( ! isset( $theme_options['can-update-theme-tablet-breakpoint'] ) ) {
		// Set a flag to check if we need to change the theme tablet breakpoint value.
		$theme_options['can-update-theme-tablet-breakpoint'] = false;
	}

	update_option( 'astra-settings', $theme_options );
}

/**
 * Migrate option data from site layout background option to its desktop counterpart.
 *
 * @since 2.4.0
 *
 * @return void
 */
function astra_responsive_base_background_option() {

	$theme_options = get_option( 'astra-settings', array() );

	if ( false === get_option( 'site-layout-outside-bg-obj-responsive', false ) && isset( $theme_options['site-layout-outside-bg-obj'] ) ) {

		$theme_options['site-layout-outside-bg-obj-responsive']['desktop'] = $theme_options['site-layout-outside-bg-obj'];
		$theme_options['site-layout-outside-bg-obj-responsive']['tablet']  = array(
			'background-color'      => '',
			'background-image'      => '',
			'background-repeat'     => 'repeat',
			'background-position'   => 'center center',
			'background-size'       => 'auto',
			'background-attachment' => 'scroll',
		);
		$theme_options['site-layout-outside-bg-obj-responsive']['mobile']  = array(
			'background-color'      => '',
			'background-image'      => '',
			'background-repeat'     => 'repeat',
			'background-position'   => 'center center',
			'background-size'       => 'auto',
			'background-attachment' => 'scroll',
		);
	}

	update_option( 'astra-settings', $theme_options );
}

/**
 * Do not apply new wide/full image CSS for existing users.
 *
 * @since 2.4.4
 *
 * @return void
 */
function astra_gtn_full_wide_image_group_css() {

	$theme_options = get_option( 'astra-settings', array() );

	// Set flag to not load button specific CSS.
	if ( ! isset( $theme_options['gtn-full-wide-image-grp-css'] ) ) {
		$theme_options['gtn-full-wide-image-grp-css'] = false;
		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * Do not apply new wide/full Group and Cover block CSS for existing users.
 *
 * @since 2.5.0
 *
 * @return void
 */
function astra_gtn_full_wide_group_cover_css() {
	$theme_options = get_option( 'astra-settings', array() );

	if ( ! isset( $theme_options['gtn-full-wide-grp-cover-css'] ) ) {
		$theme_options['gtn-full-wide-grp-cover-css'] = false;
		update_option( 'astra-settings', $theme_options );
	}
}


/**
 * Do not apply the global border width and border color setting for the existng users.
 *
 * @since 2.5.0
 *
 * @return void
 */
function astra_global_button_woo_css() {
	$theme_options = get_option( 'astra-settings', array() );

	// Set flag to not load button specific CSS.
	if ( ! isset( $theme_options['global-btn-woo-css'] ) ) {
		$theme_options['global-btn-woo-css'] = false;
		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * Migrate Footer Widget param to array.
 *
 * @since 2.5.2
 *
 * @return void
 */
function astra_footer_widget_bg() {
	$theme_options = get_option( 'astra-settings', array() );

	// Check if Footer Backgound array is already set or not. If not then set it as array.
	if ( isset( $theme_options['footer-adv-bg-obj'] ) && ! is_array( $theme_options['footer-adv-bg-obj'] ) ) {
		$theme_options['footer-adv-bg-obj'] = array(
			'background-color'      => '',
			'background-image'      => '',
			'background-repeat'     => 'repeat',
			'background-position'   => 'center center',
			'background-size'       => 'auto',
			'background-attachment' => 'scroll',
		);
		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * Check if we need to load icons as font or SVG.
 *
 * @since 3.3.0
 * @return void
 */
function astra_icons_svg_compatibility() {

	$theme_options = get_option( 'astra-settings' );

	if ( ! isset( $theme_options['can-update-astra-icons-svg'] ) ) {
		// Set a flag to check if we need to add icons as SVG.
		$theme_options['can-update-astra-icons-svg'] = false;
		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * Migrate Background control options to new array.
 *
 * @since 3.0.0
 *
 * @return void
 */
function astra_bg_control_migration() {

	$db_options = array(
		'footer-adv-bg-obj',
		'footer-bg-obj',
		'sidebar-bg-obj',
	);

	$theme_options = get_option( 'astra-settings', array() );

	foreach ( $db_options as $option_name ) {

		if ( ! ( isset( $theme_options[ $option_name ]['background-type'] ) && isset( $theme_options[ $option_name ]['background-media'] ) ) && isset( $theme_options[ $option_name ] ) ) {

			if ( ! empty( $theme_options[ $option_name ]['background-image'] ) ) {
				$theme_options[ $option_name ]['background-type']  = 'image';
				$theme_options[ $option_name ]['background-media'] = attachment_url_to_postid( $theme_options[ $option_name ]['background-image'] );
			} else {
				$theme_options[ $option_name ]['background-type']  = '';
				$theme_options[ $option_name ]['background-media'] = '';
			}

			error_log( sprintf( 'Astra: Migrating Background Option - %s', $option_name ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
			update_option( 'astra-settings', $theme_options );
		}
	}
}

/**
 * Migrate Background Responsive options to new array.
 *
 * @since 3.0.0
 *
 * @return void
 */
function astra_bg_responsive_control_migration() {

	$db_options = array(
		'site-layout-outside-bg-obj-responsive',
		'content-bg-obj-responsive',
		'header-bg-obj-responsive',
		'primary-menu-bg-obj-responsive',
		'above-header-bg-obj-responsive',
		'above-header-menu-bg-obj-responsive',
		'below-header-bg-obj-responsive',
		'below-header-menu-bg-obj-responsive',
	);

	$theme_options = get_option( 'astra-settings', array() );

	foreach ( $db_options as $option_name ) {

		if ( ! ( isset( $theme_options[ $option_name ]['desktop']['background-type'] ) && isset( $theme_options[ $option_name ]['desktop']['background-media'] ) ) && isset( $theme_options[ $option_name ] ) ) {

			if ( ! empty( $theme_options[ $option_name ]['desktop']['background-image'] ) ) {
				$theme_options[ $option_name ]['desktop']['background-type']  = 'image';
				$theme_options[ $option_name ]['desktop']['background-media'] = attachment_url_to_postid( $theme_options[ $option_name ]['desktop']['background-image'] );
			} else {
				$theme_options[ $option_name ]['desktop']['background-type']  = '';
				$theme_options[ $option_name ]['desktop']['background-media'] = '';
			}

			if ( ! empty( $theme_options[ $option_name ]['tablet']['background-image'] ) ) {
				$theme_options[ $option_name ]['tablet']['background-type']  = 'image';
				$theme_options[ $option_name ]['tablet']['background-media'] = attachment_url_to_postid( $theme_options[ $option_name ]['tablet']['background-image'] );
			} else {
				$theme_options[ $option_name ]['tablet']['background-type']  = '';
				$theme_options[ $option_name ]['tablet']['background-media'] = '';
			}

			if ( ! empty( $theme_options[ $option_name ]['mobile']['background-image'] ) ) {
				$theme_options[ $option_name ]['mobile']['background-type']  = 'image';
				$theme_options[ $option_name ]['mobile']['background-media'] = attachment_url_to_postid( $theme_options[ $option_name ]['mobile']['background-image'] );
			} else {
				$theme_options[ $option_name ]['mobile']['background-type']  = '';
				$theme_options[ $option_name ]['mobile']['background-media'] = '';
			}

			error_log( sprintf( 'Astra: Migrating Background Response Option - %s', $option_name ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
			update_option( 'astra-settings', $theme_options );
		}
	}
}

/**
 * Do not apply new Group, Column and Media & Text block CSS for existing users.
 *
 * @since 3.0.0
 *
 * @return void
 */
function astra_gutenberg_core_blocks_design_compatibility() {
	$theme_options = get_option( 'astra-settings', array() );

	if ( ! isset( $theme_options['guntenberg-core-blocks-comp-css'] ) ) {
		$theme_options['guntenberg-core-blocks-comp-css'] = false;
		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * Header Footer builder - Migration compatibility.
 *
 * @since 3.0.0
 *
 * @return void
 */
function astra_header_builder_compatibility() {
	$theme_options = get_option( 'astra-settings', array() );

	// Set flag to not load button specific CSS.
	if ( ! isset( $theme_options['is-header-footer-builder'] ) ) {
		$theme_options['is-header-footer-builder'] = false;
		update_option( 'astra-settings', $theme_options );
	}
	if ( ! isset( $theme_options['header-footer-builder-notice'] ) ) {
		$theme_options['header-footer-builder-notice'] = false;
		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * Clears assets cache and regenerates new assets files.
 *
 * @since 3.0.1
 *
 * @return void
 */
function astra_clear_assets_cache() {
	if ( is_callable( 'Astra_Minify::refresh_assets' ) ) {
		Astra_Minify::refresh_assets();
	}
}

/**
 * Do not apply new Media & Text block padding CSS & not remove padding for #primary on mobile devices directly for existing users.
 *
 * @since 2.6.1
 *
 * @return void
 */
function astra_gutenberg_media_text_block_css_compatibility() {
	$theme_options = get_option( 'astra-settings', array() );

	if ( ! isset( $theme_options['guntenberg-media-text-block-padding-css'] ) ) {
		$theme_options['guntenberg-media-text-block-padding-css'] = false;
		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * Gutenberg pattern compatibility changes.
 *
 * @since 3.3.0
 *
 * @return void
 */
function astra_gutenberg_pattern_compatibility() {
	$theme_options = get_option( 'astra-settings', array() );

	if ( ! isset( $theme_options['guntenberg-button-pattern-compat-css'] ) ) {
		$theme_options['guntenberg-button-pattern-compat-css'] = false;
		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * Set flag to provide backward compatibility of float based CSS for existing users.
 *
 * @since 3.3.0
 * @return void.
 */
function astra_check_flex_based_css() {
	$theme_options = get_option( 'astra-settings', array() );

	if ( ! isset( $theme_options['is-flex-based-css'] ) ) {
		$theme_options['is-flex-based-css'] = false;
		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * Update the Cart Style, Icon color & Border radius if None style is selected.
 *
 * @since 3.4.0
 * @return void.
 */
function astra_update_cart_style() {
	$theme_options = get_option( 'astra-settings', array() );
	if ( isset( $theme_options['woo-header-cart-icon-style'] ) && 'none' === $theme_options['woo-header-cart-icon-style'] ) {
		$theme_options['woo-header-cart-icon-style']  = 'outline';
		$theme_options['header-woo-cart-icon-color']  = '';
		$theme_options['woo-header-cart-icon-color']  = '';
		$theme_options['woo-header-cart-icon-radius'] = '';
	}

	if ( isset( $theme_options['edd-header-cart-icon-style'] ) && 'none' === $theme_options['edd-header-cart-icon-style'] ) {
		$theme_options['edd-header-cart-icon-style']  = 'outline';
		$theme_options['edd-header-cart-icon-color']  = '';
		$theme_options['edd-header-cart-icon-radius'] = '';
	}

	update_option( 'astra-settings', $theme_options );
}

/**
 * Update existing 'Grid Column Layout' option in responsive way in Related Posts.
 * Till this update 3.5.0 we have 'Grid Column Layout' only for singular option, but now we are improving it as responsive.
 *
 * @since 3.5.0
 * @return void.
 */
function astra_update_related_posts_grid_layout() {

	$theme_options = get_option( 'astra-settings', array() );

	if ( ! isset( $theme_options['related-posts-grid-responsive'] ) && isset( $theme_options['related-posts-grid'] ) ) {

		/**
		 * Managed here switch case to reduce further conditions in dynamic-css to get CSS value based on grid-template-columns. Because there are following CSS props used.
		 *
		 * '1' = grid-template-columns: 1fr;
		 * '2' = grid-template-columns: repeat(2,1fr);
		 * '3' = grid-template-columns: repeat(3,1fr);
		 * '4' = grid-template-columns: repeat(4,1fr);
		 *
		 * And we already have Astra_Builder_Helper::$grid_size_mapping (used for footer layouts) for getting CSS values based on grid layouts. So migrating old value of grid here to new grid value.
		 */
		switch ( $theme_options['related-posts-grid'] ) {
			case '1':
				$grid_layout = 'full';
				break;

			case '2':
				$grid_layout = '2-equal';
				break;

			case '3':
				$grid_layout = '3-equal';
				break;

			case '4':
				$grid_layout = '4-equal';
				break;
		}

		$theme_options['related-posts-grid-responsive'] = array(
			'desktop' => $grid_layout,
			'tablet'  => $grid_layout,
			'mobile'  => 'full',
		);

		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * Migrate Site Title & Site Tagline options to new responsive array.
 *
 * @since 3.5.0
 *
 * @return void
 */
function astra_site_title_tagline_responsive_control_migration() {

	$theme_options = get_option( 'astra-settings', array() );

	if ( false === get_option( 'display-site-title-responsive', false ) && isset( $theme_options['display-site-title'] ) ) {
		$theme_options['display-site-title-responsive']['desktop'] = $theme_options['display-site-title'];
		$theme_options['display-site-title-responsive']['tablet']  = $theme_options['display-site-title'];
		$theme_options['display-site-title-responsive']['mobile']  = $theme_options['display-site-title'];
	}

	if ( false === get_option( 'display-site-tagline-responsive', false ) && isset( $theme_options['display-site-tagline'] ) ) {
		$theme_options['display-site-tagline-responsive']['desktop'] = $theme_options['display-site-tagline'];
		$theme_options['display-site-tagline-responsive']['tablet']  = $theme_options['display-site-tagline'];
		$theme_options['display-site-tagline-responsive']['mobile']  = $theme_options['display-site-tagline'];
	}

	update_option( 'astra-settings', $theme_options );
}

/**
 * Do not apply new font-weight heading support CSS in editor/frontend directly.
 *
 * 1. Adding Font-weight support to widget titles.
 * 2. Customizer font CSS not supporting in editor.
 *
 * @since 3.6.0
 *
 * @return void
 */
function astra_headings_font_support() {
	$theme_options = get_option( 'astra-settings', array() );

	if ( ! isset( $theme_options['can-support-widget-and-editor-fonts'] ) ) {
		$theme_options['can-support-widget-and-editor-fonts'] = false;
		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * Set flag to avoid direct reflections on live site & to maintain backward compatibility for existing users.
 *
 * @since 3.6.0
 * @return void.
 */
function astra_remove_logo_max_width() {
	$theme_options = get_option( 'astra-settings', array() );

	if ( ! isset( $theme_options['can-remove-logo-max-width-css'] ) ) {
		$theme_options['can-remove-logo-max-width-css'] = false;
		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * Set flag to maintain backward compatibility for existing users for Transparent Header border bottom default value i.e from '' to 0.
 *
 * @since 3.6.0
 * @return void.
 */
function astra_transparent_header_default_value() {
	$theme_options = get_option( 'astra-settings', array() );

	if ( ! isset( $theme_options['transparent-header-default-border'] ) ) {
		$theme_options['transparent-header-default-border'] = false;
		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * Clear Astra + Astra Pro assets cache.
 *
 * @since 3.6.1
 * @return void.
 */
function astra_clear_all_assets_cache() {
	if ( ! class_exists( 'Astra_Cache_Base' ) ) {
		return;
	}
	// Clear Astra theme asset cache.
	$astra_cache_base_instance = new Astra_Cache_Base( 'astra' );
	$astra_cache_base_instance->refresh_assets( 'astra' );

	// Clear Astra Addon's static and dynamic CSS asset cache.
	astra_clear_assets_cache();
	$astra_addon_cache_base_instance = new Astra_Cache_Base( 'astra-addon' );
	$astra_addon_cache_base_instance->refresh_assets( 'astra-addon' );
}

/**
 * Set flag for updated default values for buttons & add GB Buttons padding support.
 *
 * @since 3.6.3
 * @return void
 */
function astra_button_default_values_updated() {
	$theme_options = get_option( 'astra-settings', array() );

	if ( ! isset( $theme_options['btn-default-padding-updated'] ) ) {
		$theme_options['btn-default-padding-updated'] = false;
		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * Set flag for old users, to not directly apply underline to content links.
 *
 * @since 3.6.4
 * @return void
 */
function astra_update_underline_link_setting() {
	$theme_options = get_option( 'astra-settings', array() );

	if ( ! isset( $theme_options['underline-content-links'] ) ) {
		$theme_options['underline-content-links'] = false;
		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * Add compatibility support for WP-5.8. as some of settings & blocks already their in WP-5.7 versions, that's why added backward here.
 *
 * @since 3.6.5
 * @return void
 */
function astra_support_block_editor() {
	$theme_options = get_option( 'astra-settings' );

	// Set flag on existing user's site to not reflect changes directly.
	if ( ! isset( $theme_options['support-block-editor'] ) ) {
		$theme_options['support-block-editor'] = false;
		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * Set flag to maintain backward compatibility for existing users.
 * Fixing the case where footer widget's right margin space not working.
 *
 * @since 3.6.7
 * @return void
 */
function astra_fix_footer_widget_right_margin_case() {
	$theme_options = get_option( 'astra-settings', array() );

	if ( ! isset( $theme_options['support-footer-widget-right-margin'] ) ) {
		$theme_options['support-footer-widget-right-margin'] = false;
		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * Set flag to avoid direct reflections on live site & to maintain backward compatibility for existing users.
 *
 * @since 3.6.7
 * @return void
 */
function astra_remove_elementor_toc_margin() {
	$theme_options = get_option( 'astra-settings', array() );

	if ( ! isset( $theme_options['remove-elementor-toc-margin-css'] ) ) {
		$theme_options['remove-elementor-toc-margin-css'] = false;
		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * Set flag to avoid direct reflections on live site & to maintain backward compatibility for existing users.
 * Use: Setting flag for removing widget specific design options when WordPress 5.8 & above activated on site.
 *
 * @since 3.6.8
 * @return void
 */
function astra_set_removal_widget_design_options_flag() {
	$theme_options = get_option( 'astra-settings', array() );

	if ( ! isset( $theme_options['remove-widget-design-options'] ) ) {
		$theme_options['remove-widget-design-options'] = false;
		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * Apply zero font size for new users.
 *
 * @since 3.6.9
 * @return void
 */
function astra_zero_font_size_comp() {
	$theme_options = get_option( 'astra-settings', array() );

	if ( ! isset( $theme_options['astra-zero-font-size-case-css'] ) ) {
		$theme_options['astra-zero-font-size-case-css'] = false;
		update_option( 'astra-settings', $theme_options );
	}
}

/** Set flag to avoid direct reflections on live site & to maintain backward compatibility for existing users.
 *
 * @since 3.6.9
 * @return void
 */
function astra_unset_builder_elements_underline() {
	$theme_options = get_option( 'astra-settings', array() );

	if ( ! isset( $theme_options['unset-builder-elements-underline'] ) ) {
		$theme_options['unset-builder-elements-underline'] = false;
		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * Migrating Builder > Account > transparent resonsive menu color options to single color options.
 * Because we do not show menu on resonsive devices, whereas we trigger login link on responsive devices instead of showing menu.
 *
 * @since 3.6.9
 *
 * @return void
 */
function astra_remove_responsive_account_menu_colors_support() {

	$theme_options = get_option( 'astra-settings', array() );

	$account_menu_colors = array(
		'transparent-account-menu-color',                // Menu color.
		'transparent-account-menu-bg-obj',               // Menu background color.
		'transparent-account-menu-h-color',              // Menu hover color.
		'transparent-account-menu-h-bg-color',           // Menu background hover color.
		'transparent-account-menu-a-color',              // Menu active color.
		'transparent-account-menu-a-bg-color',           // Menu background active color.
	);

	foreach ( $account_menu_colors as $color_option ) {
		if ( ! isset( $theme_options[ $color_option ] ) && isset( $theme_options[ $color_option . '-responsive' ]['desktop'] ) ) {
			$theme_options[ $color_option ] = $theme_options[ $color_option . '-responsive' ]['desktop'];
		}
	}

	update_option( 'astra-settings', $theme_options );
}

/**
 * Link default color compatibility.
 *
 * @since 3.7.0
 * @return void
 */
function astra_global_color_compatibility() {
	$theme_options = get_option( 'astra-settings', array() );

	if ( ! isset( $theme_options['support-global-color-format'] ) ) {
		$theme_options['support-global-color-format'] = false;
	}

	// Set Footer copyright text color for existing users to #3a3a3a.
	if ( ! isset( $theme_options['footer-copyright-color'] ) ) {
		$theme_options['footer-copyright-color'] = '#3a3a3a';
	}

	update_option( 'astra-settings', $theme_options );
}

/**
 * Set flag to avoid direct reflections on live site & to maintain backward compatibility for existing users.
 *
 * @since 3.7.4
 * @return void
 */
function astra_improve_gutenberg_editor_ui() {
	$theme_options = get_option( 'astra-settings', array() );

	if ( ! isset( $theme_options['improve-gb-editor-ui'] ) ) {
		$theme_options['improve-gb-editor-ui'] = false;
		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * Set flag to avoid direct reflections on live site & to maintain backward compatibility for existing users.
 *
 * Starting supporting content-background color for Full Width Contained & Full Width Stretched layouts.
 *
 * @since 3.7.8
 * @return void
 */
function astra_fullwidth_layouts_apply_content_background() {
	$theme_options = get_option( 'astra-settings', array() );

	if ( ! isset( $theme_options['apply-content-background-fullwidth-layouts'] ) ) {
		$theme_options['apply-content-background-fullwidth-layouts'] = false;
		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * Sets the default breadcrumb separator selector value if the current user is an exsisting user
 *
 * @since 3.7.8
 * @return void
 */
function astra_set_default_breadcrumb_separator_option() {
	$theme_options = get_option( 'astra-settings', array() );

	if ( ! isset( $theme_options['breadcrumb-separator-selector'] ) ) {
		$theme_options['breadcrumb-separator-selector'] = 'unicode';
		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * Set flag to avoid direct reflections on live site & to maintain backward compatibility for existing users.
 *
 * Backward flag purpose - To initiate modern & updated UI of block editor & frontend.
 *
 * @since 3.8.0
 * @return void
 */
function astra_apply_modern_block_editor_ui() {
	$theme_options = get_option( 'astra-settings', array() );

	if ( ! isset( $theme_options['wp-blocks-ui'] ) && ! version_compare( $theme_options['theme-auto-version'], '3.8.0', '==' ) ) {
		$theme_options['blocks-legacy-setup'] = true;
		$theme_options['wp-blocks-ui']        = 'legacy';
		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * Set flag to avoid direct reflections on live site & to maintain backward compatibility for existing users.
 *
 * Backward flag purpose - To keep structure defaults updation by filter.
 *
 * @since 3.8.3
 * @return void
 */
function astra_update_customizer_layout_defaults() {
	$theme_options = get_option( 'astra-settings', array() );

	if ( ! isset( $theme_options['customizer-default-layout-update'] ) ) {
		$theme_options['customizer-default-layout-update'] = false;
		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * Set flag to avoid direct reflections on live site & to maintain backward compatibility for existing users.
 *
 * Backward flag purpose - To initiate maintain modern, updated v2 experience of block editor & frontend.
 *
 * @since 3.8.3
 * @return void
 */
function astra_apply_modern_block_editor_v2_ui() {
	$theme_options  = get_option( 'astra-settings', array() );
	$option_updated = false;
	if ( ! isset( $theme_options['wp-blocks-v2-ui'] ) ) {
		$theme_options['wp-blocks-v2-ui'] = false;
		$option_updated                   = true;
	}
	if ( ! isset( $theme_options['wp-blocks-ui'] ) ) {
		$theme_options['wp-blocks-ui'] = 'custom';
		$option_updated                = true;
	}
	if ( $option_updated ) {
		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * Display Cart Total and Title compatibility.
 *
 * @since 3.9.0
 * @return void
 */
function astra_display_cart_total_title_compatibility() {
	$theme_options = get_option( 'astra-settings', array() );

	if ( ! isset( $theme_options['woo-header-cart-label-display'] ) ) {
		// Set the Display Cart Label toggle values with shortcodes.
		$cart_total_status = isset( $theme_options['woo-header-cart-total-display'] ) ? $theme_options['woo-header-cart-total-display'] : true;
		$cart_label_status = isset( $theme_options['woo-header-cart-title-display'] ) ? $theme_options['woo-header-cart-title-display'] : true;

		if ( $cart_total_status && $cart_label_status ) {
			$theme_options['woo-header-cart-label-display'] = __( 'Cart', 'astra' ) . '/{cart_total_currency_symbol}';
		} elseif ( $cart_total_status ) {
			$theme_options['woo-header-cart-label-display'] = '{cart_total_currency_symbol}';
		} elseif ( $cart_label_status ) {
			$theme_options['woo-header-cart-label-display'] = __( 'Cart', 'astra' );
		}

		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * If old user then it keeps then default cart icon.
 *
 * @since 3.9.0
 * @return void
 */
function astra_update_woocommerce_cart_icons() {
	$theme_options = get_option( 'astra-settings', array() );

	if ( ! isset( $theme_options['astra-woocommerce-cart-icons-flag'] ) ) {
		$theme_options['astra-woocommerce-cart-icons-flag'] = false;
	}
}

/**
 * Set brder color to blank for old users for new users 'default' will take over.
 *
 * @since 3.9.0
 * @return void
 */
function astra_legacy_customizer_maintenance() {
	$theme_options = get_option( 'astra-settings', array() );
	if ( ! isset( $theme_options['border-color'] ) ) {
		$theme_options['border-color'] = '#dddddd';
		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * Enable single product breadcrumb to maintain backward compatibility for existing users.
 *
 * @since 3.9.0
 * @return void
 */
function astra_update_single_product_breadcrumb() {
	$theme_options = get_option( 'astra-settings', array() );
	if ( isset( $theme_options['single-product-breadcrumb-disable'] ) ) {
		$theme_options['single-product-breadcrumb-disable'] = ( true === $theme_options['single-product-breadcrumb-disable'] ) ? false : true;
	} else {
		$theme_options['single-product-breadcrumb-disable'] = true;
	}
	update_option( 'astra-settings', $theme_options );
}

/**
 * Restrict direct changes on users end so make it filterable.
 *
 * @since 3.9.0
 * @return void
 */
function astra_apply_modern_ecommerce_setup() {
	$theme_options = get_option( 'astra-settings', array() );
	if ( ! isset( $theme_options['modern-ecommerce-setup'] ) ) {
		$theme_options['modern-ecommerce-setup'] = false;
		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * Migrate old user data to new responsive format layout for shop's summary box content alignment.
 *
 * @since 3.9.0
 * @return void
 */
function astra_responsive_shop_content_alignment() {
	$theme_options = get_option( 'astra-settings', array() );
	if ( ! isset( $theme_options['shop-product-align-responsive'] ) && isset( $theme_options['shop-product-align'] ) ) {
		$theme_options['shop-product-align-responsive'] = array(
			'desktop' => $theme_options['shop-product-align'],
			'tablet'  => $theme_options['shop-product-align'],
			'mobile'  => $theme_options['shop-product-align'],
		);
		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * Change default layout to standard for old users.
 *
 * @since 3.9.2
 * @return void
 */
function astra_shop_style_design_layout() {
	$theme_options = get_option( 'astra-settings', array() );
	if ( ! isset( $theme_options['woo-shop-style-flag'] ) ) {
		$theme_options['woo-shop-style-flag'] = true;
		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * Apply css for show password icon on woocommerce account page.
 *
 * @since 3.9.2
 * @return void
 */
function astra_apply_woocommerce_show_password_icon_css() {
	$theme_options = get_option( 'astra-settings', array() );
	if ( ! isset( $theme_options['woo-show-password-icon'] ) ) {
		$theme_options['woo-show-password-icon'] = false;
		update_option( 'astra-settings', $theme_options );
	}
}
