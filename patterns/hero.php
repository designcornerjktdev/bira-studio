<?php
/**
 * Title: BIRA Hero
 * Slug: bira/hero
 * Categories: featured
 * Inserter: no
 */
$id     = (int) get_option( 'page_on_front' );
$title  = get_field( 'hero_headline', $id ) ?: 'We make brands part of the conversation.';
$accent = get_field( 'hero_accent_word', $id ) ?: 'conversation';
$desc   = get_field( 'hero_description', $id ) ?: '';
$label  = get_field( 'hero_cta_label', $id ) ?: 'Let’s talk';
$url    = get_field( 'hero_cta_url', $id ) ?: '/contact';
$posts = [];
for ( $n = 1; $n <= 4; $n++ ) {
	$g = get_field( "hero_post_$n", $id );
	if ( ! empty( $g['image'] ) ) {
		$posts[] = [
			'image'   => (int) $g['image'],
			'handle'  => $g['handle'] ?? '',
			'caption' => $g['caption'] ?? '',
		];
	}
}
$fallback = get_field( 'hero_fallback_image', $id );

// Bungkus kata aksen (hanya kemunculan pertama).
$title_html = esc_html( $title );
if ( $accent ) {
	$title_html = preg_replace(
	'/' . preg_quote( esc_html( $accent ), '/' ) . '/i',
	'<span class="bira-hero__accent text-pop">$0</span>',
	$title_html,
	1
);
}

// Arah sebar tiap kartu: x, y, rotasi.
$scatter = [ '-40,-30,-6', '50,40,7', '0,0,0', '0,0,0' ];
?>
<section class="bira-hero relative grid min-h-svh grid-cols-1 items-center gap-8 overflow-clip p-[clamp(1.5rem,5vw,4rem)] min-[900px]:grid-cols-[1.4fr_1fr]" data-hero>
	<div class="bira-hero__text relative z-[1]">
	<h1 class="bira-hero__title m-0 font-display text-[clamp(3rem,9vw,9rem)] leading-[0.95] font-normal uppercase" data-hero-title><?php echo wp_kses( $title_html, [ 'span' => [ 'class' => true ] ] ); ?></h1>	
		<?php if ( $desc ) : ?>
			<p class="bira-hero__desc my-6 max-w-[34ch] text-[clamp(1rem,1.4vw,1.25rem)]"><?php echo esc_html( $desc ); ?></p>
		<?php endif; ?>
		<a class="bira-hero__cta inline-block rounded-full bg-ink px-[1.6em] py-[0.9em] font-semibold text-white no-underline transition-[background-color,transform] duration-[250ms] ease-[ease] hover:[transform:translateY(-2px)] hover:bg-pop" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $label ); ?></a>
	</div>

	<?php if ( $posts ) : ?>
		<div class="bira-hero__mockup relative z-[1] min-h-[clamp(22rem,62svh,42rem)] transition-opacity duration-[600ms] ease-[ease]" data-hero-mockup>
			<?php foreach ( $posts as $i => $p ) : ?>
				<figure class="bira-hero__card absolute m-0 aspect-[4/5] w-[55%] overflow-hidden rounded-2xl [&_img]:block [&_img]:size-full [&_img]:object-cover <?php echo 0 === $i ? 'top-0 left-[5%] [transform:rotate(-3deg)]' : ( 1 === $i ? 'top-[30%] right-0 [transform:rotate(4deg)]' : 'hidden' ); ?>" data-mock-card
					data-scatter="<?php echo esc_attr( $scatter[ $i ] ?? '0,0,0' ); ?>"
					data-handle="<?php echo esc_attr( $p['handle'] ); ?>"
					data-caption="<?php echo esc_attr( $p['caption'] ); ?>">
					<?php echo wp_get_attachment_image( $p['image'], 'large', false, [ 'loading' => 'eager' ] ); ?>
				</figure>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<div class="bira-hero__stage pointer-events-none absolute inset-0 z-0" data-hero-stage>
		<?php
		if ( $fallback ) {
			echo wp_get_attachment_image( $fallback, 'large', false, [
				'class'   => 'bira-hero__stage-fallback absolute inset-0 size-full object-cover opacity-25 transition-opacity duration-[800ms] ease-[ease]',
				'alt'     => '',
				'loading' => 'eager',
			] );
		}
		?>
	</div>
</section>