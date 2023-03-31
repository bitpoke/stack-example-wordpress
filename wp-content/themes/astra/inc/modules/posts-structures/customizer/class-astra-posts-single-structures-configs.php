<?php
/**
 * Posts Strctures Options for our theme.
 *
 * @package     Astra
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2022, Brainstorm Force
 * @link        https://www.brainstormforce.com
 * @since       Astra 4.0.0
 */

// Block direct access to the file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Bail if Customizer config base class does not exist.
if ( ! class_exists( 'Astra_Customizer_Config_Base' ) ) {
	return;
}

/**
 * Register Posts Strctures Customizer Configurations.
 *
 * @since 4.0.0
 */
class Astra_Posts_Single_Structures_Configs extends Astra_Customizer_Config_Base {

	/**
	 * Getting dynamic context for sidebar.
	 * Compatibility case: Narrow width + dynamic customizer controls.
	 *
	 * @param string $post_type On basis of this will decide to hide sidebar control or not.
	 * @since 4.0.0
	 */
	public function get_sidebar_context( $post_type ) {
		if ( ! in_array( $post_type, Astra_Posts_Structures_Configs::get_narrow_width_exculde_cpts() ) ) {
			return array(
				'relation' => 'AND',
				Astra_Builder_Helper::$general_tab_config,
				array(
					'setting'  => ASTRA_THEME_SETTINGS . '[single-' . $post_type . '-content-layout]',
					'operator' => '!=',
					'value'    => 'narrow-container',
				),
				array(
					'relation' => 'OR',
					array(
						'setting'  => ASTRA_THEME_SETTINGS . '[single-' . $post_type . '-content-layout]',
						'operator' => '!=',
						'value'    => 'default',
					),
					array(
						'setting'  => ASTRA_THEME_SETTINGS . '[site-content-layout]',
						'operator' => '!=',
						'value'    => 'narrow-container',
					),
				),
			);
		} else {
			return Astra_Builder_Helper::$general_tab;
		}
	}

	/**
	 * Getting content layout dynamically.
	 * Compatibility case: Narrow width + dynamic customizer controls.
	 *
	 * @param string $post_type On basis of this will decide to show narrow-width layout or not.
	 * @since 4.0.0
	 */
	public function get_content_layout_choices( $post_type ) {
		if ( ! in_array( $post_type, Astra_Posts_Structures_Configs::get_narrow_width_exculde_cpts() ) ) {
			return array(
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
				'narrow-container'        => array(
					'label' => __( 'Narrow Width', 'astra' ),
					'path'  => ( class_exists( 'Astra_Builder_UI_Controller' ) ) ? Astra_Builder_UI_Controller::fetch_svg_icon( 'narrow-container', false ) : '',
				),
			);
		} else {
			return array(
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
			);
		}
	}

