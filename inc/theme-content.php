<?php
/**
 * Homepage content getters. Events and testimonials are managed in WordPress;
 * the small About features and category fallbacks are defined here.
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
		$events[] = hugginbutt_build_event_data( $event_post );
	}

	return apply_filters( 'hugginbutt_events', $events, $when );
}

/**
 * Builds the same event array shape used by hugginbutt_get_events() from a
 * single hb_event post - shared so the homepage/schedule cards and the
 * single-hb_event.php detail template stay in sync with one source of truth.
 */
function hugginbutt_build_event_data( $event_post ) {
	$image_url   = get_the_post_thumbnail_url( $event_post, 'hugginbutt-event' );
	$description = trim( $event_post->post_content );
	$start_date  = get_post_meta( $event_post->ID, 'hb_event_start_date', true );
	$end_date    = get_post_meta( $event_post->ID, 'hb_event_end_date', true );

	return array(
		'date_range'  => hugginbutt_format_event_date_range( $start_date, $end_date ),
		'date_long'   => hugginbutt_format_event_date_long( $start_date, $end_date ),
		'name'        => get_the_title( $event_post ),
		'location'    => get_post_meta( $event_post->ID, 'hb_event_location', true ),
		'image_url'   => $image_url ? $image_url : '',
		'image'       => 'event',
		'description' => $description ? apply_filters( 'the_content', $description ) : '',
		'permalink'   => get_permalink( $event_post ),
	);
}

/**
 * Renders one "hb-events-page__list" of event items (the same card markup
 * used on the /events/ schedule page, the homepage Upcoming Events section,
 * and single-hb_event.php), or an empty-state message if there are none.
 * $link_media controls whether the photo links to the event's permalink -
 * disabled on the event's own single page, since linking to itself there
 * would be pointless.
 */
function hugginbutt_render_event_list( array $events, $empty_message, $link_media = true ) {
	if ( ! $events ) {
		printf( '<p class="hb-events-page__empty">%s</p>', esc_html( $empty_message ) );
		return;
	}
	?>
	<div class="hb-events-page__list">
		<?php foreach ( $events as $event ) : ?>
			<article class="hb-events-page__item hb-paper">
				<?php
				$media_tag = $link_media ? 'a' : 'span';
				?>
				<<?php echo esc_html( $media_tag ); ?>
					<?php if ( $link_media ) : ?>href="<?php echo esc_url( $event['permalink'] ); ?>"<?php endif; ?>
					class="hb-event-card__media hb-events-page__media"
				>
					<?php if ( ! empty( $event['image_url'] ) ) : ?>
						<img src="<?php echo esc_url( $event['image_url'] ); ?>" alt="<?php echo esc_attr( $event['name'] ); ?>" />
					<?php else : ?>
						<?php hugginbutt_placeholder_image( $event['image'], $event['name'] ); ?>
					<?php endif; ?>
					<span class="hb-event-card__date"><?php echo esc_html( strtoupper( $event['date_range'] ) ); ?></span>
				</<?php echo esc_html( $media_tag ); ?>>
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

/**
 * Published customer stories, with public catalog products or a custom item name.
 */
function hugginbutt_get_testimonials() {
	$testimonials = array();
	$posts = get_posts( array(
		'post_type' => 'hb_testimonial',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'orderby' => array( 'menu_order' => 'ASC', 'date' => 'DESC', 'ID' => 'DESC' ),
	) );
	foreach ( $posts as $post ) {
		$author = get_post_meta( $post->ID, 'hb_testimonial_author', true );
		$title = trim( wp_strip_all_tags( $post->post_title ) );
		$quote = trim( wp_strip_all_tags( strip_shortcodes( $post->post_content ) ) );
		$items = array();
		foreach ( (array) get_post_meta( $post->ID, 'hb_testimonial_products', true ) as $product_id ) {
			if ( 'product' !== get_post_type( $product_id ) || 'publish' !== get_post_status( $product_id ) || post_password_required( $product_id ) ) {
				continue;
			}
			$items[] = array(
				'name' => get_the_title( $product_id ),
				'url' => get_permalink( $product_id ),
				'image' => get_the_post_thumbnail( $product_id, 'woocommerce_thumbnail', array( 'class' => 'hb-testimonial-product__image', 'alt' => '' ) ),
			);
		}
		$other_item = get_post_meta( $post->ID, 'hb_testimonial_item', true );
		if ( $other_item ) {
			$items[] = array( 'name' => $other_item, 'url' => '', 'image' => '' );
		}
		if ( ! $title || ! $quote || ! $author || ! $items ) {
			continue;
		}
		$testimonials[] = array( 'title' => $title, 'quote' => $quote, 'author' => $author, 'items' => $items );
	}

	return apply_filters( 'hugginbutt_testimonials', $testimonials );
}

/**
 * The small icon+label features under the "About Us" copy.
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
			'icon'  => 'feature-quality',
			'label' => __( 'Quality Materials', 'hugginbutt-child' ),
			'sub'   => __( 'Made to last', 'hugginbutt-child' ),
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
