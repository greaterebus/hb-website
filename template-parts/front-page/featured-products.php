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
					global $product; // woocommerce_template_loop_add_to_cart() reads this global.
					$product = $loop_product;
					?>
					<div class="hb-product-card">
						<a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="hb-product-card__media">
							<?php echo $product->get_image( 'hugginbutt-product' ); // phpcs:ignore WordPress.Security.EscapeOutput -- WC-escaped image markup. ?>
						</a>
						<h3 class="hb-product-card__name">
							<a href="<?php echo esc_url( $product->get_permalink() ); ?>"><?php echo esc_html( $product->get_name() ); ?></a>
						</h3>
						<div class="hb-product-card__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>
						<div class="hb-product-card__cta">
							<?php woocommerce_template_loop_add_to_cart(); ?>
						</div>
					</div>
				<?php endforeach; ?>
			<?php else : ?>
				<?php for ( $i = 1; $i <= 6; $i++ ) : ?>
					<div class="hb-product-card hb-product-card--placeholder">
						<span class="hb-product-card__media">
							<?php hugginbutt_placeholder_image( 'product-' . ( ( $i - 1 ) % 3 + 1 ), __( 'Coming soon', 'hugginbutt-child' ) ); ?>
						</span>
						<h3 class="hb-product-card__name"><?php esc_html_e( 'Coming Soon', 'hugginbutt-child' ); ?></h3>
						<a href="<?php echo esc_url( $shop_url ); ?>" class="hb-button hb-button--small"><?php esc_html_e( 'Visit Shop', 'hugginbutt-child' ); ?></a>
					</div>
				<?php endfor; ?>
			<?php endif; ?>
		</div>
	</div>
</section>
