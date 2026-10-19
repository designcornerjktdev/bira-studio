<?php
/**
 * Post type Project (menu "Projects" di admin). URL: /work/{slug}, arsip: /work.
 * Project yang dicentang "Featured" tampil di section The Good Stuff di homepage.
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
		'has_archive'   => 'work',
		'rewrite'       => [ 'slug' => 'work', 'with_front' => false ],
		'menu_icon'     => 'dashicons-portfolio',
		'menu_position' => 5,
		'show_in_rest'  => true,
		'supports'      => [ 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ],
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
			'separate_items_with_commas' => 'Separate categories with commas. Each category becomes a filter on the Work page.',
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

	// Sekali: pindahkan isi field teks lama "Social / Content / …" ke kategori.
	if ( ! get_option( 'bira_project_cats_migrated' ) ) {
		foreach ( get_posts( [ 'post_type' => 'bira_project', 'post_status' => 'any', 'posts_per_page' => -1, 'fields' => 'ids' ] ) as $id ) {
			$names = array_values( bira_project_cats( (string) get_post_meta( $id, 'cats', true ) ) );
			if ( $names && ! has_term( '', 'bira_project_cat', $id ) ) {
				wp_set_object_terms( $id, $names, 'bira_project_cat' );
			}
		}
		update_option( 'bira_project_cats_migrated', 1 );
	}

	// Flush rewrite sekali setiap versi ini berubah, supaya /work langsung jalan.
	if ( get_option( 'bira_project_rewrite' ) !== '1' ) {
		flush_rewrite_rules( false );
		update_option( 'bira_project_rewrite', '1' );
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
			$f( 'featured', 'Featured ★', 'true_false', [ 'ui' => 1, 'instructions' => 'Show in The Good Stuff on the homepage.' ] ),
			$f( 'brand', 'Brand', 'text', [ 'placeholder' => 'Relaxa', 'instructions' => 'Shown at the top left of the card.' ] ),
			$f( 'tag', 'Badge', 'text', [ 'placeholder' => 'Sample campaign', 'instructions' => 'Small pill at the top right of the card.' ] ),
			$f( 'hook', 'Hook (big text on image)', 'textarea', [ 'rows' => 2, 'new_lines' => '', 'instructions' => 'Press Enter for a new line.' ] ),
			$f( 'stats', 'Stats', 'text', [ 'placeholder' => 'e.g. 20M+ Views', 'instructions' => 'Type a stat, then press Enter or comma to add it. Click × to remove.', 'wrapper' => [ 'class' => 'bira-stats-field' ] ] ),
		],
		'location' => [ [ [ 'param' => 'post_type', 'operator' => '==', 'value' => 'bira_project' ] ] ],
		'position' => 'side',
	] );
} );

// Kolom ★ di daftar Projects.
add_filter( 'manage_bira_project_posts_columns', function ( array $cols ): array {
	return array_slice( $cols, 0, 1, true ) + [ 'bira_featured' => '★' ] + $cols;
} );
add_action( 'manage_bira_project_posts_custom_column', function ( string $col, int $id ) {
	if ( 'bira_featured' === $col ) {
		echo get_post_meta( $id, 'featured', true ) ? '<span style="color:#1f7a3a;font-size:18px" title="Featured">★</span>' : '<span style="color:#ccc;font-size:18px">☆</span>';
	}
}, 10, 2 );
add_action( 'admin_head', function () {
	echo '<style>.column-bira_featured{width:2.5em;text-align:center}</style>';
} );

/**
 * Project featured untuk homepage, dalam format kartu The Good Stuff.
 */
function bira_featured_projects(): array {
	$posts = get_posts( [
		'post_type'      => 'bira_project',
		'posts_per_page' => -1,
		'meta_key'       => 'featured',
		'meta_value'     => '1',
		'orderby'        => [ 'menu_order' => 'ASC', 'date' => 'DESC' ],
	] );
	return array_map( 'bira_project_data', $posts );
}

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

// Arsip /work menampilkan semua project sekaligus (filter kategori jalan di browser).
add_action( 'pre_get_posts', function ( WP_Query $q ) {
	if ( ! is_admin() && $q->is_main_query() && $q->is_post_type_archive( 'bira_project' ) ) {
		$q->set( 'posts_per_page', -1 );
		$q->set( 'orderby', [ 'menu_order' => 'ASC', 'date' => 'DESC' ] );
	}
} );

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
