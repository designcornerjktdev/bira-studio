<?php
/**
 * How We Work — proses 5 langkah (struktur dari prototype "How we make things move").
 * Kolom kiri sticky di desktop; langkah aktif + progress bar diatur oleh src/steps.js.
 */
$eyebrow = bira_text( 'hww_eyebrow', 'Not a formula. A feeling.' );
$title   = bira_text( 'hww_title', 'How we make' );
$muted   = bira_text( 'hww_title_2', 'things move' );
$intro   = bira_text( 'hww_intro', 'From a spark in the group chat to something the whole feed is talking about.' );
$steps   = bira_items( 'hww_step', [
	[ 'title' => 'Insight', 'text' => 'Find what people actually care about.' ],
	[ 'title' => 'Idea', 'text' => 'Turn that tension into something worth talking about.' ],
	[ 'title' => 'Content', 'text' => 'Create formats people naturally want to watch.' ],
	[ 'title' => 'People', 'text' => 'Put the idea in the hands of the right creators and communities.' ],
	[ 'title' => 'Velocity', 'text' => 'Build enough momentum for the conversation to spread.' ],
] );
?>
<section id="how-we-work" class="bira-section bira-line-wrap relative overflow-clip bg-ink text-cream" aria-labelledby="how-we-work-title" data-steps data-scroll-line>
	<svg class="bira-scroll-line" aria-hidden="true" focusable="false"><path /></svg>
	<div class="bira-container relative grid gap-12 md:grid-cols-2 md:gap-16">
		<div>
			<div class="md:sticky md:top-36">
				<span class="bira-eyebrow text-sand">[ <?php echo esc_html( $eyebrow ); ?> ]</span>
				<h2 id="how-we-work-title" class="bira-h2 bira-h2--static mt-4 text-[clamp(3rem,5.6vw,5.7rem)] leading-none font-extrabold tracking-[-0.02em]">
					<?php echo esc_html( $title ); ?><br>
					<?php echo esc_html( $muted ); ?><span class="text-sand">.</span>
				</h2>
				<p class="mt-7 max-w-xs text-sm leading-relaxed text-cream/55"><?php echo esc_html( $intro ); ?></p>
				<div class="mt-10 hidden gap-2 md:flex" aria-hidden="true">
					<?php foreach ( $steps as $i => $step ) : ?>
						<span class="bira-steps__bar h-1 w-9 bg-cream/20 transition-colors duration-300<?php echo 0 === $i ? ' is-active' : ''; ?>"></span>
					<?php endforeach; ?>
				</div>
			</div>
		</div>

		<ol class="bira-steps relative border-l border-cream/20 pl-7 md:pl-10">
			<?php foreach ( $steps as $i => $step ) : ?>
				<li class="bira-step relative py-8 first:pt-0 last:pb-0 md:py-12<?php echo 0 === $i ? ' is-active' : ''; ?>">
					<span class="bira-step__dot" aria-hidden="true"></span>
					<span class="mb-3 block text-[0.625rem] text-sand"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
					<h3 class="bira-step__title text-4xl leading-none font-extrabold tracking-[-0.02em] uppercase md:text-5xl"><?php echo esc_html( $step['title'] ); ?></h3>
					<p class="mt-4 max-w-sm text-sm leading-relaxed text-cream/75"><?php echo esc_html( $step['text'] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
