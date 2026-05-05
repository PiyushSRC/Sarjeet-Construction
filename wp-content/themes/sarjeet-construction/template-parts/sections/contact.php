<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$c = sarjeet_field( 'contact' );
?>
<section id="contact" class="section contact">
	<div class="container">
		<div class="reveal section-head">
			<div>
				<span class="eyebrow">06 — Contact</span>
				<h2>Reach our project desk.</h2>
			</div>
			<p class="lede">For tender clarifications, feasibility briefs, or new construction inquiries — share your scope and we will reply within one business day.</p>
		</div>

		<div class="contact-panel">
			<div class="contact-panel__card">
				<span class="eyebrow">Direct contact</span>
				<dl class="contact-panel__info">
					<?php if ( ! empty( $c['phone'] ) ) : ?>
						<div>
							<dt>Phone</dt>
							<dd><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', (string) $c['phone'] ) ); ?>"><?php echo esc_html( $c['phone'] ); ?></a></dd>
						</div>
					<?php endif; ?>
					<?php if ( ! empty( $c['email'] ) ) : ?>
						<div>
							<dt>Email</dt>
							<dd><a href="mailto:<?php echo esc_attr( $c['email'] ); ?>"><?php echo esc_html( $c['email'] ); ?></a></dd>
						</div>
					<?php endif; ?>
					<?php if ( ! empty( $c['addr1'] ) || ! empty( $c['addr2'] ) ) : ?>
						<div>
							<dt>Office</dt>
							<dd>
								<?php echo esc_html( $c['addr1'] ?? '' ); ?>
								<?php if ( ! empty( $c['addr2'] ) ) : ?><br><?php echo esc_html( $c['addr2'] ); ?><?php endif; ?>
							</dd>
						</div>
					<?php endif; ?>
					<?php if ( ! empty( $c['hours'] ) ) : ?>
						<div>
							<dt>Hours</dt>
							<dd><?php echo esc_html( $c['hours'] ); ?></dd>
						</div>
					<?php endif; ?>
				</dl>
			</div>

			<div class="contact-panel__steps">
				<span class="eyebrow">What happens next</span>
				<ol>
					<li>
						<span class="contact-panel__step-num">01</span>
						<span class="contact-panel__step-text">We acknowledge your inquiry and assign a project lead within one business day.</span>
					</li>
					<li>
						<span class="contact-panel__step-num">02</span>
						<span class="contact-panel__step-text">A 30-minute scoping call to understand the brief, timelines and tender constraints.</span>
					</li>
					<li>
						<span class="contact-panel__step-num">03</span>
						<span class="contact-panel__step-text">Site visit, methodology proposal, and a written response with budget and schedule.</span>
					</li>
				</ol>
				<a class="btn contact-panel__cta" href="<?php echo esc_url( home_url( '/?view=contact' ) ); ?>">
					Send a project brief <span class="arrow">→</span>
				</a>
			</div>
		</div>
	</div>
</section>
