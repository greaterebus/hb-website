<?php
/**
 * "HugginButt Homepage Content" customizer panel.
 *
 * Holds scalar/single-value copy only (headings, paragraphs, single images,
 * URLs). Repeating list content (events, testimonials, about-us features)
 * lives in inc/theme-content.php instead, since the Customizer has no
 * built-in repeater control.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'customize_register', 'hugginbutt_customize_register' );

/**
 * Declarative list of every field in the panel: id, section, control type,
 * label and default. Keeping this data-driven avoids ~30 near-identical
 * add_setting()/add_control() calls below.
 */
function hugginbutt_customizer_fields() {
	return array(
		// Announcement bar.
		array(
			'id'      => 'hb_announcement_text',
			'section' => 'hb_announcement',
			'type'    => 'text',
			'label'   => __( 'Announcement text', 'hugginbutt-child' ),
			'default' => 'Handcrafted fantasy jewelry • Faire-ready treasures • Made with mischief',
		),

		// Hero.
		array(
			'id'      => 'hb_hero_heading',
			'section' => 'hb_hero',
			'type'    => 'text',
			'label'   => __( 'Hero heading', 'hugginbutt-child' ),
			'default' => 'Handcrafted Fantasy Jewelry',
		),
		array(
			'id'      => 'hb_hero_subheading',
			'section' => 'hb_hero',
			'type'    => 'textarea',
			'label'   => __( 'Hero subheading', 'hugginbutt-child' ),
			'default' => 'Inspired by nature, lore, and a little bit of mischief. Handcrafted with care for ren faire days and everyday adventures.',
		),
		array(
			'id'      => 'hb_hero_button_text',
			'section' => 'hb_hero',
			'type'    => 'text',
			'label'   => __( 'Hero button text', 'hugginbutt-child' ),
			'default' => 'Shop Now',
		),
		array(
			'id'      => 'hb_hero_image',
			'section' => 'hb_hero',
			'type'    => 'image',
			'label'   => __( 'Hero photo', 'hugginbutt-child' ),
			'default' => '',
		),

		// Shop by category.
		array(
			'id'      => 'hb_category_heading',
			'section' => 'hb_category',
			'type'    => 'text',
			'label'   => __( 'Section heading', 'hugginbutt-child' ),
			'default' => 'Shop By Category',
		),

		// Featured products.
		array(
			'id'      => 'hb_featured_heading',
			'section' => 'hb_featured',
			'type'    => 'text',
			'label'   => __( 'Section heading', 'hugginbutt-child' ),
			'default' => 'Featured Products',
		),

		// About us.
		array(
			'id'      => 'hb_about_heading',
			'section' => 'hb_about',
			'type'    => 'text',
			'label'   => __( 'Heading', 'hugginbutt-child' ),
			'default' => 'A Little About Us',
		),
		array(
			'id'      => 'hb_about_paragraph_1',
			'section' => 'hb_about',
			'type'    => 'textarea',
			'label'   => __( 'Paragraph 1', 'hugginbutt-child' ),
			'default' => 'HugginButt was born at the faire and forged in good times, loud music, and a love for all things fantasy.',
		),
		array(
			'id'      => 'hb_about_paragraph_2',
			'section' => 'hb_about',
			'type'    => 'textarea',
			'label'   => __( 'Paragraph 2', 'hugginbutt-child' ),
			'default' => 'We handcraft every piece with care (and a bit of attitude). Thanks for supporting our small, weird business!',
		),
		array(
			'id'      => 'hb_about_image',
			'section' => 'hb_about',
			'type'    => 'image',
			'label'   => __( 'Illustration / photo', 'hugginbutt-child' ),
			'default' => '',
		),

		// Events.
		array(
			'id'      => 'hb_events_heading',
			'section' => 'hb_events',
			'type'    => 'text',
			'label'   => __( 'Heading', 'hugginbutt-child' ),
			'default' => 'Upcoming Events',
		),
		array(
			'id'      => 'hb_events_button_text',
			'section' => 'hb_events',
			'type'    => 'text',
			'label'   => __( 'Button text', 'hugginbutt-child' ),
			'default' => 'View All Events',
		),
		array(
			'id'      => 'hb_events_button_url',
			'section' => 'hb_events',
			'type'    => 'url',
			'label'   => __( 'Button link', 'hugginbutt-child' ),
			'default' => '',
		),

		// Testimonials.
		array(
			'id'      => 'hb_testimonial_heading',
			'section' => 'hb_testimonial',
			'type'    => 'text',
			'label'   => __( 'Heading', 'hugginbutt-child' ),
			'default' => 'What Our Customers Say',
		),
		array(
			'id'      => 'hb_testimonial_image',
			'section' => 'hb_testimonial',
			'type'    => 'image',
			'label'   => __( 'Middle illustration', 'hugginbutt-child' ),
			'default' => '',
		),

		// Newsletter.
		array(
			'id'      => 'hb_newsletter_heading',
			'section' => 'hb_newsletter',
			'type'    => 'text',
			'label'   => __( 'Heading', 'hugginbutt-child' ),
			'default' => 'Join Our Mischief',
		),
		array(
			'id'      => 'hb_newsletter_text',
			'section' => 'hb_newsletter',
			'type'    => 'textarea',
			'label'   => __( 'Blurb', 'hugginbutt-child' ),
			'default' => 'Get first dibs on new pieces, faire dates, exclusive deals, and behind-the-scenes fun.',
		),
		array(
			'id'      => 'hb_newsletter_button_text',
			'section' => 'hb_newsletter',
			'type'    => 'text',
			'label'   => __( 'Button text', 'hugginbutt-child' ),
			'default' => 'Join the Adventure',
		),
		array(
			'id'      => 'hb_newsletter_form_action',
			'section' => 'hb_newsletter',
			'type'    => 'url',
			'label'   => __( 'Form action URL (e.g. Mailchimp/Klaviyo signup URL)', 'hugginbutt-child' ),
			'default' => '',
		),

		// Social + contact (used in the footer).
		array(
			'id'      => 'hb_social_facebook',
			'section' => 'hb_social',
			'type'    => 'url',
			'label'   => __( 'Facebook URL', 'hugginbutt-child' ),
			'default' => '',
		),
		array(
			'id'      => 'hb_social_instagram',
			'section' => 'hb_social',
			'type'    => 'url',
			'label'   => __( 'Instagram URL', 'hugginbutt-child' ),
			'default' => '',
		),
		array(
			'id'      => 'hb_social_pinterest',
			'section' => 'hb_social',
			'type'    => 'url',
			'label'   => __( 'Pinterest URL', 'hugginbutt-child' ),
			'default' => '',
		),
		array(
			'id'      => 'hb_social_tiktok',
			'section' => 'hb_social',
			'type'    => 'url',
			'label'   => __( 'TikTok URL', 'hugginbutt-child' ),
			'default' => '',
		),
		array(
			'id'      => 'hb_social_email',
			'section' => 'hb_social',
			'type'    => 'text',
			'label'   => __( 'Contact email', 'hugginbutt-child' ),
			'default' => 'hello@hugginbutt.com',
		),
		array(
			'id'      => 'hb_footer_blurb',
			'section' => 'hb_social',
			'type'    => 'textarea',
			'label'   => __( 'Footer brand blurb', 'hugginbutt-child' ),
			'default' => 'Handcrafted fantasy jewelry for misfits, dreamers, and adventurers.',
		),
		array(
			'id'      => 'hb_terms_url',
			'section' => 'hb_social',
			'type'    => 'url',
			'label'   => __( 'Terms of Service URL', 'hugginbutt-child' ),
			'default' => '',
		),
	);
}

