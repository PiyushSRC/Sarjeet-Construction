<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'wp_enqueue_scripts', function () {

	wp_enqueue_style(
		'sarjeet-fonts',
		'https://fonts.googleapis.com/css2?family=Archivo:wght@400;500;600;700;800&family=Inter+Tight:wght@400;500;600;700&family=Fraunces:ital,opsz,wght@1,9..144,400&family=JetBrains+Mono:wght@400;500&display=swap',
		[],
		null
	);

	wp_enqueue_style(
		'sarjeet-styles',
		SARJEET_THEME_URI . '/assets/css/styles.css',
		[],
		SARJEET_THEME_VERSION
	);

	wp_enqueue_script(
		'sarjeet-main',
		SARJEET_THEME_URI . '/assets/js/main.js',
		[],
		SARJEET_THEME_VERSION,
		true
	);

	wp_localize_script( 'sarjeet-main', 'SARJEET_BOOT', [
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		'nonce'   => wp_create_nonce( 'sarjeet_contact' ),
	] );
} );

add_filter( 'style_loader_tag', function ( $tag, $handle ) {
	if ( $handle === 'sarjeet-fonts' ) {
		// crossorigin + async load (media swap pattern) so fonts CSS doesn't block render
		$tag = preg_replace( '/href=/', 'crossorigin href=', $tag, 1 );
		$tag = preg_replace(
			"/media=['\"]all['\"]/",
			"media='print' onload=\"this.media='all'\"",
			$tag,
			1
		);
	}
	return $tag;
}, 10, 2 );

add_filter( 'script_loader_tag', function ( $tag, $handle ) {
	if ( $handle === 'sarjeet-main' ) {
		return str_replace( ' src=', ' defer src=', $tag );
	}
	return $tag;
}, 10, 2 );
