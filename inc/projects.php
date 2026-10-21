<?php
/**
 * Post type Project (menu "Projects" di admin). URL detail: /work/{slug}.
 * Semua project tampil di section Work di homepage (#work), dimuat bertahap (lazy load).
 * Tidak ada halaman arsip: /work dialihkan ke /#work.
 */

add_action( 'init', function () {
	register_post_type( 'bira_project', [
		'labels'        => [
			'name'          => 'Projects',
			'singular_name' => 'Project',
			'menu_name'             => 'Projects',
			'add_new'               => 'Add Project',
			'add_new_item'          => 'Add New Project',
			'new_item'              => 'New Project',
			'edit_item'             => 'Edit Project',
			'view_item'             => 'View Project',
			'view_items'            => 'View Projects',
			'all_items'             => 'All Projects',
			'search_items'          => 'Search Projects',
			'not_found'             => 'No projects found.',
			'not_found_in_trash'    => 'No projects found in Trash.',
			'archives'              => 'Project Archives',
			'featured_image'        => 'Card Image',
			'set_featured_image'    => 'Set card image',
			'remove_featured_image' => 'Remove card image',
			'use_featured_image'    => 'Use as card image',
			'item_published'        => 'Project published.',
			'item_updated'          => 'Project updated.',
		],
		'public'        => true,
		'has_archive'   => false, // daftar project ada di section Work homepage
		'rewrite'       => [ 'slug' => 'work', 'with_front' => false ],
		'menu_icon'     => 'dashicons-portfolio',
		'menu_position' => 5,
		'show_in_rest'  => true,
		'supports'      => [ 'title', 'excerpt', 'thumbnail', 'page-attributes' ], // tanpa editor: isi halaman detail lewat field Case Study Page
	] );

	// Kategori project bergaya tag: pilih yang sudah ada (autocomplete / "most used") atau ketik baru langsung.
	register_taxonomy( 'bira_project_cat', 'bira_project', [
		'labels'            => [
			'name'                       => 'Categories',
			'singular_name'              => 'Category',
			'search_items'               => 'Search Categories',
			'popular_items'              => 'Popular Categories',
			'all_items'                  => 'All Categories',
			'edit_item'                  => 'Edit Category',
			'update_item'                => 'Update Category',
			'add_new_item'               => 'Add New Category',
			'new_item_name'              => 'New Category Name',
			'separate_items_with_commas' => 'Separate categories with commas. Shown on the project card and detail page.',
			'add_or_remove_items'        => 'Add or remove categories',
			'choose_from_most_used'      => 'Choose from the most used categories',
			'not_found'                  => 'No categories found.',
			'menu_name'                  => 'Categories',
		],
		'public'            => false,
		'show_ui'           => true,
		'show_in_rest'      => true, // tampil di sidebar editor blok
		'show_admin_column' => true,
		'hierarchical'      => false,
		'sort'              => true, // urutan kategori mengikuti urutan saat diisi
		'rewrite'           => false,
	] );

	// Flush rewrite sekali setiap versi ini berubah (2 = arsip /work dihapus).
	if ( get_option( 'bira_project_rewrite' ) !== '2' ) {
		flush_rewrite_rules( false );
		update_option( 'bira_project_rewrite', '2' );
	}
} );

