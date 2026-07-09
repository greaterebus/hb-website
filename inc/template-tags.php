<?php
/**
 * Small render helpers shared across the front-page template parts.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Section heading with a gold rule + leaf ornaments on each side, matching
 * the mockup's decorative header style.
 *
 * @param string $title Heading text (already translated/escaped by caller's context).
 * @param string $tag   Heading tag, defaults to h2.
 */
function hugginbutt_section_heading( $title, $tag = 'h2' ) {
	if ( '' === trim( (string) $title ) ) {
		return;
	}
	?>
	<div class="hb-section-heading">
		<span class="hb-section-heading__ornament" aria-hidden="true"><?php hugginbutt_the_icon( 'leaf' ); ?></span>
		<<?php echo tag_escape( $tag ); ?> class="hb-section-heading__text"><?php echo esc_html( $title ); ?></<?php echo tag_escape( $tag ); ?>>
		<span class="hb-section-heading__ornament" aria-hidden="true"><?php hugginbutt_the_icon( 'leaf' ); ?></span>
	</div>
	<?php
}

/**
 * Renders a 5-star rating (filled/empty), rounded down to whole stars.
 *
 * @param int $rating 1-5.
 */
function hugginbutt_star_rating( $rating ) {
	$rating = max( 0, min( 5, (int) $rating ) );
	echo '<span class="hb-stars" role="img" aria-label="' . esc_attr( sprintf( /* translators: %d: number of stars, 1-5 */ __( '%d out of 5 stars', 'hugginbutt-child' ), $rating ) ) . '">';
	for ( $i = 1; $i <= 5; $i++ ) {
		hugginbutt_the_icon( $i <= $rating ? 'star-filled' : 'star-empty', 'hb-stars__star' );
	}
	echo '</span>';
}

/**
 * Maps fallback content keys to project artwork. Homepage imagery uses
 * optimized generated WebP assets; utility/product fallbacks remain SVGs.
 *
 * @param string $key Placeholder key, e.g. "category-rings", "event", "hero".
 * @return string Absolute URL to the placeholder SVG.
 */
function hugginbutt_placeholder_url( $key ) {
	$known = array(
		'hero'                 => 'generated/hero-jewelry-v2.webp',
		'category-rings'       => 'generated/category-rings-v2.webp',
		'category-pendants'    => 'generated/category-pendants-v2.webp',
		'category-necklaces'   => 'generated/category-pendants-v2.webp',
		'category-bracelets'   => 'generated/category-bracelets-v2.webp',
		'category-earrings'    => 'generated/category-earrings-v2.webp',
		'category-accessories' => 'generated/category-accessories-v2.webp',
		'product-1'            => 'placeholders/product-placeholder-1.svg',
		'product-2'            => 'placeholders/product-placeholder-2.svg',
		'product-3'            => 'placeholders/product-placeholder-3.svg',
		'about'                => 'generated/about-makers-v2.webp',
		'event'                => 'generated/ren-faire-v2.webp',
		'testimonial'          => 'generated/testimonial-mascot.png',
		'logo'                 => 'generated/logo-gnome-v2.webp',
	);

	$file = isset( $known[ $key ] ) ? $known[ $key ] : ( 0 === strpos( $key, 'category-' ) ? 'placeholders/category-generic.svg' : 'placeholders/product-placeholder-1.svg' );

	return HUGGINBUTT_URI . '/assets/images/' . $file;
}

/**
 * Echoes an <img> for a placeholder key with the given alt text and class.
 */
function hugginbutt_placeholder_image( $key, $alt = '', $class = '' ) {
	printf(
		'<img src="%1$s" alt="%2$s" class="%3$s" loading="lazy" />',
		esc_url( hugginbutt_placeholder_url( $key ) ),
		esc_attr( $alt ),
		esc_attr( $class )
	);
}
