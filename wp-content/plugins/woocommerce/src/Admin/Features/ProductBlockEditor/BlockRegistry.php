<?php
/**
 * WooCommerce Product Editor Block Registration
 */

namespace Automattic\WooCommerce\Admin\Features\ProductBlockEditor;

use Automattic\WooCommerce\Internal\Admin\WCAdminAssets;

/**
 * Product block registration and style registration functionality.
 */
class BlockRegistry {

	/**
	 * Generic blocks directory.
	 */
	const GENERIC_BLOCKS_DIR = 'product-editor/blocks/generic';
	/**
	 * Product fields blocks directory.
	 */
	const PRODUCT_FIELDS_BLOCKS_DIR = 'product-editor/blocks/product-fields';
	/**
	 * Array of all available generic blocks.
	 */
	const GENERIC_BLOCKS = array(
		'woocommerce/conditional',
		'woocommerce/product-checkbox-field',
		'woocommerce/product-collapsible',
		'woocommerce/product-radio-field',
		'woocommerce/product-pricing-field',
		'woocommerce/product-section',
		'woocommerce/product-tab',
		'woocommerce/product-toggle-field',
		'woocommerce/product-taxonomy-field',
		'woocommerce/product-text-field',
		'woocommerce/product-number-field',
	);

	/**
	 * Array of all available product fields blocks.
	 */
	const PRODUCT_FIELDS_BLOCKS = array(
		'woocommerce/product-catalog-visibility-field',
		'woocommerce/product-description-field',
		'woocommerce/product-downloads-field',
		'woocommerce/product-images-field',
		'woocommerce/product-inventory-email-field',
		'woocommerce/product-sku-field',
		'woocommerce/product-name-field',
		'woocommerce/product-regular-price-field',
		'woocommerce/product-sale-price-field',
		'woocommerce/product-schedule-sale-fields',
		'woocommerce/product-shipping-class-field',
		'woocommerce/product-shipping-dimensions-fields',
		'woocommerce/product-summary-field',
		'woocommerce/product-tag-field',
		'woocommerce/product-inventory-quantity-field',
		'woocommerce/product-variation-items-field',
		'woocommerce/product-variations-fields',
		'woocommerce/product-password-field',
		'woocommerce/product-has-variations-notice',
		'woocommerce/product-single-variation-notice',
	);

	/**
	 * Get a file path for a given block file.
	 *
	 * @param string $path File path.
	 * @param string $dir File directory.
	 */
	private function get_file_path( $path, $dir ) {
		return WC_ABSPATH . WCAdminAssets::get_path( 'js' ) . trailingslashit( $dir ) . $path;
	}

	/**
	 * Initialize all blocks.
	 */
	public function init() {
		add_filter( 'block_categories_all', array( $this, 'register_categories' ), 10, 2 );
		$this->register_product_blocks();
	}

	/**
	 * Register all the product blocks.
	 */
	private function register_product_blocks() {
		foreach ( self::PRODUCT_FIELDS_BLOCKS as $block_name ) {
			$this->register_block( $block_name, self::PRODUCT_FIELDS_BLOCKS_DIR );
		}
		foreach ( self::GENERIC_BLOCKS as $block_name ) {
			$this->register_block( $block_name, self::GENERIC_BLOCKS_DIR );
		}
	}

	/**
	 * Register product related block categories.
	 *
	 * @param array[]                 $block_categories Array of categories for block types.
	 * @param WP_Block_Editor_Context $editor_context   The current block editor context.
	 */
	public function register_categories( $block_categories, $editor_context ) {
		if ( INIT::EDITOR_CONTEXT_NAME === $editor_context->name ) {
			$block_categories[] = array(
				'slug'  => 'woocommerce',
				'title' => __( 'WooCommerce', 'woocommerce' ),
				'icon'  => null,
			);
		}

		return $block_categories;
	}

	/**
	 * Get the block name without the "woocommerce/" prefix.
	 *
	 * @param string $block_name Block name.
	 *
	 * @return string
	 */
	private function remove_block_prefix( $block_name ) {
		if ( 0 === strpos( $block_name, 'woocommerce/' ) ) {
			return substr_replace( $block_name, '', 0, strlen( 'woocommerce/' ) );
		}

		return $block_name;
	}

	/**
	 * Augment the attributes of a block by adding attributes that are used by the product editor.
	 *
	 * @param array $attributes Block attributes.
	 */
	private function augment_attributes( $attributes ) {
		// Note: If you modify this function, also update the client-side
		// registerWooBlockType function in @woocommerce/block-templates.
		return array_merge(
			$attributes,
			array(
				'_templateBlockId'                => array(
					'type'               => 'string',
					'__experimentalRole' => 'content',
				),
				'_templateBlockOrder'             => array(
					'type'               => 'integer',
					'__experimentalRole' => 'content',
				),
				'_templateBlockHideConditions'    => array(
					'type'               => 'array',
					'__experimentalRole' => 'content',
				),
				'_templateBlockDisableConditions' => array(
					'type'               => 'array',
					'__experimentalRole' => 'content',
				),
				'disabled'                        => isset( $attributes['disabled'] ) ? $attributes['disabled'] : array(
					'type'               => 'boolean',
					'__experimentalRole' => 'content',
				),
			)
		);
	}

	/**
	 * Augment the uses_context of a block by adding attributes that are used by the product editor.
	 *
	 * @param array $uses_context Block uses_context.
	 */
	private function augment_uses_context( $uses_context ) {
		// Note: If you modify this function, also update the client-side
		// registerProductEditorBlockType function in @woocommerce/product-editor.
		return array_merge(
			isset( $uses_context ) ? $uses_context : array(),
			array(
				'postType',
			)
		);
	}

	/**
	 * Register a single block.
	 *
	 * @param string $block_name Block name.
	 * @param string $block_dir Block directory.
	 *
	 * @return WP_Block_Type|false The registered block type on success, or false on failure.
	 */
	private function register_block( $block_name, $block_dir ) {
		$block_name      = $this->remove_block_prefix( $block_name );
		$block_json_file = $this->get_file_path( $block_name . '/block.json', $block_dir );

		if ( ! file_exists( $block_json_file ) ) {
			return false;
		}

		// phpcs:disable WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		$metadata = json_decode( file_get_contents( $block_json_file ), true );
		if ( ! is_array( $metadata ) || ! $metadata['name'] ) {
			return false;
		}

		$registry = \WP_Block_Type_Registry::get_instance();

		if ( $registry->is_registered( $metadata['name'] ) ) {
			$registry->unregister( $metadata['name'] );
		}

		return register_block_type_from_metadata(
			$block_json_file,
			array(
				'attributes'   => $this->augment_attributes( isset( $metadata['attributes'] ) ? $metadata['attributes'] : array() ),
				'uses_context' => $this->augment_uses_context( isset( $metadata['usesContext'] ) ? $metadata['usesContext'] : array() ),
			)
		);
	}

}
