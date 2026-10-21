<?php
/**
 * Arsip post: halaman blog, kategori, tag, penulis, tanggal, dan pencarian.
 * Hero (eyebrow + judul besar) → filter kategori → post terbaru besar (halaman 1) → grid kartu → paginasi.
 */
get_header();

$blog_url = get_option( 'page_for_posts' ) ? get_permalink( (int) get_option( 'page_for_posts' ) ) : home_url( '/' );
if ( is_search() ) {
	$eyebrow = __( 'Search results', 'bira' );
	/* translators: %s: search query */
	$title = sprintf( __( '“%s”', 'bira' ), get_search_query() );
} elseif ( is_category() || is_tag() || is_tax() ) {
	$eyebrow = is_category() ? __( 'Category', 'bira' ) : __( 'Topic', 'bira' );
	$title   = single_term_title( '', false );
} elseif ( is_archive() ) {
	$eyebrow = __( 'Archive', 'bira' );
	$title   = wp_strip_all_tags( get_the_archive_title() );
} else {
	$eyebrow = __( 'Notes from the feed', 'bira' );
	$title   = get_option( 'page_for_posts' ) ? get_the_title( (int) get_option( 'page_for_posts' ) ) : __( 'Journal', 'bira' );
}
$description = is_search() ? '' : wp_strip_all_tags( get_the_archive_description() );
$categories  = is_search() ? [] : get_categories( [ 'hide_empty' => true, 'orderby' => 'count', 'order' => 'DESC', 'number' => 8 ] );
$current_cat = is_category() ? get_queried_object_id() : 0;
$first_page  = ! is_paged();
?>
<section class="bira-section relative isolate overflow-clip pb-0 md:pb-0" aria-labelledby="archive-title">
	<?php echo bira_bg_doodles( [ [ 'sparkle', 'top-[20%] right-[10%] size-9 rotate-[12deg]' ] ] ); ?>
	<div class="bira-container">
		<span class="bira-eyebrow text-green">[ <?php echo esc_html( $eyebrow ); ?> ]</span>
		<h1 id="archive-title" class="bira-h2 bira-h2--static mt-6 text-[clamp(3rem,7vw,6.5rem)] leading-[0.95] font-extrabold tracking-[-0.03em]"><?php echo esc_html( rtrim( $title, '. ' ) ); ?><span class="text-green">.</span></h1>
		<?php if ( $description ) : ?>
			<p class="mt-6 max-w-xl text-base leading-relaxed text-ink/65"><?php echo esc_html( $description ); ?></p>
		<?php endif; ?>

		<?php if ( count( $categories ) > 1 ) : ?>
			<nav class="mt-10 flex flex-wrap gap-2 border-y border-ink/15 py-5" aria-label="<?php esc_attr_e( 'Categories', 'bira' ); ?>">
				<a class="bira-pill<?php echo $current_cat ? '' : ' is-active'; ?>" href="<?php echo esc_url( $blog_url ); ?>"<?php echo $current_cat ? '' : ' aria-current="page"'; ?>><?php esc_html_e( 'All', 'bira' ); ?></a>
				<?php foreach ( $categories as $cat ) : ?>
					<a class="bira-pill<?php echo $current_cat === $cat->term_id ? ' is-active' : ''; ?>" href="<?php echo esc_url( get_category_link( $cat ) ); ?>"<?php echo $current_cat === $cat->term_id ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $cat->name ); ?></a>
				<?php endforeach; ?>
			</nav>
		<?php endif; ?>
	</div>
</section>

<section class="bira-section">
	<div class="bira-container">
		<?php if ( have_posts() ) : ?>
			<?php
			// Halaman pertama: post terbaru tampil besar, sisanya di grid.
			if ( $first_page ) {
				the_post();
				get_template_part( 'template-parts/post-card', null, [ 'featured' => true ] );
			}
			?>
			<?php if ( have_posts() ) : ?>
				<div class="grid gap-x-8 gap-y-14 sm:grid-cols-2 lg:grid-cols-3<?php echo $first_page ? ' mt-16 border-t border-ink/15 pt-16' : ''; ?>">
					<?php
					while ( have_posts() ) {
						the_post();
						get_template_part( 'template-parts/post-card' );
					}
					?>
				</div>
			<?php endif; ?>

			<?php
			the_posts_pagination( [
				'class'     => 'bira-pagination',
				'mid_size'  => 1,
				'prev_text' => '←<span class="sr-only"> ' . esc_html__( 'Previous', 'bira' ) . '</span>',
				'next_text' => '<span class="sr-only">' . esc_html__( 'Next', 'bira' ) . ' </span>→',
			] );
			?>
		<?php else : ?>
			<div class="border-y border-ink/15 py-16">
				<p class="text-2xl font-bold tracking-[-0.03em]"><?php esc_html_e( 'Nothing here yet.', 'bira' ); ?></p>
				<p class="mt-3 text-base text-ink/65"><?php esc_html_e( 'Try another topic, or head back to the good stuff.', 'bira' ); ?></p>
				<div class="mt-8"><?php echo bira_button( __( 'Back to BIRA', 'bira' ), home_url( '/' ) ); ?></div>
			</div>
		<?php endif; ?>
	</div>
</section>
<?php
get_template_part( 'template-parts/cta' );
get_footer();
