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
			'menu_position' => 21,
			'supports'     => array( 'title', 'editor', 'thumbnail' ),
			'rewrite'      => array( 'slug' => 'paroisse' ),
		)
	);
}
add_action( 'init', 'dz_register_cpt_paroisse' );

/**
 * Directory listing sorted alphabetically by name, more usable than the
 * post-type archive's date-based default for a parish directory.
 */
function dz_paroisse_archive_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( is_post_type_archive( 'paroisse' ) ) {
		$query->set( 'orderby', 'title' );
		$query->set( 'order', 'ASC' );
	}
}
add_action( 'pre_get_posts', 'dz_paroisse_archive_query' );

/**
 * Clergy assigned to a paroisse, optionally filtered by function
 * (see field_dz_pretre_fonction: cure/vicaire/diacre/autre).
 *
 * Reads the `paroisse_pretres` relationship field, which ACF keeps in sync
 * with each pretre's own `pretre_paroisse` field (bidirectional relation,
 * see DECISIONS.md) — this is the single source of truth for "who serves
 * this parish", there is no separately maintained list.
 *
 * @param int    $paroisse_id
 * @param string $fonction Optional. 'cure', 'vicaire', 'diacre', 'autre'. Empty returns all.
 * @return WP_Post[]
 */
function dz_get_paroisse_clergy( $paroisse_id, $fonction = '' ) {
	$dz_pretre_ids = dz_get_field( 'paroisse_pretres', $paroisse_id, array() );
	$dz_clergy     = array();

	foreach ( (array) $dz_pretre_ids as $dz_pretre_id ) {
		if ( '' !== $fonction && $fonction !== dz_get_field( 'pretre_fonction', $dz_pretre_id ) ) {
			continue;
		}

		$dz_pretre = get_post( $dz_pretre_id );
		if ( $dz_pretre ) {
			$dz_clergy[] = $dz_pretre;
		}
	}

	return $dz_clergy;
}
