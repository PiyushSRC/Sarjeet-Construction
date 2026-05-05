<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$a = sarjeet_field( 'about' );
$creds = $a['credentials'] ?? [];
?>
<section id="about" class="section about">
	<div class="container">
		<div class="about-grid">
			<div class="reveal about-text">
				<span class="eyebrow"><?php echo esc_html( $a['eyebrow'] ?? '' ); ?></span>
				<h2><?php echo wp_kses_post( $a['heading_html'] ?? '' ); ?></h2>
				<?php echo wp_kses_post( $a['body_html'] ?? '' ); ?>
				<div class="about-feats">
					<?php foreach ( $creds as $c ) : ?>
						<div class="about-feat">
							<strong><?php echo esc_html( $c['title'] ); ?></strong>
							<span><?php echo esc_html( $c['desc'] ); ?></span>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="reveal about-photo placeholder">
				<span class="placeholder__corner"></span>
				<span class="placeholder__corner placeholder__corner--tr"></span>
				<?php if ( ! empty( $a['photo_url'] ) ) :
					$_a_base = strtok( $a['photo_url'], '?' );
					$_a_make = function ( $w ) use ( $_a_base ) { return $_a_base . '?w=' . $w . '&q=65&fm=webp'; };
					?>
					<img class="about-photo__img"
						src="<?php echo esc_url( $_a_make( 1200 ) ); ?>"
						srcset="<?php echo esc_url( $_a_make( 600 ) ); ?> 600w, <?php echo esc_url( $_a_make( 900 ) ); ?> 900w, <?php echo esc_url( $_a_make( 1200 ) ); ?> 1200w"
						sizes="(max-width: 540px) 100vw, (max-width: 960px) 50vw, 40vw"
						alt="<?php esc_attr_e( 'Sarjeet Construction engineering team on a project site in Gujarat', 'sarjeet' ); ?>"
						width="700" height="900" loading="lazy" decoding="async" />
				<?php endif; ?>
				<div class="placeholder__label">
					<span><?php echo esc_html( $a['photo_label_top'] ?? '' ); ?></span>
					<span><?php echo esc_html( $a['photo_label_bot'] ?? '' ); ?></span>
				</div>
			</div>
		</div>
	</div>
</section>
