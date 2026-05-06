<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ] );
	add_theme_support( 'custom-logo', [
		'height'      => 64,
		'width'       => 240,
		'flex-height' => true,
		'flex-width'  => true,
	] );
	add_theme_support( 'responsive-embeds' );

	register_nav_menus( [
		'primary' => __( 'Primary navigation (header)', 'sarjeet' ),
		'footer_capabilities' => __( 'Footer — Capabilities', 'sarjeet' ),
		'footer_company'      => __( 'Footer — Company', 'sarjeet' ),
	] );
} );

add_action( 'init', function () {
	add_image_size( 'sarjeet_card', 1200, 900, true );
	add_image_size( 'sarjeet_hero', 1920, 1280, true );
} );

add_action( 'wp_head', function () {
	if ( has_site_icon() ) return;
	$base = get_template_directory_uri() . '/assets/images';
	echo '<link rel="icon" type="image/png" sizes="32x32" href="' . esc_url( $base . '/favicon-32.png' ) . '" />' . "\n";
	echo '<link rel="icon" type="image/png" sizes="192x192" href="' . esc_url( $base . '/favicon.png' ) . '" />' . "\n";
	echo '<link rel="apple-touch-icon" sizes="180x180" href="' . esc_url( $base . '/apple-touch-icon.png' ) . '" />' . "\n";
}, 1 );
