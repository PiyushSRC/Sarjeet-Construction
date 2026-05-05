<?php
/**
 * Template Name: Project Detail (virtual)
 *
 * Loaded via template_include when the request carries ?project_view=<slug>.
 * Looks up the project by slug from sarjeet_projects() and renders a
 * full single-project page using the same chrome (header/footer) and
 * design language as the rest of the theme.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$slug    = sanitize_title( (string) get_query_var( 'project_view' ) );
$project = $slug ? sarjeet_project_by_slug( $slug ) : null;

if ( ! $project ) {
	status_header( 404 );
	nocache_headers();
}

get_header();

if ( ! $project ) :
	?>
	<main id="main" class="section project-detail project-detail--missing">
		<div class="container">
			<span class="eyebrow">Project not found</span>
			<h1 class="project-detail__title">We couldn’t find that project.</h1>
			<p class="lede">The project you’re looking for either moved or doesn’t exist yet. Head back to the projects index and pick another one.</p>
			<p style="margin-top:24px"><a class="btn btn--ghost" href="<?php echo esc_url( home_url( '/#projects' ) ); ?>">← All projects</a></p>
		</div>
	</main>
	<?php
	get_footer();
	return;
endif;

$status_class = '';
$y = strtolower( trim( (string) $project->year ) );
if ( $y === 'completed' ) $status_class = 'completed';
elseif ( $y === 'ongoing' ) $status_class = 'ongoing';

$summary = [
	'Value'    => $project->value,
	'Status'   => $project->year,
	'Location' => $project->loc,
	'Length'   => $project->length,
	'Capacity' => $project->capacity,
	'Client'   => $project->client,
];

$detail_specs = is_array( $project->specs ?? null ) ? $project->specs : [];
$partners     = is_array( $project->partners ?? null ) ? $project->partners : [];

$related = sarjeet_related_projects( $project->slug ?? '', 3, $project->cat ?? '' );
?>

<main id="main" class="project-detail">

	<section class="project-detail__hero section">
		<div class="container">
			<nav class="project-detail__crumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'sarjeet' ); ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'sarjeet' ); ?></a>
				<span aria-hidden="true">·</span>
				<a href="<?php echo esc_url( home_url( '/#projects' ) ); ?>"><?php esc_html_e( 'Projects', 'sarjeet' ); ?></a>
				<span aria-hidden="true">·</span>
				<span class="project-detail__crumb-current">P/<?php echo esc_html( $project->n ); ?></span>
			</nav>

			<div class="project-detail__hero-row reveal">
				<div class="project-detail__hero-text">
					<span class="eyebrow project-detail__eyebrow">
						<span><?php echo esc_html( $project->cat ); ?></span>
						<span aria-hidden="true">·</span>
						<span>P/<?php echo esc_html( $project->n ); ?></span>
						<?php if ( $status_class ) : ?>
							<span class="project-detail__status project-detail__status--<?php echo esc_attr( $status_class ); ?>">
								<span class="project-detail__status-dot" aria-hidden="true"></span>
								<?php echo esc_html( $project->year ); ?>
							</span>
						<?php endif; ?>
					</span>
					<h1 class="project-detail__title"><?php echo esc_html( $project->title ); ?></h1>
					<p class="project-detail__loc">
						<svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true"><path d="M7 13s4.5-4.2 4.5-7.5A4.5 4.5 0 0 0 2.5 5.5C2.5 8.8 7 13 7 13Z"/><circle cx="7" cy="5.5" r="1.6"/></svg>
						<?php echo esc_html( $project->loc ); ?>
					</p>
				</div>
				<div class="project-detail__hero-value">
					<span class="eyebrow"><?php esc_html_e( 'Project Value', 'sarjeet' ); ?></span>
					<strong><?php echo esc_html( $project->value ?: '—' ); ?></strong>
				</div>
			</div>
		</div>
	</section>

	<section class="project-detail__body section">
		<div class="container">
			<div class="project-detail__layout">

				<article class="project-detail__main reveal">
					<span class="eyebrow"><?php esc_html_e( 'Overview', 'sarjeet' ); ?></span>
					<p class="lede project-detail__lede"><?php echo esc_html( $project->desc ); ?></p>

					<div class="project-detail__block">
						<span class="eyebrow"><?php esc_html_e( 'Client &amp; Partners', 'sarjeet' ); ?></span>
						<dl class="project-detail__client">
							<div class="project-detail__client-row">
								<dt><?php esc_html_e( 'Client', 'sarjeet' ); ?></dt>
								<dd><?php echo esc_html( $project->client ?: '—' ); ?></dd>
							</div>
							<?php if ( ! empty( $partners ) ) : ?>
								<div class="project-detail__client-row">
									<dt><?php esc_html_e( 'In association with', 'sarjeet' ); ?></dt>
									<dd>
										<ul class="project-detail__partners">
											<?php foreach ( $partners as $partner ) : ?>
												<li><?php echo esc_html( $partner ); ?></li>
											<?php endforeach; ?>
										</ul>
									</dd>
								</div>
							<?php endif; ?>
						</dl>
					</div>

					<?php if ( ! empty( $detail_specs ) ) : ?>
						<div class="project-detail__block">
							<span class="eyebrow"><?php esc_html_e( 'Project Specifications', 'sarjeet' ); ?></span>
							<dl class="project-detail__specs-table">
								<?php foreach ( $detail_specs as $row ) :
									if ( empty( $row['label'] ) ) continue;
								?>
									<div class="project-detail__specs-row">
										<dt><?php echo esc_html( $row['label'] ); ?></dt>
										<dd><?php echo esc_html( $row['value'] ?? '' ); ?></dd>
									</div>
								<?php endforeach; ?>
							</dl>
						</div>
					<?php endif; ?>
				</article>

				<aside class="project-detail__aside reveal">
					<div class="project-detail__spec-card">
						<span class="eyebrow project-detail__spec-head"><?php esc_html_e( 'At a Glance', 'sarjeet' ); ?></span>
						<dl class="project-detail__specs">
							<?php foreach ( $summary as $label => $value ) : if ( empty( $value ) ) continue; ?>
								<div class="project-detail__spec-row">
									<dt><?php echo esc_html( $label ); ?></dt>
									<dd><?php echo esc_html( $value ); ?></dd>
								</div>
							<?php endforeach; ?>
						</dl>
					</div>

					<a class="btn btn--ghost project-detail__back" href="<?php echo esc_url( home_url( '/#projects' ) ); ?>">
						← <?php esc_html_e( 'Back to all projects', 'sarjeet' ); ?>
					</a>
				</aside>

			</div>
		</div>
	</section>

	<?php if ( ! empty( $related ) ) : ?>
		<section class="project-detail__related section">
			<div class="container">
				<div class="reveal section-head">
					<span class="eyebrow"><?php esc_html_e( 'Related Projects', 'sarjeet' ); ?></span>
					<h2><?php esc_html_e( 'More from our books.', 'sarjeet' ); ?></h2>
				</div>
				<div class="project-detail__related-grid">
					<?php foreach ( $related as $r ) : ?>
						<a class="project-detail__related-card" href="<?php echo esc_url( $r->permalink ); ?>">
							<div class="project-detail__related-head">
								<span>P/<?php echo esc_html( $r->n ); ?></span>
								<span><?php echo esc_html( $r->cat ); ?></span>
							</div>
							<div class="project-detail__related-meta">
								<h3><?php echo esc_html( $r->title ); ?></h3>
								<p class="project-detail__related-loc">
									<svg width="10" height="10" viewBox="0 0 10 10" fill="none" stroke="currentColor"><circle cx="5" cy="4" r="2.2" /><path d="M5 6.2 V9" /></svg>
									<?php echo esc_html( $r->loc ); ?>
								</p>
								<span class="project-detail__related-value"><?php echo esc_html( $r->value ?: '—' ); ?></span>
							</div>
							<span class="project-detail__related-arrow" aria-hidden="true">↗</span>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>


</main>

<?php
get_footer();
