<?php
/**
 * Homepage — section order follows the BIRA Website Guideline:
 * hero → trust → clients → services → process → work → team → pov → about → cta (template-parts file name = section id)
 */
get_header();

// Hero + trust are pinned together while the mockup fan opens (src/mockup.js).
echo '<div class="bg-cream" data-hero-pin>';
get_template_part( 'template-parts/hero' );
get_template_part( 'template-parts/trust' );
echo '</div>';
get_template_part( 'template-parts/clients' );
get_template_part( 'template-parts/services' );
get_template_part( 'template-parts/process' ); // contains the SVG scroll line (src/scroll-line.js)
get_template_part( 'template-parts/work' );
get_template_part( 'template-parts/team' );
get_template_part( 'template-parts/pov' );
get_template_part( 'template-parts/about' );
get_template_part( 'template-parts/cta' );

get_footer();
