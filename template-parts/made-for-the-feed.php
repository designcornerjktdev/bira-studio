<?php
/**
 * Made for the Feed — moodboard kartu bergaya post sosial (struktur dari prototype).
 * Desktop: 4 kolom miring berselang-seling. Mobile: swipe dengan scroll-snap.
 * Kartu tanpa gambar tampil sebagai blok warna.
 */
$eyebrow = bira_text( 'feed_eyebrow', 'Less skip. More send.' );
$intro   = bira_text( 'feed_intro', "Not just content that fills a calendar.\nContent that makes someone feel something." );
$sticker = bira_text( 'feed_sticker', "Less polished.\nMore human." );
$comment = bira_text( 'feed_comment', '“this lives in my head rent free”' );
$tiles   = bira_items( 'feed_item', [
	[ 'image' => 0, 'label' => 'Creator culture', 'title' => 'Main character energy.', 'text' => 'Out of the group chat. Into the street.' ],
	[ 'image' => 0, 'label' => 'Content mood', 'title' => 'A little sweet. A little extra.', 'text' => 'The kind of color your feed ordered.' ],
	[ 'image' => 0, 'label' => 'Behind the feed', 'title' => 'POV: you love making things.', 'text' => 'The good stuff happens behind the scenes.' ],
	[ 'image' => 0, 'label' => 'Street notes', 'title' => 'Not made to blend in.', 'text' => 'Your style. Your rules. Your feed.' ],
] );
$icon_heart = '<svg class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 14c1.5-1.5 3-3.2 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.8 0-3 .5-4.5 2-1.5-1.5-2.7-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4 3 5.5l7 7Z"/></svg>';
$icon_chat  = '<svg class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/></svg>';
?>
<section id="made-for-the-feed" class="bira-section bira-feed-section relative overflow-hidden" aria-labelledby="feed-title">
	<div class="bira-container">
		<header class="flex flex-col justify-between gap-6 sm:flex-row sm:items-end">
			<div>
				<span class="bira-eyebrow text-green">[ <?php echo esc_html( $eyebrow ); ?> ]</span>
				<h2 id="feed-title" class="bira-h2 mt-4 text-[clamp(3.5rem,7vw,7rem)] leading-[0.98] font-extrabold tracking-[-0.02em]">Made for<br>the <span class="text-green">feed.</span></h2>
			</div>
			<p class="max-w-[255px] text-xs leading-[1.8] whitespace-pre-line text-ink/65" data-reveal><?php echo esc_html( $intro ); ?>
				<span class="mt-3 flex items-center gap-1 text-[0.625rem] font-bold tracking-wide text-green uppercase">Swipe. Tap. Take a look. <?php echo bira_doodle( 'arrow', 'size-3' ); ?></span>
			</p>
		</header>

		<div class="relative mt-10 md:mt-12">
			<div class="bira-feed-sticker absolute -top-8 -right-3 z-20 flex size-24 rotate-12 items-center justify-center rounded-full bg-green text-center text-[0.625rem] leading-tight font-bold whitespace-pre-line text-white uppercase md:-top-7 md:-right-4" aria-hidden="true"><?php echo esc_html( $sticker ); ?></div>

			<ul class="bira-feed-cards">
				<?php foreach ( $tiles as $i => $tile ) : ?>
					<li class="bira-feed-card group" tabindex="0" aria-label="<?php echo esc_attr( $tile['title'] ); ?>">
						<div class="bira-feed-card__media">
							<?php
							if ( ! empty( $tile['image'] ) ) {
								echo wp_get_attachment_image( (int) $tile['image'], 'large', false, [ 'class' => 'absolute inset-0 size-full object-cover', 'loading' => 'lazy' ] );
							}
							?>
							<div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-black/10"></div>
							<span class="absolute top-3 left-3 rounded-full border border-white/50 bg-black/15 px-2 py-1 text-[0.5rem] font-bold tracking-widest text-white uppercase"><?php echo esc_html( $tile['label'] ); ?></span>
							<span class="absolute top-3 right-3 flex size-6 items-center justify-center rounded-full bg-cream text-ink"><?php echo bira_doodle( 'arrow', 'size-3' ); ?></span>
							<div class="absolute right-4 bottom-4 left-4 text-white">
								<p class="text-xl leading-[1.1] font-extrabold tracking-[-0.02em]"><?php echo esc_html( $tile['title'] ); ?></p>
								<p class="bira-feed-card__more text-[0.625rem]"><?php echo esc_html( $tile['text'] ); ?></p>
								<div class="mt-4 flex justify-between border-t border-white/30 pt-3">
									<span class="text-[0.5rem] font-semibold tracking-wider uppercase">Bira moodboard</span>
									<span class="flex gap-3"><?php echo $icon_heart . $icon_chat; // phpcs:ignore WordPress.Security.EscapeOutput -- SVG statis ?></span>
								</div>
							</div>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>

			<div class="pointer-events-none absolute bottom-10 left-[20%] z-20 hidden -rotate-6 items-center gap-3 rounded-xl bg-cream px-5 py-4 shadow-md md:flex" aria-hidden="true">
				<span class="text-green"><?php echo $icon_chat; // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				<p class="text-xs font-bold"><?php echo esc_html( $comment ); ?></p>
				<span class="text-green [&_svg]:fill-current"><?php echo $icon_heart; // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
			</div>
			<?php echo bira_doodle( 'star', 'bira-drift absolute -bottom-2 right-[9%] hidden size-16 rotate-12 text-green md:block' ); ?>
		</div>
	</div>
</section>
