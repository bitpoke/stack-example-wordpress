<?php
/**
 * This file is part of the MailPoet Email Editor package.
 *
 * @package MailPoet\EmailEditor
 */

declare(strict_types = 1);
namespace MailPoet\EmailEditor\Engine\Renderer\ContentRenderer;

use MailPoet\EmailEditor\Engine\Renderer\ContentRenderer\Postprocessors\Highlighting_Postprocessor;
use MailPoet\EmailEditor\Engine\Renderer\ContentRenderer\Postprocessors\Postprocessor;
use MailPoet\EmailEditor\Engine\Renderer\ContentRenderer\Postprocessors\Variables_Postprocessor;
use MailPoet\EmailEditor\Engine\Renderer\ContentRenderer\Preprocessors\Blocks_Width_Preprocessor;
use MailPoet\EmailEditor\Engine\Renderer\ContentRenderer\Preprocessors\Cleanup_Preprocessor;
use MailPoet\EmailEditor\Engine\Renderer\ContentRenderer\Preprocessors\Preprocessor;
use MailPoet\EmailEditor\Engine\Renderer\ContentRenderer\Preprocessors\Spacing_Preprocessor;
use MailPoet\EmailEditor\Engine\Renderer\ContentRenderer\Preprocessors\Typography_Preprocessor;

/**
 * Class Process_Manager
 */
class Process_Manager {
	/**
	 * List of preprocessors
	 *
	 * @var Preprocessor[]
	 */
	private $preprocessors = array();

	/**
	 * List of postprocessors
	 *
	 * @var Postprocessor[]
	 */
	private $postprocessors = array();

	/**
	 * Process_Manager constructor.
	 *
	 * @param Cleanup_Preprocessor       $cleanup_preprocessor Cleanup preprocessor.
	 * @param Blocks_Width_Preprocessor  $blocks_width_preprocessor Blocks width preprocessor.
	 * @param Typography_Preprocessor    $typography_preprocessor Typography preprocessor.
	 * @param Spacing_Preprocessor       $spacing_preprocessor Spacing preprocessor.
	 * @param Highlighting_Postprocessor $highlighting_postprocessor Highlighting postprocessor.
	 * @param Variables_Postprocessor    $variables_postprocessor Variables postprocessor.
	 */
	public function __construct(
		Cleanup_Preprocessor $cleanup_preprocessor,
		Blocks_Width_Preprocessor $blocks_width_preprocessor,
		Typography_Preprocessor $typography_preprocessor,
		Spacing_Preprocessor $spacing_preprocessor,
		Highlighting_Postprocessor $highlighting_postprocessor,
		Variables_Postprocessor $variables_postprocessor
	) {
		$this->register_preprocessor( $cleanup_preprocessor );
		$this->register_preprocessor( $blocks_width_preprocessor );
		$this->register_preprocessor( $typography_preprocessor );
		$this->register_preprocessor( $spacing_preprocessor );
		$this->register_postprocessor( $highlighting_postprocessor );
		$this->register_postprocessor( $variables_postprocessor );
	}

	/**
	 * Method to preprocess blocks
	 *
	 * @param array                                                                                                             $parsed_blocks Parsed blocks.
	 * @param array{contentSize: string, wideSize?: string, allowEditing?: bool, allowCustomContentAndWideSize?: bool}          $layout Layout.
	 * @param array{spacing: array{padding: array{bottom: string, left: string, right: string, top: string}, blockGap: string}} $styles Styles.
	 * @return array
	 */
	public function preprocess( array $parsed_blocks, array $layout, array $styles ): array {
		foreach ( $this->preprocessors as $preprocessor ) {
			$parsed_blocks = $preprocessor->preprocess( $parsed_blocks, $layout, $styles );
		}
		return $parsed_blocks;
	}

	/**
	 * Method to postprocess the content
	 *
	 * @param string $html HTML content.
	 * @return string
	 */
	public function postprocess( string $html ): string {
		foreach ( $this->postprocessors as $postprocessor ) {
			$html = $postprocessor->postprocess( $html );
		}
		return $html;
	}

	/**
	 * Register preprocessor
	 *
	 * @param Preprocessor $preprocessor Preprocessor.
	 */
	public function register_preprocessor( Preprocessor $preprocessor ): void {
		$this->preprocessors[] = $preprocessor;
	}

	/**
	 * Register postprocessor
	 *
	 * @param Postprocessor $postprocessor Postprocessor.
	 */
	public function register_postprocessor( Postprocessor $postprocessor ): void {
		$this->postprocessors[] = $postprocessor;
	}
}
