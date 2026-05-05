<?php
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
?>
<main id="main" class="section">
	<div class="container">
		<?php while ( have_posts() ) : the_post(); ?>
			<article class="reveal">
				<h1><?php the_title(); ?></h1>
				<div class="lede"><?php the_content(); ?></div>
			</article>
		<?php endwhile; ?>
	</div>
</main>
<?php get_footer();
