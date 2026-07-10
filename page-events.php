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
