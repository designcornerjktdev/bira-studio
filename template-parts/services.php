<?php
/**
 * What We Do — layanan sebagai accordion (struktur dari prototype).
 * Item pertama terbuka; satu item terbuka pada satu waktu (lihat src/accordion.js).
 */
$title = bira_text( 'wwd_title', 'We do internet things.' );
$muted = bira_text( 'wwd_title_muted', 'But with a plan.' );
$intro = bira_text( 'wwd_intro', "A good idea doesn’t live in one format.\nNeither do we." );
$items = bira_items( 'wwd_item', [
	[ 'title' => 'Social', 'text' => 'Less broadcasting. More back-and-forth.', 'desc' => 'Social is a group chat, not a billboard. we make the posts people reply to, send to their bestie, and come back for.', 'tags' => 'Social strategy, Social campaigns, Always-on content, Conversation strategy' ],
	[ 'title' => 'Content', 'text' => 'For the watch-it-again kind of feeling.', 'desc' => 'Made for the scroll from day one. we direct, shoot and cut short-form that feels native to every platform, never like an ad.', 'tags' => 'Social-first content, Creative direction, Short-form video, Content production' ],
	[ 'title' => 'Influence', 'text' => 'The right people. Not just the big numbers.', 'desc' => 'No random creator roulette. we find the ones who actually get your brand and hand them briefs they’re lowkey obsessed with.', 'tags' => 'Creator campaigns, Influencer activation, Community activation, Creator seeding' ],
	[ 'title' => 'Culture', 'text' => 'Read the room. Then become part of it.', 'desc' => 'We know the trends, the memes, and the lore. we spot what’s worth jumping on and help your brand join and not cringeworthy.', 'tags' => 'Social listening, Trend activation, Community conversations, Cultural moments' ],
] );
?>
<section id="services" class="bira-section relative isolate overflow-clip border-b border-ink/15" aria-labelledby="services-title">
	<?php echo bira_bg_doodles( [ [ 'diamond', 'bottom-[7%] right-[5%] size-10 rotate-[-8deg]' ] ] ); ?>
	<div class="bira-container">
		<header class="mb-12 flex flex-col justify-between gap-6 lg:flex-row lg:items-end">
			<div class="relative">
				<?php echo bira_doodle( 'sparkle', 'bira-drift absolute -top-3 -right-12 hidden size-9 rotate-[14deg] text-green md:block [&_path]:stroke-[1.8]' ); ?>
				<?php // data-split="center": baris 1 naik dari garis tengah, baris 2 turun (lihat split-heading.js). ?>
				<h2 id="services-title" class="bira-h2 text-[clamp(2.5rem,5.2vw,5.3rem)] leading-[1.02] font-extrabold tracking-[-0.02em]" data-split="center">
					<span class="bira-split-mask"><span class="block" data-split-line="up"><?php echo esc_html( $title ); ?></span></span>
					<span class="bira-split-mask"><span class="block text-ink/45" data-split-line="down"><?php echo esc_html( $muted ); ?></span></span>
				</h2>
			</div>
			<p class="max-w-[230px] text-sm leading-[1.8] whitespace-pre-line text-ink/65" data-reveal><?php echo esc_html( $intro ); ?></p>
		</header>

		<div class="border-t border-ink" data-accordion>
			<?php foreach ( $items as $i => $item ) : ?>
				<?php
				$open = 0 === $i;
				$tags = array_filter( array_map( 'trim', explode( ',', (string) ( $item['tags'] ?? '' ) ) ) );
				?>
				<div class="bira-acc group border-b border-ink/15 transition-colors<?php echo $open ? ' is-open' : ''; ?>">
					<button type="button" id="service-trigger-<?php echo (int) $i; ?>" class="flex w-full items-center gap-5 px-2 py-6 text-left md:gap-10 md:px-5 md:py-7" aria-expanded="<?php echo $open ? 'true' : 'false'; ?>" aria-controls="service-<?php echo (int) $i; ?>">
						<span class="self-start pt-2 text-sm text-ink/65"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
						<span class="bira-acc__title text-[clamp(2.8rem,6vw,6rem)] leading-none font-extrabold tracking-[-0.02em] uppercase transition-colors group-hover:text-green"><?php echo esc_html( $item['title'] ); ?></span>
						<span class="ml-auto hidden text-sm text-ink/60 lg:block"><?php echo esc_html( $item['text'] ); ?></span>
						<span class="bira-acc__icon ml-auto flex size-10 shrink-0 items-center justify-center rounded-full border border-ink/15 transition-colors lg:ml-10" aria-hidden="true">
							<svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M5 12h14"/><path class="bira-acc__plus" d="M12 5v14"/></svg>
						</span>
					</button>
					<div id="service-<?php echo (int) $i; ?>" role="region" aria-labelledby="service-trigger-<?php echo (int) $i; ?>" class="bira-acc__panel"<?php echo $open ? "" : " inert"; ?>>
						<div class="bira-acc__inner">
							<div class="pb-7 pl-11 pr-5 md:pl-[82px]">
								<p class="mb-5 text-sm text-ink/65 lg:hidden"><?php echo esc_html( $item['text'] ); ?></p>
								<?php if ( ! empty( $item['desc'] ) ) : ?>
									<p class="bira-body mb-6 max-w-[60ch] text-ink/75"><?php echo esc_html( $item['desc'] ); ?></p>
								<?php endif; ?>
								<?php if ( $tags ) : ?>
									<ul class="flex flex-wrap gap-x-6 gap-y-3">
										<?php foreach ( $tags as $tag ) : ?>
											<li class="flex items-center gap-2 text-sm"><?php echo bira_doodle( 'arrow', 'size-3 text-green' ); ?><?php echo esc_html( $tag ); ?></li>
										<?php endforeach; ?>
									</ul>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
