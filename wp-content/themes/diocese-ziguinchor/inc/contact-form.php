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
