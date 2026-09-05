<?php
/**
 * Registers the service_diocesain custom post type.
 *
 * See SPEC.md §3 and DECISIONS.md ("Un Custom Post Type séparé par grande
 * rubrique organisationnelle"). Covers Économat, Caritas, ODEC, Apostolat
 * des Laïcs, Coopération Missionnaire, Exorcisme, Cérémoniaires, Formation
 * et Recherche, Communication, Pèlerinages — some with attached
 * sous-structures (Hôtel Carabane, Librairie Djibékel, Imprimerie du Sud),
 * see field_dz_service_diocesain_sous_structures in acf-json/.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dz_register_cpt_service_diocesain() {
	$labels = array(
		'name'                  => _x( 'Services diocésains', 'Post type general name', 'diocese-ziguinchor' ),
		'singular_name'         => _x( 'Service diocésain', 'Post type singular name', 'diocese-ziguinchor' ),
		'menu_name'             => _x( 'Services diocésains', 'Admin Menu text', 'diocese-ziguinchor' ),
		'name_admin_bar'        => _x( 'Service diocésain', 'Add New on Toolbar', 'diocese-ziguinchor' ),
		'add_new'               => __( 'Ajouter', 'diocese-ziguinchor' ),
		'add_new_item'          => __( 'Ajouter un service diocésain', 'diocese-ziguinchor' ),
		'edit_item'             => __( 'Modifier le service diocésain', 'diocese-ziguinchor' ),
		'new_item'              => __( 'Nouveau service diocésain', 'diocese-ziguinchor' ),
		'view_item'             => __( 'Voir le service diocésain', 'diocese-ziguinchor' ),
		'view_items'            => __( 'Voir les services diocésains', 'diocese-ziguinchor' ),
		'search_items'          => __( 'Rechercher un service diocésain', 'diocese-ziguinchor' ),
		'not_found'             => __( 'Aucun service diocésain trouvé', 'diocese-ziguinchor' ),
		'not_found_in_trash'    => __( 'Aucun service diocésain dans la corbeille', 'diocese-ziguinchor' ),
		'all_items'             => __( 'Tous les services diocésains', 'diocese-ziguinchor' ),
		'archives'              => __( 'Archives des services diocésains', 'diocese-ziguinchor' ),
		'attributes'            => __( 'Attributs du service diocésain', 'diocese-ziguinchor' ),
		'insert_into_item'      => __( 'Insérer dans le service diocésain', 'diocese-ziguinchor' ),
		'uploaded_to_this_item' => __( 'Téléversé pour ce service diocésain', 'diocese-ziguinchor' ),
	);

	register_post_type(
		'service_diocesain',
		array(
			'labels'        => $labels,
			'public'        => true,
			'has_archive'   => true,
			'show_in_rest'  => true,
			'menu_icon'     => 'dashicons-admin-tools',
			'menu_position' => 27,
			'supports'      => array( 'title', 'editor', 'thumbnail' ),
			'rewrite'       => array( 'slug' => 'service_diocesain' ),
		)
	);
}
add_action( 'init', 'dz_register_cpt_service_diocesain' );
