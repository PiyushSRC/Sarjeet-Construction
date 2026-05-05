<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$eyebrow   = sarjeet_field( 'hero.eyebrow' );
$headline  = sarjeet_field( 'hero.headline_html' );
$sub       = sarjeet_field( 'hero.subheadline' );
$cta_p     = sarjeet_field( 'hero.cta_primary' );
$cta_s     = sarjeet_field( 'hero.cta_secondary' );
$photo     = sarjeet_field( 'hero.photo_url' );
$tag_top   = sarjeet_field( 'hero.photo_tag_top' );
$tag_bot   = sarjeet_field( 'hero.photo_tag_bot' );
$compl     = sarjeet_field( 'hero.compliance_line' );
?>
<section id="hero" class="hero" data-variant="full">
	<div class="hero-main">
		<div class="hero-text">
			<div class="hero-eyebrow-row">
				<span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
			</div>
			<h1 class="hero-h"><?php echo wp_kses_post( $headline ); ?></h1>
			<p class="hero-sub"><?php echo esc_html( $sub ); ?></p>
			<div class="hero-ctas">
				<a href="<?php echo esc_url( $cta_p['link'] ?? '#projects' ); ?>" class="btn"><?php echo esc_html( $cta_p['label'] ?? 'View Projects' ); ?> <span class="arrow">→</span></a>
				<a href="<?php echo esc_url( $cta_s['link'] ?? '#contact' ); ?>" class="btn btn--ghost"><?php echo esc_html( $cta_s['label'] ?? 'Contact Us' ); ?> <span class="arrow">→</span></a>
			</div>
		</div>
		<div class="hero-photo">
			<?php
				// Build a responsive srcset by stripping any existing query and re-attaching width-specific params
				$_photo_base = strtok( $photo, '?' );
				$_make = function ( $w ) use ( $_photo_base ) { return $_photo_base . '?w=' . $w . '&q=55&fm=webp'; };
				?>
				<img
					src="<?php echo esc_url( $_make( 1200 ) ); ?>"
					srcset="<?php echo esc_url( $_make( 600 ) ); ?> 600w, <?php echo esc_url( $_make( 900 ) ); ?> 900w, <?php echo esc_url( $_make( 1200 ) ); ?> 1200w"
					sizes="(max-width: 768px) 55vw, (max-width: 960px) 55vw, 50vw"
					alt="<?php esc_attr_e( 'Sarjeet engineers on a sewerage and infrastructure construction site', 'sarjeet' ); ?>"
					width="800" height="1000" decoding="async" loading="eager" fetchpriority="high" />
			<div class="photo-tag">
				<span><?php echo esc_html( $tag_top ); ?></span>
				<span><?php echo esc_html( $tag_bot ); ?></span>
			</div>
		</div>
	</div>

	<div class="hero-foot">
		<div class="hero-foot__row">
			<div class="scroll-cue"><span>Scroll</span><span class="line"></span></div>
			<div class="label-num"><?php echo esc_html( date_i18n( 'd / m / Y' ) ); ?></div>
		</div>
		<?php if ( ! empty( $compl ) ) : ?>
			<div class="hero-foot__compliance"><?php echo esc_html( $compl ); ?></div>
		<?php endif; ?>
	</div>
</section>
