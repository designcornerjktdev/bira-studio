<?php
/**
 * Field ACF untuk section homepage (lihat BIRA Website Guideline, "Homepage Architecture").
 * Semua field didaftarkan lewat kode agar ikut ter-versioning; tidak ada field group di database.
 *
 * Hanya memakai tipe field ACF gratis (tanpa Repeater/Gallery), jadi item berulang
 * dibuat sebagai grup bernomor dengan jumlah tetap sesuai desain.
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

add_action( 'acf/include_fields', function () {
	$f = static function ( string $name, string $label, string $type = 'text', array $extra = [] ): array {
		return array_merge( [
			'key'   => 'field_bira_' . $name,
			'name'  => $name,
			'label' => $label,
			'type'  => $type,
		], $extra );
	};

	$tab = static fn( string $name, string $label ): array => [
		'key'       => 'field_bira_tab_' . $name,
		'label'     => $label,
		'type'      => 'tab',
		'placement' => 'top',
	];

	// Grup bernomor: prefix_1 ... prefix_n, masing-masing dengan sub field yang sama.
	$numbered = static function ( string $prefix, string $label, int $count, array $subs ) use ( $f ): array {
		$out = [];
		for ( $i = 1; $i <= $count; $i++ ) {
			$sub_fields = [];
			foreach ( $subs as [ $name, $sub_label, $type ] ) {
				$extra = 'image' === $type ? [ 'return_format' => 'id', 'preview_size' => 'medium' ] : [];
				$sub_fields[] = $f( "{$prefix}_{$i}_{$name}", $sub_label, $type, [ 'name' => $name ] + $extra );
			}
			$out[] = $f( "{$prefix}_{$i}", "{$label} {$i}", 'group', [ 'layout' => 'block', 'sub_fields' => $sub_fields ] );
		}
		return $out;
	};

	// Kartu mockup hero. Key field sengaja sama dengan grup lama di database
	// (sebelum dipindah ke kode), supaya data yang sudah diisi tetap terbaca.
	$hero_post = static function ( int $n, string $group_key, array $keys ): array {
		$help = [
			'image'   => 'Rasio mendekati 4:3 atau persegi paling aman',
			'handle'  => 'Misalnya @madeforthefeed',
			'caption' => 'Maksimal sekitar 2 baris, dipotong otomatis',
		];
		$subs = [];
		foreach ( [ 'image', 'handle', 'caption' ] as $i => $name ) {
			$subs[] = [
				'key'          => $keys[ $i ],
				'name'         => $name,
				'label'        => ucfirst( $name ),
				'type'         => 'image' === $name ? 'image' : 'text',
				'instructions' => $help[ $name ],
			] + ( 'image' === $name ? [ 'return_format' => 'id', 'preview_size' => 'medium' ] : [] );
		}
		return [ 'key' => $group_key, 'name' => "hero_post_{$n}", 'label' => "Kartu mockup {$n}", 'type' => 'group', 'layout' => 'block', 'sub_fields' => $subs ];
	};

	$fields = array_merge(
		[ $tab( 'hero', 'Hero' ) ],
		[
			[ 'key' => 'field_6ac89835a1906', 'name' => 'hero_headline', 'label' => 'Headline', 'type' => 'text' ],
			[ 'key' => 'field_6ac8abef4ca10', 'name' => 'hero_accent_word', 'label' => 'Kata aksen (hijau)', 'type' => 'text' ],
			[ 'key' => 'field_6ac8984fa1907', 'name' => 'hero_description', 'label' => 'Description', 'type' => 'textarea' ],
			[ 'key' => 'field_6ac8985ca1908', 'name' => 'hero_cta_label', 'label' => 'CTA utama — label', 'type' => 'text', 'placeholder' => 'See our work' ],
			[ 'key' => 'field_6ac89866a1909', 'name' => 'hero_cta_url', 'label' => 'CTA utama — URL', 'type' => 'url' ],
			$hero_post( 1, 'field_6ac8bba663955', [ 'field_6ac8bbd063956', 'field_6ac8bbf363957', 'field_6ac8bc0a63958' ] ),
			$hero_post( 2, 'field_6ac8bc3163959', [ 'field_6ac8bc316395a', 'field_6ac8bc316395b', 'field_6ac8bc316395c' ] ),
			$hero_post( 3, 'field_6ac8bc326395d', [ 'field_6ac8bc326395e', 'field_6ac8bc326395f', 'field_6ac8bc3263960' ] ),
			$hero_post( 4, 'field_6ac8bc3463961', [ 'field_6ac8bc3463962', 'field_6ac8bc3463963', 'field_6ac8bc3463964' ] ),
		],
		[
			$f( 'hero_eyebrow', 'Eyebrow', 'text', [ 'placeholder' => 'Independent studio. Internet people.' ] ),
			$f( 'hero_lead', 'Kalimat pembuka (tebal)', 'text', [ 'placeholder' => 'Social, content & influence from Indonesia.', 'instructions' => 'Tampil di atas field Description.' ] ),
			$f( 'hero_cta2_label', 'CTA kedua — label', 'text', [ 'placeholder' => 'Let’s make something' ] ),
			$f( 'hero_cta2_url', 'CTA kedua — URL', 'text', [ 'placeholder' => '#contact' ] ),
			$f( 'hero_symbol', 'Simbol BIRA (latar mockup)', 'image', [ 'return_format' => 'id', 'preview_size' => 'medium', 'instructions' => 'PNG transparan. Kosongkan untuk memakai garis bingkai.' ] ),
			$f( 'hero_strip', 'Teks strip bawah', 'text', [ 'placeholder' => 'Social. Content. Influence.' ] ),
		],

		[ $tab( 'clients', 'Klien (marquee)' ) ],
		[ $f( 'clients_title', 'Judul strip', 'text', [ 'placeholder' => 'Brands we’ve made some noise with' ] ) ],
		$numbered( 'client', 'Klien', 5, [ [ 'name', 'Nama brand', 'text' ], [ 'style', 'Class gaya huruf (opsional)', 'text' ] ] ),

		[ $tab( 'work', 'The Good Stuff' ) ],
		[
			$f( 'work_eyebrow', 'Eyebrow', 'text', [ 'placeholder' => 'The good stuff' ] ),
			$f( 'work_title', 'Judul', 'text', [ 'placeholder' => 'Stuff we’re proud of' ] ),
			$f( 'work_all_url', 'URL "All work"', 'text', [ 'placeholder' => '/work' ] ),
		],
		$numbered( 'work_item', 'Project', 3, [
			[ 'image', 'Gambar', 'image' ],
			[ 'brand', 'Brand', 'text' ],
			[ 'tag', 'Badge', 'text' ],
			[ 'hook', 'Kalimat besar di gambar', 'textarea' ],
			[ 'cats', 'Kategori (Social / Content / …)', 'text' ],
			[ 'title', 'Judul project', 'text' ],
			[ 'text', 'Deskripsi', 'textarea' ],
			[ 'stats', 'Statistik, pisahkan koma (mis. 20M+ Views, 150+ Content pieces)', 'text' ],
			[ 'url', 'URL', 'text' ],
		] ),

		[ $tab( 'wwd', 'What We Do' ) ],
		[
			$f( 'wwd_title', 'Judul baris 1', 'text', [ 'placeholder' => 'We do internet things.' ] ),
			$f( 'wwd_title_muted', 'Judul baris 2 (abu-abu)', 'text', [ 'placeholder' => 'But with a plan.' ] ),
			$f( 'wwd_intro', 'Intro', 'textarea', [ 'rows' => 2 ] ),
		],
		$numbered( 'wwd_item', 'Layanan', 4, [
			[ 'title', 'Nama', 'text' ],
			[ 'text', 'Tagline', 'text' ],
			[ 'desc', 'Deskripsi', 'textarea' ],
			[ 'tags', 'Sub-layanan, pisahkan koma', 'text' ],
		] ),

		[ $tab( 'hww', 'How We Work' ) ],
		[
			$f( 'hww_eyebrow', 'Eyebrow', 'text', [ 'placeholder' => 'Not a formula. A feeling.' ] ),
			$f( 'hww_title', 'Judul baris 1', 'text', [ 'placeholder' => 'How we make' ] ),
			$f( 'hww_title_2', 'Judul baris 2', 'text', [ 'placeholder' => 'things move' ] ),
			$f( 'hww_intro', 'Intro', 'textarea', [ 'rows' => 2 ] ),
		],
		$numbered( 'hww_step', 'Langkah', 5, [ [ 'title', 'Nama', 'text' ], [ 'text', 'Deskripsi', 'textarea' ] ] ),

		[ $tab( 'feed', 'Made for the Feed' ) ],
		[
			$f( 'feed_eyebrow', 'Eyebrow', 'text', [ 'placeholder' => 'Less skip. More send.' ] ),
			$f( 'feed_intro', 'Intro', 'textarea', [ 'rows' => 2 ] ),
			$f( 'feed_sticker', 'Stiker bulat', 'textarea', [ 'rows' => 2, 'placeholder' => "Less polished.\nMore human." ] ),
			$f( 'feed_comment', 'Bubble komentar', 'text', [ 'placeholder' => '“this lives in my head rent free”' ] ),
		],
		$numbered( 'feed_item', 'Kartu', 4, [
			[ 'image', 'Gambar', 'image' ],
			[ 'label', 'Label', 'text' ],
			[ 'title', 'Judul', 'text' ],
			[ 'text', 'Teks saat hover', 'text' ],
		] ),

		[ $tab( 'pov', 'BIRA POV' ) ],
		[
			$f( 'pov_eyebrow', 'Eyebrow', 'text', [ 'placeholder' => 'A friendly reminder' ] ),
			$f( 'pov_line_1', 'Baris 1', 'text', [ 'placeholder' => 'Don’t interrupt' ] ),
			$f( 'pov_line_2', 'Baris 2', 'text', [ 'placeholder' => 'the culture.' ] ),
			$f( 'pov_accent', 'Baris 3 (putih)', 'text', [ 'placeholder' => 'Join it.' ] ),
			$f( 'pov_body', 'Teks pendukung', 'textarea', [ 'rows' => 3 ] ),
		],

		[ $tab( 'people', 'Internet People' ) ],
		[
			$f( 'people_eyebrow', 'Eyebrow', 'text', [ 'placeholder' => 'Meet the internet people' ] ),
			$f( 'people_title', 'Judul', 'text', [ 'placeholder' => 'From Indonesia, into the feed' ] ),
			$f( 'people_intro', 'Intro paragraf 1', 'textarea', [ 'rows' => 2 ] ),
			$f( 'people_intro_2', 'Intro paragraf 2', 'textarea', [ 'rows' => 3 ] ),
			$f( 'people_more_url', 'URL "A little more about us"', 'text', [ 'placeholder' => '/about' ] ),
		],
		$numbered( 'people_member', 'Orang', 5, [ [ 'photo', 'Foto', 'image' ], [ 'name', 'Nama', 'text' ], [ 'role', 'Peran', 'text' ] ] ),

		[ $tab( 'cta', 'Final CTA' ) ],
		[
			$f( 'cta_status', 'Status', 'text', [ 'placeholder' => 'Open for good conversations' ] ),
			$f( 'cta_line_1', 'Judul baris 1', 'text', [ 'placeholder' => 'Got something' ] ),
			$f( 'cta_accent', 'Kata aksen (hijau)', 'text', [ 'placeholder' => 'Weird' ] ),
			$f( 'cta_line_2', 'Sisa baris 2', 'text', [ 'placeholder' => 'in mind?' ] ),
			$f( 'cta_label', 'Label tombol bulat', 'text', [ 'placeholder' => 'Talk to BIRA' ] ),
			$f( 'cta_url', 'URL tombol', 'text', [ 'placeholder' => '/contact' ] ),
			$f( 'cta_note', 'Catatan tulisan tangan (hijau)', 'text', [ 'placeholder' => 'Good.' ] ),
			$f( 'cta_note_2', 'Catatan tulisan tangan (abu-abu)', 'text', [ 'placeholder' => 'Let’s make it happen.' ] ),
		],

		[ $tab( 'footer', 'Footer' ) ],
		[
			$f( 'footer_tagline', 'Tagline', 'textarea', [ 'rows' => 2, 'placeholder' => "Born in Indonesia.\nBuilt for the internet." ] ),
			$f( 'footer_legal', 'Teks copyright', 'text', [ 'placeholder' => 'A little different. On purpose.' ] ),
			$f( 'footer_email', 'Email (mailto:)', 'text', [ 'placeholder' => 'mailto:hello@birastudio.co' ] ),
			$f( 'footer_instagram', 'URL Instagram', 'url' ),
			$f( 'footer_tiktok', 'URL TikTok', 'url' ),
		]
	);

	acf_add_local_field_group( [
		'key'        => 'group_bira_home_sections',
		'title'      => 'Homepage Sections',
		'fields'     => $fields,
		'location'   => [ [ [ 'param' => 'page_type', 'operator' => '==', 'value' => 'front_page' ] ] ],
		'menu_order' => 10,
		'position'   => 'normal',
	] );
} );
