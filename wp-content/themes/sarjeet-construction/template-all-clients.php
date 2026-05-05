<?php
/**
 * Template Name: All Clients (virtual)
 *
 * Loaded via template_include when the request carries ?view=all-clients.
 * Renders every client from the trust/clients dataset in the original 4×3
 * trust grid so visitors can browse the full government clientele.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$trust   = sarjeet_field( 'trust' );
$clients = sarjeet_field( 'clients' );
if ( empty( $clients ) ) $clients = sarjeet_defaults()['clients'];

get_header();
?>

<main id="main" class="all-clients">

	<section class="all-clients__hero section">
		<div class="container">
			<nav class="all-clients__crumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'sarjeet' ); ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'sarjeet' ); ?></a>
				<span aria-hidden="true">·</span>
				<span class="all-clients__crumb-current"><?php esc_html_e( 'All Clients', 'sarjeet' ); ?></span>
			</nav>
			<span class="eyebrow"><?php echo esc_html( $trust['eyebrow'] ?? '04 — Trust & Licence' ); ?></span>
			<h1 class="all-clients__title"><?php esc_html_e( 'All Clients', 'sarjeet' ); ?></h1>
			<p class="all-clients__lede"><?php echo esc_html( $trust['subheading'] ?? '' ); ?></p>
		</div>
	</section>

	<section class="all-clients__body section trust">
		<div class="trust-grid">
			<?php foreach ( $clients as $i => $it ) :
				$shape = $it['shape'] ?? '';
			?>
				<div class="trust-cell" style="--i:<?php echo (int) $i; ?>" title="<?php echo esc_attr( $it['name'] ); ?>">
					<span class="trust-cat"><?php echo esc_html( $it['cat'] ?? '' ); ?></span>
					<div class="trust-logo">
						<?php if ( ! empty( $it['logo'] ) ) : ?>
							<img class="mark <?php echo esc_attr( $shape ); ?>" src="<?php echo esc_url( $it['logo'] ); ?>" alt="" width="140" height="120" loading="lazy" decoding="async" />
						<?php else : ?>
							<span class="mark <?php echo esc_attr( $shape ); ?>"><?php echo esc_html( $it['mark'] ?? '' ); ?></span>
						<?php endif; ?>
						<span class="name"><?php echo esc_html( $it['name'] ); ?></span>
						<span class="sub"><?php echo esc_html( $it['sub'] ?? '' ); ?></span>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php if ( ! empty( $trust['foot'] ) ) : ?>
			<div class="trust-foot">
				<span><?php echo esc_html( $trust['foot'] ); ?></span>
			</div>
		<?php endif; ?>
	</section>


</main>

<?php
get_footer();
