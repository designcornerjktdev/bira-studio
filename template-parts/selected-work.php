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
				<?php $stats = array_filter( array_map( 'trim', explode( ',', (string) $project['stats'] ) ) ); ?>
				<li class="bira-work__item">
					<article class="group">
						<a class="block focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-green" href="<?php echo esc_url( str_starts_with( $project['url'], 'http' ) ? $project['url'] : home_url( $project['url'] ) ); ?>">
							<div class="bira-work__media bira-work__media--<?php echo esc_attr( $project['tone'] ); ?>">
								<?php
								if ( ! empty( $project['image'] ) ) {
									echo wp_get_attachment_image( (int) $project['image'], 'large', false, [ 'class' => 'bira-work__img', 'loading' => 'lazy' ] );
								}
								?>
								<div class="absolute inset-0 bg-gradient-to-t from-black/65 via-black/5 to-black/15"></div>
								<div class="absolute inset-x-0 top-0 flex items-start justify-between p-5 md:p-7">
									<span class="text-xl font-extrabold tracking-[-0.07em] text-white uppercase"><?php echo esc_html( $project['brand'] ); ?></span>
									<span class="rounded-full border border-white/60 bg-black/15 px-2.5 py-1 text-[0.5rem] font-semibold tracking-wider text-white uppercase"><?php echo esc_html( $project['tag'] ); ?></span>
								</div>
								<div class="absolute inset-x-0 bottom-0 flex items-end justify-between gap-4 p-5 text-white md:p-7">
									<span class="max-w-[90%] whitespace-pre-line"><span class="bira-work__hook text-[clamp(2rem,3.4vw,4rem)] leading-[0.97] font-extrabold tracking-[-0.065em]"><?php echo esc_html( $project['hook'] ); ?></span></span>
									<span class="bira-swipe-arrow relative flex size-10 shrink-0 items-center justify-center overflow-hidden rounded-full bg-cream text-ink transition-colors group-hover:bg-green group-hover:text-white md:size-12">
										<?php echo bira_doodle( 'arrow', 'bira-swipe-arrow__out size-5' ); ?>
										<?php echo bira_doodle( 'arrow', 'bira-swipe-arrow__in absolute size-5' ); ?>
									</span>
								</div>
							</div>
							<div class="pt-5">
								<p class="text-[0.625rem] font-bold tracking-[0.1em] text-green uppercase"><?php echo esc_html( $project['cats'] ); ?></p>
								<h3 class="mt-2 text-lg font-bold tracking-[-0.04em] md:text-xl"><?php echo esc_html( $project['title'] ); ?></h3>
								<p class="mt-2 max-w-md text-sm leading-relaxed text-ink/60"><?php echo esc_html( $project['text'] ); ?></p>
							</div>
							<?php if ( $stats ) : ?>
								<div class="mt-4 flex flex-wrap gap-5 border-t border-ink/15 pt-3">
									<?php foreach ( $stats as $stat ) : ?>
										<?php [ $num, $label ] = array_pad( explode( ' ', $stat, 2 ), 2, '' ); ?>
										<span class="text-[0.625rem] font-semibold tracking-wide uppercase"><span class="mr-1.5 font-extrabold"><?php echo esc_html( $num ); ?></span><?php echo esc_html( $label ); ?></span>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						</a>
					</article>
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
