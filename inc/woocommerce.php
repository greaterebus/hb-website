<?php
/**
 * Live WooCommerce data for the "Shop By Category" and "Featured Products"
 * homepage sections. Every function here is safe to call even if
 * WooCommerce is deactivated (returns an empty array).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'woocommerce_product_add_to_cart_success_message', 'hugginbutt_add_to_cart_success_message' );

/**
 * Renames "cart" to "loot" in the add-to-cart success message (used both
 * as the classic WooCommerce notice and as the text in our own
 * add-to-cart toast, via each button's data-success_message attribute).
 */
function hugginbutt_add_to_cart_success_message( $message ) {
	return str_ireplace( 'your cart', 'your loot', $message );
}

/**
 * Up to 5 shop category tiles. Prefers real `product_cat` terms (with
 * whatever "Thumbnail" image is set on the term in Products > Categories);
 * pads with the fixed fallback labels from hugginbutt_get_fallback_categories()
 * when fewer than 5 real categories exist yet, so the section never looks
 * sparse on a brand-new store.
 *
 * Each returned row: name, link, image_id (real thumbnail attachment id, or
 * 0), image (placeholder key fallback).
 */
function hugginbutt_get_shop_categories() {
	$rows = array();

	if ( ! class_exists( 'WooCommerce' ) ) {
		return $rows;
	}

	$terms = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => true,
			'number'     => 0,
			'orderby'    => 'name',
			'order'      => 'ASC',
		)
	);

	if ( is_wp_error( $terms ) ) {
		return $rows;
	}

	foreach ( $terms as $term ) {
		if ( 'uncategorized' === $term->slug ) {
			continue;
		}

		$link = add_query_arg( 'product_cat', $term->slug, wc_get_page_permalink( 'shop' ) );

		$thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );

		// Prefer a real product photo when no category thumbnail was chosen.
		// Products > Categories > Thumbnail remains the explicit override.
		if ( ! $thumbnail_id ) {
			$category_products = wc_get_products(
				array(
					'status'   => 'publish',
					'category' => array( $term->slug ),
					'limit'    => 12,
					'orderby'  => 'date',
					'order'    => 'DESC',
				)
			);

			foreach ( $category_products as $category_product ) {
				if ( $category_product->get_image_id() ) {
					$thumbnail_id = $category_product->get_image_id();
					break;
				}
			}
		}
		$rows[] = array(
			'name'     => $term->name,
			'link'     => $link,
			'image_id' => $thumbnail_id ? absint( $thumbnail_id ) : 0,
			'image'    => 'category-' . $term->slug,
		);
	}

	return $rows;
}

/**
 * Up to 6 featured products. Prefers products marked "Featured" in
 * WooCommerce; tops up with the most recently published products when
 * fewer than 6 are featured. Returns an empty array if the store has no
 * published products at all, so the template can render its placeholder
 * "coming soon" cards instead.
 *
 * @return \WC_Product[]
 */
function hugginbutt_get_featured_products() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return array();
	}

	$featured = wc_get_products(
		array(
			'status'   => 'publish',
			'featured' => true,
			'limit'    => 6,
			'orderby'  => 'date',
			'order'    => 'DESC',
		)
	);

	if ( count( $featured ) >= 6 ) {
		return $featured;
	}

	$existing_ids = array();
	foreach ( $featured as $product ) {
		$existing_ids[] = $product->get_id();
	}

	$recent = wc_get_products(
		array(
			'status'  => 'publish',
			'limit'   => 6 - count( $featured ),
			'orderby' => 'date',
			'order'   => 'DESC',
			'exclude' => $existing_ids,
		)
	);

	return array_merge( $featured, $recent );
}

add_filter( 'body_class', 'hugginbutt_coming_soon_body_class' );

/**
 * Tags <body> on the WooCommerce "coming soon" page so hugginbutt.css can
 * override Kadence's default cream body background (`body{background:var(--global-palette8)}`)
 * with the site's green fabric look, scoped to just this page.
 */
function hugginbutt_coming_soon_body_class( $classes ) {
	if ( ! function_exists( 'wc_get_container' ) ) {
		return $classes;
	}

	$helper = wc_get_container()->get( \Automattic\WooCommerce\Internal\ComingSoon\ComingSoonHelper::class );

	if ( $helper->is_current_page_coming_soon() ) {
		$classes[] = 'hb-coming-soon-page';
	}

	return $classes;
}

