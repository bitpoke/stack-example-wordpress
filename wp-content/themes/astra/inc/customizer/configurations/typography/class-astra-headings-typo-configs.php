<?php
/**
 * Styling Options for Astra Theme.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2021, Astra
 * @link        https://wpastra.com/
 * @since       Astra 3.7.0
 */

/** @psalm-suppress ParadoxicalCondition **/ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Customizer Sanitizes Initial setup
 */
class Astra_Headings_Typo_Configs extends Astra_Customizer_Config_Base {

	/**
	 * Register headings Typography Customizer Configurations.
	 *
	 * @param Array                $configurations Astra Customizer Configurations.
	 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
	 * @since 3.7.0
	 * @return Array Astra Customizer Configurations with updated configurations.
	 */
	public function register_configuration( $configurations, $wp_customize ) {

		$section = 'section-typography';

		$_configs = array(

			/**
			 * Heading Typography starts here - h1 - h3
			 */

			/**
			 * Option: Heading <H1> Font Family
			 */
			array(
				'name'      => 'font-family-h1',
				'type'      => 'sub-control',
				'parent'    => ASTRA_THEME_SETTINGS . '[ast-heading-h1-typo]',
				'control'   => 'ast-font',
				'font_type' => 'ast-font-family',
				'default'   => astra_get_option( 'font-family-h1' ),
				'title'     => __( 'Font Family', 'astra' ),
				'section'   => $section,
				'priority'  => 28,
				'connect'   => ASTRA_THEME_SETTINGS . '[font-weight-h1]',
				'transport' => 'postMessage',
			),

			/**
			 * Option: Heading 1 (H1) Font Size
			 */

			array(
				'name'              => 'font-size-h1',
				'parent'            => ASTRA_THEME_SETTINGS . '[ast-heading-h1-typo]',
				'type'              => 'sub-control',
				'control'           => 'ast-responsive-slider',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
				'section'           => $section,
				'default'           => astra_get_option( 'font-size-h1' ),
				'transport'         => 'postMessage',
				'priority'          => 28,
				'title'             => __( 'Size', 'astra' ),
				'suffix'            => array( 'px', 'em' ),
				'input_attrs'       => array(
					'px' => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 100,
					),
					'em' => array(
						'min'  => 0,
						'step' => 0.01,
						'max'  => 20,
					),
				),
			),


			/**
			 * Option: Heading <H1> Font Weight
			 */
			array(
				'name'              => 'font-weight-h1',
				'type'              => 'sub-control',
				'parent'            => ASTRA_THEME_SETTINGS . '[ast-heading-h1-typo]',
				'control'           => 'ast-font',
				'font_type'         => 'ast-font-weight',
				'title'             => __( 'Weight', 'astra' ),
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
				'default'           => astra_get_option( 'font-weight-h1' ),
				'section'           => $section,
				'priority'          => 28,
				'connect'           => 'font-family-h1',
				'transport'         => 'postMessage',
			),

			/**
			 * Option: Heading <H1> Text Transform
			 */
			array(
				'name'      => 'text-transform-h1',
				'section'   => $section,
				'default'   => astra_get_option( 'text-transform-h1' ),
				'title'     => __( 'Text Transform', 'astra' ),
				'type'      => 'sub-control',
				'parent'    => ASTRA_THEME_SETTINGS . '[ast-heading-h1-typo]',
				'control'   => 'ast-select',
				'priority'  => 28,
				'choices'   => array(
					''           => __( 'Inherit', 'astra' ),
					'none'       => __( 'None', 'astra' ),
					'capitalize' => __( 'Capitalize', 'astra' ),
					'uppercase'  => __( 'Uppercase', 'astra' ),
					'lowercase'  => __( 'Lowercase', 'astra' ),
				),
				'transport' => 'postMessage',
			),

			/**
			 * Option: Heading <H1> Line Height
			 */
			array(
				'name'              => 'line-height-h1',
				'section'           => $section,
				'default'           => astra_get_option( 'line-height-h1' ),
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
				'parent'            => ASTRA_THEME_SETTINGS . '[ast-heading-h1-typo]',
				'type'              => 'sub-control',
				'control'           => 'ast-slider',
				'title'             => __( 'Line Height', 'astra' ),
				'transport'         => 'postMessage',
				'priority'          => 28,
				'suffix'            => 'em',
				'input_attrs'       => array(
					'min'  => 1,
					'step' => 0.01,
					'max'  => 5,
				),
			),

			/**
			 * Option: Heading <H2> Font Family
			 */
			array(
				'name'      => 'font-family-h2',
				'type'      => 'sub-control',
				'control'   => 'ast-font',
				'parent'    => ASTRA_THEME_SETTINGS . '[ast-heading-h2-typo]',
				'font_type' => 'ast-font-family',
				'title'     => __( 'Font Family', 'astra' ),
				'default'   => astra_get_option( 'font-family-h2' ),
				'section'   => $section,
				'priority'  => 28,
				'connect'   => ASTRA_THEME_SETTINGS . '[font-weight-h2]',
				'transport' => 'postMessage',
			),

			/**
			 * Option: Heading 2 (H2) Font Size
			 */

			array(
				'name'              => 'font-size-h2',
				'parent'            => ASTRA_THEME_SETTINGS . '[ast-heading-h2-typo]',
				'type'              => 'sub-control',
				'control'           => 'ast-responsive-slider',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
				'section'           => $section,
				'default'           => astra_get_option( 'font-size-h2' ),
				'transport'         => 'postMessage',
				'priority'          => 28,
				'title'             => __( 'Size', 'astra' ),
				'suffix'            => array( 'px', 'em' ),
				'input_attrs'       => array(
					'px' => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 100,
					),
					'em' => array(
						'min'  => 0,
						'step' => 0.01,
						'max'  => 20,
					),
				),
			),

