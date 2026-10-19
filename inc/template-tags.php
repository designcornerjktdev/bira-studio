<?php
/**
 * Helper untuk template-parts.
 */

/**
 * Teks dari Customizer (Appearance → Customize → Homepage), dengan fallback bila kosong.
 */
function bira_text( string $key, string $default = '' ): string {
	$value = get_theme_mod( $key, '' );
	return is_scalar( $value ) && '' !== trim( (string) $value ) ? (string) $value : $default;
}

/**
 * Item bernomor (mis. wwd_item_1_title ... wwd_item_4_tags) sebagai array item.
 * Sub field yang kosong memakai fallback pada indeks yang sama.
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
		// Gaya "business card": sparkle, mahkota, berlian (outline, sedikit tidak simetris).
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
 * Doodle latar: ikon kecil, samar, di belakang konten. Pakai 1–2 per section.
 * Section induk perlu class `relative isolate` (doodle memakai z-index -1).
 *
 * @param array  $items [ [ nama_doodle, class posisi/ukuran ], ... ]
 * @param string $tone  Class warna, mis. 'text-green' atau 'text-cream' di latar gelap.
 */
function bira_bg_doodles( array $items, string $tone = 'text-green' ): string {
	$out = '';
	foreach ( $items as [ $name, $class ] ) {
		$out .= bira_doodle( $name, "bira-bg-doodle {$tone} {$class}" );
	}
	return $out;
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
