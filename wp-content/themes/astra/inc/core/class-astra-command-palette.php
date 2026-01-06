<?php
/**
 * Astra Command Palette Integration
 *
 * Integrates Astra customizer panels with WordPress Command Palette.
 *
 * @package     Astra
 * @link        https://wpastra.com/
 * @since       Astra 4.12.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Astra Command Palette Integration
 */
if ( ! class_exists( 'Astra_Command_Palette' ) ) {

	/**
	 * Astra Command Palette Integration Class
	 */
	class Astra_Command_Palette {
		/**
		 * Constructor
		 */
		public function __construct() {
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_command_palette_script' ) );
		}

		/**
		 * Enqueue Command Palette integration script
		 *
		 * @since 4.12.0
		 * @return void
		 */
		public function enqueue_command_palette_script() {

			/** @psalm-suppress InvalidGlobal */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			global $wp_version;
			if ( version_compare( $wp_version, '6.3', '<' ) ) {
				return;
			}

			/** @psalm-suppress RedundantCondition */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			$dir_name = SCRIPT_DEBUG ? 'unminified' : 'minified';
			/** @psalm-suppress RedundantCondition */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			$file_prefix = SCRIPT_DEBUG ? '' : '.min';

			$script_path = ASTRA_THEME_DIR . 'assets/js/' . $dir_name . '/command-palette' . $file_prefix . '.js';

			if ( ! file_exists( $script_path ) ) {
				return;
			}

			/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			wp_enqueue_script(
				'astra-command-palette',
				ASTRA_THEME_URI . 'assets/js/' . $dir_name . '/command-palette' . $file_prefix . '.js',
				array( 'wp-data', 'wp-element', 'wp-commands', 'wp-dom-ready' ),
				ASTRA_THEME_VERSION,
				true
			);

			wp_localize_script(
				'astra-command-palette',
				'astraCommandPalette',
				array(
					'customizerUrl' => admin_url( 'customize.php' ),
					'panels'        => $this->get_customizer_panels(),
					'iconUrl'       => ASTRA_THEME_URI . 'inc/assets/images/astra-logo.svg',
				)
			);
		}

		/**
		 * Get customizer panels configuration
		 *
		 * @since 4.12.0
		 * @return array List of customizer panels.
		 */
		private function get_customizer_panels() {
			$panels = array(
				array(
					'name'        => 'astra/customizer-global',
					'label'       => __( 'Customizer: Global', 'astra' ),
					'searchLabel' => __( 'Global, Container, Layout, Colors, Color, Background, Base Colors, Typography, Font, Fonts, Headings, Text, Buttons, Button, Style, Base Typography', 'astra' ),
					'type'        => 'panel',
					'id'          => 'panel-global',
				),
				array(
					'name'        => 'astra/customizer-header',
					'label'       => __( 'Customizer: Header', 'astra' ),
					'searchLabel' => __( 'Header, Site Identity, Logo, Site Title, Tagline, Primary Header, Primary Menu, Menu, Navigation, Above Header, Below Header, Mobile Header, Mobile Menu, Search, Button, Social, Account, Cart, Widget, HTML, Off Canvas, Mobile Trigger', 'astra' ),
					'type'        => 'panel',
					'id'          => 'panel-header-builder-group',
				),
				array(
					'name'        => 'astra/customizer-footer',
					'label'       => __( 'Customizer: Footer', 'astra' ),
					'searchLabel' => __( 'Footer, Footer Widgets, Footer Bar, Copyright, Primary Footer, Above Footer, Below Footer, Menu, Social, HTML, Widget', 'astra' ),
					'type'        => 'panel',
					'id'          => 'panel-footer-builder-group',
				),
				array(
					'name'        => 'astra/customizer-blog',
					'label'       => __( 'Customizer: Blog', 'astra' ),
					'searchLabel' => __( 'Blog, Archive, Single Post, Post, Single Page, Page, Content, Excerpt, Featured Image, Meta, Author, Date, Categories, Tags, Comments', 'astra' ),
					'type'        => 'section',
					'id'          => 'section-blog-group',
				),
				array(
					'name'        => 'astra/customizer-general',
					'label'       => __( 'Customizer: General', 'astra' ),
					'searchLabel' => __( 'General, Sidebar, Accessibility, Skip to Content, Block Editor, Gutenberg, Misc, Scroll To Top, Back to Top', 'astra' ),
					'type'        => 'section',
					'id'          => 'section-general-group',
				),
			);

			// Add WooCommerce panel only if WooCommerce is active.
			if ( class_exists( 'WooCommerce' ) ) {
				$panels[] = array(
					'name'        => 'astra/customizer-woocommerce',
					'label'       => __( 'Customizer: WooCommerce', 'astra' ),
					'searchLabel' => __( 'WooCommerce, Shop, Store, Products, Single Product, Cart, Checkout, Account, My Account, Orders, Sidebar', 'astra' ),
					'type'        => 'panel',
					'id'          => 'woocommerce',
				);
			}

			return $panels;
		}
	}

	new Astra_Command_Palette();
}
