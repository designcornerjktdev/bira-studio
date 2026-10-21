<?php
/**
 * Konten homepage di Appearance → Customize → Homepage (live preview).
 * Satu skema di bawah dipakai untuk: section & control Customizer, sanitasi,
 * migrasi sekali dari field ACF lama, dan bira_text()/bira_items() di template.
 *
 * Nilai disimpan sebagai theme_mod dengan nama sama seperti meta ACF lama
 * (mis. hero_headline, wwd_item_1_title). Kosong = template memakai teks default.
 */

/**
 * Skema: section => [ panel (default bira_home), title, partial selector, fields ].
 * Field: key => [ label, type, extra ]. Type: text | textarea | url | image | gallery (banyak gambar, ID dipisah koma).
 * Item berulang: 'repeat' => [ prefix, label, jumlah, [ sub => [ label, type ] ] ].
 */
function bira_content_schema(): array {
	return [
		'hero'    => [
			'title'    => 'Hero',
			'desc'     => 'Stack card 1 is at the bottom of the pile, Stack card 5 on top. On hover the pile fans out. Animated GIFs are supported.',
			'selector' => '#hero',
			'part'     => 'hero',
			'fields'   => [
				'hero_headline'    => [ 'Headline', 'textarea', [ 'placeholder' => "We make\nbrands part\nof the\nconversation.", 'desc' => 'Press Enter for a new line in the headline.' ] ],
				'hero_accent_word' => [ 'Accent word (green)', 'text', [ 'placeholder' => 'conversation', 'desc' => 'The word in the headline shown in green.' ] ],
				'hero_lead'        => [ 'Lead sentence (bold)', 'text', [ 'placeholder' => 'Social, content & influence from Indonesia.', 'desc' => 'Shown in bold above the description.' ] ],
				'hero_description' => [ 'Description', 'textarea', [ 'placeholder' => 'Built for brands that want people to actually talk about them.' ] ],
				'hero_cta_label'   => [ 'Primary CTA — label', 'text', [ 'placeholder' => 'See our work' ] ],
				'hero_cta_url'     => [ 'Primary CTA — URL', 'text', [ 'placeholder' => '#work' ] ],
				'hero_cta2_label'  => [ 'Secondary CTA — label', 'text', [ 'placeholder' => 'Let’s make something' ] ],
				'hero_cta2_url'    => [ 'Secondary CTA — URL', 'text', [ 'placeholder' => '#cta' ] ],
				'hero_symbol'      => [ 'BIRA symbol (mockup background)', 'image', [ 'desc' => 'Transparent PNG. Leave empty to use the outline frame.' ] ],
			],
			'repeat'   => [ 'hero_post', 'Stack card', 5, [
				'image'   => [ 'Image', 'image', [ 'desc' => 'A ratio close to 4:3 or square works best.' ] ],
				'handle'  => [ 'Handle', 'text', [ 'placeholder' => '@madeforthefeed' ] ],
				'caption' => [ 'Caption', 'text', [ 'desc' => 'One line; longer text is cut off with “…”.' ] ],
			] ],
		],
		'trust'   => [
			'title'    => 'Trust',
			'desc'     => 'Numbers shown above the brand strip. The last number is green.',
			'selector' => '#trust',
			'part'     => 'trust',
			'fields'   => [],
			'repeat'   => [ 'trust_stat', 'Stat', 3, [
				'number' => [ 'Number', 'text', [ 'placeholder' => 'e.g. 50M+' ] ],
				'label'  => [ 'Label', 'text', [ 'placeholder' => 'e.g. Views' ] ],
			] ],
		],
		'clients' => [
			'title'    => 'Clients',
			'selector' => '#clients',
			'part'     => 'clients',
			'fields'   => [
				'clients_title' => [ 'Strip title', 'text', [ 'placeholder' => 'Brands we’ve made some noise with' ] ],
				'clients_logos' => [ 'Brand logos', 'gallery', [ 'desc' => 'Select as many logos as you like (transparent PNG works best). Order follows your selection. When empty, the brand names below are shown as text.' ] ],
				'clients_list'  => [ 'Brand names (fallback)', 'textarea', [ 'placeholder' => 'Relaxa, ABC, Your Brand, Next Up, Watch This Space', 'desc' => 'Only used when no logos are selected. Separate brands with commas.' ] ],
			],
		],
		'services' => [
			'title'    => 'Services (What We Do)',
			'selector' => '#services',
			'part'     => 'services',
			'fields'   => [
				'wwd_title'       => [ 'Title line 1', 'text', [ 'placeholder' => 'We do internet things.' ] ],
				'wwd_title_muted' => [ 'Title line 2 (grey)', 'text', [ 'placeholder' => 'But with a plan.' ] ],
				'wwd_intro'       => [ 'Intro', 'textarea' ],
			],
			'repeat'   => [ 'wwd_item', 'Service', 4, [
				'title' => [ 'Name', 'text' ],
				'text'  => [ 'Tagline', 'text' ],
				'desc'  => [ 'Description', 'textarea' ],
				'tags'  => [ 'Sub-services, separated by commas', 'text' ],
			] ],
		],
		'process' => [
			'title'    => 'Process (How We Work)',
			'selector' => '#process',
			'part'     => 'process',
			'fields'   => [
				'hww_eyebrow' => [ 'Eyebrow', 'text', [ 'placeholder' => 'Not a formula. A feeling.' ] ],
				'hww_title'   => [ 'Title', 'text', [ 'placeholder' => 'How we make things move', 'desc' => 'Wraps automatically. A sand-colored period is added at the end.' ] ],
				'hww_intro'   => [ 'Intro', 'textarea' ],
			],
			'repeat'   => [ 'hww_step', 'Step', 5, [
				'title' => [ 'Name', 'text' ],
				'text'  => [ 'Description', 'textarea' ],
			] ],
		],
		'work'    => [
			'title'    => 'Work (The Good Stuff)',
			'desc'     => 'Projects are managed in the Projects menu. Projects marked Featured ★ appear here.',
			'selector' => '#work',
			'part'     => 'work',
			'fields'   => [
				'work_eyebrow' => [ 'Eyebrow', 'text', [ 'placeholder' => 'The good stuff' ] ],
				'work_title'   => [ 'Title', 'text', [ 'placeholder' => 'Stuff we’re proud of' ] ],
				'work_all_url' => [ 'URL "All work"', 'text', [ 'placeholder' => '/work' ] ],
			],
		],
		'team'    => [
			'title'    => 'Team (The Group Chat Admins)',
			'desc'     => 'Team cards.',
			'selector' => '#team',
			'part'     => 'team',
			'fields'   => [
				'feed_eyebrow'  => [ 'Eyebrow', 'text', [ 'placeholder' => 'Straight from group chat.' ] ],
				'feed_headline' => [ 'Headline', 'textarea', [ 'placeholder' => "The group\nchat admins.", 'desc' => 'Press Enter for a new line.' ] ],
				'feed_accent'   => [ 'Accent word (green)', 'text', [ 'placeholder' => 'admins.', 'desc' => 'The word or phrase in the headline shown in green.' ] ],
				'feed_intro'    => [ 'Intro', 'textarea', [ 'placeholder' => 'A few of the people behind the ideas, conversations, and occasional group chat chaos.' ] ],
				'feed_sticker'  => [ 'Round sticker', 'textarea', [ 'placeholder' => "24/7\nAlways online" ] ],
				'feed_comment'  => [ 'Comment bubble', 'text', [ 'placeholder' => '“the ones who sent the photos on time.”' ] ],
			],
			'repeat'   => [ 'team_member', 'Member', 5, [
				'photo'  => [ 'Photo', 'image', [ 'desc' => 'Portrait works best (about 2:3).' ] ],
				'name'   => [ 'Name', 'text' ],
				'role'   => [ 'Role', 'text', [ 'placeholder' => 'e.g. Creative Director' ] ],
				'fun'    => [ 'Fun fact (shown on hover)', 'text', [ 'placeholder' => 'e.g. Pitches ideas in voice notes.' ] ],
				'handle' => [ 'Social handle (optional)', 'text', [ 'placeholder' => '@username', 'desc' => 'Shown at the bottom of the card. Empty = "BIRA team".' ] ],
			] ],
		],
		'pov'     => [
			'title'    => 'POV (A Friendly Reminder)',
			'selector' => '#pov',
			'part'     => 'pov',
			'fields'   => [
				'pov_eyebrow' => [ 'Eyebrow', 'text', [ 'placeholder' => 'A friendly reminder' ] ],
				'pov_title'   => [ 'Headline', 'textarea', [ 'placeholder' => "Don’t interrupt\nthe culture.\nJoin it.", 'desc' => 'Press Enter for a new line in the headline.' ] ],
				'pov_accent'  => [ 'Accent word (green)', 'text', [ 'placeholder' => 'Join it.', 'desc' => 'The word or phrase in the headline shown in green.' ] ],
				'pov_body'    => [ 'Supporting text', 'textarea' ],
			],
		],
		'people'  => [
			'title'    => 'People (hidden)',
			'desc'     => 'This section is not shown on the homepage right now. Content is kept in case it comes back.',
			'selector' => '#people',
			'part'     => 'people',
			'fields'   => [
				'people_eyebrow'  => [ 'Eyebrow', 'text', [ 'placeholder' => 'Meet the internet people' ] ],
				'people_title'    => [ 'Title', 'text', [ 'placeholder' => 'From Indonesia, into the feed' ] ],
				'people_intro_2'  => [ 'Intro', 'textarea', [ 'desc' => 'Press Enter for a new line; an empty line starts a new paragraph.' ] ],
				'people_more_url' => [ 'URL "A little more about us"', 'text', [ 'placeholder' => '/about' ] ],
			],
			'repeat'   => [ 'people_member', 'Person', 5, [
				'photo' => [ 'Photo', 'image' ],
				'name'  => [ 'Name', 'text' ],
				'role'  => [ 'Role', 'text' ],
			] ],
		],
		'cta'     => [
			'title'    => 'CTA',
			'selector' => '#cta',
			'part'     => 'cta',
			'fields'   => [
				'cta_status' => [ 'Status', 'text', [ 'placeholder' => 'Open for good conversations' ] ],
				'cta_title'  => [ 'Headline', 'textarea', [ 'placeholder' => "Got something\nWeird in mind?", 'desc' => 'Press Enter for a new line in the headline.' ] ],
				'cta_accent' => [ 'Accent word (green)', 'text', [ 'placeholder' => 'Weird', 'desc' => 'The word or phrase in the headline shown in green.' ] ],
				'cta_label'  => [ 'Round button label', 'text', [ 'placeholder' => 'Talk to BIRA' ] ],
				'cta_url'    => [ 'Button URL', 'text', [ 'placeholder' => '/contact' ] ],
				'cta_note'   => [ 'Handwritten note (green)', 'text', [ 'placeholder' => 'Good.' ] ],
				'cta_note_2' => [ 'Handwritten note (grey)', 'text', [ 'placeholder' => 'Let’s make it happen.' ] ],
			],
		],
		'footer'  => [
			'title'    => 'Footer',
			'selector' => '.bira-footer',
			'part'     => null, // footer.php memuat wp_footer(), jadi tidak bisa dirender ulang sebagian
			'fields'   => [
				'footer_tagline'   => [ 'Tagline', 'textarea', [ 'placeholder' => "Born in Indonesia.\nBuilt for the internet." ] ],
				'footer_legal'     => [ 'Copyright text', 'text', [ 'placeholder' => 'A little different. On purpose.' ] ],
				'footer_email'     => [ 'Email (mailto:)', 'text', [ 'placeholder' => 'mailto:hello@birastudio.co' ] ],
				'footer_instagram' => [ 'Instagram URL', 'url' ],
				'footer_tiktok'    => [ 'TikTok URL', 'url' ],
			],
		],

		// ---------- Halaman Work (archive-bira_project.php) ----------
		'work_page'      => [
			'panel'    => 'bira_work',
			'title'    => 'Hero',
			'desc'     => 'The project grid below the hero is managed in the Projects menu.',
			'selector' => '.bira-work-hero',
			'part'     => null,
			'fields'   => [
				'work_page_eyebrow'  => [ 'Eyebrow', 'text', [ 'placeholder' => 'Our corner of the internet' ] ],
				'work_page_headline' => [ 'Headline', 'textarea', [ 'placeholder' => "Good work.\nGreat conversations.", 'desc' => 'Press Enter for a new line.' ] ],
				'work_page_accent'   => [ 'Accent words (green)', 'text', [ 'placeholder' => 'Great conversations.', 'desc' => 'The word or phrase in the headline shown in green.' ] ],
				'work_page_intro'    => [ 'Intro', 'textarea', [ 'placeholder' => "Different brands. Different briefs. The same obsession:\nmaking something people want to be part of.", 'desc' => 'Press Enter for a new line.' ] ],
				'work_page_count'    => [ 'Count label', 'text', [ 'placeholder' => 'Projects / explorations', 'desc' => 'Shown after the number of projects, next to the filters.' ] ],
			],
		],

		// ---------- Halaman About (page-about.php) ----------
		'about_hero'     => [
			'panel'    => 'bira_about',
			'title'    => 'Hero',
			'selector' => '.bira-about-hero',
			'part'     => null,
			'fields'   => [
				'about_eyebrow'      => [ 'Eyebrow', 'text', [ 'placeholder' => 'Independent minds. Shared internet brain.' ] ],
				'about_headline'     => [ 'Headline', 'textarea', [ 'placeholder' => "Small studio.\nBig internet\nenergy", 'desc' => 'Press Enter for a new line. A green period is added at the end.' ] ],
				'about_accent'       => [ 'Accent word (green)', 'text', [ 'placeholder' => 'internet' ] ],
				'about_intro'        => [ 'Intro', 'textarea', [ 'placeholder' => 'We’re Bira. A social, content & influence studio based in Indonesia. We spend a lot of time on the internet.' ] ],
				'about_intro_muted'  => [ 'Intro — last line (grey)', 'text', [ 'placeholder' => 'And we know how to make that useful.' ] ],
			],
		],
		'about_who'      => [
			'panel'    => 'bira_about',
			'title'    => 'Who We Are',
			'selector' => '.bira-about-who',
			'part'     => null,
			'fields'   => [
				'about_who_image'   => [ 'Image', 'image', [ 'desc' => 'Close to square works best.' ] ],
				'about_who_caption' => [ 'Image caption (optional)', 'text', [ 'placeholder' => 'Studio moodboard' ] ],
				'about_who_eyebrow' => [ 'Eyebrow', 'text', [ 'placeholder' => 'Who we are' ] ],
				'about_who_title'   => [ 'Title', 'textarea', [ 'placeholder' => "Good people.\nGood instincts.\nGreat group chat.", 'desc' => 'Press Enter for a new line.' ] ],
				'about_who_text'    => [ 'Text', 'textarea', [ 'desc' => 'An empty line starts a new paragraph.' ] ],
			],
		],
		'about_believe'  => [
			'panel'    => 'bira_about',
			'title'    => 'What We Believe',
			'selector' => '.bira-about-believe',
			'part'     => null,
			'fields'   => [
				'about_believe_eyebrow' => [ 'Eyebrow', 'text', [ 'placeholder' => 'What we believe' ] ],
			],
			'repeat'   => [ 'about_belief', 'Belief', 3, [
				'title' => [ 'Title', 'text' ],
				'text'  => [ 'Text', 'textarea' ],
			] ],
		],
		'about_think'    => [
			'panel'    => 'bira_about',
			'title'    => 'How We Think',
			'selector' => '.bira-about-think',
			'part'     => null,
			'fields'   => [
				'about_think_eyebrow'  => [ 'Eyebrow', 'text', [ 'placeholder' => 'How we think' ] ],
				'about_think_headline' => [ 'Headline', 'textarea', [ 'placeholder' => "We don’t ask\n“What should\nthe brand say?”", 'desc' => 'Press Enter for a new line.' ] ],
				'about_think_line'     => [ 'Answer line', 'text', [ 'placeholder' => 'We ask “why would anyone care?”' ] ],
			],
		],
		'about_approach' => [
			'panel'    => 'bira_about',
			'title'    => 'Approach & People',
			'selector' => '.bira-about-approach',
			'part'     => null,
			'fields'   => [
				'about_approach_eyebrow' => [ 'Approach — eyebrow', 'text', [ 'placeholder' => 'Our approach' ] ],
				'about_approach_title'   => [ 'Approach — title', 'textarea', [ 'placeholder' => "Listen close.\nThink big.\nMake it human." ] ],
				'about_approach_text'    => [ 'Approach — text', 'textarea' ],
				'about_people_eyebrow'   => [ 'People — eyebrow', 'text', [ 'placeholder' => 'Our people' ] ],
				'about_people_title'     => [ 'People — title', 'textarea', [ 'placeholder' => "Many brains.\nOne good feeling." ] ],
				'about_people_text'      => [ 'People — text', 'textarea' ],
				'about_people_note'      => [ 'People — small note (optional)', 'text', [ 'placeholder' => 'Real team portraits and introductions coming soon.' ] ],
			],
		],
	];
}

