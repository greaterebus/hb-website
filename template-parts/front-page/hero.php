<?php
/**
 * Hero section: full-bleed product banner photo with the "Shop Now" CTA
 * overlaid on top.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$heading     = hugginbutt_get_content( 'hb_hero_heading' );
$button_text = hugginbutt_get_content( 'hb_hero_button_text' );
$shop_url    = class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
$banner_base = content_url( '/uploads/2026/07/product-banner' );
?>
<section class="hb-hero">
	<div class="hb-hero__inner">
		<div class="hb-hero__content">
			<img
				src="<?php echo esc_url( "{$banner_base}-1536x659.png" ); ?>"
				srcset="<?php echo esc_attr( "{$banner_base}-768x329.png 768w, {$banner_base}-1024x439.png 1024w, {$banner_base}-1536x659.png 1536w" ); ?>"
				sizes="100vw"
				alt="<?php echo esc_attr( $heading ); ?>"
				class="hb-hero__banner-image"
			/>
			<a href="<?php echo esc_url( $shop_url ); ?>" class="hb-button hb-button--primary hb-hero__cta">
				<?php echo esc_html( $button_text ); ?>
				<?php hugginbutt_the_icon( 'arrow', 'hb-button__arrow' ); ?>
			</a>
		</div>
	</div>
</section>
