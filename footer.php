</main>

<?php
// Footer (struktur dari prototype): logo, tagline, link sosial, back to top.
$tagline = bira_text( 'footer_tagline', "Born in Indonesia.\nBuilt for the internet." );
$legal   = bira_text( 'footer_legal', 'A little different. On purpose.' );
$socials = array_filter( [
	'Email'     => bira_text( 'footer_email', 'mailto:hello@birastudio.co' ),
	'Instagram' => bira_text( 'footer_instagram', 'https://instagram.com/' ),
	'TikTok'    => bira_text( 'footer_tiktok', 'https://tiktok.com/' ),
] );
?>
<footer class="bira-footer relative isolate overflow-clip border-t border-ink/15">
	<?php echo bira_bg_doodles( [ [ 'sparkle', 'top-[22%] right-[22%] size-7 rotate-[10deg]' ] ] ); ?>
	<div class="bira-container">
		<div class="flex flex-col justify-between gap-9 py-10 md:flex-row md:items-center">
			<a class="bira-logo shrink-0" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
				<?php
				$logo_id = (int) get_theme_mod( 'custom_logo' );
				if ( $logo_id ) {
					echo wp_get_attachment_image( $logo_id, 'medium', false, [ 'class' => 'h-auto w-[102px]', 'alt' => '' ] );
				} else {
					echo '<span class="font-display text-2xl font-extrabold uppercase">BIRA</span>';
				}
				?>
			</a>

			<p class="text-sm leading-relaxed font-bold tracking-[0.1em] whitespace-pre-line uppercase"><?php echo esc_html( $tagline ); ?></p>

			<ul class="flex flex-wrap gap-6">
				<?php foreach ( $socials as $name => $href ) : ?>
					<li>
						<a class="bira-link inline-flex min-h-11 items-center gap-2 text-sm font-bold tracking-[0.12em] uppercase" href="<?php echo esc_url( $href ); ?>"<?php echo str_starts_with( $href, 'http' ) ? ' target="_blank" rel="noopener"' : ''; ?>>
							<span class="bira-link__text"><?php echo esc_html( $name ); ?></span>
							<?php echo bira_doodle( 'arrow', 'bira-link__arrow size-3.5' ); ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>

		</div>

		<div class="flex flex-col justify-between gap-2 border-t border-ink/15 py-5 text-sm tracking-wider text-ink/55 uppercase sm:flex-row">
			<span>&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php echo esc_html( $legal ); ?></span>
			<?php if ( has_nav_menu( 'footer' ) ) : ?>
				<nav aria-label="<?php esc_attr_e( 'Footer', 'bira' ); ?>">
					<?php
					wp_nav_menu( [
						'theme_location' => 'footer',
						'container'      => false,
						'menu_class'     => 'flex gap-6',
						'depth'          => 1,
					] );
					?>
				</nav>
			<?php endif; ?>
		</div>
	</div>
</footer>

<a class="bira-fab" href="#top" data-fab aria-label="<?php esc_attr_e( 'Back to top', 'bira' ); ?>">
	<svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="m5 12 7-7 7 7"/><path d="M12 19V5"/></svg>
</a>

<?php wp_footer(); ?>
</body>
</html>