			/**
			 * Option: Heading <H2> Font Weight
			 */
			array(
				'name'              => 'font-weight-h2',
				'parent'            => ASTRA_THEME_SETTINGS . '[ast-heading-h2-typo]',
				'type'              => 'sub-control',
				'control'           => 'ast-font',
				'font_type'         => 'ast-font-weight',
				'title'             => __( 'Weight', 'astra' ),
				'section'           => $section,
				'default'           => astra_get_option( 'font-weight-h2' ),
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
				'priority'          => 28,
				'connect'           => 'font-family-h2',
				'transport'         => 'postMessage',
			),

			/**
			 * Option: Heading <H2> Text Transform
			 */
			array(
				'name'      => 'text-transform-h2',
				'section'   => $section,
				'default'   => astra_get_option( 'text-transform-h2' ),
				'title'     => __( 'Text Transform', 'astra' ),
				'parent'    => ASTRA_THEME_SETTINGS . '[ast-heading-h2-typo]',
				'type'      => 'sub-control',
				'lazy'      => true,
				'control'   => 'ast-select',
				'transport' => 'postMessage',
				'priority'  => 28,
				'choices'   => array(
					''           => __( 'Inherit', 'astra' ),
					'none'       => __( 'None', 'astra' ),
					'capitalize' => __( 'Capitalize', 'astra' ),
					'uppercase'  => __( 'Uppercase', 'astra' ),
					'lowercase'  => __( 'Lowercase', 'astra' ),
				),
			),

			/**
			 * Option: Heading <H2> Line Height
			 */

			array(
				'name'              => 'line-height-h2',
				'section'           => $section,
				'parent'            => ASTRA_THEME_SETTINGS . '[ast-heading-h2-typo]',
				'type'              => 'sub-control',
				'control'           => 'ast-slider',
				'default'           => astra_get_option( 'line-height-h2' ),
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
				'transport'         => 'postMessage',
				'title'             => __( 'Line Height', 'astra' ),
				'priority'          => 30,
				'lazy'              => true,
				'suffix'            => 'em',
				'input_attrs'       => array(
					'min'  => 1,
					'step' => 0.01,
					'max'  => 5,
				),
			),

			/**
			 * Option: Heading <H3> Font Family
			 */
			array(
				'name'      => 'font-family-h3',
				'parent'    => ASTRA_THEME_SETTINGS . '[ast-heading-h3-typo]',
				'type'      => 'sub-control',
				'control'   => 'ast-font',
				'font_type' => 'ast-font-family',
				'default'   => astra_get_option( 'font-family-h3' ),
				'title'     => __( 'Font Family', 'astra' ),
				'section'   => $section,
				'priority'  => 28,
				'connect'   => ASTRA_THEME_SETTINGS . '[font-weight-h3]',
				'transport' => 'postMessage',
			),

			/**
			 * Option: Heading 3 (H3) Font Size
			 */

