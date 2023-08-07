<?php
/**
 * Styling Options for Astra Theme.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
 * @link        https://wpastra.com/
 * @since       Astra 1.0.15
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Astra_Content_Typo_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class Astra_Content_Typo_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Content Typography Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Heading 1 (H1) Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[divider-section-h1]',
					'type'     => 'control',
					'control'  => 'ast-heading',
					'section'  => 'section-content-typo',
					'priority' => 4,
					'title'    => __( 'Heading 1', 'astra' ),
					'settings' => array(),
				),

				/**
				 * Option: Heading 1 (H1) Font Size
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[font-size-h1]',
					'type'        => 'control',
					'section'     => 'section-content-typo',
					'default'     => astra_get_option( 'font-size-h1' ),
					'transport'   => 'postMessage',
					'priority'    => 6,
					'title'       => __( 'Font Size', 'astra' ),
					'control'     => 'ast-responsive-slider',
					'suffix'      => array( 'px', 'em' ),
					'input_attrs' => array(
						'px' => array(
							'min'  => 0,
							'step' => 1,
							'max'  => 200,
						),
						'em' => array(
							'min'  => 0,
							'step' => 0.01,
							'max'  => 20,
						),
					),
				),

				/**
				 * Option: Heading 2 (H2) Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[divider-section-h2]',
					'type'     => 'control',
					'control'  => 'ast-heading',
					'section'  => 'section-content-typo',
					'priority' => 9,
					'title'    => __( 'Heading 2', 'astra' ),
					'settings' => array(),
				),

				/**
				 * Option: Heading 2 (H2) Font Size
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[font-size-h2]',
					'type'        => 'control',
					'section'     => 'section-content-typo',
					'default'     => astra_get_option( 'font-size-h2' ),
					'transport'   => 'postMessage',
					'priority'    => 11,
					'title'       => __( 'Font Size', 'astra' ),
					'control'     => 'ast-responsive-slider',
					'suffix'      => array( 'px', 'em' ),
					'input_attrs' => array(
						'px' => array(
							'min'  => 0,
							'step' => 1,
							'max'  => 200,
						),
						'em' => array(
							'min'  => 0,
							'step' => 0.01,
							'max'  => 20,
						),
					),
				),

				/**
				 * Option: Heading 3 (H3) Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[divider-section-h3]',
					'type'     => 'control',
					'control'  => 'ast-heading',
					'section'  => 'section-content-typo',
					'priority' => 15,
					'title'    => __( 'Heading 3', 'astra' ),
					'settings' => array(),
				),

				/**
				 * Option: Heading 3 (H3) Font Size
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[font-size-h3]',
					'type'        => 'control',
					'section'     => 'section-content-typo',
					'priority'    => 16,
					'default'     => astra_get_option( 'font-size-h3' ),
					'transport'   => 'postMessage',
					'title'       => __( 'Font Size', 'astra' ),
					'control'     => 'ast-responsive-slider',
					'suffix'      => array( 'px', 'em' ),
					'input_attrs' => array(
						'px' => array(
							'min'  => 0,
							'step' => 1,
							'max'  => 200,
						),
						'em' => array(
							'min'  => 0,
							'step' => 0.01,
							'max'  => 20,
						),
					),
				),

				/**
				 * Option: Heading 4 (H4) Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[divider-section-h4]',
					'type'     => 'control',
					'title'    => __( 'Heading 4', 'astra' ),
					'section'  => 'section-content-typo',
					'control'  => 'ast-heading',
					'priority' => 20,
					'settings' => array(),
				),

				/**
				 * Option: Heading 4 (H4) Font Size
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[font-size-h4]',
					'type'        => 'control',
					'section'     => 'section-content-typo',
					'default'     => astra_get_option( 'font-size-h4' ),
					'transport'   => 'postMessage',
					'priority'    => 21,
					'title'       => __( 'Font Size', 'astra' ),
					'control'     => 'ast-responsive-slider',
					'suffix'      => array( 'px', 'em' ),
					'input_attrs' => array(
						'px' => array(
							'min'  => 0,
							'step' => 1,
							'max'  => 200,
						),
						'em' => array(
							'min'  => 0,
							'step' => 0.01,
							'max'  => 20,
						),
					),
				),

				/**
				 * Option: Heading 5 (H5) Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[divider-section-h5]',
					'type'     => 'control',
					'control'  => 'ast-heading',
					'section'  => 'section-content-typo',
					'priority' => 25,
					'title'    => __( 'Heading 5', 'astra' ),
					'settings' => array(),
				),

				/**
				 * Option: Heading 5 (H5) Font Size
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[font-size-h5]',
					'type'        => 'control',
					'section'     => 'section-content-typo',
					'default'     => astra_get_option( 'font-size-h5' ),
					'transport'   => 'postMessage',
					'priority'    => 26,
					'title'       => __( 'Font Size', 'astra' ),
					'control'     => 'ast-responsive-slider',
					'suffix'      => array( 'px', 'em' ),
					'input_attrs' => array(
						'px' => array(
							'min'  => 0,
							'step' => 1,
							'max'  => 200,
						),
						'em' => array(
							'min'  => 0,
							'step' => 0.01,
							'max'  => 20,
						),
					),
				),

				/**
				 * Option: Heading 6 (H6) Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[divider-section-h6]',
					'type'     => 'control',
					'control'  => 'ast-heading',
					'title'    => __( 'Heading 6', 'astra' ),
					'section'  => 'section-content-typo',
					'priority' => 30,
					'settings' => array(),
				),

				/**
				 * Option: Heading 6 (H6) Font Size
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[font-size-h6]',
					'type'        => 'control',
					'section'     => 'section-content-typo',
					'default'     => astra_get_option( 'font-size-h6' ),
					'transport'   => 'postMessage',
					'priority'    => 31,
					'title'       => __( 'Font Size', 'astra' ),
					'control'     => 'ast-responsive-slider',
					'suffix'      => array( 'px', 'em' ),
					'input_attrs' => array(
						'px' => array(
							'min'  => 0,
							'step' => 1,
							'max'  => 200,
						),
						'em' => array(
							'min'  => 0,
							'step' => 0.01,
							'max'  => 20,
						),
					),
				),
			);

			$configurations = array_merge( $configurations, $_configs );

			// Learn More link if Astra Pro is not activated.
			if ( ! defined( 'ASTRA_EXT_VER' ) ) {

				$_configs = array(
					/**
					 * Option: Learn More about Contant Typography
					 */
					array(
						'name'     => ASTRA_THEME_SETTINGS . '[ast-content-typography-more-feature-description]',
						'type'     => 'control',
						'control'  => 'ast-description',
						'section'  => 'section-content-typo',
						'priority' => 999,
						'title'    => '',
						'help'     => '<p>' . __( 'More Options Available in Astra Pro!', 'astra' ) . '</p><a href="' . ASTRA_PRO_CUSTOMIZER_UPGRADE_URL . '" class="button button-secondary"  target="_blank" rel="noopener">' . __( 'Learn More', 'astra' ) . '</a>',
						'settings' => array(),
					),
				);

				$configurations = array_merge( $configurations, $_configs );

			}

			return $configurations;
		}
	}
}

new Astra_Content_Typo_Configs();

