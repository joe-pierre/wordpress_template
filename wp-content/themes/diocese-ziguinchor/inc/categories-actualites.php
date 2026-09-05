<?php
/**
 * Seeds the native `category` terms still missing for the Actualités
 * rubric (PROMPT 16, SPEC.md §3: "Homélies (déjà en place), Cathéchèses,
 * Communiqués, Nécrologie, Vatican, Diocèse"). "Homélies" already exists
 * (created by hand in wp-admin, not by code) — not re-seeded here, only the
 * 5 still missing. Same idempotent term_exists()/wp_insert_term() pattern
 * as dz_seed_type_aumonerie_terms()/dz_seed_evenement_type_terms().
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dz_seed_categories_actualites() {
	$dz_categories = array(
		__( 'Cathéchèses', 'diocese-ziguinchor' ),
		__( 'Communiqués', 'diocese-ziguinchor' ),
		__( 'Nécrologie', 'diocese-ziguinchor' ),
		__( 'Vatican', 'diocese-ziguinchor' ),
		__( 'Diocèse', 'diocese-ziguinchor' ),
	);

	foreach ( $dz_categories as $dz_name ) {
		if ( ! term_exists( $dz_name, 'category' ) ) {
			wp_insert_term( $dz_name, 'category' );
		}
	}
}
add_action( 'init', 'dz_seed_categories_actualites', 11 );