	/**
	 * Register Single Post's Structures Customizer Configurations.
	 *
	 * @param string $parent_section Section of dynamic customizer.
	 * @param string $post_type Post Type.
	 * @since 4.0.0
	 *
	 * @return array Customizer Configurations.
	 */
	public function get_layout_configuration( $parent_section, $post_type ) {
		return array(
			array(
				'name'              => ASTRA_THEME_SETTINGS . '[single-' . $post_type . '-content-layout]',
				'type'              => 'control',
				'control'           => 'ast-radio-image',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
				'section'           => $parent_section,
				'default'           => astra_get_option( 'single-' . $post_type . '-content-layout', 'default' ),
				'priority'          => 3,
				'title'             => __( 'Container Layout', 'astra' ),
				'choices'           => $this->get_content_layout_choices( $post_type ),
				'divider'           => array( 'ast_class' => 'ast-top-divider' ),
			),
			array(
				'name'              => ASTRA_THEME_SETTINGS . '[single-' . $post_type . '-sidebar-layout]',
				'type'              => 'control',
				'control'           => 'ast-radio-image',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
				'section'           => $parent_section,
				'default'           => astra_get_option( 'single-' . $post_type . '-sidebar-layout', 'default' ),
				'priority'          => 3,
				'title'             => __( 'Sidebar Layout', 'astra' ),
				'context'           => $this->get_sidebar_context( $post_type ),
				'divider'           => array( 'ast_class' => 'ast-top-divider' ),
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
		);
	}

	/**
	 * Register Posts Strctures Customizer Configurations.
	 *
	 * @param Array                $configurations Astra Customizer Configurations.
	 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
	 * @since 4.0.0
	 * @return Array Astra Customizer Configurations with updated configurations.
	 */
	public function register_configuration( $configurations, $wp_customize ) {

		$post_types = Astra_Posts_Structure_Loader::get_supported_post_types();
		foreach ( $post_types as $index => $post_type ) {

			$raw_taxonomies     = array_diff(
				get_object_taxonomies( $post_type ),
				array( 'post_format' )
			);
			$raw_taxonomies[''] = __( 'Select', 'astra' );

			// Filter out taxonomies in index-value format.
			$taxonomies = array();
			foreach ( $raw_taxonomies as $index => $value ) {
				/** @psalm-suppress PossiblyInvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
				$tax_object = get_taxonomy( $value );
				/** @psalm-suppress PossiblyInvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort

				// @codingStandardsIgnoreStart
				$tax_val    = ( is_object( $tax_object ) && ! empty( $tax_object->label ) ) ? $tax_object->label : $value;
				// @codingStandardsIgnoreEnd

				if ( '' === $index ) {
					$taxonomies[''] = $tax_val;
				} else {
					$taxonomies[ $value ] = $tax_val;
				}
			}
			/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			$taxonomies = array_reverse( $taxonomies );
			/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort

			$section          = 'single-posttype-' . $post_type;
			$title_section    = 'ast-dynamic-single-' . $post_type;
			$post_type_object = get_post_type_object( $post_type );

			if ( 'product' === $post_type ) {
				$parent_section = 'section-woo-shop-single';
			} elseif ( 'post' === $post_type ) {
				$parent_section = 'section-blog-single';
			} elseif ( 'page' === $post_type ) {
				$parent_section = 'section-page-dynamic-group';
			} elseif ( 'download' === $post_type ) {
				$parent_section = 'section-edd-single';
			} else {
				$parent_section = $section;
			}

			$meta_config_options = array();
			$clone_limit         = 0;
			/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			if ( count( $taxonomies ) > 1 ) {
				/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
				$clone_limit = 3;
				$to_clone    = true;
				if ( absint( astra_get_option( $title_section . '-taxonomy-clone-tracker', 1 ) ) === $clone_limit ) {
					$to_clone = false;
				}
				$meta_config_options[ $title_section . '-taxonomy' ]   = array(
					'clone'         => $to_clone,
					'is_parent'     => true,
					'main_index'    => $title_section . '-taxonomy',
					'clone_limit'   => $clone_limit,
					'clone_tracker' => ASTRA_THEME_SETTINGS . '[' . $title_section . '-taxonomy-clone-tracker]',
					'title'         => __( 'Taxonomy', 'astra' ),
				);
				$meta_config_options[ $title_section . '-taxonomy-1' ] = array(
					'clone'         => $to_clone,
					'is_parent'     => true,
					'main_index'    => $title_section . '-taxonomy',
					'clone_limit'   => $clone_limit,
					'clone_tracker' => ASTRA_THEME_SETTINGS . '[' . $title_section . '-taxonomy-clone-tracker]',
					'title'         => __( 'Taxonomy', 'astra' ),
				);
				$meta_config_options[ $title_section . '-taxonomy-2' ] = array(
					'clone'         => $to_clone,
					'is_parent'     => true,
					'main_index'    => $title_section . '-taxonomy',
					'clone_limit'   => $clone_limit,
					'clone_tracker' => ASTRA_THEME_SETTINGS . '[' . $title_section . '-taxonomy-clone-tracker]',
					'title'         => __( 'Taxonomy', 'astra' ),
				);
			}
			$meta_config_options['date'] = array(
				'clone'       => false,
				'is_parent'   => true,
				'main_index'  => 'date',
				'clone_limit' => 1,
				'title'       => __( 'Date', 'astra' ),
			);

			// Display Read Time option in Meta options only when Astra Addon is activated.
			/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			if ( defined( 'ASTRA_EXT_VER' ) && Astra_Ext_Extension::is_active( 'blog-pro' ) ) {
				$meta_config_options['read-time'] = __( 'Read Time', 'astra' );
			}

			$structure_sub_controls = array();
			// Add featured as background sub-control.
			$structure_sub_controls[ $title_section . '-image' ] = array(
				'clone'       => false,
				'is_parent'   => true,
				'main_index'  => $title_section . '-image',
				'clone_limit' => 2,
				'title'       => __( 'Featured Image', 'astra' ),
			);

			$configurations = array_merge( $configurations, $this->get_layout_configuration( $parent_section, $post_type ) );

			$_configs = array(

				/**
				 * Option: Builder Tabs
				 */
				array(
					'name'        => $title_section . '-ast-context-tabs',
					'section'     => $title_section,
					'type'        => 'control',
					'control'     => 'ast-builder-header-control',
					'priority'    => 0,
					'description' => '',
					'context'     => array(),
				),

				array(
					'name'     => $title_section,
					'title'    => isset( $post_type_object->labels->singular_name ) ? ucfirst( $post_type_object->labels->singular_name ) . __( ' Title', 'astra' ) : ucfirst( $post_type ) . __( ' Title', 'astra' ),
					'type'     => 'section',
					'section'  => $parent_section,
					'panel'    => ( 'product' === $post_type ) ? 'woocommerce' : '',
					'priority' => 1,
				),

				array(
					'name'     => ASTRA_THEME_SETTINGS . '[ast-single-' . $post_type . '-title]',
					'type'     => 'control',
					'default'  => astra_get_option( 'ast-single-' . $post_type . '-title', ( class_exists( 'WooCommerce' ) && 'product' === $post_type ) ? false : true ),
					'control'  => 'ast-section-toggle',
					'section'  => $parent_section,
					'priority' => 2,
					'linked'   => $title_section,
					'linkText' => isset( $post_type_object->labels->singular_name ) ? ucfirst( $post_type_object->labels->singular_name ) . __( ' Title', 'astra' ) : ucfirst( $post_type ) . __( ' Title', 'astra' ),
					'divider'  => array( 'ast_class' => 'ast-bottom-divider ast-bottom-section-divider' ),
				),

				/**
				 * Layout option.
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[' . $title_section . '-layout]',
					'type'              => 'control',
					'control'           => 'ast-radio-image',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
					'section'           => $title_section,
					'default'           => astra_get_option( $title_section . '-layout', 'layout-1' ),
					'priority'          => 5,
					'context'           => Astra_Builder_Helper::$general_tab,
					'title'             => __( 'Banner Layout', 'astra' ),
					'divider'           => array( 'ast_class' => 'ast-section-spacing ast-bottom-spacing' ),
					'choices'           => array(
						'layout-1' => array(
							'label' => __( 'Layout 1', 'astra' ),
							'path'  => Astra_Builder_UI_Controller::fetch_svg_icon( 'post-layout' ),
						),
						'layout-2' => array(
							'label' => __( 'Layout 2', 'astra' ),
							'path'  => '<span class="ahfb-svg-iconset ast-inline-flex"><svg width="100" height="70" viewBox="0 0 100 70" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M10 12C10 10.8954 10.8954 10 12 10H88C89.1046 10 90 10.8954 90 12V70H10V12Z" fill="white"></path> <mask id="' . esc_attr( $title_section ) . '-masking" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="10" y="10" width="80" height="60"> <path d="M10 12C10 10.8954 10.8954 10 12 10H88C89.1046 10 90 10.8954 90 12V70H10V12Z" fill="white"></path> </mask> <g mask="url(#' . esc_attr( $title_section ) . '-masking)"> <path d="M2 9H95V35H2V9Z" fill="#DADDE2"></path> </g> <path fill-rule="evenodd" clip-rule="evenodd" d="M83 58H16V56H83V58Z" fill="#E9EAEE"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M83 64H16V62H83V64Z" fill="#E9EAEE"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M61 21H41V19H61V21Z" fill="white"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M53.4 25H33V23H53.4V25Z" fill="white"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M67 25H54.76V23H67V25Z" fill="white"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M42.4783 29H40V28H42.4783V29Z" fill="white"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M50.7391 29H47.4348V28H50.7391V29Z" fill="white"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M46.6087 29H43.3044V28H46.6087V29Z" fill="white"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M54.8696 29H51.5652V28H54.8696V29Z" fill="white"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M59 29H55.6956V28H59V29Z" fill="white"></path> <rect x="16" y="40" width="67" height="12" fill="#E9EAEE"></rect> </svg></span>',
						),
					),
				),

				/**
				 * Option: Banner Content Width.
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[' . $title_section . '-banner-width-type]',
					'type'       => 'control',
					'control'    => 'ast-selector',
					'section'    => $title_section,
					'default'    => astra_get_option( $title_section . '-banner-width-type', 'fullwidth' ),
					'priority'   => 10,
					'title'      => __( 'Container Width', 'astra' ),
					'choices'    => array(
						'fullwidth' => __( 'Full Width', 'astra' ),
						'custom'    => __( 'Custom', 'astra' ),
					),
					'divider'    => array( 'ast_class' => 'ast-top-divider ast-bottom-spacing' ),
					'responsive' => false,
					'renderAs'   => 'text',
					'context'    => array(
						Astra_Builder_Helper::$general_tab_config,
						'relation' => 'AND',
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[' . $title_section . '-layout]',
							'operator' => '===',
							'value'    => 'layout-2',
						),
					),
				),

				/**
				 * Option: Enter Width
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[' . $title_section . '-banner-custom-width]',
					'type'        => 'control',
					'control'     => 'ast-slider',
					'section'     => $title_section,
					'transport'   => 'postMessage',
					'default'     => astra_get_option( $title_section . '-banner-custom-width', 1200 ),
					'context'     => array(
						Astra_Builder_Helper::$general_tab_config,
						'relation' => 'AND',
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[' . $title_section . '-layout]',
							'operator' => '===',
							'value'    => 'layout-2',
						),
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[' . $title_section . '-banner-width-type]',
							'operator' => '===',
							'value'    => 'custom',
						),
					),
					'priority'    => 15,
					'title'       => __( 'Custom Width', 'astra' ),
					'suffix'      => 'px',
					'input_attrs' => array(
						'min'  => 768,
						'step' => 1,
						'max'  => 1920,
					),
				),

				/**
				 * Option: Display Post Structure
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[' . $title_section . '-structure]',
					'type'              => 'control',
					'control'           => 'ast-sortable',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_multi_choices' ),
					'section'           => $title_section,
					'context'           => Astra_Builder_Helper::$general_tab,
					'default'           => astra_get_option( $title_section . '-structure', 'page' === $post_type ? array( $title_section . '-image', $title_section . '-title' ) : array( $title_section . '-title', $title_section . '-meta' ) ),
					'priority'          => 20,
					'title'             => __( 'Structure', 'astra' ),
					'divider'           => array( 'ast_class' => 'ast-top-divider ast-bottom-spacing' ),
					'choices'           => array_merge(
						array(
							$title_section . '-title'      => __( 'Title', 'astra' ),
							$title_section . '-meta'       => __( 'Meta', 'astra' ),
							$title_section . '-breadcrumb' => __( 'Breadcrumb', 'astra' ),
							$title_section . '-excerpt'    => __( 'Excerpt', 'astra' ),
						),
						$structure_sub_controls
					),
				),

				/**
				 * Single product payment sub control Visa.
				 */
				array(
					'name'      => $title_section . '-featured-as-background',
					'parent'    => ASTRA_THEME_SETTINGS . '[' . $title_section . '-structure]',
					'default'   => astra_get_option( $title_section . '-featured-as-background', false ),
					'linked'    => $title_section . '-image',
					'type'      => 'sub-control',
					'control'   => 'ast-toggle',
					'section'   => $title_section,
					'priority'  => 5,
					'title'     => __( 'Use as Background', 'astra' ),
					'transport' => 'postMessage',
					'context'   => array(
						Astra_Builder_Helper::$general_tab_config,
						'relation' => 'AND',
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[' . $title_section . '-layout]',
							'operator' => '===',
							'value'    => 'layout-2',
						),
					),
				),

				/**
				 * Option: Featured Image Overlay Color.
				 */
				array(
					'name'     => $title_section . '-banner-featured-overlay',
					'parent'   => ASTRA_THEME_SETTINGS . '[' . $title_section . '-structure]',
					'default'  => astra_get_option( $title_section . '-banner-featured-overlay', '' ),
					'linked'   => $title_section . '-image',
					'type'     => 'sub-control',
					'control'  => 'ast-color',
					'section'  => $title_section,
					'priority' => 5,
					'title'    => __( 'Overlay Color', 'astra' ),
					'context'  => array(
						Astra_Builder_Helper::$general_tab_config,
						'relation' => 'AND',
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[' . $title_section . '-layout]',
							'operator' => '===',
							'value'    => 'layout-2',
						),
					),
				),

				array(
					'name'     => $title_section . '-featured-help-notice',
					'parent'   => ASTRA_THEME_SETTINGS . '[' . $title_section . '-structure]',
					'linked'   => $title_section . '-image',
					'type'     => 'sub-control',
					'control'  => 'ast-description',
					'section'  => $title_section,
					'priority' => 10,
					'label'    => '',
					'help'     => __( 'Note: These featured settings will only work for Layout 2 banner design.', 'astra' ),
				),

				array(
					'name'      => ASTRA_THEME_SETTINGS . '[' . $title_section . '-taxonomy-clone-tracker]',
					'section'   => $title_section,
					'type'      => 'control',
					'control'   => 'ast-hidden',
					'priority'  => 22,
					'transport' => 'postMessage',
					'partial'   => false,
					'default'   => astra_get_option( $title_section . '-taxonomy-clone-tracker', 1 ),
				),

				array(
					'name'              => ASTRA_THEME_SETTINGS . '[' . $title_section . '-metadata]',
					'type'              => 'control',
					'control'           => 'ast-sortable',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_multi_choices' ),
					'default'           => astra_get_option( $title_section . '-metadata', array( 'comments', 'author', 'date' ) ),
					'context'           => array(
						Astra_Builder_Helper::$general_tab_config,
						'relation' => 'AND',
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[' . $title_section . '-structure]',
							'operator' => 'contains',
							'value'    => $title_section . '-meta',
						),
					),
					'section'           => $title_section,
					'priority'          => 25,
					'divider'           => array( 'ast_class' => 'ast-bottom-spacing' ),
					'title'             => __( 'Meta', 'astra' ),
					'choices'           => array_merge(
						array(
							'comments' => __( 'Comments', 'astra' ),
							'author'   => __( 'Author', 'astra' ),
						),
						$meta_config_options
					),
				),

				/**
				 * Option: Date Meta Type.
				 */
				array(
					'name'       => $title_section . '-meta-date-type',
					'parent'     => ASTRA_THEME_SETTINGS . '[' . $title_section . '-metadata]',
					'type'       => 'sub-control',
					'control'    => 'ast-selector',
					'section'    => $title_section,
					'default'    => astra_get_option( $title_section . '-meta-date-type', 'published' ),
					'priority'   => 1,
					'linked'     => 'date',
					'transport'  => 'refresh',
					'title'      => __( 'Type', 'astra' ),
					'choices'    => array(
						'published' => __( 'Published', 'astra' ),
						'updated'   => __( 'Last Updated', 'astra' ),
					),
					'divider'    => array( 'ast_class' => 'ast-top-divider ast-bottom-spacing' ),
					'responsive' => false,
					'renderAs'   => 'text',
				),

				/**
				 * Date format support for meta field.
				 */
				array(
					'name'       => $title_section . '-date-format',
					'default'    => astra_get_option( $title_section . '-date-format', '' ),
					'parent'     => ASTRA_THEME_SETTINGS . '[' . $title_section . '-metadata]',
					'linked'     => 'date',
					'type'       => 'sub-control',
					'control'    => 'ast-select',
					'section'    => $title_section,
					'priority'   => 2,
					'responsive' => false,
					'renderAs'   => 'text',
					'title'      => __( 'Format', 'astra' ),
					'choices'    => array(
						''       => __( 'Default', 'astra' ),
						'F j, Y' => 'November 6, 2010',
						'Y-m-d'  => '2010-11-06',
						'm/d/Y'  => '11/06/2010',
						'd/m/Y'  => '06/11/2010',
					),
				),

				/**
				 * Option: Horizontal Alignment.
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[' . $title_section . '-horizontal-alignment]',
					'default'   => astra_get_option( $title_section . '-horizontal-alignment' ),
					'type'      => 'control',
					'control'   => 'ast-selector',
					'section'   => $title_section,
					'priority'  => 27,
					'title'     => __( 'Horizontal Alignment', 'astra' ),
					'context'   => Astra_Builder_Helper::$general_tab,
					'transport' => 'postMessage',
					'choices'   => array(
						'left'   => 'align-left',
						'center' => 'align-center',
						'right'  => 'align-right',
					),
					'divider'   => array( 'ast_class' => 'ast-top-divider' ),
				),

				/**
				 * Option: Vertical Alignment
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[' . $title_section . '-vertical-alignment]',
					'default'    => astra_get_option( $title_section . '-vertical-alignment', 'center' ),
					'type'       => 'control',
					'control'    => 'ast-selector',
					'section'    => $title_section,
					'priority'   => 28,
					'title'      => __( 'Vertical Alignment', 'astra' ),
					'choices'    => array(
						'flex-start' => __( 'Top', 'astra' ),
						'center'     => __( 'Middle', 'astra' ),
						'flex-end'   => __( 'Bottom', 'astra' ),
					),
					'divider'    => array( 'ast_class' => 'ast-top-divider ast-section-spacing' ),
					'context'    => array(
						Astra_Builder_Helper::$general_tab_config,
						'relation' => 'AND',
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[' . $title_section . '-layout]',
							'operator' => '===',
							'value'    => 'layout-2',
						),
					),
					'transport'  => 'postMessage',
					'renderAs'   => 'text',
					'responsive' => false,
				),

				/**
				 * Option: Container min height.
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[' . $title_section . '-banner-height]',
					'type'              => 'control',
					'control'           => 'ast-responsive-slider',
					'section'           => $title_section,
					'transport'         => 'postMessage',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
					'default'           => astra_get_option( $title_section . '-banner-height', Astra_Posts_Structure_Loader::get_customizer_default( 'responsive-slider' ) ),
					'context'           => array(
						Astra_Builder_Helper::$design_tab_config,
						'relation' => 'AND',
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[' . $title_section . '-layout]',
							'operator' => '===',
							'value'    => 'layout-2',
						),
					),
					'priority'          => 1,
					'title'             => __( 'Banner Min Height', 'astra' ),
					'suffix'            => 'px',
					'input_attrs'       => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 1000,
					),
					'divider'           => array( 'ast_class' => 'ast-bottom-divider ast-section-spacing' ),
				),

				/**
				 * Option: Elements gap.
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[' . $title_section . '-elements-gap]',
					'type'        => 'control',
					'control'     => 'ast-slider',
					'section'     => $title_section,
					'transport'   => 'postMessage',
					'default'     => astra_get_option( $title_section . '-elements-gap', 10 ),
					'context'     => Astra_Builder_Helper::$design_tab,
					'priority'    => 5,
					'title'       => __( 'Inner Elements Spacing', 'astra' ),
					'suffix'      => 'px',
					'input_attrs' => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 100,
					),
					'divider'     => array( 'ast_class' => 'ast-bottom-divider ast-bottom-spacing ast-section-spacing' ),
				),

				/**
				 * Option: Featured Image Custom Banner BG.
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[' . $title_section . '-banner-background]',
					'type'      => 'control',
					'default'   => astra_get_option( $title_section . '-banner-background', Astra_Posts_Structure_Loader::get_customizer_default( 'responsive-background' ) ),
					'section'   => $title_section,
					'control'   => 'ast-responsive-background',
					'title'     => __( 'Background', 'astra' ),
					'transport' => 'postMessage',
					'context'   => array(
						Astra_Builder_Helper::$design_tab_config,
						'relation' => 'AND',
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[' . $title_section . '-featured-as-background]',
							'operator' => '!=',
							'value'    => true,
						),
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[' . $title_section . '-layout]',
							'operator' => '===',
							'value'    => 'layout-2',
						),
					),
					'priority'  => 5,
				),

				/**
				 * Option: Title Color
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[' . $title_section . '-banner-title-color]',
					'type'      => 'control',
					'control'   => 'ast-color',
					'section'   => $title_section,
					'default'   => astra_get_option( $title_section . '-banner-title-color' ),
					'transport' => 'postMessage',
					'priority'  => 5,
					'title'     => __( 'Title Color', 'astra' ),
					'context'   => Astra_Builder_Helper::$design_tab,
				),

				/**
				 * Option: Text Color
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[' . $title_section . '-banner-text-color]',
					'type'      => 'control',
					'control'   => 'ast-color',
					'section'   => $title_section,
					'default'   => astra_get_option( $title_section . '-banner-text-color' ),
					'priority'  => 10,
					'title'     => __( 'Text Color', 'astra' ),
					'transport' => 'postMessage',
					'context'   => Astra_Builder_Helper::$design_tab,
				),

				/**
				 * Option: Link Color
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[' . $title_section . '-banner-link-color]',
					'type'      => 'control',
					'control'   => 'ast-color',
					'section'   => $title_section,
					'default'   => astra_get_option( $title_section . '-banner-link-color' ),
					'transport' => 'postMessage',
					'priority'  => 15,
					'title'     => __( 'Link Color', 'astra' ),
					'context'   => Astra_Builder_Helper::$design_tab,
				),

				/**
				 * Option: Link Hover Color
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[' . $title_section . '-banner-link-hover-color]',
					'type'      => 'control',
					'control'   => 'ast-color',
					'section'   => $title_section,
					'default'   => astra_get_option( $title_section . '-banner-link-hover-color' ),
					'transport' => 'postMessage',
					'priority'  => 20,
					'title'     => __( 'Link Hover Color', 'astra' ),
					'divider'   => array( 'ast_class' => 'ast-bottom-spacing' ),
					'context'   => Astra_Builder_Helper::$design_tab,
				),

				array(
					'name'      => ASTRA_THEME_SETTINGS . '[' . $title_section . '-banner-title-typography-group]',
					'type'      => 'control',
					'priority'  => 25,
					'control'   => 'ast-settings-group',
					'context'   => array(
						Astra_Builder_Helper::$design_tab_config,
						'relation' => 'AND',
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[' . $title_section . '-structure]',
							'operator' => 'contains',
							'value'    => $title_section . '-title',
						),
					),
					'divider'   => array( 'ast_class' => 'ast-top-divider' ),
					'title'     => __( 'Title Font', 'astra' ),
					'section'   => $title_section,
					'transport' => 'postMessage',
				),

				array(
					'name'      => ASTRA_THEME_SETTINGS . '[' . $title_section . '-banner-text-typography-group]',
					'type'      => 'control',
					'priority'  => 30,
					'control'   => 'ast-settings-group',
					'context'   => Astra_Builder_Helper::$design_tab,
					'title'     => __( 'Text Font', 'astra' ),
					'section'   => $title_section,
					'transport' => 'postMessage',
				),

				/**
				 * Option: Text Font Family
				 */
				array(
					'name'      => $title_section . '-text-font-family',
					'parent'    => ASTRA_THEME_SETTINGS . '[' . $title_section . '-banner-text-typography-group]',
					'section'   => $title_section,
					'type'      => 'sub-control',
					'control'   => 'ast-font',
					'font_type' => 'ast-font-family',
					'default'   => astra_get_option( $title_section . '-text-font-family', 'inherit' ),
					'title'     => __( 'Font Family', 'astra' ),
					'connect'   => ASTRA_THEME_SETTINGS . '[' . $title_section . '-text-font-weight]',
					'divider'   => array( 'ast_class' => 'ast-sub-bottom-dotted-divider' ),
				),

				/**
				 * Option: Text Font Weight
				 */
				array(
					'name'              => $title_section . '-text-font-weight',
					'parent'            => ASTRA_THEME_SETTINGS . '[' . $title_section . '-banner-text-typography-group]',
					'section'           => $title_section,
					'type'              => 'sub-control',
					'control'           => 'ast-font',
					'font_type'         => 'ast-font-weight',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'default'           => astra_get_option( $title_section . '-text-font-weight', 'inherit' ),
					'title'             => __( 'Font Weight', 'astra' ),
					'connect'           => $title_section . '-text-font-family',
					'divider'           => array( 'ast_class' => 'ast-sub-bottom-dotted-divider' ),
				),


				/**
				 * Option: Text Font Size
				 */

				array(
					'name'              => $title_section . '-text-font-size',
					'parent'            => ASTRA_THEME_SETTINGS . '[' . $title_section . '-banner-text-typography-group]',
					'section'           => $title_section,
					'type'              => 'sub-control',
					'control'           => 'ast-responsive-slider',
					'default'           => astra_get_option( $title_section . '-text-font-size', Astra_Posts_Structure_Loader::get_customizer_default( 'font-size' ) ),
					'transport'         => 'postMessage',
					'title'             => __( 'Font Size', 'astra' ),
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
					'suffix'            => array( 'px', 'em' ),
					'input_attrs'       => array(
						'px' => array(
							'min'  => 0,
							'step' => 1,
							'max'  => 100,
						),
						'em' => array(
							'min'  => 0,
							'step' => 1,
							'max'  => 20,
						),
					),
				),

				/**
				 * Option: Single Post Banner Text Font Extras
				 */
				array(
					'name'    => $title_section . '-text-font-extras',
					'parent'  => ASTRA_THEME_SETTINGS . '[' . $title_section . '-banner-text-typography-group]',
					'section' => $title_section,
					'type'    => 'sub-control',
					'control' => 'ast-font-extras',
					'default' => astra_get_option( $title_section . '-text-font-extras', Astra_Posts_Structure_Loader::get_customizer_default( 'font-extras' ) ),
					'title'   => __( 'Font Extras', 'astra' ),
				),

				/**
				 * Option: Title Font Family
				 */
				array(
					'name'      => $title_section . '-title-font-family',
					'parent'    => ASTRA_THEME_SETTINGS . '[' . $title_section . '-banner-title-typography-group]',
					'section'   => $title_section,
					'type'      => 'sub-control',
					'control'   => 'ast-font',
					'font_type' => 'ast-font-family',
					'default'   => astra_get_option( $title_section . '-title-font-family', 'inherit' ),
					'title'     => __( 'Font Family', 'astra' ),
					'connect'   => ASTRA_THEME_SETTINGS . '[' . $title_section . '-title-font-weight]',
					'divider'   => array( 'ast_class' => 'ast-sub-bottom-dotted-divider' ),
				),

				/**
				 * Option: Title Font Weight
				 */
				array(
					'name'              => $title_section . '-title-font-weight',
					'parent'            => ASTRA_THEME_SETTINGS . '[' . $title_section . '-banner-title-typography-group]',
					'section'           => $title_section,
					'type'              => 'sub-control',
					'control'           => 'ast-font',
					'font_type'         => 'ast-font-weight',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'default'           => astra_get_option( $title_section . '-title-font-weight', Astra_Posts_Structure_Loader::get_customizer_default( 'title-font-weight' ) ),
					'title'             => __( 'Font Weight', 'astra' ),
					'connect'           => $title_section . '-title-font-family',
					'divider'           => array( 'ast_class' => 'ast-sub-bottom-dotted-divider' ),
				),

				/**
				 * Option: Title Font Size
				 */

				array(
					'name'              => $title_section . '-title-font-size',
					'parent'            => ASTRA_THEME_SETTINGS . '[' . $title_section . '-banner-title-typography-group]',
					'section'           => $title_section,
					'type'              => 'sub-control',
					'control'           => 'ast-responsive-slider',
					'default'           => astra_get_option( $title_section . '-title-font-size', Astra_Posts_Structure_Loader::get_customizer_default( 'title-font-size' ) ),
					'transport'         => 'postMessage',
					'title'             => __( 'Font Size', 'astra' ),
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
					'suffix'            => array( 'px', 'em' ),
					'input_attrs'       => array(
						'px' => array(
							'min'  => 0,
							'step' => 1,
							'max'  => 100,
						),
						'em' => array(
							'min'  => 0,
							'step' => 1,
							'max'  => 20,
						),
					),
				),

				/**
				 * Option: Single Post Banner Title Font Extras
				 */
				array(
					'name'    => $title_section . '-title-font-extras',
					'parent'  => ASTRA_THEME_SETTINGS . '[' . $title_section . '-banner-title-typography-group]',
					'section' => $title_section,
					'type'    => 'sub-control',
					'control' => 'ast-font-extras',
					'default' => astra_get_option( $title_section . '-title-font-extras', Astra_Posts_Structure_Loader::get_customizer_default( 'font-extras' ) ),
					'title'   => __( 'Font Extras', 'astra' ),
				),

				array(
					'name'      => ASTRA_THEME_SETTINGS . '[' . $title_section . '-banner-meta-typography-group]',
					'type'      => 'control',
					'priority'  => 35,
					'control'   => 'ast-settings-group',
					'context'   => array(
						Astra_Builder_Helper::$design_tab_config,
						'relation' => 'AND',
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[' . $title_section . '-structure]',
							'operator' => 'contains',
							'value'    => $title_section . '-meta',
						),
					),
					'title'     => __( 'Meta Font', 'astra' ),
					'divider'   => array( 'ast_class' => 'ast-bottom-spacing' ),
					'section'   => $title_section,
					'transport' => 'postMessage',
				),