add_filter( 'woocommerce_add_to_cart_fragments', 'hugginbutt_cart_count_fragment' );

/**
 * Keeps the header cart badge live-updated after an AJAX add-to-cart,
 * without a full page reload (relies on wc-cart-fragments being enqueued).
 */
function hugginbutt_cart_count_fragment( $fragments ) {
	if ( ! WC()->cart ) {
		return $fragments;
	}

	$count = WC()->cart->get_cart_contents_count();

	$fragments['.hugginbutt-cart-count'] = '<span class="hugginbutt-cart-count">' . intval( $count ) . '</span>';

	return $fragments;
}

/**
 * Disables WooCommerce's persistent cart (saving/restoring logged-in
 * customers' carts as user meta), which otherwise writes to the database on
 * every cart change.
 */
add_filter( 'woocommerce_persistent_cart_enabled', '__return_false' );

add_filter( 'kadence_post_layout', 'hugginbutt_hide_shop_archive_hero' );

/**
 * Removes Kadence's entry-hero banner (the "Shop" title on the Shop page's
 * product-archive-hero-section, the "Cart" title on the Cart page's
 * page-hero-section, the "Checkout" title on the Checkout page, the
 * "My Account" title on the My Account page, and the "About" title on the
 * About page), which duplicated the page's own header content.
 */
function hugginbutt_hide_shop_archive_hero( $layout ) {
	if ( function_exists( 'is_shop' ) && is_shop() && ! is_search() ) {
		$layout['title'] = 'hide';
	}

	if ( function_exists( 'is_cart' ) && is_cart() ) {
		$layout['title'] = 'hide';
	}

	if ( function_exists( 'is_checkout' ) && is_checkout() ) {
		$layout['title'] = 'hide';
	}

	if ( function_exists( 'is_account_page' ) && is_account_page() ) {
		$layout['title'] = 'hide';
	}

	if ( is_page( 'about' ) ) {
		$layout['title'] = 'hide';
	}

	return $layout;
}

/**
 * Sets products per row on the shop/category/tag archives.
 */
add_filter( 'loop_shop_columns', 'hugginbutt_shop_columns' );

function hugginbutt_shop_columns() {
	return 4;
}

/**
 * Markup for the "View product" icon link. Called directly by
 * hugginbutt_render_product_card() below - every product loop on the site
 * (shop, category/tag archives, related products, homepage Featured
 * Products) now renders through that one function, so this no longer needs
 * its own woocommerce_after_shop_loop_item hook.
 */
function hugginbutt_render_view_product_icon( $product ) {
	if ( ! $product instanceof WC_Product ) {
		return;
	}

	printf(
		'<a href="%1$s" class="hb-card-view-product" aria-label="%2$s"><span class="screen-reader-text">%2$s</span></a>',
		esc_url( get_permalink( $product->get_id() ) ),
		esc_attr__( 'View product', 'hugginbutt-child' )
	);
}

/**
 * Renders one .hb-product-card - the homepage Featured Products markup,
 * shared with hugginbutt_related_products() below so a product looks
 * identical everywhere it's featured in a hand-rolled grid rather than a
 * WooCommerce shop loop.
 */
function hugginbutt_render_product_card( $card_product ) {
	if ( ! $card_product instanceof WC_Product ) {
		return;
	}

	// woocommerce_template_loop_add_to_cart() reads this global rather than
	// taking the product as an argument.
	global $product;
	$outer_product = $product;
	$product        = $card_product;
	?>
	<div class="hb-product-card">
		<a href="<?php echo esc_url( $card_product->get_permalink() ); ?>" class="hb-product-card__media">
			<?php echo $card_product->get_image( 'hugginbutt-product' ); // phpcs:ignore WordPress.Security.EscapeOutput -- WC-escaped image markup. ?>
		</a>
		<h6 class="hb-product-card__name">
			<a href="<?php echo esc_url( $card_product->get_permalink() ); ?>"><?php echo esc_html( $card_product->get_name() ); ?></a>
		</h6>
		<div class="hb-product-card__price"><?php echo wp_kses_post( $card_product->get_price_html() ); ?></div>
		<div class="hb-product-card__cta">
			<?php
			hugginbutt_render_view_product_icon( $card_product );
			woocommerce_template_loop_add_to_cart();
			?>
		</div>
	</div>
	<?php
	$product = $outer_product;
}

