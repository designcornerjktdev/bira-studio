<?php
/**
 * Post card for the blog archive (index.php). $args['featured'] = true → large card (image left, text right).
 * Called inside The Loop.
 */
$featured = ! empty( $args['featured'] );
$category = get_the_category()[0] ?? null;
$minutes  = bira_read_minutes();
?>
<article <?php post_class( 'group' . ( $featured ? ' bira-post--featured' : '' ) ); ?>>
	<a class="bira-post block focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-green" href="<?php the_permalink(); ?>">
		<div class="bira-post__media">
			<?php
			if ( has_post_thumbnail() ) {
				the_post_thumbnail( $featured ? 'full' : 'large', [ 'class' => 'bira-post__img', 'loading' => $featured ? 'eager' : 'lazy' ] );
			} else {
				echo bira_doodle( 'sparkle', 'absolute top-1/2 left-1/2 size-12 -translate-1/2 text-cream' );
			}
			?>
			<?php if ( $category ) : ?>
				<span class="absolute top-4 left-4 rounded-full border border-white/60 bg-black/20 px-3 py-1 text-sm font-semibold tracking-wider text-white uppercase backdrop-blur-sm"><?php echo esc_html( $category->name ); ?></span>
			<?php endif; ?>
		</div>

		<div class="bira-post__body">
			<p class="flex flex-wrap items-center gap-x-3 gap-y-1 text-sm tracking-wider text-ink/55 uppercase">
				<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
				<span aria-hidden="true">·</span>
				<span><?php echo esc_html( sprintf( _n( '%d min read', '%d min read', $minutes, 'bira' ), $minutes ) ); ?></span>
			</p>
			<h2 class="mt-3 font-bold tracking-[-0.03em] <?php echo $featured ? 'text-[clamp(1.75rem,3.2vw,3rem)] leading-[1.05]' : 'text-xl leading-snug md:text-2xl'; ?>"><?php the_title(); ?></h2>
			<p class="mt-3 text-base leading-relaxed text-ink/65 <?php echo $featured ? 'max-w-xl' : 'line-clamp-2'; ?>"><?php echo esc_html( wp_trim_words( get_the_excerpt(), $featured ? 36 : 22 ) ); ?></p>
			<span class="mt-5 inline-flex items-center gap-2 text-sm font-bold tracking-[0.12em] uppercase transition-colors group-hover:text-green">
				<?php esc_html_e( 'Read more', 'bira' ); ?>
				<?php echo bira_swipe_arrow(); ?>
			</span>
		</div>
	</a>
</article>
