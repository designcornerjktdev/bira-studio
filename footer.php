</main>

<?php
// Footer ala web agency:
// 1. Atas: logo + tagline besar (kiri), ajakan kontak + email besar (kanan).
// 2. Kolom info: logo + Family, Menu, Follow, Office.
// 3. Baris legal: copyright.
$tagline = bira_text( 'footer_tagline', "Born in Indonesia.\nBuilt for the internet." );
$legal   = bira_text( 'footer_legal', 'A little different. On purpose.' );
$family  = bira_text( 'footer_family', 'Bira is part of the ID Ads family, along with our siblings Gili Social and Moyo Technologies.' );
$office  = bira_text( 'footer_office', 'Bira Studio Office' );
$address = bira_text( 'footer_address', "AD Premier Office Park 9th Floor, Jl. TB Simatupang No.5\nJakarta Selatan 12550" );
[ 'email' => $email, 'socials' => $socials ] = bira_contact_links();
// Menu footer: dari Appearance → Menus (lokasi "Footer Menu"); kalau belum diatur, link ke section homepage.
$menu_fallback = [
	'#services' => __( 'Services', 'bira' ),
	'#process'  => __( 'Process', 'bira' ),
	'#work'     => __( 'Work', 'bira' ),
	'#team'     => __( 'Team', 'bira' ),
	'#about'    => __( 'About', 'bira' ),
	'#contact'  => __( 'Contact', 'bira' ), // membuka pop-up kontak
];
?>
<footer class="bira-footer bira-pov-section relative isolate overflow-clip"><?php // latar sand muda, sama dengan section POV ?>
	<?php echo bira_bg_doodles( [ [ 'sparkle', 'top-[22%] right-[22%] size-7 rotate-[10deg]' ] ] ); ?>
	<div class="bira-container">
		<?php // 1. Atas: logo + tagline besar | ajakan kontak + email besar ?>
		<div class="grid gap-10 pt-16 pb-12 md:grid-cols-12 md:items-end md:gap-8 md:pt-24 md:pb-16">
			<div class="md:col-span-7">
				<p class="mt-8 text-[clamp(2rem,4.2vw,3.75rem)] leading-[0.98] font-extrabold tracking-[-0.03em] whitespace-pre-line uppercase"><?php echo esc_html( $tagline ); ?></p>
			</div>
			<?php if ( $email ) : ?>
				<div class="md:col-span-5 md:justify-self-end md:text-right">
					<p class="bira-eyebrow text-green">[ Say hi ]</p>
					<a class="bira-link group mt-3 inline-flex min-h-11 items-center gap-3 text-[clamp(1.25rem,2.2vw,1.75rem)] font-bold tracking-[-0.01em]" href="<?php echo esc_url( $email ); ?>">
						<span class="bira-link__text"><?php echo esc_html( bira_link_label( $email ) ); ?></span>
						<?php echo bira_swipe_arrow( 'size-5' ); ?>
					</a>
				</div>
			<?php endif; ?>
		</div>

		<?php // 2. Kolom info berlabel ?>
		<div class="grid gap-10 border-t border-ink/15 py-10 sm:grid-cols-2 md:grid-cols-12 md:gap-8 md:py-12">
			<?php if ( $family ) : ?>
				<div class="md:col-span-4">
					<a class="bira-logo inline-block -ml-4" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
					<?php
					$logo_id = (int) get_theme_mod( 'custom_logo' );
					if ( $logo_id ) {
						echo wp_get_attachment_image( $logo_id, 'medium', false, [ 'class' => 'h-auto w-[102px]', 'alt' => '' ] );
					} else {
						echo '<span class="font-display text-2xl font-extrabold uppercase">BIRA</span>';
					}
					?>
				</a>
					<p class="max-w-xs text-sm leading-relaxed text-ink/75"><?php echo esc_html( $family ); ?></p>
				</div>
			<?php endif; ?>

			<nav class="bira-footer__menu md:col-span-2 md:pt-4" aria-label="<?php esc_attr_e( 'Footer', 'bira' ); ?>">
				<h2 class="bira-eyebrow mb-4 text-ink/50">Link</h2>
				<?php
				if ( has_nav_menu( 'footer' ) ) {
					wp_nav_menu( [
						'theme_location' => 'footer',
						'container'      => false,
						'menu_class'     => 'grid gap-1',
						'depth'          => 1,
					] );
				} else {
					echo '<ul class="grid gap-1">';
					foreach ( $menu_fallback as $hash => $label ) {
						printf( '<li><a href="%s">%s</a></li>', esc_url( home_url( '/' ) . $hash ), esc_html( $label ) );
					}
					echo '</ul>';
				}
				?>
			</nav>

			<?php if ( $socials ) : ?>
				<div class="md:col-span-3 pt-0 md:pt-4">
					<h2 class="bira-eyebrow mb-4 text-ink/50">Follow</h2>
					<ul class="grid gap-1">
						<?php foreach ( $socials as $name => $href ) : ?>
							<li>
								<a class="bira-link group inline-flex min-h-9 items-center gap-2 text-sm font-bold tracking-[0.12em] uppercase" href="<?php echo esc_url( $href ); ?>"<?php echo str_starts_with( $href, 'http' ) ? ' target="_blank" rel="noopener"' : ''; ?>>
									<span class="bira-link__text"><?php echo esc_html( $name ); ?></span>
									<?php echo bira_swipe_arrow(); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>

			<?php if ( $office || $address ) : ?>
				<div class="md:col-span-3 pt-0 md:pt-4">
					<h2 class="bira-eyebrow mb-4 text-ink/50">Office</h2>
					<address class="text-sm leading-relaxed text-ink/75 not-italic">
						<?php if ( $office ) : ?>
							<strong class="block font-bold text-ink"><?php echo esc_html( $office ); ?></strong>
						<?php endif; ?>
						<?php echo nl2br( esc_html( $address ), false ); ?>
					</address>
				</div>
			<?php endif; ?>
		</div>

		<?php // 3. Baris legal ?>
		<div class="border-t border-ink/15 py-5 text-sm tracking-wider text-ink/55 uppercase">
			<span>&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php echo esc_html( $legal ); ?></span>
		</div>
	</div>
</footer>

<a class="bira-fab" href="#top" data-fab aria-label="<?php esc_attr_e( 'Back to top', 'bira' ); ?>">
	<svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="m5 12 7-7 7 7"/><path d="M12 19V5"/></svg>
</a>

<?php get_template_part( 'template-parts/contact-modal' ); ?>

<?php wp_footer(); ?>
</body>
</html>
