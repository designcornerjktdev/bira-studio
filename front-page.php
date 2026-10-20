<?php
/**
 * Homepage — urutan section mengikuti BIRA Website Guideline:
 * Hero → Trust → Clients → The Good Stuff → What We Do → How We Work → Made for the Feed → BIRA POV → Internet People → CTA
 */
get_header();

get_template_part( 'template-parts/hero' );
get_template_part( 'template-parts/trust' );
get_template_part( 'template-parts/clients' );
get_template_part( 'template-parts/selected-work' );
get_template_part( 'template-parts/what-we-do' );
get_template_part( 'template-parts/how-we-work' ); // berisi garis scroll SVG (src/scroll-line.js)
get_template_part( 'template-parts/made-for-the-feed' );
get_template_part( 'template-parts/bira-pov' );
get_template_part( 'template-parts/internet-people' );
get_template_part( 'template-parts/final-cta' );

get_footer();
