<?php
/**
 * "Events" custom post type: gives site editors an Events screen in
 * wp-admin to add/edit/remove ren-faire dates without touching code.
 * Powers the "Upcoming Events" section on the front page — see
 * hugginbutt_get_events() in inc/theme-content.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', 'hugginbutt_register_event_post_type' );

// Force the classic editor for events. The "Event Details" meta box below
// renders fine there; in the block editor, classic meta boxes go through a
// fragile AJAX compatibility layer that silently disappears if anything
// (even a stray PHP notice) breaks its JSON response — which is exactly
// what was happening here.
add_filter( 'use_block_editor_for_post_type', 'hugginbutt_disable_block_editor_for_events', 10, 2 );

function hugginbutt_disable_block_editor_for_events( $use_block_editor, $post_type ) {
	if ( 'hb_event' === $post_type ) {
		return false;
	}
	return $use_block_editor;
}

function hugginbutt_register_event_post_type() {
	register_post_type(
		'hb_event',
		array(
			'labels'        => array(
				'name'          => __( 'Events', 'hugginbutt-child' ),
				'singular_name' => __( 'Event', 'hugginbutt-child' ),
				'add_new_item'  => __( 'Add New Event', 'hugginbutt-child' ),
				'edit_item'     => __( 'Edit Event', 'hugginbutt-child' ),
				'all_items'     => __( 'Events', 'hugginbutt-child' ),
				'search_items'  => __( 'Search Events', 'hugginbutt-child' ),
				'not_found'     => __( 'No events found', 'hugginbutt-child' ),
			),
			'public'        => false,
			'show_ui'       => true,
			'show_in_menu'  => true,
			'show_in_rest'  => true,
			'menu_icon'     => 'dashicons-calendar-alt',
			'menu_position' => 25,
			'supports'      => array( 'title', 'editor', 'thumbnail' ),
		)
	);
}

add_action( 'add_meta_boxes', 'hugginbutt_event_meta_box' );

function hugginbutt_event_meta_box() {
	add_meta_box(
		'hb_event_details',
		__( 'Event Details', 'hugginbutt-child' ),
		'hugginbutt_render_event_meta_box',
		'hb_event',
		'normal',
		'high'
	);
}

function hugginbutt_render_event_meta_box( $post ) {
	wp_nonce_field( 'hugginbutt_save_event', 'hugginbutt_event_nonce' );
	$start    = get_post_meta( $post->ID, 'hb_event_start_date', true );
	$end      = get_post_meta( $post->ID, 'hb_event_end_date', true );
	$location = get_post_meta( $post->ID, 'hb_event_location', true );
	?>
	<p>
		<label for="hb_event_start_date"><strong><?php esc_html_e( 'Start date', 'hugginbutt-child' ); ?></strong></label><br>
		<input type="date" id="hb_event_start_date" name="hb_event_start_date" value="<?php echo esc_attr( $start ); ?>" />
	</p>
	<p>
		<label for="hb_event_end_date"><strong><?php esc_html_e( 'End date', 'hugginbutt-child' ); ?></strong></label><br>
		<input type="date" id="hb_event_end_date" name="hb_event_end_date" value="<?php echo esc_attr( $end ); ?>" />
	</p>
	<p>
		<label for="hb_event_location"><strong><?php esc_html_e( 'Location', 'hugginbutt-child' ); ?></strong></label><br>
		<input type="text" id="hb_event_location" name="hb_event_location" class="widefat" placeholder="City, ST" value="<?php echo esc_attr( $location ); ?>" />
	</p>
	<p class="description">
		<?php esc_html_e( 'Set a featured image above for the event card photo (falls back to a placeholder if left blank). Use the description editor below for a longer blurb shown on the full Events page.', 'hugginbutt-child' ); ?>
	</p>
	<?php
}

add_action( 'save_post_hb_event', 'hugginbutt_save_event_meta' );

function hugginbutt_save_event_meta( $post_id ) {
	if ( ! isset( $_POST['hugginbutt_event_nonce'] ) || ! wp_verify_nonce( $_POST['hugginbutt_event_nonce'], 'hugginbutt_save_event' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( isset( $_POST['hb_event_start_date'] ) ) {
		update_post_meta( $post_id, 'hb_event_start_date', sanitize_text_field( wp_unslash( $_POST['hb_event_start_date'] ) ) );
	}
	if ( isset( $_POST['hb_event_end_date'] ) ) {
		update_post_meta( $post_id, 'hb_event_end_date', sanitize_text_field( wp_unslash( $_POST['hb_event_end_date'] ) ) );
	}
	if ( isset( $_POST['hb_event_location'] ) ) {
		update_post_meta( $post_id, 'hb_event_location', sanitize_text_field( wp_unslash( $_POST['hb_event_location'] ) ) );
	}
}

// Admin list table: show dates + location at a glance instead of just the title.
add_filter( 'manage_hb_event_posts_columns', 'hugginbutt_event_columns' );

function hugginbutt_event_columns( $columns ) {
	$columns['hb_event_dates']    = __( 'Dates', 'hugginbutt-child' );
	$columns['hb_event_location'] = __( 'Location', 'hugginbutt-child' );
	return $columns;
}

add_action( 'manage_hb_event_posts_custom_column', 'hugginbutt_event_column_content', 10, 2 );

function hugginbutt_event_column_content( $column, $post_id ) {
	if ( 'hb_event_dates' === $column ) {
		echo esc_html(
			hugginbutt_format_event_date_range(
				get_post_meta( $post_id, 'hb_event_start_date', true ),
				get_post_meta( $post_id, 'hb_event_end_date', true )
			)
		);
	} elseif ( 'hb_event_location' === $column ) {
		echo esc_html( get_post_meta( $post_id, 'hb_event_location', true ) );
	}
}

/**
 * Formats two Y-m-d dates as "May 24 – Jun 23", or just "May 24" if the
 * event is a single day (or the end date hasn't been set).
 */
