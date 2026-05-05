<?php
/**
 * ACF field-group registration + Project meta box (no-ACF fallback).
 *
 * - When ACF Pro is active: registers an Options page "Site Content" with all
 *   admin-editable groups (hero, services repeater, stats, clients repeater,
 *   about, cta, trust, contact, brand) and a per-Project field group with the
 *   prototype's project meta.
 * - When ACF is not active: still ships a simple meta box on the Project CPT
 *   so projects remain editable.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/* -----------------------------------------------------------------
 * 1. ACF integrations
 * -----------------------------------------------------------------*/
add_action( 'acf/init', function () {

	if ( function_exists( 'acf_add_options_page' ) ) {
		acf_add_options_page( [
			'page_title' => 'Site Content',
			'menu_title' => 'Site Content',
			'menu_slug'  => 'sarjeet-site-content',
			'capability' => 'edit_theme_options',
			'icon_url'   => 'dashicons-admin-customizer',
			'position'   => 3,
		] );
	}

	if ( ! function_exists( 'acf_add_local_field_group' ) ) return;

	/* Brand --------------------------------------------------------*/
	acf_add_local_field_group( [
		'key'    => 'group_sarjeet_brand',
		'title'  => 'Brand',
		'fields' => [
			[ 'key' => 'fld_brand', 'name' => 'brand', 'label' => 'Brand', 'type' => 'group', 'layout' => 'block', 'sub_fields' => [
				[ 'key' => 'fld_brand_name',    'name' => 'name',    'label' => 'Company name',    'type' => 'text', 'default_value' => 'Sarjeet Construction' ],
				[ 'key' => 'fld_brand_short',   'name' => 'short',   'label' => 'Short name',      'type' => 'text', 'default_value' => 'Sarjeet' ],
				[ 'key' => 'fld_brand_estd',    'name' => 'estd',    'label' => 'Established (year)', 'type' => 'text', 'default_value' => '1999' ],
				[ 'key' => 'fld_brand_tagline', 'name' => 'tagline', 'label' => 'Tagline',         'type' => 'text' ],
			] ],
		],
		'location' => [ [ [ 'param' => 'options_page', 'operator' => '==', 'value' => 'sarjeet-site-content' ] ] ],
	] );

	/* Hero ---------------------------------------------------------*/
	acf_add_local_field_group( [
		'key'    => 'group_sarjeet_hero',
		'title'  => 'Hero',
		'fields' => [
			[ 'key' => 'fld_hero', 'name' => 'hero', 'label' => 'Hero', 'type' => 'group', 'layout' => 'block', 'sub_fields' => [
				[ 'key' => 'fld_hero_eyebrow',    'name' => 'eyebrow',     'label' => 'Eyebrow',          'type' => 'text' ],
				[ 'key' => 'fld_hero_headline',   'name' => 'headline_html','label' => 'Headline (HTML)', 'type' => 'wysiwyg', 'toolbar' => 'basic', 'media_upload' => 0 ],
				[ 'key' => 'fld_hero_sub',        'name' => 'subheadline', 'label' => 'Subheadline',      'type' => 'textarea', 'rows' => 3 ],
				[ 'key' => 'fld_hero_cta_p',      'name' => 'cta_primary', 'label' => 'Primary CTA',      'type' => 'group', 'layout' => 'block', 'sub_fields' => [
					[ 'key' => 'fld_hero_cta_p_label', 'name' => 'label', 'label' => 'Label', 'type' => 'text' ],
					[ 'key' => 'fld_hero_cta_p_link',  'name' => 'link',  'label' => 'Link',  'type' => 'text' ],
				] ],
				[ 'key' => 'fld_hero_cta_s',      'name' => 'cta_secondary','label' => 'Secondary CTA',    'type' => 'group', 'layout' => 'block', 'sub_fields' => [
					[ 'key' => 'fld_hero_cta_s_label', 'name' => 'label', 'label' => 'Label', 'type' => 'text' ],
					[ 'key' => 'fld_hero_cta_s_link',  'name' => 'link',  'label' => 'Link',  'type' => 'text' ],
				] ],
				[ 'key' => 'fld_hero_photo',      'name' => 'photo_url',   'label' => 'Background photo URL', 'type' => 'text' ],
				[ 'key' => 'fld_hero_tag_top',    'name' => 'photo_tag_top','label' => 'Photo tag (top)',   'type' => 'text' ],
				[ 'key' => 'fld_hero_tag_bot',    'name' => 'photo_tag_bot','label' => 'Photo tag (bottom)','type' => 'text' ],
				[ 'key' => 'fld_hero_compl',      'name' => 'compliance_line','label' => 'Compliance footer', 'type' => 'text' ],
			] ],
		],
		'location' => [ [ [ 'param' => 'options_page', 'operator' => '==', 'value' => 'sarjeet-site-content' ] ] ],
	] );

	/* Services -----------------------------------------------------*/
	acf_add_local_field_group( [
		'key'    => 'group_sarjeet_services',
		'title'  => 'Services',
		'fields' => [
			[ 'key' => 'fld_services', 'name' => 'services', 'label' => 'Services', 'type' => 'repeater', 'button_label' => 'Add service', 'layout' => 'block', 'sub_fields' => [
				[ 'key' => 'fld_svc_n',     'name' => 'n',     'label' => 'Number (e.g. S/01)', 'type' => 'text' ],
				[ 'key' => 'fld_svc_title', 'name' => 'title', 'label' => 'Title',              'type' => 'text' ],
				[ 'key' => 'fld_svc_copy',  'name' => 'copy',  'label' => 'Copy',               'type' => 'textarea', 'rows' => 3 ],
				[ 'key' => 'fld_svc_tag',   'name' => 'tag',   'label' => 'Tag line',           'type' => 'text' ],
				[ 'key' => 'fld_svc_ico',   'name' => 'ico',   'label' => 'Icon',               'type' => 'select', 'choices' => [ 'sewer' => 'Sewer', 'water' => 'Water', 'urban' => 'Urban' ] ],
			] ],
		],
		'location' => [ [ [ 'param' => 'options_page', 'operator' => '==', 'value' => 'sarjeet-site-content' ] ] ],
	] );

	/* Stats --------------------------------------------------------*/
	acf_add_local_field_group( [
		'key'    => 'group_sarjeet_stats',
		'title'  => 'Stats',
		'fields' => [
			[ 'key' => 'fld_stats', 'name' => 'stats', 'label' => 'Stats', 'type' => 'group', 'layout' => 'block', 'sub_fields' => [
				[ 'key' => 'fld_stats_value',          'name' => 'value',          'label' => 'Total value (number)', 'type' => 'text' ],
				[ 'key' => 'fld_stats_value_unit',     'name' => 'value_unit',     'label' => 'Value unit',           'type' => 'text' ],
				[ 'key' => 'fld_stats_value_label',    'name' => 'value_label',    'label' => 'Value label',          'type' => 'text' ],
				[ 'key' => 'fld_stats_value_detail',   'name' => 'value_detail',   'label' => 'Value detail',         'type' => 'textarea', 'rows' => 2 ],
				[ 'key' => 'fld_stats_proj',           'name' => 'projects',       'label' => 'Projects (number)',    'type' => 'text' ],
				[ 'key' => 'fld_stats_proj_label',     'name' => 'projects_label', 'label' => 'Projects label',       'type' => 'text' ],
				[ 'key' => 'fld_stats_proj_detail',    'name' => 'projects_detail','label' => 'Projects detail',      'type' => 'textarea', 'rows' => 2 ],
				[ 'key' => 'fld_stats_years',          'name' => 'years',          'label' => 'Years (number)',       'type' => 'text' ],
				[ 'key' => 'fld_stats_years_label',    'name' => 'years_label',    'label' => 'Years label',          'type' => 'text' ],
				[ 'key' => 'fld_stats_years_detail',   'name' => 'years_detail',   'label' => 'Years detail',         'type' => 'textarea', 'rows' => 2 ],
			] ],
		],
		'location' => [ [ [ 'param' => 'options_page', 'operator' => '==', 'value' => 'sarjeet-site-content' ] ] ],
	] );

	/* Clients / Trust ---------------------------------------------*/
	acf_add_local_field_group( [
		'key'    => 'group_sarjeet_trust',
		'title'  => 'Trust & Clients',
		'fields' => [
			[ 'key' => 'fld_trust', 'name' => 'trust', 'label' => 'Trust intro', 'type' => 'group', 'layout' => 'block', 'sub_fields' => [
				[ 'key' => 'fld_trust_eyebrow',    'name' => 'eyebrow',       'label' => 'Eyebrow',           'type' => 'text' ],
				[ 'key' => 'fld_trust_heading',    'name' => 'heading_html',  'label' => 'Heading (HTML)',    'type' => 'wysiwyg', 'toolbar' => 'basic', 'media_upload' => 0 ],
				[ 'key' => 'fld_trust_sub',        'name' => 'subheading',    'label' => 'Subheading',        'type' => 'textarea', 'rows' => 3 ],
				[ 'key' => 'fld_trust_foot',       'name' => 'foot',          'label' => 'Footer line',       'type' => 'text' ],
			] ],
			[ 'key' => 'fld_clients', 'name' => 'clients', 'label' => 'Clients & certifications', 'type' => 'repeater', 'button_label' => 'Add client', 'layout' => 'block', 'sub_fields' => [
				[ 'key' => 'fld_cl_name',  'name' => 'name',  'label' => 'Name',  'type' => 'text' ],
				[ 'key' => 'fld_cl_sub',   'name' => 'sub',   'label' => 'Sub',   'type' => 'text' ],
				[ 'key' => 'fld_cl_mark',  'name' => 'mark',  'label' => 'Mark',  'type' => 'text' ],
				[ 'key' => 'fld_cl_shape', 'name' => 'shape', 'label' => 'Shape', 'type' => 'select', 'choices' => [ '' => 'Circle (default)', 'sq' => 'Square', 'shield' => 'Shield' ] ],
				[ 'key' => 'fld_cl_cat',   'name' => 'cat',   'label' => 'Category', 'type' => 'text' ],
				[ 'key' => 'fld_cl_logo',  'name' => 'logo',  'label' => 'Logo (optional)', 'type' => 'image', 'return_format' => 'url' ],
			] ],
		],
		'location' => [ [ [ 'param' => 'options_page', 'operator' => '==', 'value' => 'sarjeet-site-content' ] ] ],
	] );

	/* About --------------------------------------------------------*/
	acf_add_local_field_group( [
		'key'    => 'group_sarjeet_about',
		'title'  => 'About',
		'fields' => [
			[ 'key' => 'fld_about', 'name' => 'about', 'label' => 'About', 'type' => 'group', 'layout' => 'block', 'sub_fields' => [
				[ 'key' => 'fld_about_eyebrow',     'name' => 'eyebrow',        'label' => 'Eyebrow',         'type' => 'text' ],
				[ 'key' => 'fld_about_heading',     'name' => 'heading_html',   'label' => 'Heading (HTML)',  'type' => 'wysiwyg', 'toolbar' => 'basic', 'media_upload' => 0 ],
				[ 'key' => 'fld_about_body',        'name' => 'body_html',      'label' => 'Body (HTML)',     'type' => 'wysiwyg' ],
				[ 'key' => 'fld_about_photo',       'name' => 'photo_url',      'label' => 'Photo URL',       'type' => 'text' ],
				[ 'key' => 'fld_about_tag_top',     'name' => 'photo_label_top','label' => 'Photo label (top)',   'type' => 'text' ],
				[ 'key' => 'fld_about_tag_bot',     'name' => 'photo_label_bot','label' => 'Photo label (bottom)','type' => 'text' ],
				[ 'key' => 'fld_about_creds',       'name' => 'credentials',    'label' => 'Credentials',     'type' => 'repeater', 'button_label' => 'Add credential', 'layout' => 'block', 'sub_fields' => [
					[ 'key' => 'fld_cred_title', 'name' => 'title', 'label' => 'Headline', 'type' => 'text' ],
					[ 'key' => 'fld_cred_desc',  'name' => 'desc',  'label' => 'Caption',  'type' => 'textarea', 'rows' => 2 ],
				] ],
			] ],
		],
		'location' => [ [ [ 'param' => 'options_page', 'operator' => '==', 'value' => 'sarjeet-site-content' ] ] ],
	] );

	/* CTA Banner ---------------------------------------------------*/
	acf_add_local_field_group( [
		'key'    => 'group_sarjeet_cta',
		'title'  => 'CTA Banner',
		'fields' => [
			[ 'key' => 'fld_cta', 'name' => 'cta', 'label' => 'CTA banner', 'type' => 'group', 'layout' => 'block', 'sub_fields' => [
				[ 'key' => 'fld_cta_eyebrow',     'name' => 'eyebrow',      'label' => 'Eyebrow',      'type' => 'text' ],
				[ 'key' => 'fld_cta_heading',     'name' => 'heading_html', 'label' => 'Heading (HTML)','type' => 'wysiwyg', 'toolbar' => 'basic', 'media_upload' => 0 ],
				[ 'key' => 'fld_cta_btn_label',   'name' => 'button_label', 'label' => 'Button label', 'type' => 'text' ],
				[ 'key' => 'fld_cta_btn_link',    'name' => 'button_link',  'label' => 'Button link',  'type' => 'text' ],
			] ],
		],
		'location' => [ [ [ 'param' => 'options_page', 'operator' => '==', 'value' => 'sarjeet-site-content' ] ] ],
	] );

	/* Contact ------------------------------------------------------*/
	acf_add_local_field_group( [
		'key'    => 'group_sarjeet_contact',
		'title'  => 'Contact',
		'fields' => [
			[ 'key' => 'fld_contact', 'name' => 'contact', 'label' => 'Contact', 'type' => 'group', 'layout' => 'block', 'sub_fields' => [
				[ 'key' => 'fld_c_phone', 'name' => 'phone', 'label' => 'Phone', 'type' => 'text' ],
				[ 'key' => 'fld_c_email', 'name' => 'email', 'label' => 'Email', 'type' => 'text' ],
				[ 'key' => 'fld_c_a1',    'name' => 'addr1', 'label' => 'Address line 1', 'type' => 'text' ],
				[ 'key' => 'fld_c_a2',    'name' => 'addr2', 'label' => 'Address line 2', 'type' => 'text' ],
				[ 'key' => 'fld_c_hours', 'name' => 'hours', 'label' => 'Hours', 'type' => 'text' ],
				[ 'key' => 'fld_c_cin',   'name' => 'cin',   'label' => 'CIN',   'type' => 'text' ],
				[ 'key' => 'fld_c_cf7',   'name' => 'cf7_shortcode', 'label' => 'Contact Form 7 shortcode (optional)', 'type' => 'text', 'instructions' => 'If left empty, the theme renders a built-in form that posts via AJAX.' ],
			] ],
		],
		'location' => [ [ [ 'param' => 'options_page', 'operator' => '==', 'value' => 'sarjeet-site-content' ] ] ],
	] );

	/* Project per-post fields -------------------------------------*/
	acf_add_local_field_group( [
		'key'    => 'group_sarjeet_project',
		'title'  => 'Project details',
		'fields' => [
			[ 'key' => 'fld_p_loc',      'name' => 'project_location', 'label' => 'Location',      'type' => 'text' ],
			[ 'key' => 'fld_p_value',    'name' => 'project_value',    'label' => 'Project value', 'type' => 'text' ],
			[ 'key' => 'fld_p_year',     'name' => 'project_year',     'label' => 'Year(s)',       'type' => 'text' ],
			[ 'key' => 'fld_p_length',   'name' => 'project_length',   'label' => 'Length',        'type' => 'text' ],
			[ 'key' => 'fld_p_capacity', 'name' => 'project_capacity', 'label' => 'Capacity',      'type' => 'text' ],
			[ 'key' => 'fld_p_client',   'name' => 'project_client',   'label' => 'Client',        'type' => 'text' ],
			[ 'key' => 'fld_p_shape',    'name' => 'project_shape',    'label' => 'Card shape',    'type' => 'select', 'choices' => [
				'default' => 'Default',
				'feat'    => 'Feature (large)',
				'tall'    => 'Tall',
				'wide'    => 'Wide',
			], 'default_value' => 'default' ],
		],
		'location' => [ [ [ 'param' => 'post_type', 'operator' => '==', 'value' => 'project' ] ] ],
	] );
} );