add_action( 'acf/include_fields', function () {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}
	$f = static fn( string $name, string $label, string $type = 'text', array $extra = [] ): array => array_merge( [
		'key'   => 'field_bira_project_' . $name,
		'name'  => $name,
		'label' => $label,
		'type'  => $type,
	], $extra );

	acf_add_local_field_group( [
		'key'      => 'group_bira_project',
		'title'    => 'Project Details',
		'fields'   => [
			$f( 'brand', 'Brand', 'text', [ 'placeholder' => 'Relaxa', 'instructions' => 'Shown at the top left of the card.' ] ),
			$f( 'tag', 'Badge', 'text', [ 'placeholder' => 'Sample campaign', 'instructions' => 'Small pill at the top right of the card.' ] ),
			$f( 'hook', 'Hook (big text on image)', 'textarea', [ 'rows' => 2, 'new_lines' => '', 'instructions' => 'Press Enter for a new line.' ] ),
			$f( 'stats', 'Stats', 'text', [ 'placeholder' => 'e.g. 20M+ Views', 'instructions' => 'Type a stat, then press Enter or comma to add it. Click × to remove.', 'wrapper' => [ 'class' => 'bira-stats-field' ] ] ),
		],
		'location' => [ [ [ 'param' => 'post_type', 'operator' => '==', 'value' => 'bira_project' ] ] ],
		'position' => 'side',
	] );

	// Isi halaman detail /work/{slug} (single-bira_project.php). Bagian yang kosong tidak ditampilkan.
	$tab = static fn( string $name, string $label ): array => [ 'key' => 'field_bira_project_tab_' . $name, 'label' => $label, 'type' => 'tab', 'placement' => 'top' ];
	acf_add_local_field_group( [
		'key'      => 'group_bira_project_case',
		'title'    => 'Case Study Page',
		'fields'   => [
			$tab( 'intro', 'Intro' ),
			$f( 'lede', 'Tagline', 'text', [ 'placeholder' => 'A little candy. A lot of conversation.', 'instructions' => 'Short line under the title.' ] ),
			$f( 'chips', 'Approach tags', 'text', [ 'placeholder' => 'e.g. Social-first', 'instructions' => 'Type a tag, then press Enter or comma to add it.', 'wrapper' => [ 'class' => 'bira-stats-field' ] ] ),
			$f( 'note', 'Disclaimer (optional)', 'textarea', [ 'rows' => 2, 'instructions' => 'Small note with a green line, e.g. that imagery is a placeholder.' ] ),

			$tab( 'story', 'Story' ),
			$f( 'challenge', '01 — The Challenge', 'textarea', [ 'rows' => 3 ] ),
			$f( 'insight', '02 — The Insight', 'textarea', [ 'rows' => 3 ] ),
			$f( 'idea', '03 — The Idea', 'textarea', [ 'rows' => 3, 'instructions' => 'Shown big on a tinted band.' ] ),

			$tab( 'execution', 'Execution' ),
			$f( 'execution_title', '04 — Execution title', 'textarea', [ 'rows' => 2, 'new_lines' => '', 'placeholder' => "Made to live\nin the feed.", 'instructions' => 'Press Enter for a new line.' ] ),
			$f( 'execution', '04 — Execution text', 'textarea', [ 'rows' => 3 ] ),
			$f( 'content', '05 — The Content', 'textarea', [ 'rows' => 3 ] ),
			$f( 'content_image', 'Supporting image', 'image', [ 'return_format' => 'id', 'preview_size' => 'medium', 'instructions' => 'Portrait works best (about 7:8).' ] ),
			$f( 'people', '06 — The People', 'textarea', [ 'rows' => 3 ] ),

			$tab( 'results', 'Results' ),
			[ 'key' => 'field_bira_project_results_msg', 'label' => 'Numbers', 'type' => 'message', 'message' => 'The big numbers come from <strong>Stats</strong> in Project Details.' ],
			$f( 'results_note', 'Results note (optional)', 'textarea', [ 'rows' => 2, 'instructions' => 'Small print under the numbers.' ] ),
		],
		'location' => [ [ [ 'param' => 'post_type', 'operator' => '==', 'value' => 'bira_project' ] ] ],
		'position' => 'normal',
	] );
} );

/** Jumlah kartu per muatan di section Work (awal halaman dan setiap lazy load). */
const BIRA_WORK_BATCH = 3;

/**
 * Sebagian project untuk section Work, urut Order lalu terbaru.
 * Hasil: [ 'items' => data kartu, 'total' => jumlah semua project ].
 */
function bira_projects( int $offset, int $count ): array {
	$q = new WP_Query( [
		'post_type'      => 'bira_project',
		'post_status'    => 'publish',
		'posts_per_page' => $count,
		'offset'         => $offset,
		'orderby'        => [ 'menu_order' => 'ASC', 'date' => 'DESC' ],
	] );
	return [ 'items' => array_map( 'bira_project_data', $q->posts ), 'total' => (int) $q->found_posts ];
}

/**
 * HTML satu kartu di baris Work (<li> + kartu). Dipakai template dan endpoint lazy load.
 */
function bira_work_item_html( array $project ): string {
	ob_start();
	echo '<li class="bira-work__item">';
	get_template_part( 'template-parts/project-card', null, [ 'project' => $project ] );
	echo '</li>';
	return (string) ob_get_clean();
}

// Lazy load: GET /wp-json/bira/v1/work?offset=3 → kartu berikutnya (HTML) + apakah masih ada sisa.
add_action( 'rest_api_init', function () {
	register_rest_route( 'bira/v1', '/work', [
		'methods'             => 'GET',
		'permission_callback' => '__return_true',
		'args'                => [ 'offset' => [ 'type' => 'integer', 'minimum' => 0, 'default' => 0 ] ],
		'callback'            => function ( WP_REST_Request $req ) {
			$offset = (int) $req['offset'];
			$batch  = bira_projects( $offset, BIRA_WORK_BATCH );
			return [
				'html' => implode( '', array_map( 'bira_work_item_html', $batch['items'] ) ),
				'next' => $offset + count( $batch['items'] ) < $batch['total'] ? $offset + count( $batch['items'] ) : null,
			];
		},
	] );
} );

