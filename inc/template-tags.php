<?php
/**
 * Helpers for template-parts.
 */

/**
 * Text from the Customizer (Appearance → Customize → Homepage), with a fallback when empty.
 */
function bira_text( string $key, string $default = '' ): string {
	$value = get_theme_mod( $key, '' );
	return is_scalar( $value ) && '' !== trim( (string) $value ) ? (string) $value : $default;
}

/**
 * Numbered items (e.g. wwd_item_1_title ... wwd_item_4_tags) as an array of items.
 * Empty sub fields use the fallback at the same index.
 */
function bira_items( string $prefix, array $defaults ): array {
	$items = [];
	foreach ( $defaults as $i => $default ) {
		$item = [];
		foreach ( $default as $sub => $fallback ) {
			$item[ $sub ] = bira_text( "{$prefix}_" . ( $i + 1 ) . "_{$sub}", (string) $fallback );
		}
		$items[] = $item;
	}
	return $items;
}

/**
 * Escape text, then wrap the first occurrence of $accent in a <span>.
 */
function bira_accent( string $text, string $accent, string $class ): string {
	$html = esc_html( $text );
	if ( '' === $accent ) {
		return $html;
	}
	return preg_replace(
		'/' . preg_quote( esc_html( $accent ), '/' ) . '/i',
		'<span class="' . esc_attr( $class ) . '">$0</span>',
		$html,
		1
	);
}

/**
 * Doodles from the "Graphic Language" guideline. At most one or two per section.
 */
function bira_doodle( string $name, string $class = '' ): string {
	$paths = [
		'star'  => '<path d="M12 2v20M3.3 7l17.4 10M3.3 17 20.7 7"/>',
		'wave'  => '<path d="M2 14c2-6 4-6 6 0s4 6 6 0 4-6 6 0 3 5 2 3"/>',
		'arrow' => '<path d="M4 18 19 5M10 5h9v9"/>',
		'next'  => '<path d="M4 12h15M13 6l6 6-6 6"/>',
		// "Business card" style: sparkle, crown, diamond (outline, slightly asymmetric).
		'sparkle' => '<path d="M12 2.5c.7 5 4.3 8.7 9.5 9.6-5.2.8-8.8 4.5-9.6 9.6-.7-5.1-4.4-8.8-9.4-9.5 5.1-.9 8.8-4.6 9.5-9.7Z"/>',
		'crown'   => '<path d="M3.5 18.5 2.8 7.2l5.4 5.1 3.9-8.6 3.6 8.8 5.6-5.3-.9 11.4c-5.4.6-10.6.6-16.9-.1Z"/>',
		'diamond' => '<path d="M6.2 3.4h11.4L22 9.2 12 21 2.2 9.3Z"/><path d="M2.4 9.2h19.4M8.9 3.6 7.4 9.3 12 20.6l4.7-11.3-1.6-5.8"/>',
	];
	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}
	$view = 'wave' === $name ? '0 0 24 20' : '0 0 24 24';
	return sprintf(
		'<svg class="%s" viewBox="%s" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%s</svg>',
		esc_attr( $class ),
		$view,
		$paths[ $name ]
	);
}

/**
 * Background doodles: small, faint icons behind the content. Use 1–2 per section.
 * The parent section needs `relative isolate` (doodles use z-index -1).
 *
 * @param array  $items [ [ doodle_name, position/size classes ], ... ]
 * @param string $tone  Color class, e.g. 'text-green', or 'text-cream' on dark backgrounds.
 */
function bira_bg_doodles( array $items, string $tone = 'text-green' ): string {
	$out = '';
	foreach ( $items as [ $name, $class ] ) {
		$out .= bira_doodle( $name, "bira-bg-doodle {$tone} {$class}" );
	}
	return $out;
}

/**
 * Primary button: black pill with a dot → green on hover (wave from the dot) + arrow.
 */
function bira_button( string $label, string $url, string $class = '' ): string {
	return sprintf(
		'<a class="bira-btn %1$s" href="%2$s"><span class="bira-btn__dot" aria-hidden="true"></span><span class="bira-btn__label">%3$s</span>%4$s</a>',
		esc_attr( $class ),
		esc_url( $url ),
		esc_html( $label ),
		bira_doodle( 'next', 'bira-btn__arrow' )
	);
}

/**
 * Contact links from the Customizer (Homepage → Footer), used by the footer and contact pop-up.
 * Returns: [ 'email' => 'mailto:…', 'socials' => [ label => url, … ] ]. Empty ones are dropped.
 */
function bira_contact_links(): array {
	return [
		'email'   => bira_text( 'footer_email', 'mailto:hello@birastudio.co' ),
		'socials' => array_filter( [
			'Instagram' => bira_text( 'footer_instagram', 'https://www.instagram.com/idads.co/' ),
			'LinkedIn'  => bira_text( 'footer_linkedin', 'https://www.linkedin.com/company/id-ads/' ),
			// Second Instagram with its own label (default: the Moyo account).
			bira_text( 'footer_instagram_2_label', 'Life at Moyo' ) => bira_text( 'footer_instagram_2', 'https://www.instagram.com/lifeatmoyo/' ),
		] ),
	];
}

/**
 * Display text for a link: "mailto:hi@x.co" → "hi@x.co", "https://www.instagram.com/idads.co/" → "instagram.com/idads.co".
 */
function bira_link_label( string $url ): string {
	return rtrim( (string) preg_replace( [ '#^mailto:#i', '#^https?://(www\.)?#i' ], '', $url ), '/' );
}

/**
 * Diagonal "swipe" arrow as on Work cards: when the parent .group is hovered/focused, the arrow exits
 * top-right and a copy enters from bottom-left (CSS .bira-swipe-arrow). The parent must have the "group" class.
 */
function bira_swipe_arrow( string $size = 'size-3.5', string $class = '' ): string {
	return sprintf(
		'<span class="bira-swipe-arrow relative inline-flex shrink-0 items-center justify-center overflow-hidden %s" aria-hidden="true">%s%s</span>',
		esc_attr( $class ),
		bira_doodle( 'arrow', "bira-swipe-arrow__out {$size}" ),
		bira_doodle( 'arrow', "bira-swipe-arrow__in absolute {$size}" )
	);
}

/**
 * Estimated post reading time (minutes, ~200 words/minute). Used by archive cards and post detail.
 */
function bira_read_minutes( ?int $post_id = null ): int {
	$words = str_word_count( wp_strip_all_tags( (string) get_post_field( 'post_content', $post_id ?? get_the_ID() ) ) );
	return max( 1, (int) round( $words / 200 ) );
}
