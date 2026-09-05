<?php
/**
 * One-off seed script for PROMPT 16 — creates the 13 simple static pages
 * listed in SPEC.md §3 ("Pages statiques simples (gabarit page.php, pas de
 * CPT dédié)"), so page.php/page-cartographie.php can be checked against
 * real WordPress content once an actual install exists.
 *
 * NOT a theme file — this repository has no live WordPress/MySQL instance
 * to run it against yet (see TODO.md Phase 8, DECISIONS.md). Run once with:
 *
 *     wp eval-file bin/seed-static-pages.php
 *
 * from the WordPress root, after activating the diocese-ziguinchor theme.
 *
 * "Contacts" here is deliberately distinct from the already-implemented
 * "Contact" page (page-contact.php, form + map): SPEC.md §3 lists both —
 * "Contacts" as a sous-page of Accueil (a simple contacts-directory page),
 * "Contact" separately as its own top-level entry (§11) — see DECISIONS.md
 * for this reading.
 *
 * Like bin/seed-cpt-organisation.php (PROMPT 13), every page is created in
 * `draft` status with an explicit "à compléter" editorial placeholder, no
 * invented content — real diocese text (Mot de l'évêque, historique...) is
 * not available in this repository.
 */

if ( ! function_exists( 'wp_insert_post' ) ) {
	echo "This script must be run via `wp eval-file`, not included directly.\n";
	exit( 1 );
}

/**
 * @param string $title
 * @param string $content
 * @param string $page_template Relative theme file name, or '' for the
 *                               default page.php.
 * @return int Post ID.
 */
function dz_seed_static_page( $title, $content, $page_template = '' ) {
	$existing = get_page_by_title( $title, OBJECT, 'page' );
	if ( $existing ) {
		echo "Skipped (already exists): {$title} #{$existing->ID}\n";
		return $existing->ID;
	}

	$post_id = wp_insert_post(
		array(
			'post_type'    => 'page',
			'post_title'   => wp_strip_all_tags( $title ),
			'post_content' => wp_kses_post( $content ),
			'post_status'  => 'draft',
		),
		true
	);

	if ( is_wp_error( $post_id ) ) {
		echo "FAILED: {$title} — " . $post_id->get_error_message() . "\n";
		return 0;
	}

	if ( $page_template ) {
		update_post_meta( $post_id, '_wp_page_template', $page_template );
	}

	echo "Created (draft): {$title} #{$post_id}\n";
	return $post_id;
}

$dz_todo_note = __( 'Page en cours de complétion par le secrétariat diocésain.', 'diocese-ziguinchor' );

dz_seed_static_page( __( "Mot de l'évêque", 'diocese-ziguinchor' ), $dz_todo_note );
dz_seed_static_page( __( 'Contacts', 'diocese-ziguinchor' ), $dz_todo_note );
dz_seed_static_page( __( 'Évêché', 'diocese-ziguinchor' ), $dz_todo_note );
dz_seed_static_page( __( 'Chancellerie', 'diocese-ziguinchor' ), $dz_todo_note );
dz_seed_static_page( __( 'Historique', 'diocese-ziguinchor' ), $dz_todo_note );
dz_seed_static_page( __( "L'évêque", 'diocese-ziguinchor' ), $dz_todo_note );

dz_seed_static_page(
	__( 'Cartographie du diocèse', 'diocese-ziguinchor' ),
	__( "Carte simplifiée en attendant des données géographiques plus précises (localisation de chaque paroisse). La carte affichée reprend l'adresse générale du diocèse, déjà configurée sur la page « Réglages du thème ».", 'diocese-ziguinchor' ),
	'page-cartographie.php'
);

dz_seed_static_page( __( 'Vie Consacrée', 'diocese-ziguinchor' ), $dz_todo_note );
dz_seed_static_page( __( 'Prières', 'diocese-ziguinchor' ), $dz_todo_note );
dz_seed_static_page( __( 'Pèlerinages Nationaux', 'diocese-ziguinchor' ), $dz_todo_note );
dz_seed_static_page( __( 'Pèlerinages Diocésains', 'diocese-ziguinchor' ), $dz_todo_note );

dz_seed_static_page(
	__( 'Devenir bénévole', 'diocese-ziguinchor' ),
	__( 'Formulaire à venir : une fois le plugin Contact Form 7 installé et un formulaire dédié créé (voir TODO.md Phase 6), coller ici son shortcode [contact-form-7 ...].', 'diocese-ziguinchor' )
);

dz_seed_static_page( __( 'Secrétariat diocésain', 'diocese-ziguinchor' ), $dz_todo_note );
