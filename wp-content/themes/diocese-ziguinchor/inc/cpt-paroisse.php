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
 * doyenne taxonomy (PROMPT 16, SPEC.md §3 "Doyennés et Paroisses") —
 * replaces the earlier `secteur_pastoral` idea, see DECISIONS.md. Open list
 * (unlike `type_aumonerie`/`evenement_type`, no fixed set of values is
 * documented anywhere): deanery names are diocese-specific real-world data,
 * so editors create the terms themselves through the standard hierarchical
 * (category-style) admin UI, nothing pre-seeded here.
 *
 * `rewrite => false`, same policy already applied to `type_aumonerie`/
 * `evenement_type`: no `taxonomy-doyenne.php` template exists in this theme,
 * so a real archive route would silently fall back to the generic blog
 * archive.php instead of the paroisse card grid — no such route is
 * requested here, so none is created.
 */
function dz_register_taxonomy_doyenne() {
	$labels = array(
		'name'          => _x( 'Doyennés', 'Taxonomy general name', 'diocese-ziguinchor' ),
		'singular_name' => _x( 'Doyenné', 'Taxonomy singular name', 'diocese-ziguinchor' ),
		'search_items'  => __( 'Rechercher un doyenné', 'diocese-ziguinchor' ),
		'all_items'     => __( 'Tous les doyennés', 'diocese-ziguinchor' ),
		'edit_item'     => __( 'Modifier le doyenné', 'diocese-ziguinchor' ),
		'update_item'   => __( 'Mettre à jour le doyenné', 'diocese-ziguinchor' ),
		'add_new_item'  => __( 'Ajouter un doyenné', 'diocese-ziguinchor' ),
		'new_item_name' => __( 'Nom du nouveau doyenné', 'diocese-ziguinchor' ),
		'menu_name'     => __( 'Doyennés', 'diocese-ziguinchor' ),
	);

	register_taxonomy(
		'doyenne',
		array( 'paroisse' ),
		array(
			'labels'            => $labels,
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => false,
		)
	);
}
add_action( 'init', 'dz_register_taxonomy_doyenne' );

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

/**
 * Days of the week, Monday-first, matching `field_dz_paroisse_horaire_jour`
 * choices and ISO-8601 day numbers (`current_time( 'N' )`: 1 = Monday ... 7 =
 * Sunday) — the single source of truth for both `single-paroisse.php` (mass
 * schedule table) and `dz_get_paroisse_next_mass()` below, so the two never
 * drift out of sync.
 *
 * @return array<string,string> jour key => libellé
 */
function dz_get_jours_semaine() {
	return array(
		'lundi'    => __( 'Lundi', 'diocese-ziguinchor' ),
		'mardi'    => __( 'Mardi', 'diocese-ziguinchor' ),
		'mercredi' => __( 'Mercredi', 'diocese-ziguinchor' ),
		'jeudi'    => __( 'Jeudi', 'diocese-ziguinchor' ),
		'vendredi' => __( 'Vendredi', 'diocese-ziguinchor' ),
		'samedi'   => __( 'Samedi', 'diocese-ziguinchor' ),
		'dimanche' => __( 'Dimanche', 'diocese-ziguinchor' ),
	);
}

/**
 * Mass schedule of a paroisse, grouped by day and sorted chronologically
 * within each day (see DECISIONS.md "Regroupement des horaires de messes par
 * jour"). Each entry also carries its optional event label (`ADORATION
 * 17:00`, `CHEMIN DE CROIX`...) for the badge shown next to the time.
 *
 * @param int $paroisse_id
 * @return array<string,array<int,array{heure:string,libelle:string}>> jour key => horaires, in $dz_jours order, only non-empty days present
 */
