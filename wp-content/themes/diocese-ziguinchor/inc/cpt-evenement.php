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

/**
 * Restricts the evenement archive to upcoming/ongoing events (SPEC.md §4:
 * past events are filtered out of the query, never deleted), ordered
 * soonest-first. Does not affect wp-admin, where editors must still see
 * past events to manage them.
 */
function dz_evenement_archive_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( is_post_type_archive( 'evenement' ) ) {
		$dz_now = current_time( 'mysql' );

		$query->set(
			'meta_query',
			array(
				'relation' => 'OR',
				array(
					'key'     => 'evenement_date_fin',
					'value'   => $dz_now,
					'compare' => '>=',
					'type'    => 'DATETIME',
				),
				array(
					'relation' => 'AND',
					array(
						'key'     => 'evenement_date_fin',
						'compare' => 'NOT EXISTS',
					),
					array(
						'key'     => 'evenement_date_debut',
						'value'   => $dz_now,
						'compare' => '>=',
						'type'    => 'DATETIME',
					),
				),
			)
		);
		$query->set( 'meta_key', 'evenement_date_debut' );
		$query->set( 'orderby', 'meta_value' );
		$query->set( 'order', 'ASC' );
	}
}
add_action( 'pre_get_posts', 'dz_evenement_archive_query' );

/**
 * Where an evenement takes place: its linked paroisse takes precedence
 * over the free-text "lieu" field (see SPEC.md §3 — "lieu (texte libre ou
 * relation paroisse)").
 *
 * @param int $post_id
 * @return array{label:string,url:string}
 */
function dz_get_evenement_lieu( $post_id ) {
	$dz_paroisse_id = dz_get_field( 'evenement_paroisse', $post_id );
	if ( $dz_paroisse_id && get_post( $dz_paroisse_id ) ) {
		return array(
			'label' => get_the_title( $dz_paroisse_id ),
			'url'   => get_permalink( $dz_paroisse_id ),
		);
	}

	$dz_lieu_libre = dz_get_field( 'evenement_lieu', $post_id );

	return array(
		'label' => $dz_lieu_libre ? $dz_lieu_libre : '',
		'url'   => '',
	);
}

/**
 * Human-readable start/end date-time label for an evenement.
 *
 * @param int $post_id
 * @return string
 */
function dz_get_evenement_dates_label( $post_id ) {
	$dz_debut = dz_get_field( 'evenement_date_debut', $post_id );
	if ( ! $dz_debut ) {
		return '';
	}

	$dz_datetime_format = get_option( 'date_format' ) . ' ' . get_option( 'time_format' );
	$dz_debut_label     = date_i18n( $dz_datetime_format, strtotime( $dz_debut ) );

	$dz_fin = dz_get_field( 'evenement_date_fin', $post_id );
	if ( ! $dz_fin || $dz_fin === $dz_debut ) {
		return $dz_debut_label;
	}

	return sprintf(
		/* translators: 1: start date/time, 2: end date/time */
		__( '%1$s – %2$s', 'diocese-ziguinchor' ),
		$dz_debut_label,
		date_i18n( $dz_datetime_format, strtotime( $dz_fin ) )
	);
}

/**
 * Whether an evenement is upcoming, ongoing, or over.
 *
 * @param int $post_id
 * @return string 'a_venir'|'en_cours'|'termine'|'' (empty when no start date is set)
 */
function dz_get_evenement_status( $post_id ) {
	$dz_debut = dz_get_field( 'evenement_date_debut', $post_id );
	if ( ! $dz_debut ) {
		return '';
	}

	$dz_fin = dz_get_field( 'evenement_date_fin', $post_id, $dz_debut );
	$dz_now = current_time( 'mysql' );

	if ( $dz_now < $dz_debut ) {
		return 'a_venir';
	}

	if ( $dz_now > $dz_fin ) {
		return 'termine';
	}

	return 'en_cours';
}

/**
 * French label for a dz_get_evenement_status() value.
 *
 * @param string $status
 * @return string
 */
function dz_get_evenement_status_label( $status ) {
	$dz_labels = array(
		'a_venir' => __( 'À venir', 'diocese-ziguinchor' ),
		'en_cours' => __( 'En cours', 'diocese-ziguinchor' ),
		'termine' => __( 'Terminé', 'diocese-ziguinchor' ),
	);

	return isset( $dz_labels[ $status ] ) ? $dz_labels[ $status ] : '';
}
