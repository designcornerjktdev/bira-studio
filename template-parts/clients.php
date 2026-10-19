<?php
/**
 * Strip logo klien: wordmark teks dengan gaya berbeda-beda (placeholder sampai logo asli tersedia).
 */
$clients_title = bira_text( 'clients_title', 'Brands we’ve made some noise with' );
// Satu textarea dipisah koma → array nama brand.
$names = array_values( array_filter( array_map( 'trim', explode( ',', bira_text( 'clients_list', 'Relaxa, ABC, Your Brand, Next Up, Watch This Space' ) ) ) ) );
// Gaya wordmark bergantian supaya strip tetap terlihat seperti deretan logo berbeda.
$styles  = [
	'italic font-extrabold tracking-tighter',
	'font-black tracking-tighter',
	'font-bold tracking-tighter',
	'font-black italic tracking-tight',
	'font-semibold tracking-tighter',
];
$clients = array_map( static fn( string $name, int $i ): array => [ 'name' => $name, 'style' => $styles[ $i % count( $styles ) ] ], $names, array_keys( $names ) );
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
