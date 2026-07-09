<?php
/**
 * Full footer replacement: closes the <main> opened in header.php, then
 * renders the 5-column footer + bottom bar.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
	</main>

	<footer id="colophon" class="hb-footer">
		<div class="hb-footer__inner">

			<div class="hb-footer__col hb-footer__col--brand">
				<span class="hb-footer__logo">
					<?php if ( has_custom_logo() ) : ?>
						<?php the_custom_logo(); ?>
					<?php else : ?>
						<?php hugginbutt_placeholder_image( 'logo', get_bloginfo( 'name' ) ); ?>
					<?php endif; ?>
					<span class="hb-footer__wordmark"><?php bloginfo( 'name' ); ?></span>
				</span>
				<p class="hb-footer__blurb"><?php echo esc_html( hugginbutt_get_content( 'hb_footer_blurb' ) ); ?></p>
				<div class="hb-footer__social">
					<?php
					$social = array(
						'facebook'  => hugginbutt_get_content( 'hb_social_facebook' ),
						'instagram' => hugginbutt_get_content( 'hb_social_instagram' ),
						'pinterest' => hugginbutt_get_content( 'hb_social_pinterest' ),
						'tiktok'    => hugginbutt_get_content( 'hb_social_tiktok' ),
					);
					$email = hugginbutt_get_content( 'hb_social_email' );
					foreach ( $social as $network => $url ) :
						if ( '' === $url ) {
							continue;
						}
						?>
						<a href="<?php echo esc_url( $url ); ?>" class="hb-footer__social-link" aria-label="<?php echo esc_attr( ucfirst( $network ) ); ?>" target="_blank" rel="noopener noreferrer">
							<?php hugginbutt_the_icon( 'social-' . $network ); ?>
						</a>
					<?php endforeach; ?>
					<?php if ( $email ) : ?>
						<a href="mailto:<?php echo esc_attr( antispambot( $email ) ); ?>" class="hb-footer__social-link" aria-label="<?php esc_attr_e( 'Email', 'hugginbutt-child' ); ?>">
							<?php hugginbutt_the_icon( 'social-email' ); ?>
						</a>
					<?php endif; ?>
				</div>
			</div>

			<div class="hb-footer__col">
				<h3 class="hb-footer__heading"><?php esc_html_e( 'Shop', 'hugginbutt-child' ); ?></h3>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer-shop',
						'container'      => false,
						'menu_class'     => 'hb-footer__menu',
						'fallback_cb'    => false,
						'depth'          => 1,
					)
				);
				?>
			</div>

			<div class="hb-footer__col">
				<h3 class="hb-footer__heading"><?php esc_html_e( 'Collections', 'hugginbutt-child' ); ?></h3>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer-collections',
						'container'      => false,
						'menu_class'     => 'hb-footer__menu',
						'fallback_cb'    => false,
						'depth'          => 1,
					)
				);
				?>
			</div>

			<div class="hb-footer__col">
				<h3 class="hb-footer__heading"><?php esc_html_e( 'Customer Care', 'hugginbutt-child' ); ?></h3>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer-care',
						'container'      => false,
						'menu_class'     => 'hb-footer__menu',
						'fallback_cb'    => false,
						'depth'          => 1,
					)
				);
				?>
			</div>

			<div class="hb-footer__col">
				<h3 class="hb-footer__heading"><?php esc_html_e( 'Stay In Touch', 'hugginbutt-child' ); ?></h3>
				<p><?php esc_html_e( 'Questions? Email us anytime.', 'hugginbutt-child' ); ?></p>
				<?php if ( $email ) : ?>
					<p><a href="mailto:<?php echo esc_attr( antispambot( $email ) ); ?>"><?php echo esc_html( $email ); ?></a></p>
				<?php endif; ?>
				<p class="hb-footer__handmade"><?php esc_html_e( 'Proudly handmade in the USA and shipped worldwide.', 'hugginbutt-child' ); ?></p>
			</div>

		</div>

		<div class="hb-footer__bottom">
			<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved.', 'hugginbutt-child' ); ?></p>
			<nav class="hb-footer__legal" aria-label="<?php esc_attr_e( 'Legal', 'hugginbutt-child' ); ?>">
				<?php if ( get_privacy_policy_url() ) : ?>
					<a href="<?php echo esc_url( get_privacy_policy_url() ); ?>"><?php esc_html_e( 'Privacy Policy', 'hugginbutt-child' ); ?></a>
				<?php endif; ?>
				<?php if ( hugginbutt_get_content( 'hb_terms_url' ) ) : ?>
					<a href="<?php echo esc_url( hugginbutt_get_content( 'hb_terms_url' ) ); ?>"><?php esc_html_e( 'Terms of Service', 'hugginbutt-child' ); ?></a>
				<?php endif; ?>
			</nav>
		</div>
	</footer>

</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
