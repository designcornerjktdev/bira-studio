<?php
/**
 * Homepage sections, in order (template-parts file name = section id):
 * hero → about → services → process → trust → clients → work → team → pov → cta
 */
get_header();

get_template_part( 'template-parts/hero' );
get_template_part( 'template-parts/about' );
get_template_part( 'template-parts/services' );
get_template_part( 'template-parts/process' ); // contains the SVG scroll line (src/scroll-line.js)
get_template_part( 'template-parts/trust' );
get_template_part( 'template-parts/clients' );
get_template_part( 'template-parts/work' );
get_template_part( 'template-parts/team' );
get_template_part( 'template-parts/pov' );
get_template_part( 'template-parts/cta' );

get_footer();
