<?php
/**
 * Registers the sacrement custom post type.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dz_register_cpt_sacrement() {
	$labels = array(
		'name'                  => _x( 'Sacrements', 'Post type general name', 'diocese-ziguinchor' ),
		'singular_name'         => _x( 'Sacrement', 'Post type singular name', 'diocese-ziguinchor' ),
		'menu_name'             => _x( 'Sacrements', 'Admin Menu text', 'diocese-ziguinchor' ),
		'name_admin_bar'        => _x( 'Sacrement', 'Add New on Toolbar', 'diocese-ziguinchor' ),
		'add_new'               => __( 'Ajouter', 'diocese-ziguinchor' ),
		'add_new_item'          => __( 'Ajouter un sacrement', 'diocese-ziguinchor' ),
		'edit_item'             => __( 'Modifier le sacrement', 'diocese-ziguinchor' ),
		'new_item'              => __( 'Nouveau sacrement', 'diocese-ziguinchor' ),
		'view_item'             => __( 'Voir le sacrement', 'diocese-ziguinchor' ),
		'view_items'            => __( 'Voir les sacrements', 'diocese-ziguinchor' ),
		'search_items'          => __( 'Rechercher un sacrement', 'diocese-ziguinchor' ),
		'not_found'             => __( 'Aucun sacrement trouvé', 'diocese-ziguinchor' ),
		'not_found_in_trash'    => __( 'Aucun sacrement dans la corbeille', 'diocese-ziguinchor' ),
		'all_items'             => __( 'Tous les sacrements', 'diocese-ziguinchor' ),
		'archives'              => __( 'Archives des sacrements', 'diocese-ziguinchor' ),
		'attributes'            => __( 'Attributs du sacrement', 'diocese-ziguinchor' ),
		'insert_into_item'      => __( 'Insérer dans le sacrement', 'diocese-ziguinchor' ),
		'uploaded_to_this_item' => __( 'Téléversé pour ce sacrement', 'diocese-ziguinchor' ),
	);

	register_post_type(
		'sacrement',
		array(
			'labels'        => $labels,
			'public'        => true,
			'has_archive'   => true,
			'show_in_rest'  => true,
			'menu_icon'     => 'dashicons-awards',
			'menu_position' => 23,
			'supports'      => array( 'title', 'editor', 'thumbnail' ),
			'rewrite'       => array( 'slug' => 'sacrement' ),
		)
	);
}
add_action( 'init', 'dz_register_cpt_sacrement' );
