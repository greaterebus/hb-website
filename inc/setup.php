<?php
/**
 * Theme supports, image sizes, nav menu locations.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'after_setup_theme', 'hugginbutt_theme_setup' );

function hugginbutt_theme_setup() {
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 120,
			'width'       => 120,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	add_image_size( 'hugginbutt-hero', 900, 700, true );
	add_image_size( 'hugginbutt-category', 500, 500, true );
	add_image_size( 'hugginbutt-about', 760, 860, true );
	add_image_size( 'hugginbutt-event', 400, 300, true );
	add_image_size( 'hugginbutt-product', 500, 500, true );

	// The 'primary' nav menu location is already registered by the Kadence
	// parent theme (Appearance > Menus). We only add the footer columns here.
	register_nav_menus(
		array(
			'footer-shop'        => __( 'Footer – Shop Links', 'hugginbutt-child' ),
			'footer-collections' => __( 'Footer – Collections Links', 'hugginbutt-child' ),
			'footer-care'        => __( 'Footer – Customer Care Links', 'hugginbutt-child' ),
		)
	);
}
