<?php
/**
 * Astra Theme Customizer Configuration Builder.
 *
 * @package     astra-builder
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
 * @link        https://wpastra.com/
 * @since       3.0.0
 */

// No direct access, please.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'Astra_Customizer_Config_Base' ) ) {

	/**
	 * Register Builder Customizer Configurations.
	 *
	 * @since 3.0.0
	 */
	class Astra_Customizer_Primary_Header_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Builder Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 3.0.0
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_section = 'section-primary-header-builder';

			$_configs = array(

				/*
				 * Panel - New Header
				 *
				 * @since 3.0.0
				 */
				array(
					'name'     => 'panel-header-builder-group',
					'type'     => 'panel',
					'priority' => 20,
					'title'    => __( 'Header Builder', 'astra' ),
				),

				// Section: Primary Header.
				array(
					'name'     => $_section,
					'type'     => 'section',
					'title'    => __( 'Primary Header', 'astra' ),
					'panel'    => 'panel-header-builder-group',
					'priority' => 20,
				),

				/**
				 * Option: Header Builder Tabs
				 */
				array(
					'name'        => $_section . '-ast-context-tabs',
					'section'     => $_section,
					'type'        => 'control',
					'control'     => 'ast-builder-header-control',
					'priority'    => 0,
					'description' => '',
				),

				// Section: Primary Header Height.
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[hb-header-height]',
					'section'           => $_section,
					'transport'         => 'postMessage',
					'default'           => astra_get_option( 'hb-header-height' ),
					'priority'          => 3,
					'title'             => __( 'Height', 'astra' ),
					'type'              => 'control',
					'control'           => 'ast-responsive-slider',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
					'suffix'            => 'px',
					'input_attrs'       => array(
						'min'  => 30,
						'step' => 1,
						'max'  => 600,
					),
					'context'           => Astra_Builder_Helper::$general_tab,
					'divider'           => array( 'ast_class' => 'ast-section-spacing' ),
				),

				// Sub Option: Header Background.
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[hb-header-bg-obj-responsive]',
					'section'    => $_section,
					'type'       => 'control',
					'control'    => 'ast-responsive-background',
					'transport'  => 'postMessage',
					'context'    => Astra_Builder_Helper::$design_tab,
					'priority'   => 5,
					'data_attrs' => array(
						'name' => 'hb-header-bg-obj-responsive',
					),
					'default'    => astra_get_option( 'hb-header-bg-obj-responsive' ),
					'title'      => __( 'Background', 'astra' ),
					'divider'    => array( 'ast_class' => 'ast-section-spacing' ),
				),

				// Option: Header Bottom Boder Color.
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[hb-header-main-sep-color]',
					'transport'         => 'postMessage',
					'default'           => astra_get_option( 'hb-header-main-sep-color' ),
					'type'              => 'control',
					'control'           => 'ast-color',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ),
					'section'           => $_section,
					'priority'          => 5,
					'title'             => __( 'Bottom Border Color', 'astra' ),
					'context'           => array(
						Astra_Builder_Helper::$design_tab_config,
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[hb-header-main-sep]',
							'operator' => '>=',
							'value'    => 1,
						),
					),
				),

				// Option: Header Separator.
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[hb-header-main-sep]',
					'transport'   => 'postMessage',
					'default'     => astra_get_option( 'hb-header-main-sep' ),
					'type'        => 'control',
					'control'     => 'ast-slider',
					'section'     => $_section,
					'priority'    => 5,
					'title'       => __( 'Bottom Border Size', 'astra' ),
					'suffix'      => 'px',
					'input_attrs' => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 10,
					),
					'context'     => Astra_Builder_Helper::$design_tab,
					'divider'     => array( 'ast_class' => 'ast-top-section-divider' ),
				),

			

			);

			$_configs = array_merge( $_configs, Astra_Builder_Base_Configuration::prepare_advanced_tab( $_section ) );

			$_configs = array_merge( $_configs, Astra_Builder_Base_Configuration::prepare_visibility_tab( $_section ) );

			return array_merge( $configurations, $_configs );
		}
	}

	/**
	 * Kicking this off by creating object of this class.
	 */
	new Astra_Customizer_Primary_Header_Configs();
}
