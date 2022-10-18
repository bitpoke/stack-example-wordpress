<?php
/**
 * WooCommerce Options for Astra Theme.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
 * @link        https://wpastra.com/
 * @since       Astra 1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Astra_Woo_Shop_Cart_Layout_Configs' ) ) {


	/**
	 * Customizer Sanitizes Initial setup
	 */
	class Astra_Woo_Shop_Cart_Layout_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Astra-WooCommerce Shop Cart Layout Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(


				/**
				 * Option: Enable checkout button text
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[woo-enable-cart-button-text]',
					'default'     => astra_get_option( 'woo-enable-cart-button-text' ),
					'type'        => 'control',
					'section'     => 'section-woo-shop-cart',
					'title'       => __( 'Change Cart Button Text', 'astra' ),
					'description' => __( 'Add custom text for cart button', 'astra' ),
					'control'     => 'ast-toggle-control',
					'priority'    => 2,
				),

				/**
				 * Option: Checkout
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[woo-cart-button-text]',
					'default'  => astra_get_option( 'woo-cart-button-text' ),
					'type'     => 'control',
					'section'  => 'section-woo-shop-cart',
					'title'    => __( 'Cart Button Text', 'astra' ),
					'context'  => array(
						Astra_Builder_Helper::$general_tab_config,
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[woo-enable-cart-button-text]',
							'operator' => '==',
							'value'    => true,
						),
					),
					'control'  => 'text',
					'priority' => 2,
				),

				/**
				 * Option: Cart upsells
				 *
				 * Enable Cross-sells - in the code it is refrenced as upsells rather than cross-sells.
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[enable-cart-upsells]',
					'section'  => 'section-woo-shop-cart',
					'type'     => 'control',
					'control'  => 'ast-toggle-control',
					'default'  => astra_get_option( 'enable-cart-upsells' ),
					'title'    => __( 'Enable Cross-sells', 'astra' ),
					'priority' => 2.7,
					'divider'  => array( 'ast_class' => 'ast-section-spacing' ),
				),
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

new Astra_Woo_Shop_Cart_Layout_Configs();
