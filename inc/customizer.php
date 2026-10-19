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
 * Skema: section => [ title, partial selector, fields ].
 * Field: key => [ label, type, extra ]. Type: text | textarea | url | image.
 * Item berulang: 'repeat' => [ prefix, label, jumlah, [ sub => [ label, type ] ] ].
 */
function bira_content_schema(): array {
	return [
		'hero'    => [
			'title'    => 'Hero',
			'selector' => '.bira-hero',
			'part'     => 'hero',
			'fields'   => [
				'hero_headline'    => [ 'Headline', 'textarea', [ 'placeholder' => "We make\nbrands part\nof the\nconversation.", 'desc' => 'Press Enter for a new line in the headline.' ] ],
				'hero_accent_word' => [ 'Accent word (green)', 'text', [ 'placeholder' => 'conversation', 'desc' => 'The word in the headline shown in green.' ] ],
				'hero_lead'        => [ 'Lead sentence (bold)', 'text', [ 'placeholder' => 'Social, content & influence from Indonesia.', 'desc' => 'Shown in bold above the description.' ] ],
				'hero_description' => [ 'Description', 'textarea', [ 'placeholder' => 'Built for brands that want people to actually talk about them.' ] ],
				'hero_cta_label'   => [ 'Primary CTA — label', 'text', [ 'placeholder' => 'See our work' ] ],
				'hero_cta_url'     => [ 'Primary CTA — URL', 'text', [ 'placeholder' => '#made-for-the-feed' ] ],
				'hero_cta2_label'  => [ 'Secondary CTA — label', 'text', [ 'placeholder' => 'Let’s make something' ] ],
				'hero_cta2_url'    => [ 'Secondary CTA — URL', 'text', [ 'placeholder' => '#contact' ] ],
				'hero_symbol'      => [ 'BIRA symbol (mockup background)', 'image', [ 'desc' => 'Transparent PNG. Leave empty to use the outline frame.' ] ],
			],
			'repeat'   => [ 'hero_post', 'Mockup card', 2, [
				'image'   => [ 'Image', 'image', [ 'desc' => 'A ratio close to 4:3 or square works best.' ] ],
				'handle'  => [ 'Handle', 'text', [ 'placeholder' => '@madeforthefeed' ] ],
				'caption' => [ 'Caption', 'text', [ 'desc' => 'One line; longer text is cut off with “…”.' ] ],
			] ],
		],
		'clients' => [
			'title'    => 'Clients',
			'selector' => '.bira-clients',
			'part'     => 'clients',
			'fields'   => [
				'clients_title' => [ 'Strip title', 'text', [ 'placeholder' => 'Brands we’ve made some noise with' ] ],
				'clients_list'  => [ 'Brand names', 'textarea', [ 'placeholder' => 'Relaxa, ABC, Your Brand, Next Up, Watch This Space', 'desc' => 'Separate brands with commas. Each brand gets an alternating text style automatically.' ] ],
			],
		],
		'work'    => [
			'title'    => 'The Good Stuff',
			'desc'     => 'Projects are managed in the Projects menu. Projects marked Featured ★ appear here.',
			'selector' => '#selected-work',
			'part'     => 'selected-work',
			'fields'   => [
				'work_eyebrow' => [ 'Eyebrow', 'text', [ 'placeholder' => 'The good stuff' ] ],
				'work_title'   => [ 'Title', 'text', [ 'placeholder' => 'Stuff we’re proud of' ] ],
				'work_all_url' => [ 'URL "All work"', 'text', [ 'placeholder' => '/work' ] ],
			],
		],
		'wwd'     => [
			'title'    => 'What We Do',
			'selector' => '#what-we-do',
			'part'     => 'what-we-do',
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
		'hww'     => [
			'title'    => 'How We Work',
			'selector' => '#how-we-work',
			'part'     => 'how-we-work',
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
		'feed'    => [
			'title'    => 'Made for the Feed',
			'selector' => '#made-for-the-feed',
			'part'     => 'made-for-the-feed',
			'fields'   => [
				'feed_eyebrow' => [ 'Eyebrow', 'text', [ 'placeholder' => 'Less skip. More send.' ] ],
				'feed_intro'   => [ 'Intro', 'textarea' ],
				'feed_sticker' => [ 'Round sticker', 'textarea', [ 'placeholder' => "Less polished.\nMore human." ] ],
				'feed_comment' => [ 'Comment bubble', 'text', [ 'placeholder' => '“this lives in my head rent free”' ] ],
			],
			'repeat'   => [ 'feed_item', 'Card', 4, [
				'image' => [ 'Image', 'image' ],
				'label' => [ 'Label', 'text' ],
				'title' => [ 'Title', 'text' ],
				'text'  => [ 'Hover text', 'text' ],
			] ],
		],
		'pov'     => [
			'title'    => 'BIRA POV',
			'selector' => '#bira-pov',
			'part'     => 'bira-pov',
			'fields'   => [
				'pov_eyebrow' => [ 'Eyebrow', 'text', [ 'placeholder' => 'A friendly reminder' ] ],
				'pov_title'   => [ 'Headline', 'textarea', [ 'placeholder' => "Don’t interrupt\nthe culture.\nJoin it.", 'desc' => 'Press Enter for a new line in the headline.' ] ],
				'pov_accent'  => [ 'Accent word (green)', 'text', [ 'placeholder' => 'Join it.', 'desc' => 'The word or phrase in the headline shown in green.' ] ],
				'pov_body'    => [ 'Supporting text', 'textarea' ],
			],
		],
		'people'  => [
			'title'    => 'Internet People',
			'selector' => '#internet-people',
			'part'     => 'internet-people',
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
			'title'    => 'Final CTA',
			'selector' => '#contact',
			'part'     => 'final-cta',
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

	$priority = 0;
	foreach ( bira_content_schema() as $id => $section ) {
		$section_id = "bira_home_{$id}";
		$wp_customize->add_section( $section_id, [ 'title' => $section['title'], 'description' => $section['desc'] ?? '', 'panel' => 'bira_home', 'priority' => $priority += 10 ] );

		$keys = [];
		foreach ( bira_content_fields( $section ) as $key => $def ) {
			[ $label, $type ] = $def;
			$extra = $def[2] ?? [];

			$sanitize = [
				'textarea' => 'sanitize_textarea_field',
				'url'      => 'esc_url_raw',
				'image'    => 'absint',
			][ $type ] ?? 'sanitize_text_field';

			$wp_customize->add_setting( $key, [ 'default' => '', 'sanitize_callback' => $sanitize ] );
			$keys[] = $key;

			$args = [ 'label' => $label, 'description' => $extra['desc'] ?? '', 'section' => $section_id ];
			if ( 'image' === $type ) {
				$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, $key, $args + [ 'mime_type' => 'image' ] ) );
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
