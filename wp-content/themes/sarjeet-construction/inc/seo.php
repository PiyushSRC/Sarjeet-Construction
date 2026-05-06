<?php
/**
 * Sarjeet Construction — SEO meta layer.
 *
 * Hand-coded interim SEO before Rank Math is installed post-deploy.
 * Provides: per-page <title>, meta description, Open Graph, Twitter Cards,
 * Organization + LocalBusiness JSON-LD, BreadcrumbList on subpages,
 * Article schema on project detail pages. Also blocks the users sitemap
 * and extends robots.txt.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Resolve the current "page context" from query vars so the meta layer
 * knows which view is being rendered. Returns a small descriptive array.
 */
function sarjeet_seo_context(): array {
	$slug    = sanitize_title( (string) get_query_var( 'project_view' ) );
	$view    = sanitize_key( (string) get_query_var( 'view' ) );
	$brand   = sarjeet_field( 'brand.name' ) ?: 'Sarjeet Construction';
	$tagline = sarjeet_field( 'brand.tagline' ) ?: '';

	if ( $slug ) {
		$project = sarjeet_project_by_slug( $slug );
		if ( $project ) {
			return [
				'type'        => 'project',
				'title'       => $project->title . ' — ' . $brand,
				'description' => wp_strip_all_tags( $project->desc ?? '' ),
				'url'         => home_url( '/?project_view=' . $slug ),
				'image'       => $project->img ?? '',
				'project'     => $project,
			];
		}
	}

	$legal_titles = [
		'privacy'    => 'Privacy Policy',
		'terms'      => 'Terms & Conditions',
		'disclaimer' => 'Disclaimer',
		'cookies'    => 'Cookie Policy',
	];

	if ( $view === 'all-projects' ) {
		return [
			'type'        => 'archive',
			'title'       => 'All Projects — ' . $brand,
			'description' => 'Eight headline civil engineering projects across Gujarat, Rajasthan, Madhya Pradesh, Maharashtra and Andhra Pradesh — sewerage, water supply and urban infrastructure delivered under government tenders.',
			'url'         => home_url( '/?view=all-projects' ),
		];
	}
	if ( $view === 'all-clients' ) {
		return [
			'type'        => 'archive',
			'title'       => 'Government Clientele — ' . $brand,
			'description' => '12 government clients including GWSSB, RUDSICO, RUIDP, Municipal Corporations and state authorities served by Sarjeet Construction.',
			'url'         => home_url( '/?view=all-clients' ),
		];
	}
	if ( $view === 'contact' ) {
		return [
			'type'        => 'contact',
			'title'       => 'Contact — ' . $brand,
			'description' => 'For tender clarifications, feasibility briefs, or new construction inquiries — share your scope and we will reply within one business day.',
			'url'         => home_url( '/?view=contact' ),
		];
	}
	if ( isset( $legal_titles[ $view ] ) ) {
		$doc = sarjeet_field( 'legal.' . $view );
		return [
			'type'        => 'legal',
			'title'       => $legal_titles[ $view ] . ' — ' . $brand,
			'description' => is_array( $doc ) && ! empty( $doc['lede'] ) ? $doc['lede'] : ( $legal_titles[ $view ] . ' for ' . $brand . '.' ),
			'url'         => home_url( '/?view=' . $view ),
		];
	}

	// Front page (or unknown / fallback)
	return [
		'type'        => 'home',
		'title'       => $brand,
		'description' => sarjeet_field( 'hero.subheadline' ) ?: $tagline,
		'url'         => home_url( '/' ),
		'image'       => sarjeet_field( 'hero.photo_url' ) ?: '',
	];
}

/**
 * Override WordPress's default <title> with our page-aware version.
 */
add_filter( 'pre_get_document_title', function ( $title ) {
	$ctx = sarjeet_seo_context();
	return $ctx['title'] ?? $title;
}, 99 );

/**
 * Print meta description, Open Graph and Twitter Card tags into <head>.
 */
add_action( 'wp_head', function () {
	$ctx     = sarjeet_seo_context();
	$desc    = wp_trim_words( (string) ( $ctx['description'] ?? '' ), 32, '…' );
	$brand   = sarjeet_field( 'brand.name' ) ?: 'Sarjeet Construction';
	$ogimg   = $ctx['image'] ?? sarjeet_field( 'hero.photo_url' );
	$url     = $ctx['url'] ?? home_url( '/' );
	$locale  = get_locale() ?: 'en_IN';

	echo "\n<!-- SEO meta -->\n";
	echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
	echo '<meta name="robots" content="index, follow, max-image-preview:large">' . "\n";

	echo '<meta property="og:type" content="' . ( $ctx['type'] === 'project' ? 'article' : 'website' ) . '">' . "\n";
	echo '<meta property="og:site_name" content="' . esc_attr( $brand ) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $ctx['title'] ) . '">' . "\n";
	echo '<meta property="og:description" content="' . esc_attr( $desc ) . '">' . "\n";
	echo '<meta property="og:url" content="' . esc_url( $url ) . '">' . "\n";
	echo '<meta property="og:locale" content="' . esc_attr( $locale ) . '">' . "\n";
	if ( $ogimg ) {
		echo '<meta property="og:image" content="' . esc_url( $ogimg ) . '">' . "\n";
		echo '<meta property="og:image:width" content="1200">' . "\n";
		echo '<meta property="og:image:height" content="800">' . "\n";
	}

	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
	echo '<meta name="twitter:title" content="' . esc_attr( $ctx['title'] ) . '">' . "\n";
	echo '<meta name="twitter:description" content="' . esc_attr( $desc ) . '">' . "\n";
	if ( $ogimg ) {
		echo '<meta name="twitter:image" content="' . esc_url( $ogimg ) . '">' . "\n";
	}
}, 1 );

