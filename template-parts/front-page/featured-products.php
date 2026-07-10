<?php
/**
 * "Featured Products" section: live WooCommerce featured/recent products,
 * or 6 "coming soon" placeholder cards if the store has no products yet.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$products = hugginbutt_get_featured_products();
$shop_url = class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
?>
<section class="hb-products">
	<div class="hb-products__inner">
		<?php hugginbutt_section_heading( hugginbutt_get_content( 'hb_featured_heading' ) ); ?>

		<div class="hb-products__grid">
			<?php if ( ! empty( $products ) ) : ?>
				<?php
				foreach ( $products as $loop_product ) :
					hugginbutt_render_product_card( $loop_product );
				endforeach;
				?>
			<?php else : ?>
				<?php for ( $i = 1; $i <= 6; $i++ ) : ?>
					<div class="hb-product-card hb-product-card--placeholder">
						<span class="hb-product-card__media">
							<?php hugginbutt_placeholder_image( 'product-' . ( ( $i - 1 ) % 3 + 1 ), __( 'Coming soon', 'hugginbutt-child' ) ); ?>
						</span>
						<h6 class="hb-product-card__name"><?php esc_html_e( 'Coming Soon', 'hugginbutt-child' ); ?></h6>
						<a href="<?php echo esc_url( $shop_url ); ?>" class="hb-button hb-button--small"><?php esc_html_e( 'Visit Shop', 'hugginbutt-child' ); ?></a>
					</div>
				<?php endfor; ?>
			<?php endif; ?>
		</div>
	</div>
</section>
