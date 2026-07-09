<?php
/**
 * HugginButt child theme bootstrap.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'HUGGINBUTT_VERSION', '1.0.2' );
define( 'HUGGINBUTT_DIR', get_stylesheet_directory() );
define( 'HUGGINBUTT_URI', get_stylesheet_directory_uri() );

require HUGGINBUTT_DIR . '/inc/setup.php';
require HUGGINBUTT_DIR . '/inc/enqueue.php';
require HUGGINBUTT_DIR . '/inc/icons.php';
require HUGGINBUTT_DIR . '/inc/customizer.php';
require HUGGINBUTT_DIR . '/inc/theme-content.php';
require HUGGINBUTT_DIR . '/inc/events-cpt.php';
require HUGGINBUTT_DIR . '/inc/woocommerce.php';
require HUGGINBUTT_DIR . '/inc/template-tags.php';
require HUGGINBUTT_DIR . '/inc/shop-sidebar.php';
