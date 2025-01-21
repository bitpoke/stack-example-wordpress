<?php
declare(strict_types=1);

namespace Automattic\WooCommerce\Blocks\BlockTypes\ProductCollection;

use Automattic\WooCommerce\Blocks\BlockTypes\AbstractBlock;
use WP_Query;

/**
 * Controller class.
 */
class Controller extends AbstractBlock {

	/**
	 * Block name.
	 *
	 * @var string
	 */
	protected $block_name = 'product-collection';

	/**
	 * Instance of HandlerRegistry.
	 *
	 * @var HandlerRegistry
	 */
	protected $collection_handler_registry;

	/**
	 * Instance of QueryBuilder.
	 *
	 * @var QueryBuilder
	 */
	protected $query_builder;

	/**
	 * Instance of Renderer.
	 *
	 * @var Renderer
	 */
	protected $renderer;

	/**
	 * Initialize this block type.
	 *
	 * - Register hooks and filters.
	 * - Set up QueryBuilder, Renderer and HandlerRegistry.
	 */
	protected function initialize() {
		parent::initialize();

		$this->query_builder               = new QueryBuilder();
		$this->renderer                    = new Renderer();
		$this->collection_handler_registry = new HandlerRegistry();

		// Update query for frontend rendering.
		add_filter(
			'query_loop_block_query_vars',
			array( $this, 'build_frontend_query' ),
			10,
			3
		);

		add_filter(
			'pre_render_block',
			array( $this, 'add_support_for_filter_blocks' ),
			10,
			2
		);

		// Update the query for Editor.
		add_filter( 'rest_product_query', array( $this, 'update_rest_query_in_editor' ), 10, 2 );

		// Extend allowed `collection_params` for the REST API.
		add_filter( 'rest_product_collection_params', array( $this, 'extend_rest_query_allowed_params' ), 10, 1 );
		add_filter( 'render_block_core/post-title', array( $this, 'add_product_title_click_event_directives' ), 10, 3 );

		// Disable client-side-navigation if incompatible blocks are detected.
		add_filter( 'render_block_data', array( $this, 'disable_enhanced_pagination' ), 10, 1 );

		$this->register_core_collections_and_set_handler_store();
	}

	/**
	 * Add interactivity to the Product Title block within Product Collection.
	 * This enables the triggering of a custom event when the product title is clicked.
	 *
	 * @param string    $block_content The block content.
	 * @param array     $block         The full block, including name and attributes.
	 * @param \WP_Block $instance      The block instance.
	 * @return string   Modified block content with added interactivity.
	 */
	public function add_product_title_click_event_directives( $block_content, $block, $instance ) {
		$namespace              = $instance->attributes['__woocommerceNamespace'] ?? '';
		$is_product_title_block = 'woocommerce/product-collection/product-title' === $namespace;
		$is_link                = $instance->attributes['isLink'] ?? false;

		// Only proceed if the block is a Product Title (Post Title variation) block.
		if ( $is_product_title_block && $is_link ) {
			$p = new \WP_HTML_Tag_Processor( $block_content );
			$p->next_tag( array( 'class_name' => 'wp-block-post-title' ) );
			$is_anchor = $p->next_tag( array( 'tag_name' => 'a' ) );

			if ( $is_anchor ) {
				$p->set_attribute( 'data-wc-on--click', 'woocommerce/product-collection::actions.viewProduct' );

				$block_content = $p->get_updated_html();
			}
		}

		return $block_content;
	}

	/**
	 * Verifies if the inner block is compatible with Interactivity API.
	 *
	 * @param string $block_name Name of the block to verify.
	 * @return boolean
	 */
	private function is_block_compatible( $block_name ) {
		// Check for explicitly unsupported blocks.
		$unsupported_blocks = array(
			'core/post-content',
			'woocommerce/mini-cart',
			'woocommerce/featured-product',
			'woocommerce/active-filters',
			'woocommerce/price-filter',
			'woocommerce/stock-filter',
			'woocommerce/attribute-filter',
			'woocommerce/rating-filter',
		);

		if ( in_array( $block_name, $unsupported_blocks, true ) ) {
			return false;
		}

		// Check for supported prefixes.
		if (
			str_starts_with( $block_name, 'core/' ) ||
			str_starts_with( $block_name, 'woocommerce/' )
		) {
			return true;
		}

		// Otherwise block is unsupported.
		return false;
	}

