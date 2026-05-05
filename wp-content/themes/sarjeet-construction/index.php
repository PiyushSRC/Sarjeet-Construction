<?php
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
?>
<main id="main" class="section">
	<div class="container">
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<article class="reveal">
				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<?php the_excerpt(); ?>
			</article>
		<?php endwhile; else : ?>
			<p>Nothing here yet.</p>
		<?php endif; ?>
	</div>
</main>
<?php get_footer();
