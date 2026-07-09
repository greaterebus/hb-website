<?php
/**
 * Split band: testimonial carousel (left, parchment), mascot illustration
 * (middle), newsletter signup (right, parchment). JS-driven dot carousel
 * lives in assets/js/hugginbutt.js.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$testimonials    = hugginbutt_get_testimonials();
$testimonial_img = hugginbutt_get_content( 'hb_testimonial_image' );
$form_action     = hugginbutt_get_content( 'hb_newsletter_form_action' );
?>
<section class="hb-split-band hb-paper hb-torn-top">

	<div class="hb-testimonials">
		<h2 class="hb-testimonials__heading"><?php echo esc_html( hugginbutt_get_content( 'hb_testimonial_heading' ) ); ?></h2>

		<div class="hb-testimonial-carousel" data-hb-carousel>
			<?php foreach ( $testimonials as $index => $testimonial ) : ?>
				<blockquote class="hb-testimonial-slide<?php echo 0 === $index ? ' is-active' : ''; ?>">
					<p class="hb-testimonial-slide__quote">&ldquo;<?php echo esc_html( $testimonial['quote'] ); ?>&rdquo;</p>
					<cite class="hb-testimonial-slide__author">&mdash; <?php echo esc_html( $testimonial['author'] ); ?></cite>
					<?php hugginbutt_star_rating( $testimonial['rating'] ); ?>
				</blockquote>
			<?php endforeach; ?>

			<?php if ( count( $testimonials ) > 1 ) : ?>
				<div class="hb-testimonial-carousel__dots">
					<?php foreach ( $testimonials as $index => $testimonial ) : ?>
						<button type="button" class="hb-testimonial-carousel__dot<?php echo 0 === $index ? ' is-active' : ''; ?>" data-hb-slide="<?php echo esc_attr( $index ); ?>">
							<span class="screen-reader-text"><?php echo esc_html( sprintf( /* translators: %d: testimonial number */ __( 'Testimonial %d', 'hugginbutt-child' ), $index + 1 ) ); ?></span>
						</button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<div class="hb-split-band__mascot">
		<?php if ( $testimonial_img ) : ?>
			<img src="<?php echo esc_url( $testimonial_img ); ?>" alt="" />
		<?php else : ?>
			<?php hugginbutt_placeholder_image( 'testimonial', '' ); ?>
		<?php endif; ?>
	</div>

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
