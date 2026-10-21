<?php
/**
 * Detail post blog. (Page biasa tetap memakai singular.php, project memakai single-bira_project.php.)
 * Progress baca → kembali ke arsip + meta → judul besar + ringkasan → gambar lebar →
 * isi (kolom baca nyaman) dengan bagikan di samping → tag → post terkait → Final CTA.
 */
get_header();
the_post();

$blog_url   = get_option( 'page_for_posts' ) ? get_permalink( (int) get_option( 'page_for_posts' ) ) : home_url( '/' );
$category   = get_the_category()[0] ?? null;
$tags       = get_the_tags() ?: [];
$minutes    = bira_read_minutes();
$permalink  = rawurlencode( get_permalink() );
// Judul untuk teks share: entity HTML (mis. &#8217;) didekode dulu supaya tidak tampil mentah di X/WhatsApp.
$share_text = rawurlencode( html_entity_decode( wp_strip_all_tags( get_the_title() ), ENT_QUOTES, get_bloginfo( 'charset' ) ) );
$shares     = [
	'LinkedIn' => "https://www.linkedin.com/sharing/share-offsite/?url={$permalink}",
	'X'        => "https://x.com/intent/post?url={$permalink}&text={$share_text}",
	'WhatsApp' => "https://wa.me/?text={$share_text}%20{$permalink}",
];
$related = new WP_Query( [
	'post_type'           => 'post',
	'posts_per_page'      => 3,
	'post__not_in'        => [ get_the_ID() ],
	'category__in'        => $category ? [ $category->term_id ] : [],
	'ignore_sticky_posts' => true,
	'no_found_rows'       => true,
] );
?>
<div class="bira-read-progress" aria-hidden="true"></div>

