<?php
/**
 * Template for a single hb_event post. Mirrors the card markup on the
 * /events/ schedule page (see page-events.php / hugginbutt_render_event_list())
 * so an individual event looks identical to its card there, just alone on
 * the page instead of in a list.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

if ( have_posts() ) {
	the_post();
}

$event = hugginbutt_build_event_data( get_post() );
?>
<section class="hb-events-page">
	<div class="hb-events-page__inner">
		<header class="hb-events-page__header">
			<h1 class="hb-events-page__heading"><?php echo esc_html( $event['name'] ); ?></h1>
		</header>

		<?php hugginbutt_render_event_list( array( $event ), '', false ); ?>
	</div>
</section>
<?php
get_footer();
