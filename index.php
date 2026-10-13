<?php
/**
 * Fallback untuk blog, arsip, dan pencarian.
 */
get_header();
?>
<div class="bira-container py-16"><div class="max-w-3xl">
	<h1 class="mb-10 font-display text-[length:clamp(2.5rem,6vw,5rem)] leading-none uppercase">
		<?php
		if ( is_home() && ! is_front_page() ) {
			single_post_title();
		} elseif ( is_search() ) {
			printf( esc_html__( 'Search: %s', 'bira' ), esc_html( get_search_query() ) );
		} elseif ( is_archive() ) {
			echo wp_kses_post( get_the_archive_title() );
		} else {
			bloginfo( 'name' );
		}
		?>
	</h1>

	<?php if ( have_posts() ) : ?>
		<div class="grid gap-10">
			<?php while ( have_posts() ) : the_post(); ?>
				<article <?php post_class(); ?>>
					<h2 class="font-display text-3xl uppercase"><a class="hover:text-green" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<p class="mt-1 text-sm opacity-60"><?php echo esc_html( get_the_date() ); ?></p>
					<div class="mt-3"><?php the_excerpt(); ?></div>
				</article>
			<?php endwhile; ?>
		</div>
		<div class="mt-12"><?php the_posts_pagination(); ?></div>
	<?php else : ?>
		<p><?php esc_html_e( 'Nothing found.', 'bira' ); ?></p>
	<?php endif; ?>
</div></div>
<?php
get_footer();
