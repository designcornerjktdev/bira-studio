<?php
/**
 * Template Name: About
 *
 * Halaman About (struktur dari prototype bira-preview /about). Otomatis dipakai Page ber-slug "about".
 * Konten dari Customizer → About Page. Hero → Who we are → What we believe → How we think
 * → Approach & People → Final CTA.
 */
get_header();

$headline = rtrim( bira_text( 'about_headline', "Small studio.\nBig internet\nenergy" ), '. ' );
$who_img  = (int) bira_text( 'about_who_image', '0' );
$who_text = bira_text( 'about_who_text', "We bring strategists, creatives, creators and communities together to make brand ideas feel like something people actually want to join.\n\nOur roots are in Indonesia. Our curiosity goes everywhere. We care about the little things that make a conversation catch on." );
$beliefs  = bira_items( 'about_belief', [
	[ 'title' => 'People first. Platforms second.', 'text' => 'Formats change. Human behavior is where the interesting stuff starts.' ],
	[ 'title' => 'An idea is only as good as its life outside the deck.', 'text' => 'We make things to be watched, shared, recreated and talked about.' ],
	[ 'title' => 'Culture isn’t a trend report.', 'text' => 'It’s what people are doing, saying and feeling. You have to be in it.' ],
] );
$columns  = [
	[
		bira_text( 'about_approach_eyebrow', 'Our approach' ),
		bira_text( 'about_approach_title', "Listen close.\nThink big.\nMake it human." ),
		bira_text( 'about_approach_text', 'Insight becomes an idea. The idea becomes content. The right people give it momentum. We stay close to the conversation the whole way through.' ),
		'',
	],
	[
		bira_text( 'about_people_eyebrow', 'Our people' ),
		bira_text( 'about_people_title', "Many brains.\nOne good feeling." ),
		bira_text( 'about_people_text', 'Strategists who scroll. Creatives who listen. Creators who know their people. A collective of different perspectives, connected by the urge to make something good.' ),
		bira_text( 'about_people_note' ),
	],
];
$kses = [ 'span' => [ 'class' => [] ], 'br' => [] ];
?>
<section class="bira-about-hero bira-container pt-16 pb-16 md:pt-24 md:pb-24">
	<span class="bira-eyebrow mb-8 text-green">[ <?php echo esc_html( bira_text( 'about_eyebrow', 'Independent minds. Shared internet brain.' ) ); ?> ]</span>
	<h1 class="m-0 text-[clamp(3.4rem,8.5vw,8.5rem)] leading-[0.98] font-extrabold tracking-[-0.04em] uppercase">
		<?php echo wp_kses( nl2br( bira_accent( $headline, bira_text( 'about_accent', 'internet' ), 'text-green' ), false ), $kses ); ?><span class="text-green">.</span>
	</h1>
	<div class="mt-12 grid gap-7 md:grid-cols-2" data-reveal>
		<?php echo bira_doodle( 'wave', 'w-16 text-green' ); ?>
		<p class="max-w-xl text-lg leading-relaxed tracking-tight">
			<?php echo esc_html( bira_text( 'about_intro', 'We’re Bira. A social, content & influence studio based in Indonesia. We spend a lot of time on the internet.' ) ); ?><br>
			<span class="text-ink/50"><?php echo esc_html( bira_text( 'about_intro_muted', 'And we know how to make that useful.' ) ); ?></span>
		</p>
	</div>
</section>

