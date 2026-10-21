<?php
/**
 * How We Work — proses 5 langkah (struktur dari prototype "How we make things move").
 * Kolom kiri sticky di desktop; langkah aktif + progress bar diatur oleh src/steps.js.
 */
$eyebrow = bira_text( 'hww_eyebrow', 'Not a formula. A feeling.' );
$title   = rtrim( bira_text( 'hww_title', 'How we make things move' ), '. ' ); // titik akhir diganti titik warna sand
$intro   = bira_text( 'hww_intro', 'From a spark in the group chat to something the whole feed is talking about.' );
$steps   = bira_items( 'hww_step', [
	[ 'title' => 'Insight', 'text' => 'Deep in the comments, group chats and fyp rabbit holes, finding what people are actually obsessed with. the stuff they rant about at 2am, the ‘why is this so me’ moments, the tea everyone has an opinion on.' ],
	[ 'title' => 'Idea', 'text' => 'Turning that into an idea people want to be part of, not just double-tap. a prompt, format or trend that’s easy to copy and fun to make your own.' ],
	[ 'title' => 'Content', 'text' => 'Dropping the first wave, from hero videos to templates and example posts, so everyone knows the assignment. native to every platform and real enough that nobody clocks it’s a brand. it just looks like a vibe.' ],
	[ 'title' => 'People', 'text' => 'Handing it to creators, communities and everyday people who actually get it, then letting them cook. real faces, real stories, and that’s exactly why their followers believe it.' ],
	[ 'title' => 'Velocity', 'text' => 'Stitches, duets, remixes, reposts, building the momentum until everyone wants in. the first few posts get it started, then the internet takes it from there and it’s giving main character.' ],
] );
?>
<section id="process" class="bira-section bira-line-wrap relative isolate overflow-clip bg-ink text-cream" aria-labelledby="process-title" data-steps data-scroll-line>
	<?php echo bira_bg_doodles( [ [ 'sparkle', 'top-[10%] right-[8%] size-9 rotate-[14deg]' ], [ 'crown', 'bottom-[8%] left-[4%] size-10 rotate-[-10deg]' ] ], 'text-cream' ); ?>
	<svg class="bira-scroll-line" aria-hidden="true" focusable="false"><path /></svg>
	<div class="bira-container relative grid gap-12 md:grid-cols-2 md:gap-16">
		<div>
			<div class="md:sticky md:top-36">
				<span class="bira-eyebrow text-sand">[ <?php echo esc_html( $eyebrow ); ?> ]</span>
				<h2 id="process-title" class="bira-h2 bira-h2--static mt-4 text-[clamp(3rem,5.6vw,5.7rem)] leading-none font-extrabold tracking-[-0.02em]">
					<?php echo esc_html( $title ); ?><span class="text-sand">.</span>
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
					<span class="mb-3 block text-sm text-sand"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
					<h3 class="bira-step__title text-4xl leading-none font-extrabold tracking-[-0.02em] uppercase md:text-5xl"><?php echo esc_html( $step['title'] ); ?></h3>
					<p class="mt-4 max-w-xl text-sm leading-relaxed text-cream/75"><?php echo esc_html( $step['text'] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
