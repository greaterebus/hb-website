<?php
/**
 * Repeating list content with no natural home in the Customizer or
 * WooCommerce: ren-faire events, customer testimonials, and the four
 * "about us" feature callouts. Edit the arrays below directly to add,
 * remove, or change entries — each item follows the same shape.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Ren faire events. Data-driven from the "Events" admin screen (see
 * inc/events-cpt.php) rather than a hardcoded list — add/edit/remove events
 * there and this will pick them up.
 *
 * @param string $when  'upcoming' (default) returns events whose end date
 *                       hasn't passed yet, soonest first. 'past' returns
 *                       events whose end date has passed, most recent first.
 * @param int    $limit posts_per_page for the query. Default -1 (all).
 */
function hugginbutt_get_events( $when = 'upcoming', $limit = -1 ) {
	$today = current_time( 'Y-m-d' );
	$args  = array(
		'post_type'      => 'hb_event',
		'post_status'    => 'publish',
		'posts_per_page' => $limit,
		'meta_key'       => 'hb_event_end_date',
		'orderby'        => 'meta_value',
	);

	if ( 'past' === $when ) {
		$args['order']      = 'DESC';
		$args['meta_query'] = array(
			array(
				'key'     => 'hb_event_end_date',
				'value'   => $today,
				'compare' => '<',
				'type'    => 'DATE',
			),
		);
	} else {
		$args['order']      = 'ASC';
		$args['meta_query'] = array(
			array(
				'key'     => 'hb_event_end_date',
				'value'   => $today,
				'compare' => '>=',
				'type'    => 'DATE',
			),
		);
	}

	$query = new WP_Query( $args );

	$events = array();
	foreach ( $query->posts as $event_post ) {
		$image_url   = get_the_post_thumbnail_url( $event_post, 'hugginbutt-event' );
		$description = trim( $event_post->post_content );
		$start_date  = get_post_meta( $event_post->ID, 'hb_event_start_date', true );
		$end_date    = get_post_meta( $event_post->ID, 'hb_event_end_date', true );
		$events[]    = array(
			'date_range'  => hugginbutt_format_event_date_range( $start_date, $end_date ),
			'date_long'   => hugginbutt_format_event_date_long( $start_date, $end_date ),
			'name'        => get_the_title( $event_post ),
			'location'    => get_post_meta( $event_post->ID, 'hb_event_location', true ),
			'image_url'   => $image_url ? $image_url : '',
			'image'       => 'event',
			'description' => $description ? apply_filters( 'the_content', $description ) : '',
		);
	}

	return apply_filters( 'hugginbutt_events', $events, $when );
}

/**
 * Customer testimonials shown in the split band above the newsletter signup.
 * `rating` is 1-5.
 */
function hugginbutt_get_testimonials() {
	$testimonials = array(
		array(
			'quote'  => 'The craftsmanship is amazing and the designs are so unique! I always get compliments on it at the faire.',
			'author' => 'Jessica M.',
			'rating' => 5,
		),
		array(
			'quote'  => 'Every piece feels like it has its own story. My Dragon Eye Ring is my favorite thing I own.',
			'author' => 'Sam R.',
			'rating' => 5,
		),
		array(
			'quote'  => 'Fast shipping, gorgeous packaging, and the earrings are even prettier in person.',
			'author' => 'Devon P.',
			'rating' => 4,
		),
	);

	return apply_filters( 'hugginbutt_testimonials', $testimonials );
}

/**
 * The four small icon+label features under the "About Us" copy.
 * `icon` keys map to inc/icons.php.
 */
function hugginbutt_get_about_features() {
	$features = array(
		array(
			'icon'  => 'feature-handmade',
			'label' => __( 'Handmade', 'hugginbutt-child' ),
			'sub'   => __( 'Each piece is unique', 'hugginbutt-child' ),
		),
		array(
			'icon'  => 'feature-fairelife',
			'label' => __( 'Faire Life', 'hugginbutt-child' ),
			'sub'   => __( "You'll find us on the road", 'hugginbutt-child' ),
		),
		array(
			'icon'  => 'feature-quality',
			'label' => __( 'Quality Materials', 'hugginbutt-child' ),
			'sub'   => __( 'Made to last', 'hugginbutt-child' ),
		),
		array(
			'icon'  => 'feature-shipping',
			'label' => __( 'Ships Worldwide', 'hugginbutt-child' ),
			'sub'   => __( 'We ship everywhere', 'hugginbutt-child' ),
		),
	);

	return apply_filters( 'hugginbutt_about_features', $features );
}

/**
 * Fallback labels for the 5 "Shop By Category" tiles, used to pad out real
 * WooCommerce product categories when fewer than 5 exist yet.
 */
function hugginbutt_get_fallback_categories() {
	return array(
		array( 'name' => __( 'Rings', 'hugginbutt-child' ), 'image' => 'category-rings' ),
		array( 'name' => __( 'Pendants', 'hugginbutt-child' ), 'image' => 'category-pendants' ),
		array( 'name' => __( 'Bracelets', 'hugginbutt-child' ), 'image' => 'category-bracelets' ),
		array( 'name' => __( 'Earrings', 'hugginbutt-child' ), 'image' => 'category-earrings' ),
		array( 'name' => __( 'Accessories', 'hugginbutt-child' ), 'image' => 'category-accessories' ),
	);
}
