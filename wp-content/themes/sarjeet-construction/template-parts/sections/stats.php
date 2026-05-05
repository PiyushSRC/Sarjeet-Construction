<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$s = sarjeet_field( 'stats' );
?>
<section id="stats" class="section stats">
	<div class="container">
		<div class="reveal section-head">
			<div>
				<span class="eyebrow">04 — Track Record</span>
				<h2>Numbers that<br>survive an audit.</h2>
			</div>
			<p class="lede">Independently verified by our auditors and reflected in every state-government empanelment we hold. Updated quarterly; figures as on 31 March 2026.</p>
		</div>
		<div class="stats-grid">
			<div class="stat">
				<span class="bignum">₹<span data-count-to="<?php echo esc_attr( $s['value'] ); ?>">0</span>&nbsp;<span class="bignum__unit"><?php echo esc_html( $s['value_unit'] ); ?></span></span>
				<span class="stat-label"><?php echo esc_html( $s['value_label'] ); ?></span>
				<span class="stat-detail"><?php echo esc_html( $s['value_detail'] ); ?></span>
			</div>
			<div class="stat">
				<span class="bignum"><span data-count-to="<?php echo esc_attr( $s['projects'] ); ?>">0</span>+</span>
				<span class="stat-label"><?php echo esc_html( $s['projects_label'] ); ?></span>
				<span class="stat-detail"><?php echo esc_html( $s['projects_detail'] ); ?></span>
			</div>
			<div class="stat">
				<span class="bignum"><span data-count-to="<?php echo esc_attr( $s['years'] ); ?>">0</span></span>
				<span class="stat-label"><?php echo esc_html( $s['years_label'] ); ?></span>
				<span class="stat-detail"><?php echo esc_html( $s['years_detail'] ); ?></span>
			</div>
		</div>
	</div>
</section>
