<?php
/**
 * Built-in AJAX contact-form handler — used when no Contact Form 7 shortcode
 * has been entered in Site Content → Contact.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'wp_ajax_sarjeet_contact',        'sarjeet_handle_contact' );
add_action( 'wp_ajax_nopriv_sarjeet_contact', 'sarjeet_handle_contact' );

function sarjeet_handle_contact() {
	check_ajax_referer( 'sarjeet_contact', 'nonce' );

	// Honeypot: real users never fill this hidden field; bots fill every input they see.
	if ( ! empty( $_POST['website'] ) ) {
		// Pretend success so the bot doesn't retry with a different strategy.
		wp_send_json_success( [ 'msg' => 'Brief received.' ] );
	}

	// Per-IP rate limit: max 3 submissions per 10 minutes.
	$ip       = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'unknown';
	$rate_key = 'sarjeet_contact_rl_' . md5( $ip );
	$hits     = (int) get_transient( $rate_key );
	if ( $hits >= 3 ) {
		wp_send_json_error( [ 'msg' => 'Too many submissions. Please try again in a few minutes.' ], 429 );
	}
	set_transient( $rate_key, $hits + 1, 10 * MINUTE_IN_SECONDS );

	$name    = isset( $_POST['name'] )    ? sanitize_text_field( wp_unslash( $_POST['name'] ) )    : '';
	$email   = isset( $_POST['email'] )   ? sanitize_email( wp_unslash( $_POST['email'] ) )        : '';
	$org     = isset( $_POST['org'] )     ? sanitize_text_field( wp_unslash( $_POST['org'] ) )     : '';
	$message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

	if ( ! $name || ! $email || ! $message ) {
		wp_send_json_error( [ 'msg' => 'Please fill name, email and message.' ], 400 );
	}
	if ( ! is_email( $email ) ) {
		wp_send_json_error( [ 'msg' => 'Enter a valid email address.' ], 400 );
	}

	$to      = sarjeet_field( 'contact.email' );
	$subject = sprintf( '[Sarjeet] New project brief from %s', $name );
	$body    = "Name: {$name}\nEmail: {$email}\nOrganisation: {$org}\n\nMessage:\n{$message}";
	$headers = [ 'Reply-To: ' . $email ];

	$ok = wp_mail( $to, $subject, $body, $headers );

	if ( $ok ) {
		wp_send_json_success( [ 'msg' => 'Brief received. Our projects desk will reply within one business day.' ] );
	}
	wp_send_json_error( [ 'msg' => 'Mail server unavailable. Please try again or call us directly.' ], 500 );
}
