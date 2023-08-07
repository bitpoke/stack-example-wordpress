<?php
/**
 * Container Options for Astra theme.
 *
 * @package     Astra
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2020, Brainstorm Force
 * @link        https://www.brainstormforce.com
 * @since       1.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Astra_Learndash_Container_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class Astra_Learndash_Container_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register LearnDash Container settings.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Container Layout.
				 */

				array(
					'name'              => ASTRA_THEME_SETTINGS . '[learndash-content-layout]',
					'type'              => 'control',
					'control'           => 'ast-radio-image',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
					'section'           => 'section-leandash-general',
					'default'           => astra_get_option( 'learndash-content-layout' ),
					'priority'          => 5,
					'title'             => __( 'Container Layout', 'astra' ),
					'choices'           => array(
						'default'                 => array(
							'label' => __( 'Default', 'astra' ),
							'path'  => ( class_exists( 'Astra_Builder_UI_Controller' ) ) ? Astra_Builder_UI_Controller::fetch_svg_icon( 'layout-default', false ) : '',
						),
						'boxed-container'         => array(
							'label' => __( 'Boxed', 'astra' ),
							'path'  => ( class_exists( 'Astra_Builder_UI_Controller' ) ) ? Astra_Builder_UI_Controller::fetch_svg_icon( 'container-boxed', false ) : '',
						),
						'content-boxed-container' => array(
							'label' => __( 'Content Boxed', 'astra' ),
							'path'  => ( class_exists( 'Astra_Builder_UI_Controller' ) ) ? Astra_Builder_UI_Controller::fetch_svg_icon( 'container-content-boxed', false ) : '',
						),
						'plain-container'         => array(
							'label' => __( 'Full Width / Contained', 'astra' ),
							'path'  => ( class_exists( 'Astra_Builder_UI_Controller' ) ) ? Astra_Builder_UI_Controller::fetch_svg_icon( 'container-full-width-contained', false ) : '',
						),
						'page-builder'            => array(
							'label' => __( 'Full Width / Stretched', 'astra' ),
							'path'  => ( class_exists( 'Astra_Builder_UI_Controller' ) ) ? Astra_Builder_UI_Controller::fetch_svg_icon( 'container-full-width-stretched', false ) : '',
						),
					),
					'divider'           => array( 'ast_class' => 'ast-bottom-section-divider ast-section-spacing' ),
				),
			);

			return array_merge( $configurations, $_configs );

		}
	}
}

new Astra_Learndash_Container_Configs();
