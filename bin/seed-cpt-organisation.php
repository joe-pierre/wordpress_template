<?php
/**
 * One-off seed script for PROMPT 13 — creates a demo "Conseil épiscopal"
 * entry (CPT conseil) so single-conseil.php/archive-conseil.php can be
 * checked against real WordPress content once an actual install exists.
 *
 * NOT a theme file — this repository has no live WordPress/MySQL instance
 * to run it against yet (see TODO.md Phase 8, DECISIONS.md). Run once with:
 *
 *     wp eval-file bin/seed-cpt-organisation.php
 *
 * from the WordPress root, after activating the diocese-ziguinchor theme
 * and ACF Pro.
 *
 * Originally also seeded a demo "Catéchèse" (commission_diocesaine) and
 * "Économat" (service_diocesain) entry — removed at CONTENT_PROMPTS.md
 * PROMPT 3, which now creates both (as "Catéchèse" and "Économat
 * Diocésain" respectively) through the proper idempotent import tool
 * (Réglages > Import contenu diocèse, inc/import/import-tools.php).
 * Running both scripts against the same site would otherwise duplicate
 * those two entries: this script's own `get_page_by_title()` idempotence
 * check has no way to see the `_dz_import_source_id`-tagged posts the
 * newer tool creates, and vice versa — see DECISIONS.md. `conseil` isn't
 * covered by any CONTENT_PROMPTS.md prompt, so this script keeps that one
 * entity as its sole remaining responsibility.
 *
 * IMPORTANT — NOMINATIONS_SERVICES_COMMISSIONS_AUMONERIES_2027.pdf (the
 * source of the real personnel names for "responsable"/"membres") is not
 * present in this repository, so this script deliberately does NOT invent
 * any person's name. It only seeds the post itself (title = real rubric
 * name already documented in SPEC.md §3, content = an explicit "to be
 * completed" editorial note) and leaves the org_responsable/org_membres
 * ACF fields empty — the templates already handle that as a clean empty
 * state. Fill those fields in from the PDF once it is available.
 */

if ( ! function_exists( 'wp_insert_post' ) ) {
	echo "This script must be run via `wp eval-file`, not included directly.\n";
	exit( 1 );
}

/**
 * @param string $post_type
 * @param string $title
 * @param string $todo_note Editorial placeholder shown as the entry's content.
 * @return int Post ID.
 */
function dz_seed_organisation_entry( $post_type, $title, $todo_note ) {
	$existing = get_page_by_title( $title, OBJECT, $post_type );
	if ( $existing ) {
		echo "Skipped (already exists): {$title} [{$post_type}] #{$existing->ID}\n";
		return $existing->ID;
	}

	$post_id = wp_insert_post(
		array(
			'post_type'    => $post_type,
			'post_title'   => wp_strip_all_tags( $title ),
			'post_content' => wp_kses_post( $todo_note ),
			'post_status'  => 'draft',
		),
		true
	);

	if ( is_wp_error( $post_id ) ) {
		echo "FAILED: {$title} [{$post_type}] — " . $post_id->get_error_message() . "\n";
		return 0;
	}

	echo "Created (draft): {$title} [{$post_type}] #{$post_id}\n";
	return $post_id;
}

$dz_todo_note = __(
	'Fiche en cours de complétion — responsable et composition à saisir à partir de NOMINATIONS_SERVICES_COMMISSIONS_AUMONERIES_2027.pdf.',
	'diocese-ziguinchor'
);

dz_seed_organisation_entry( 'conseil', __( 'Conseil épiscopal', 'diocese-ziguinchor' ), $dz_todo_note );
