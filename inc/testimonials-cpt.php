<?php
/** Editor-managed customer stories, with purchased products instead of ratings. */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', 'hugginbutt_register_testimonials' );
function hugginbutt_register_testimonials() {
	register_post_type( 'hb_testimonial', array(
		'labels' => array(
			'name' => __( 'Testimonials', 'hugginbutt-child' ),
			'singular_name' => __( 'Testimonial', 'hugginbutt-child' ),
			'add_new_item' => __( 'Add New Testimonial', 'hugginbutt-child' ),
			'edit_item' => __( 'Edit Testimonial', 'hugginbutt-child' ),
			'not_found' => __( 'No testimonials yet.', 'hugginbutt-child' ),
		),
		'public' => false,
		'show_ui' => true,
		'show_in_menu' => true,
		'rewrite' => false,
		'menu_icon' => 'dashicons-format-quote',
		'menu_position' => 26,
		'supports' => array( 'title', 'editor', 'revisions' ),
	) );
}

add_filter( 'use_block_editor_for_post_type', 'hugginbutt_testimonial_classic_editor', 10, 2 );
function hugginbutt_testimonial_classic_editor( $use_block_editor, $post_type ) {
	return 'hb_testimonial' === $post_type ? false : $use_block_editor;
}

add_filter( 'enter_title_here', 'hugginbutt_testimonial_title_prompt', 10, 2 );
function hugginbutt_testimonial_title_prompt( $title, $post ) {
	return 'hb_testimonial' === $post->post_type ? __( 'Internal label (optional, not shown on the website)', 'hugginbutt-child' ) : $title;
}

add_action( 'add_meta_boxes', 'hugginbutt_testimonial_meta_box' );
function hugginbutt_testimonial_meta_box() {
	add_meta_box( 'hb_testimonial_details', __( 'Customer & Purchased Items', 'hugginbutt-child' ), 'hugginbutt_render_testimonial_fields', 'hb_testimonial', 'normal', 'high' );
}

