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
			'menu_position' => 22,
			'supports'      => array( 'title', 'editor', 'thumbnail' ),
			'rewrite'       => array( 'slug' => 'pretre' ),
		)
	);
}
add_action( 'init', 'dz_register_cpt_pretre' );

/**
 * Directory listing sorted alphabetically by name, more usable than the
 * post-type archive's date-based default for a clergy directory.
 */
function dz_pretre_archive_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( is_post_type_archive( 'pretre' ) ) {
		$query->set( 'orderby', 'title' );
		$query->set( 'order', 'ASC' );
	}
}
add_action( 'pre_get_posts', 'dz_pretre_archive_query' );

/**
 * French label for a `pretre_fonction` choice (see acf-json/group_dz_cpt_pretre.json).
 *
 * @param string $fonction
 * @return string
 */
function dz_get_pretre_fonction_label( $fonction ) {
	$dz_labels = array(
		'cure'    => __( 'Curé', 'diocese-ziguinchor' ),
		'vicaire' => __( 'Vicaire', 'diocese-ziguinchor' ),
		'diacre'  => __( 'Diacre', 'diocese-ziguinchor' ),
		'autre'   => __( 'Autre', 'diocese-ziguinchor' ),
	);

	return isset( $dz_labels[ $fonction ] ) ? $dz_labels[ $fonction ] : '';
}

/**
 * French label for a `pretre_statut` choice (see acf-json/group_dz_cpt_pretre.json).
 *
 * @param string $statut
 * @return string
 */
function dz_get_pretre_statut_label( $statut ) {
	$dz_labels = array(
		'en_fonction'      => __( 'En fonction', 'diocese-ziguinchor' ),
		'retraite'         => __( 'Retraité', 'diocese-ziguinchor' ),
		'en_formation'     => __( 'En formation', 'diocese-ziguinchor' ),
		'sans_affectation' => __( 'Sans affectation', 'diocese-ziguinchor' ),
	);

	return isset( $dz_labels[ $statut ] ) ? $dz_labels[ $statut ] : '';
}