/**
 * Semua field satu section, item berulang dijabarkan jadi prefix_1_sub, prefix_2_sub, …
 */
function bira_content_fields( array $section ): array {
	$fields = $section['fields'];
	if ( isset( $section['repeat'] ) ) {
		[ $prefix, $label, $count, $subs ] = $section['repeat'];
		for ( $i = 1; $i <= $count; $i++ ) {
			foreach ( $subs as $sub => $def ) {
				$def[0] = "{$label} {$i} — {$def[0]}";
				$fields[ "{$prefix}_{$i}_{$sub}" ] = $def;
			}
		}
	}
	return $fields;
}

add_action( 'customize_register', function ( WP_Customize_Manager $wp_customize ) {
	$wp_customize->add_panel( 'bira_home', [
		'title'       => 'Homepage',
		'description' => 'Content for each homepage section. Empty fields use the default text shown as placeholder.',
		'priority'    => 30,
	] );
	$wp_customize->add_panel( 'bira_work', [
		'title'       => 'Work Page',
		'description' => 'Content for the Work page (/work). Open that page in the preview to see changes. Empty fields use the default text.',
		'priority'    => 31,
	] );
	$wp_customize->add_panel( 'bira_about', [
		'title'       => 'About Page',
		'description' => 'Content for the About page (a Page with the slug "about"). Open that page in the preview to see changes. Empty fields use the default text.',
		'priority'    => 32,
	] );

	$priority = 0;
	foreach ( bira_content_schema() as $id => $section ) {
		$section_id = "bira_section_{$id}";
		$wp_customize->add_section( $section_id, [ 'title' => $section['title'], 'description' => $section['desc'] ?? '', 'panel' => $section['panel'] ?? 'bira_home', 'priority' => $priority += 10 ] );

		$keys = [];
		foreach ( bira_content_fields( $section ) as $key => $def ) {
			[ $label, $type ] = $def;
			$extra = $def[2] ?? [];

			$sanitize = [
				'textarea' => 'sanitize_textarea_field',
				'url'      => 'esc_url_raw',
				'image'    => 'absint',
				'gallery'  => 'bira_sanitize_ids',
			][ $type ] ?? 'sanitize_text_field';

			$wp_customize->add_setting( $key, [ 'default' => '', 'sanitize_callback' => $sanitize ] );
			$keys[] = $key;

			$args = [ 'label' => $label, 'description' => $extra['desc'] ?? '', 'section' => $section_id ];
			if ( 'image' === $type ) {
				$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, $key, $args + [ 'mime_type' => 'image' ] ) );
			} elseif ( 'gallery' === $type ) {
				$wp_customize->add_control( new Bira_Gallery_Control( $wp_customize, $key, $args ) );
			} else {
				$wp_customize->add_control( $key, $args + [
					'type'        => $type,
					'input_attrs' => isset( $extra['placeholder'] ) ? [ 'placeholder' => $extra['placeholder'] ] : [],
				] );
			}
		}

		// Partial hanya untuk tombol pensil (edit shortcut) di preview; setting tetap transport "refresh"
		// supaya animasi GSAP section dijalankan ulang dengan benar.
		if ( isset( $wp_customize->selective_refresh ) && $keys ) {
			$part = $section['part'];
			$wp_customize->selective_refresh->add_partial( $section_id, [
				'selector'            => $section['selector'],
				'settings'            => $keys,
				'container_inclusive' => true,
				'render_callback'     => $part ? static function () use ( $part ) {
					get_template_part( "template-parts/{$part}" );
				} : '__return_false',
			] );
		}
	}
} );

