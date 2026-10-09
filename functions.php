<?php
add_action( 'wp_enqueue_scripts', function () {
	$ver = wp_get_theme()->get( 'Version' );
	wp_enqueue_style( 'bira-child', get_stylesheet_uri(), [], $ver );
} );

// Tandai bahwa JS aktif (dipakai CSS untuk menyembunyikan teks sebelum animasi).
add_action( 'wp_head', function () {
	echo '<script>document.documentElement.classList.add("js")</script>' . "\n";
}, 1 );

// Muat bundle Vite sebagai script module (WordPress 6.5+).
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

add_action( 'wp_head', function () {
	$url = get_stylesheet_directory_uri() . '/assets/fonts/Anton-Regular.woff2';
	echo '<link rel="preload" href="' . esc_url( $url ) . '" as="font" type="font/woff2" crossorigin>' . "\n";
}, 2 );

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