<?php
declare(strict_types=1);

namespace Automattic\WooCommerce\Blocks\BlockTypes\AddToCartWithOptions;

use Automattic\WooCommerce\Blocks\BlockTypes\AbstractBlock;
use Automattic\WooCommerce\Blocks\BlockTypes\EnableBlockJsonAssetsTrait;
use Automattic\WooCommerce\Blocks\Utils\StyleAttributesUtils;

/**
 * Block type for variation selector attribute options in add to cart with options.
 * It's responsible to render the attribute options.
 */
class VariationSelectorAttributeOptions extends AbstractBlock {

	use EnableBlockJsonAssetsTrait;

	/**
	 * Block name.
	 *
	 * @var string
	 */
	protected $block_name = 'add-to-cart-with-options-variation-selector-attribute-options';

	/**
	 * Get the block's attributes.
	 *
	 * @param array $attributes Block attributes. Default empty array.
	 * @return array  Block attributes merged with defaults.
	 */
	private function parse_attributes( $attributes ) {
		// These should match what's set in JS `registerBlockType`.
		$defaults = array(
			'style' => 'pills',
		);

		return wp_parse_args( $attributes, $defaults );
	}

	/**
	 * Render the block.
	 *
	 * @param array    $attributes Block attributes.
	 * @param string   $content Block content.
	 * @param WP_Block $block Block instance.
	 * @return string Rendered block output.
	 */
	protected function render( $attributes, $content, $block ): string {
		if ( empty( $block->context ) ) {
			return '';
		}

		$attribute_name = $block->context['woocommerce/attributeName'];

		if ( isset( $attribute_name ) ) {

			$attributes = $this->parse_attributes( $attributes );

			$classes_and_styles = StyleAttributesUtils::get_classes_and_styles_by_attributes( $attributes, array(), array( 'extra_classes' ) );

			$field_style = $attributes['style'];

			$wrapper_attributes = get_block_wrapper_attributes(
				array(
					'data-wp-interactive' => 'woocommerce/add-to-cart-with-options',
					'class'               => esc_attr( $classes_and_styles['classes'] ),
					'style'               => esc_attr( $classes_and_styles['styles'] ),
				)
			);

			if ( 'dropdown' === $field_style ) {
				$content = $this->render_dropdown( $attributes, $content, $block );
			} else {
				$content = $this->render_pills( $attributes, $content, $block );
			}

			return sprintf(
				'<div %s>%s</div>',
				$wrapper_attributes,
				$content
			);
		}

		return '';
	}

	/**
	 * Get the normalized version of the attributes.
	 *
	 * @param array $attributes         The element's attributes.
	 * @param array $default_attributes The element's default attributes.
	 * @return string The HTML element's attributes.
	 */
	public static function get_normalized_attributes( $attributes, $default_attributes = array() ) {
		$normalized_attributes = array();

		$merged_attributes = array_merge( $default_attributes, $attributes );

		foreach ( $merged_attributes as $key => $value ) {
			if ( is_null( $value ) ) {
				continue;
			}
			if ( is_array( $value ) || is_object( $value ) ) {
				$value = wp_json_encode(
					$value,
					JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
				);
			}
			$normalized_attributes[] = sprintf( '%s="%s"', esc_attr( $key ), esc_attr( $value ) );
		}

		return implode( ' ', $normalized_attributes );
	}

	/**
	 * Get the default selected attribute.
	 *
	 * @param array $attribute_terms The attribute's.
	 * @return string|null The default selected attribute.
	 */
	protected function get_default_selected_attribute( $attribute_terms ) {
		foreach ( $attribute_terms as $attribute_term ) {
			if ( $attribute_term['isSelected'] ) {
				return $attribute_term['value'];
			}
		}

		return null;
	}

