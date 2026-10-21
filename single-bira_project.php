<?php
/**
 * Detail project /work/{slug} (struktur dari prototype bira-preview /work/relaxa).
 * Intro → gambar hero → 01 Challenge / 02 Insight → 03 Idea → 04 Execution + 05 Content
 * → 06 People → Results → Up next → Final CTA. Bagian yang field-nya kosong dilewati.
 */
get_header();
the_post();

$id      = get_the_ID();
$project = bira_project_data( get_post() );
$meta    = static fn( string $key ): string => trim( (string) get_post_meta( $id, $key, true ) );
$list    = static fn( string $key ): array => array_values( array_filter( array_map( 'trim', explode( ',', $meta( $key ) ) ) ) );

$cats      = array_values( bira_project_cats( $project['cats'] ) );
$chips     = $list( 'chips' );
$stats     = $list( 'stats' );
$challenge = $meta( 'challenge' );
$insight   = $meta( 'insight' );
$exec_text = $meta( 'execution' );
$content   = $meta( 'content' );
$image_2   = (int) $meta( 'content_image' );
$has_exec  = $meta( 'execution_title' ) || $exec_text || $content || $image_2;
$next      = bira_next_project( $id );

// Nomor bagian: label kecil hijau "01 / The Challenge".
$label = static fn( string $n, string $text, string $class = 'mb-6' ): string => sprintf(
	'<p class="%s text-sm font-bold tracking-widest text-green uppercase">%s / %s</p>',
	esc_attr( $class ),
	esc_html( $n ),
	esc_html( $text )
);
?>
<section class="bira-container pt-12 pb-12 md:pt-16 md:pb-16">
	<a class="inline-flex min-h-11 items-center gap-2 text-sm font-bold tracking-[0.12em] text-ink/65 uppercase transition-colors hover:text-green" href="<?php echo esc_url( get_post_type_archive_link( 'bira_project' ) ); ?>">
		<?php echo bira_doodle( 'next', 'size-3.5 rotate-180' ); ?>
		<span>Back to work</span>
	</a>

	<?php if ( $project['brand'] || $cats ) : ?>
		<p class="mt-10 flex flex-wrap items-center gap-4 text-sm font-bold tracking-widest uppercase">
			<?php if ( $project['brand'] ) : ?>
				<span class="text-green"><?php echo esc_html( $project['brand'] ); ?></span>
			<?php endif; ?>
			<?php if ( $project['brand'] && $cats ) : ?>
				<span class="text-ink/30" aria-hidden="true">/</span>
			<?php endif; ?>
			<?php if ( $cats ) : ?>
				<span><?php echo esc_html( implode( ' + ', $cats ) ); ?></span>
			<?php endif; ?>
		</p>
	<?php endif; ?>

	<h1 class="mt-6 max-w-6xl text-[clamp(3rem,7.5vw,7.5rem)] leading-[0.99] font-extrabold tracking-[-0.04em]"><?php echo esc_html( rtrim( get_the_title(), '. ' ) ); ?><span class="text-green">.</span></h1>

	<?php if ( $meta( 'lede' ) ) : ?>
		<p class="mt-7 max-w-xl text-base leading-relaxed text-ink/65" data-reveal><?php echo esc_html( $meta( 'lede' ) ); ?></p>
	<?php endif; ?>

	<?php if ( $chips ) : ?>
		<ul class="mt-7 flex flex-wrap gap-2" data-reveal>
			<?php foreach ( $chips as $chip ) : ?>
				<li class="rounded-full border border-ink/15 px-3 py-1.5 text-sm"><?php echo esc_html( $chip ); ?></li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

	<?php if ( $meta( 'note' ) ) : ?>
		<p class="mt-8 max-w-2xl border-l-2 border-green pl-3 text-sm leading-relaxed text-ink/55 uppercase"><?php echo esc_html( $meta( 'note' ) ); ?></p>
	<?php endif; ?>
</section>

<?php if ( $project['image'] ) : ?>
	<div class="bira-container">
		<div class="relative aspect-[1.35] overflow-hidden md:aspect-[2.1]">
			<?php echo wp_get_attachment_image( (int) $project['image'], 'full', false, [ 'class' => 'absolute inset-0 size-full object-cover', 'sizes' => '100vw', 'loading' => 'eager' ] ); ?>
			<div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
			<?php if ( $project['brand'] ) : ?>
				<p class="absolute bottom-7 left-7 text-[clamp(3rem,8vw,8rem)] leading-none font-extrabold tracking-[-0.06em] text-white uppercase" aria-hidden="true"><?php echo esc_html( $project['brand'] ); ?></p>
			<?php endif; ?>
		</div>
	</div>
<?php endif; ?>

<?php if ( $challenge || $insight ) : ?>
	<section class="bira-container grid gap-12 py-20 md:grid-cols-2 md:gap-24 md:py-28">
		<?php foreach ( [ [ '01', 'The Challenge', $challenge ], [ '02', 'The Insight', $insight ] ] as [ $n, $heading, $text ] ) : ?>
			<?php if ( $text ) : ?>
				<div data-reveal>
					<?php echo $label( $n, $heading ); ?>
					<h2 class="text-2xl leading-snug font-bold tracking-[-0.04em] md:text-3xl"><?php echo esc_html( $text ); ?></h2>
				</div>
			<?php endif; ?>
		<?php endforeach; ?>
	</section>
