<?php
/**
 * This file is part of the MailPoet plugin.
 *
 * @package MailPoet\EmailEditor
 */

declare( strict_types = 1 );
namespace MailPoet\EmailEditor\Integrations\Core\Renderer\Blocks;

use MailPoet\EmailEditor\Engine\Renderer\ContentRenderer\Block_Renderer;
use MailPoet\EmailEditor\Engine\Settings_Controller;
use WP_Style_Engine;

/**
 * Shared functionality for block renderers.
 */
abstract class Abstract_Block_Renderer implements Block_Renderer {
	/**
	 * Wrapper for wp_style_engine_get_styles which ensures all values are returned.
	 *
	 * @param array $block_styles Array of block styles.
	 * @param bool  $skip_convert_vars If true, --wp_preset--spacing--x type values will be left in the original var:preset:spacing:x format.
	 * @return array
	 */
	protected function get_styles_from_block( array $block_styles, $skip_convert_vars = false ) {
		$styles = wp_style_engine_get_styles( $block_styles, array( 'convert_vars_to_classnames' => $skip_convert_vars ) );
		return wp_parse_args(
			$styles,
			array(
				'css'          => '',
				'declarations' => array(),
				'classnames'   => '',
			)
		);
	}

	/**
	 * Compile objects containing CSS properties to a string.
	 *
	 * @param array ...$styles Style arrays to compile.
	 * @return string
	 */
	protected function compile_css( ...$styles ): string {
		return WP_Style_Engine::compile_css( array_merge( ...$styles ), '' );
	}

	/**
	 * Add a spacer around the block.
	 *
	 * @param string $content The block content.
	 * @param array  $email_attrs The email attributes.
	 * @return string
	 */
	protected function add_spacer( $content, $email_attrs ): string {
		$gap_style     = WP_Style_Engine::compile_css( array_intersect_key( $email_attrs, array_flip( array( 'margin-top' ) ) ), '' );
		$padding_style = WP_Style_Engine::compile_css( array_intersect_key( $email_attrs, array_flip( array( 'padding-left', 'padding-right' ) ) ), '' );

		if ( ! $gap_style && ! $padding_style ) {
			return $content;
		}

		return sprintf(
			'<!--[if mso | IE]><table align="left" role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%%" style="%2$s"><tr><td style="%3$s"><![endif]-->
      <div class="email-block-layout" style="%2$s %3$s">%1$s</div>
      <!--[if mso | IE]></td></tr></table><![endif]-->',
			$content,
			esc_attr( $gap_style ),
			esc_attr( $padding_style )
		);
	}

	/**
	 * Render the block.
	 *
	 * @param string              $block_content The block content.
	 * @param array               $parsed_block The parsed block.
	 * @param Settings_Controller $settings_controller The settings controller.
	 * @return string
	 */
	public function render( string $block_content, array $parsed_block, Settings_Controller $settings_controller ): string {
		return $this->add_spacer(
			$this->render_content( $block_content, $parsed_block, $settings_controller ),
			$parsed_block['email_attrs'] ?? array()
		);
	}

	/**
	 * Render the block content.
	 *
	 * @param string              $block_content The block content.
	 * @param array               $parsed_block The parsed block.
	 * @param Settings_Controller $settings_controller The settings controller.
	 * @return string
	 */
	abstract protected function render_content( string $block_content, array $parsed_block, Settings_Controller $settings_controller ): string;
}
