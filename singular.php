<?php
/**
 * Single pages and posts.
 */
get_header();

while ( have_posts() ) :
	the_post();
	?>
	<article <?php post_class( 'bira-container py-16' ); ?>>
		<h1 class="font-display text-[length:clamp(2.5rem,6vw,5rem)] leading-none uppercase"><?php the_title(); ?></h1>
		<?php if ( is_single() ) : ?>
			<p class="mt-3 text-sm opacity-60"><?php echo esc_html( get_the_date() ); ?></p>
		<?php endif; ?>

		<div class="bira-content bira-body mt-10 max-w-3xl">
			<?php the_content(); ?>
		</div>
	</article>
	<?php
endwhile;

get_footer();
