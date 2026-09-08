<?php
/**
 * Registers the mouvement (Mouvements d'Action Catholique) custom post type.
 *
 * Same organisational socle as conseil/service_diocesain/commission_diocesain
 * (PROMPT 13) — see SPEC.md §3 and DECISIONS.md ("Un Custom Post Type séparé
 * par grande rubrique organisationnelle"). Covers Coordination des Jeunes,
 * CV/AV, JAC/UJRCS/MARCS, JOC, JEC, Scouts et Guides. Adds its own
 * `mouvement_aumonier` relation field, see acf-json/group_dz_cpt_mouvement.json.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dz_register_cpt_mouvement() {
	$labels = array(
		'name'                  => _x( 'Mouvements', 'Post type general name', 'diocese-ziguinchor' ),
		'singular_name'         => _x( 'Mouvement', 'Post type singular name', 'diocese-ziguinchor' ),
		'menu_name'             => _x( "Mouvements d'Action Catholique", 'Admin Menu text', 'diocese-ziguinchor' ),
		'name_admin_bar'        => _x( 'Mouvement', 'Add New on Toolbar', 'diocese-ziguinchor' ),
		'add_new'               => __( 'Ajouter', 'diocese-ziguinchor' ),
		'add_new_item'          => __( 'Ajouter un mouvement', 'diocese-ziguinchor' ),
		'edit_item'             => __( 'Modifier le mouvement', 'diocese-ziguinchor' ),
		'new_item'              => __( 'Nouveau mouvement', 'diocese-ziguinchor' ),
		'view_item'             => __( 'Voir le mouvement', 'diocese-ziguinchor' ),
		'view_items'            => __( 'Voir les mouvements', 'diocese-ziguinchor' ),
		'search_items'          => __( 'Rechercher un mouvement', 'diocese-ziguinchor' ),
		'not_found'             => __( 'Aucun mouvement trouvé', 'diocese-ziguinchor' ),
		'not_found_in_trash'    => __( 'Aucun mouvement dans la corbeille', 'diocese-ziguinchor' ),
		'all_items'             => __( 'Tous les mouvements', 'diocese-ziguinchor' ),
		'archives'              => __( 'Archives des mouvements', 'diocese-ziguinchor' ),
		'attributes'            => __( 'Attributs du mouvement', 'diocese-ziguinchor' ),
		'insert_into_item'      => __( 'Insérer dans le mouvement', 'diocese-ziguinchor' ),
		'uploaded_to_this_item' => __( 'Téléversé pour ce mouvement', 'diocese-ziguinchor' ),
	);

	register_post_type(
		'mouvement',
		array(
			'labels'        => $labels,
			'public'        => true,
			'has_archive'   => true,
			'show_in_rest'  => true,
			'menu_icon'     => 'dashicons-flag',
			'menu_position' => 29,
			'supports'      => array( 'title', 'editor', 'thumbnail' ),
			'rewrite'       => array( 'slug' => 'mouvement' ),
		)
	);
}
add_action( 'init', 'dz_register_cpt_mouvement' );
