<?php
/**
 * Homepage — urutan section mengikuti BIRA Website Guideline:
 * hero → trust → clients → services → process → work → team → pov → cta (nama file template-parts = id section)
 */
get_header();

get_template_part( 'template-parts/hero' );
get_template_part( 'template-parts/trust' );
get_template_part( 'template-parts/clients' );
get_template_part( 'template-parts/services' );
get_template_part( 'template-parts/process' ); // berisi garis scroll SVG (src/scroll-line.js)
get_template_part( 'template-parts/work' );
get_template_part( 'template-parts/team' );
get_template_part( 'template-parts/pov' );
get_template_part( 'template-parts/cta' );

get_footer();