	/**
	 * Check inner blocks of Product Collection block if there's one
	 * incompatible with the Interactivity API and if so, disable client-side
	 * navigation.
	 *
	 * @param array $parsed_block The block being rendered.
	 * @return string Returns the parsed block, unmodified.
	 */
	public function disable_enhanced_pagination( $parsed_block ) {
		static $enhanced_query_stack               = array();
		static $dirty_enhanced_queries             = array();
		static $render_product_collection_callback = null;

		$block_name                  = $parsed_block['blockName'];
		$is_product_collection_block = $parsed_block['attrs']['query']['isProductCollectionBlock'] ?? false;
		$force_page_reload_global    =
			$parsed_block['attrs']['forcePageReload'] ?? false &&
			isset( $parsed_block['attrs']['queryId'] );

		if (
			$is_product_collection_block &&
			'woocommerce/product-collection' === $block_name &&
			! $force_page_reload_global
		) {
			$enhanced_query_stack[] = $parsed_block['attrs']['queryId'];

			if ( ! isset( $render_product_collection_callback ) ) {
				/**
				 * Filter that disables the enhanced pagination feature during block
				 * rendering when a plugin block has been found inside. It does so
				 * by adding an attribute called `data-wp-navigation-disabled` which
				 * is later handled by the front-end logic.
				 *
				 * @param string   $content  The block content.
				 * @param array    $block    The full block, including name and attributes.
				 * @return string Returns the modified output of the query block.
				 */
				$render_product_collection_callback = static function ( $content, $block ) use ( &$enhanced_query_stack, &$dirty_enhanced_queries, &$render_product_collection_callback ) {
					$force_page_reload =
						$parsed_block['attrs']['forcePageReload'] ?? false &&
						isset( $block['attrs']['queryId'] );

					if ( $force_page_reload ) {
						return $content;
					}

					if ( isset( $dirty_enhanced_queries[ $block['attrs']['queryId'] ] ) ) {
						$p = new \WP_HTML_Tag_Processor( $content );
						if ( $p->next_tag() ) {
							$p->set_attribute( 'data-wc-navigation-disabled', 'true' );
						}
						$content = $p->get_updated_html();
						$dirty_enhanced_queries[ $block['attrs']['queryId'] ] = null;
					}

					array_pop( $enhanced_query_stack );

					if ( empty( $enhanced_query_stack ) ) {
						remove_filter( 'render_block_woocommerce/product-collection', $render_product_collection_callback );
						$render_product_collection_callback = null;
					}

					return $content;
				};

				add_filter( 'render_block_woocommerce/product-collection', $render_product_collection_callback, 10, 2 );
			}
		} elseif (
			! empty( $enhanced_query_stack ) &&
			isset( $block_name ) &&
			! $this->is_block_compatible( $block_name )
		) {
			foreach ( $enhanced_query_stack as $query_id ) {
				$dirty_enhanced_queries[ $query_id ] = true;
			}
		}

		return $parsed_block;
	}

	/**
	 * Extra data passed through from server to client for block.
	 *
	 * @param array $attributes  Any attributes that currently are available from the block.
	 *                           Note, this will be empty in the editor context when the block is
	 *                           not in the post content on editor load.
	 */
	protected function enqueue_data( array $attributes = array() ) {
		parent::enqueue_data( $attributes );

		// The `loop_shop_per_page` filter can be found in WC_Query::product_query().
		// phpcs:ignore WooCommerce.Commenting.CommentHooks.MissingHookComment
		$this->asset_data_registry->add( 'loopShopPerPage', apply_filters( 'loop_shop_per_page', wc_get_default_products_per_row() * wc_get_default_product_rows_per_page() ) );
	}

