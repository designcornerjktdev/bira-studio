<?php
/**
 * Work (The Good Stuff) — ALL projects in one horizontal row.
 * Desktop: the section is pinned and cards slide on scroll. Mobile: plain swipe (scroll-snap).
 * Lazy load: only the first BIRA_WORK_BATCH cards are rendered; the rest are fetched from
 * /wp-json/bira/v1/work as the row nears its end (src/work.js).
 * Cards without an image render as color blocks.
 */
$eyebrow  = bira_text( 'work_eyebrow', 'The good stuff' );
$title    = bira_text( 'work_title', 'Stuff we’re proud of' );
$batch    = bira_projects( 0, BIRA_WORK_BATCH );
$next     = count( $batch['items'] ) < $batch['total'] ? count( $batch['items'] ) : null; // offset of the next batch
// The samples below only show while no project is published.
$projects = $batch['items'] ?: [
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
<section id="work" class="bira-work bira-section relative isolate" aria-labelledby="work-title" data-work data-reveal-once>
	<?php echo bira_bg_doodles( [ [ 'star', 'top-[9%] right-[7%] size-9 rotate-[10deg]' ], [ 'wave', 'bottom-[6%] left-[38%] w-16' ] ] ); ?>
	<div class="bira-container">
		<header class="mb-12" data-reveal>
			<span class="bira-eyebrow text-green">[ <?php echo esc_html( $eyebrow ); ?> ]</span>
			<h2 id="work-title" class="bira-h2 mt-4"><?php echo esc_html( $title ); ?><span class="text-green">.</span></h2>
		</header>
	</div>

	<div class="bira-work__viewport" data-work-viewport>
		<ul class="bira-work__track" data-work-track<?php if ( null !== $next ) : ?> data-work-next="<?php echo (int) $next; ?>" data-work-endpoint="<?php echo esc_url( rest_url( 'bira/v1/work' ) ); ?>"<?php endif; ?>>
			<?php
			foreach ( $projects as $project ) {
				echo bira_work_item_html( $project ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in project-card.php
			}
			?>
			<li class="bira-work__item bira-work__item--outro" data-work-outro>
				<p class="max-w-xs text-sm leading-relaxed text-ink/65">
					<?php echo bira_doodle( 'next', 'mb-4 block size-6 text-green' ); ?>
					Different brands. Different communities.<br>
					<span class="font-semibold text-ink">Always something worth talking about.</span>
				</p>
			</li>
		</ul>
	</div>
</section>
