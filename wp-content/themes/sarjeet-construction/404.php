<?php
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
?>
<main id="main" class="section">
	<div class="container">
		<span class="eyebrow">404</span>
		<h1>Page not found.</h1>
		<p class="lede">The page you requested doesn't exist or has moved.</p>
		<p><a class="btn" href="<?php echo esc_url( home_url( '/' ) ); ?>">Back to home <span class="arrow">→</span></a></p>
	</div>
</main>
<?php get_footer();
