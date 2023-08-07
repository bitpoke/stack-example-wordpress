<?php
/**
 * Content Spacing Options for our theme.
 *
 * @package     Astra
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2020, Brainstorm Force
 * @link        https://www.brainstormforce.com
 * @since       Astra 1.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Astra_Lifter_Sidebar_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class Astra_Lifter_Sidebar_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Astra-LifterLMS Sidebar Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {
			$common_title   = __( 'Sidebar Layout', 'astra' );
			$common_section = 'section-lifterlms';

			/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			if ( defined( 'ASTRA_EXT_VER' ) && Astra_Ext_Extension::is_active( 'lifterlms' ) ) {
				$section_general          = 'section-lifterlms-general';
				$section_courses          = 'section-lifterlms-course-lesson';
				$title_lifter_lms         = $common_title;
				$title_lifter_lms_courses = $common_title;
			} else {
				$section_general          = $common_section;
				$section_courses          = $common_section;
				$title_lifter_lms         = __( 'Global Sidebar Layout', 'astra' );
				$title_lifter_lms_courses = __( 'Course/Lesson Sidebar Layout', 'astra' );
			}


			$_configs = array(

				/**
				 * Option: Global Sidebar Layout.
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[lifterlms-sidebar-layout]',
					'type'              => 'control',
					'control'           => 'ast-radio-image',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
					'section'           => $section_general,
					'default'           => astra_get_option( 'lifterlms-sidebar-layout' ),
					'priority'          => 1,
					'title'             => $title_lifter_lms,
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
				 * Option: Course/Lesson Sidebar Layout.
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[lifterlms-course-lesson-sidebar-layout]',
					'type'              => 'control',
					'control'           => 'ast-radio-image',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
					'section'           => $section_courses,
					'default'           => astra_get_option( 'lifterlms-course-lesson-sidebar-layout' ),
					'priority'          => 1,
					'title'             => $title_lifter_lms_courses,
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
					'divider'           => array( 'ast_class' => 'ast-section-spacing' ),
				),
			);

			return array_merge( $configurations, $_configs );

		}
	}
}

new Astra_Lifter_Sidebar_Configs();