/**
 * Replaces WooCommerce's default related-products loop (plain ul.products
 * markup) with the same .hb-product-card grid used on the homepage, so
 * related products look identical to Featured Products instead of the
 * generic shop-loop styling.
 */
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
add_action( 'woocommerce_after_single_product_summary', 'hugginbutt_related_products', 20 );

function hugginbutt_related_products() {
	global $product;

	if ( ! $product instanceof WC_Product ) {
		return;
	}

	$related_ids = wc_get_related_products( $product->get_id(), 4, array() );

	if ( empty( $related_ids ) ) {
		return;
	}
	?>
	<section class="hb-products hb-related-products">
		<div class="hb-products__inner">
			<?php hugginbutt_section_heading( __( 'You May Also Like', 'hugginbutt-child' ) ); ?>
			<div class="hb-products__grid">
				<?php
				foreach ( $related_ids as $related_id ) {
					hugginbutt_render_product_card( wc_get_product( $related_id ) );
				}
				?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Renames the empty-cart pattern's "New in store" heading to "Fresh Loot"
 * and swaps it for our own themed section-heading component (leaf
 * ornaments on each side), instead of a plain core/heading block. This
 * heading has no distinguishing class in WooCommerce's pattern, so this
 * matches on its text content and leaves every other core/heading block
 * on the site untouched.
 */
add_filter( 'render_block_core/heading', 'hugginbutt_rename_new_in_store_heading', 10, 2 );

function hugginbutt_rename_new_in_store_heading( $block_content, $block ) {
	if ( false === strpos( $block_content, 'New in store' ) ) {
		return $block_content;
	}

	ob_start();
	?>
	<div class="hb-empty-cart-heading">
		<?php hugginbutt_section_heading( __( 'Fresh Loot', 'hugginbutt-child' ) ); ?>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Replaces the "New in Store" grid on the empty-cart page (the
 * woocommerce/product-new block, part of WooCommerce's built-in empty-cart
 * block pattern) with the same .hb-product-card grid used everywhere else,
 * instead of that block's own default product-grid markup. render_block_*
 * fires for every block regardless of how it renders, so this works
 * whether the block is server- or client-hydrated.
 */
add_filter( 'render_block_woocommerce/product-new', 'hugginbutt_render_product_new_block', 10, 2 );

function hugginbutt_render_product_new_block( $block_content, $block ) {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return $block_content;
	}

	$attrs   = isset( $block['attrs'] ) ? $block['attrs'] : array();
	$columns = ! empty( $attrs['columns'] ) ? absint( $attrs['columns'] ) : 4;
	$rows    = ! empty( $attrs['rows'] ) ? absint( $attrs['rows'] ) : 1;

	$products = wc_get_products(
		array(
			'status'  => 'publish',
			'limit'   => max( 1, $columns * $rows ),
			'orderby' => 'date',
			'order'   => 'DESC',
		)
	);

	if ( empty( $products ) ) {
		return $block_content;
	}

	ob_start();
	?>
	<div class="hb-products__grid">
		<?php foreach ( $products as $new_product ) : ?>
			<?php hugginbutt_render_product_card( $new_product ); ?>
		<?php endforeach; ?>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Swaps the shop/category loop's outer wrapper from Kadence's
 * <ul class="content-wrap product-archive grid-cols ..."> to the same plain
 * <div class="hb-products__grid"> the homepage/related-products grids use,
 * now that woocommerce/content-product.php renders .hb-product-card items
 * (divs, not <li>s) instead of Kadence's default loop markup. Priority 20
 * so it runs after Kadence's own woocommerce_product_loop_start filter
 * (priority 5) and replaces its output outright.
 */
add_filter( 'woocommerce_product_loop_start', 'hugginbutt_product_loop_start', 20 );

function hugginbutt_product_loop_start() {
	return '<div class="hb-products__grid">';
}

add_filter( 'woocommerce_product_loop_end', 'hugginbutt_product_loop_end', 20 );

function hugginbutt_product_loop_end() {
	return '</div>';
}