			array(
				'name'              => 'font-size-h3',
				'parent'            => ASTRA_THEME_SETTINGS . '[ast-heading-h3-typo]',
				'type'              => 'sub-control',
				'control'           => 'ast-responsive-slider',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
				'section'           => $section,
				'default'           => astra_get_option( 'font-size-h3' ),
				'transport'         => 'postMessage',
				'priority'          => 28,
				'title'             => __( 'Size', 'astra' ),
				'suffix'            => array( 'px', 'em' ),
				'input_attrs'       => array(
					'px' => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 100,
					),
					'em' => array(
						'min'  => 0,
						'step' => 0.01,
						'max'  => 20,
					),
				),
			),

			/**
			 * Option: Heading <H3> Font Weight
			 */
			array(
				'name'              => 'font-weight-h3',
				'parent'            => ASTRA_THEME_SETTINGS . '[ast-heading-h3-typo]',
				'type'              => 'sub-control',
				'control'           => 'ast-font',
				'font_type'         => 'ast-font-weight',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
				'default'           => astra_get_option( 'font-weight-h3' ),
				'title'             => __( 'Weight', 'astra' ),
				'section'           => $section,
				'priority'          => 28,
				'connect'           => 'font-family-h3',
				'transport'         => 'postMessage',
			),

			/**
			 * Option: Heading <H3> Text Transform
			 */
			array(
				'name'      => 'text-transform-h3',
				'parent'    => ASTRA_THEME_SETTINGS . '[ast-heading-h3-typo]',
				'type'      => 'sub-control',
				'section'   => $section,
				'lazy'      => true,
				'title'     => __( 'Text Transform', 'astra' ),
				'default'   => astra_get_option( 'text-transform-h3' ),
				'transport' => 'postMessage',
				'control'   => 'ast-select',
				'priority'  => 28,
				'choices'   => array(
					''           => __( 'Inherit', 'astra' ),
					'none'       => __( 'None', 'astra' ),
					'capitalize' => __( 'Capitalize', 'astra' ),
					'uppercase'  => __( 'Uppercase', 'astra' ),
					'lowercase'  => __( 'Lowercase', 'astra' ),
				),
			),

			/**
			 * Option: Heading <H3> Line Height
			 */
			array(
				'name'              => 'line-height-h3',
				'parent'            => ASTRA_THEME_SETTINGS . '[ast-heading-h3-typo]',
				'type'              => 'sub-control',
				'control'           => 'ast-slider',
				'section'           => $section,
				'lazy'              => true,
				'title'             => __( 'Line Height', 'astra' ),
				'transport'         => 'postMessage',
				'default'           => astra_get_option( 'line-height-h3' ),
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
				'priority'          => 28,
				'suffix'            => 'em',
				'input_attrs'       => array(
					'min'  => 1,
					'step' => 0.01,
					'max'  => 5,
				),
			),

			/**
			 * Option: Heading <H4> Font Family
			 */
			array(
				'name'      => 'font-family-h4',
				'parent'    => ASTRA_THEME_SETTINGS . '[ast-heading-h4-typo]',
				'type'      => 'sub-control',
				'control'   => 'ast-font',
				'font_type' => 'ast-font-family',
				'title'     => __( 'Font Family', 'astra' ),
				'default'   => astra_get_option( 'font-family-h4' ),
				'section'   => $section,
				'priority'  => 28,
				'connect'   => ASTRA_THEME_SETTINGS . '[font-weight-h4]',
				'transport' => 'postMessage',
			),

			/**
			 * Option: Heading 4 (H4) Font Size
			 */

			array(
				'name'              => 'font-size-h4',
				'parent'            => ASTRA_THEME_SETTINGS . '[ast-heading-h4-typo]',
				'type'              => 'sub-control',
				'control'           => 'ast-responsive-slider',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
				'section'           => $section,
				'default'           => astra_get_option( 'font-size-h4' ),
				'transport'         => 'postMessage',
				'priority'          => 28,
				'title'             => __( 'Size', 'astra' ),
				'suffix'            => array( 'px', 'em' ),
				'input_attrs'       => array(
					'px' => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 100,
					),
					'em' => array(
						'min'  => 0,
						'step' => 0.01,
						'max'  => 20,
					),
				),
			),

			/**
			 * Option: Heading <H4> Font Weight
			 */
			array(
				'name'              => 'font-weight-h4',
				'parent'            => ASTRA_THEME_SETTINGS . '[ast-heading-h4-typo]',
				'type'              => 'sub-control',
				'control'           => 'ast-font',
				'font_type'         => 'ast-font-weight',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
				'title'             => __( 'Weight', 'astra' ),
				'default'           => astra_get_option( 'font-weight-h4' ),
				'section'           => $section,
				'priority'          => 28,
				'connect'           => 'font-family-h4',
				'transport'         => 'postMessage',
			),

			/**
			 * Option: Heading <H4> Text Transform
			 */
			array(
				'name'      => 'text-transform-h4',
				'section'   => $section,
				'parent'    => ASTRA_THEME_SETTINGS . '[ast-heading-h4-typo]',
				'type'      => 'sub-control',
				'title'     => __( 'Text Transform', 'astra' ),
				'default'   => astra_get_option( 'text-transform-h4' ),
				'transport' => 'postMessage',
				'control'   => 'ast-select',
				'lazy'      => true,
				'priority'  => 28,
				'choices'   => array(
					''           => __( 'Inherit', 'astra' ),
					'none'       => __( 'None', 'astra' ),
					'capitalize' => __( 'Capitalize', 'astra' ),
					'uppercase'  => __( 'Uppercase', 'astra' ),
					'lowercase'  => __( 'Lowercase', 'astra' ),
				),
			),

			/**
			 * Option: Heading <H4> Line Height
			 */
			array(
				'name'              => 'line-height-h4',
				'parent'            => ASTRA_THEME_SETTINGS . '[ast-heading-h4-typo]',
				'type'              => 'sub-control',
				'section'           => $section,
				'default'           => astra_get_option( 'line-height-h4' ),
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
				'title'             => __( 'Line Height', 'astra' ),
				'control'           => 'ast-slider',
				'priority'          => 28,
				'lazy'              => true,
				'transport'         => 'postMessage',
				'suffix'            => 'em',
				'input_attrs'       => array(
					'min'  => 1,
					'step' => 0.01,
					'max'  => 5,
				),
			),

			/**
			 * Option: Heading <H5> Font Family
			 */
			array(
				'name'      => 'font-family-h5',
				'parent'    => ASTRA_THEME_SETTINGS . '[ast-heading-h5-typo]',
				'type'      => 'sub-control',
				'control'   => 'ast-font',
				'font_type' => 'ast-font-family',
				'default'   => astra_get_option( 'font-family-h5' ),
				'title'     => __( 'Font Family', 'astra' ),
				'section'   => $section,
				'priority'  => 28,
				'connect'   => ASTRA_THEME_SETTINGS . '[font-weight-h5]',
				'transport' => 'postMessage',
			),

			/**
			 * Option: Heading 5 (H5) Font Size
			 */
			array(
				'name'              => 'font-size-h5',
				'parent'            => ASTRA_THEME_SETTINGS . '[ast-heading-h5-typo]',
				'type'              => 'sub-control',
				'control'           => 'ast-responsive-slider',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
				'section'           => $section,
				'default'           => astra_get_option( 'font-size-h5' ),
				'transport'         => 'postMessage',
				'priority'          => 28,
				'title'             => __( 'Size', 'astra' ),
				'suffix'            => array( 'px', 'em' ),
				'input_attrs'       => array(
					'px' => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 100,
					),
					'em' => array(
						'min'  => 0,
						'step' => 0.01,
						'max'  => 20,
					),
				),
			),
			/**
			 * Option: Heading <H5> Font Weight
			 */
			array(
				'name'              => 'font-weight-h5',
				'parent'            => ASTRA_THEME_SETTINGS . '[ast-heading-h5-typo]',
				'type'              => 'sub-control',
				'control'           => 'ast-font',
				'font_type'         => 'ast-font-weight',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
				'title'             => __( 'Weight', 'astra' ),
				'section'           => $section,
				'default'           => astra_get_option( 'font-weight-h5' ),
				'priority'          => 28,
				'connect'           => 'font-family-h5',
				'transport'         => 'postMessage',
			),

			/**
			 * Option: Heading <H5> Text Transform
			 */
			array(
				'name'      => 'text-transform-h5',
				'parent'    => ASTRA_THEME_SETTINGS . '[ast-heading-h5-typo]',
				'type'      => 'sub-control',
				'section'   => $section,
				'lazy'      => true,
				'control'   => 'ast-select',
				'title'     => __( 'Text Transform', 'astra' ),
				'transport' => 'postMessage',
				'default'   => astra_get_option( 'text-transform-h5' ),
				'priority'  => 28,
				'choices'   => array(
					''           => __( 'Inherit', 'astra' ),
					'none'       => __( 'None', 'astra' ),
					'capitalize' => __( 'Capitalize', 'astra' ),
					'uppercase'  => __( 'Uppercase', 'astra' ),
					'lowercase'  => __( 'Lowercase', 'astra' ),
				),
			),

			/**
			 * Option: Heading <H5> Line Height
			 */

			array(
				'name'              => 'line-height-h5',
				'parent'            => ASTRA_THEME_SETTINGS . '[ast-heading-h5-typo]',
				'type'              => 'sub-control',
				'control'           => 'ast-slider',
				'lazy'              => true,
				'section'           => $section,
				'default'           => astra_get_option( 'line-height-h5' ),
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
				'title'             => __( 'Line Height', 'astra' ),
				'transport'         => 'postMessage',
				'priority'          => 28,
				'suffix'            => 'em',
				'input_attrs'       => array(
					'min'  => 1,
					'step' => 0.01,
					'max'  => 5,
				),
			),

			/**
			 * Option: Heading <H6> Font Family
			 */
			array(
				'name'      => 'font-family-h6',
				'parent'    => ASTRA_THEME_SETTINGS . '[ast-heading-h6-typo]',
				'type'      => 'sub-control',
				'control'   => 'ast-font',
				'font_type' => 'ast-font-family',
				'default'   => astra_get_option( 'font-family-h6' ),
				'title'     => __( 'Font Family', 'astra' ),
				'section'   => $section,
				'priority'  => 28,
				'connect'   => ASTRA_THEME_SETTINGS . '[font-weight-h6]',
				'transport' => 'postMessage',
			),

			/**
			 * Option: Heading 6 (H6) Font Size
			 */
			array(
				'name'              => 'font-size-h6',
				'parent'            => ASTRA_THEME_SETTINGS . '[ast-heading-h6-typo]',
				'type'              => 'sub-control',
				'control'           => 'ast-responsive-slider',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
				'section'           => $section,
				'default'           => astra_get_option( 'font-size-h6' ),
				'transport'         => 'postMessage',
				'priority'          => 28,
				'title'             => __( 'Size', 'astra' ),
				'suffix'            => array( 'px', 'em' ),
				'input_attrs'       => array(
					'px' => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 100,
					),
					'em' => array(
						'min'  => 0,
						'step' => 0.01,
						'max'  => 20,
					),
				),
			),

			/**
			 * Option: Heading <H6> Font Weight
			 */
			array(
				'name'              => 'font-weight-h6',
				'parent'            => ASTRA_THEME_SETTINGS . '[ast-heading-h6-typo]',
				'type'              => 'sub-control',
				'control'           => 'ast-font',
				'font_type'         => 'ast-font-weight',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
				'default'           => astra_get_option( 'font-weight-h6' ),
				'title'             => __( 'Weight', 'astra' ),
				'section'           => $section,
				'priority'          => 28,
				'connect'           => 'font-family-h6',
				'transport'         => 'postMessage',
			),

			/**
			 * Option: Heading <H6> Text Transform
			 */
			array(
				'name'      => 'text-transform-h6',
				'section'   => $section,
				'parent'    => ASTRA_THEME_SETTINGS . '[ast-heading-h6-typo]',
				'type'      => 'sub-control',
				'control'   => 'ast-select',
				'lazy'      => true,
				'title'     => __( 'Text Transform', 'astra' ),
				'transport' => 'postMessage',
				'priority'  => 28,
				'default'   => astra_get_option( 'text-transform-h6' ),
				'choices'   => array(
					''           => __( 'Inherit', 'astra' ),
					'none'       => __( 'None', 'astra' ),
					'capitalize' => __( 'Capitalize', 'astra' ),
					'uppercase'  => __( 'Uppercase', 'astra' ),
					'lowercase'  => __( 'Lowercase', 'astra' ),
				),
			),

			/**
			 * Option: Heading <H6> Line Height
			 */
			array(
				'name'              => 'line-height-h6',
				'parent'            => ASTRA_THEME_SETTINGS . '[ast-heading-h6-typo]',
				'type'              => 'sub-control',
				'section'           => $section,
				'lazy'              => true,
				'transport'         => 'postMessage',
				'default'           => astra_get_option( 'line-height-h6' ),
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
				'title'             => __( 'Line Height', 'astra' ),
				'control'           => 'ast-slider',
				'priority'          => 29,
				'suffix'            => 'em',
				'input_attrs'       => array(
					'min'  => 1,
					'step' => 0.01,
					'max'  => 5,
				),
			),
		);
		return array_merge( $configurations, $_configs );
	}
}

new Astra_Headings_Typo_Configs();