				/**
				 * Option: Meta Font Family
				 */
				array(
					'name'      => $title_section . '-meta-font-family',
					'parent'    => ASTRA_THEME_SETTINGS . '[' . $title_section . '-banner-meta-typography-group]',
					'section'   => $title_section,
					'type'      => 'sub-control',
					'control'   => 'ast-font',
					'font_type' => 'ast-font-family',
					'default'   => astra_get_option( $title_section . '-meta-font-family', 'inherit' ),
					'title'     => __( 'Font Family', 'astra' ),
					'connect'   => ASTRA_THEME_SETTINGS . '[' . $title_section . '-meta-font-weight]',
					'divider'   => array( 'ast_class' => 'ast-sub-bottom-dotted-divider' ),
				),

				/**
				 * Option: Meta Font Weight
				 */
				array(
					'name'              => $title_section . '-meta-font-weight',
					'parent'            => ASTRA_THEME_SETTINGS . '[' . $title_section . '-banner-meta-typography-group]',
					'section'           => $title_section,
					'type'              => 'sub-control',
					'control'           => 'ast-font',
					'font_type'         => 'ast-font-weight',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'default'           => astra_get_option( $title_section . '-meta-font-weight', 'inherit' ),
					'title'             => __( 'Font Weight', 'astra' ),
					'connect'           => $title_section . '-meta-font-family',
					'divider'           => array( 'ast_class' => 'ast-sub-bottom-dotted-divider' ),
				),