function hugginbutt_customize_register( $wp_customize ) {
	$wp_customize->add_panel(
		'hugginbutt_content',
		array(
			'title'    => __( 'HugginButt Homepage Content', 'hugginbutt-child' ),
			'priority' => 10,
		)
	);

	$sections = array(
		'hb_announcement' => __( 'Announcement Bar', 'hugginbutt-child' ),
		'hb_hero'         => __( 'Hero', 'hugginbutt-child' ),
		'hb_category'     => __( 'Shop By Category', 'hugginbutt-child' ),
		'hb_featured'     => __( 'Featured Products', 'hugginbutt-child' ),
		'hb_about'        => __( 'About Us', 'hugginbutt-child' ),
		'hb_events'       => __( 'Events', 'hugginbutt-child' ),
		'hb_testimonial'  => __( 'Testimonials', 'hugginbutt-child' ),
		'hb_newsletter'   => __( 'Newsletter', 'hugginbutt-child' ),
		'hb_social'       => __( 'Social, Contact & Footer', 'hugginbutt-child' ),
	);

	foreach ( $sections as $id => $label ) {
		$wp_customize->add_section(
			$id,
			array(
				'title' => $label,
				'panel' => 'hugginbutt_content',
			)
		);
	}

	foreach ( hugginbutt_customizer_fields() as $field ) {
		$sanitize = 'sanitize_text_field';
		if ( 'textarea' === $field['type'] ) {
			$sanitize = 'sanitize_textarea_field';
		} elseif ( in_array( $field['type'], array( 'url', 'image' ), true ) ) {
			$sanitize = 'esc_url_raw';
		}

		$wp_customize->add_setting(
			$field['id'],
			array(
				'default'           => $field['default'],
				'sanitize_callback' => $sanitize,
			)
		);

		if ( 'textarea' === $field['type'] ) {
			$wp_customize->add_control(
				$field['id'],
				array(
					'label'   => $field['label'],
					'section' => $field['section'],
					'type'    => 'textarea',
				)
			);
		} elseif ( 'image' === $field['type'] ) {
			$wp_customize->add_control(
				new \WP_Customize_Image_Control(
					$wp_customize,
					$field['id'],
					array(
						'label'   => $field['label'],
						'section' => $field['section'],
					)
				)
			);
		} else {
			$wp_customize->add_control(
				$field['id'],
				array(
					'label'   => $field['label'],
					'section' => $field['section'],
					'type'    => 'url' === $field['type'] ? 'url' : 'text',
				)
			);
		}
	}
}

/**
 * Small helper so templates don't repeat get_theme_mod() + default lookups.
 *
 * @param string $id Customizer setting id (see hugginbutt_customizer_fields()).
 */
function hugginbutt_get_content( $id ) {
	static $defaults = null;

	if ( null === $defaults ) {
		$defaults = array();
		foreach ( hugginbutt_customizer_fields() as $field ) {
			$defaults[ $field['id'] ] = $field['default'];
		}
	}

	$default = isset( $defaults[ $id ] ) ? $defaults[ $id ] : '';

	return get_theme_mod( $id, $default );
}