function hugginbutt_render_testimonial_fields( $post ) {
	wp_nonce_field( 'hugginbutt_save_testimonial', 'hb_testimonial_nonce' );
	$selected = array_map( 'absint', (array) get_post_meta( $post->ID, 'hb_testimonial_products', true ) );
	$products = get_posts( array( 'post_type' => 'product', 'post_status' => 'publish', 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC' ) );
	?>
	<p><?php esc_html_e( 'Write the customer’s review in the main editor. Add a customer name and at least one purchased item before publishing. The optional title is only an internal label. Only published testimonials appear on the homepage.', 'hugginbutt-child' ); ?></p>
	<p><label for="hb_testimonial_author"><strong><?php esc_html_e( 'Customer display name', 'hugginbutt-child' ); ?></strong></label><br>
	<input class="widefat" id="hb_testimonial_author" name="hb_testimonial_author" value="<?php echo esc_attr( get_post_meta( $post->ID, 'hb_testimonial_author', true ) ); ?>" placeholder="Jessica M." /></p>
	<p><strong><?php esc_html_e( 'Purchased products', 'hugginbutt-child' ); ?></strong><br><?php esc_html_e( 'Check each item mentioned in the review. Its current name and photo will appear automatically.', 'hugginbutt-child' ); ?></p>
	<fieldset style="max-height:240px;overflow:auto;border:1px solid #c3c4c7;padding:12px">
		<legend class="screen-reader-text"><?php esc_html_e( 'Purchased products', 'hugginbutt-child' ); ?></legend>
		<?php foreach ( $products as $product ) : ?>
			<label style="display:block;margin-bottom:8px"><input type="checkbox" name="hb_testimonial_products[]" value="<?php echo esc_attr( $product->ID ); ?>" <?php checked( in_array( $product->ID, $selected, true ) ); ?> /> <?php echo esc_html( $product->post_title ); ?></label>
		<?php endforeach; ?>
		<?php if ( ! $products ) : ?><p><?php esc_html_e( 'No published products yet. Use the item name below.', 'hugginbutt-child' ); ?></p><?php endif; ?>
	</fieldset>
	<p><label for="hb_testimonial_item"><strong><?php esc_html_e( 'Other purchased item (optional)', 'hugginbutt-child' ); ?></strong></label><br>
	<input class="widefat" id="hb_testimonial_item" name="hb_testimonial_item" value="<?php echo esc_attr( get_post_meta( $post->ID, 'hb_testimonial_item', true ) ); ?>" placeholder="Custom woodland necklace" />
	<span class="description"><?php esc_html_e( 'For a custom or older item that is no longer in the catalog. Displayed alongside any checked products.', 'hugginbutt-child' ); ?></span></p>
	<p><label for="hb_testimonial_order"><strong><?php esc_html_e( 'Display order', 'hugginbutt-child' ); ?></strong></label><br>
	<input type="number" min="0" step="1" id="hb_testimonial_order" name="hb_testimonial_order" value="<?php echo esc_attr( $post->menu_order ); ?>" />
	<span class="description"><?php esc_html_e( 'Lower numbers appear first. Reviews with the same number show newest first.', 'hugginbutt-child' ); ?></span></p>
	<?php
}

add_action( 'save_post_hb_testimonial', 'hugginbutt_save_testimonial' );
function hugginbutt_save_testimonial( $post_id ) {
	if ( ! isset( $_POST['hb_testimonial_nonce'] ) || ! is_string( $_POST['hb_testimonial_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['hb_testimonial_nonce'] ) ), 'hugginbutt_save_testimonial' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || wp_is_post_revision( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	foreach ( array( 'author', 'item' ) as $field ) {
		$key = 'hb_testimonial_' . $field;
		update_post_meta( $post_id, $key, isset( $_POST[ $key ] ) && is_string( $_POST[ $key ] ) ? sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) : '' );
	}
	$ids = isset( $_POST['hb_testimonial_products'] ) && is_array( $_POST['hb_testimonial_products'] ) ? array_unique( array_map( 'absint', array_filter( $_POST['hb_testimonial_products'], 'is_scalar' ) ) ) : array();
	$ids = array_values( array_filter( $ids, function ( $id ) {
		return 'product' === get_post_type( $id ) && 'publish' === get_post_status( $id );
	} ) );
	update_post_meta( $post_id, 'hb_testimonial_products', $ids );
	$post = get_post( $post_id );
	$complete = trim( wp_strip_all_tags( $post->post_content ) ) && get_post_meta( $post_id, 'hb_testimonial_author', true ) && ( $ids || get_post_meta( $post_id, 'hb_testimonial_item', true ) );
	$update = array( 'ID' => $post_id, 'menu_order' => isset( $_POST['hb_testimonial_order'] ) && is_scalar( $_POST['hb_testimonial_order'] ) ? absint( $_POST['hb_testimonial_order'] ) : 0 );
	if ( ! $complete && in_array( $post->post_status, array( 'publish', 'future' ), true ) ) {
		$update['post_status'] = 'draft';
		add_filter( 'redirect_post_location', 'hugginbutt_testimonial_incomplete_redirect' );
	}
	remove_action( 'save_post_hb_testimonial', 'hugginbutt_save_testimonial' );
	wp_update_post( $update );
	add_action( 'save_post_hb_testimonial', 'hugginbutt_save_testimonial' );
}

function hugginbutt_testimonial_incomplete_redirect( $location ) {
	return add_query_arg( 'hb_testimonial_incomplete', '1', remove_query_arg( 'message', $location ) );
}

add_action( 'admin_notices', 'hugginbutt_testimonial_notice' );
function hugginbutt_testimonial_notice() {
	$screen = get_current_screen();
	if ( $screen && 'hb_testimonial' === $screen->post_type && isset( $_GET['hb_testimonial_incomplete'] ) ) {
		echo '<div class="notice notice-warning"><p>' . esc_html__( 'Saved as a draft. Add review text, customer name, and a purchased product or item name before publishing.', 'hugginbutt-child' ) . '</p></div>';
	}
}

/** Preserve the old hard-coded examples privately, without publishing invented reviews. */
add_action( 'admin_init', 'hugginbutt_preserve_sample_testimonials' );
function hugginbutt_preserve_sample_testimonials() {
	if ( ! current_user_can( 'manage_options' ) || get_option( 'hugginbutt_testimonial_drafts_created' ) ) {
		return;
	}
	$samples = array(
		array( 'Jessica M.', 'The craftsmanship is amazing and the designs are so unique! I always get compliments on it at the faire.' ),
		array( 'Sam R.', 'Every piece feels like it has its own story. My Dragon Eye Ring is my favorite thing I own.' ),
		array( 'Devon P.', 'Fast shipping, gorgeous packaging, and the earrings are even prettier in person.' ),
	);
	foreach ( $samples as $index => $sample ) {
		$slug = 'hb-legacy-testimonial-' . $index;
		if ( get_page_by_path( $slug, OBJECT, 'hb_testimonial' ) ) {
			continue;
		}
		$id = wp_insert_post( array(
			'post_type' => 'hb_testimonial',
			'post_status' => 'draft',
			'post_name' => $slug,
			'post_title' => sprintf( __( 'Sample — %s (review before publishing)', 'hugginbutt-child' ), $sample[0] ),
			'post_content' => $sample[1],
			'meta_input' => array( 'hb_testimonial_author' => $sample[0] ),
		), true );
		if ( is_wp_error( $id ) || ! $id ) {
			return;
		}
	}
	update_option( 'hugginbutt_testimonial_drafts_created', 1 );
}
