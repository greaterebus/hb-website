<?php
/**
 * Overrides WooCommerce's default loop item template (normally an <li> built
 * from a chain of woocommerce_before/after_shop_loop_item* hooks) so every
 * product loop on the site - shop archive, category/tag archives, related
 * products - renders through the same hugginbutt_render_product_card() markup
 * as the homepage Featured Products section, instead of two different card
 * designs.
 *
 * @see woocommerce/templates/content-product.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

if ( ! is_a( $product, 'WC_Product' ) || ! $product->is_visible() ) {
	return;
}

hugginbutt_render_product_card( $product );
