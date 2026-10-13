<?php
/**
 * Final CTA — "Got something weird in mind?" (struktur dari prototype).
 */
$status = bira_text( 'cta_status', 'Open for good conversations' );
$line_1 = bira_text( 'cta_line_1', 'Got something' );
$accent = bira_text( 'cta_accent', 'Weird' );
$line_2 = bira_text( 'cta_line_2', 'in mind?' );
$label  = bira_text( 'cta_label', 'Talk to BIRA' );
$url    = bira_text( 'cta_url', '/contact' );
$note   = bira_text( 'cta_note', 'Good.' );
$note_2 = bira_text( 'cta_note_2', 'Let’s make it happen.' );
?>
<section id="contact" class="bira-section relative isolate scroll-mt-24 overflow-hidden" aria-labelledby="cta-title">
	<div class="bira-container relative">
		<p class="flex items-center gap-2 text-[0.6875rem] font-bold tracking-[0.13em] uppercase">
			<span class="bira-cta__pulse size-2 rounded-full bg-green" aria-hidden="true"></span>
			<?php echo esc_html( $status ); ?>
		</p>

		<div class="relative mt-10 flex flex-col items-start justify-between gap-8 md:flex-row md:items-end">
			<h2 id="cta-title" class="bira-h2 max-w-4xl text-[clamp(3.1rem,7vw,7rem)] leading-[0.97] font-extrabold tracking-[-0.02em]">
				<?php echo esc_html( $line_1 ); ?><br>
				<span class="text-green"><?php echo esc_html( $accent ); ?></span> <?php echo esc_html( $line_2 ); ?>
			</h2>
			<a class="bira-cta__circle group" href="<?php echo esc_url( home_url( $url ) ); ?>" data-reveal>
				<?php echo esc_html( $label ); ?>
				<?php echo bira_doodle( 'arrow', 'size-4 transition-transform duration-300 group-hover:translate-x-0.5 group-hover:-translate-y-0.5' ); ?>
			</a>
		</div>

		<p class="mt-8 inline-block -rotate-2 font-hand text-[clamp(2rem,3.5vw,2.75rem)] leading-none font-bold" data-reveal><span class="text-green"><?php echo esc_html( $note ); ?></span> <span class="text-ink/55"><?php echo esc_html( $note_2 ); ?></span></p>
	</div>
</section>
