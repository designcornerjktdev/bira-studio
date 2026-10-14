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
			'add_new_item'  => 'Tambah Project',
			'edit_item'     => 'Edit Project',
			'all_items'     => 'Semua Project',
		],
		'public'        => true,
		'has_archive'   => 'work',
		'rewrite'       => [ 'slug' => 'work', 'with_front' => false ],
		'menu_icon'     => 'dashicons-portfolio',
		'menu_position' => 5,
		'show_in_rest'  => true,
		'supports'      => [ 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ],
	] );

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
		'title'    => 'Detail Project',
		'fields'   => [
			$f( 'featured', 'Featured ★', 'true_false', [ 'ui' => 1, 'instructions' => 'Tampilkan di The Good Stuff (homepage).' ] ),
			$f( 'brand', 'Brand', 'text', [ 'placeholder' => 'Relaxa' ] ),
			$f( 'tag', 'Badge', 'text', [ 'placeholder' => 'Sample campaign' ] ),
			$f( 'hook', 'Kalimat besar di gambar', 'textarea', [ 'rows' => 2, 'new_lines' => '', 'instructions' => 'Enter = pindah baris.' ] ),
			$f( 'cats', 'Kategori', 'text', [ 'placeholder' => 'Social / Content / Influence' ] ),
			$f( 'stats', 'Statistik', 'text', [ 'placeholder' => '20M+ Views, 150+ Content pieces', 'instructions' => 'Pisahkan dengan koma.' ] ),
			$f( 'tone', 'Warna kartu (tanpa gambar)', 'select', [ 'choices' => [ 'green' => 'Hijau', 'sand' => 'Pasir', 'ink' => 'Gelap' ], 'default_value' => 'green' ] ),
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
	return array_map( static fn( WP_Post $p ): array => [
		'image' => get_post_thumbnail_id( $p ),
		'brand' => (string) get_post_meta( $p->ID, 'brand', true ),
		'tag'   => (string) get_post_meta( $p->ID, 'tag', true ),
		'hook'  => (string) get_post_meta( $p->ID, 'hook', true ),
		'cats'  => (string) get_post_meta( $p->ID, 'cats', true ),
		'title' => get_the_title( $p ),
		'text'  => get_the_excerpt( $p ),
		'stats' => (string) get_post_meta( $p->ID, 'stats', true ),
		'url'   => get_permalink( $p ),
		'tone'  => get_post_meta( $p->ID, 'tone', true ) ?: 'green',
	], $posts );
}
