<?php
/**
 * ProductFilterPriceSlider class.
 *
 * @package Automattic\WooCommerce\Blocks\BlockTypes
 */

declare( strict_types = 1 );

namespace Automattic\WooCommerce\Blocks\BlockTypes;

/**
 * ProductFilterPriceSlider class.
 */
class ProductFilterPriceSlider extends AbstractBlock {

	/**
	 * Block name.
	 *
	 * @var string
	 */
	protected $block_name = 'product-filter-price-slider';

	/**
	 * Render the block.
	 *
	 * @param array    $attributes Block attributes.
	 * @param string   $content    Block content.
	 * @param WP_Block $block      Block instance.
	 * @return string Rendered block type output.
	 */
	protected function render( $attributes, $content, $block ) {
		if ( is_admin() || wp_doing_ajax() || empty( $block->context['filterData'] ) || empty( $block->context['filterData']['price'] ) ) {
			return '';
		}

		$price_data = $block->context['filterData']['price'];
		$min_price  = $price_data['minPrice'];
		$max_price  = $price_data['maxPrice'];
		$min_range  = $price_data['minRange'];
		$max_range  = $price_data['maxRange'];

		if ( $min_range === $max_range ) {
			return;
		}

		$classes = '';
		$style   = '';

		$tags = new \WP_HTML_Tag_Processor( $content );
		if ( $tags->next_tag( array( 'class_name' => 'wc-block-product-filter-price-slider' ) ) ) {
			$classes = $tags->get_attribute( 'class' );
			$style   = $tags->get_attribute( 'style' );
		}

		$formatted_min_price = wc_price( $min_price, array( 'decimals' => 0 ) );
		$formatted_max_price = wc_price( $max_price, array( 'decimals' => 0 ) );

		$show_input_fields = isset( $attributes['showInputFields'] ) ? $attributes['showInputFields'] : false;
		$inline_input      = isset( $attributes['inlineInput'] ) ? $attributes['inlineInput'] : false;

		$wrapper_attributes = get_block_wrapper_attributes(
			array(
				'class'               => esc_attr( $classes ),
				'style'               => esc_attr( $style ),
				'data-wc-interactive' => wp_json_encode(
					array(
						'namespace' => $this->get_full_block_name(),
					),
					JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
				),
				'data-wc-key'         => wp_unique_prefixed_id( $this->get_full_block_name() ),

			)
		);

		$content_class = 'wc-block-product-filter-price-slider__content';
		if ( $inline_input && $show_input_fields ) {
			$content_class .= ' wc-block-product-filter-price-slider__content--inline';
		}

		// CSS variables for the range bar style.
		$__low       = 100 * ( $min_price - $min_range ) / ( $max_range - $min_range );
		$__high      = 100 * ( $max_price - $min_range ) / ( $max_range - $min_range );
		$range_style = "--low: $__low%; --high: $__high%";

		ob_start();
		?>
		<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<div class="<?php echo esc_attr( $content_class ); ?>">
				<div class="wc-block-product-filter-price-slider__left text">
					<?php if ( $show_input_fields ) : ?>
						<input
							class="min"
							type="text"
							value="<?php echo esc_attr( wp_strip_all_tags( $formatted_min_price ) ); ?>"
							data-wc-bind--value="woocommerce/product-filter-price::state.formattedMinPrice"
							data-wc-on--focus="actions.selectInputContent"
							data-wc-on--input="actions.debounceSetPrice"
							data-wc-on--change--set-price="woocommerce/product-filter-price::actions.setMinPrice"
							data-wc-on--change--navigate="woocommerce/product-filters::actions.navigate"
						/>
					<?php else : ?>
						<span data-wc-text="woocommerce/product-filter-price::state.formattedMinPrice"><?php echo wp_kses_post( $formatted_min_price ); ?></span>
					<?php endif; ?>
				</div>
				<div
					class="wc-block-product-filter-price-slider__range"
					style="<?php echo esc_attr( $range_style ); ?>"
					data-wc-bind--style="state.rangeStyle"
				>
					<div class="range-bar"></div>
					<input
						type="range"
						class="min"
						min="<?php echo esc_attr( $min_range ); ?>"
						max="<?php echo esc_attr( $max_range ); ?>"
						value="<?php echo esc_attr( $min_price ); ?>"
						data-wc-bind--value="woocommerce/product-filter-price::state.minPrice"
						data-wc-bind--min="woocommerce/product-filter-price::context.minRange"
						data-wc-bind--max="woocommerce/product-filter-price::context.maxRange"
						data-wc-on--input--update-price="woocommerce/product-filter-price::actions.setMinPrice"
						data-wc-on--input--limit-range="actions.limitRange"
						data-wc-on--mouseup="woocommerce/product-filters::actions.navigate"
						data-wc-on--keyup="woocommerce/product-filters::actions.navigate"
						data-wc-on--touchend="woocommerce/product-filters::actions.navigate"
					/>
					<input
						type="range"
						class="max"
						min="<?php echo esc_attr( $min_range ); ?>"
						max="<?php echo esc_attr( $max_range ); ?>"
						value="<?php echo esc_attr( $max_price ); ?>"
						data-wc-bind--value="woocommerce/product-filter-price::state.maxPrice"
						data-wc-bind--max="woocommerce/product-filter-price::context.maxRange"
						data-wc-bind--max="woocommerce/product-filter-price::context.maxRange"
						data-wc-on--input--update-price="woocommerce/product-filter-price::actions.setMaxPrice"
						data-wc-on--input--limit-range="actions.limitRange"
						data-wc-on--mouseup="woocommerce/product-filters::actions.navigate"
						data-wc-on--keyup="woocommerce/product-filters::actions.navigate"
						data-wc-on--touchend="woocommerce/product-filters::actions.navigate"
					/>
				</div>
				<div class="wc-block-product-filter-price-slider__right text">
					<?php if ( $show_input_fields ) : ?>
						<input
							class="max"
							type="text"
							value="<?php echo esc_attr( wp_strip_all_tags( $formatted_max_price ) ); ?>"
							data-wc-bind--value="woocommerce/product-filter-price::state.formattedMaxPrice"
							data-wc-on--focus="actions.selectInputContent"
							data-wc-on--input="actions.debounceSetPrice"
							data-wc-on--change--set-price="woocommerce/product-filter-price::actions.setMaxPrice"
							data-wc-on--change--navigate="woocommerce/product-filters::actions.navigate"
						/>
					<?php else : ?>
					<span data-wc-text="woocommerce/product-filter-price::state.formattedMaxPrice"><?php echo wp_kses_post( $formatted_max_price ); ?></span>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}
