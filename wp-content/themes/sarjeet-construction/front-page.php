<?php
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
?>

<main id="main">
	<?php
	// Order matches the navbar: Home → About Us → Our Project → Our Client → Contact.
	// (Services, Stats and CTA have no nav anchor; they are tucked between nav sections.)
	get_template_part( 'template-parts/sections/hero' );      // #hero     → Home
	get_template_part( 'template-parts/sections/about' );     // #about    → About Us
	get_template_part( 'template-parts/sections/services' );  //           (supports About)
	get_template_part( 'template-parts/sections/projects' );  // #projects → Our Project
	get_template_part( 'template-parts/sections/stats' );     //           (supports Projects)
	get_template_part( 'template-parts/sections/clients' );   // #clients  → Our Client
	get_template_part( 'template-parts/sections/cta' );       //           (drives to Contact)
	get_template_part( 'template-parts/sections/contact' );   // #contact  → Contact
	?>
</main>

<?php get_footer(); ?>
