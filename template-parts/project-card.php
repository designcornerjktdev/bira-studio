<?php
/**
 * Kartu project — dipakai The Good Stuff (homepage) dan arsip /work.
 * $args['project']: data dari bira_project_data() atau contoh statis.
 * 'tone' (green/sand/ink) hanya dipakai kartu contoh; project asli tanpa gambar berlatar hijau.
 */
$project = $args['project'];
$stats   = array_filter( array_map( 'trim', explode( ',', (string) $project['stats'] ) ) );
$url     = str_starts_with( $project['url'], 'http' ) ? $project['url'] : home_url( $project['url'] );
?>
<article class="group">
	<a class="block focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-green" href="<?php echo esc_url( $url ); ?>">
		<div class="bira-work__media<?php echo empty( $project['tone'] ) ? '' : ' bira-work__media--' . esc_attr( $project['tone'] ); ?>">
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
