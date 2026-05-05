<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'init', function () {

	register_post_type( 'project', [
		'label'         => __( 'Projects', 'sarjeet' ),
		'labels'        => [
			'name'          => __( 'Projects', 'sarjeet' ),
			'singular_name' => __( 'Project', 'sarjeet' ),
			'add_new_item'  => __( 'Add New Project', 'sarjeet' ),
			'edit_item'     => __( 'Edit Project', 'sarjeet' ),
		],
		'public'        => true,
		'show_in_rest'  => true,
		'menu_position' => 5,
		'menu_icon'     => 'dashicons-hammer',
		'has_archive'   => 'projects-archive',
		'rewrite'       => [ 'slug' => 'projects' ],
		'supports'      => [ 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ],
	] );

	register_taxonomy( 'project_category', [ 'project' ], [
		'label'             => __( 'Project categories', 'sarjeet' ),
		'public'            => true,
		'show_admin_column' => true,
		'hierarchical'      => true,
		'show_in_rest'      => true,
		'rewrite'           => [ 'slug' => 'project-category' ],
	] );
} );

/**
 * Seed default categories on theme activation.
 */
add_action( 'after_switch_theme', function () {
	foreach ( [ 'Sewerage', 'Water Supply', 'Urban' ] as $term ) {
		if ( ! term_exists( $term, 'project_category' ) ) {
			wp_insert_term( $term, 'project_category' );
		}
	}
	flush_rewrite_rules();
} );