/**
 * 2. ACF "save to post meta" mirror — keeps simple text values reachable via
 * get_post_meta() in helpers.php whether ACF is active or not.
 */
add_action( 'acf/save_post', function ( $post_id ) {
	if ( get_post_type( $post_id ) !== 'project' ) return;
	foreach ( [ 'project_location', 'project_value', 'project_year', 'project_length', 'project_capacity', 'project_client', 'project_shape' ] as $key ) {
		$v = get_field( $key, $post_id );
		if ( $v !== null && $v !== false ) {
			update_post_meta( $post_id, $key, is_array( $v ) ? wp_json_encode( $v ) : $v );
		}
	}
}, 20 );

/* -----------------------------------------------------------------
 * 3. Native meta box — works without ACF.
 * -----------------------------------------------------------------*/
add_action( 'add_meta_boxes', function () {
	if ( function_exists( 'acf_add_local_field_group' ) ) return; // ACF handles it
	add_meta_box( 'sarjeet_project_meta', 'Project details', 'sarjeet_render_project_metabox', 'project', 'normal', 'high' );
} );

function sarjeet_render_project_metabox( $post ) {
	wp_nonce_field( 'sarjeet_project_meta', 'sarjeet_project_meta_nonce' );
	$fields = [
		'project_location' => 'Location (e.g. Lucknow, UP)',
		'project_value'    => 'Project value (e.g. ₹482 Cr)',
		'project_year'     => 'Year (e.g. 2022 — 2025)',
		'project_length'   => 'Length (e.g. 27.4 km)',
		'project_capacity' => 'Capacity (e.g. 320 MLD)',
		'project_client'   => 'Client (e.g. UP Jal Nigam)',
	];
	echo '<table class="form-table"><tbody>';
	foreach ( $fields as $key => $label ) {
		$val = esc_attr( get_post_meta( $post->ID, $key, true ) );
		echo '<tr><th><label for="' . esc_attr( $key ) . '">' . esc_html( $label ) . '</label></th>';
		echo '<td><input class="regular-text" type="text" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . $val . '" /></td></tr>';
	}
	$shape = get_post_meta( $post->ID, 'project_shape', true ) ?: 'default';
	echo '<tr><th><label for="project_shape">Card shape</label></th><td><select id="project_shape" name="project_shape">';
	foreach ( [ 'default' => 'Default', 'feat' => 'Feature (large)', 'tall' => 'Tall', 'wide' => 'Wide' ] as $val => $l ) {
		echo '<option value="' . esc_attr( $val ) . '"' . selected( $shape, $val, false ) . '>' . esc_html( $l ) . '</option>';
	}
	echo '</select></td></tr>';
	echo '</tbody></table>';
}

add_action( 'save_post_project', function ( $post_id ) {
	if ( ! isset( $_POST['sarjeet_project_meta_nonce'] ) || ! wp_verify_nonce( $_POST['sarjeet_project_meta_nonce'], 'sarjeet_project_meta' ) ) return;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;

	foreach ( [ 'project_location', 'project_value', 'project_year', 'project_length', 'project_capacity', 'project_client', 'project_shape' ] as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
		}
	}
} );
