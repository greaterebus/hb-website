<?php
/**
 * Site-wide typography: heading/body font pairing + text-size scale.
 *
 * Kadence ships its own Typography Customizer panel (Base Font, Heading
 * Font, per-H1-H6 sizing), but hugginbutt.css unconditionally overrides the
 * --global-body-font-family/--global-heading-font-family variables it
 * writes to, and reads its own separate --hb-fs-* scale for sizing instead
 * of Kadence's per-heading values - so that panel has no visible effect on
 * this site. This file adds a small HugginButt-owned replacement that
 * actually drives the tokens the site uses, following the same
 * data-driven Customizer pattern as inc/customizer.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Curated font pairings. 'google' is the Google Fonts css2 API family
 * fragment (only the weights the theme actually sets: 600/700 for
 * headings, 400/500/600 + italic 400 for body). 'roles' controls which
 * of the Heading Font / Body Font dropdowns a font appears in.
 */
function hugginbutt_typography_font_catalog() {
	return array(
		'cinzel'      => array(
			'label'  => __( 'Cinzel', 'hugginbutt-child' ),
			'stack'  => "'Cinzel', Georgia, serif",
			'google' => 'Cinzel:wght@600;700',
			'roles'  => array( 'heading', 'body' ),
		),
		'cormorant'   => array(
			'label'  => __( 'Cormorant Garamond', 'hugginbutt-child' ),
			'stack'  => "'Cormorant Garamond', Georgia, serif",
			'google' => 'Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400',
			'roles'  => array( 'heading', 'body' ),
		),
		'playfair'    => array(
			'label'  => __( 'Playfair Display', 'hugginbutt-child' ),
			'stack'  => "'Playfair Display', Georgia, serif",
			'google' => 'Playfair+Display:wght@600;700',
			'roles'  => array( 'heading' ),
		),
		'marcellus'   => array(
			'label'  => __( 'Marcellus', 'hugginbutt-child' ),
			'stack'  => "'Marcellus', Georgia, serif",
			'google' => 'Marcellus',
			'roles'  => array( 'heading' ),
		),
		'im-fell'     => array(
			'label'  => __( 'IM Fell English SC', 'hugginbutt-child' ),
			'stack'  => "'IM Fell English SC', Georgia, serif",
			'google' => 'IM+Fell+English+SC',
			'roles'  => array( 'heading' ),
		),
		'eb-garamond' => array(
			'label'  => __( 'EB Garamond', 'hugginbutt-child' ),
			'stack'  => "'EB Garamond', Georgia, serif",
			'google' => 'EB+Garamond:ital,wght@0,400;0,500;0,600;1,400',
			'roles'  => array( 'body' ),
		),
		'lora'        => array(
			'label'  => __( 'Lora', 'hugginbutt-child' ),
			'stack'  => "'Lora', Georgia, serif",
			'google' => 'Lora:ital,wght@0,400;0,500;0,600;1,400',
			'roles'  => array( 'body' ),
		),
	);
}

/**
 * Text-size presets. 'multiplier' feeds --hb-fs-scale, which every
 * --hb-fs-* token in hugginbutt.css is wrapped in a calc() against - so
 * changing it rescales every font-size on the site proportionally.
 */
function hugginbutt_typography_size_scale() {
	return array(
		'small'  => array(
			'label'      => __( 'Small', 'hugginbutt-child' ),
			'multiplier' => '0.92',
		),
		'medium' => array(
			'label'      => __( 'Medium (default)', 'hugginbutt-child' ),
			'multiplier' => '1',
		),
		'large'  => array(
			'label'      => __( 'Large', 'hugginbutt-child' ),
			'multiplier' => '1.1',
		),
	);
}

/**
 * Catalog entries valid for a given role, as an id => label map (ready to
 * hand to a Customizer select control's 'choices').
 *
 * @param string $role 'heading' or 'body'.
 */
function hugginbutt_typography_fonts_for_role( $role ) {
	$fonts = array();
	foreach ( hugginbutt_typography_font_catalog() as $key => $font ) {
		if ( in_array( $role, $font['roles'], true ) ) {
			$fonts[ $key ] = $font['label'];
		}
	}
	return $fonts;
}

/**
 * Returns $value if it's a key in $choices, otherwise $default. Used both
 * as a Customizer sanitize_callback and when reading saved theme_mods, so
 * a font/size removed from the catalog later can't leave a stale,
 * unrenderable value in the database.
 */
function hugginbutt_typography_sanitize_choice( $value, $choices, $default ) {
	return array_key_exists( $value, $choices ) ? $value : $default;
}

add_action( 'customize_register', 'hugginbutt_typography_customize_register' );