/**
 * Migrasi sekali: salin isi field ACF lama (meta halaman depan) ke theme_mod.
 * Tidak menimpa nilai yang sudah ada di Customizer.
 */
add_action( 'init', function () {
	if ( get_option( 'bira_content_migrated' ) ) {
		return;
	}
	$front = (int) get_option( 'page_on_front' );
	if ( $front ) {
		foreach ( bira_content_schema() as $section ) {
			foreach ( array_keys( bira_content_fields( $section ) ) as $key ) {
				$value = get_post_meta( $front, $key, true );
				if ( is_string( $value ) && '' !== trim( $value ) && '' === (string) get_theme_mod( $key, '' ) ) {
					set_theme_mod( $key, $value );
				}
			}
		}
	}
	update_option( 'bira_content_migrated', 1 );
} );

/**
 * "12,5,9" → "12,5,9" (hanya ID attachment yang valid, urutan dipertahankan).
 */
function bira_sanitize_ids( $value ): string {
	$ids = array_filter( array_map( 'absint', explode( ',', (string) $value ) ) );
	return implode( ',', array_unique( $ids ) );
}

/**
 * Daftar ID gambar dari field gallery.
 */
function bira_gallery( string $key ): array {
	return array_values( array_filter( array_map( 'absint', explode( ',', bira_text( $key ) ) ), 'wp_attachment_is_image' ) );
}

