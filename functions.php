<?php
/**
 * BIRA — standalone theme (no parent theme).
 */

add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'custom-logo', [ 'height' => 58, 'width' => 128, 'flex-height' => true, 'flex-width' => true ] );
	add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ] );

	register_nav_menus( [
		'primary' => __( 'Primary Menu', 'bira' ),
		'footer'  => __( 'Footer Menu', 'bira' ),
	] );
} );

require_once get_template_directory() . '/inc/template-tags.php';
require_once get_template_directory() . '/inc/customizer.php'; // homepage content (Appearance → Customize → Homepage)
require_once get_template_directory() . '/inc/projects.php';

add_action( 'wp_enqueue_scripts', function () {
	$ver = wp_get_theme()->get( 'Version' );
	wp_enqueue_style( 'bira', get_stylesheet_uri(), [], $ver );
} );

// Flag that JS is active (used by CSS to hide text before animating).
add_action( 'wp_head', function () {
	echo '<script>document.documentElement.classList.add("js")</script>' . "\n";
}, 1 );

// Load the Vite bundle as a script module (WordPress 6.5+).
add_action( 'wp_enqueue_scripts', function () {
	$file = get_stylesheet_directory() . '/assets/dist/main.js';
	if ( file_exists( $file ) ) {
		wp_enqueue_script_module(
			'bira-main',
			get_stylesheet_directory_uri() . '/assets/dist/main.js',
			[],
			filemtime( $file )
		);
	}
} );

// Fonts from the Google Fonts CDN: Hanken Grotesk (grotesque for all UI) + Caveat (handwritten accent).
const BIRA_FONTS_URL = 'https://fonts.googleapis.com/css2?family=Hanken+Grotesk:ital,wght@0,300..900;1,300..900&family=Caveat:wght@500..700&display=swap';

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'bira-fonts', BIRA_FONTS_URL, [], null );
} );

add_filter( 'wp_resource_hints', function ( array $urls, string $relation ): array {
	if ( 'preconnect' === $relation ) {
		$urls[] = 'https://fonts.googleapis.com';
		$urls[] = [ 'href' => 'https://fonts.gstatic.com', 'crossorigin' ];
	}
	return $urls;
}, 10, 2 );

add_action( 'wp_enqueue_scripts', function () {
	$css = get_stylesheet_directory() . '/assets/dist/main.css';
	if ( file_exists( $css ) ) {
		wp_enqueue_style(
			'bira-tailwind',
			get_stylesheet_directory_uri() . '/assets/dist/main.css',
			[],
			filemtime( $css )
		);
	}
} );

/*
 * Animated GIFs: WordPress generates smaller sizes (medium, large, …) containing only the first frame.
 * For GIFs, always use the original file and disable srcset so the animation plays everywhere
 * (hero cards, team photos, projects, etc.).
 */
function bira_is_gif( int $id ): bool {
	return 'image/gif' === get_post_mime_type( $id );
}

add_filter( 'wp_get_attachment_image_src', function ( $image, $id, $size ) {
	if ( $image && 'full' !== $size && bira_is_gif( (int) $id ) ) {
		$full = wp_get_attachment_image_src( $id, 'full' );
		return $full ?: $image;
	}
	return $image;
}, 10, 3 );

add_filter( 'wp_calculate_image_srcset', function ( $sources, $size_array, $src, $meta, $id ) {
	return bira_is_gif( (int) $id ) ? false : $sources;
}, 10, 5 );

// The About page no longer exists (its content is now the #about homepage section): redirect /about there.
add_action( 'template_redirect', function () {
	$path = static fn( string $url ): string => trim( (string) wp_parse_url( $url, PHP_URL_PATH ), '/' );
	if ( $path( home_url( '/about' ) ) === $path( $_SERVER['REQUEST_URI'] ?? '' ) && ( is_404() || is_page( 'about' ) ) ) {
		wp_safe_redirect( home_url( '/#about' ), 301 );
		exit;
	}
} );
