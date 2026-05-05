<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$services = sarjeet_field( 'services' );
if ( empty( $services ) ) $services = sarjeet_defaults()['services'];
?>
<section id="services" class="section services">
	<div class="container">
		<div class="reveal section-head">
			<span class="eyebrow">02 — Capabilities</span>
			<h2>Three disciplines.<br>One in-house team.</h2>
			<p class="lede">Vertically integrated engineering, procurement and construction across the three civic systems that decide whether a city actually works. Survey to handover, under one accountable contract.</p>
		</div>
		<div class="reveal-stagger services-grid">
			<?php foreach ( $services as $i => $s ) : ?>
				<article class="service" style="--i:<?php echo (int) $i; ?>">
					<div class="service-num"><?php echo esc_html( $s['n'] ); ?></div>
					<div class="service-icon"><?php sarjeet_icon( $s['ico'] ); ?></div>
					<h3><?php echo esc_html( $s['title'] ); ?></h3>
					<p><?php echo esc_html( $s['copy'] ); ?></p>
					<div class="service-foot">
						<span><?php echo esc_html( $s['tag'] ); ?></span>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
