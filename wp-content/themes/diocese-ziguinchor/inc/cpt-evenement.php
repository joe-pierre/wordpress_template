<?php
/**
 * Registers the evenement custom post type.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dz_register_cpt_evenement() {
	$labels = array(
		'name'                  => _x( 'Événements', 'Post type general name', 'diocese-ziguinchor' ),
		'singular_name'         => _x( 'Événement', 'Post type singular name', 'diocese-ziguinchor' ),
		'menu_name'             => _x( 'Événements', 'Admin Menu text', 'diocese-ziguinchor' ),
		'name_admin_bar'        => _x( 'Événement', 'Add New on Toolbar', 'diocese-ziguinchor' ),
		'add_new'               => __( 'Ajouter', 'diocese-ziguinchor' ),
		'add_new_item'          => __( 'Ajouter un événement', 'diocese-ziguinchor' ),
		'edit_item'             => __( 'Modifier l\'événement', 'diocese-ziguinchor' ),
		'new_item'              => __( 'Nouvel événement', 'diocese-ziguinchor' ),
		'view_item'             => __( 'Voir l\'événement', 'diocese-ziguinchor' ),
		'view_items'            => __( 'Voir les événements', 'diocese-ziguinchor' ),
		'search_items'          => __( 'Rechercher un événement', 'diocese-ziguinchor' ),
		'not_found'             => __( 'Aucun événement trouvé', 'diocese-ziguinchor' ),
		'not_found_in_trash'    => __( 'Aucun événement dans la corbeille', 'diocese-ziguinchor' ),
		'all_items'             => __( 'Tous les événements', 'diocese-ziguinchor' ),
		'archives'              => __( 'Archives des événements', 'diocese-ziguinchor' ),
		'attributes'            => __( 'Attributs de l\'événement', 'diocese-ziguinchor' ),
		'insert_into_item'      => __( 'Insérer dans l\'événement', 'diocese-ziguinchor' ),
		'uploaded_to_this_item' => __( 'Téléversé pour cet événement', 'diocese-ziguinchor' ),
	);

	register_post_type(
		'evenement',
		array(
			'labels'        => $labels,
			'public'        => true,
			'has_archive'   => true,
			'show_in_rest'  => true,
			'menu_icon'     => 'dashicons-calendar-alt',
			'menu_position' => 22,
			'supports'      => array( 'title', 'editor', 'thumbnail' ),
			'rewrite'       => array( 'slug' => 'evenement' ),
		)
	);
}
add_action( 'init', 'dz_register_cpt_evenement' );
