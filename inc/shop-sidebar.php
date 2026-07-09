<?php
/**
 * Left-hand "Shop Filters" sidebar on the shop and product category pages,
 * built on Kadence's existing sidebar/layout system (register a sidebar,
 * then force Kadence to show it as a left sidebar on shop pages) rather
 * than hand-rolling a new column layout.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'widgets_init', 'hugginbutt_register_shop_sidebar' );

function hugginbutt_register_shop_sidebar() {
	register_sidebar(
		array(
			'name'          => __( 'Shop Filters', 'hugginbutt-child' ),
			'id'            => 'hb-shop-filters',
			'description'   => __( 'Widgets shown in the left-hand filter panel on the shop and product category pages.', 'hugginbutt-child' ),
			'before_widget' => '<div class="hb-shop-filter-widget widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="hb-shop-filter-widget__title widget-title">',
			'after_title'   => '</h3>',
		)
	);
}

add_filter( 'kadence_post_layout', 'hugginbutt_force_shop_sidebar' );

function hugginbutt_force_shop_sidebar( $layout ) {
	if (
		class_exists( 'WooCommerce' ) &&
		( is_shop() || is_product_category() || is_product_tag() ) &&
		is_active_sidebar( 'hb-shop-filters' )
	) {
		$layout['sidebar']    = 'enable';
		$layout['side']       = 'left';
		$layout['sidebar_id'] = 'hb-shop-filters';
	}

	return $layout;
}
