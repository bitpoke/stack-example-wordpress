<?php
/**
 * This file is part of the MailPoet Email Editor package.
 *
 * @package MailPoet\EmailEditor
 */

declare(strict_types = 1);
namespace MailPoet\EmailEditor\Engine\Renderer\ContentRenderer;

use MailPoet\EmailEditor\Engine\Renderer\Css_Inliner;
use MailPoet\EmailEditor\Engine\Settings_Controller;
use MailPoet\EmailEditor\Engine\Theme_Controller;
use WP_Block_Template;
use WP_Post;

/**
 * Class Content_Renderer
 */
class Content_Renderer {
	/**
	 * Blocks registry
	 *
	 * @var Blocks_Registry
	 */
	private Blocks_Registry $blocks_registry;

	/**
	 * Process manager
	 *
	 * @var Process_Manager
	 */
	private Process_Manager $process_manager;

	/**
	 * Settings controller
	 *
	 * @var Settings_Controller
	 */
	private Settings_Controller $settings_controller;

	/**
	 * Theme controller
	 *
	 * @var Theme_Controller
	 */
	private Theme_Controller $theme_controller;

	const CONTENT_STYLES_FILE = 'content.css';

	/**
	 * CSS inliner
	 *
	 * @var Css_Inliner
	 */
	private Css_Inliner $css_inliner;

	/**
	 * Content_Renderer constructor.
	 *
	 * @param Process_Manager     $preprocess_manager Preprocess manager.
	 * @param Blocks_Registry     $blocks_registry Blocks registry.
	 * @param Settings_Controller $settings_controller Settings controller.
	 * @param Css_Inliner         $css_inliner Css inliner.
	 * @param Theme_Controller    $theme_controller Theme controller.
	 */
	public function __construct(
		Process_Manager $preprocess_manager,
		Blocks_Registry $blocks_registry,
		Settings_Controller $settings_controller,
		Css_Inliner $css_inliner,
		Theme_Controller $theme_controller
	) {
		$this->process_manager     = $preprocess_manager;
		$this->blocks_registry     = $blocks_registry;
		$this->settings_controller = $settings_controller;
		$this->theme_controller    = $theme_controller;
		$this->css_inliner         = $css_inliner;
	}

	/**
	 * Initialize the content renderer
	 *
	 * @return void
	 */
	private function initialize() {
		add_filter( 'render_block', array( $this, 'render_block' ), 10, 2 );
		add_filter( 'block_parser_class', array( $this, 'block_parser' ) );
		add_filter( 'mailpoet_blocks_renderer_parsed_blocks', array( $this, 'preprocess_parsed_blocks' ) );

		do_action( 'mailpoet_blocks_renderer_initialized', $this->blocks_registry );
	}

	/**
	 * Render the content
	 *
	 * @param WP_Post           $post Post object.
	 * @param WP_Block_Template $template Block template.
	 * @return string
	 */
	public function render( WP_Post $post, WP_Block_Template $template ): string {
		$this->set_template_globals( $post, $template );
		$this->initialize();
		$rendered_html = get_the_block_template_html();
		$this->reset();

		return $this->process_manager->postprocess( $this->inline_styles( $rendered_html, $post, $template ) );
	}

	/**
	 * Get block parser class
	 *
	 * @return string
	 */
	public function block_parser() {
		return 'MailPoet\EmailEditor\Engine\Renderer\ContentRenderer\Blocks_Parser';
	}

	/**
	 * Preprocess parsed blocks
	 *
	 * @param array $parsed_blocks Parsed blocks.
	 * @return array
	 */
	public function preprocess_parsed_blocks( array $parsed_blocks ): array {
		return $this->process_manager->preprocess( $parsed_blocks, $this->theme_controller->get_layout_settings(), $this->theme_controller->get_styles() );
	}

	/**
	 * Renders block
	 * Translates block's HTML to HTML suitable for email clients. The method is intended as a callback for 'render_block' filter.
	 *
	 * @param string $block_content Block content.
	 * @param array  $parsed_block Parsed block.
	 * @return string
	 */
	public function render_block( string $block_content, array $parsed_block ): string {
		$renderer = $this->blocks_registry->get_block_renderer( $parsed_block['blockName'] );
		if ( ! $renderer ) {
			$renderer = $this->blocks_registry->get_fallback_renderer();
		}
		return $renderer ? $renderer->render( $block_content, $parsed_block, $this->settings_controller ) : $block_content;
	}

