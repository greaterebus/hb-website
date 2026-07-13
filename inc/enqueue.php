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
		hugginbutt_typography_google_fonts_url(),
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

add_action( 'wp_head', 'hugginbutt_font_preconnect', 1 );

/**
 * Preconnects to Google Fonts' two hosts so the browser opens the
 * connection before it even parses the fonts stylesheet. Works for
 * whatever heading/body fonts are currently selected in the Typography
 * Customizer section (inc/typography.php), unlike preloading one font's
 * hashed .woff2 URLs directly, which would go stale the moment the
 * selection changes (or Google rotates the hash).
 */
function hugginbutt_font_preconnect() {
	?>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<?php
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
