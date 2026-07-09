<?php
/**
 * Title: Default Coming Soon
 * Slug: woocommerce/page-coming-soon-default
 * Categories: WooCommerce
 * Template Types: coming-soon
 * Inserter: false
 *
 * HugginButt override of WooCommerce's bundled "coming soon" pattern.
 * Registered with the identical slug so it replaces the plugin's version
 * (theme /patterns/ files register after the plugin's, so same-slug
 * patterns here win) — keeps this update-safe instead of editing the
 * plugin file directly. Styling matches the green fabric background and
 * cream/rust palette used on the homepage (.hb-body in hugginbutt.css).
 */

use Automattic\WooCommerce\Blocks\Templates\ComingSoonTemplate;

$fonts               = ComingSoonTemplate::get_font_families();
$heading_font_family = $fonts['heading'];
$body_font_family    = $fonts['body'];

?>

<!-- wp:woocommerce/coming-soon {"comingSoonPatternId":"page-coming-soon-default","className":"woocommerce-coming-soon-default hb-coming-soon","style":{"color":{"background":"#303722","text":"#e8d8b4"},"elements":{"link":{"color":{"text":"#e8d8b4"}}}}} -->
<div class="wp-block-woocommerce-coming-soon woocommerce-coming-soon-default hb-coming-soon has-text-color has-background has-link-color" style="color:#e8d8b4;background-color:#303722"><!-- wp:cover {"customOverlayColor":"transparent","isUserOverlayColor":true,"className":"coming-soon-is-vertically-aligned-center coming-soon-cover hb-coming-soon-cover","style":{"spacing":{"padding":{"top":"0px","bottom":"0px","left":"24px","right":"24px"}},"color":{"text":"inherit"},"elements":{"link":{"color":{"text":"inherit"}}}},"layout":{"type":"constrained","wideSize":"1280px"}} -->
<div class="wp-block-cover coming-soon-is-vertically-aligned-center coming-soon-cover hb-coming-soon-cover has-text-color has-link-color" style="color:inherit;padding-top:0px;padding-right:24px;padding-bottom:0px;padding-left:24px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-100 has-background-dim" style="background-color:transparent"></span><div class="wp-block-cover__inner-container hb-coming-soon-center"><!-- wp:image {"className":"hb-coming-soon-wordmark","sizeSlug":"large"} -->
<figure class="wp-block-image size-large hb-coming-soon-wordmark"><img src="<?php echo esc_url( HUGGINBUTT_URI . '/assets/images/generated/hugginbutt-logo-wordmark-transparent.png' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"/></figure>
<!-- /wp:image --><!-- wp:image {"className":"hb-coming-soon-logo","sizeSlug":"large"} -->
<figure class="wp-block-image size-large hb-coming-soon-logo"><img src="<?php echo esc_url( HUGGINBUTT_URI . '/assets/images/generated/coming-soon-work-in-progress.png' ); ?>" alt="<?php echo esc_attr__( 'Work in progress', 'hugginbutt' ); ?>"/></figure>
<!-- /wp:image --></div></div>
<!-- /wp:cover -->
</div>
<!-- /wp:woocommerce/coming-soon -->

<?php $hb_coming_soon_events = hugginbutt_get_events( 'upcoming', 8 ); ?>
<section class="hb-coming-soon-events">
	<div class="hb-coming-soon-events__inner">
		<img class="hb-coming-soon-events__heading-image" src="<?php echo esc_url( HUGGINBUTT_URI . '/assets/images/generated/coming-soon-upcoming-events.png' ); ?>" alt="<?php esc_attr_e( 'Upcoming Events', 'hugginbutt' ); ?>"/>

		<?php if ( $hb_coming_soon_events ) : ?>
			<div class="hb-events__grid">
				<?php foreach ( $hb_coming_soon_events as $event ) : ?>
					<div class="hb-event-card hb-paper">
						<span class="hb-event-card__media">
							<?php if ( ! empty( $event['image_url'] ) ) : ?>
								<img src="<?php echo esc_url( $event['image_url'] ); ?>" alt="<?php echo esc_attr( $event['name'] ); ?>" />
							<?php else : ?>
								<?php hugginbutt_placeholder_image( $event['image'], $event['name'] ); ?>
							<?php endif; ?>
							<span class="hb-event-card__date"><?php echo esc_html( strtoupper( $event['date_range'] ) ); ?></span>
						</span>
						<h3 class="hb-event-card__name"><?php echo esc_html( $event['name'] ); ?><?php if ( ! empty( $event['date_long'] ) ) : ?> <span class="hb-event-card__date-long">- <?php echo esc_html( $event['date_long'] ); ?></span><?php endif; ?></h3>
						<p class="hb-event-card__location"><?php echo esc_html( $event['location'] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
			<p class="hb-coming-soon-events__empty"><?php esc_html_e( 'No upcoming events right now — check back soon!', 'hugginbutt' ); ?></p>
		<?php endif; ?>
	</div>
</section>
