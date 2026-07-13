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

add_action( 'wp_loaded', 'hugginbutt_ensure_primary_nav_menu' );

/**
 * So adding a new Page "just works" without a separate trip to Appearance >
 * Menus: makes sure the 'primary' location has a menu assigned (creating one
 * pre-filled with existing pages if nothing is assigned yet) and turns on
 * that menu's built-in "automatically add new top-level pages" setting.
 */
function hugginbutt_ensure_primary_nav_menu() {
	if ( ! array_key_exists( 'primary', get_registered_nav_menus() ) ) {
		return;
	}

	$locations = get_nav_menu_locations();
	$menu_id   = isset( $locations['primary'] ) ? $locations['primary'] : 0;

	if ( ! $menu_id || ! wp_get_nav_menu_object( $menu_id ) ) {
		$existing = wp_get_nav_menu_object( __( 'Primary Menu', 'hugginbutt-child' ) );
		$menu_id  = $existing ? $existing->term_id : wp_create_nav_menu( __( 'Primary Menu', 'hugginbutt-child' ) );

		if ( is_wp_error( $menu_id ) ) {
			return;
		}

		foreach ( get_pages( array( 'sort_column' => 'menu_order,post_title' ) ) as $page ) {
			wp_update_nav_menu_item(
				$menu_id,
				0,
				array(
					'menu-item-title'     => $page->post_title,
					'menu-item-object'    => 'page',
					'menu-item-object-id' => $page->ID,
					'menu-item-type'      => 'post_type',
					'menu-item-status'    => 'publish',
				)
			);
		}

		$locations['primary'] = $menu_id;
		set_theme_mod( 'nav_menu_locations', $locations );
	}

	$nav_menu_options = (array) get_option( 'nav_menu_options', array() );
	$auto_add         = isset( $nav_menu_options['auto_add'] ) ? (array) $nav_menu_options['auto_add'] : array();

	if ( ! in_array( (int) $menu_id, $auto_add, true ) ) {
		$auto_add[]                  = (int) $menu_id;
		$nav_menu_options['auto_add'] = $auto_add;
		update_option( 'nav_menu_options', $nav_menu_options );
	}
}

/**
 * Kadence declares editor-styles support and loads its own editor
 * stylesheet (plus Google Fonts CSS) so the block editor previews roughly
 * match the live site. Turning that off here so Posts/Pages/Events edit
 * on a plain, unstyled canvas instead - the site's actual dark fabric
 * background and decorative fonts made the writing surface harder to
 * read, not more helpful as a preview. Hooked after Kadence's own
 * after_setup_theme registration (priority 20 vs. its default 10) so this
 * removal runs second and wins.
 */
add_action( 'after_setup_theme', 'hugginbutt_disable_editor_styles', 20 );

function hugginbutt_disable_editor_styles() {
	remove_theme_support( 'editor-styles' );
}
