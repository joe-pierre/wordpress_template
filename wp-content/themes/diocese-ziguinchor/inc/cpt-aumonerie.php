<?php
/**
 * Registers the aumonerie (chaplaincies) custom post type and its
 * type_aumonerie taxonomy.
 *
 * Same organisational socle as conseil/service_diocesain/commission_diocesaine
 * (PROMPT 13) — see SPEC.md §3 and DECISIONS.md ("Un Custom Post Type séparé
 * par grande rubrique organisationnelle"). Covers aumôneries scolaires,
 * universitaires, de santé et carcérales.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dz_register_cpt_aumonerie() {
	$labels = array(
		'name'                  => _x( 'Aumôneries', 'Post type general name', 'diocese-ziguinchor' ),
		'singular_name'         => _x( 'Aumônerie', 'Post type singular name', 'diocese-ziguinchor' ),
		'menu_name'             => _x( 'Aumôneries', 'Admin Menu text', 'diocese-ziguinchor' ),
		'name_admin_bar'        => _x( 'Aumônerie', 'Add New on Toolbar', 'diocese-ziguinchor' ),
		'add_new'               => __( 'Ajouter', 'diocese-ziguinchor' ),
		'add_new_item'          => __( 'Ajouter une aumônerie', 'diocese-ziguinchor' ),
		'edit_item'             => __( "Modifier l'aumônerie", 'diocese-ziguinchor' ),
		'new_item'              => __( 'Nouvelle aumônerie', 'diocese-ziguinchor' ),
		'view_item'             => __( "Voir l'aumônerie", 'diocese-ziguinchor' ),
		'view_items'            => __( 'Voir les aumôneries', 'diocese-ziguinchor' ),
		'search_items'          => __( 'Rechercher une aumônerie', 'diocese-ziguinchor' ),
		'not_found'             => __( 'Aucune aumônerie trouvée', 'diocese-ziguinchor' ),
		'not_found_in_trash'    => __( 'Aucune aumônerie dans la corbeille', 'diocese-ziguinchor' ),
		'all_items'             => __( 'Toutes les aumôneries', 'diocese-ziguinchor' ),
		'archives'              => __( 'Archives des aumôneries', 'diocese-ziguinchor' ),
		'attributes'            => __( "Attributs de l'aumônerie", 'diocese-ziguinchor' ),
		'insert_into_item'      => __( "Insérer dans l'aumônerie", 'diocese-ziguinchor' ),
		'uploaded_to_this_item' => __( 'Téléversé pour cette aumônerie', 'diocese-ziguinchor' ),
	);

	register_post_type(
		'aumonerie',
		array(
			'labels'        => $labels,
			'public'        => true,
			'has_archive'   => true,
			'show_in_rest'  => true,
			'menu_icon'     => 'dashicons-shield-alt',
			'menu_position' => 31,
			'supports'      => array( 'title', 'editor', 'thumbnail' ),
			'rewrite'       => array( 'slug' => 'aumonerie' ),
		)
	);
}
add_action( 'init', 'dz_register_cpt_aumonerie' );

/**
 * type_aumonerie taxonomy — fixed classification (scolaire/universitaire/
 * santé/carcérale), not a free folksonomy, hence hierarchical (checkbox UI,
 * same admin pattern as native categories) rather than tag-style free text.
 *
 * No dedicated taxonomy archive route (`rewrite => false`): filtering happens
 * on archive-aumonerie.php itself via `?type_aumonerie=<slug>` + the
 * dz_aumonerie_archive_query() pre_get_posts filter below, so every
 * aumonerie listing — filtered or not — renders through the same template
 * and the same .organisation-card grid (see PROMPT 14). A term archive route
 * would otherwise fall back to the generic archive.php (blog card style),
 * inconsistent with that requirement.
 */
function dz_register_taxonomy_type_aumonerie() {
	$labels = array(
		'name'              => _x( "Types d'aumônerie", 'Taxonomy general name', 'diocese-ziguinchor' ),
		'singular_name'     => _x( "Type d'aumônerie", 'Taxonomy singular name', 'diocese-ziguinchor' ),
		'search_items'      => __( 'Rechercher un type', 'diocese-ziguinchor' ),
		'all_items'         => __( 'Tous les types', 'diocese-ziguinchor' ),
		'edit_item'         => __( 'Modifier le type', 'diocese-ziguinchor' ),
		'update_item'       => __( 'Mettre à jour le type', 'diocese-ziguinchor' ),
		'add_new_item'      => __( 'Ajouter un type', 'diocese-ziguinchor' ),
		'new_item_name'     => __( 'Nom du nouveau type', 'diocese-ziguinchor' ),
		'menu_name'         => __( "Types d'aumônerie", 'diocese-ziguinchor' ),
	);

	register_taxonomy(
		'type_aumonerie',
		array( 'aumonerie' ),
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
add_action( 'init', 'dz_register_taxonomy_type_aumonerie' );

/**
 * Seeds the 4 fixed type_aumonerie terms so editors only ever pick among
 * them (scolaire/universitaire/santé/carcérale — see SPEC.md §3), instead of
 * starting from an empty taxonomy. Idempotent: skips terms that already exist.
 */
function dz_seed_type_aumonerie_terms() {
	$dz_terms = array(
		'scolaire'      => __( 'Scolaire', 'diocese-ziguinchor' ),
		'universitaire' => __( 'Universitaire', 'diocese-ziguinchor' ),
		'sante'         => __( 'Santé', 'diocese-ziguinchor' ),
		'carcerale'     => __( 'Carcérale', 'diocese-ziguinchor' ),
	);

	foreach ( $dz_terms as $dz_slug => $dz_name ) {
		if ( ! term_exists( $dz_slug, 'type_aumonerie' ) ) {
			wp_insert_term( $dz_name, 'type_aumonerie', array( 'slug' => $dz_slug ) );
		}
	}
}
add_action( 'init', 'dz_seed_type_aumonerie_terms', 11 );

/**
 * Filters the aumonerie archive to a single type_aumonerie term when
 * `?type_aumonerie=<slug>` is present (see archive-aumonerie.php filter
 * tabs). Sanitized at the entry per CONVENTIONS.md; silently ignored if the
 * slug does not match a real term.
 */
function dz_aumonerie_archive_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( ! is_post_type_archive( 'aumonerie' ) || empty( $_GET['type_aumonerie'] ) ) {
		return;
	}

	$dz_type_aumonerie = sanitize_title( wp_unslash( $_GET['type_aumonerie'] ) );

	$query->set(
		'tax_query',
		array(
			array(
				'taxonomy' => 'type_aumonerie',
				'field'    => 'slug',
				'terms'    => $dz_type_aumonerie,
			),
		)
	);
}
add_action( 'pre_get_posts', 'dz_aumonerie_archive_query' );
