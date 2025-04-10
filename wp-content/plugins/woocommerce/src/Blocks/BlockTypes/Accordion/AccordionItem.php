<?php
declare(strict_types=1);

namespace Automattic\WooCommerce\Blocks\BlockTypes\Accordion;

use Automattic\WooCommerce\Blocks\BlockTypes\AbstractBlock;

/**
 * AccordionItem class.
 */
class AccordionItem extends AbstractBlock {
	/**
	 * Block name.
	 *
	 * @var string
	 */
	protected $block_name = 'accordion-item';

	/**
	 * Include and render the block.
	 *
	 * @param array    $attributes Block attributes. Default empty array.
	 * @param string   $content    Block content. Default empty string.
	 * @param WP_Block $block      Block instance.
	 * @return string Rendered block type output.
	 */
	protected function render( $attributes, $content, $block ) {
		if ( ! $content ) {
			return $content;
		}

		$p         = new \WP_HTML_Tag_Processor( $content );
		$unique_id = wp_unique_id( 'woocommerce-accordion-item-' );

		// Initialize the state of the item on the server using a closure,
		// since we need to get derived state based on the current context.
		wc_initial_state(
			'woocommerce/accordion',
			array(
				'isOpen' => function () {
					$context = wp_interactivity_get_context();
					return $context['openByDefault'];
				},
			)
		);

		if ( $p->next_tag( array( 'class_name' => 'wp-block-woocommerce-accordion-item' ) ) ) {
			$interactivity_context = array(
				'id'            => $unique_id,
				'openByDefault' => $attributes['openByDefault'],
			);
			$p->set_attribute( 'data-wc-context', wp_json_encode( $interactivity_context, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP ) );
			$p->set_attribute( 'data-wc-class--is-open', 'state.isOpen' );
			$p->set_attribute( 'data-wc-init', 'callbacks.initIsOpen' );

			if ( $p->next_tag( array( 'class_name' => 'accordion-item__toggle' ) ) ) {
				$p->set_attribute( 'data-wc-on--click', 'actions.toggle' );
				$p->set_attribute( 'id', $unique_id );
				$p->set_attribute( 'aria-controls', $unique_id . '-panel' );
				$p->set_attribute( 'data-wc-bind--aria-expanded', 'state.isOpen' );

				if ( $p->next_tag( array( 'class_name' => 'wp-block-woocommerce-accordion-panel' ) ) ) {
					$p->set_attribute( 'id', $unique_id . '-panel' );
					$p->set_attribute( 'aria-labelledby', $unique_id );
					$p->set_attribute( 'role', 'region' );
					$p->set_attribute( 'data-wc-bind--inert', '!state.isOpen' );

					// Only modify content if all directives have been set.
					$content = $p->get_updated_html();
				}
			}
		}

		return $content;
	}

	/**
	 * Get the frontend style handle for this block type.
	 *
	 * @return string[]|null
	 */
	protected function get_block_type_style() {
		return null;
	}

	/**
	 * Get the frontend script handle for this block type.
	 *
	 * @see $this->register_block_type()
	 * @param string $key Data to get, or default to everything.
	 * @return array|string|null
	 */
	protected function get_block_type_script( $key = null ) {
		return null;
	}
}
