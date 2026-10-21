<?php
/**
 * Trust — angka pencapaian di atas strip brand (Clients). Angka terakhir berwarna hijau.
 * Konten dari Customizer → Homepage → Trust.
 */
$stats = bira_items( 'trust_stat', [
	[ 'number' => '50M+', 'label' => 'Views' ],
	[ 'number' => '1000+', 'label' => 'Content pieces' ],
	[ 'number' => '500+', 'label' => 'Creators' ],
] );
$last  = count( $stats ) - 1;
?>
<section id="trust" class="bira-trust bira-container mt-12" aria-label="<?php esc_attr_e( 'BIRA in numbers', 'bira' ); ?>">
	<dl class="grid gap-8 py-10 sm:grid-cols-3 md:py-12 justify-center">
		<?php foreach ( $stats as $i => $stat ) : ?>
			<div class="flex flex-col-reverse gap-2" data-reveal>
				<dt class="text-sm tracking-[0.12em] text-ink/60 uppercase"><?php echo esc_html( $stat['label'] ); ?></dt>
				<dd data-count class="m-0 text-[clamp(3rem,6vw,5.5rem)] leading-none font-extrabold tracking-[-0.04em] tabular-nums <?php echo $i === $last ? 'text-green' : ''; ?>"><?php echo esc_html( $stat['number'] ); ?></dd>
			</div>
		<?php endforeach; ?>
	</dl>
</section>
