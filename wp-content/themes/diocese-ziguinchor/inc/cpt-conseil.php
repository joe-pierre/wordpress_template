<?php
/**
 * Registers the conseil (bishop's council) custom post type.
 *
 * First of the organisational CPTs added following Arborescence_PDF.pdf and
 * NOMINATIONS_SERVICES_COMMISSIONS_AUMONERIES_2027.pdf — see SPEC.md §3 and
 * DECISIONS.md ("Un Custom Post Type séparé par grande rubrique
 * organisationnelle"). Covers "Les Conseils de l'évêque" (Conseil épiscopal,
 * presbytéral, Collège des consulteurs, Affaires économiques, Pastoral
 * Diocésain) as one CPT with several entries, not one CPT per council.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dz_register_cpt_conseil() {
	$labels = array(
		'name'                  => _x( 'Conseils', 'Post type general name', 'diocese-ziguinchor' ),
		'singular_name'         => _x( 'Conseil', 'Post type singular name', 'diocese-ziguinchor' ),
		'menu_name'             => _x( 'Conseils de l\'évêque', 'Admin Menu text', 'diocese-ziguinchor' ),
		'name_admin_bar'        => _x( 'Conseil', 'Add New on Toolbar', 'diocese-ziguinchor' ),
		'add_new'               => __( 'Ajouter', 'diocese-ziguinchor' ),
		'add_new_item'          => __( 'Ajouter un conseil', 'diocese-ziguinchor' ),
		'edit_item'             => __( 'Modifier le conseil', 'diocese-ziguinchor' ),
		'new_item'              => __( 'Nouveau conseil', 'diocese-ziguinchor' ),
		'view_item'             => __( 'Voir le conseil', 'diocese-ziguinchor' ),
		'view_items'            => __( 'Voir les conseils', 'diocese-ziguinchor' ),
		'search_items'          => __( 'Rechercher un conseil', 'diocese-ziguinchor' ),
		'not_found'             => __( 'Aucun conseil trouvé', 'diocese-ziguinchor' ),
		'not_found_in_trash'    => __( 'Aucun conseil dans la corbeille', 'diocese-ziguinchor' ),
		'all_items'             => __( 'Tous les conseils', 'diocese-ziguinchor' ),
		'archives'              => __( 'Archives des conseils', 'diocese-ziguinchor' ),
		'attributes'            => __( 'Attributs du conseil', 'diocese-ziguinchor' ),
		'insert_into_item'      => __( 'Insérer dans le conseil', 'diocese-ziguinchor' ),
		'uploaded_to_this_item' => __( 'Téléversé pour ce conseil', 'diocese-ziguinchor' ),
	);

	register_post_type(
		'conseil',
		array(
			'labels'        => $labels,
			'public'        => true,
			'has_archive'   => true,
			'show_in_rest'  => true,
			'menu_icon'     => 'dashicons-groups',
			'menu_position' => 26,
			'supports'      => array( 'title', 'editor', 'thumbnail' ),
			'rewrite'       => array( 'slug' => 'conseil' ),
		)
	);
}
add_action( 'init', 'dz_register_cpt_conseil' );
