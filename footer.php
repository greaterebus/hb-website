<?php
/**
 * Full footer replacement: closes the <main> opened in header.php, then
 * renders a centered mascot illustration + the bottom bar (copyright,
 * legal links).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
	</main>

	<footer id="colophon" class="hb-footer">
		<div class="hb-footer__top">
			<div class="hb-footer__mascot">
				<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/generated/footer-mascot.webp' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />
			</div>

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
