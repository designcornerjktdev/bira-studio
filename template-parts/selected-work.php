<?php
/**
 * The Good Stuff — project pilihan dalam satu baris horizontal.
 * Desktop: section di-pin dan kartu digeser saat scroll. Mobile: swipe biasa (scroll-snap).
 * Kartu tanpa gambar tampil sebagai blok warna.
 */
$eyebrow  = bira_text( 'work_eyebrow', 'The good stuff' );
$title    = bira_text( 'work_title', 'Stuff we’re proud of' );
$all_url  = bira_text( 'work_all_url', '/work' );
// Project featured dari menu Projects; contoh di bawah hanya tampil selama belum ada yang featured.
$projects = bira_featured_projects() ?: [
	[
		'image'  => 0,
		'brand'  => 'Relaxa',
		'tag'    => 'Sample campaign',
		'hook'   => "fresh takes.\nreal talk.",
		'cats'   => 'Social / Content / Influence',
		'title'  => 'Making Candy Part of the Conversation',
		'text'   => 'A fresh take on everyday moments. Made to be shared, not skipped.',
		'stats'  => '20M+ Views, 150+ Content pieces',
		'url'    => '/work/relaxa',
		'tone'   => 'green',
	],
	[
		'image'  => 0,
		'brand'  => 'Studio concept',
		'tag'    => 'Studio concept',
		'hook'   => 'street notes.',
		'cats'   => 'Content / Culture',
		'title'  => 'Street Notes',
		'text'   => 'Style belongs to the people wearing it. An exploration of personal style and creator culture.',
		'stats'  => '',
		'url'    => '/work/street-notes',
		'tone'   => 'sand',
	],
	[
		'image'  => 0,
		'brand'  => 'ABC',
		'tag'    => 'Sample campaign',
		'hook'   => "#Rela\nNgapain?",
		'cats'   => 'Social / Culture',
		'title'  => '#RelaNgapain',
		'text'   => 'A social conversation with a little heat. One question. So many answers.',
		'stats'  => '10M+ Target views',
		'url'    => '/work/abc-rela-ngapain',
		'tone'   => 'ink',
	],
];
?>
<section id="selected-work" class="bira-work bira-section relative isolate" aria-labelledby="work-title" data-work data-reveal-once>
	<?php echo bira_bg_doodles( [ [ 'star', 'top-[9%] right-[7%] size-9 rotate-[10deg]' ], [ 'wave', 'bottom-[6%] left-[38%] w-16' ] ] ); ?>
	<div class="bira-container">
		<header class="mb-12 flex flex-col justify-between gap-6 sm:flex-row sm:items-end" data-reveal>
			<div>
				<span class="bira-eyebrow text-green">[ <?php echo esc_html( $eyebrow ); ?> ]</span>
				<h2 id="work-title" class="bira-h2 mt-4"><?php echo esc_html( $title ); ?><span class="text-green">.</span></h2>
			</div>
			<a class="bira-link inline-flex min-h-11 w-fit items-center gap-2 font-bold tracking-[0.12em] uppercase" href="<?php echo esc_url( home_url( $all_url ) ); ?>">
				<span class="bira-link__text">All work</span>
				<?php echo bira_doodle( 'next', 'bira-link__arrow size-3.5' ); ?>
			</a>
		</header>
	</div>

	<div class="bira-work__viewport" data-work-viewport>
		<ul class="bira-work__track" data-work-track>
			<?php foreach ( $projects as $project ) : ?>
				<li class="bira-work__item">
					<?php get_template_part( 'template-parts/project-card', null, [ 'project' => $project ] ); ?>
				</li>
			<?php endforeach; ?>
			<li class="bira-work__item bira-work__item--outro">
				<p class="max-w-xs text-sm leading-relaxed text-ink/65">
					<?php echo bira_doodle( 'next', 'mb-4 block size-6 text-green' ); ?>
					Different brands. Different communities.<br>
					<span class="font-semibold text-ink">Always something worth talking about.</span>
				</p>
			</li>
		</ul>
	</div>
</section>
