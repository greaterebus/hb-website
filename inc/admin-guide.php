<?php
/**
 * A single wp-admin page ("Site Guide") that walks non-technical site
 * editors through the day-to-day tasks: adding products, marking them
 * featured, adding events, and editing homepage text/images. Everything
 * it links to already exists (WooCommerce, the hb_event post type, the
 * Customizer) - this page is just a friendly front door to those screens.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'admin_menu', 'hugginbutt_register_guide_page' );

function hugginbutt_register_guide_page() {
	add_menu_page(
		__( 'Site Guide', 'hugginbutt-child' ),
		__( 'Site Guide', 'hugginbutt-child' ),
		'edit_posts',
		'hugginbutt-guide',
		'hugginbutt_render_guide_page',
		'dashicons-book-alt',
		3
	);
}

function hugginbutt_render_guide_page() {
	$product_new_url    = admin_url( 'post-new.php?post_type=product' );
	$product_list_url   = admin_url( 'edit.php?post_type=product' );
	$product_cats_url   = admin_url( 'edit-tags.php?taxonomy=product_cat&post_type=product' );
	$event_new_url      = admin_url( 'post-new.php?post_type=hb_event' );
	$event_list_url     = admin_url( 'edit.php?post_type=hb_event' );
	$customizer_url     = admin_url( 'customize.php' );
	$typography_url     = admin_url( 'customize.php?autofocus[section]=hb_typography' );
	?>
	<div class="wrap hugginbutt-guide">
		<h1><?php esc_html_e( 'Site Guide', 'hugginbutt-child' ); ?></h1>
		<p><?php esc_html_e( "Everything you need to keep the shop up to date. Each section links straight to the right screen - you don't need to touch any code.", 'hugginbutt-child' ); ?></p>

		<div class="hugginbutt-guide__card">
			<h2><?php esc_html_e( '1. Add a new product', 'hugginbutt-child' ); ?></h2>
			<ol>
				<li><?php echo wp_kses_post( sprintf( __( 'Go to %s.', 'hugginbutt-child' ), '<a href="' . esc_url( $product_new_url ) . '">' . esc_html__( 'Products &rsaquo; Add New', 'hugginbutt-child' ) . '</a>' ) ); ?></li>
				<li><?php esc_html_e( 'Give it a title and a description.', 'hugginbutt-child' ); ?></li>
				<li><?php esc_html_e( 'Set a price under "Product data" (near the bottom of the page).', 'hugginbutt-child' ); ?></li>
				<li><?php esc_html_e( 'Add a main photo in "Product image" and any extra photos in "Product gallery" (right-hand side).', 'hugginbutt-child' ); ?></li>
				<li><?php esc_html_e( 'Pick a category (Rings, Earrings, Bracelets, etc.) on the right-hand side so it shows up in the correct shop section.', 'hugginbutt-child' ); ?></li>
				<li><?php esc_html_e( 'Click "Publish".', 'hugginbutt-child' ); ?></li>
			</ol>
			<p class="description">
				<?php echo wp_kses_post( sprintf( __( 'Need a new category (not Rings/Earrings/Bracelets/Pendants/Accessories)? Add it under %s.', 'hugginbutt-child' ), '<a href="' . esc_url( $product_cats_url ) . '">' . esc_html__( 'Products &rsaquo; Categories', 'hugginbutt-child' ) . '</a>' ) ); ?>
			</p>
		</div>

		<div class="hugginbutt-guide__card">
			<h2><?php esc_html_e( '2. Choose which products are "Featured" on the homepage', 'hugginbutt-child' ); ?></h2>
			<p><?php esc_html_e( 'The homepage "Featured Products" section shows whichever products you\'ve starred as Featured (up to 6). If fewer than 6 are starred, it fills in the rest with your newest products.', 'hugginbutt-child' ); ?></p>
			<ol>
				<li><?php echo wp_kses_post( sprintf( __( 'Go to %s.', 'hugginbutt-child' ), '<a href="' . esc_url( $product_list_url ) . '">' . esc_html__( 'Products', 'hugginbutt-child' ) . '</a>' ) ); ?></li>
				<li><?php esc_html_e( 'Find the star icon in the row for each product (in the same row as the price and category) and click it to turn Featured on or off - no need to open the product.', 'hugginbutt-child' ); ?></li>
			</ol>
		</div>

		<div class="hugginbutt-guide__card">
			<h2><?php esc_html_e( '3. Add an event (faire, market, pop-up)', 'hugginbutt-child' ); ?></h2>
			<ol>
				<li><?php echo wp_kses_post( sprintf( __( 'Go to %s.', 'hugginbutt-child' ), '<a href="' . esc_url( $event_new_url ) . '">' . esc_html__( 'Events &rsaquo; Add New', 'hugginbutt-child' ) . '</a>' ) ); ?></li>
				<li><?php esc_html_e( 'Give it a title (e.g. "Texas Renaissance Festival").', 'hugginbutt-child' ); ?></li>
				<li><?php esc_html_e( 'Set the Start date, End date, and Location in the "Event Details" box.', 'hugginbutt-child' ); ?></li>
				<li><?php esc_html_e( 'Set a Featured Image (right-hand side) for the event photo - if you skip this, a placeholder image is used instead.', 'hugginbutt-child' ); ?></li>
				<li><?php esc_html_e( 'Optional: write a longer description in the main text box - it appears on the full Events page.', 'hugginbutt-child' ); ?></li>
				<li><?php esc_html_e( 'Click "Publish".', 'hugginbutt-child' ); ?></li>
			</ol>
			<p class="description">
				<?php esc_html_e( 'Events automatically disappear from "Upcoming" and move to "Past" the day after their end date - you don\'t need to delete them.', 'hugginbutt-child' ); ?>
				<?php echo wp_kses_post( sprintf( __( 'See all events (upcoming and past) under %s.', 'hugginbutt-child' ), '<a href="' . esc_url( $event_list_url ) . '">' . esc_html__( 'Events', 'hugginbutt-child' ) . '</a>' ) ); ?>
			</p>
		</div>

		<div class="hugginbutt-guide__card">
			<h2><?php esc_html_e( '4. Edit homepage text, headings, and images', 'hugginbutt-child' ); ?></h2>
			<p><?php echo wp_kses_post( sprintf( __( 'Go to %s. It opens a live preview of the site - click any section on the left (Navbar, Main Shop Image, About, Events, Newsletter, etc.) to edit its text or image, and see the change before saving.', 'hugginbutt-child' ), '<a href="' . esc_url( $customizer_url ) . '">' . esc_html__( 'Appearance &rsaquo; Customize', 'hugginbutt-child' ) . '</a>' ) ); ?></p>
			<p class="description"><?php esc_html_e( 'The "Navbar" section there also lets you change the name, tagline, and logo shown next to it in the header.', 'hugginbutt-child' ); ?></p>
		</div>

		<div class="hugginbutt-guide__card">
			<h2><?php esc_html_e( '5. Add a new page (About Us, FAQ, Contact, etc.)', 'hugginbutt-child' ); ?></h2>
			<ol>
				<li><?php echo wp_kses_post( sprintf( __( 'Go to %s.', 'hugginbutt-child' ), '<a href="' . esc_url( admin_url( 'post-new.php?post_type=page' ) ) . '">' . esc_html__( 'Pages &rsaquo; Add New', 'hugginbutt-child' ) . '</a>' ) ); ?></li>
				<li><?php esc_html_e( 'Give it a title and write the page content.', 'hugginbutt-child' ); ?></li>
				<li><?php esc_html_e( 'Click "Publish".', 'hugginbutt-child' ); ?></li>
			</ol>
			<p class="description"><?php esc_html_e( 'That\'s it - new pages are automatically added to the navbar at the top of the site, no extra setup needed.', 'hugginbutt-child' ); ?></p>
		</div>

		<div class="hugginbutt-guide__card">
			<h2><?php esc_html_e( '6. Change site fonts and text size', 'hugginbutt-child' ); ?></h2>
			<p><?php echo wp_kses_post( sprintf( __( 'Go to %s. Pick a heading font, a body font, and a text size (Small/Medium/Large) - the preview updates live before you save.', 'hugginbutt-child' ), '<a href="' . esc_url( $typography_url ) . '">' . esc_html__( 'Appearance &rsaquo; Customize &rsaquo; Typography', 'hugginbutt-child' ) . '</a>' ) ); ?></p>
			<p class="description"><?php esc_html_e( "Use this instead of any other font/typography settings you may see elsewhere in the Customizer - this is the one that actually controls the site.", 'hugginbutt-child' ); ?></p>
		</div>
	</div>
	<?php
}

add_action( 'admin_head-toplevel_page_hugginbutt-guide', 'hugginbutt_guide_page_styles' );

function hugginbutt_guide_page_styles() {
	?>
	<style>
		.hugginbutt-guide__card {
			background: #fff;
			border: 1px solid #dcdcde;
			border-radius: 4px;
			padding: 1.25rem 1.5rem;
			margin: 1.25rem 0;
			max-width: 720px;
		}
		.hugginbutt-guide__card h2 {
			margin-top: 0;
		}
		.hugginbutt-guide__card ol {
			margin-left: 1.25rem;
			list-style: decimal;
		}
		.hugginbutt-guide__card li {
			margin-bottom: 0.4rem;
		}
	</style>
	<?php
}
