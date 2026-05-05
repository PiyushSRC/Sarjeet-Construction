<?php
/**
 * Template Name: Contact (virtual)
 *
 * Loaded via template_include when the request carries ?view=contact.
 * Renders the dedicated contact page with a richer construction inquiry
 * form than the homepage contact section.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$c = sarjeet_field( 'contact' );

get_header();
?>

<main id="main" class="contact-page">

	<section class="contact-page__hero section">
		<div class="container">
			<nav class="contact-page__crumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'sarjeet' ); ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'sarjeet' ); ?></a>
				<span aria-hidden="true">·</span>
				<span class="contact-page__crumb-current"><?php esc_html_e( 'Contact', 'sarjeet' ); ?></span>
			</nav>
			<span class="eyebrow"><?php esc_html_e( 'Contact', 'sarjeet' ); ?></span>
			<h1 class="contact-page__title"><?php esc_html_e( 'Start a project with us.', 'sarjeet' ); ?></h1>
			<p class="contact-page__lede"><?php esc_html_e( 'For tender clarifications, feasibility briefs, or new construction inquiries — share your scope and we will reply within one business day.', 'sarjeet' ); ?></p>
		</div>
	</section>

	<section class="contact-page__body section">
		<div class="container">
			<div class="contact-page__layout">

				<form class="contact-page__form contact-form contact-page__form--stepped" id="sarjeet-contact-form" novalidate data-stepper>
					<div class="hp-field" aria-hidden="true">
						<label>Website (leave blank)<input type="text" name="website" tabindex="-1" autocomplete="off" /></label>
					</div>
					<ol class="contact-page__steps-nav" aria-label="<?php esc_attr_e( 'Form progress', 'sarjeet' ); ?>">
						<li class="contact-page__step-pill is-active" data-step-pill="1"><span>01</span> <?php esc_html_e( 'You', 'sarjeet' ); ?></li>
						<li class="contact-page__step-pill" data-step-pill="2"><span>02</span> <?php esc_html_e( 'Project', 'sarjeet' ); ?></li>
						<li class="contact-page__step-pill" data-step-pill="3"><span>03</span> <?php esc_html_e( 'Brief', 'sarjeet' ); ?></li>
					</ol>

					<fieldset class="contact-page__fieldset contact-page__step is-active" data-step="1">
						<legend class="eyebrow"><?php esc_html_e( 'Your details', 'sarjeet' ); ?></legend>
						<div class="contact-page__form-grid">
							<label class="field">
								<span><?php esc_html_e( 'Full name *', 'sarjeet' ); ?></span>
								<input type="text" name="name" required />
							</label>
							<label class="field">
								<span><?php esc_html_e( 'Organisation / Company', 'sarjeet' ); ?></span>
								<input type="text" name="org" />
							</label>
							<label class="field">
								<span><?php esc_html_e( 'Email *', 'sarjeet' ); ?></span>
								<input type="email" name="email" required />
							</label>
							<label class="field">
								<span><?php esc_html_e( 'Phone *', 'sarjeet' ); ?></span>
								<input type="tel" name="phone" required />
							</label>
						</div>
					</fieldset>

					<fieldset class="contact-page__fieldset contact-page__step" data-step="2">
						<legend class="eyebrow"><?php esc_html_e( 'Project', 'sarjeet' ); ?></legend>
						<div class="contact-page__form-grid">
							<label class="field">
								<span><?php esc_html_e( 'Project type *', 'sarjeet' ); ?></span>
								<select name="project_type" required>
									<option value=""><?php esc_html_e( 'Select project type', 'sarjeet' ); ?></option>
									<option value="Sewerage & Drainage"><?php esc_html_e( 'Sewerage & Drainage', 'sarjeet' ); ?></option>
									<option value="Water Supply"><?php esc_html_e( 'Water Supply', 'sarjeet' ); ?></option>
									<option value="Urban Infrastructure"><?php esc_html_e( 'Urban Infrastructure', 'sarjeet' ); ?></option>
									<option value="Other"><?php esc_html_e( 'Other', 'sarjeet' ); ?></option>
								</select>
							</label>
							<label class="field">
								<span><?php esc_html_e( 'Project location (city, state)', 'sarjeet' ); ?></span>
								<input type="text" name="location" placeholder="<?php esc_attr_e( 'e.g. Ahmedabad, Gujarat', 'sarjeet' ); ?>" />
							</label>
							<label class="field">
								<span><?php esc_html_e( 'Estimated project value', 'sarjeet' ); ?></span>
								<select name="value_range">
									<option value=""><?php esc_html_e( 'Select range (optional)', 'sarjeet' ); ?></option>
									<option value="<10M">&lt; ₹10 M</option>
									<option value="10-100M">₹10 — 100 M</option>
									<option value="100-500M">₹100 — 500 M</option>
									<option value="500M-1B">₹500 M — 1 B</option>
									<option value=">1B">&gt; ₹1 B</option>
									<option value="not-sure"><?php esc_html_e( 'Not yet sure', 'sarjeet' ); ?></option>
								</select>
							</label>
							<label class="field">
								<span><?php esc_html_e( 'Tender / RFP reference', 'sarjeet' ); ?></span>
								<input type="text" name="tender_ref" placeholder="<?php esc_attr_e( 'Optional', 'sarjeet' ); ?>" />
							</label>
							<label class="field contact-page__field--full">
								<span><?php esc_html_e( 'Expected start', 'sarjeet' ); ?></span>
								<input type="text" name="timeline" placeholder="<?php esc_attr_e( 'e.g. Q3 2026, immediate, etc.', 'sarjeet' ); ?>" />
							</label>
						</div>
					</fieldset>

					<fieldset class="contact-page__fieldset contact-page__step" data-step="3">
						<legend class="eyebrow"><?php esc_html_e( 'Brief', 'sarjeet' ); ?></legend>
						<label class="field">
							<span><?php esc_html_e( 'Scope of work / message *', 'sarjeet' ); ?></span>
							<textarea name="message" rows="6" required placeholder="<?php esc_attr_e( 'Outline the scope, deliverables, key constraints, and any drawings or attachments we should see.', 'sarjeet' ); ?>"></textarea>
						</label>
					</fieldset>

					<div class="contact-page__step-actions">
						<button type="button" class="btn btn--ghost" data-step-prev hidden><?php esc_html_e( '← Back', 'sarjeet' ); ?></button>
						<button type="button" class="btn" data-step-next><?php esc_html_e( 'Next', 'sarjeet' ); ?> <span class="arrow">→</span></button>
						<button type="submit" class="btn" data-step-submit hidden><?php esc_html_e( 'Send inquiry', 'sarjeet' ); ?> <span class="arrow">→</span></button>
						<span class="form-status" data-status><?php esc_html_e( 'WE REPLY WITHIN ONE BUSINESS DAY', 'sarjeet' ); ?></span>
					</div>
				</form>

				<aside class="contact-page__aside">
					<div class="contact-page__info">
						<span class="eyebrow"><?php esc_html_e( 'Direct contact', 'sarjeet' ); ?></span>
						<dl class="contact-page__info-list">
							<?php if ( ! empty( $c['phone'] ) ) : ?>
								<div>
									<dt><?php esc_html_e( 'Phone', 'sarjeet' ); ?></dt>
									<dd><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', (string) $c['phone'] ) ); ?>"><?php echo esc_html( $c['phone'] ); ?></a></dd>
								</div>
							<?php endif; ?>
							<?php if ( ! empty( $c['email'] ) ) : ?>
								<div>
									<dt><?php esc_html_e( 'Email', 'sarjeet' ); ?></dt>
									<dd><a href="mailto:<?php echo esc_attr( $c['email'] ); ?>"><?php echo esc_html( $c['email'] ); ?></a></dd>
								</div>
							<?php endif; ?>
							<?php if ( ! empty( $c['addr1'] ) || ! empty( $c['addr2'] ) ) : ?>
								<div>
									<dt><?php esc_html_e( 'Office', 'sarjeet' ); ?></dt>
									<dd>
										<?php echo esc_html( $c['addr1'] ?? '' ); ?>
										<?php if ( ! empty( $c['addr2'] ) ) : ?><br><?php echo esc_html( $c['addr2'] ); ?><?php endif; ?>
									</dd>
								</div>
							<?php endif; ?>
							<?php if ( ! empty( $c['hours'] ) ) : ?>
								<div>
									<dt><?php esc_html_e( 'Hours', 'sarjeet' ); ?></dt>
									<dd><?php echo esc_html( $c['hours'] ); ?></dd>
								</div>
							<?php endif; ?>
						</dl>
					</div>

					<div class="contact-page__steps">
						<span class="eyebrow"><?php esc_html_e( 'What happens next', 'sarjeet' ); ?></span>
						<ol>
							<li>
								<span class="contact-page__step-num">01</span>
								<span class="contact-page__step-text"><?php esc_html_e( 'We acknowledge your inquiry and assign a project lead within one business day.', 'sarjeet' ); ?></span>
							</li>
							<li>
								<span class="contact-page__step-num">02</span>
								<span class="contact-page__step-text"><?php esc_html_e( 'A 30-minute scoping call to understand the brief, timelines and tender constraints.', 'sarjeet' ); ?></span>
							</li>
							<li>
								<span class="contact-page__step-num">03</span>
								<span class="contact-page__step-text"><?php esc_html_e( 'Site visit, methodology proposal, and a written response with budget and schedule.', 'sarjeet' ); ?></span>
							</li>
						</ol>
					</div>
				</aside>

			</div>
		</div>
	</section>


</main>

<?php
get_footer();
