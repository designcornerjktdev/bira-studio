<?php
get_header();
?>
<div class="bira-container py-24">
	<h1 class="font-display text-[length:clamp(3rem,9vw,9rem)] leading-none uppercase">404</h1>
	<p class="my-6"><?php esc_html_e( 'This page could not be found.', 'bira' ); ?></p>
	<?php echo bira_button( __( 'Back home', 'bira' ), home_url( '/' ) ); ?>
</div>
<?php
get_footer();