	/**
	 * Render the attribute options as pills.
	 *
	 * @param array    $attributes Block attributes.
	 * @param string   $content Block content.
	 * @param WP_Block $block Block instance.
	 * @return string The pills.
	 */
	protected function render_pills( $attributes, $content, $block ) {
		$attribute_id    = $block->context['woocommerce/attributeId'];
		$attribute_name  = $block->context['woocommerce/attributeName'];
		$attribute_terms = $block->context['woocommerce/attributeTerms'];

		$pills = '';
		foreach ( $attribute_terms as $attribute_term ) {
			$pills .= sprintf(
				'<div %s>%s</div>',
				$this->get_normalized_attributes(
					array(
						'role'                        => 'radio',
						'class'                       => 'wc-block-add-to-cart-with-options-variation-selector-attribute-options__pill',
						'data-wp-bind--tabindex'      => 'state.pillTabIndex',
						'data-wp-bind--aria-checked'  => 'state.isPillSelected',
						'data-wp-bind--aria-disabled' => 'state.isPillDisabled',
						'data-wp-watch'               => 'callbacks.watchSelected',
						'data-wp-on--click'           => 'actions.toggleSelected',
						'data-wp-on--keydown'         => 'actions.handleKeyDown',
						'data-wp-context'             => array(
							'option' => $attribute_term,
						),
					),
				),
				$attribute_term['label']
			);
		}

		return sprintf(
			'<div %s>%s</div>',
			$this->get_normalized_attributes(
				array(
					'class'               => 'wc-block-add-to-cart-with-options-variation-selector-attribute-options__pills',
					'role'                => 'radiogroup',
					'id'                  => $attribute_id,
					'aria-labeledby'      => $attribute_id . '_label',
					'data-wp-interactive' => $this->get_full_block_name() . '__pills',
					'data-wp-context'     => array(
						'name'          => $attribute_name,
						'options'       => $attribute_terms,
						'selectedValue' => $this->get_default_selected_attribute( $attribute_terms ),
						'focused'       => '',
					),
					'data-wp-init'        => 'callbacks.setDefaultSelectedAttribute',
				),
			),
			$pills,
		);
	}

	/**
	 * Render the attribute options as a dropdown.
	 *
	 * @param array    $attributes Block attributes.
	 * @param string   $content Block content.
	 * @param WP_Block $block Block instance.
	 * @return string The dropdown.
	 */
	protected function render_dropdown( $attributes, $content, $block ) {
		$attribute_id    = $block->context['woocommerce/attributeId'];
		$attribute_name  = $block->context['woocommerce/attributeName'];
		$attribute_terms = $block->context['woocommerce/attributeTerms'];
		$default_option  = array(
			'label'      => esc_html__( 'Choose an option', 'woocommerce' ),
			'value'      => '',
			'isSelected' => false,
		);

		$attribute_terms = array_merge(
			array( $default_option ),
			$attribute_terms
		);

		$options = '';
		foreach ( $attribute_terms as $attribute_term ) {
			$options .= sprintf(
				'<option %s>%s</option>',
				$this->get_normalized_attributes(
					array(
						'value'                  => $attribute_term['value'],
						'data-wp-bind--disabled' => 'state.isOptionDisabled',
						'data-wp-context'        => array(
							'option'  => $attribute_term,
							'name'    => $attribute_name,
							'options' => $attribute_terms,
						),
					),
				),
				$attribute_term['label']
			);
		}

		return sprintf(
			'<select %s>%s</select>',
			$this->get_normalized_attributes(
				array(
					'class'               => 'wc-block-add-to-cart-with-options-variation-selector-attribute-options__dropdown',
					'id'                  => $attribute_id,
					'data-wp-interactive' => $this->get_full_block_name() . '__dropdown',
					'data-wp-context'     => array(
						'name'          => $attribute_name,
						'options'       => $attribute_terms,
						'selectedValue' => $this->get_default_selected_attribute( $attribute_terms ),
					),
					'data-wp-init'        => 'callbacks.setDefaultSelectedAttribute',
					'data-wp-on--change'  => 'actions.handleChange',
				),
			),
			$options,
		);
	}
}
