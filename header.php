<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'bg-cream text-ink antialiased' ); ?>>
<?php wp_body_open(); ?>
<a class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-50 focus:rounded focus:bg-white focus:px-4 focus:py-2" href="#main"><?php esc_html_e( 'Skip to content', 'bira' ); ?></a>

<?php
// Menu tanpa lokasi terpasang: section di homepage.
$bira_menu_fallback = static function () {
	$links = [
		'#work'        => __( 'Work', 'bira' ),
		'#services'    => __( 'What we do', 'bira' ),
		'#about'       => __( 'About', 'bira' ),
	];
	echo '<ul class="bira-menu">';
	foreach ( $links as $path => $label ) {
		printf( '<li><a href="%s">%s</a></li>', esc_url( home_url( '/' ) . $path ), esc_html( $label ) );
	}
	echo '</ul>';
};
$bira_menu_args = [
	'theme_location' => 'primary',
	'container'      => false,
	'menu_class'     => 'bira-menu',
	'depth'          => 1,
	'fallback_cb'    => $bira_menu_fallback,
];
$bira_talk_url = home_url( '/' ) . '#cta';
?>

<header class="bira-header sticky top-0 z-40 border-b border-ink/15 bg-cream" data-nav>
	<div class="bira-container flex h-18 items-center justify-between gap-6 md:h-22">
		<a class="bira-logo shrink-0" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
			<?php
			$logo_id = (int) get_theme_mod( 'custom_logo' );
			if ( $logo_id ) {
				echo wp_get_attachment_image( $logo_id, 'medium', false, [ 'class' => 'h-auto w-[106px] md:w-[118px]', 'alt' => '' ] );
			} else {
				echo '<span class="font-display text-2xl font-extrabold uppercase">BIRA</span>';
			}
			?>
		</a>

		<nav class="hidden items-center gap-10 md:flex" aria-label="<?php esc_attr_e( 'Primary', 'bira' ); ?>">
			<?php wp_nav_menu( $bira_menu_args ); ?>
			<?php echo bira_button( __( 'Let’s talk', 'bira' ), $bira_talk_url, 'bira-contact-open' ); ?>
		</nav>

		<button class="flex size-11 items-center justify-center md:hidden" type="button" aria-label="<?php esc_attr_e( 'Open navigation', 'bira' ); ?>" aria-expanded="false" aria-controls="mobile-navigation" data-nav-toggle>
			<svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
		</button>
	</div>

	<nav id="mobile-navigation" class="bira-mobile-nav border-t border-ink/15 md:hidden" aria-label="<?php esc_attr_e( 'Mobile', 'bira' ); ?>" hidden>
		<div class="bira-container flex flex-col items-start gap-2 py-6">
			<?php wp_nav_menu( $bira_menu_args ); ?>
			<?php echo bira_button( __( 'Let’s talk', 'bira' ), $bira_talk_url, 'bira-contact-open mt-4' ); ?>
		</div>
	</nav>
</header>

<main id="main">