/**
 * Kontrol Customizer untuk banyak gambar sekaligus (pengganti ACF Gallery yang hanya ada di versi Pro).
 * Nilai disimpan sebagai ID dipisah koma; logika pemilih ada di admin/gallery-control.js.
 */
add_action( 'customize_register', function () {
	class Bira_Gallery_Control extends WP_Customize_Control {
		public $type = 'bira_gallery';

		public function render_content() {
			?>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php if ( $this->description ) : ?>
				<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<?php endif; ?>
			<div class="bira-gallery" data-bira-gallery>
				<input type="hidden" <?php $this->link(); ?> value="<?php echo esc_attr( $this->value() ); ?>">
				<ul class="bira-gallery__list">
					<?php foreach ( array_filter( array_map( 'absint', explode( ',', (string) $this->value() ) ) ) as $id ) : ?>
						<li><?php echo wp_get_attachment_image( $id, 'thumbnail' ); ?></li>
					<?php endforeach; ?>
				</ul>
				<button type="button" class="button bira-gallery__select">Select logos</button>
				<button type="button" class="button-link bira-gallery__clear">Remove all</button>
			</div>
			<?php
		}
	}
}, 1 );

add_action( 'customize_controls_enqueue_scripts', function () {
	wp_enqueue_media();
	$file = get_template_directory() . '/admin/gallery-control.js';
	wp_enqueue_script( 'bira-gallery-control', get_template_directory_uri() . '/admin/gallery-control.js', [ 'customize-controls', 'jquery' ], filemtime( $file ), true );
	wp_add_inline_style( 'customize-controls', '
		.bira-gallery__list{display:grid;grid-template-columns:repeat(4,1fr);gap:6px;margin:8px 0;padding:0;list-style:none}
		.bira-gallery__list:empty{display:none}
		.bira-gallery__list li{margin:0;aspect-ratio:1;background:#f0f0f1;border:1px solid #dcdcde;border-radius:3px;display:grid;place-items:center;overflow:hidden}
		.bira-gallery__list img{max-width:90%;max-height:90%;width:auto;height:auto;object-fit:contain}
		.bira-gallery__clear{margin-left:8px;color:#b32d2e}
	' );
} );
