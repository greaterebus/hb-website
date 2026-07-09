<?php
/**
 * "Shop By Category" section: a single-row, scrollable carousel of live
 * WooCommerce categories.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$categories = hugginbutt_get_shop_categories();

if ( empty( $categories ) ) {
	return;
}
?>
<section class="hb-categories hb-paper hb-torn-bottom">
	<div class="hb-categories__inner">
		<?php hugginbutt_section_heading( hugginbutt_get_content( 'hb_category_heading' ) ); ?>

		<div class="hb-categories__carousel" data-hb-category-carousel>
			<button class="hb-categories__arrow hb-categories__arrow--prev" type="button" aria-label="<?php esc_attr_e( 'Previous categories', 'hugginbutt-child' ); ?>" data-hb-category-prev>
				<span aria-hidden="true">&lsaquo;</span>
			</button>

			<div class="hb-categories__viewport" data-hb-category-viewport tabindex="0">
				<div class="hb-categories__grid">
					<?php foreach ( $categories as $category ) : ?>
						<a href="<?php echo esc_url( $category['link'] ); ?>" class="hb-category">
							<span class="hb-category__image">
								<?php if ( ! empty( $category['image_id'] ) ) : ?>
									<?php echo wp_get_attachment_image( $category['image_id'], 'hugginbutt-category' ); ?>
								<?php else : ?>
									<?php hugginbutt_placeholder_image( $category['image'], $category['name'] ); ?>
								<?php endif; ?>
							</span>
							<span class="hb-category__name"><?php echo esc_html( $category['name'] ); ?></span>
							<span class="hb-category__cta"><?php esc_html_e( 'Shop Now', 'hugginbutt-child' ); ?></span>
						</a>
					<?php endforeach; ?>
				</div>
			</div>

			<button class="hb-categories__arrow hb-categories__arrow--next" type="button" aria-label="<?php esc_attr_e( 'Next categories', 'hugginbutt-child' ); ?>" data-hb-category-next>
				<span aria-hidden="true">&rsaquo;</span>
			</button>
		</div>
	</div>
</section>