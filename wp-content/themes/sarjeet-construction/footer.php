<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$D_phone = sarjeet_field( 'contact.phone' );
$D_email = sarjeet_field( 'contact.email' );
$D_a1    = sarjeet_field( 'contact.addr1' );
$D_a2    = sarjeet_field( 'contact.addr2' );
?>

<footer class="footer">
	<div class="footer-top">
		<div class="footer-brand">
			<a class="wordmark" href="<?php echo esc_url( home_url( '/#hero' ) ); ?>">Sarjeet<br><em class="wordmark__suf">Construction.</em></a>
			<p>Civil engineering for civic systems. Sewerage, water and urban infrastructure for India&rsquo;s growing cities.</p>
		</div>
		<div>
			<h3>Capabilities</h3>
			<?php if ( has_nav_menu( 'footer_capabilities' ) ) {
				wp_nav_menu( [ 'theme_location' => 'footer_capabilities', 'container' => false, 'menu_class' => '' ] );
			} else { ?>
			<ul>
				<li><a href="<?php echo esc_url( home_url( '/#services' ) ); ?>">Sewerage &amp; drainage</a></li>
				<li><a href="<?php echo esc_url( home_url( '/#services' ) ); ?>">Water supply</a></li>
				<li><a href="<?php echo esc_url( home_url( '/#services' ) ); ?>">Urban infrastructure</a></li>
				<li><a href="<?php echo esc_url( home_url( '/?view=all-projects' ) ); ?>">All projects</a></li>
			</ul>
			<?php } ?>
		</div>
		<div>
			<h3>Company</h3>
			<?php if ( has_nav_menu( 'footer_company' ) ) {
				wp_nav_menu( [ 'theme_location' => 'footer_company', 'container' => false, 'menu_class' => '' ] );
			} else { ?>
			<ul>
				<li><a href="<?php echo esc_url( home_url( '/#about' ) ); ?>">About us</a></li>
				<li><a href="<?php echo esc_url( home_url( '/#stats' ) ); ?>">Track record</a></li>
				<li><a href="<?php echo esc_url( home_url( '/?view=all-clients' ) ); ?>">Government clientele</a></li>
				<li><a href="<?php echo esc_url( home_url( '/?view=contact' ) ); ?>">Contact</a></li>
			</ul>
			<?php } ?>
		</div>
		<div>
			<h3>Get in touch</h3>
			<ul>
				<?php if ( ! empty( $D_phone ) ) : ?>
					<li><a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $D_phone ) ); ?>"><?php echo esc_html( $D_phone ); ?></a></li>
				<?php endif; ?>
				<?php if ( ! empty( $D_email ) ) : ?>
					<li><a href="mailto:<?php echo esc_attr( $D_email ); ?>"><?php echo esc_html( $D_email ); ?></a></li>
				<?php endif; ?>
				<?php if ( ! empty( $D_a1 ) || ! empty( $D_a2 ) ) : ?>
					<li>
						<?php echo esc_html( $D_a1 ?? '' ); ?>
						<?php if ( ! empty( $D_a1 ) && ! empty( $D_a2 ) ) echo '<br>'; ?>
						<?php echo esc_html( $D_a2 ?? '' ); ?>
					</li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
	<div class="footer-bot">
		<span>© <?php echo esc_html( wp_date( 'Y' ) ); ?> Sarjeet Construction Pvt. Ltd. · All rights reserved.</span>
		<span class="footer-legal"><a href="<?php echo esc_url( home_url( '/?view=privacy' ) ); ?>">Privacy Policy</a> · <a href="<?php echo esc_url( home_url( '/?view=terms' ) ); ?>">Terms &amp; Conditions</a> · <a href="<?php echo esc_url( home_url( '/?view=disclaimer' ) ); ?>">Disclaimer</a> · <a href="<?php echo esc_url( home_url( '/?view=cookies' ) ); ?>">Cookie Policy</a></span>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