function hugginbutt_typography_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'hb_typography',
		array(
			'title'       => __( 'Typography', 'hugginbutt-child' ),
			'priority'    => 31,
			'description' => __( "Site-wide heading font, body font, and text size.", 'hugginbutt-child' ),
		)
	);

	$heading_fonts = hugginbutt_typography_fonts_for_role( 'heading' );
	$body_fonts    = hugginbutt_typography_fonts_for_role( 'body' );
	$size_choices  = wp_list_pluck( hugginbutt_typography_size_scale(), 'label' );

	$wp_customize->add_setting(
		'hb_typography_heading_font',
		array(
			'default'           => 'cinzel',
			'sanitize_callback' => function ( $value ) use ( $heading_fonts ) {
				return hugginbutt_typography_sanitize_choice( $value, $heading_fonts, 'cinzel' );
			},
		)
	);
	$wp_customize->add_control(
		'hb_typography_heading_font',
		array(
			'label'   => __( 'Heading Font', 'hugginbutt-child' ),
			'section' => 'hb_typography',
			'type'    => 'select',
			'choices' => $heading_fonts,
		)
	);

	$wp_customize->add_setting(
		'hb_typography_body_font',
		array(
			'default'           => 'cinzel',
			'sanitize_callback' => function ( $value ) use ( $body_fonts ) {
				return hugginbutt_typography_sanitize_choice( $value, $body_fonts, 'cinzel' );
			},
		)
	);
	$wp_customize->add_control(
		'hb_typography_body_font',
		array(
			'label'   => __( 'Body Font', 'hugginbutt-child' ),
			'section' => 'hb_typography',
			'type'    => 'select',
			'choices' => $body_fonts,
		)
	);

	$wp_customize->add_setting(
		'hb_typography_text_size',
		array(
			'default'           => 'medium',
			'sanitize_callback' => function ( $value ) use ( $size_choices ) {
				return hugginbutt_typography_sanitize_choice( $value, $size_choices, 'medium' );
			},
		)
	);
	$wp_customize->add_control(
		'hb_typography_text_size',
		array(
			'label'   => __( 'Text Size', 'hugginbutt-child' ),
			'section' => 'hb_typography',
			'type'    => 'select',
			'choices' => $size_choices,
		)
	);
}

/**
 * Saved typography choices, each already validated against the current
 * catalog/scale (falls back to the default key if a saved value is stale).
 */
function hugginbutt_typography_current_choices() {
	$heading_fonts = hugginbutt_typography_fonts_for_role( 'heading' );
	$body_fonts    = hugginbutt_typography_fonts_for_role( 'body' );
	$size_choices  = wp_list_pluck( hugginbutt_typography_size_scale(), 'label' );

	return array(
		'heading_key' => hugginbutt_typography_sanitize_choice( get_theme_mod( 'hb_typography_heading_font', 'cinzel' ), $heading_fonts, 'cinzel' ),
		'body_key'    => hugginbutt_typography_sanitize_choice( get_theme_mod( 'hb_typography_body_font', 'cinzel' ), $body_fonts, 'cinzel' ),
		'size_key'    => hugginbutt_typography_sanitize_choice( get_theme_mod( 'hb_typography_text_size', 'medium' ), $size_choices, 'medium' ),
	);
}

/**
 * Builds the Google Fonts css2 URL for the two currently-selected fonts
 * (deduped if the same font is chosen for both roles), so the site only
 * ever fetches fonts it's actually going to render.
 */
function hugginbutt_typography_google_fonts_url() {
	$catalog = hugginbutt_typography_font_catalog();
	$choices = hugginbutt_typography_current_choices();

	$families = array();
	foreach ( array( $choices['heading_key'], $choices['body_key'] ) as $key ) {
		if ( isset( $catalog[ $key ] ) ) {
			$families[ $catalog[ $key ]['google'] ] = true;
		}
	}

	$query = array();
	foreach ( array_keys( $families ) as $family ) {
		$query[] = 'family=' . $family;
	}
	$query[] = 'display=swap';

	return 'https://fonts.googleapis.com/css2?' . implode( '&', $query );
}

add_action( 'wp_enqueue_scripts', 'hugginbutt_typography_inline_css', 21 );

/**
 * Overrides --hb-font-display/--hb-font-body/--hb-fs-scale at :root with
 * the current Customizer choices, attached as inline CSS right after the
 * main stylesheet - same "override the token at :root" trick already used
 * for the color palette. Priority 21 so 'hugginbutt-style' (registered at
 * priority 20 in inc/enqueue.php) already exists to attach to.
 */
function hugginbutt_typography_inline_css() {
	if ( ! wp_style_is( 'hugginbutt-style', 'registered' ) ) {
		return;
	}

	$catalog = hugginbutt_typography_font_catalog();
	$sizes   = hugginbutt_typography_size_scale();
	$choices = hugginbutt_typography_current_choices();

	$css = sprintf(
		':root{--hb-font-display:%s;--hb-font-body:%s;--hb-fs-scale:%s;}',
		$catalog[ $choices['heading_key'] ]['stack'],
		$catalog[ $choices['body_key'] ]['stack'],
		$sizes[ $choices['size_key'] ]['multiplier']
	);

	wp_add_inline_style( 'hugginbutt-style', $css );
}