				/**
				 * Option: Meta Font Size
				 */
				array(
					'name'              => $title_section . '-meta-font-size',
					'parent'            => ASTRA_THEME_SETTINGS . '[' . $title_section . '-banner-meta-typography-group]',
					'section'           => $title_section,
					'type'              => 'sub-control',
					'control'           => 'ast-responsive-slider',
					'default'           => astra_get_option( $title_section . '-meta-font-size', Astra_Posts_Structure_Loader::get_customizer_default( 'font-size' ) ),
					'transport'         => 'postMessage',
					'title'             => __( 'Font Size', 'astra' ),
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
					'suffix'            => array( 'px', 'em' ),
					'input_attrs'       => array(
						'px' => array(
							'min'  => 0,
							'step' => 1,
							'max'  => 100,
						),
						'em' => array(
							'min'  => 0,
							'step' => 1,
							'max'  => 20,
						),
					),
				),

				/**
				 * Option: Single Post Banner Title Font Extras
				 */
				array(
					'name'    => $title_section . '-meta-font-extras',
					'parent'  => ASTRA_THEME_SETTINGS . '[' . $title_section . '-banner-meta-typography-group]',
					'section' => $title_section,
					'type'    => 'sub-control',
					'control' => 'ast-font-extras',
					'default' => astra_get_option( $title_section . '-meta-font-extras', Astra_Posts_Structure_Loader::get_customizer_default( 'font-extras' ) ),
					'title'   => __( 'Font Extras', 'astra' ),
				),

