<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?><!doctype html>
<html <?php language_attributes(); ?> data-palette="white-blue" data-density="comfortable">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="profile" href="https://gmpg.org/xfn/11" />
	<link rel="preconnect" href="https://images.unsplash.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="dns-prefetch" href="https://fonts.googleapis.com">
	<link rel="prefetch" href="<?php echo esc_url( home_url( '/?view=contact' ) ); ?>">
	<?php
	// Preload the hero image (LCP element) on the front page only.
	// Supports both Unsplash-style (?w= query params) and local theme files (filename suffix).
	if ( is_front_page() && ( $_hero = sarjeet_field( 'hero.photo_url' ) ) ) :
		$_hero_base  = strtok( $_hero, '?' );
		$_hero_local = ( strpos( $_hero, '?' ) === false );
		if ( $_hero_local ) {
			$_hero_ext  = pathinfo( $_hero_base, PATHINFO_EXTENSION );
			$_hero_stem = preg_replace( '/-\d+$/', '', preg_replace( '/\.[^.]+$/', '', $_hero_base ) );
			$_hero_make = function ( $w ) use ( $_hero_stem, $_hero_ext ) { return $_hero_stem . '-' . $w . '.' . $_hero_ext; };
		} else {
			$_hero_make = function ( $w ) use ( $_hero_base ) { return $_hero_base . '?w=' . $w . '&q=55&fm=webp'; };
		}
		?>
		<link rel="preload" as="image"
			href="<?php echo esc_url( $_hero_make( 1200 ) ); ?>"
			imagesrcset="<?php echo esc_attr( $_hero_make( 600 ) . ' 600w, ' . $_hero_make( 900 ) . ' 900w, ' . $_hero_make( 1200 ) . ' 1200w, ' . $_hero_make( 1920 ) . ' 1920w' ); ?>"
			imagesizes="100vw"
			fetchpriority="high">
	<?php endif; ?>
	<?php wp_head(); ?>
	<noscript>
		<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Archivo:wght@400;500;600;700;800&family=Inter+Tight:wght@400;500;600;700&family=Fraunces:ital,opsz,wght@1,9..144,400&family=JetBrains+Mono:wght@400;500&display=swap">
	</noscript>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main"><?php esc_html_e( 'Skip to content', 'sarjeet' ); ?></a>

<header class="header scrolled" id="site-header" data-scrolled="true">
	<div class="header-row">
		<a href="<?php echo esc_url( home_url( '/#hero' ) ); ?>" class="logo" aria-label="<?php echo esc_attr( sarjeet_field( 'brand.name' ) ); ?>">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo.png' ); ?>" alt="<?php echo esc_attr( sarjeet_field( 'brand.name' ) ); ?>" class="logo-img" width="240" height="68" decoding="async" />
		</a>

		<nav class="nav" aria-label="Primary">
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu( [
					'theme_location' => 'primary',
					'container'      => false,
					'items_wrap'     => '%3$s',
					'walker'         => null,
					'fallback_cb'    => false,
				] );
			} else {
				$links = [
					'hero'     => 'Home',
					'about'    => 'About Us',
					'projects' => 'Our Project',
					'clients'  => 'Our Client',
					'contact'  => 'Contact',
				];
				foreach ( $links as $id => $label ) {
					$href = $id === 'contact'
						? esc_url( home_url( '/?view=contact' ) )
						: esc_url( home_url( '/#' . $id ) );
					echo '<a href="' . $href . '" data-spy="' . esc_attr( $id ) . '"><span class="nav-label">' . esc_html( $label ) . '</span></a>';
				}
			}
			?>
		</nav>

		<div class="header-cta">
			<a href="<?php echo esc_url( home_url( '/?view=contact' ) ); ?>" class="btn btn--quote">Start a Project</a>
			<button class="hamburger" aria-label="Open menu" aria-expanded="false" type="button">
				<span></span><span></span><span></span>
			</button>
		</div>
	</div>
</header>

<div class="mobile-menu" aria-hidden="true" inert>
		<nav class="mobile-nav">
			<?php
			$links = [
				'hero'     => 'Home',
				'about'    => 'About Us',
				'projects' => 'Our Project',
				'clients'  => 'Our Client',
				'contact'  => 'Contact',
			];
			$i = 0;
			foreach ( $links as $id => $label ) {
				$num  = str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT );
				$href = $id === 'contact'
					? esc_url( home_url( '/?view=contact' ) )
					: esc_url( home_url( '/#' . $id ) );
				echo '<a href="' . $href . '" data-spy="' . esc_attr( $id ) . '" style="--i:' . (int) $i . '">'
					. '<span class="mobile-num">' . esc_html( $num ) . '</span>'
					. '<span class="mobile-label">' . esc_html( $label ) . '</span>'
					. '<span class="mobile-arr">→</span></a>';
				$i++;
			}
			?>
		</nav>
		<div class="mobile-foot">
			<a href="<?php echo esc_url( home_url( '/?view=contact' ) ); ?>" class="btn btn--quote">Start a Project <span class="arrow">→</span></a>
		</div>
</div>
