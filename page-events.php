<?php
/**
 * Template for the "/events/" page: the full ren-faire schedule, split into
 * "Upcoming Events" (soonest first) and "Past Events" (most recent first).
 * Picked up automatically by WordPress for the page with slug "events"
 * (template hierarchy: page-{slug}.php). Data comes from hugginbutt_get_events().
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'hugginbutt_render_event_list' ) ) {
	/**
	 * Renders one "hb-events-page__list" of event items, or an empty-state
	 * message if there are none.
	 */
	function hugginbutt_render_event_list( array $events, $empty_message ) {
		if ( ! $events ) {
			printf( '<p class="hb-events-page__empty">%s</p>', esc_html( $empty_message ) );
			return;
		}
		?>
		<div class="hb-events-page__list">
			<?php foreach ( $events as $event ) : ?>
				<article class="hb-events-page__item hb-paper">
					<a href="<?php echo esc_url( $event['permalink'] ); ?>" class="hb-event-card__media hb-events-page__media">
						<?php if ( ! empty( $event['image_url'] ) ) : ?>
							<img src="<?php echo esc_url( $event['image_url'] ); ?>" alt="<?php echo esc_attr( $event['name'] ); ?>" />
						<?php else : ?>
							<?php hugginbutt_placeholder_image( $event['image'], $event['name'] ); ?>
						<?php endif; ?>
						<span class="hb-event-card__date"><?php echo esc_html( strtoupper( $event['date_range'] ) ); ?></span>
					</a>
					<div class="hb-events-page__details">
						<h3 class="hb-event-card__name hb-events-page__name"><?php echo esc_html( $event['name'] ); ?><?php if ( ! empty( $event['date_long'] ) ) : ?> <span class="hb-event-card__date-long">- <?php echo esc_html( $event['date_long'] ); ?></span><?php endif; ?></h3>
						<p class="hb-event-card__location"><?php echo esc_html( $event['location'] ); ?></p>
						<?php if ( ! empty( $event['description'] ) ) : ?>
							<div class="hb-events-page__description">
								<?php echo wp_kses_post( $event['description'] ); ?>
							</div>
						<?php endif; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
		<?php
	}
}

get_header();

if ( have_posts() ) {
	the_post();
}
?>
<section class="hb-events-page">
	<div class="hb-events-page__inner">
		<header class="hb-events-page__header">
			<h1 class="hb-events-page__heading"><?php the_title(); ?></h1>
		</header>

		<section class="hb-events-page__section">
			<h2 class="hb-events-page__section-heading"><?php esc_html_e( 'Upcoming Events', 'hugginbutt-child' ); ?></h2>
			<?php hugginbutt_render_event_list( hugginbutt_get_events( 'upcoming' ), __( 'No upcoming events right now — check back soon!', 'hugginbutt-child' ) ); ?>
		</section>

		<section class="hb-events-page__section">
			<h2 class="hb-events-page__section-heading"><?php esc_html_e( 'Past Events', 'hugginbutt-child' ); ?></h2>
			<?php hugginbutt_render_event_list( hugginbutt_get_events( 'past' ), __( 'No past events yet.', 'hugginbutt-child' ) ); ?>
		</section>
	</div>
</section>
<?php
get_footer();
