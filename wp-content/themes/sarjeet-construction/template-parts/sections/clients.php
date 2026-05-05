<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$trust   = sarjeet_field( 'trust' );
$clients = sarjeet_field( 'clients' );
if ( empty( $clients ) ) $clients = sarjeet_defaults()['clients'];

// We render the logo set TWICE so the keyframe-driven track loops seamlessly
// (translates by -50%, ending where the first set ends).
$render_item = static function ( array $it ) {
	$shape = $it['shape'] ?? '';
	?>
	<a class="client-marquee__item" role="listitem" href="<?php echo esc_url( home_url( '/?view=all-clients' ) ); ?>" aria-label="<?php echo esc_attr( $it['name'] ); ?>">
		<span class="client-marquee__logo">
			<?php if ( ! empty( $it['logo'] ) ) : ?>
				<img src="<?php echo esc_url( $it['logo'] ); ?>" alt="" width="140" height="120" loading="lazy" decoding="async" />
			<?php else : ?>
				<span class="<?php echo esc_attr( $shape ); ?>" aria-hidden="true"><?php echo esc_html( $it['mark'] ?? '' ); ?></span>
			<?php endif; ?>
		</span>
		<span class="client-marquee__name"><?php echo esc_html( $it['name'] ); ?></span>
	</a>
	<?php
};
?>
<section id="clients" class="trust">
	<div class="reveal trust-head">
		<span class="eyebrow"><?php echo esc_html( $trust['eyebrow'] ?? '05 — Trust & Licence' ); ?></span>
		<h2><?php echo wp_kses_post( $trust['heading_html'] ?? '' ); ?></h2>
		<p><?php echo esc_html( $trust['subheading'] ?? '' ); ?></p>
	</div>

	<div class="container">
		<div class="client-marquee" aria-label="<?php esc_attr_e( 'Government clientele', 'sarjeet' ); ?>">
			<div class="client-marquee__track" role="list">
				<?php foreach ( $clients as $it ) $render_item( $it ); ?>
				<?php // Duplicate set for seamless loop (aria-hidden so screen readers don't repeat). ?>
				<div aria-hidden="true" style="display:contents">
					<?php foreach ( $clients as $it ) $render_item( $it ); ?>
				</div>
			</div>
		</div>

		<div class="client-view-all">
			<a class="btn client-view-all__btn" href="<?php echo esc_url( home_url( '/?view=all-clients' ) ); ?>">
				<?php esc_html_e( 'View all clients', 'sarjeet' ); ?> <span class="arrow">→</span>
			</a>
		</div>
	</div>

	<?php if ( ! empty( $trust['foot'] ) ) : ?>
		<div class="trust-foot">
			<span><?php echo esc_html( $trust['foot'] ); ?></span>
		</div>
	<?php endif; ?>
</section>
