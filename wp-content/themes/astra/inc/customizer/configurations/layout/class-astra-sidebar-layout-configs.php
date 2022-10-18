<?php
/**
 * Bottom Footer Options for Astra Theme.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
 * @link        https://wpastra.com/
 * @since       Astra 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Astra_Sidebar_Layout_Configs' ) ) {

	/**
	 * Register Astra Sidebar Layout Configurations.
	 */
	class Astra_Sidebar_Layout_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Astra Sidebar Layout Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Default Sidebar Position
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[site-sidebar-layout]',
					'type'              => 'control',
					'control'           => 'ast-radio-image',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
					'section'           => 'section-sidebars',
					'default'           => astra_get_option( 'site-sidebar-layout' ),
					'priority'          => 5,
					'title'             => __( 'Default Layout', 'astra' ),
					'choices'           => array(
						'no-sidebar'    => array(
							'label' => __( 'No Sidebar', 'astra' ),
							'path'  => ( class_exists( 'Astra_Builder_UI_Controller' ) ) ? Astra_Builder_UI_Controller::fetch_svg_icon( 'no-sidebar', false ) : '',
						),
						'left-sidebar'  => array(
							'label' => __( 'Left Sidebar', 'astra' ),
							'path'  => ( class_exists( 'Astra_Builder_UI_Controller' ) ) ? Astra_Builder_UI_Controller::fetch_svg_icon( 'left-sidebar', false ) : '',
						),
						'right-sidebar' => array(
							'label' => __( 'Right Sidebar', 'astra' ),
							'path'  => ( class_exists( 'Astra_Builder_UI_Controller' ) ) ? Astra_Builder_UI_Controller::fetch_svg_icon( 'right-sidebar', false ) : '',
						),
					),
					'divider'           => array( 'ast_class' => 'ast-bottom-section-divider ast-section-spacing' ),
				),

				/**
					 * Option: Sidebar Page
					 */

					array(
						'name'              => ASTRA_THEME_SETTINGS . '[single-page-sidebar-layout]',
						'type'              => 'control',
						'control'           => 'ast-radio-image',
						'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
						'section'           => 'section-page-group',
						'default'           => astra_get_option( 'single-page-sidebar-layout', 'default' ),
						'priority'          => 5,
						'title'             => __( 'Sidebar Layout', 'astra' ),
						'choices'           => array(
							'default'       => array(
								'label' => __( 'Default', 'astra' ),
								'path'  => ( class_exists( 'Astra_Builder_UI_Controller' ) ) ? Astra_Builder_UI_Controller::fetch_svg_icon( 'layout-default', false ) : '',
							),
							'no-sidebar'    => array(
								'label' => __( 'No Sidebar', 'astra' ),
								'path'  => ( class_exists( 'Astra_Builder_UI_Controller' ) ) ? Astra_Builder_UI_Controller::fetch_svg_icon( 'no-sidebar', false ) : '',
							),
							'left-sidebar'  => array(
								'label' => __( 'Left Sidebar', 'astra' ),
								'path'  => ( class_exists( 'Astra_Builder_UI_Controller' ) ) ? Astra_Builder_UI_Controller::fetch_svg_icon( 'left-sidebar', false ) : '',
							),
							'right-sidebar' => array(
								'label' => __( 'Right Sidebar', 'astra' ),
								'path'  => ( class_exists( 'Astra_Builder_UI_Controller' ) ) ? Astra_Builder_UI_Controller::fetch_svg_icon( 'right-sidebar', false ) : '',
							),
						),
					),

				/**
				 * Option: Primary Content Width
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[site-sidebar-width]',
					'type'        => 'control',
					'control'     => 'ast-slider',
					'default'     => astra_get_option( 'site-sidebar-width' ),
					'section'     => 'section-sidebars',
					'priority'    => 15,
					'title'       => __( 'Sidebar Width', 'astra' ),
					'suffix'      => '%',
					'transport'   => 'postMessage',
					'input_attrs' => array(
						'min'  => 15,
						'step' => 1,
						'max'  => 50,
					),

				),

				array(
					'name'     => ASTRA_THEME_SETTINGS . '[site-sidebar-width-description]',
					'type'     => 'control',
					'control'  => 'ast-description',
					'section'  => 'section-sidebars',
					'priority' => 15,
					'title'    => '',
					'help'     => __( 'Sidebar width will apply only when one of the above sidebar is set.', 'astra' ),
					'divider'  => array( 'ast_class' => 'ast-bottom-section-divider' ),
					'settings' => array(),
				),
			);

			// Learn More link if Astra Pro is not activated.
			if ( ! defined( 'ASTRA_EXT_VER' ) ) {

				$_configs[] = array(
					'name'     => ASTRA_THEME_SETTINGS . '[site-sidebars-ast-button-link]',
					'type'     => 'control',
					'control'  => 'ast-button-link',
					'section'  => 'section-sidebars',
					'priority' => 999,
					'title'    => __( 'View Astra Pro Features', 'astra' ),
					'url'      => astra_get_pro_url( 'https://wpastra.com/pro/', 'customizer', 'learn-more', 'upgrade-to-pro' ),
					'settings' => array(),
					'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
				);
			}

			// Learn More link if Astra Pro is not activated.
			if ( ! defined( 'ASTRA_EXT_VER' ) ) {

				$_configs[] = array(
					'name'     => ASTRA_THEME_SETTINGS . '[site-page-group-ast-button-link]',
					'type'     => 'control',
					'control'  => 'ast-button-link',
					'section'  => 'section-page-group',
					'priority' => 999,
					'title'    => __( 'View Astra Pro Features', 'astra' ),
					'url'      => astra_get_pro_url( 'https://wpastra.com/pro/', 'customizer', 'learn-more', 'upgrade-to-pro' ),
					'settings' => array(),
					'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
				);
			}

			return array_merge( $configurations, $_configs );
		}
	}
}


new Astra_Sidebar_Layout_Configs();





