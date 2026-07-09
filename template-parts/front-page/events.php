<?php
/**
 * "Upcoming Events" section: intro + button on the left, a row of
 * event cards on the right (static list, see hugginbutt_get_events()).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$button_url = hugginbutt_get_content( 'hb_events_button_url' );
if ( '' === $button_url ) {
	$events_page = get_page_by_path( 'events' );
	$button_url  = $events_page ? get_permalink( $events_page ) : home_url( '/' );
}
?>
<section class="hb-events">
	<div class="hb-events__inner">
		<div class="hb-events__intro">
			<h2 class="hb-events__heading"><?php echo esc_html( hugginbutt_get_content( 'hb_events_heading' ) ); ?></h2>
			<a href="<?php echo esc_url( $button_url ); ?>" class="hb-button hb-button--outline">
				<?php echo esc_html( hugginbutt_get_content( 'hb_events_button_text' ) ); ?>
			</a>
		</div>

		<?php $events = hugginbutt_get_events( 'upcoming', 8 ); ?>
		<?php if ( $events ) : ?>
			<div class="hb-events__grid">
				<?php foreach ( $events as $event ) : ?>
					<div class="hb-event-card hb-paper hb-torn-top hb-torn-right hb-torn-bottom hb-torn-left">
						<span class="hb-event-card__media">
							<?php if ( ! empty( $event['image_url'] ) ) : ?>
								<img src="<?php echo esc_url( $event['image_url'] ); ?>" alt="<?php echo esc_attr( $event['name'] ); ?>" />
							<?php else : ?>
								<?php hugginbutt_placeholder_image( $event['image'], $event['name'] ); ?>
							<?php endif; ?>
							<span class="hb-event-card__date"><?php echo esc_html( strtoupper( $event['date_range'] ) ); ?></span>
						</span>
						<h3 class="hb-event-card__name"><?php echo esc_html( $event['name'] ); ?><?php if ( ! empty( $event['date_long'] ) ) : ?> <span class="hb-event-card__date-long">- <?php echo esc_html( $event['date_long'] ); ?></span><?php endif; ?></h3>
						<p class="hb-event-card__location"><?php echo esc_html( $event['location'] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
			<p class="hb-events__empty"><?php esc_html_e( 'No upcoming events right now — check back soon!', 'hugginbutt-child' ); ?></p>
		<?php endif; ?>
	</div>
</section>