/**
 * Print JSON-LD structured data (Organization, LocalBusiness, optional BreadcrumbList + Article).
 */
add_action( 'wp_head', function () {
	$ctx     = sarjeet_seo_context();
	$brand   = sarjeet_field( 'brand.name' ) ?: 'Sarjeet Construction';
	$email   = sarjeet_field( 'contact.email' );
	$phone   = sarjeet_field( 'contact.phone' );
	$addr1   = sarjeet_field( 'contact.addr1' );
	$addr2   = sarjeet_field( 'contact.addr2' );
	$logo    = get_template_directory_uri() . '/assets/images/logo.png';
	$home    = home_url( '/' );

	$organization = [
		'@context' => 'https://schema.org',
		'@type'    => 'Organization',
		'@id'      => $home . '#organization',
		'name'     => $brand,
		'url'      => $home,
		'logo'     => $logo,
		'description' => 'Civil engineering for civic systems — sewerage, water supply and urban infrastructure for India\'s growing cities.',
	];
	if ( $email ) $organization['email'] = $email;
	if ( $phone ) $organization['telephone'] = $phone;
	if ( $addr1 || $addr2 ) {
		$organization['address'] = [
			'@type'           => 'PostalAddress',
			'streetAddress'   => trim( ( $addr1 ?? '' ) . ' ' . ( $addr2 ?? '' ) ),
			'addressLocality' => 'Ahmedabad',
			'addressRegion'   => 'Gujarat',
			'postalCode'      => '382350',
			'addressCountry'  => 'IN',
		];
	}

	$local_business = array_merge( $organization, [
		'@type' => 'LocalBusiness',
		'@id'   => $home . '#localbusiness',
		'priceRange' => 'INR ₹',
		'areaServed' => [ 'Gujarat', 'Rajasthan', 'Madhya Pradesh', 'Maharashtra', 'Andhra Pradesh' ],
		'openingHours' => [ 'Mo-Fr 09:00-17:00', 'Sa 10:00-14:00' ],
	] );

	$website = [
		'@context' => 'https://schema.org',
		'@type'    => 'WebSite',
		'@id'      => $home . '#website',
		'name'     => $brand,
		'url'      => $home,
		'publisher' => [ '@id' => $home . '#organization' ],
		'inLanguage' => 'en-IN',
	];

	$nodes = [ $organization, $local_business, $website ];

	// Project page → add Article schema and BreadcrumbList
	if ( $ctx['type'] === 'project' && ! empty( $ctx['project'] ) ) {
		$p = $ctx['project'];
		$nodes[] = [
			'@context' => 'https://schema.org',
			'@type'    => 'Article',
			'headline' => $p->title,
			'description' => wp_strip_all_tags( $p->desc ?? '' ),
			'image'    => $p->img ?? '',
			'mainEntityOfPage' => $ctx['url'],
			'publisher' => [ '@id' => $home . '#organization' ],
			'about' => [
				'@type' => 'Project',
				'name'  => $p->title,
				'location' => $p->loc ?? '',
				'funder' => $p->client ?? '',
			],
		];
		$nodes[] = [
			'@context' => 'https://schema.org',
			'@type' => 'BreadcrumbList',
			'itemListElement' => [
				[ '@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => $home ],
				[ '@type' => 'ListItem', 'position' => 2, 'name' => 'Projects', 'item' => home_url( '/?view=all-projects' ) ],
				[ '@type' => 'ListItem', 'position' => 3, 'name' => $p->title, 'item' => $ctx['url'] ],
			],
		];
	}

	// Legal/contact/archive pages → BreadcrumbList
	if ( in_array( $ctx['type'], [ 'legal', 'archive', 'contact' ], true ) ) {
		$nodes[] = [
			'@context' => 'https://schema.org',
			'@type' => 'BreadcrumbList',
			'itemListElement' => [
				[ '@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => $home ],
				[ '@type' => 'ListItem', 'position' => 2, 'name' => str_replace( ' — ' . $brand, '', $ctx['title'] ), 'item' => $ctx['url'] ],
			],
		];
	}

	echo "\n<script type=\"application/ld+json\">\n";
	echo wp_json_encode( $nodes, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
	echo "\n</script>\n";
}, 1 );

/**
 * Block /wp-sitemap-users-1.xml — leaks usernames.
 */
add_filter( 'wp_sitemaps_add_provider', function ( $provider, $name ) {
	return ( $name === 'users' ) ? false : $provider;
}, 10, 2 );

/**
 * Extend robots.txt with explicit disallows + sitemap link.
 */
add_filter( 'robots_txt', function ( $output, $public ) {
	if ( ! $public ) return $output;
	$extra  = "Disallow: /wp-includes/\n";
	$extra .= "Disallow: /wp-content/plugins/\n";
	$extra .= "Disallow: /xmlrpc.php\n";
	$extra .= "Disallow: /readme.html\n";
	$extra .= "Disallow: /?author=\n";
	// Append to existing, before "Sitemap:" line
	if ( strpos( $output, 'Sitemap:' ) !== false ) {
		$output = preg_replace( '/(\nSitemap:)/', $extra . '$1', $output, 1 );
	} else {
		$output .= $extra;
	}
	return $output;
}, 10, 2 );