	/**
	 * Set template globals
	 *
	 * @param WP_Post           $post Post object.
	 * @param WP_Block_Template $template Block template.
	 * @return void
	 */
	private function set_template_globals( WP_Post $post, WP_Block_Template $template ) {
		global $_wp_current_template_content, $_wp_current_template_id;
		$_wp_current_template_id      = $template->id;
		$_wp_current_template_content = $template->content;
		$GLOBALS['post']              = $post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- I have not found a better way to set the post object for the block renderer.
	}

	/**
	 * As we use default WordPress filters, we need to remove them after email rendering
	 * so that we don't interfere with possible post rendering that might happen later.
	 */
	private function reset(): void {
		$this->blocks_registry->remove_all_block_renderers();
		remove_filter( 'render_block', array( $this, 'render_block' ) );
		remove_filter( 'block_parser_class', array( $this, 'block_parser' ) );
		remove_filter( 'mailpoet_blocks_renderer_parsed_blocks', array( $this, 'preprocess_parsed_blocks' ) );
	}

	/**
	 * Method to inline styles into the HTML
	 *
	 * @param string                 $html HTML content.
	 * @param WP_Post                $post Post object.
	 * @param WP_Block_Template|null $template Block template.
	 * @return string
	 */
	private function inline_styles( $html, WP_Post $post, $template = null ) {
		$styles  = (string) file_get_contents( __DIR__ . '/' . self::CONTENT_STYLES_FILE );
		$styles .= (string) file_get_contents( __DIR__ . '/../../content-shared.css' );

		// Apply default contentWidth to constrained blocks.
		$layout  = $this->theme_controller->get_layout_settings();
		$styles .= sprintf(
			'
      .is-layout-constrained > *:not(.alignleft):not(.alignright):not(.alignfull) {
        max-width: %1$s;
        margin-left: auto !important;
        margin-right: auto !important;
      }
      .is-layout-constrained > .alignwide {
        max-width: %2$s;
        margin-left: auto !important;
        margin-right: auto !important;
      }
      ',
			$layout['contentSize'],
			$layout['wideSize']
		);

		// Get styles from theme.
		$styles              .= $this->theme_controller->get_stylesheet_for_rendering( $post, $template );
		$block_support_styles = $this->theme_controller->get_stylesheet_from_context( 'block-supports', array() );
		// Get styles from block-supports stylesheet. This includes rules such as layout (contentWidth) that some blocks use.
		// @see https://github.com/WordPress/WordPress/blob/3c5da9c74344aaf5bf8097f2e2c6a1a781600e03/wp-includes/script-loader.php#L3134
		// @internal :where is not supported by emogrifier, so we need to replace it with *.
		$block_support_styles = str_replace(
			':where(:not(.alignleft):not(.alignright):not(.alignfull))',
			'*:not(.alignleft):not(.alignright):not(.alignfull)',
			$block_support_styles
		);

		/*
		 * Layout CSS assumes the top level block will have a single DIV wrapper with children. Since our blocks use tables,
		 * we need to adjust this to look for children in the TD element. This may requires more advanced replacement but
		 * this works in the current version of Gutenberg.
		 * Example rule we're targetting: .wp-container-core-group-is-layout-1.wp-container-core-group-is-layout-1 > *
		 */
		$block_support_styles = preg_replace(
			'/group-is-layout-(\d+) >/',
			'group-is-layout-$1 > tbody tr td >',
			$block_support_styles
		);

		$styles .= $block_support_styles;

		/*
		 * Debugging for content styles. Remember these get inlined.
		 * echo '<pre>';
		 * var_dump($styles);
		 * echo '</pre>';
		 */

		$styles = '<style>' . wp_strip_all_tags( (string) apply_filters( 'mailpoet_email_content_renderer_styles', $styles, $post ) ) . '</style>';

		return $this->css_inliner->from_html( $styles . $html )->inline_css()->render();
	}
}
