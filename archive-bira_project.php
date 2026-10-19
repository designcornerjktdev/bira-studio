<?php
/**
 * Halaman /work — semua project (struktur dari prototype bira-preview /work).
 * Hero → filter kategori → grid project 2 kolom (tinggi mengikuti rasio gambar) → Final CTA.
 */
get_header();

$projects = array_map( 'bira_project_data', $GLOBALS['wp_query']->posts );

// Daftar filter dari kategori semua project, urut kemunculan pertama.
$filters = [];
foreach ( $projects as $project ) {
	$filters += bira_project_cats( $project['cats'] );
}
?>
<section class="bira-container pt-16 pb-12 md:pt-24 md:pb-16">
	<span class="bira-eyebrow mb-7 text-green" data-reveal>[ Our corner of the internet ]</span>
	<h1 class="m-0 text-[9.2vw] sm:text-[clamp(3.7rem,9vw,9rem)] leading-[0.95] font-extrabold tracking-[-0.04em] uppercase">
		Good work.<br>
		<span class="text-green">Great conversations.</span>
	</h1>
	<p class="mt-8 max-w-md text-sm leading-relaxed text-ink/65" data-reveal>Different brands. Different briefs. The same obsession:<br>making something people want to be part of.</p>
</section>

<div class="bira-container pb-24" data-work-filter>
	<?php if ( $projects ) : ?>
		<div class="mb-12 flex flex-wrap items-center justify-between gap-5 border-y border-ink/15 py-5">
			<div class="flex flex-wrap gap-2" role="group" aria-label="Filter project">
				<button type="button" class="bira-filter" aria-pressed="true" data-filter="all">All</button>
				<?php foreach ( $filters as $slug => $label ) : ?>
					<button type="button" class="bira-filter" aria-pressed="false" data-filter="<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $label ); ?></button>
				<?php endforeach; ?>
			</div>
			<p class="text-sm tracking-wide text-ink/60 uppercase" aria-live="polite"><span data-filter-count><?php echo count( $projects ); ?></span> Projects / explorations</p>
		</div>

		<ul class="bira-work-grid">
			<?php foreach ( $projects as $project ) : ?>
				<li class="bira-work-grid__item" data-cats="<?php echo esc_attr( implode( ' ', array_keys( bira_project_cats( $project['cats'] ) ) ) ); ?>">
					<?php get_template_part( 'template-parts/project-card', null, [ 'project' => $project ] ); ?>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php else : ?>
		<p class="border-y border-ink/15 py-10 text-ink/60">Belum ada project. Tambahkan lewat menu Projects di admin.</p>
	<?php endif; ?>
</div>

<?php
get_template_part( 'template-parts/final-cta' );
get_footer();