	/**
	 * Update the query for the product query block in Editor.
	 *
	 * @param array           $query   Query args.
	 * @param WP_REST_Request $request Request.
	 */
	public function update_rest_query_in_editor( $query, $request ): array {
		// Only update the query if this is a product collection block.
		$is_product_collection_block = $request->get_param( 'isProductCollectionBlock' );
		if ( ! $is_product_collection_block ) {
			return $query;
		}

		$product_collection_query_context = $request->get_param( 'productCollectionQueryContext' );
		$collection_args                  = array(
			'name'                      => $product_collection_query_context['collection'] ?? '',
			// The editor uses a REST query to grab product post types. This means we don't have a block
			// instance to work with and the client needs to provide the location context.
			'productCollectionLocation' => $request->get_param( 'productCollectionLocation' ),
		);

		// Allow collections to modify the collection arguments passed to the query builder.
		$handlers = $this->collection_handler_registry->get_collection_handler( $collection_args['name'] );
		if ( isset( $handlers['editor_args'] ) ) {
			$collection_args = call_user_func( $handlers['editor_args'], $collection_args, $query, $request );
		}

		// When requested, short-circuit the query and return the preview query args.
		$preview_state = $request->get_param( 'previewState' );
		if ( isset( $preview_state['isPreview'] ) && 'true' === $preview_state['isPreview'] ) {
			return $this->query_builder->get_preview_query_args( $collection_args, $query, $request );
		}

		$orderby             = $request->get_param( 'orderby' );
		$on_sale             = $request->get_param( 'woocommerceOnSale' ) === 'true';
		$stock_status        = $request->get_param( 'woocommerceStockStatus' );
		$product_attributes  = $request->get_param( 'woocommerceAttributes' );
		$handpicked_products = $request->get_param( 'woocommerceHandPickedProducts' );
		$featured            = $request->get_param( 'featured' );
		$time_frame          = $request->get_param( 'timeFrame' );
		$price_range         = $request->get_param( 'priceRange' );
		// This argument is required for the tests to PHP Unit Tests to run correctly.
		// Most likely this argument is being accessed in the test environment image.
		$query['author'] = '';

		// Use QueryBuilder to get the final query args.
		return $this->query_builder->get_final_query_args(
			$collection_args,
			$query,
			array(
				'orderby'             => $orderby,
				'on_sale'             => $on_sale,
				'stock_status'        => $stock_status,
				'product_attributes'  => $product_attributes,
				'handpicked_products' => $handpicked_products,
				'featured'            => $featured,
				'timeFrame'           => $time_frame,
				'priceRange'          => $price_range,
			)
		);
	}

	/**
	 * Add support for filter blocks:
	 * - Price filter block
	 * - Attributes filter block
	 * - Rating filter block
	 * - In stock filter block etc.
	 *
	 * @param array $pre_render   The pre-rendered block.
	 * @param array $parsed_block The parsed block.
	 */
	public function add_support_for_filter_blocks( $pre_render, $parsed_block ) {
		$is_product_collection_block = $parsed_block['attrs']['query']['isProductCollectionBlock'] ?? false;

		if ( ! $is_product_collection_block ) {
			return $pre_render;
		}

		$this->renderer->set_parsed_block( $parsed_block );
		$this->asset_data_registry->add( 'hasFilterableProducts', true );
		/**
		 * It enables the page to refresh when a filter is applied, ensuring that the product collection block,
		 * which is a server-side rendered (SSR) block, retrieves the products that match the filters.
		 */
		$this->asset_data_registry->add( 'isRenderingPhpTemplate', true );

		return $pre_render;
	}

	/**
	 * Return a custom query based on attributes, filters and global WP_Query.
	 *
	 * @param WP_Query $query The WordPress Query.
	 * @param WP_Block $block The block being rendered.
	 * @param int      $page  The page number.
	 *
	 * @return array
	 */
	public function build_frontend_query( $query, $block, $page ) {
		// If not in context of product collection block, return the query as is.
		$is_product_collection_block = $block->context['query']['isProductCollectionBlock'] ?? false;
		if ( ! $is_product_collection_block ) {
			return $query;
		}

		$block_context_query = $block->context['query'];

		// phpcs:ignore WordPress.DB.SlowDBQuery
		$block_context_query['tax_query'] = ! empty( $query['tax_query'] ) ? $query['tax_query'] : array();

		$inherit    = $block->context['query']['inherit'] ?? false;
		$filterable = $block->context['query']['filterable'] ?? false;

		$is_exclude_applied_filters = ! ( $inherit || $filterable );

		$collection_args = array(
			'name'                      => $block->context['collection'] ?? '',
			'productCollectionLocation' => $block->context['productCollectionLocation'] ?? null,
		);

		// Use QueryBuilder to construct the query.
		return $this->query_builder->get_final_frontend_query(
			$collection_args,
			$block_context_query,
			$page,
			$is_exclude_applied_filters
		);
	}

	/**
	 * Extends allowed `collection_params` for the REST API
	 *
	 * By itself, the REST API doesn't accept custom `orderby` values,
	 * even if they are supported by a custom post type.
	 *
	 * @param array $params  A list of allowed `orderby` values.
	 *
	 * @return array
	 */
	public function extend_rest_query_allowed_params( $params ) {
		$original_enum             = isset( $params['orderby']['enum'] ) ? $params['orderby']['enum'] : array();
		$params['orderby']['enum'] = array_unique( array_merge( $original_enum, $this->query_builder->get_custom_order_opts() ) );
		return $params;
	}

	/**
	 * Registers core collections and sets the handler store.
	 */
	protected function register_core_collections_and_set_handler_store() {
		// Use HandlerRegistry to register collections.
		$collection_handler_store = $this->collection_handler_registry->register_core_collections();
		$this->query_builder->set_collection_handler_store( $collection_handler_store );
	}
}
