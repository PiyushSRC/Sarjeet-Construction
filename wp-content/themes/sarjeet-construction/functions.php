<?php
/**
 * Sarjeet Construction — theme bootstrap.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'SARJEET_THEME_VERSION', '1.0.0' );
define( 'SARJEET_THEME_DIR', get_template_directory() );
define( 'SARJEET_THEME_URI', get_template_directory_uri() );

require SARJEET_THEME_DIR . '/inc/theme-setup.php';
require SARJEET_THEME_DIR . '/inc/enqueue.php';
require SARJEET_THEME_DIR . '/inc/cpt.php';
require SARJEET_THEME_DIR . '/inc/defaults.php';
require SARJEET_THEME_DIR . '/inc/helpers.php';
require SARJEET_THEME_DIR . '/inc/acf-fields.php';
require SARJEET_THEME_DIR . '/inc/contact-handler.php';
require SARJEET_THEME_DIR . '/inc/seo.php';

/**
 * Strip WordPress core overhead this theme doesn't use.
 * Removes emoji detection (~12 KB JS), block-editor CSS (~8 KB), discovery links,
 * and disables XML-RPC.
 */
add_action( 'init', function () {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head', 10 );

	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );

	// Hide REST API discovery from <head> AND from the Link HTTP header
	remove_action( 'wp_head', 'rest_output_link_wp_head' );
	remove_action( 'template_redirect', 'rest_output_link_header', 11 );

	// Drop the shortlink Link: HTTP header too (we already removed the <link> tag earlier)
	remove_action( 'template_redirect', 'wp_shortlink_header', 11 );
} );

add_action( 'wp_enqueue_scripts', function () {
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'global-styles' );
}, 100 );

add_filter( 'xmlrpc_enabled', '__return_false' );

/**
 * Security: HTTP response headers (defends against clickjacking, MIME-sniffing, referrer leaks).
 */
add_action( 'send_headers', function () {
	if ( is_admin() ) return;
	header( 'X-Content-Type-Options: nosniff' );
	header( 'X-Frame-Options: SAMEORIGIN' );
	header( 'Referrer-Policy: strict-origin-when-cross-origin' );
	header( 'Permissions-Policy: camera=(), microphone=(), geolocation=(), interest-cohort=()' );
	// Strip server fingerprinting headers (PHP version, REST + shortlink discovery)
	header_remove( 'X-Powered-By' );
	header_remove( 'Link' );
	if ( is_ssl() ) {
		header( 'Strict-Transport-Security: max-age=31536000; includeSubDomains' );
	}
} );

/**
 * Security: block /?author=N username enumeration on the front-end.
 */
add_action( 'init', function () {
	if ( ! is_admin() && isset( $_GET['author'] ) ) {
		wp_safe_redirect( home_url(), 301 );
		exit;
	}
} );

/**
 * Security: remove the REST API users endpoint so attackers can't list usernames via /wp-json/wp/v2/users.
 */
add_filter( 'rest_endpoints', function ( $endpoints ) {
	if ( isset( $endpoints['/wp/v2/users'] ) )                       unset( $endpoints['/wp/v2/users'] );
	if ( isset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] ) )         unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
	return $endpoints;
} );
