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
 * post-type archive's date-based default for a clergy directory. Also
 * powers the "Nos prêtres" role pills (`?fonction=`) and free-text search
 * (`?q=`) — see DECISIONS.md "Refonte archive prêtres". Both filters are
 * plain query vars driving the main DB query (same `?evenement_type=`/
 * `?type_aumonerie=` full-page-reload pattern already used by the other
 * filtered archives, see `dz_get_pretre_search_ids()` below), on purpose:
 * scales to any number of priests server-side, unlike a client-side JS
 * filter that would need to load every card in the DOM up front.
 */
function dz_pretre_archive_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( ! is_post_type_archive( 'pretre' ) ) {
		return;
	}

	$query->set( 'orderby', 'title' );
	$query->set( 'order', 'ASC' );

	$dz_fonction = isset( $_GET['fonction'] ) ? sanitize_key( wp_unslash( $_GET['fonction'] ) ) : '';
	if ( $dz_fonction && array_key_exists( $dz_fonction, dz_get_pretre_fonction_labels() ) ) {
		$query->set( 'meta_key', 'pretre_fonction' );
		$query->set( 'meta_value', $dz_fonction );
	}

	$dz_search = isset( $_GET['q'] ) ? sanitize_text_field( wp_unslash( $_GET['q'] ) ) : '';
	if ( '' !== $dz_search ) {
		$dz_matching_ids = dz_get_pretre_search_ids( $dz_search );
		// No match at all: force an empty result set rather than falling
		// back to "no filter" (post__in => [] is ignored by WP_Query).
		$query->set( 'post__in', $dz_matching_ids ? $dz_matching_ids : array( 0 ) );
	}
}
add_action( 'pre_get_posts', 'dz_pretre_archive_query' );

/**
 * IDs of `pretre` posts matching a free-text search on either the priest's
 * own name or the name of his assigned paroisse (SPEC.md/prompt "Nos
 * prêtres" — "recherche... sur le nom du prêtre et/ou le nom de sa paroisse
 * d'affectation"). Two small DB-side lookups + a merge, no client-side
 * dataset size limit as the clergy directory grows.
 *
 * @param string $term
 * @return int[]
 */
function dz_get_pretre_search_ids( $term ) {
	$dz_by_name = get_posts(
		array(
			'post_type'      => 'pretre',
			'posts_per_page' => -1,
			's'              => $term,
			'fields'         => 'ids',
		)
	);

	$dz_paroisse_ids = get_posts(
		array(
			'post_type'      => 'paroisse',
			'posts_per_page' => -1,
			's'              => $term,
			'fields'         => 'ids',
		)
	);

	$dz_by_paroisse = array();
	if ( $dz_paroisse_ids ) {
		$dz_by_paroisse = get_posts(
			array(
				'post_type'      => 'pretre',
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'meta_query'     => array(
					array(
						'key'     => 'pretre_paroisse',
						'value'   => $dz_paroisse_ids,
						'compare' => 'IN',
					),
				),
			)
		);
	}

	return array_unique( array_merge( $dz_by_name, $dz_by_paroisse ) );
}

/**
 * All `pretre_fonction` choices and their French label (see
 * acf-json/group_dz_cpt_pretre.json — single source of truth for both the
 * ACF choices and this list, kept manually in sync since ACF-JSON choices
 * aren't PHP-readable without loading ACF's admin-only importer).
 *
 * @return array<string,string> fonction key => label
 */
function dz_get_pretre_fonction_labels() {
	return array(
		'cure'              => __( 'Curé', 'diocese-ziguinchor' ),
		'vicaire'           => __( 'Vicaire', 'diocese-ziguinchor' ),
		'econome'           => __( 'Économe', 'diocese-ziguinchor' ),
		'service_diocesain' => __( 'Service diocésain', 'diocese-ziguinchor' ),
		'diacre'            => __( 'Diacre', 'diocese-ziguinchor' ),
		'autre'             => __( 'Autre', 'diocese-ziguinchor' ),
	);
}

/**
 * French label for a `pretre_fonction` choice (see acf-json/group_dz_cpt_pretre.json).
 *
 * @param string $fonction
 * @return string
 */
function dz_get_pretre_fonction_label( $fonction ) {
	$dz_labels = dz_get_pretre_fonction_labels();

	return isset( $dz_labels[ $fonction ] ) ? $dz_labels[ $fonction ] : '';
}

/**
 * CSS modifier class for the role badge on `template-parts/card-pretre.php`
 * (one colour per role, see DECISIONS.md "Refonte archive prêtres" —
 * `.pretre-badge--{fonction}` in assets/css/main.css). `$fonction` always
 * comes from the fixed ACF select above, so no sanitisation is needed
 * before using it verbatim in a class name.
 *
 * @param string $fonction
 * @return string
 */
function dz_get_pretre_fonction_badge_class( $fonction ) {
	return isset( dz_get_pretre_fonction_labels()[ $fonction ] ) ? 'pretre-badge--' . $fonction : 'pretre-badge--autre';
}

/**
 * Number of published priests per `pretre_fonction` value — powers the
 * "Nos prêtres" role pills, which only list roles actually in use
 * (see DECISIONS.md: a pill for a role with zero priests would be dead UI,
 * and the acceptance criteria explicitly ask that the filters "reflètent
 * les rôles réellement utilisés en back-office"). One aggregate query
 * rather than looping `dz_get_field()` over every priest, so this stays
 * cheap as the directory grows.
 *
 * @return array<string,int> fonction key => count
 */
function dz_get_pretre_fonction_counts() {
	global $wpdb;

	$dz_rows = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT pm.meta_value AS fonction, COUNT(*) AS total
			FROM {$wpdb->postmeta} pm
			INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
			WHERE pm.meta_key = %s
			AND p.post_type = %s
			AND p.post_status = 'publish'
			GROUP BY pm.meta_value",
			'pretre_fonction',
			'pretre'
		)
	);

	$dz_counts = array();
	foreach ( (array) $dz_rows as $dz_row ) {
		$dz_counts[ $dz_row->fonction ] = (int) $dz_row->total;
	}

	return $dz_counts;
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
