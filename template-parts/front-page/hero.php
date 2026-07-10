<?php
/**
 * Main shop image: full-bleed banner photo with the "Shop Now" CTA
 * overlaid on top.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$button_text = hugginbutt_get_content( 'hb_shop_banner_button_text' );
$shop_url    = class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
$hero_image  = hugginbutt_get_content( 'hb_shop_banner_image' );

// Falls back to the original hardcoded banner (with its pre-generated
// responsive sizes) until an editor picks a photo in Customizer > Main Shop Image.
if ( $hero_image ) {
	$hero_src           = $hero_image;
	$hero_attachment_id = attachment_url_to_postid( $hero_image );
	$hero_srcset        = $hero_attachment_id ? wp_get_attachment_image_srcset( $hero_attachment_id, 'full' ) : false;
} else {
	$banner_base = content_url( '/uploads/2026/07/product-banner' );
	$hero_src    = "{$banner_base}-1536x659.png";
	$hero_srcset = "{$banner_base}-768x329.png 768w, {$banner_base}-1024x439.png 1024w, {$banner_base}-1536x659.png 1536w";
}
?>
<section class="hb-hero">
	<div class="hb-hero__inner">
		<div class="hb-hero__content">
			<img
				src="<?php echo esc_url( $hero_src ); ?>"
				<?php if ( $hero_srcset ) : ?>srcset="<?php echo esc_attr( $hero_srcset ); ?>"<?php endif; ?>
				sizes="100vw"
				alt="<?php echo esc_attr( sprintf( __( '%s shop banner', 'hugginbutt-child' ), get_bloginfo( 'name' ) ) ); ?>"
				class="hb-hero__banner-image"
			/>
			<a href="<?php echo esc_url( $shop_url ); ?>" class="hb-button hb-button--primary hb-hero__cta">
				<?php echo esc_html( $button_text ); ?>
				<?php hugginbutt_the_icon( 'arrow', 'hb-button__arrow' ); ?>
			</a>
		</div>
	</div>
</section>
