<?php
/**
 * Pop-up kontak "Let's make some noise." — dibuka oleh tombol "Let's talk" (header) dan
 * "Talk to BIRA" (CTA), lihat src/contact.js. Dimuat di footer.php, jadi ada di semua halaman.
 * Memakai <dialog> bawaan: fokus terkunci di dalam, Esc menutup, latar di belakang tidak bisa diklik.
 */
[ 'email' => $email, 'socials' => $socials ] = bira_contact_links();
$rows = array_filter( [ 'Email' => $email ] + $socials );
?>
<dialog class="bira-contact" id="contact" aria-labelledby="contact-title" data-contact>
	<div class="bira-contact__panel">
		<button type="button" class="bira-contact__close" data-contact-close aria-label="<?php esc_attr_e( 'Close', 'bira' ); ?>">
			<svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true" focusable="false"><path d="M6 6l12 12M18 6 6 18"/></svg>
		</button>

		<p class="bira-eyebrow text-green"><?php echo esc_html( bira_text( 'contact_eyebrow', 'Something good starts here.' ) ); ?></p>
		<h2 id="contact-title" class="bira-h2 bira-h2--static mt-8 text-[clamp(2.5rem,6vw,3.75rem)] leading-[0.95] font-extrabold tracking-[-0.03em]"><?php echo nl2br( esc_html( rtrim( bira_text( 'contact_title', "Let’s make\nsome noise" ), '. ' ) ), false ); ?><span class="text-green">.</span></h2>
		<p class="mt-6 text-base leading-relaxed font-semibold text-ink/65"><?php echo esc_html( bira_text( 'contact_intro', 'Have a brand, an idea, or a beautifully weird brief? We’d love to hear it.' ) ); ?></p>

		<?php if ( $rows ) : ?>
			<ul class="mt-8 border-t border-ink/15">
				<?php foreach ( $rows as $label => $href ) : ?>
					<li class="border-b border-ink/15">
						<a class="group flex min-h-14 items-center justify-between gap-4 py-3 transition-colors hover:text-green" href="<?php echo esc_url( $href ); ?>"<?php echo str_starts_with( $href, 'http' ) ? ' target="_blank" rel="noopener"' : ''; ?>>
							<span class="text-sm font-bold tracking-[0.08em] uppercase"><?php echo esc_html( $label ); ?></span>
							<span class="flex min-w-0 items-center gap-3 text-sm text-ink/55 group-hover:text-green">
								<span class="truncate"><?php echo esc_html( bira_link_label( $href ) ); ?></span>
								<?php echo bira_swipe_arrow(); ?>
							</span>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php // Sama dengan primary button (bira_button()), tapi <button> karena menutup pop-up. ?>
		<button type="button" class="bira-btn mt-10" data-contact-close>
			<span class="bira-btn__dot" aria-hidden="true"></span>
			<span class="bira-btn__label"><?php echo esc_html( bira_text( 'contact_back', 'Back to exploring' ) ); ?></span>
			<?php echo bira_doodle( 'next', 'bira-btn__arrow' ); ?>
		</button>
	</div>
</dialog>
