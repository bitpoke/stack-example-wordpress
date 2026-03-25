<?php
/**
 * Abilities Init
 *
 * Main initialization class for Astra Abilities API integration.
 * Registers the 'astra' category and loads all ability classes.
 *
 * @package Astra
 * @subpackage Abilities
 * @since 4.12.6
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Astra_Abilities_Init
 */
class Astra_Abilities_Init {
	/**
	 * Instance of this class.
	 *
	 * @var self|null
	 */
	private static $instance = null;

	/**
	 * Whether abilities have been registered.
	 *
	 * @var bool
	 */
	private $registered = false;

	/**
	 * Whether categories have been registered.
	 *
	 * @var bool
	 */
	private $categories_registered = false;

	/**
	 * Get singleton instance.
	 *
	 * @return self
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Support both pre-6.9 and 6.9+ (core) action names.
		add_action( 'abilities_api_categories_init', array( $this, 'register_categories' ) );
		add_action( 'wp_abilities_api_categories_init', array( $this, 'register_categories' ) );

		add_action( 'abilities_api_init', array( $this, 'register_abilities' ) );
		add_action( 'wp_abilities_api_init', array( $this, 'register_abilities' ) );
	}

	/**
	 * Register ability categories.
	 *
	 * @return void
	 */
	public function register_categories() {
		if ( $this->categories_registered ) {
			return;
		}

		if ( ! function_exists( 'wp_register_ability_category' ) ) {
			return;
		}

		wp_register_ability_category(
			'astra',
			array(
				'label'       => __( 'Astra Theme', 'astra' ),
				'description' => __( 'Astra theme customization abilities for typography, colors, layout, and design settings.', 'astra' ),
			)
		);

		$this->categories_registered = true;
	}

	/**
	 * Register all Astra abilities.
	 *
	 * @return void
	 */
	public function register_abilities() {
		if ( $this->registered ) {
			return;
		}

		if ( ! function_exists( 'wp_register_ability' ) ) {
			return;
		}

		$abilities_dir = ASTRA_THEME_DIR . 'inc/abilities/';

		$ability_files = array(
			// Typography abilities.
			'customizer/globals/typography/class-astra-get-body-font',
			'customizer/globals/typography/class-astra-update-body-font',
			'customizer/globals/typography/class-astra-get-headings-font',
			'customizer/globals/typography/class-astra-update-headings-font',
			'customizer/globals/typography/class-astra-list-font-families',
			// Individual heading typography abilities (H1-H6) — registered via loop.
			'customizer/globals/typography/class-astra-get-heading-font',
			'customizer/globals/typography/class-astra-update-heading-font',
			// Paragraph margin and underline links abilities.
			'customizer/globals/typography/class-astra-get-paragraph-margin',
			'customizer/globals/typography/class-astra-update-paragraph-margin',
			'customizer/globals/typography/class-astra-get-underline-links-status',
			'customizer/globals/typography/class-astra-toggle-underline-links',

			// Colors abilities.
			'customizer/globals/colors/class-astra-get-global-palette',
			'customizer/globals/colors/class-astra-update-global-palette',
			'customizer/globals/colors/class-astra-get-background-colors',
			'customizer/globals/colors/class-astra-update-background-colors',
			'customizer/globals/colors/class-astra-update-theme-colors',

			// Container abilities.
			'customizer/globals/container/class-astra-get-container-layout',
			'customizer/globals/container/class-astra-update-container-layout',
			'customizer/globals/container/class-astra-list-container-settings',

			// Buttons abilities.
			'customizer/globals/buttons/class-astra-get-global-buttons',
			'customizer/globals/buttons/class-astra-update-global-buttons',

			// Blogs / Post Types abilities.
			'customizer/posttypes/blog/class-astra-get-blog-archive',
			'customizer/posttypes/blog/class-astra-update-blog-archive',
			'customizer/posttypes/blog/class-astra-get-single-post',
			'customizer/posttypes/blog/class-astra-update-single-post',
			'customizer/posttypes/blog/class-astra-get-single-page',
			'customizer/posttypes/blog/class-astra-update-single-page',

			// Site Identity abilities.
			'customizer/siteidentity/class-astra-get-site-title-logo',
			'customizer/siteidentity/class-astra-update-site-title-logo',

			// Breadcrumb abilities.
			'customizer/general/breadcrumb/class-astra-get-breadcrumb',
			'customizer/general/breadcrumb/class-astra-update-breadcrumb',

			// Post Meta abilities.
			'admin/postmeta/class-astra-get-postmeta',
			'admin/postmeta/class-astra-update-postmeta',
		);

		foreach ( $ability_files as $file ) {
			require_once $abilities_dir . $file . '.php';
		}

		$this->registered = true;
	}
}
