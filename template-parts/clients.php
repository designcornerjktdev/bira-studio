<?php
/**
 * Strip logo klien: wordmark teks dengan gaya berbeda-beda (placeholder sampai logo asli tersedia).
 */
$clients_title = bira_text( 'clients_title', 'Brands we’ve made some noise with' );
$clients       = bira_items( 'client', [
	[ 'name' => 'Relaxa', 'style' => 'italic font-extrabold tracking-tighter' ],
	[ 'name' => 'ABC', 'style' => 'font-black tracking-tighter' ],
	[ 'name' => 'Your Brand', 'style' => 'font-bold tracking-tighter' ],
	[ 'name' => 'Next Up', 'style' => 'font-black italic tracking-tight' ],
	[ 'name' => 'Watch This Space', 'style' => 'font-semibold tracking-tighter' ],
] );
?>
<section class="bira-clients border-t border-b border-ink/15 py-9 md:py-12 mt-12" aria-label="<?php esc_attr_e( 'Client logo strip', 'bira' ); ?>">
	<div class="bira-container">
		<h2 class="text-[0.6875rem] font-bold tracking-[0.14em] uppercase"><?php echo esc_html( $clients_title ); ?> <span class="ml-1 text-green" aria-hidden="true">✦</span></h2>
	</div>
	<div class="bira-marquee mt-7">
		<div class="bira-marquee__track bira-marquee__track--clients">
			<?php for ( $copy = 0; $copy < 2; $copy++ ) : ?>
				<?php foreach ( $clients as $client ) : ?>
					<span class="flex shrink-0 items-center gap-14 whitespace-nowrap text-2xl uppercase text-ink/45 md:gap-24 md:text-3xl <?php echo esc_attr( $client['style'] ); ?>"<?php echo $copy ? ' aria-hidden="true"' : ''; ?>>
						<?php echo esc_html( $client['name'] ); ?>
						<?php echo bira_doodle( 'star', 'size-4 text-green/55' ); ?>
					</span>
				<?php endforeach; ?>
			<?php endfor; ?>
		</div>
	</div>
</section>
