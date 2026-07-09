<?php
/**
 * Homepage template. WordPress uses front-page.php for the site root
 * automatically, regardless of the Settings > Reading choice.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

get_template_part( 'template-parts/front-page/hero' );
get_template_part( 'template-parts/front-page/shop-by-category' );
get_template_part( 'template-parts/front-page/featured-products' );
get_template_part( 'template-parts/front-page/about' );
get_template_part( 'template-parts/front-page/events' );
get_template_part( 'template-parts/front-page/testimonial-newsletter' );

get_footer();
