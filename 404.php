<?php
/**
 * 404 — "This one didn't post." (struktur dari prototype bira-preview).
 */
get_header();
?>
<section class="bira-section relative isolate overflow-clip" aria-labelledby="not-found-title">
	<?php echo bira_bg_doodles( [ [ 'sparkle', 'top-[18%] right-[14%] size-10 rotate-[12deg]' ], [ 'wave', 'bottom-[16%] left-[40%] w-16' ] ] ); ?>
	<div class="bira-container">
		<span class="bira-eyebrow text-green">404 / A little off-feed</span>
		<h1 id="not-found-title" class="bira-h2 bira-h2--static mt-8 text-[clamp(3.5rem,9vw,8rem)] leading-[0.95] font-extrabold tracking-[-0.03em]">This one<br>didn’t post<span class="text-green">.</span></h1>
		<p class="mt-8 max-w-md text-base text-ink/65">That page isn’t here. The good stuff still is.</p>
		<div class="mt-10 flex flex-wrap items-center gap-6">
			<?php echo bira_button( __( 'Back to BIRA', 'bira' ), home_url( '/' ) ); ?>
			<a class="bira-link inline-flex min-h-11 items-center gap-2 font-bold tracking-[0.12em] uppercase" href="<?php echo esc_url( home_url( '/#work' ) ); ?>">
				<span class="bira-link__text"><?php esc_html_e( 'See our work', 'bira' ); ?></span>
				<?php echo bira_doodle( 'next', 'bira-link__arrow size-3.5' ); ?>
			</a>
		</div>
	</div>
</section>
<?php
get_footer();
