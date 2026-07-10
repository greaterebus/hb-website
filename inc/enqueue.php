<?php
/**
 * Styles and scripts.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_enqueue_scripts', 'hugginbutt_enqueue_assets', 20 );

function hugginbutt_enqueue_assets() {
	wp_enqueue_style(
		'hugginbutt-fonts',
		'https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'hugginbutt-style',
		HUGGINBUTT_URI . '/assets/css/hugginbutt.css',
		array( 'kadence-global' ),
		(string) filemtime( HUGGINBUTT_DIR . '/assets/css/hugginbutt.css' )
	);

	wp_enqueue_script(
		'hugginbutt-script',
		HUGGINBUTT_URI . '/assets/js/hugginbutt.js',
		array(),
		(string) filemtime( HUGGINBUTT_DIR . '/assets/js/hugginbutt.js' ),
		true
	);

	if ( class_exists( '\\WooCommerce' ) && ( is_cart() || is_checkout() ) ) {
		wp_enqueue_script( 'wc-cart-fragments' );
	}
}

add_action( 'wp_head', 'hugginbutt_preload_fonts', 1 );

/**
 * Preloads the Latin-subset woff2 files for the Google Fonts used in
 * headings (Cinzel) and body copy (Cormorant Garamond), so the browser
 * starts fetching them immediately instead of waiting to parse the Google
 * Fonts CSS first - shrinks the fallback-font flash before they swap in.
 *
 * The hashed URLs are a snapshot of what Google Fonts currently serves for
 * this font/weight/subset combo; if Google rotates them the preload just
 * silently stops helping (no breakage) and this list would need refreshing.
 */
function hugginbutt_preload_fonts() {
	$fonts = array(
		'https://fonts.gstatic.com/s/cinzel/v26/8vIJ7ww63mVu7gt79mT7.woff2',
		'https://fonts.gstatic.com/s/cormorantgaramond/v21/co3bmX5slCNuHLi8bLeY9MK7whWMhyjYqXtK.woff2',
		'https://fonts.gstatic.com/s/cormorantgaramond/v21/co3smX5slCNuHLi8bLeY9MK7whWMhyjYrGFEsdtdc62E6zd58jD-iNM8.woff2',
	);

	foreach ( $fonts as $font_url ) {
		printf(
			'<link rel="preload" as="font" type="font/woff2" href="%s" crossorigin>' . "\n",
			esc_url( $font_url )
		);
	}
}

/**
 * Replace the uploaded site icon with the theme's transparent fairy-wing set.
 * WordPress requests 32, 180, and 192px variants for browser and device tags.
 */
add_filter( 'get_site_icon_url', 'hugginbutt_site_icon_url', 10, 3 );

function hugginbutt_site_icon_url( $url, $size, $blog_id ) {
	$available = array( 16, 32, 48, 180, 192, 512 );
	$size      = absint( $size );
	$selected  = 512;

	foreach ( $available as $candidate ) {
		if ( $candidate >= $size ) {
			$selected = $candidate;
			break;
		}
	}

	return HUGGINBUTT_URI . '/assets/images/generated/fairy-wings-favicon-' . $selected . '.png';
}
