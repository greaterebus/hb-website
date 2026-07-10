<?php
/**
 * Live WooCommerce data for the "Shop By Category" and "Featured Products"
 * homepage sections. Every function here is safe to call even if
 * WooCommerce is deactivated (returns an empty array).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
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
 * product-archive-hero-section, and the "Cart" title on the Cart page's
 * page-hero-section), which duplicated the page's own header content.
 */
function hugginbutt_hide_shop_archive_hero( $layout ) {
	if ( function_exists( 'is_shop' ) && is_shop() && ! is_search() ) {
		$layout['title'] = 'hide';
	}

	if ( function_exists( 'is_cart' ) && is_cart() ) {
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
 * Adds a "View product" icon link into each shop/category card, alongside
 * the existing Add to Cart button. Kadence opens .product-action-wrap on
 * this same hook at priority 5 and closes it at priority 20, with
 * WooCommerce's own add-to-cart button rendering at the default priority
 * 10 - priority 7 lands us inside that wrap, before the cart button.
 */
add_action( 'woocommerce_after_shop_loop_item', 'hugginbutt_view_product_icon', 7 );

function hugginbutt_view_product_icon() {
	if ( ! ( is_shop() || is_product_category() || is_product_tag() ) ) {
		return;
	}

	global $product;

	if ( ! $product instanceof WC_Product ) {
		return;
	}

	printf(
		'<a href="%1$s" class="hb-card-view-product" aria-label="%2$s"><span class="screen-reader-text">%2$s</span></a>',
		esc_url( get_permalink( $product->get_id() ) ),
		esc_attr__( 'View product', 'hugginbutt-child' )
	);
}
