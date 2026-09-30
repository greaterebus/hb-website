<?php
/**
 * Split band: testimonial carousel with purchased-item thumbnails and newsletter signup.
 * JS-driven dot carousel
 * lives in assets/js/hugginbutt.js.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$testimonials = hugginbutt_get_testimonials();
$form_action  = hugginbutt_get_content( 'hb_newsletter_form_action' );
?>
<section class="hb-split-band hb-paper hb-torn-top<?php echo $testimonials ? '' : ' hb-split-band--newsletter-only'; ?>">

	<?php if ( $testimonials ) : ?>
	<div class="hb-testimonials">
		<h2 class="hb-testimonials__heading"><?php echo esc_html( hugginbutt_get_content( 'hb_testimonial_heading' ) ); ?></h2>

		<div class="hb-testimonial-carousel" data-hb-carousel aria-live="polite">
			<?php foreach ( $testimonials as $index => $testimonial ) : ?>
				<article class="hb-testimonial-slide<?php echo 0 === $index ? ' is-active' : ''; ?>">
					<h3 class="hb-testimonial-slide__title"><?php echo esc_html( $testimonial['title'] ); ?></h3>
					<blockquote class="hb-testimonial-story">
						<p class="hb-testimonial-slide__quote">&ldquo;<?php echo esc_html( $testimonial['quote'] ); ?>&rdquo;</p>
						<cite class="hb-testimonial-slide__author">&mdash; <?php echo esc_html( $testimonial['author'] ); ?></cite>
					</blockquote>
					<div class="hb-testimonial-purchases">
						<h4 class="hb-testimonial-purchases__heading"><?php esc_html_e( 'Purchased', 'hugginbutt-child' ); ?></h4>
						<ul class="hb-testimonial-products" aria-label="<?php esc_attr_e( 'Purchased items', 'hugginbutt-child' ); ?>">
							<?php foreach ( $testimonial['items'] as $item ) : ?>
								<li class="hb-testimonial-product">
									<?php if ( $item['url'] ) : ?>
										<a class="hb-testimonial-product__tile" href="<?php echo esc_url( $item['url'] ); ?>" aria-label="<?php echo esc_attr( $item['name'] ); ?>">
									<?php else : ?>
										<span class="hb-testimonial-product__tile hb-testimonial-product__tile--custom" role="img" aria-label="<?php echo esc_attr( $item['name'] ); ?>">
									<?php endif; ?>
										<span class="hb-testimonial-product__photo" aria-hidden="true">
											<?php if ( $item['image'] ) : ?>
												<?php echo wp_kses_post( $item['image'] ); ?>
											<?php else : ?>
												<?php hugginbutt_the_icon( 'feature-handmade' ); ?>
											<?php endif; ?>
										</span>
										<span class="hb-testimonial-product__name" aria-hidden="true"><?php echo esc_html( $item['name'] ); ?></span>
									<?php if ( $item['url'] ) : ?></a><?php else : ?></span><?php endif; ?>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				</article>
			<?php endforeach; ?>

			<?php if ( count( $testimonials ) > 1 ) : ?>
				<div class="hb-testimonial-carousel__dots">
					<?php foreach ( $testimonials as $index => $testimonial ) : ?>
						<button type="button" class="hb-testimonial-carousel__dot<?php echo 0 === $index ? ' is-active' : ''; ?>" data-hb-slide="<?php echo esc_attr( $index ); ?>" aria-pressed="<?php echo 0 === $index ? 'true' : 'false'; ?>">
							<span class="screen-reader-text"><?php echo esc_html( sprintf( /* translators: %d: testimonial number */ __( 'Testimonial %d', 'hugginbutt-child' ), $index + 1 ) ); ?></span>
						</button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<?php endif; ?>

	<div class="hb-newsletter">
		<h2 class="hb-newsletter__heading"><?php echo esc_html( hugginbutt_get_content( 'hb_newsletter_heading' ) ); ?></h2>
		<p><?php echo esc_html( hugginbutt_get_content( 'hb_newsletter_text' ) ); ?></p>
		<form class="hb-newsletter__form" action="<?php echo esc_url( $form_action ? $form_action : '#' ); ?>" method="post" target="_blank">
			<label class="screen-reader-text" for="hb-newsletter-email"><?php esc_html_e( 'Email address', 'hugginbutt-child' ); ?></label>
			<input type="email" id="hb-newsletter-email" name="email" placeholder="<?php esc_attr_e( 'Enter your email address', 'hugginbutt-child' ); ?>" required />
			<button type="submit" class="hb-button hb-button--primary">
				<?php echo esc_html( hugginbutt_get_content( 'hb_newsletter_button_text' ) ); ?>
			</button>
		</form>
	</div>

</section>
