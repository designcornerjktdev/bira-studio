<?php
/**
 * BIRA POV — manifesto "A friendly reminder" (structure from the prototype).
 */
$eyebrow = bira_text( 'pov_eyebrow', 'A friendly reminder' );
$title   = bira_text( 'pov_title', "Don’t interrupt\nthe culture.\nJoin it." );
$accent  = bira_text( 'pov_accent', 'Join it.' );
// Same as the hero headline: Enter = line break, the accent word (first occurrence) turns green.
$title_html = nl2br( bira_accent( $title, $accent, 'text-green' ), false );
$body    = bira_text( 'pov_body', "Nobody opens TikTok thinking ‘omg I hope I see an ad today.’ so we make brands show up like part of the feed, not a pop-up in it." );
?>
<section id="pov" class="relative isolate flex min-h-[660px] items-center overflow-hidden bira-pov-section py-24 text-ink md:min-h-[820px] md:py-32" aria-labelledby="pov-title">
	<?php echo bira_bg_doodles( [ [ 'diamond', 'bottom-[10%] left-[6%] size-11 rotate-[8deg]' ], [ 'sparkle', 'top-[18%] right-[30%] size-8' ] ], 'text-green' ); ?>
	<svg class="pointer-events-none absolute -top-1/3 -right-1/4 w-full rotate-12 text-ink/15" viewBox="0 0 680 640" fill="none" aria-hidden="true" focusable="false">
		<path d="M-100 110C100-45 660 15 550 190C455 340 48 167 103 360C151 527 606 315 701 557" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
	</svg>

	<div class="bira-container relative">
		<span class="bira-eyebrow mb-12 text-green">[ <?php echo esc_html( $eyebrow ); ?> ]</span>
		<h2 id="pov-title" data-split="loop" class="bira-h2 text-[clamp(3.2rem,8.6vw,8.5rem)] leading-[0.98] font-extrabold tracking-[-0.02em]">
			<?php echo wp_kses( $title_html, [ 'span' => [ 'class' => [] ], 'br' => [] ] ); ?>
			<span class="ml-5 inline-block align-middle text-green md:ml-9"><?php echo bira_doodle( 'star', 'bira-drift size-14 md:size-24 [&_path]:stroke-[1.3]' ); ?></span>
		</h2>
		<div class="mt-12 flex justify-end">
			<p class="max-w-md text-sm leading-[1.8] whitespace-pre-line text-ink/70 md:text-base" data-reveal><?php echo esc_html( $body ); ?></p>
		</div>
	</div>
</section>
