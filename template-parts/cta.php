<?php
/**
 * Final CTA — "Got something weird in mind?" (struktur dari prototype).
 */
$status = bira_text( 'cta_status', 'Open for good conversations' );
$title  = bira_text( 'cta_title', "Got something\nWeird in mind?" );
$accent = bira_text( 'cta_accent', 'Weird' );
// Sama seperti headline hero: Enter = pindah baris, kata aksen (kemunculan pertama) jadi hijau.
$title_html = nl2br( bira_accent( $title, $accent, 'text-green' ), false );
$label  = bira_text( 'cta_label', 'Talk to BIRA' );
$url    = bira_text( 'cta_url', '/contact' );
$note   = bira_text( 'cta_note', 'Good.' );
$note_2 = bira_text( 'cta_note_2', 'Let’s make it happen.' );
?>
<section id="cta" class="bira-section relative isolate scroll-mt-24 overflow-hidden" aria-labelledby="cta-title">
	<?php echo bira_bg_doodles( [ [ 'crown', 'top-[14%] right-[12%] size-10 rotate-[10deg]' ], [ 'sparkle', 'bottom-[14%] left-[48%] size-8 rotate-[-12deg]' ] ] ); ?>
	<div class="bira-container relative">
		<p class="flex items-center gap-2 text-sm font-bold tracking-[0.13em] uppercase">
			<span class="bira-cta__pulse size-2 rounded-full bg-green" aria-hidden="true"></span>
			<?php echo esc_html( $status ); ?>
		</p>

		<div class="relative mt-10 flex flex-col items-start justify-between gap-8 md:flex-row md:items-end">
			<h2 id="cta-title" class="bira-h2 max-w-4xl text-[clamp(3.1rem,7vw,7rem)] leading-[0.97] font-extrabold tracking-[-0.02em]">
				<?php echo wp_kses( $title_html, [ 'span' => [ 'class' => [] ], 'br' => [] ] ); ?>
			</h2>
			<a class="bira-cta__circle group" href="<?php echo esc_url( home_url( $url ) ); ?>" data-reveal>
				<?php echo esc_html( $label ); ?>
			</a>
		</div>

		<p class="mt-8 inline-block -rotate-2 font-hand text-[clamp(2rem,3.5vw,2.75rem)] leading-none font-bold" data-reveal><span class="text-green"><?php echo esc_html( $note ); ?></span> <span class="text-ink/55"><?php echo esc_html( $note_2 ); ?></span></p>

		<?php // Panah tangan dari catatan ke tombol bulat, di belakang konten (tidak ikut layout). Ujungnya di kiri tombol (lebar tombol 11rem). ?>
		<svg class="pointer-events-none absolute right-44 -bottom-26 left-120 -z-10 hidden h-60 text-green opacity-[0.28] lg:block" viewBox="-10 -10 800 285" preserveAspectRatio="none" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
			<path vector-effect="non-scaling-stroke" d="M2 152C70 225 170 262 262 260C360 258 440 220 535 140C620 70 700 30 776 6"/>
			<path vector-effect="non-scaling-stroke" d="M732 -4 776 6 746 39"/>
		</svg>
	</div>
</section>
