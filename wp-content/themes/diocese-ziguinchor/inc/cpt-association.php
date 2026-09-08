<?php
/**
 * Registers the association (Associations et Groupes d'Apostolat) custom post type.
 *
 * Same organisational socle as conseil/service_diocesain/commission_diocesain
 * (PROMPT 13) — see SPEC.md §3 and DECISIONS.md ("Un Custom Post Type séparé
 * par grande rubrique organisationnelle"). Covers UDAFC/Z, Légion de Marie,
 * Coordination des Chorales, Renouveau Charismatique, Vie Montante, Équipes
 * Enseignantes, Forces de Défense et Sécurité. No field specific to this CPT.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dz_register_cpt_association() {
	$labels = array(
		'name'                  => _x( 'Associations', 'Post type general name', 'diocese-ziguinchor' ),
		'singular_name'         => _x( 'Association', 'Post type singular name', 'diocese-ziguinchor' ),
		'menu_name'             => _x( "Associations et Groupes d'Apostolat", 'Admin Menu text', 'diocese-ziguinchor' ),
		'name_admin_bar'        => _x( 'Association', 'Add New on Toolbar', 'diocese-ziguinchor' ),
		'add_new'               => __( 'Ajouter', 'diocese-ziguinchor' ),
		'add_new_item'          => __( 'Ajouter une association', 'diocese-ziguinchor' ),
		'edit_item'             => __( "Modifier l'association", 'diocese-ziguinchor' ),
		'new_item'              => __( 'Nouvelle association', 'diocese-ziguinchor' ),
		'view_item'             => __( "Voir l'association", 'diocese-ziguinchor' ),
		'view_items'            => __( 'Voir les associations', 'diocese-ziguinchor' ),
		'search_items'          => __( 'Rechercher une association', 'diocese-ziguinchor' ),
		'not_found'             => __( 'Aucune association trouvée', 'diocese-ziguinchor' ),
		'not_found_in_trash'    => __( 'Aucune association dans la corbeille', 'diocese-ziguinchor' ),
		'all_items'             => __( 'Toutes les associations', 'diocese-ziguinchor' ),
		'archives'              => __( 'Archives des associations', 'diocese-ziguinchor' ),
		'attributes'            => __( "Attributs de l'association", 'diocese-ziguinchor' ),
		'insert_into_item'      => __( "Insérer dans l'association", 'diocese-ziguinchor' ),
		'uploaded_to_this_item' => __( 'Téléversé pour cette association', 'diocese-ziguinchor' ),
	);

	register_post_type(
		'association',
		array(
			'labels'        => $labels,
			'public'        => true,
			'has_archive'   => true,
			'show_in_rest'  => true,
			'menu_icon'     => 'dashicons-networking',
			'menu_position' => 30,
			'supports'      => array( 'title', 'editor', 'thumbnail' ),
			'rewrite'       => array( 'slug' => 'association' ),
		)
	);
}
add_action( 'init', 'dz_register_cpt_association' );
