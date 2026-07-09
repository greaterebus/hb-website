<?php
/**
 * "A Little About Us" section: illustration on the left, brand story +
 * 4 feature callouts on the right.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$heading = hugginbutt_get_content( 'hb_about_heading' );
$image_url = hugginbutt_get_content( 'hb_about_image' );
?>
<section class="hb-about hb-paper hb-torn-bottom">
	<div class="hb-about__inner">
		<div class="hb-about__media">
			<?php if ( $image_url ) : ?>
				<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $heading ); ?>" />
			<?php else : ?>
				<?php hugginbutt_placeholder_image( 'about', $heading ); ?>
			<?php endif; ?>
		</div>
		<div class="hb-about__content">
			<?php hugginbutt_section_heading( $heading ); ?>
			<p><?php echo esc_html( hugginbutt_get_content( 'hb_about_paragraph_1' ) ); ?></p>
			<p><?php echo esc_html( hugginbutt_get_content( 'hb_about_paragraph_2' ) ); ?></p>

			<div class="hb-about__features">
				<?php foreach ( hugginbutt_get_about_features() as $feature ) : ?>
					<div class="hb-about-feature">
						<?php hugginbutt_the_icon( $feature['icon'], 'hb-about-feature__icon' ); ?>
						<span class="hb-about-feature__label"><?php echo esc_html( $feature['label'] ); ?></span>
						<span class="hb-about-feature__sub"><?php echo esc_html( $feature['sub'] ); ?></span>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
