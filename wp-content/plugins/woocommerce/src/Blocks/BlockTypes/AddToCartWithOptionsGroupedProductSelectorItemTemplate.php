<?php
declare(strict_types=1);

namespace Automattic\WooCommerce\Blocks\BlockTypes;

use Automattic\WooCommerce\Admin\Features\Features;
use Automattic\WooCommerce\Blocks\Utils\StyleAttributesUtils;
use WP_Block;

/**
 * Block type for grouped product selector item in add to cart with options.
 * It's responsible to render each child product in a form of a list item.
 */
class AddToCartWithOptionsGroupedProductSelectorItemTemplate extends AbstractBlock {
	/**
	 * Block name.
	 *
	 * @var string
	 */
	protected $block_name = 'add-to-cart-with-options-grouped-product-selector-item';

	/**
	 * Get the frontend style handle for this block type.
	 *
	 * @return null
	 */
	protected function get_block_type_style() {
		return null;
	}

	/**
	 * Get product row HTML.
	 *
	 * @param string   $product_id Product ID.
	 * @param array    $attributes Block attributes.
	 * @param WP_Block $block The Block.
	 * @return string Row HTML
	 */
	private function get_product_row( $product_id, $attributes, $block ): string {
		global $post;
		$previous_post = $post;

		// Since this template uses the core/post-title block to show the product name
		// a temporally replacement of the global post is needed. This is reverted back
		// to its initial post value that is stored in the $previous_post variable.
		$post = get_post( $product_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

		// Get an instance of the current Post Template block.
		$block_instance = $block->parsed_block;

		$new_block = new WP_Block(
			$block_instance,
			array(
				'postType' => 'product',
				'postId'   => $post->ID,
			),
		);

		// Render the inner blocks of the Post Template block with `dynamic` set to `false` to prevent calling
		// `render_callback` and ensure that no wrapper markup is included.
		$block_content = $new_block->render( array( 'dynamic' => false ) );

		$post = $previous_post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

		return $block_content;
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
		global $product;

		$content = '';

		$children = array_filter( array_map( 'wc_get_product', $product->get_children() ), 'wc_products_array_filter_visible_grouped' );

		foreach ( $children as $child ) {
			$content .= $this->get_product_row( $child->get_id(), $attributes, $block );
		}

		return $content;
	}
}
