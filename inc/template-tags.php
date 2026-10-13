<?php
/**
 * Helper untuk template-parts.
 */

/**
 * Teks dari field ACF di halaman depan, dengan fallback bila kosong.
 */
function bira_text( string $key, string $default = '' ): string {
	$value = bira_field( $key, (int) get_option( 'page_on_front' ) );
	return is_string( $value ) && '' !== trim( $value ) ? $value : $default;
}

/**
 * Grup ACF bernomor (mis. wwd_item_1 ... wwd_item_4) sebagai array item.
 * Item yang semua isinya kosong memakai fallback pada indeks yang sama.
 */
function bira_items( string $prefix, array $defaults ): array {
	$id    = (int) get_option( 'page_on_front' );
	$items = [];
	foreach ( $defaults as $i => $default ) {
		$group   = bira_field( $prefix . '_' . ( $i + 1 ), $id );
		$group   = is_array( $group ) ? array_filter( $group ) : [];
		$items[] = $group + $default;
	}
	return $items;
}

/**
 * Escape teks lalu bungkus kemunculan pertama $accent dengan <span>.
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
 * Doodle dari "Graphic Language" guideline. Maksimal satu atau dua per section.
 */
function bira_doodle( string $name, string $class = '' ): string {
	$paths = [
		'star'  => '<path d="M12 2v20M3.3 7l17.4 10M3.3 17 20.7 7"/>',
		'wave'  => '<path d="M2 14c2-6 4-6 6 0s4 6 6 0 4-6 6 0 3 5 2 3"/>',
		'arrow' => '<path d="M4 18 19 5M10 5h9v9"/>',
		'next'  => '<path d="M4 12h15M13 6l6 6-6 6"/>',
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
 * Primary button: pill hitam dengan titik → hover hijau (wave dari titik) + panah.
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
