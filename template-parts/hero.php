<?php
/**
 * 01 Hero — konten dari Customizer (Homepage → Hero).
 * Struktur mengikuti prototype (bira-preview), token warna/font mengikuti guideline.
 */
$title    = bira_text( 'hero_headline', "We make\nbrands part\nof the\nconversation." );
$accent   = bira_text( 'hero_accent_word', 'conversation' );
$desc_raw = bira_text( 'hero_description' );
$desc     = $desc_raw ?: 'Built for brands that want people to actually talk about them.';
// Kalimat pembuka default hanya dipakai bila Description juga masih default (hindari teks ganda).
$lead     = bira_text( 'hero_lead', $desc_raw ? '' : 'Social, content & influence from Indonesia.' );
$label  = bira_text( 'hero_cta_label', 'See our work' );
$url    = bira_text( 'hero_cta_url', '#work' );
$label2 = bira_text( 'hero_cta2_label', 'Let’s make something' );
$url2   = bira_text( 'hero_cta2_url', '#cta' );
$symbol = (int) bira_text( 'hero_symbol', '0' );

// Baris baru di headline jadi pemenggalan baris; kata aksen hanya kemunculan pertama.
$title_html = nl2br( bira_accent( $title, $accent, 'bira-hero__accent text-green' ), false );

// 5 kartu bertumpuk (hero_post_1_* … hero_post_5_*: image, handle, caption). Kartu 1 paling bawah, kartu 5 paling atas.
$cards = bira_items( 'hero_post', [
	[ 'image' => 0, 'handle' => '@madeforthefeed', 'caption' => 'Main character energy' ],
	[ 'image' => 0, 'handle' => 'Creator culture', 'caption' => 'a little behind the scenes.' ],
	[ 'image' => 0, 'handle' => '@birastudio', 'caption' => 'POV: the group chat approved.' ],
	[ 'image' => 0, 'handle' => 'Street notes', 'caption' => 'not made to blend in.' ],
	[ 'image' => 0, 'handle' => 'Content mood', 'caption' => 'a little sweet. a little extra.' ],
] );
// Tumpukan: tiap kartu sedikit miring acak (seperti foto ditaruh tangan).
$tilt = [ -6, 5, -4, 4, -1 ];
// Kipas saat hover (data-spread di mockup.js: geser x %, geser y %, tambahan putar °).
// Sudut kipas dibuat rata (t × 10°, t = -2 … 2); tambahan putar = sudut kipas − miring acak,
// jadi saat terbuka semua kartu berjarak sama tanpa sisa kemiringan acak.
$fan = static function ( int $i, int $count ) use ( $tilt ): string {
	$t = $i - ( $count - 1 ) / 2;
	return sprintf( '%s %s %s', round( $t * 10, 1 ), round( $t * $t * 1.2, 1 ), round( $t * 10 - $tilt[ $i % count( $tilt ) ], 1 ) );
};

$card_img = static function ( $att ) {
	if ( $att ) {
		echo wp_get_attachment_image( (int) $att, 'large', false, [ 'loading' => 'eager' ] );
	}
};
?>
<section id="hero" class="bira-hero relative isolate overflow-clip bg-cream" data-hero>
	<?php echo bira_bg_doodles( [ [ 'sparkle', 'top-[16%] left-[44%] size-8 rotate-[12deg]' ], [ 'wave', 'bottom-[10%] left-[3%] w-16 rotate-[-8deg]' ] ] ); ?>
	<div class="bira-container">
		<div class="bira-grid items-center gap-y-8 pt-[clamp(2.5rem,6vw,4.75rem)] lg:min-h-[min(80svh,640px)]">
			<div class="bira-hero__text relative z-10 lg:col-span-7">
				<h1 class="bira-hero__title m-0 font-display text-[length:clamp(2.5rem,6.4vw,6rem)] leading-[0.95] font-bold uppercase" data-hero-title><?php echo wp_kses( $title_html, [ 'span' => [ 'class' => [] ], 'br' => [] ] ); ?></h1>

				<div class="bira-body mt-7 max-w-[38ch] text-ink/75">
					<?php if ( $lead ) : ?>
						<p class="font-semibold text-ink"><?php echo esc_html( $lead ); ?></p>
					<?php endif; ?>
					<p><?php echo esc_html( $desc ); ?></p>
				</div>

				<div class="mt-8 flex flex-wrap items-center gap-6">
					<?php echo bira_button( $label, $url, 'bira-hero__cta' ); ?>
					<a class="bira-link inline-flex min-h-11 items-center gap-2 font-bold tracking-[0.12em] uppercase" href="<?php echo esc_url( $url2 ); ?>">
						<span class="bira-link__text"><?php echo esc_html( $label2 ); ?></span>
						<?php echo bira_doodle( 'next', 'bira-link__arrow size-3.5' ); ?>
					</a>
				</div>
			</div>

			<div class="bira-mock lg:col-span-5 lg:col-start-8" data-hero-mockup>
				<?php if ( $symbol ) : ?>
					<span class="bira-mock__symbol" data-mock-frame data-spread="0 0 6" aria-hidden="true"><?php echo wp_get_attachment_image( $symbol, 'large', false, [ 'alt' => '', 'loading' => 'eager' ] ); ?></span>
				<?php else : ?>
					<span class="bira-mock__frame" data-mock-frame data-spread="0 0 6" aria-hidden="true"></span>
				<?php endif; ?>

				<?php foreach ( $cards as $i => $card ) : ?>
					<article class="bira-mock__card" style="transform: rotate(<?php echo (int) $tilt[ $i % count( $tilt ) ]; ?>deg)" data-mock-item data-depth="<?php echo esc_attr( round( 0.4 + $i * 0.1, 2 ) ); ?>" data-spread="<?php echo esc_attr( $fan( $i, count( $cards ) ) ); ?>">
						<?php $card_img( $card['image'] ); ?>
						<?php if ( $i === count( $cards ) - 1 ) : ?>
							<span class="bira-mock__arrow" aria-hidden="true">
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M8 7h9v9"/></svg>
							</span>
						<?php endif; ?>
						<div class="bira-mock__meta">
							<p class="bira-mock__handle"><?php echo esc_html( $card['handle'] ); ?></p>
							<p class="bira-mock__caption"><?php echo esc_html( $card['caption'] ); ?></p>
						</div>
					</article>
				<?php endforeach; ?>

				<div class="bira-mock__sticker" data-mock-item data-depth="1.2" data-spread="7 -7 18">
					<div class="bira-mock__sticker-inner">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" aria-hidden="true"><path d="M12 2v20M3.3 7l17.4 10M3.3 17 20.7 7"/></svg>
						<p>Not made to be skipped.</p>
					</div>
				</div>

				<span class="bira-mock__star" data-mock-item data-depth="1.4" data-spread="-8 0 -45" aria-hidden="true">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"><path d="M12 2v20M3.3 7l17.4 10M3.3 17 20.7 7"/></svg>
				</span>

				<div class="bira-mock__comment" data-mock-item data-depth="1" data-spread="-7 7 -4">
					<span class="bira-mock__avatar" aria-hidden="true">b.</span>
					<div>
						<small>someone in the comments</small>
						<p>okay but this is actually so good.</p>
					</div>
					<span class="bira-mock__heart" aria-hidden="true">
						<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
					</span>
				</div>

				<p class="bira-mock__note" data-mock-item data-depth="0.4" data-spread="0 6 3">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 5v4a4 4 0 0 0 4 4h12M16 9l4 4-4 4"/></svg>
					<span>less ads. more “send this to you”.</span>
				</p>
			</div>
		</div>
	</div>
</section>