				array(
					'name'              => ASTRA_THEME_SETTINGS . '[' . $title_section . '-banner-margin]',
					'default'           => astra_get_option( $title_section . '-banner-margin', Astra_Posts_Structure_Loader::get_customizer_default( 'responsive-spacing' ) ),
					'type'              => 'control',
					'control'           => 'ast-responsive-spacing',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
					'section'           => $title_section,
					'title'             => __( 'Margin', 'astra' ),
					'linked_choices'    => true,
					'transport'         => 'postMessage',
					'divider'           => array( 'ast_class' => 'ast-top-divider' ),
					'unit_choices'      => array( 'px', 'em', '%' ),
					'choices'           => array(
						'top'    => __( 'Top', 'astra' ),
						'right'  => __( 'Right', 'astra' ),
						'bottom' => __( 'Bottom', 'astra' ),
						'left'   => __( 'Left', 'astra' ),
					),
					'context'           => array(
						Astra_Builder_Helper::$design_tab_config,
						'relation' => 'AND',
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[' . $title_section . '-layout]',
							'operator' => '===',
							'value'    => 'layout-2',
						),
					),
					'priority'          => 100,
					'connected'         => false,
				),

				array(
					'name'              => ASTRA_THEME_SETTINGS . '[' . $title_section . '-banner-padding]',
					'default'           => astra_get_option( $title_section . '-banner-padding', Astra_Posts_Structure_Loader::get_customizer_default( 'responsive-padding' ) ),
					'type'              => 'control',
					'control'           => 'ast-responsive-spacing',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
					'section'           => $title_section,
					'title'             => __( 'Padding', 'astra' ),
					'linked_choices'    => true,
					'transport'         => 'postMessage',
					'unit_choices'      => array( 'px', 'em', '%' ),
					'choices'           => array(
						'top'    => __( 'Top', 'astra' ),
						'right'  => __( 'Right', 'astra' ),
						'bottom' => __( 'Bottom', 'astra' ),
						'left'   => __( 'Left', 'astra' ),
					),
					'context'           => array(
						Astra_Builder_Helper::$design_tab_config,
						'relation' => 'AND',
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[' . $title_section . '-layout]',
							'operator' => '===',
							'value'    => 'layout-2',
						),
					),
					'priority'          => 120,
					'connected'         => false,
				),
			);

			/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			if ( count( $taxonomies ) > 1 ) {
				/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
				for ( $index = 1; $index <= $clone_limit; $index++ ) {

					$control_suffix = ( 1 === $index ) ? '' : '-' . ( $index - 1 );

					/**
					 * Option: Taxonomy Selection.
					 */
					$_configs[] = array(
						'name'      => $title_section . '-taxonomy' . $control_suffix,
						'parent'    => ASTRA_THEME_SETTINGS . '[' . $title_section . '-metadata]',
						'default'   => astra_get_option( $title_section . '-taxonomy' . $control_suffix ),
						'linked'    => $title_section . '-taxonomy' . $control_suffix,
						'type'      => 'sub-control',
						'control'   => 'ast-select',
						'transport' => 'refresh',
						'section'   => $title_section,
						'priority'  => 5,
						'title'     => __( 'Select Taxonomy', 'astra' ),
						'choices'   => $taxonomies,
					);
				}
			}

			$configurations = array_merge( $configurations, $_configs );
		}

		return $configurations;
	}
}

/**
 * Kicking this off by creating new object.
 */
new Astra_Posts_Single_Structures_Configs();