function dz_get_paroisse_horaires_par_jour( $paroisse_id ) {
	$dz_par_jour = array();

	if ( ! function_exists( 'have_rows' ) || ! have_rows( 'paroisse_horaires_messes', $paroisse_id ) ) {
		return $dz_par_jour;
	}

	while ( have_rows( 'paroisse_horaires_messes', $paroisse_id ) ) {
		the_row();
		$dz_jour    = get_sub_field( 'paroisse_horaire_jour' );
		$dz_heure   = get_sub_field( 'paroisse_horaire_heure' );
		$dz_libelle = get_sub_field( 'paroisse_horaire_libelle' );

		if ( ! $dz_jour || ! $dz_heure ) {
			continue;
		}

		$dz_par_jour[ $dz_jour ][] = array(
			'heure'   => $dz_heure,
			'libelle' => $dz_libelle ? $dz_libelle : '',
		);
	}

	foreach ( $dz_par_jour as $dz_jour => $dz_horaires ) {
		usort(
			$dz_horaires,
			function ( $dz_a, $dz_b ) {
				return strcmp( $dz_a['heure'], $dz_b['heure'] );
			}
		);
		$dz_par_jour[ $dz_jour ] = $dz_horaires;
	}

	// Re-key in canonical Lundi->Dimanche order, independent of entry order.
	$dz_ordered = array();
	foreach ( array_keys( dz_get_jours_semaine() ) as $dz_jour_key ) {
		if ( ! empty( $dz_par_jour[ $dz_jour_key ] ) ) {
			$dz_ordered[ $dz_jour_key ] = $dz_par_jour[ $dz_jour_key ];
		}
	}

	return $dz_ordered;
}

/**
 * Next upcoming mass from now (site timezone, see `current_time()`), looking
 * across the current day first (only times still ahead) then, if needed,
 * wrapping forward through the rest of the week and back to the start —
 * used by the utility bar ("Prochaine messe : dimanche 07:00").
 *
 * @param int $paroisse_id
 * @return array{jour_key:string,jour_label:string,heure:string}|null Null when the paroisse has no schedule at all.
 */
function dz_get_paroisse_next_mass( $paroisse_id ) {
	$dz_par_jour = dz_get_paroisse_horaires_par_jour( $paroisse_id );

	if ( ! $dz_par_jour ) {
		return null;
	}

	$dz_jours       = dz_get_jours_semaine();
	$dz_jour_keys   = array_keys( $dz_jours );
	$dz_today_index = (int) current_time( 'N' ) - 1; // 0-based, Monday = 0
	$dz_now_hi      = current_time( 'H:i' );

	for ( $dz_offset = 0; $dz_offset < 7; $dz_offset++ ) {
		$dz_jour_key = $dz_jour_keys[ ( $dz_today_index + $dz_offset ) % 7 ];

		if ( empty( $dz_par_jour[ $dz_jour_key ] ) ) {
			continue;
		}

		foreach ( $dz_par_jour[ $dz_jour_key ] as $dz_horaire ) {
			if ( 0 === $dz_offset && $dz_horaire['heure'] < $dz_now_hi ) {
				continue; // Today, but this mass already happened.
			}

			return array(
				'jour_key'   => $dz_jour_key,
				'jour_label' => $dz_jours[ $dz_jour_key ],
				'heure'      => $dz_horaire['heure'],
			);
		}
	}

	return null;
}

/**
 * Contact phone shown for a paroisse (utility bar, "Nous trouver" — see
 * DECISIONS.md "Ajustements typographiques round 3") : the curé's own
 * phone takes priority when he has one on file, falling back to the
 * paroisse's own `paroisse_telephone` field otherwise (e.g. no curé
 * assigned yet, or he has no phone on file). Keeps the utility bar and
 * "Nous trouver" in sync — both call this, never `paroisse_telephone`
 * directly, so the same number never shows differently in two places on
 * the same page.
 *
 * @param int $paroisse_id
 * @return string Empty string if neither source has a phone.
 */
function dz_get_paroisse_contact_phone( $paroisse_id ) {
	$dz_cure = dz_get_paroisse_clergy( $paroisse_id, 'cure' );
	if ( $dz_cure ) {
		$dz_phone = dz_get_field( 'pretre_telephone', $dz_cure[0]->ID );
		if ( $dz_phone ) {
			return $dz_phone;
		}
	}

	return (string) dz_get_field( 'paroisse_telephone', $paroisse_id, '' );
}

/**
 * Contact e-mail shown for a paroisse ("Nous trouver") — same priority
 * order as dz_get_paroisse_contact_phone() above.
 *
 * @param int $paroisse_id
 * @return string Empty string if neither source has an e-mail.
 */
function dz_get_paroisse_contact_email( $paroisse_id ) {
	$dz_cure = dz_get_paroisse_clergy( $paroisse_id, 'cure' );
	if ( $dz_cure ) {
		$dz_email = dz_get_field( 'pretre_email', $dz_cure[0]->ID );
		if ( $dz_email ) {
			return $dz_email;
		}
	}

	return (string) dz_get_field( 'paroisse_email', $paroisse_id, '' );
}
