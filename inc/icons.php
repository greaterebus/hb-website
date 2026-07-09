<?php
/**
 * Inline SVG icon set. currentColor-based so icons pick up surrounding CSS color.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns inline SVG markup for a fixed icon set.
 *
 * @param string $name Icon key.
 * @return string SVG markup, or empty string if the key is unknown.
 */
function hugginbutt_icon( $name ) {
	$icons = array(
		'search'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="10.5" cy="10.5" r="6.5"/><line x1="20" y1="20" x2="15.3" y2="15.3"/></svg>',
		'account'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="4"/><path d="M4 20c1.6-4 5-6 8-6s6.4 2 8 6"/></svg>',
		'cart'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 4h2l2.4 12.2a2 2 0 0 0 2 1.6h7.6a2 2 0 0 0 2-1.6L21 8H6"/><circle cx="9.5" cy="20.5" r="1.4" fill="currentColor" stroke="none"/><circle cx="17.5" cy="20.5" r="1.4" fill="currentColor" stroke="none"/></svg>',
		'menu'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>',
		'close'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><line x1="5" y1="5" x2="19" y2="19"/><line x1="19" y1="5" x2="5" y2="19"/></svg>',
		'chevron'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6,9 12,15 18,9"/></svg>',
		'arrow'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="4" y1="12" x2="19" y2="12"/><polyline points="13,6 19,12 13,18"/></svg>',
		'leaf'     => '<svg viewBox="0 0 76 28" fill="none" stroke="currentColor" stroke-width="1.55" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21c20 0 42-7 69-17"/><path d="M16 19c-5-1-8-4-9-8 5-.4 9 1.7 11.5 6.6M29 15.8c-5.2-2.2-7.1-6-6.8-10 5.2.7 8.5 3.7 9.8 8.8M43 11.5c-3.5-3.5-3.8-7.4-2.2-11 4.4 2.2 6.4 5.8 5.6 9.8M23 17.2c-.8 4.5-3.6 7.2-7.8 8.2-.5-4.7 1.8-8 6-10M37 13.7c.4 4.8-1.8 8.2-6 10.1-1.7-4.6-.2-8.4 4.2-11.5M52 8.5c1.2 4.4-.4 7.8-4.5 10.2-2.4-4-1.5-7.7 2.7-11"/></svg>',
		'leaf-diagonal' => '<svg viewBox="0 0 78 58" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M5 52C26 45 45 27 71 5"/><path d="M18 45c-6-.2-10-3.1-12-7.8 6-1 11 1.2 14.5 6.5M31 36c-5.7-1.7-8.7-5.5-9-10.4 5.9.4 10 3.2 12.2 8.5M45 25c-4.9-3.2-6.4-7.5-5-12 5.3 1.9 8.4 5.6 8.5 10.5M27 39c.3 5.6-2 9.5-6.9 11.7-2-5.3-.4-9.6 4.8-12.7M41 29.5c1.5 5.2 0 9.4-4.5 12.4-3-4.6-2.2-9 2.4-13.1M56 17c2.3 4.8 1.5 8.9-2.4 12.3-3.7-4-3.5-8.3.7-12.8"/></svg>',
		'star-filled' => '<svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12,2 15,9 22,9.5 16.5,14.5 18.3,21.5 12,17.5 5.7,21.5 7.5,14.5 2,9.5 9,9"/></svg>',
		'star-empty'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4"><polygon points="12,2 15,9 22,9.5 16.5,14.5 18.3,21.5 12,17.5 5.7,21.5 7.5,14.5 2,9.5 9,9"/></svg>',
		'social-facebook'  => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M15 3h-2.2C10.6 3 9 4.7 9 7v2.4H7V13h2v8h3.3v-8h2.6l.4-3.6h-3V7.3c0-.8.3-1.3 1.4-1.3H15V3z"/></svg>',
		'social-instagram' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3.5" y="3.5" width="17" height="17" rx="4.5"/><circle cx="12" cy="12" r="4"/><circle cx="17.2" cy="6.8" r="0.9" fill="currentColor" stroke="none"/></svg>',
		'social-pinterest' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="12" cy="12" r="9"/><path d="M9.5 19c1-4 1.3-6.6 1.3-8 0-2 1-3.4 2.8-3.4 1.5 0 2.4 1.1 2.4 2.7 0 1.6-1 4-1.6 5-.5.9-.1 2 1 2 1.7 0 3-2 3-4.8 0-2.6-1.9-4.6-4.9-4.6-3.4 0-5.4 2.5-5.4 5.1 0 1 .4 2.1.9 2.7"/></svg>',
		'social-tiktok'    => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M14.5 3c.4 2 1.8 3.6 4 3.9v2.6c-1.4 0-2.8-.4-4-1.2v6.1c0 3.1-2.5 5.6-5.6 5.6S3.3 17.5 3.3 14.4 5.8 8.8 8.9 8.8c.4 0 .8 0 1.1.1v2.7a2.9 2.9 0 1 0 2 2.7V3h2.5z"/></svg>',
		'social-email'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M4 6.5l8 6 8-6"/></svg>',
		'feature-handmade'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M7 13V6a1.6 1.6 0 0 1 3.2 0v5"/><path d="M10.2 11V4.6a1.6 1.6 0 0 1 3.2 0V11"/><path d="M13.4 11.4V5.8a1.6 1.6 0 0 1 3.2 0V13"/><path d="M16.6 8.6a1.6 1.6 0 0 1 3.2 0v6.4c0 3.6-2.4 6-6.4 6-3 0-4.4-1-6-3l-3-4.4c-.6-.9.1-2.1 1.2-2 .5.1.9.4 1.2.8L8 14"/></svg>',
		'feature-fairelife' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 3l2.6 5.4 6 .8-4.3 4.1 1 5.9-5.3-2.8-5.3 2.8 1-5.9-4.3-4.1 6-.8L12 3z"/></svg>',
		'feature-quality'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 3l2.1 3.6 4.1.9-2.8 3.1.5 4.2L12 13l-3.9 1.8.5-4.2-2.8-3.1 4.1-.9L12 3z"/><path d="M7 15.5L5 21l7-3 7 3-2-5.5"/></svg>',
		'feature-shipping'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a13 13 0 0 1 0 18a13 13 0 0 1 0-18z"/></svg>',
	);

	if ( isset( $icons[ $name ] ) ) {
		return $icons[ $name ];
	}

	return '';
}

/**
 * Echoes the icon markup, wrapped in a span for easy CSS sizing.
 *
 * @param string $name  Icon key.
 * @param string $class Extra class(es) for the wrapper span.
 */
function hugginbutt_the_icon( $name, $class = '' ) {
	printf(
		'<span class="hb-icon hb-icon--%1$s %2$s" aria-hidden="true">%3$s</span>',
		esc_attr( $name ),
		esc_attr( $class ),
		hugginbutt_icon( $name ) // phpcs:ignore -- fixed internal SVG set, not user input.
	);
}
