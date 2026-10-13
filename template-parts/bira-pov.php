<?php
/**
 * BIRA POV — manifesto "A friendly reminder" (struktur dari prototype).
 */
$eyebrow = bira_text( 'pov_eyebrow', 'A friendly reminder' );
$line_1  = bira_text( 'pov_line_1', 'Don’t interrupt' );
$line_2  = bira_text( 'pov_line_2', 'the culture.' );
$accent  = bira_text( 'pov_accent', 'Join it.' );
$body    = bira_text( 'pov_body', "People don’t open social media looking for ads.\n\nSo we make brands feel like part of what they’re already watching, sharing and talking about." );
?>
<section id="bira-pov" class="relative flex min-h-[660px] items-center overflow-hidden bg-green py-24 text-ink md:min-h-[820px] md:py-32" aria-labelledby="pov-title">
	<svg class="pointer-events-none absolute -top-1/3 -right-1/4 w-full rotate-12 text-white/30" viewBox="0 0 680 640" fill="none" aria-hidden="true" focusable="false">
		<path d="M-100 110C100-45 660 15 550 190C455 340 48 167 103 360C151 527 606 315 701 557" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
	</svg>

	<div class="bira-container relative">
		<span class="bira-eyebrow mb-12 text-white">[ <?php echo esc_html( $eyebrow ); ?> ]</span>
		<h2 id="pov-title" data-split="loop" class="bira-h2 text-[clamp(3.2rem,8.6vw,8.5rem)] leading-[0.98] font-extrabold tracking-[-0.02em]">
			<?php echo esc_html( $line_1 ); ?><br>
			<?php echo esc_html( $line_2 ); ?><br>
			<span class="inline-flex items-center gap-5 text-white md:gap-9">
				<?php echo esc_html( $accent ); ?>
				<?php echo bira_doodle( 'star', 'bira-drift size-14 md:size-24 [&_path]:stroke-[1.3]' ); ?>
			</span>
		</h2>
		<div class="mt-12 flex justify-end">
			<p class="max-w-md text-sm leading-[1.8] whitespace-pre-line text-white/90 md:text-base" data-reveal><?php echo esc_html( $body ); ?></p>
		</div>
	</div>
</section>
