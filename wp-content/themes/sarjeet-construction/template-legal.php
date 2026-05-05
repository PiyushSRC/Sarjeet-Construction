<?php
/**
 * Template Name: Legal (virtual)
 *
 * Loaded via template_include when ?view=privacy|terms|disclaimer|cookies.
 * Renders one of four long-form legal documents from the 'legal' data group
 * in defaults.php. Single source of truth, single template.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$view = get_query_var( 'view' );
$doc  = sarjeet_field( 'legal.' . $view );

// If the view doesn't match a known legal doc, bail to home.
if ( ! is_array( $doc ) || empty( $doc['title'] ) ) {
	wp_safe_redirect( home_url( '/' ), 302 );
	exit;
}

$last_updated = sarjeet_field( 'legal.last_updated' );
$company      = sarjeet_field( 'legal.company' );

get_header();
?>

<main id="main" class="legal-page">

	<section class="legal-page__hero section">
		<div class="container">
			<nav class="legal-page__crumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'sarjeet' ); ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'sarjeet' ); ?></a>
				<span aria-hidden="true">·</span>
				<span class="legal-page__crumb-current"><?php echo esc_html( $doc['title'] ); ?></span>
			</nav>
			<span class="eyebrow"><?php esc_html_e( 'Legal', 'sarjeet' ); ?></span>
			<h1 class="legal-page__title"><?php echo esc_html( $doc['title'] ); ?></h1>
			<?php if ( ! empty( $doc['lede'] ) ) : ?>
				<p class="legal-page__lede"><?php echo esc_html( $doc['lede'] ); ?></p>
			<?php endif; ?>
			<?php if ( $last_updated ) : ?>
				<p class="legal-page__updated">
					<span><?php esc_html_e( 'Last updated', 'sarjeet' ); ?>:</span>
					<strong><?php echo esc_html( $last_updated ); ?></strong>
				</p>
			<?php endif; ?>
		</div>
	</section>

	<section class="legal-page__body section">
		<div class="container">
			<div class="legal-page__layout">

				<aside class="legal-page__toc" aria-label="<?php esc_attr_e( 'On this page', 'sarjeet' ); ?>">
					<div class="legal-page__toc-block">
						<span class="eyebrow"><?php esc_html_e( 'On this page', 'sarjeet' ); ?></span>
						<ol class="legal-page__toc-list">
							<?php foreach ( $doc['sections'] as $i => $sec ) :
								$anchor = 'sec-' . ( $i + 1 );
								?>
								<li><a href="#<?php echo esc_attr( $anchor ); ?>"><?php echo esc_html( $sec['h'] ); ?></a></li>
							<?php endforeach; ?>
						</ol>
					</div>
					<div class="legal-page__toc-card">
						<span class="eyebrow"><?php esc_html_e( 'Questions?', 'sarjeet' ); ?></span>
						<p><?php esc_html_e( 'For privacy concerns or any legal queries, our project desk replies within one business day.', 'sarjeet' ); ?></p>
						<a class="legal-page__toc-link" href="<?php echo esc_url( home_url( '/?view=contact' ) ); ?>">
							<?php esc_html_e( 'Contact us', 'sarjeet' ); ?> <span class="arrow">&rarr;</span>
						</a>
						<dl class="legal-page__toc-meta">
							<div><dt><?php esc_html_e( 'Email', 'sarjeet' ); ?></dt><dd><a href="mailto:sc@sarjeet.com">sc@sarjeet.com</a></dd></div>
							<div><dt><?php esc_html_e( 'Office', 'sarjeet' ); ?></dt><dd>Ahmedabad, Gujarat</dd></div>
						</dl>
					</div>
				</aside>

				<article class="legal-page__article">
					<?php foreach ( $doc['sections'] as $i => $sec ) :
						$anchor = 'sec-' . ( $i + 1 );
						?>
						<section class="legal-page__section" id="<?php echo esc_attr( $anchor ); ?>">
							<h2 class="legal-page__h2"><?php echo esc_html( $sec['h'] ); ?></h2>
							<div class="legal-page__copy"><?php echo wp_kses_post( $sec['body'] ); ?></div>
						</section>
					<?php endforeach; ?>

					<div class="legal-page__foot">
						<p>&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php echo esc_html( $company ); ?>. <?php esc_html_e( 'All rights reserved.', 'sarjeet' ); ?></p>
						<nav class="legal-page__related" aria-label="<?php esc_attr_e( 'Other legal documents', 'sarjeet' ); ?>">
							<?php
							$others = [
								'privacy'    => __( 'Privacy Policy', 'sarjeet' ),
								'terms'      => __( 'Terms & Conditions', 'sarjeet' ),
								'disclaimer' => __( 'Disclaimer', 'sarjeet' ),
								'cookies'    => __( 'Cookie Policy', 'sarjeet' ),
							];
							foreach ( $others as $slug => $label ) {
								if ( $slug === $view ) continue;
								echo '<a href="' . esc_url( home_url( '/?view=' . $slug ) ) . '">' . esc_html( $label ) . '</a>';
							}
							?>
						</nav>
					</div>
				</article>

			</div>
		</div>
	</section>

</main>

<?php
get_footer();
