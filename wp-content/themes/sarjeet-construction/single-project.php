<?php
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
while ( have_posts() ) : the_post();
	$loc      = get_post_meta( get_the_ID(), 'project_location', true );
	$value    = get_post_meta( get_the_ID(), 'project_value', true );
	$year     = get_post_meta( get_the_ID(), 'project_year', true );
	$length   = get_post_meta( get_the_ID(), 'project_length', true );
	$capacity = get_post_meta( get_the_ID(), 'project_capacity', true );
	$client   = get_post_meta( get_the_ID(), 'project_client', true );
	$terms    = get_the_terms( get_the_ID(), 'project_category' );
	$cat      = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';
	?>
	<main id="main" class="section">
		<div class="container">
			<article class="reveal">
				<span class="eyebrow"><?php echo esc_html( $loc . ( $year ? ' · ' . $year : '' ) ); ?></span>
				<h1 style="margin-top:12px"><?php the_title(); ?></h1>

				<?php if ( has_post_thumbnail() ) : ?>
					<div class="modal-img card-img" style="margin:32px 0">
						<?php the_post_thumbnail( 'sarjeet_hero', [ 'style' => 'width:100%;height:100%;object-fit:cover' ] ); ?>
					</div>
				<?php endif; ?>

				<div class="modal-stats">
					<?php if ( $value ) : ?><div><span class="eyebrow">Value</span><strong><?php echo esc_html( $value ); ?></strong></div><?php endif; ?>
					<?php if ( $length ) : ?><div><span class="eyebrow">Length</span><strong><?php echo esc_html( $length ); ?></strong></div><?php endif; ?>
					<?php if ( $capacity ) : ?><div><span class="eyebrow">Capacity</span><strong><?php echo esc_html( $capacity ); ?></strong></div><?php endif; ?>
					<?php if ( $client ) : ?><div><span class="eyebrow">Client</span><strong style="font-size:18px"><?php echo esc_html( $client ); ?></strong></div><?php endif; ?>
				</div>

				<div class="lede" style="max-width:56ch"><?php the_content(); ?></div>

				<p style="margin-top:32px"><a href="<?php echo esc_url( home_url( '/#projects' ) ); ?>" class="btn btn--ghost">← Back to projects</a></p>
			</article>
		</div>
	</main>
<?php endwhile;
get_footer();
