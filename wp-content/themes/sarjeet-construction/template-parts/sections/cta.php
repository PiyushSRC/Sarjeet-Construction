<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$cta   = sarjeet_field( 'cta' );
$phone = sarjeet_field( 'contact.phone' );
$email = sarjeet_field( 'contact.email' );
?>
<section id="cta" class="cta-banner">
	<div class="cta-inner">
		<div class="reveal cta-text">
			<span class="eyebrow cta-eyebrow"><?php echo esc_html( $cta['eyebrow'] ?? '' ); ?></span>
			<h2 class="cta-heading"><?php echo wp_kses_post( $cta['heading_html'] ?? '' ); ?></h2>
		</div>
		<div class="reveal cta-side">
			<?php if ( ! empty( $phone ) ) : ?>
				<span class="label-num cta-side__label"><?php esc_html_e( 'Direct line', 'sarjeet' ); ?></span>
				<a class="cta-phone" href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', (string) $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
			<?php elseif ( ! empty( $email ) ) : ?>
				<span class="label-num cta-side__label"><?php esc_html_e( 'Email us', 'sarjeet' ); ?></span>
				<a class="cta-phone" href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
			<?php endif; ?>
			<a href="<?php echo esc_url( $cta['button_link'] ?? home_url( '/?view=contact' ) ); ?>" class="btn"><?php echo esc_html( $cta['button_label'] ?? 'Contact Us' ); ?> <span class="arrow">→</span></a>
		</div>
	</div>
</section>
