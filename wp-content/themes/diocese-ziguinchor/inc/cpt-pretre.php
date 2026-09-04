<?php
/**
 * Registers the pretre custom post type.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dz_register_cpt_pretre() {
	$labels = array(
		'name'                  => _x( 'Prêtres', 'Post type general name', 'diocese-ziguinchor' ),
		'singular_name'         => _x( 'Prêtre', 'Post type singular name', 'diocese-ziguinchor' ),
		'menu_name'             => _x( 'Prêtres', 'Admin Menu text', 'diocese-ziguinchor' ),
		'name_admin_bar'        => _x( 'Prêtre', 'Add New on Toolbar', 'diocese-ziguinchor' ),
		'add_new'               => __( 'Ajouter', 'diocese-ziguinchor' ),
		'add_new_item'          => __( 'Ajouter un prêtre', 'diocese-ziguinchor' ),
		'edit_item'             => __( 'Modifier le prêtre', 'diocese-ziguinchor' ),
		'new_item'              => __( 'Nouveau prêtre', 'diocese-ziguinchor' ),
		'view_item'             => __( 'Voir le prêtre', 'diocese-ziguinchor' ),
		'view_items'            => __( 'Voir les prêtres', 'diocese-ziguinchor' ),
		'search_items'          => __( 'Rechercher un prêtre', 'diocese-ziguinchor' ),
		'not_found'             => __( 'Aucun prêtre trouvé', 'diocese-ziguinchor' ),
		'not_found_in_trash'    => __( 'Aucun prêtre dans la corbeille', 'diocese-ziguinchor' ),
		'all_items'             => __( 'Tous les prêtres', 'diocese-ziguinchor' ),
		'archives'              => __( 'Archives des prêtres', 'diocese-ziguinchor' ),
		'attributes'            => __( 'Attributs du prêtre', 'diocese-ziguinchor' ),
		'insert_into_item'      => __( 'Insérer dans la fiche du prêtre', 'diocese-ziguinchor' ),
		'uploaded_to_this_item' => __( 'Téléversé pour ce prêtre', 'diocese-ziguinchor' ),
	);

	register_post_type(
		'pretre',
		array(
			'labels'        => $labels,
			'public'        => true,
			'has_archive'   => true,
			'show_in_rest'  => true,
			'menu_icon'     => 'dashicons-admin-users',
			'menu_position' => 21,
			'supports'      => array( 'title', 'editor', 'thumbnail' ),
			'rewrite'       => array( 'slug' => 'pretre' ),
		)
	);
}
add_action( 'init', 'dz_register_cpt_pretre' );