<?php endif; ?>

<?php if ( $meta( 'idea' ) ) : ?>
	<section class="bira-case__tint py-20 md:py-28">
		<div class="bira-container">
			<?php echo $label( '03', 'The Idea', 'mb-7' ); ?>
			<h2 class="max-w-5xl text-[clamp(2.8rem,5.3vw,5.5rem)] leading-[1.06] font-extrabold tracking-[-0.04em]" data-reveal><?php echo esc_html( $meta( 'idea' ) ); ?></h2>
		</div>
	</section>
<?php endif; ?>

<?php if ( $has_exec ) : ?>
	<section class="bira-container grid items-center gap-12 py-20 md:grid-cols-[1fr_1.1fr] md:gap-24 md:py-28">
		<div data-reveal>
			<?php if ( $meta( 'execution_title' ) || $exec_text ) : ?>
				<?php echo $label( '04', 'The Execution' ); ?>
				<?php if ( $meta( 'execution_title' ) ) : ?>
					<h2 class="text-4xl leading-[1.04] font-extrabold tracking-tighter uppercase md:text-5xl"><?php echo nl2br( esc_html( $meta( 'execution_title' ) ), false ); ?></h2>
				<?php endif; ?>
				<?php if ( $exec_text ) : ?>
					<p class="mt-7 text-sm leading-[1.85] whitespace-pre-line text-ink/70"><?php echo esc_html( $exec_text ); ?></p>
				<?php endif; ?>
			<?php endif; ?>
			<?php if ( $content ) : ?>
				<?php echo $label( '05', 'The Content', 'mt-12 mb-4' ); ?>
				<p class="text-sm leading-[1.85] whitespace-pre-line text-ink/70"><?php echo esc_html( $content ); ?></p>
			<?php endif; ?>
		</div>
		<?php if ( $image_2 ) : ?>
			<div class="relative aspect-[0.88] overflow-hidden rounded-md">
				<?php echo wp_get_attachment_image( $image_2, 'large', false, [ 'class' => 'absolute inset-0 size-full object-cover', 'loading' => 'lazy' ] ); ?>
			</div>
		<?php endif; ?>
	</section>
<?php endif; ?>

<?php if ( $meta( 'people' ) ) : ?>
	<section class="border-t border-ink/15 py-16">
		<div class="bira-container flex flex-col justify-between gap-8 md:flex-row">
			<h2 class="text-sm font-bold tracking-widest text-green uppercase">06 / The People</h2>
			<p class="max-w-2xl text-2xl leading-snug font-semibold tracking-tight" data-reveal><?php echo esc_html( $meta( 'people' ) ); ?></p>
		</div>
	</section>
<?php endif; ?>

<?php if ( $stats ) : ?>
	<section class="bira-case__tint py-20 text-ink md:py-28">
		<div class="bira-container">
			<h2 class="mb-12 text-sm font-bold tracking-widest text-green uppercase">[ The Results ]</h2>
			<div class="grid gap-10 sm:grid-cols-2">
				<?php foreach ( $stats as $stat ) : ?>
					<?php [ $num, $what ] = array_pad( explode( ' ', $stat, 2 ), 2, '' ); ?>
					<div data-reveal>
						<p class="text-[clamp(5rem,13vw,12rem)] leading-none font-extrabold tracking-[-0.06em]"><?php echo esc_html( $num ); ?></p>
						<?php if ( $what ) : ?>
							<p class="mt-4 text-sm font-bold tracking-widest text-green uppercase"><?php echo esc_html( $what ); ?></p>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
			<?php if ( $meta( 'results_note' ) ) : ?>
				<p class="mt-12 max-w-lg text-sm leading-relaxed text-ink/55"><?php echo esc_html( $meta( 'results_note' ) ); ?></p>
			<?php endif; ?>
		</div>
	</section>
<?php endif; ?>

<?php if ( $next ) : ?>
	<section class="bira-container grid items-center gap-10 py-20 md:grid-cols-2 md:py-28">
		<div>
			<p class="mb-5 text-sm font-bold tracking-widest text-green uppercase">Keep the conversation going</p>
			<h2 class="text-5xl leading-none font-extrabold tracking-tighter uppercase md:text-6xl">Up next<span class="text-green">.</span></h2>
			<a class="bira-link mt-8 inline-flex min-h-11 items-center gap-6 border-b border-ink/15 pb-2 text-sm font-bold tracking-[0.12em] uppercase" href="<?php echo esc_url( get_post_type_archive_link( 'bira_project' ) ); ?>">
				<span class="bira-link__text">All work</span>
				<?php echo bira_doodle( 'next', 'bira-link__arrow size-3.5' ); ?>
			</a>
		</div>
		<div class="bira-case__next">
			<?php get_template_part( 'template-parts/project-card', null, [ 'project' => bira_project_data( $next ) ] ); ?>
		</div>
	</section>
<?php endif; ?>

<?php
get_template_part( 'template-parts/cta' );
get_footer();
