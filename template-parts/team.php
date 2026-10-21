<?php
/**
 * Team — "The group chat admins": kartu anggota tim bergaya post sosial.
 * Kartu: foto, role (pill), nama, fun fact (muncul saat hover), handle sosmed.
 * Desktop: kartu dipin lalu menyebar jadi busur (src/feed.js). Mobile: swipe dengan scroll-snap.
 * Kartu tanpa foto tampil sebagai blok warna.
 */
$eyebrow = bira_text( 'feed_eyebrow', 'Straight from group chat.' );
// Judul: Enter = pindah baris, kata aksen (kemunculan pertama) jadi hijau — sama seperti hero.
$headline = nl2br( bira_accent( bira_text( 'feed_headline', "The group\nchat admins." ), bira_text( 'feed_accent', 'admins.' ), 'text-green' ), false );
$intro   = bira_text( 'feed_intro', "A few of the people behind the ideas, conversations, and occasional group chat chaos." );
$sticker = bira_text( 'feed_sticker', "24/7\nAlways online" );
$comment = bira_text( 'feed_comment', '“the ones who sent the photos on time.”' );
// Contoh isi sampai data tim diisi di Customizer (Homepage → Team).
$members = bira_items( 'team_member', [
	[ 'photo' => 0, 'role' => 'Strategy', 'name' => '[Name]', 'fun' => 'Has a spreadsheet for every trend.', 'handle' => '' ],
	[ 'photo' => 0, 'role' => 'Creative', 'name' => '[Name]', 'fun' => 'Pitches ideas in voice notes.', 'handle' => '' ],
	[ 'photo' => 0, 'role' => 'Content', 'name' => '[Name]', 'fun' => 'Shoots, cuts, posts. Repeat.', 'handle' => '' ],
	[ 'photo' => 0, 'role' => 'Creators', 'name' => '[Name]', 'fun' => 'Knows every creator’s cat by name.', 'handle' => '' ],
	[ 'photo' => 0, 'role' => 'Production', 'name' => '[Name]', 'fun' => 'The one who sent the photos on time.', 'handle' => '' ],
] );
$icon_heart = '<svg class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 14c1.5-1.5 3-3.2 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.8 0-3 .5-4.5 2-1.5-1.5-2.7-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4 3 5.5l7 7Z"/></svg>';
$icon_chat  = '<svg class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/></svg>';
?>
<section id="team" data-anchor-end class="bira-section relative isolate overflow-hidden" aria-labelledby="feed-title">
	<svg class="bira-watermark" viewBox="0 0 320 1000" aria-hidden="true" focusable="false">
		<text x="160" y="500" transform="rotate(-90 160 500)" text-anchor="middle" dominant-baseline="central">BIR<tspan dx="28">A</tspan></text>
	</svg>
	<?php echo bira_bg_doodles( [ [ 'crown', 'top-[9%] left-[46%] size-9 rotate-[-8deg]' ] ] ); ?>
	<div class="bira-container">
		<header class="flex flex-col justify-between gap-6 sm:flex-row sm:items-end">
			<div>
				<span class="bira-eyebrow text-green">[ <?php echo esc_html( $eyebrow ); ?> ]</span>
				<h2 id="feed-title" class="bira-h2 mt-4 text-[clamp(3.5rem,7vw,7rem)] leading-[0.98] font-extrabold tracking-[-0.02em]"><?php echo wp_kses( $headline, [ 'span' => [ 'class' => [] ], 'br' => [] ] ); ?></h2>
			</div>
			<p class="max-w-[255px] text-sm leading-[1.8] whitespace-pre-line text-ink/65" data-reveal><?php echo esc_html( $intro ); ?>
				<span class="mt-3 flex items-center gap-1 text-sm font-bold tracking-wide text-green uppercase">Swipe. Tap. Take a look. <?php echo bira_doodle( 'arrow', 'size-3' ); ?></span>
			</p>
		</header>

		<div class="relative mt-10 md:mt-12">
			<div class="bira-feed-sticker absolute -top-10 -right-3 z-20 flex size-32 rotate-12 px-3 items-center justify-center rounded-full bg-green text-center text-sm leading-tight font-bold whitespace-pre-line text-white uppercase md:-top-7 md:-right-4" aria-hidden="true"><?php echo esc_html( $sticker ); ?></div>

			<ul class="bira-feed-cards">
				<?php foreach ( $members as $member ) : ?>
					<li class="bira-feed-card group" tabindex="0" aria-label="<?php echo esc_attr( trim( $member['name'] . ', ' . $member['role'], ', ' ) ); ?>">
						<div class="bira-feed-card__media">
							<?php
							if ( ! empty( $member['photo'] ) ) {
								echo wp_get_attachment_image( (int) $member['photo'], 'large', false, [ 'class' => 'absolute inset-0 size-full object-cover', 'alt' => $member['name'], 'loading' => 'lazy' ] );
							}
							?>
							<div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-black/10"></div>
							<?php if ( $member['role'] ) : ?>
								<span class="absolute top-3 left-3 rounded-full border border-white/50 bg-black/15 px-2 py-1 text-sm font-bold tracking-widest text-white uppercase"><?php echo esc_html( $member['role'] ); ?></span>
							<?php endif; ?>
							<span class="absolute top-3 right-3 flex size-6 items-center justify-center rounded-full bg-cream text-ink"><?php echo bira_doodle( 'arrow', 'size-3' ); ?></span>
							<div class="absolute right-4 bottom-4 left-4 text-white">
								<p class="text-xl leading-[1.1] font-extrabold tracking-[-0.02em]"><?php echo esc_html( $member['name'] ); ?></p>
								<?php if ( $member['fun'] ) : ?>
									<p class="bira-feed-card__more text-sm"><?php echo esc_html( $member['fun'] ); ?></p>
								<?php endif; ?>
								<div class="mt-4 flex justify-between gap-3 border-t border-white/30 pt-3">
									<span class="truncate text-sm font-semibold tracking-wider<?php echo $member['handle'] ? '' : ' uppercase'; ?>"><?php echo esc_html( $member['handle'] ?: 'BIRA team' ); ?></span>
									<span class="flex gap-3"><?php echo $icon_heart . $icon_chat; // phpcs:ignore WordPress.Security.EscapeOutput -- SVG statis ?></span>
								</div>
							</div>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>

			<div class="pointer-events-none absolute bottom-10 left-[20%] z-20 hidden -rotate-6 items-center gap-3 rounded-xl bg-cream px-5 py-4 shadow-md md:flex" aria-hidden="true">
				<span class="text-green"><?php echo $icon_chat; // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				<p class="text-sm font-bold"><?php echo esc_html( $comment ); ?></p>
				<span class="text-green [&_svg]:fill-current"><?php echo $icon_heart; // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
			</div>
			<?php echo bira_doodle( 'star', 'bira-drift absolute -bottom-2 right-[9%] hidden size-16 rotate-12 text-green md:block' ); ?>
		</div>
	</div>
</section>
