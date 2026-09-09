<?php
/**
 * Contact Form 7 integration: route the real contact form's mail to the
 * "Réglages du thème" options page instead of a recipient hard-coded in the
 * CF7 admin (SPEC.md §4 — "adresse configurable dans les réglages, pas
 * codée en dur").
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Forces the mail recipient of the Contact page's CF7 form to the
 * dz_contact_email option, read via dz_get_option() (see inc/theme-setup.php)
 * rather than duplicating the ACF/SCF lookup here.
 *
 * Only touches the form actually embedded on the page using the
 * page-contact.php template (matched via CF7's own "container_post_id"
 * submission meta), so a future CF7 form built for a different purpose
 * (mouvement, cartographie...) on another page is never affected. Today
 * only one CF7 form exists on the site, so this check is not yet exercised
 * against a second form — it is left in place ahead of that need rather
 * than relying on a global hook that would have to be fixed later.
 *
 * @param WPCF7_ContactForm $contact_form
 */
function dz_cf7_set_contact_recipient( $contact_form ) {
	$submission = WPCF7_Submission::get_instance();

	if ( ! $submission ) {
		return;
	}

	$container_post_id = $submission->get_meta( 'container_post_id' );

	if ( ! $container_post_id ) {
		return;
	}

	$contact_page_url = dz_get_page_url_by_template( 'page-contact.php', false );

	if ( ! $contact_page_url || get_permalink( $container_post_id ) !== $contact_page_url ) {
		return;
	}

	$recipient = dz_get_option( 'dz_contact_email' );

	// Settings not configured yet: leave CF7's own default recipient untouched
	// rather than sending to an empty/invalid address.
	if ( ! $recipient || ! is_email( $recipient ) ) {
		return;
	}

	$mail = $contact_form->prop( 'mail' );

	if ( ! is_array( $mail ) ) {
		return;
	}

	$mail['recipient'] = $recipient;
	$contact_form->set_properties( array( 'mail' => $mail ) );
}
add_action( 'wpcf7_before_send_mail', 'dz_cf7_set_contact_recipient' );

/**
 * Honeypot spam check for the Contact page's CF7 form.
 *
 * Requires a text field named "site-web" to be added manually in wp-admin
 * (CF7 > the site's form > "Formulaire" tab) — named to look like a
 * plausible real field rather than something like "honeypot" that would
 * tip off a bot, and hidden via an inline style on its wrapper (never
 * type="hidden", which spam bots already know to skip):
 *
 *   <span style="position:absolute;left:-9999px;top:-9999px;" aria-hidden="true">
 *       [text site-web tabindex:-1 autocomplete:off]
 *   </span>
 *
 * A human never sees or focuses this field; a bot that blindly fills every
 * field it finds will. Any non-empty value marks the submission as spam so
 * CF7 rejects it with its own generic message, rather than a validation
 * error that would reveal the honeypot was tripped.
 *
 * Reuses the exact same "is this the real contact form" logic as
 * dz_cf7_set_contact_recipient() above (container_post_id vs.
 * dz_get_page_url_by_template()) instead of a second detection mechanism,
 * so a future CF7 form on another page is never affected.
 *
 * @param bool               $spam
 * @param WPCF7_Submission $submission
 * @return bool
 */
function dz_cf7_check_contact_honeypot( $spam, $submission ) {
	if ( $spam ) {
		return $spam;
	}

	$container_post_id = $submission->get_meta( 'container_post_id' );

	if ( ! $container_post_id ) {
		return $spam;
	}

	$contact_page_url = dz_get_page_url_by_template( 'page-contact.php', false );

	if ( ! $contact_page_url || get_permalink( $container_post_id ) !== $contact_page_url ) {
		return $spam;
	}

	$posted_data = $submission->get_posted_data();
	$honeypot    = isset( $posted_data['site-web'] ) ? trim( (string) $posted_data['site-web'] ) : '';

	if ( '' !== $honeypot ) {
		return true;
	}

	return $spam;
}
add_filter( 'wpcf7_spam', 'dz_cf7_check_contact_honeypot', 10, 2 );
