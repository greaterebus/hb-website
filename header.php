<?php
/**
 * Full header replacement (announcement bar, brand, primary nav, icon
 * cluster). Bypasses Kadence's Customizer-driven header builder so the
 * design in the mockup is locked into markup/CSS rather than needing
 * Customizer configuration.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'hb-body' ); ?>>
<?php wp_body_open(); ?>

<a class="hb-skip-link screen-reader-text" href="#hb-main"><?php esc_html_e( 'Skip to content', 'hugginbutt-child' ); ?></a>

<div id="page" class="hb-site">

	<div class="hb-announcement">
		<div class="hb-announcement__inner">
			<span class="hb-announcement__ornament" aria-hidden="true">✦</span>
			<p><?php echo esc_html( hugginbutt_get_content( 'hb_announcement_text' ) ); ?></p>
			<span class="hb-announcement__ornament" aria-hidden="true">✦</span>
		</div>
	</div>

	<header id="masthead" class="hb-header">
		<div class="hb-header__inner">

			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hb-header__brand">
				<span class="hb-header__logo">
					<?php if ( has_custom_logo() ) : ?>
						<?php echo wp_get_attachment_image( get_theme_mod( 'custom_logo' ), 'full', false, array( 'alt' => get_bloginfo( 'name' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- WP-escaped image markup. ?>
					<?php else : ?>
						<?php hugginbutt_placeholder_image( 'logo', get_bloginfo( 'name' ) ); ?>
					<?php endif; ?>
				</span>
				<span class="hb-header__wordmark-group">
					<span class="hb-header__wordmark"><?php bloginfo( 'name' ); ?></span>
					<?php if ( get_bloginfo( 'description' ) ) : ?>
						<span class="hb-header__tagline"><?php bloginfo( 'description' ); ?></span>
					<?php endif; ?>
				</span>
			</a>

			<button type="button" class="hb-header__menu-toggle" aria-expanded="false" aria-controls="hb-primary-nav">
				<?php hugginbutt_the_icon( 'menu', 'hb-header__menu-icon-open' ); ?>
				<?php hugginbutt_the_icon( 'close', 'hb-header__menu-icon-close' ); ?>
				<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'hugginbutt-child' ); ?></span>
			</button>

			<nav id="hb-primary-nav" class="hb-header__nav" aria-label="<?php esc_attr_e( 'Primary', 'hugginbutt-child' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'hb-nav-menu',
						'fallback_cb'    => false,
						'depth'          => 2,
					)
				);
				?>
			</nav>

			<div class="hb-header__icons">
				<a href="<?php echo esc_url( home_url( '/?s=' ) ); ?>" class="hb-header__icon-link hb-header__search" aria-label="<?php esc_attr_e( 'Search', 'hugginbutt-child' ); ?>">
					<?php hugginbutt_the_icon( 'search' ); ?>
				</a>
				<?php if ( class_exists( 'WooCommerce' ) ) : ?>
					<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="hb-header__icon-link" aria-label="<?php esc_attr_e( 'Account', 'hugginbutt-child' ); ?>">
						<?php hugginbutt_the_icon( 'account' ); ?>
					</a>
					<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="hb-header__icon-link hb-header__cart" aria-label="<?php esc_attr_e( 'Cart', 'hugginbutt-child' ); ?>">
						<?php hugginbutt_the_icon( 'cart' ); ?>
						<span class="hugginbutt-cart-count"><?php echo intval( WC()->cart ? WC()->cart->get_cart_contents_count() : 0 ); ?></span>
					</a>
				<?php endif; ?>
			</div>

		</div>
	</header>

	<main id="hb-main" class="hb-main">
