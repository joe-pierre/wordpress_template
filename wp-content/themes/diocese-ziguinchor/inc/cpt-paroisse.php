<?php
/**
 * Registers the paroisse custom post type.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dz_register_cpt_paroisse() {
	$labels = array(
		'name'                  => _x( 'Paroisses', 'Post type general name', 'diocese-ziguinchor' ),
		'singular_name'         => _x( 'Paroisse', 'Post type singular name', 'diocese-ziguinchor' ),
		'menu_name'             => _x( 'Paroisses', 'Admin Menu text', 'diocese-ziguinchor' ),
		'name_admin_bar'        => _x( 'Paroisse', 'Add New on Toolbar', 'diocese-ziguinchor' ),
		'add_new'               => __( 'Ajouter', 'diocese-ziguinchor' ),
		'add_new_item'          => __( 'Ajouter une paroisse', 'diocese-ziguinchor' ),
		'edit_item'             => __( 'Modifier la paroisse', 'diocese-ziguinchor' ),
		'new_item'              => __( 'Nouvelle paroisse', 'diocese-ziguinchor' ),
		'view_item'             => __( 'Voir la paroisse', 'diocese-ziguinchor' ),
		'view_items'            => __( 'Voir les paroisses', 'diocese-ziguinchor' ),
		'search_items'          => __( 'Rechercher une paroisse', 'diocese-ziguinchor' ),
		'not_found'             => __( 'Aucune paroisse trouvée', 'diocese-ziguinchor' ),
		'not_found_in_trash'    => __( 'Aucune paroisse dans la corbeille', 'diocese-ziguinchor' ),
		'all_items'             => __( 'Toutes les paroisses', 'diocese-ziguinchor' ),
		'archives'              => __( 'Archives des paroisses', 'diocese-ziguinchor' ),
		'attributes'            => __( 'Attributs de la paroisse', 'diocese-ziguinchor' ),
		'insert_into_item'      => __( 'Insérer dans la paroisse', 'diocese-ziguinchor' ),
		'uploaded_to_this_item' => __( 'Téléversé pour cette paroisse', 'diocese-ziguinchor' ),
	);

	register_post_type(
		'paroisse',
		array(
			'labels'       => $labels,
			'public'       => true,
			'has_archive'  => true,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-location-alt',
			'menu_position' => 20,
			'supports'     => array( 'title', 'editor', 'thumbnail' ),
			'rewrite'      => array( 'slug' => 'paroisse' ),
		)
	);
}
add_action( 'init', 'dz_register_cpt_paroisse' );