<section class="bira-about-who bira-case__tint py-20 md:py-28">
	<div class="bira-container grid gap-12 md:grid-cols-2 md:gap-24">
		<div class="relative aspect-[0.95] overflow-hidden bg-sand">
			<?php
			if ( $who_img ) {
				echo wp_get_attachment_image( $who_img, 'large', false, [ 'class' => 'absolute inset-0 size-full object-cover', 'loading' => 'lazy' ] );
			} else {
				echo bira_doodle( 'star', 'absolute top-1/2 left-1/2 size-12 -translate-1/2 text-cream' );
			}
			?>
			<?php if ( bira_text( 'about_who_caption' ) ) : ?>
				<span class="absolute bottom-4 left-4 text-sm tracking-widest text-white uppercase"><?php echo esc_html( bira_text( 'about_who_caption' ) ); ?></span>
			<?php endif; ?>
		</div>
		<div class="flex flex-col justify-center" data-reveal>
			<span class="bira-eyebrow mb-6 text-green">[ <?php echo esc_html( bira_text( 'about_who_eyebrow', 'Who we are' ) ); ?> ]</span>
			<h2 class="text-4xl leading-[1.05] font-extrabold tracking-tighter uppercase md:text-6xl"><?php echo nl2br( esc_html( bira_text( 'about_who_title', "Good people.\nGood instincts.\nGreat group chat." ) ), false ); ?></h2>
			<?php foreach ( array_filter( array_map( 'trim', preg_split( '/\R\s*\R/', $who_text ) ) ) as $i => $para ) : ?>
				<p class="<?php echo $i ? 'mt-4' : 'mt-8'; ?> text-sm leading-[1.9] whitespace-pre-line text-ink/70"><?php echo esc_html( $para ); ?></p>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="bira-about-believe bira-container py-20 md:py-28">
	<span class="bira-eyebrow mb-10 text-green">[ <?php echo esc_html( bira_text( 'about_believe_eyebrow', 'What we believe' ) ); ?> ]</span>
	<ol>
		<?php foreach ( $beliefs as $i => $belief ) : ?>
			<li class="grid gap-4 border-t border-ink/15 py-8 md:grid-cols-[50px_1.5fr_1fr] md:gap-10 md:py-10" data-reveal>
				<span class="pt-1 text-sm text-green"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
				<h3 class="max-w-lg text-3xl leading-tight font-bold tracking-[-0.045em]"><?php echo esc_html( $belief['title'] ); ?></h3>
				<p class="max-w-sm text-sm leading-relaxed text-ink/65"><?php echo esc_html( $belief['text'] ); ?></p>
			</li>
		<?php endforeach; ?>
	</ol>
</section>

<section class="bira-about-think bira-case__tint py-20 text-ink md:py-28">
	<div class="bira-container">
		<span class="bira-eyebrow mb-8 text-green">[ <?php echo esc_html( bira_text( 'about_think_eyebrow', 'How we think' ) ); ?> ]</span>
		<h2 class="max-w-5xl text-[clamp(3rem,7vw,7rem)] leading-[1.02] font-extrabold tracking-[-0.04em] uppercase"><?php echo nl2br( esc_html( bira_text( 'about_think_headline', "We don’t ask\n“What should\nthe brand say?”" ) ), false ); ?></h2>
		<p class="mt-10 flex max-w-3xl flex-wrap items-center gap-4 text-2xl font-semibold tracking-tight text-green md:text-4xl" data-reveal>
			<?php echo esc_html( bira_text( 'about_think_line', 'We ask “why would anyone care?”' ) ); ?>
			<?php echo bira_doodle( 'star', 'bira-drift size-8 md:size-10' ); ?>
		</p>
	</div>
</section>

<section class="bira-about-approach bira-container grid gap-14 py-20 md:grid-cols-2 md:gap-24 md:py-28">
	<?php foreach ( $columns as [ $eyebrow, $title, $text, $note ] ) : ?>
		<div data-reveal>
			<span class="bira-eyebrow mb-5 text-green">[ <?php echo esc_html( $eyebrow ); ?> ]</span>
			<h2 class="text-4xl leading-none font-extrabold tracking-tighter uppercase"><?php echo nl2br( esc_html( $title ), false ); ?></h2>
			<p class="mt-7 max-w-md text-sm leading-[1.9] whitespace-pre-line text-ink/65"><?php echo esc_html( $text ); ?></p>
			<?php if ( $note ) : ?>
				<p class="mt-4 text-sm text-ink/45"><?php echo esc_html( $note ); ?></p>
			<?php endif; ?>
		</div>
	<?php endforeach; ?>
</section>

<?php
get_template_part( 'template-parts/final-cta' );
get_footer();
