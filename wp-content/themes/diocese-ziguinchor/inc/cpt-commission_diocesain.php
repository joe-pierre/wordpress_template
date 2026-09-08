<?php
/**
 * Registers the commission_diocesain custom post type.
 *
 * Slug is "commission_diocesain" (20 characters), NOT the grammatically
 * correct "commission_diocesaine" (21) — WordPress hard-rejects any post
 * type name over 20 characters (register_post_type() returns a WP_Error
 * without registering anything; wp_posts.post_type is varchar(20)). This
 * was the actual slug for a while and caused every wp_insert_post() call
 * for it to fail in production once the database ran in strict SQL mode
 * (silent truncation otherwise) — see DECISIONS.md "commission_diocesaine :
 * slug de 21 caractères, au-delà de la limite WordPress". Only the internal
 * identifier is shortened; labels/menus below still read "Commission(s)
 * diocésaine(s)" correctly.
 *
 * See SPEC.md §3 and DECISIONS.md ("Un Custom Post Type séparé par grande
 * rubrique organisationnelle"). Covers Catéchèse, Cellule d'écoute,
 * Écologie intégrale, Dialogue œcuménique, Justice et Paix, Pastorale de la
 * Famille, Liturgie, Pastorale de la Santé, Pastorale des Vocations, Textes
 * liturgiques en langues locales.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dz_register_cpt_commission_diocesain() {
	$labels = array(
		'name'                  => _x( 'Commissions diocésaines', 'Post type general name', 'diocese-ziguinchor' ),
		'singular_name'         => _x( 'Commission diocésaine', 'Post type singular name', 'diocese-ziguinchor' ),
		'menu_name'             => _x( 'Commissions diocésaines', 'Admin Menu text', 'diocese-ziguinchor' ),
		'name_admin_bar'        => _x( 'Commission diocésaine', 'Add New on Toolbar', 'diocese-ziguinchor' ),
		'add_new'               => __( 'Ajouter', 'diocese-ziguinchor' ),
		'add_new_item'          => __( 'Ajouter une commission diocésaine', 'diocese-ziguinchor' ),
		'edit_item'             => __( 'Modifier la commission diocésaine', 'diocese-ziguinchor' ),
		'new_item'              => __( 'Nouvelle commission diocésaine', 'diocese-ziguinchor' ),
		'view_item'             => __( 'Voir la commission diocésaine', 'diocese-ziguinchor' ),
		'view_items'            => __( 'Voir les commissions diocésaines', 'diocese-ziguinchor' ),
		'search_items'          => __( 'Rechercher une commission diocésaine', 'diocese-ziguinchor' ),
		'not_found'             => __( 'Aucune commission diocésaine trouvée', 'diocese-ziguinchor' ),
		'not_found_in_trash'    => __( 'Aucune commission diocésaine dans la corbeille', 'diocese-ziguinchor' ),
		'all_items'             => __( 'Toutes les commissions diocésaines', 'diocese-ziguinchor' ),
		'archives'              => __( 'Archives des commissions diocésaines', 'diocese-ziguinchor' ),
		'attributes'            => __( 'Attributs de la commission diocésaine', 'diocese-ziguinchor' ),
		'insert_into_item'      => __( 'Insérer dans la commission diocésaine', 'diocese-ziguinchor' ),
		'uploaded_to_this_item' => __( 'Téléversé pour cette commission diocésaine', 'diocese-ziguinchor' ),
	);

	register_post_type(
		'commission_diocesain',
		array(
			'labels'        => $labels,
			'public'        => true,
			'has_archive'   => true,
			'show_in_rest'  => true,
			'menu_icon'     => 'dashicons-portfolio',
			'menu_position' => 28,
			'supports'      => array( 'title', 'editor', 'thumbnail' ),
			'rewrite'       => array( 'slug' => 'commission_diocesain' ),
		)
	);
}
add_action( 'init', 'dz_register_cpt_commission_diocesain' );
