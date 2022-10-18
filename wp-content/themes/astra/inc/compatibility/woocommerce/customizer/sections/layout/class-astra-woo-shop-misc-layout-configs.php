<?php
/**
 * WooCommerce Options for Astra Theme.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
 * @link        https://wpastra.com/
 * @since       Astra 3.9.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Astra_Woo_Shop_Misc_Layout_Configs' ) ) {


	/**
	 * Customizer Sanitizes Initial setup
	 */
	class Astra_Woo_Shop_Misc_Layout_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Astra-WooCommerce Misc Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 3.9.2
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {


			$_configs = array(
			

				/**
				 * Option: Enable Quantity Plus and Minus.
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[single-product-plus-minus-button]',
					'default'     => astra_get_option( 'single-product-plus-minus-button' ),
					'type'        => 'control',
					'section'     => 'section-woo-misc',
					'title'       => __( 'Enable Quantity Plus and Minus', 'astra' ),
					'description' => __( 'Adds plus and minus buttons besides product quantity', 'astra' ),
					'priority'    => 59,
					'control'     => 'ast-toggle-control',
					'divider'     => array( 'ast_class' => 'ast-section-spacing' ),
				),

			);

			/**
			 * Option: Adds tabs only if astra addons is enabled.
			 */
			if ( astra_has_pro_woocommerce_addon() ) {
				$_configs[] = array(
					'name'        => 'section-woo-general-tabs',
					'section'     => 'section-woo-misc',
					'type'        => 'control',
					'control'     => 'ast-builder-header-control',
					'priority'    => 0,
					'description' => '',
				);
			}

			if ( ! defined( 'ASTRA_EXT_VER' ) ) {
				// Learn More link if Astra Pro is not activated.
				$_configs[] = array(
					'name'     => ASTRA_THEME_SETTINGS . '[single-product-plus-minus-button-link]',
					'type'     => 'control',
					'control'  => 'ast-button-link',
					'section'  => 'section-woo-misc',
					'priority' => 999,
					'title'    => __( 'View Astra Pro Features', 'astra' ),
					'url'      => astra_get_pro_url( 'https://wpastra.com/pro/', 'customizer', 'learn-more', 'upgrade-to-pro' ),
					'settings' => array(),
					'divider'  => array( 'ast_class' => 'ast-section-spacing' ),
					'context'  => array(
						Astra_Builder_Helper::$general_tab_config,
					),
				);
			}

			return array_merge( $configurations, $_configs );
		}
	}
}

new Astra_Woo_Shop_Misc_Layout_Configs();
