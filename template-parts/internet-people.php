<?php
/**
 * 06 Internet People — manusiakan studionya.
 */
$title    = bira_text( 'people_title', 'From Indonesia, into the feed' );
$eyebrow  = bira_text( 'people_eyebrow', 'Meet the internet people' );
$intro    = bira_text( 'people_intro_2', 'We bring together strategists, creatives, creators and communities to turn brand ideas into conversations people actually want to join.' );
$more_url = bira_text( 'people_more_url', '/about' );
$people = bira_items( 'people_member', [
	[ 'photo' => 0, 'name' => '', 'role' => 'Founder & CD' ],
	[ 'photo' => 0, 'name' => '', 'role' => 'Strategy' ],
	[ 'photo' => 0, 'name' => '', 'role' => 'Content' ],
	[ 'photo' => 0, 'name' => '', 'role' => 'Creative' ],
	[ 'photo' => 0, 'name' => '', 'role' => 'Production' ],
] );
?>
<section id="internet-people" class="bira-section relative overflow-clip" aria-labelledby="people-title">
	<svg class="bira-watermark" viewBox="0 0 320 1000" aria-hidden="true" focusable="false">
		<text x="160" y="500" transform="rotate(-90 160 500)" text-anchor="middle" dominant-baseline="central">BIR<tspan dx="28">A</tspan></text>
	</svg>
	<div class="bira-container bira-grid relative gap-y-12">
		<header class="lg:col-span-6" data-reveal>
			<span class="bira-eyebrow text-green">[ <?php echo esc_html( $eyebrow ); ?> ]</span>
			<h2 id="people-title" class="bira-h2 mt-4"><?php echo esc_html( $title ); ?><span class="text-green">.</span></h2>
		</header>
		<div class="self-end lg:col-span-5 lg:col-start-8" data-reveal>
			<p class="bira-body mt-4 max-w-md whitespace-pre-line text-ink/65"><?php echo esc_html( $intro ); ?></p>
			<a class="bira-link mt-8 inline-flex min-h-11 items-center gap-2 font-bold tracking-[0.12em] uppercase" href="<?php echo esc_url( home_url( $more_url ) ); ?>">
				<span class="bira-link__text">A little more about us</span>
				<?php echo bira_doodle( 'next', 'bira-link__arrow size-3.5' ); ?>
			</a>
		</div>

		<ul class="bira-paper grid gap-6 sm:grid-cols-2 lg:grid-cols-5" data-paper>
			<?php foreach ( $people as $i => $person ) : ?>
				<li class="bira-paper__card">
					<p class="text-sm font-semibold tracking-[0.2em] uppercase">
						<span class="text-green"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
						<?php echo esc_html( $person['role'] ); ?>
					</p>
					<div class="bira-person__photo mt-3">
						<?php
						if ( ! empty( $person['photo'] ) ) {
							echo wp_get_attachment_image( (int) $person['photo'], 'medium_large', false, [ 'loading' => 'lazy' ] );
						} else {
							echo bira_doodle( 'star', 'size-10 text-cream' );
						}
						?>
					</div>
					<?php if ( $person['name'] ) : ?>
						<p class="mt-3 font-semibold"><?php echo esc_html( $person['name'] ); ?></p>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