<article <?php post_class( 'bira-article' ); ?>>
	<header class="bira-section relative isolate overflow-clip pb-12 md:pb-16">
		<?php echo bira_bg_doodles( [ [ 'sparkle', 'top-[18%] right-[9%] size-9 rotate-[12deg]' ] ] ); ?>
		<div class="bira-container">
			<a class="group inline-flex min-h-11 items-center gap-2 text-sm font-bold tracking-[0.12em] text-ink/65 uppercase transition-colors hover:text-green" href="<?php echo esc_url( $blog_url ); ?>">
				<?php echo bira_doodle( 'next', 'size-3.5 rotate-180 transition-transform group-hover:-translate-x-1' ); ?>
				<span><?php esc_html_e( 'All notes', 'bira' ); ?></span>
			</a>

			<p class="mt-10 flex flex-wrap items-center gap-x-3 gap-y-2 text-sm tracking-wider text-ink/55 uppercase">
				<?php if ( $category ) : ?>
					<a class="bira-pill is-active min-h-0 px-3 py-1" href="<?php echo esc_url( get_category_link( $category ) ); ?>"><?php echo esc_html( $category->name ); ?></a>
				<?php endif; ?>
				<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
				<span aria-hidden="true">·</span>
				<span><?php echo esc_html( sprintf( _n( '%d min read', '%d min read', $minutes, 'bira' ), $minutes ) ); ?></span>
				<span aria-hidden="true">·</span>
				<span><?php echo esc_html( sprintf( __( 'By %s', 'bira' ), get_the_author() ) ); ?></span>
			</p>

			<h1 class="bira-h2 bira-h2--static mt-6 max-w-5xl text-[clamp(2.5rem,6vw,5.5rem)] leading-[0.98] font-extrabold tracking-[-0.03em] normal-case"><?php the_title(); ?></h1>
			<?php if ( has_excerpt() ) : ?>
				<p class="mt-8 max-w-2xl text-lg leading-relaxed text-ink/70 md:text-xl"><?php echo esc_html( get_the_excerpt() ); ?></p>
			<?php endif; ?>
		</div>
	</header>

	<?php if ( has_post_thumbnail() ) : ?>
		<?php // Container di <div>, bukan di <figure>: CSS bawaan WordPress "figure { margin: … }" (tanpa layer) menimpa margin auto container. ?>
		<div class="bira-container">
			<figure class="m-0">
				<div class="relative aspect-[16/9] overflow-hidden rounded-md bg-sand/30">
					<?php the_post_thumbnail( 'full', [ 'class' => 'absolute inset-0 size-full object-cover', 'loading' => 'eager', 'sizes' => '100vw' ] ); ?>
				</div>
				<?php if ( get_the_post_thumbnail_caption() ) : ?>
					<figcaption class="mt-3 text-sm text-ink/55"><?php the_post_thumbnail_caption(); ?></figcaption>
				<?php endif; ?>
			</figure>
		</div>
	<?php endif; ?>

	<div class="bira-container grid gap-12 py-16 md:py-24 lg:grid-cols-12">
		<?php // Bagikan: kolom kiri yang menempel saat scroll (desktop), di bawah isi (mobile). ?>
		<aside class="order-2 lg:order-1 lg:col-span-2" aria-label="<?php esc_attr_e( 'Share', 'bira' ); ?>">
			<div class="lg:sticky lg:top-32">
				<p class="bira-eyebrow mb-4 text-ink/50"><?php esc_html_e( 'Share', 'bira' ); ?></p>
				<ul class="flex flex-wrap gap-x-6 gap-y-1 lg:grid">
					<?php foreach ( $shares as $name => $href ) : ?>
						<li>
							<a class="bira-link group inline-flex min-h-9 items-center gap-2 text-sm font-bold tracking-[0.12em] uppercase" href="<?php echo esc_url( $href ); ?>" target="_blank" rel="noopener">
								<span class="bira-link__text"><?php echo esc_html( $name ); ?></span>
								<?php echo bira_swipe_arrow(); ?>
							</a>
						</li>
					<?php endforeach; ?>
					<li>
						<?php // Salin link (src/share.js). Selalu jalan, juga di situs lokal yang tidak bisa diambil LinkedIn. ?>
						<button type="button" class="bira-link group inline-flex min-h-9 items-center gap-2 text-sm font-bold tracking-[0.12em] uppercase" data-copy-link="<?php echo esc_url( get_permalink() ); ?>">
							<span class="bira-link__text" data-copy-label><?php esc_html_e( 'Copy link', 'bira' ); ?></span>
							<span class="sr-only" aria-live="polite" data-copy-status></span>
						</button>
					</li>
				</ul>
			</div>
		</aside>

		<div class="order-1 lg:order-2 lg:col-span-10">
			<div class="bira-content bira-content--article">
				<?php the_content(); ?>
			</div>
			<?php
			wp_link_pages( [
				'before' => '<nav class="bira-pagination"><div class="nav-links">',
				'after'  => '</div></nav>',
			] );
			?>

			<?php if ( $tags ) : ?>
				<div class="mt-14 flex flex-wrap gap-2 border-t border-ink/15 pt-8">
					<?php foreach ( $tags as $tag ) : ?>
						<a class="bira-pill" href="<?php echo esc_url( get_tag_link( $tag ) ); ?>">#<?php echo esc_html( $tag->name ); ?></a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</article>

<?php if ( $related->have_posts() ) : ?>
	<section class="bira-section bira-pov-section" aria-labelledby="related-title">
		<div class="bira-container">
			<header class="mb-12 flex flex-col justify-between gap-6 sm:flex-row sm:items-end">
				<div>
					<span class="bira-eyebrow text-green">[ <?php esc_html_e( 'Keep scrolling', 'bira' ); ?> ]</span>
					<h2 id="related-title" class="bira-h2 bira-h2--static mt-4"><?php esc_html_e( 'More notes', 'bira' ); ?><span class="text-green">.</span></h2>
				</div>
				<a class="bira-link group inline-flex min-h-11 w-fit items-center gap-2 font-bold tracking-[0.12em] uppercase" href="<?php echo esc_url( $blog_url ); ?>">
					<span class="bira-link__text"><?php esc_html_e( 'All notes', 'bira' ); ?></span>
					<?php echo bira_doodle( 'next', 'bira-link__arrow size-3.5' ); ?>
				</a>
			</header>
			<div class="grid gap-x-8 gap-y-14 sm:grid-cols-2 lg:grid-cols-3">
				<?php
				while ( $related->have_posts() ) {
					$related->the_post();
					get_template_part( 'template-parts/post-card' );
				}
				wp_reset_postdata();
				?>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php
get_template_part( 'template-parts/cta' );
get_footer();
