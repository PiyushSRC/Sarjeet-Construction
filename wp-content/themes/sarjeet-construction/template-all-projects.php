<?php
/**
 * Template Name: All Projects (virtual)
 *
 * Loaded via template_include when the request carries ?view=all-projects.
 * Renders every project from sarjeet_projects() in the existing uniform
 * card grid, so visitors can browse the full set in one place.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$projects = sarjeet_projects();
$cats     = sarjeet_project_categories();
$counts   = [ 'All' => count( $projects ) ];
foreach ( $projects as $p ) {
	$counts[ $p->cat ] = ( $counts[ $p->cat ] ?? 0 ) + 1;
}

get_header();
?>

<main id="main" class="all-projects">

	<section class="all-projects__hero section">
		<div class="container">
			<nav class="all-projects__crumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'sarjeet' ); ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'sarjeet' ); ?></a>
				<span aria-hidden="true">·</span>
				<span class="all-projects__crumb-current"><?php esc_html_e( 'All Projects', 'sarjeet' ); ?></span>
			</nav>
			<span class="eyebrow"><?php esc_html_e( '02 — Selected Works · 2020 — 2026', 'sarjeet' ); ?></span>
			<h1 class="all-projects__title"><?php esc_html_e( 'All Projects', 'sarjeet' ); ?></h1>
			<p class="all-projects__lede"><?php
				printf(
					/* translators: %d project count */
					esc_html__( '%d headline projects across five states — sewer networks, water-supply schemes, and urban infrastructure delivered for state boards, ADB-funded programmes and municipal corporations.', 'sarjeet' ),
					count( $projects )
				);
			?></p>
		</div>
	</section>

	<section class="all-projects__body section projects">
		<div class="container">

			<div class="projects-tools">
				<div class="filters" role="tablist">
					<?php foreach ( $cats as $c ) : $count = (int) ( $counts[ $c ] ?? 0 ); ?>
						<button class="filter" type="button" data-filter="<?php echo esc_attr( $c ); ?>" aria-pressed="<?php echo $c === 'All' ? 'true' : 'false'; ?>">
							<?php echo esc_html( $c ); ?> <span class="count"><?php echo esc_html( str_pad( (string) $count, 2, '0', STR_PAD_LEFT ) ); ?></span>
						</button>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="proj-grid" data-layout="uniform" id="proj-grid">
				<?php foreach ( $projects as $p ) :
					$href = ! empty( $p->permalink ) && $p->permalink !== '#'
						? $p->permalink
						: home_url( '/?project_view=' . rawurlencode( $p->slug ?? '' ) );
				?>
					<article
						class="proj-card proj-card--noimg <?php echo esc_attr( $p->shape ?: 'default' ); ?>"
						data-cat="<?php echo esc_attr( $p->cat ); ?>">
						<div class="proj-card__head">
							<span class="proj-num">P/<?php echo esc_html( $p->n ); ?></span>
							<span class="proj-cat"><?php echo esc_html( $p->cat ); ?></span>
						</div>
						<div class="proj-meta">
							<h3 class="proj-title">
								<a class="proj-card__link" href="<?php echo esc_url( $href ); ?>"><?php echo esc_html( $p->title ); ?></a>
							</h3>
							<span class="proj-value"><?php echo esc_html( $p->value ); ?></span>
							<span class="proj-loc">
								<svg width="10" height="10" viewBox="0 0 10 10" fill="none" stroke="currentColor"><circle cx="5" cy="4" r="2.2" /><path d="M5 6.2 V9" /></svg>
								<?php echo esc_html( $p->loc ); ?>
							</span>
							<p class="proj-desc"><?php echo esc_html( $p->desc ); ?></p>
						</div>
					</article>
				<?php endforeach; ?>
			</div>

		</div>
	</section>


</main>

<?php
get_footer();
