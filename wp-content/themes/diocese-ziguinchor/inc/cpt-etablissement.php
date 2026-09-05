<?php
/**
 * Registers the etablissement (Enseignements diocésains) custom post type.
 *
 * Same organisational socle as conseil/service_diocesain/commission_diocesaine/
 * mouvement/association/aumonerie (PROMPT 13-14) — see SPEC.md §3 and
 * DECISIONS.md ("Un Custom Post Type séparé par grande rubrique
 * organisationnelle"). Covers DIDEC, Séminaires et Maisons de formation,
 * Collèges Diocésains, Enseignement Supérieur. Adds its own
 * `type_etablissement`/`etablissement_contact` fields, see
 * acf-json/group_dz_cpt_etablissement.json.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dz_register_cpt_etablissement() {
	$labels = array(
		'name'                  => _x( 'Établissements', 'Post type general name', 'diocese-ziguinchor' ),
		'singular_name'         => _x( 'Établissement', 'Post type singular name', 'diocese-ziguinchor' ),
		'menu_name'             => _x( 'Enseignements diocésains', 'Admin Menu text', 'diocese-ziguinchor' ),
		'name_admin_bar'        => _x( 'Établissement', 'Add New on Toolbar', 'diocese-ziguinchor' ),
		'add_new'               => __( 'Ajouter', 'diocese-ziguinchor' ),
		'add_new_item'          => __( 'Ajouter un établissement', 'diocese-ziguinchor' ),
		'edit_item'             => __( "Modifier l'établissement", 'diocese-ziguinchor' ),
		'new_item'              => __( 'Nouvel établissement', 'diocese-ziguinchor' ),
		'view_item'             => __( "Voir l'établissement", 'diocese-ziguinchor' ),
		'view_items'            => __( 'Voir les établissements', 'diocese-ziguinchor' ),
		'search_items'          => __( 'Rechercher un établissement', 'diocese-ziguinchor' ),
		'not_found'             => __( 'Aucun établissement trouvé', 'diocese-ziguinchor' ),
		'not_found_in_trash'    => __( 'Aucun établissement dans la corbeille', 'diocese-ziguinchor' ),
		'all_items'             => __( 'Tous les établissements', 'diocese-ziguinchor' ),
		'archives'              => __( 'Archives des établissements', 'diocese-ziguinchor' ),
		'attributes'            => __( "Attributs de l'établissement", 'diocese-ziguinchor' ),
		'insert_into_item'      => __( "Insérer dans l'établissement", 'diocese-ziguinchor' ),
		'uploaded_to_this_item' => __( 'Téléversé pour cet établissement', 'diocese-ziguinchor' ),
	);

	register_post_type(
		'etablissement',
		array(
			'labels'        => $labels,
			'public'        => true,
			'has_archive'   => true,
			'show_in_rest'  => true,
			'menu_icon'     => 'dashicons-welcome-learn-more',
			'menu_position' => 32,
			'supports'      => array( 'title', 'editor', 'thumbnail' ),
			'rewrite'       => array( 'slug' => 'etablissement' ),
		)
	);
}
add_action( 'init', 'dz_register_cpt_etablissement' );

/**
 * French label for a `type_etablissement` choice (see
 * acf-json/group_dz_cpt_etablissement.json).
 *
 * @param string $type
 * @return string
 */
function dz_get_etablissement_type_label( $type ) {
	$dz_labels = array(
		'didec'                      => __( 'DIDEC', 'diocese-ziguinchor' ),
		'seminaire_maison_formation' => __( 'Séminaire / Maison de formation', 'diocese-ziguinchor' ),
		'college_diocesain'          => __( 'Collège diocésain', 'diocese-ziguinchor' ),
		'enseignement_superieur'     => __( 'Enseignement supérieur', 'diocese-ziguinchor' ),
	);

	return isset( $dz_labels[ $type ] ) ? $dz_labels[ $type ] : '';
}
