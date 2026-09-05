<?php
/**
 * Registers the ancien_eveque (former bishops) custom post type, for the
 * "À propos du diocèse > Archives > Les différents évêques" sub-rubric.
 *
 * Unlike the other organisational CPTs (PROMPT 13-15), this one does NOT
 * share group_dz_cpt_organisation_socle — no responsable/membres, see
 * DECISIONS.md. Its fields are: photo (featured image, no ACF field),
 * période (ancien_eveque_date_debut/_fin, acf-json/group_dz_cpt_ancien_eveque.json),
 * biographie (the_content(), native editor, same convention as every other
 * CPT in this theme).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dz_register_cpt_ancien_eveque() {
	$labels = array(
		'name'                  => _x( 'Anciens évêques', 'Post type general name', 'diocese-ziguinchor' ),
		'singular_name'         => _x( 'Ancien évêque', 'Post type singular name', 'diocese-ziguinchor' ),
		'menu_name'             => _x( 'Anciens évêques', 'Admin Menu text', 'diocese-ziguinchor' ),
		'name_admin_bar'        => _x( 'Ancien évêque', 'Add New on Toolbar', 'diocese-ziguinchor' ),
		'add_new'               => __( 'Ajouter', 'diocese-ziguinchor' ),
		'add_new_item'          => __( 'Ajouter un ancien évêque', 'diocese-ziguinchor' ),
		'edit_item'             => __( "Modifier l'ancien évêque", 'diocese-ziguinchor' ),
		'new_item'              => __( 'Nouvel ancien évêque', 'diocese-ziguinchor' ),
		'view_item'             => __( "Voir l'ancien évêque", 'diocese-ziguinchor' ),
		'view_items'            => __( 'Voir les anciens évêques', 'diocese-ziguinchor' ),
		'search_items'          => __( 'Rechercher un ancien évêque', 'diocese-ziguinchor' ),
		'not_found'             => __( 'Aucun ancien évêque trouvé', 'diocese-ziguinchor' ),
		'not_found_in_trash'    => __( 'Aucun ancien évêque dans la corbeille', 'diocese-ziguinchor' ),
		'all_items'             => __( 'Tous les anciens évêques', 'diocese-ziguinchor' ),
		'archives'              => __( 'Archives des anciens évêques', 'diocese-ziguinchor' ),
		'attributes'            => __( "Attributs de l'ancien évêque", 'diocese-ziguinchor' ),
		'insert_into_item'      => __( "Insérer dans la fiche de l'ancien évêque", 'diocese-ziguinchor' ),
		'uploaded_to_this_item' => __( 'Téléversé pour cet ancien évêque', 'diocese-ziguinchor' ),
	);

	register_post_type(
		'ancien_eveque',
		array(
			'labels'        => $labels,
			'public'        => true,
			'has_archive'   => true,
			'show_in_rest'  => true,
			'menu_icon'     => 'dashicons-archive',
			'menu_position' => 33,
			'supports'      => array( 'title', 'editor', 'thumbnail' ),
			'rewrite'       => array( 'slug' => 'ancien_eveque' ),
		)
	);
}
add_action( 'init', 'dz_register_cpt_ancien_eveque' );

/**
 * Archive sorted by mandate start date (chronological), not WordPress
 * publication date — see DECISIONS.md for the ASC ("oldest first") choice.
 */
function dz_ancien_eveque_archive_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( is_post_type_archive( 'ancien_eveque' ) ) {
		$query->set( 'meta_key', 'ancien_eveque_date_debut' );
		$query->set( 'meta_type', 'DATE' );
		$query->set( 'orderby', 'meta_value' );
		$query->set( 'order', 'ASC' );
	}
}
add_action( 'pre_get_posts', 'dz_ancien_eveque_archive_query' );

/**
 * Human-readable mandate period ("1988 – 2005", or "Depuis 1988" when no end
 * date is set), used by single-ancien_eveque.php and
 * template-parts/card-ancien-eveque.php.
 *
 * @param int $post_id
 * @return string
 */
function dz_get_ancien_eveque_periode_label( $post_id ) {
	$dz_debut = dz_get_field( 'ancien_eveque_date_debut', $post_id );
	if ( ! $dz_debut ) {
		return '';
	}

	$dz_annee_debut = date_i18n( 'Y', strtotime( $dz_debut ) );

	$dz_fin = dz_get_field( 'ancien_eveque_date_fin', $post_id );
	if ( ! $dz_fin ) {
		/* translators: %s: mandate start year */
		return sprintf( __( 'Depuis %s', 'diocese-ziguinchor' ), $dz_annee_debut );
	}

	return sprintf(
		/* translators: 1: mandate start year, 2: mandate end year */
		__( '%1$s – %2$s', 'diocese-ziguinchor' ),
		$dz_annee_debut,
		date_i18n( 'Y', strtotime( $dz_fin ) )
	);
}
