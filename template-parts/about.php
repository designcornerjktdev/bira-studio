<?php
/**
 * About — the origin of the BIRA name (Bira Island, Thousand Islands).
 * Image on the left, short story + large headline on the right. Content from Customizer → Homepage → About.
 */
$image    = (int) bira_text( 'origin_image', '0' );
$caption  = bira_text( 'origin_caption' );
$intro    = bira_text( 'origin_intro', 'We borrowed our name from Pulau Bira in Kepulauan Seribu. Small on the map, but that’s kind of the point.' );
$headline = rtrim( bira_text( 'origin_headline', 'You don’t have to be the biggest to make waves' ), '. ' );
// Map marker below the image: handwritten name + arrow, region name, coordinates.
$pin_name   = bira_text( 'origin_pin_name', 'Bira' );
$pin_region = bira_text( 'origin_pin_region', 'Kepulauan Seribu' );
$pin_coords = array_filter( array_map( 'trim', explode( ',', bira_text( 'origin_pin_coords', '5.7300° S, 106.6275° E' ) ) ) );
?>
<section id="about" class="bira-section" aria-labelledby="about-title">
	<div class="bira-container grid items-center gap-10 md:grid-cols-2 md:gap-16 md:pb-40 lg:gap-24"><?php // pb: room for the marker hanging below the image ?>
		<figure class="relative m-0" data-reveal>
			<?php
			// Image shown as-is (original size & ratio, no background/crop), suited to transparent PNGs.
			if ( $image ) {
				echo wp_get_attachment_image( $image, 'large', false, [ 'class' => 'block h-auto w-full', 'loading' => 'lazy' ] );
			}
			?>
			<?php if ( $caption || $pin_name || $pin_region || $pin_coords ) : ?>
				<?php // Desktop: this row is out of flow (absolute) so only the image is vertically centered with the text. ?>
				<div class="mt-5 flex items-start justify-between gap-6 md:absolute md:inset-x-0 md:top-full">
					<?php if ( $caption ) : ?>
						<figcaption class="text-sm text-ink/55"><?php echo esc_html( $caption ); ?></figcaption>
					<?php endif; ?>
					<?php if ( $pin_name || $pin_region || $pin_coords ) : ?>
						<?php // Map marker outside the image; the arrow points up at the island in the image. ?>
						<div class="bira-pin" aria-hidden="true">
							<?php if ( $pin_name ) : ?>
								<svg class="bira-pin__arrow" viewBox="0 0 70 80" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" focusable="false">
									<path d="M64 74C38 70 16 52 10 8"/>
									<path d="M2 20 10 6l11 12"/>
								</svg>
								<span class="bira-pin__name"><?php echo esc_html( $pin_name ); ?></span>
							<?php endif; ?>
							<?php if ( $pin_region ) : ?>
								<span class="bira-pin__region"><?php echo esc_html( $pin_region ); ?></span>
							<?php endif; ?>
							<?php if ( $pin_coords ) : ?>
								<span class="bira-pin__coords"><?php echo implode( '<br>', array_map( 'esc_html', $pin_coords ) ); ?></span>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</figure>

		<div data-reveal>
			<p class="max-w-lg text-base leading-relaxed text-ink/75 md:text-lg"><?php echo esc_html( $intro ); ?></p>
			<h2 id="about-title" class="bira-h2 mt-8 text-[clamp(2.5rem,4.6vw,4.75rem)] leading-[0.98] font-extrabold tracking-[-0.02em]"><?php echo esc_html( $headline ); ?><span class="text-green">.</span></h2>
		</div>
	</div>
</section>