function hugginbutt_format_event_date_range( $start, $end ) {
	if ( ! $start ) {
		return '';
	}
	$start_ts = strtotime( $start );
	$end_ts   = $end ? strtotime( $end ) : $start_ts;

	$start_label = date_i18n( 'M j', $start_ts );
	if ( ! $end_ts || $end_ts === $start_ts ) {
		return $start_label;
	}
	return $start_label . ' – ' . date_i18n( 'M j', $end_ts );
}

/**
 * Long-form date for display next to an event's title, e.g.
 * "July 12th 2026", or "July 29th – August 26th 2026" for a date range
 * (year only shown once if both dates fall in the same year).
 */
function hugginbutt_format_event_date_long( $start, $end ) {
	if ( ! $start ) {
		return '';
	}
	$start_ts = strtotime( $start );
	$end_ts   = $end ? strtotime( $end ) : $start_ts;

	if ( ! $end_ts || $end_ts === $start_ts ) {
		return date_i18n( 'F jS Y', $start_ts );
	}

	if ( date_i18n( 'Y', $start_ts ) === date_i18n( 'Y', $end_ts ) ) {
		return date_i18n( 'F jS', $start_ts ) . ' – ' . date_i18n( 'F jS Y', $end_ts );
	}

	return date_i18n( 'F jS Y', $start_ts ) . ' – ' . date_i18n( 'F jS Y', $end_ts );
}

// One-time cleanup: remove the placeholder events that were seeded in
// while this feature was being built, so the site starts from a clean
// slate of real, admin-entered events only. Runs once.
add_action( 'init', 'hugginbutt_remove_seeded_dummy_events', 20 );

function hugginbutt_remove_seeded_dummy_events() {
	if ( get_option( 'hugginbutt_dummy_events_removed' ) ) {
		return;
	}
	update_option( 'hugginbutt_dummy_events_removed', 1 );

	$dummy_titles = array(
		'Texas Renaissance Festival',
		'Bristol Renaissance Faire',
		'Minnesota Renaissance Festival',
		'New York Renaissance Faire',
	);

	$dummy_posts = get_posts(
		array(
			'post_type'      => 'hb_event',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'fields'         => 'ids',
		)
	);

	foreach ( $dummy_posts as $post_id ) {
		if ( in_array( get_the_title( $post_id ), $dummy_titles, true ) ) {
			wp_delete_post( $post_id, true );
		}
	}
}