// Arsip lama /work sudah tidak ada: alihkan ke section Work di homepage.
add_action( 'template_redirect', function () {
	$path = static fn( string $url ): string => trim( (string) wp_parse_url( $url, PHP_URL_PATH ), '/' );
	// Dibandingkan dengan path home_url('/work'), jadi tetap jalan bila WordPress di subfolder (mis. /bira/work).
	if ( is_404() && $path( home_url( '/work' ) ) === $path( $_SERVER['REQUEST_URI'] ?? '' ) ) {
		wp_safe_redirect( home_url( '/#work' ), 301 );
		exit;
	}
} );

/**
 * Data satu project dalam format kartu (template-parts/project-card.php).
 */
function bira_project_data( WP_Post $p ): array {
	$cats = wp_get_object_terms( $p->ID, 'bira_project_cat', [ 'orderby' => 'term_order', 'fields' => 'names' ] );
	return [
		'image' => get_post_thumbnail_id( $p ),
		'brand' => (string) get_post_meta( $p->ID, 'brand', true ),
		'tag'   => (string) get_post_meta( $p->ID, 'tag', true ),
		'hook'  => (string) get_post_meta( $p->ID, 'hook', true ),
		'cats'  => is_wp_error( $cats ) ? '' : implode( ' / ', $cats ), // "Social / Content", sama seperti kartu contoh
		'title' => get_the_title( $p ),
		'text'  => get_the_excerpt( $p ),
		'stats' => (string) get_post_meta( $p->ID, 'stats', true ),
		'url'   => get_permalink( $p ),
	];
}

/**
 * Kategori dari teks "Social / Content / Influence" → [ 'social' => 'Social', ... ].
 */
function bira_project_cats( string $cats ): array {
	$out = [];
	foreach ( array_filter( array_map( 'trim', explode( '/', $cats ) ) ) as $cat ) {
		$out[ sanitize_title( $cat ) ] = $cat;
	}
	return $out;
}

// Field Stats sebagai tag input (admin/stats-tags.js), hanya di layar edit Project.
add_action( 'admin_enqueue_scripts', function ( string $hook ) {
	if ( ! in_array( $hook, [ 'post.php', 'post-new.php' ], true ) || 'bira_project' !== get_current_screen()->post_type ) {
		return;
	}
	$file = get_template_directory() . '/admin/stats-tags.js';
	wp_enqueue_script( 'bira-stats-tags', get_template_directory_uri() . '/admin/stats-tags.js', [], filemtime( $file ), true );
	wp_register_style( 'bira-stats-tags', false );
	wp_enqueue_style( 'bira-stats-tags' );
	wp_add_inline_style( 'bira-stats-tags', '
		.bira-tags{display:flex;flex-wrap:wrap;gap:6px;align-items:center;min-height:32px;padding:4px 6px;border:1px solid #8c8f94;border-radius:4px;background:#fff;cursor:text}
		.bira-tags:focus-within{border-color:#2271b1;box-shadow:0 0 0 1px #2271b1}
		.bira-tags__list{display:contents}
		.bira-tags__tag{display:inline-flex;align-items:center;gap:2px;padding:2px 2px 2px 8px;border-radius:2px;background:#f0f0f1;font-size:12px;line-height:20px}
		.bira-tags__remove{border:0;background:none;padding:0 6px;font-size:16px;line-height:1;color:#757575;cursor:pointer}
		.bira-tags__remove:hover{color:#d63638}
		.bira-tags .bira-tags__entry{flex:1;min-width:120px;border:0!important;box-shadow:none!important;padding:2px!important;min-height:24px;background:transparent}
	' );
} );

/**
 * Project berikutnya (urutan sama dengan section Work), kembali ke awal setelah yang terakhir.
 */
function bira_next_project( int $id ): ?WP_Post {
	$ids = get_posts( [
		'post_type'      => 'bira_project',
		'posts_per_page' => -1,
		'fields'         => 'ids',
		'orderby'        => [ 'menu_order' => 'ASC', 'date' => 'DESC' ],
	] );
	$i = array_search( $id, $ids, true );
	if ( false === $i || count( $ids ) < 2 ) {
		return null;
	}
	return get_post( $ids[ ( $i + 1 ) % count( $ids ) ] );
}
