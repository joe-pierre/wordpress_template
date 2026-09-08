<?php
/**
 * "Réglages > Import contenu diocèse" — one-shot admin import tool.
 *
 * Reusable import mechanism (CONTENT_PROMPTS.md PROMPT 0), built before any
 * of the content-specific import logic (PROMPTs 1-5): reads the structured
 * PHP data already transcribed in inc/import/data-*.php (no PDF parsing at
 * runtime, no logic in those files — see each one) and creates the matching
 * WordPress content through standard functions (wp_insert_post(),
 * update_field()), one button per source, each guarded by its own nonce and
 * `manage_options` capability check like the rest of the theme's admin
 * surface (see inc/acf-fields.php's options page).
 *
 * Idempotence is mandatory (CONTENT_PROMPTS.md PROMPT 0, point 3): every
 * import function must check dz_import_find_existing_post() before ever
 * calling wp_insert_post(), so clicking a button again — or re-running the
 * whole import after fixing a data file — never duplicates content. See
 * DECISIONS.md "Mécanisme d'import de contenu réutilisable" for the full
 * reasoning.
 *
 * Not a permanent theme feature in spirit — it reads one-off source
 * documents that won't recur every year in the same shape — but CONTENT_
 * PROMPTS.md PROMPT 6 explicitly keeps it in place after the real import
 * (idempotence makes that safe) rather than removing it, in case a future
 * nominations circular needs the same treatment.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once DZ_THEME_DIR . '/inc/import/data-hero.php';
require_once DZ_THEME_DIR . '/inc/import/data-calendrier.php';
require_once DZ_THEME_DIR . '/inc/import/data-nominations.php';
require_once DZ_THEME_DIR . '/inc/import/data-aumoneries.php';
require_once DZ_THEME_DIR . '/inc/import/data-armoiries.php';

/**
 * Data source key => admin button label + dispatcher callback. The single
 * place that ties a button on the admin page to the function it runs.
 *
 * @return array<string,array{label:string,callback:callable}>
 */
function dz_import_get_sources() {
	return array(
		'logo'        => array(
			'label'    => __( 'Importer le logo et générer le favicon', 'diocese-ziguinchor' ),
			'callback' => 'dz_import_run_logo',
		),
		'hero'        => array(
			'label'    => __( 'Importer le hero', 'diocese-ziguinchor' ),
			'callback' => 'dz_import_run_hero',
		),
		'calendrier'  => array(
			'label'    => __( 'Importer le calendrier', 'diocese-ziguinchor' ),
			'callback' => 'dz_import_run_calendrier',
		),
		'nominations' => array(
			'label'    => __( 'Importer les nominations', 'diocese-ziguinchor' ),
			'callback' => 'dz_import_run_nominations',
		),
		'aumoneries'  => array(
			'label'    => __( 'Importer les aumôneries et enseignements', 'diocese-ziguinchor' ),
			'callback' => 'dz_import_run_aumoneries',
		),
		'armoiries'   => array(
			'label'    => __( 'Importer le contenu "À propos" et les armoiries', 'diocese-ziguinchor' ),
			'callback' => 'dz_import_run_armoiries',
		),
	);
}

function dz_import_register_admin_page() {
	add_options_page(
		__( 'Import contenu diocèse', 'diocese-ziguinchor' ),
		__( 'Import contenu diocèse', 'diocese-ziguinchor' ),
		'manage_options',
		'dz-import-contenu',
		'dz_import_render_admin_page'
	);
}
add_action( 'admin_menu', 'dz_import_register_admin_page' );

/**
 * Handles a button submission (runs the matching import, stores its result
 * for display, then redirects back to the tool page — POST-redirect-GET so
 * refreshing the result page never resubmits the form). Hooked on
 * `admin_init` rather than called from the render callback: a redirect
 * needs to happen before any output is sent.
 */
function dz_import_handle_request() {
	if ( empty( $_POST['dz_import_source'] ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$dz_source_key = sanitize_key( wp_unslash( $_POST['dz_import_source'] ) );
	$dz_sources    = dz_import_get_sources();

	if ( ! isset( $dz_sources[ $dz_source_key ] ) ) {
		return;
	}

	check_admin_referer( 'dz_import_' . $dz_source_key, 'dz_import_nonce' );

	$dz_results = call_user_func( $dz_sources[ $dz_source_key ]['callback'] );

	set_transient( 'dz_import_results_' . get_current_user_id(), $dz_results, MINUTE_IN_SECONDS );

	wp_safe_redirect(
		add_query_arg(
			array(
				'page'           => 'dz-import-contenu',
				'dz_import_done' => $dz_source_key,
			),
			admin_url( 'options-general.php' )
		)
	);
	exit;
}
add_action( 'admin_init', 'dz_import_handle_request' );

/**
 * Renders the tool page: a result notice (after a redirect back from a
 * submission) followed by one button per registered source.
 */
function dz_import_render_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$dz_results = null;
	if ( ! empty( $_GET['dz_import_done'] ) ) {
		$dz_transient_key = 'dz_import_results_' . get_current_user_id();
		$dz_results        = get_transient( $dz_transient_key );
		delete_transient( $dz_transient_key );
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Import contenu diocèse', 'diocese-ziguinchor' ); ?></h1>
		<p>
			<?php esc_html_e( "Outil d'import ponctuel : lit les données déjà transcrites dans inc/import/data-*.php et crée le contenu WordPress correspondant. Chaque bouton peut être cliqué plusieurs fois sans risque : le contenu déjà importé n'est jamais dupliqué — selon la source, une entrée déjà importée est soit laissée telle quelle, soit mise à jour à partir des dernières données transcrites (voir la note de chaque fonction d'import dans inc/import/import-tools.php).", 'diocese-ziguinchor' ); ?>
		</p>

		<?php if ( is_array( $dz_results ) ) : ?>
			<div class="notice notice-info">
				<p>
					<?php
					echo esc_html(
						sprintf(
							/* translators: 1: number of items created, 2: number of items updated, 3: number of items left untouched (already up to date) */
							__( '%1$d élément(s) créé(s), %2$d mis à jour, %3$d laissé(s) tel(s) quel(s).', 'diocese-ziguinchor' ),
							(int) ( $dz_results['created'] ?? 0 ),
							(int) ( $dz_results['updated'] ?? 0 ),
							(int) ( $dz_results['skipped'] ?? 0 )
						)
					);
					?>
				</p>
				<?php if ( ! empty( $dz_results['notes'] ) ) : ?>
					<ul>
						<?php foreach ( $dz_results['notes'] as $dz_note ) : ?>
							<li><?php echo esc_html( $dz_note ); ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php foreach ( dz_import_get_sources() as $dz_source_key => $dz_source ) : ?>
			<form method="post" style="margin-bottom: 0.75em;">
				<?php wp_nonce_field( 'dz_import_' . $dz_source_key, 'dz_import_nonce' ); ?>
				<input type="hidden" name="dz_import_source" value="<?php echo esc_attr( $dz_source_key ); ?>">
				<?php submit_button( $dz_source['label'], 'secondary', 'submit', false ); ?>
			</form>
		<?php endforeach; ?>
	</div>
	<?php
}

/**
 * Marks a piece of imported content with its stable source id — the single
 * source of truth dz_import_find_existing_post() checks against. Call this
 * immediately after every wp_insert_post() an import function performs.
 *
 * @param int    $post_id
 * @param string $source_id
 */
function dz_import_mark_imported( $post_id, $source_id ) {
	update_post_meta( $post_id, '_dz_import_source_id', sanitize_key( $source_id ) );
}

/**
 * Whether a post already exists for a given source id within a given post
 * type — the idempotence check every import function must run before ever
 * calling wp_insert_post() (CONTENT_PROMPTS.md PROMPT 0, point 3).
 * `post_status => any` so a manually-trashed or drafted import is still
 * found and not recreated.
 *
 * @param string $post_type
 * @param string $source_id
 * @return int 0 if no matching post exists, otherwise its ID.
 */
function dz_import_find_existing_post( $post_type, $source_id ) {
	$dz_existing = get_posts(
		array(
			'post_type'      => $post_type,
			'post_status'    => 'any',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => '_dz_import_source_id',
			'meta_value'     => sanitize_key( $source_id ),
		)
	);

	return $dz_existing ? (int) $dz_existing[0] : 0;
}

/**
 * Sideloads a local file already sitting on disk (not an upload/$_FILES
 * entry) into the media library as a real attachment, with generated
 * metadata. media_handle_sideload()/wp_handle_sideload() COPY then DELETE
 * whatever `tmp_name` they're given (that's correct for a genuine temp
 * upload, but would destroy our permanent theme-bundled source file if we
 * pointed `tmp_name` at it directly) — so this first copies the source into
 * a real temp file via wp_tempnam() and only ever sideloads that copy.
 *
 * @param string $file_path   Absolute path to the source file.
 * @param int    $post_id     Post to attach the media to.
 * @param string $description Attachment title/description.
 * @return int|WP_Error Attachment ID, or a WP_Error on failure.
 */
function dz_import_sideload_image( $file_path, $post_id, $description ) {
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$dz_tmp_file = wp_tempnam( basename( $file_path ) );

	if ( ! copy( $file_path, $dz_tmp_file ) ) {
		return new WP_Error(
			'dz_import_copy_failed',
			sprintf(
				/* translators: %s: source file path */
				__( 'Impossible de copier %s vers un fichier temporaire.', 'diocese-ziguinchor' ),
				$file_path
			)
		);
	}

	$dz_attachment_id = media_handle_sideload(
		array(
			'name'     => basename( $file_path ),
			'tmp_name' => $dz_tmp_file,
		),
		$post_id,
		$description
	);

	if ( is_wp_error( $dz_attachment_id ) && file_exists( $dz_tmp_file ) ) {
		unlink( $dz_tmp_file );
	}

	return $dz_attachment_id;
}

/**
 * Imports the front-page hero slides: sideloads each image into the media
 * library, then appends a row to the `dz_front_hero_slides` repeater
 * (group_dz_front_hero — see inc/acf-fields.php) on the static front page.
 * Idempotence per slide (not per post — this isn't a CPT, see
 * inc/import/data-hero.php): each repeater row carries the source slide's
 * `source_id` in `dz_front_hero_slide_source_id`, checked against the
 * repeater's existing rows before sideloading anything.
 *
 * @return array{created:int,skipped:int,notes:string[]}
 */
function dz_import_run_hero() {
	$dz_slides = dz_import_get_hero_data();

	if ( ! $dz_slides ) {
		return array(
			'created' => 0,
			'skipped' => 0,
			'notes'   => array( __( 'Aucune donnée dans inc/import/data-hero.php pour le moment (voir CONTENT_PROMPTS.md PROMPT 1).', 'diocese-ziguinchor' ) ),
		);
	}

	if ( 'page' !== get_option( 'show_on_front' ) || ! get_option( 'page_on_front' ) ) {
		return array(
			'created' => 0,
			'skipped' => 0,
			'notes'   => array( __( "Aucune page d'accueil statique n'est configurée (Réglages > Lecture) : impossible d'importer le hero tant que group_dz_front_hero n'a pas de page à laquelle s'attacher.", 'diocese-ziguinchor' ) ),
		);
	}

	$dz_front_page_id = (int) get_option( 'page_on_front' );
	$dz_rows           = dz_get_field( 'dz_front_hero_slides', $dz_front_page_id, array() );
	$dz_existing_ids   = wp_list_pluck( $dz_rows, 'dz_front_hero_slide_source_id' );

	$dz_created = 0;
	$dz_skipped = 0;
	$dz_notes   = array();

	foreach ( $dz_slides as $dz_slide ) {
		if ( in_array( $dz_slide['source_id'], $dz_existing_ids, true ) ) {
			++$dz_skipped;
			continue;
		}

		$dz_image_path = DZ_THEME_DIR . '/' . ltrim( $dz_slide['image'], '/' );

		if ( ! file_exists( $dz_image_path ) ) {
			$dz_notes[] = sprintf(
				/* translators: %s: image file path */
				__( 'Image introuvable, slide ignoré : %s', 'diocese-ziguinchor' ),
				$dz_slide['image']
			);
			continue;
		}

		$dz_attachment_id = dz_import_sideload_image( $dz_image_path, $dz_front_page_id, $dz_slide['titre'] );

		if ( is_wp_error( $dz_attachment_id ) ) {
			$dz_notes[] = sprintf(
				/* translators: 1: image file path, 2: error message */
				__( "Échec de l'import de l'image %1\$s : %2\$s", 'diocese-ziguinchor' ),
				$dz_slide['image'],
				$dz_attachment_id->get_error_message()
			);
			continue;
		}

		// hero-slider.php already outputs its own alt from the slide's
		// "titre" field, not this — set for the attachment's other uses
		// (media library, if reused elsewhere) per CONTENT_PROMPTS.md's
		// "vrai attachment WordPress, avec métadonnées".
		update_post_meta( $dz_attachment_id, '_wp_attachment_image_alt', $dz_slide['titre'] );

		$dz_rows[] = array(
			'dz_front_hero_slide_image'     => $dz_attachment_id,
			'dz_front_hero_slide_titre'     => $dz_slide['titre'],
			'dz_front_hero_slide_texte'     => $dz_slide['texte'],
			'dz_front_hero_slide_lien'      => $dz_slide['lien'],
			'dz_front_hero_slide_source_id' => $dz_slide['source_id'],
		);

		++$dz_created;
	}

	if ( $dz_created > 0 ) {
		update_field( 'dz_front_hero_slides', $dz_rows, $dz_front_page_id );
	}

	return array(
		'created' => $dz_created,
		'skipped' => $dz_skipped,
		'notes'   => $dz_notes,
	);
}

/**
 * Finds an existing `paroisse` post whose title matches (case/whitespace
 * insensitive — PDF transcriptions and wp-admin entries won't always agree
 * on casing) the given lieu text, for dz_import_run_calendrier()'s
 * "evenement_paroisse relation when the lieu names a known parish, free
 * text otherwise" rule (CONTENT_PROMPTS.md PROMPT 2, point 4).
 *
 * @param string $title
 * @return int 0 if no paroisse matches, otherwise its post ID.
 */
function dz_import_find_paroisse_by_title( $title ) {
	$dz_needle = mb_strtolower( trim( $title ) );

	if ( '' === $dz_needle ) {
		return 0;
	}

	$dz_paroisses = get_posts(
		array(
			'post_type'      => 'paroisse',
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'fields'         => 'all',
		)
	);

	foreach ( $dz_paroisses as $dz_paroisse ) {
		if ( mb_strtolower( trim( $dz_paroisse->post_title ) ) === $dz_needle ) {
			return (int) $dz_paroisse->ID;
		}
	}

	return 0;
}

/**
 * Imports the diocesan calendar as `evenement` posts (one per
 * inc/import/data-calendrier.php entry). `evenement_type` defaults to
 * "diocesain" unless the entry explicitly says otherwise (CONTENT_PROMPTS.md
 * PROMPT 2, point 3 — e.g. the bishop's own ordination anniversary).
 *
 * Past events are created exactly like upcoming ones (`post_status =>
 * publish`, no special-casing): dz_evenement_archive_query()
 * (inc/cpt-evenement.php) already excludes past events from the public
 * archive on its own via a date meta_query, but only for the front-end
 * query (`is_admin()` guard) — an imported past event stays fully visible
 * in wp-admin, satisfying PROMPT 2 point 5 without any extra code here.
 *
 * @return array{created:int,skipped:int,notes:string[]}
 */
function dz_import_run_calendrier() {
	$dz_entries = dz_import_get_calendrier_data();

	if ( ! $dz_entries ) {
		return array(
			'created' => 0,
			'skipped' => 0,
			'notes'   => array( __( 'Aucune donnée dans inc/import/data-calendrier.php pour le moment (voir CONTENT_PROMPTS.md PROMPT 2).', 'diocese-ziguinchor' ) ),
		);
	}

	$dz_created = 0;
	$dz_skipped = 0;
	$dz_notes   = array();

	foreach ( $dz_entries as $dz_entry ) {
		if ( dz_import_find_existing_post( 'evenement', $dz_entry['source_id'] ) ) {
			++$dz_skipped;
			continue;
		}

		$dz_post_id = wp_insert_post(
			array(
				'post_type'   => 'evenement',
				'post_title'  => wp_strip_all_tags( $dz_entry['titre'] ),
				'post_status' => 'publish',
			),
			true
		);

		if ( is_wp_error( $dz_post_id ) ) {
			$dz_notes[] = sprintf(
				/* translators: 1: activity title, 2: error message */
				__( "Échec de la création de l'événement « %1\$s » : %2\$s", 'diocese-ziguinchor' ),
				$dz_entry['titre'],
				$dz_post_id->get_error_message()
			);
			continue;
		}

		dz_import_mark_imported( $dz_post_id, $dz_entry['source_id'] );

		update_field( 'evenement_date_debut', $dz_entry['date_debut'], $dz_post_id );
		update_field( 'evenement_date_fin', ! empty( $dz_entry['date_fin'] ) ? $dz_entry['date_fin'] : $dz_entry['date_debut'], $dz_post_id );

		$dz_paroisse_id = dz_import_find_paroisse_by_title( $dz_entry['lieu'] );
		if ( $dz_paroisse_id ) {
			update_field( 'evenement_paroisse', $dz_paroisse_id, $dz_post_id );
		} else {
			update_field( 'evenement_lieu', $dz_entry['lieu'], $dz_post_id );
		}

		$dz_type = ! empty( $dz_entry['evenement_type'] ) ? $dz_entry['evenement_type'] : 'diocesain';
		wp_set_object_terms( $dz_post_id, $dz_type, 'evenement_type' );

		++$dz_created;
	}

	return array(
		'created' => $dz_created,
		'skipped' => $dz_skipped,
		'notes'   => $dz_notes,
	);
}

/**
 * Finds an existing `pretre` post whose title matches (case/whitespace
 * insensitive, same reasoning as dz_import_find_paroisse_by_title()) the
 * given name, for the `mouvement` "aumonier" relation (CONTENT_PROMPTS.md
 * PROMPT 3, point 3).
 *
 * @param string $name
 * @return int 0 if no pretre matches, otherwise its post ID.
 */
function dz_import_find_pretre_by_title( $name ) {
	$dz_needle = mb_strtolower( trim( $name ) );

	if ( '' === $dz_needle ) {
		return 0;
	}

	$dz_pretres = get_posts(
		array(
			'post_type'      => 'pretre',
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'fields'         => 'all',
		)
	);

	foreach ( $dz_pretres as $dz_pretre ) {
		if ( mb_strtolower( trim( $dz_pretre->post_title ) ) === $dz_needle ) {
			return (int) $dz_pretre->ID;
		}
	}

	return 0;
}

/**
 * Sets the shared organisational socle fields (`org_responsable`,
 * `org_membres` — group_dz_cpt_organisation_socle, common to all 8
 * organisational CPTs) from an entry, if present. Shared by all four
 * dz_import_run_nominations_*() functions below rather than repeated in
 * each.
 *
 * @param int   $post_id
 * @param array $entry
 */
function dz_import_apply_org_socle( $post_id, array $entry ) {
	if ( ! empty( $entry['responsable'] ) ) {
		update_field( 'org_responsable', $entry['responsable'], $post_id );
	}

	if ( ! empty( $entry['membres'] ) ) {
		$dz_rows = array();
		foreach ( $entry['membres'] as $dz_membre ) {
			$dz_rows[] = array(
				'org_membre_nom'  => $dz_membre['nom'],
				'org_membre_role' => $dz_membre['role'],
			);
		}
		update_field( 'org_membres', $dz_rows, $post_id );
	}
}

/**
 * Creates OR updates an organisational post from a nominations entry
 * (title, content note, org socle), shared by all four per-CPT import
 * functions below. **Upsert, not skip-once**, unlike
 * dz_import_find_existing_post()'s other callers (hero/calendrier):
 * CONTENT_PROMPTS.md PROMPT 3bis explicitly asks that re-running the import
 * after transcribing more of the circular UPDATES the 33 entities already
 * created (empty) at PROMPT 3, rather than leaving them untouched — see
 * DECISIONS.md. `dz_import_find_existing_post()` itself is unchanged (still
 * a pure "does a post with this source_id exist" lookup); only how this
 * caller uses that answer differs.
 *
 * @param string   $post_type
 * @param array    $entry
 * @param string[] $dz_notes By reference; a creation failure is appended here.
 * @return array{0:int,1:bool} [post ID (0 on failure), true if newly created / false if it already existed and was updated]
 */
function dz_import_upsert_organisation_post( $post_type, array $entry, array &$dz_notes ) {
	$dz_post_id = dz_import_find_existing_post( $post_type, $entry['source_id'] );
	$dz_is_new  = ! $dz_post_id;

	if ( $dz_is_new ) {
		$dz_post_id = wp_insert_post(
			array(
				'post_type'   => $post_type,
				'post_title'  => wp_strip_all_tags( $entry['titre'] ),
				// Brouillon : tant que responsable/membres ne sont pas
				// confirmés à 100%, pas de contenu publié publiquement
				// (SPEC.md §9) — un rédacteur publie lui-même une fois relu.
				'post_status' => 'draft',
			),
			true
		);

		if ( is_wp_error( $dz_post_id ) ) {
			$dz_notes[] = sprintf(
				/* translators: 1: entity title, 2: error message */
				__( "Échec de la création de « %1\$s » : %2\$s", 'diocese-ziguinchor' ),
				$entry['titre'],
				$dz_post_id->get_error_message()
			);
			return array( 0, false );
		}

		dz_import_mark_imported( $dz_post_id, $entry['source_id'] );
	}

	// Applied whether new or pre-existing: this is the "reimport updates"
	// behavior. Title stays in sync too (a source name can be corrected
	// between two transcription passes, e.g. PROMPT 3's "Économat" vs.
	// PROMPT 3bis's "Économat Diocésain").
	wp_update_post(
		array(
			'ID'           => $dz_post_id,
			'post_title'   => wp_strip_all_tags( $entry['titre'] ),
			'post_content' => ! empty( $entry['note'] ) ? wp_kses_post( $entry['note'] ) : '',
		)
	);

	dz_import_apply_org_socle( $dz_post_id, $entry );

	return array( $dz_post_id, $dz_is_new );
}

/**
 * PROMPT 3, section I: service_diocesain. Économat's `sous_structures`
 * (attached committees, not separate posts — see SPEC.md §3) are
 * (re)applied here on every run, same upsert logic as the rest of the entry.
 *
 * @param array $dz_entries
 * @return array{created:int,updated:int,skipped:int,notes:string[]}
 */
function dz_import_run_nominations_service_diocesain( array $dz_entries ) {
	$dz_created = 0;
	$dz_updated = 0;
	$dz_notes   = array();

	foreach ( $dz_entries as $dz_entry ) {
		list( $dz_post_id, $dz_is_new ) = dz_import_upsert_organisation_post( 'service_diocesain', $dz_entry, $dz_notes );
		if ( ! $dz_post_id ) {
			continue;
		}

		if ( ! empty( $dz_entry['sous_structures'] ) ) {
			$dz_rows = array();
			foreach ( $dz_entry['sous_structures'] as $dz_sous_structure ) {
				$dz_rows[] = array(
					'service_diocesain_sous_structure_nom'         => $dz_sous_structure['nom'],
					'service_diocesain_sous_structure_responsable' => $dz_sous_structure['responsable'],
				);
			}
			update_field( 'service_diocesain_sous_structures', $dz_rows, $dz_post_id );
		}

		$dz_is_new ? ++$dz_created : ++$dz_updated;
	}

	return array(
		'created' => $dz_created,
		'updated' => $dz_updated,
		'skipped' => 0,
		'notes'   => $dz_notes,
	);
}

/**
 * PROMPT 3, section II: commission_diocesaine — no field specific to this
 * CPT, just the shared organisational socle.
 *
 * @param array $dz_entries
 * @return array{created:int,updated:int,skipped:int,notes:string[]}
 */
function dz_import_run_nominations_commission_diocesaine( array $dz_entries ) {
	$dz_created = 0;
	$dz_updated = 0;
	$dz_notes   = array();

	foreach ( $dz_entries as $dz_entry ) {
		list( $dz_post_id, $dz_is_new ) = dz_import_upsert_organisation_post( 'commission_diocesaine', $dz_entry, $dz_notes );
		if ( ! $dz_post_id ) {
			continue;
		}

		$dz_is_new ? ++$dz_created : ++$dz_updated;
	}

	return array(
		'created' => $dz_created,
		'updated' => $dz_updated,
		'skipped' => 0,
		'notes'   => $dz_notes,
	);
}

/**
 * PROMPT 3/3bis, section III: mouvement. `aumonier` is matched against
 * existing `pretre` titles when the entry names one: if a matching pretre
 * exists, `mouvement_aumonier` (a post_object relation, see
 * acf-json/group_dz_cpt_mouvement.json) is set to it. If the entry names
 * someone but no matching pretre fiche exists yet, the relation is left
 * unset (there is no free-text fallback field on this CPT — flagged in
 * BUGS_AND_ROADMAP.md) but the full name is folded into the entry's `note`
 * before it reaches dz_import_upsert_organisation_post(), so it lands in
 * the post's own content rather than only in the transient admin notice
 * (PROMPT 3bis: "ajoute le nom complet en commentaire dans le contenu de la
 * fiche plutôt que de le perdre silencieusement").
 *
 * @param array $dz_entries
 * @return array{created:int,updated:int,skipped:int,notes:string[]}
 */
function dz_import_run_nominations_mouvement( array $dz_entries ) {
	$dz_created = 0;
	$dz_updated = 0;
	$dz_notes   = array();

	foreach ( $dz_entries as $dz_entry ) {
		$dz_pretre_id = 0;

		if ( ! empty( $dz_entry['aumonier'] ) ) {
			$dz_pretre_id = dz_import_find_pretre_by_title( $dz_entry['aumonier'] );

			if ( ! $dz_pretre_id ) {
				$dz_missing_note = sprintf(
					/* translators: %s: chaplain name */
					__( 'Aumônier pressenti (fiche prêtre non trouvée dans le CPT prêtre) : %s — à relier manuellement au champ « Aumônier » une fois sa fiche créée.', 'diocese-ziguinchor' ),
					$dz_entry['aumonier']
				);
				$dz_entry['note'] = trim( ( $dz_entry['note'] ?? '' ) . "\n\n" . $dz_missing_note );

				$dz_notes[] = sprintf(
					/* translators: 1: chaplain name, 2: mouvement title */
					__( 'Aumônier « %1$s » introuvable dans le CPT prêtre pour « %2$s » — nom conservé dans le contenu de la fiche, relation à compléter manuellement.', 'diocese-ziguinchor' ),
					$dz_entry['aumonier'],
					$dz_entry['titre']
				);
			}
		}

		list( $dz_post_id, $dz_is_new ) = dz_import_upsert_organisation_post( 'mouvement', $dz_entry, $dz_notes );
		if ( ! $dz_post_id ) {
			continue;
		}

		if ( $dz_pretre_id ) {
			update_field( 'mouvement_aumonier', $dz_pretre_id, $dz_post_id );
		}

		$dz_is_new ? ++$dz_created : ++$dz_updated;
	}

	return array(
		'created' => $dz_created,
		'updated' => $dz_updated,
		'skipped' => 0,
		'notes'   => $dz_notes,
	);
}

/**
 * PROMPT 3, section IV: association — no field specific to this CPT
 * (aumonier here is transcribed straight into `org_responsable`/`note`,
 * there is no relation field for it on this CPT, unlike `mouvement`).
 *
 * @param array $dz_entries
 * @return array{created:int,updated:int,skipped:int,notes:string[]}
 */
function dz_import_run_nominations_association( array $dz_entries ) {
	$dz_created = 0;
	$dz_updated = 0;
	$dz_notes   = array();

	foreach ( $dz_entries as $dz_entry ) {
		list( $dz_post_id, $dz_is_new ) = dz_import_upsert_organisation_post( 'association', $dz_entry, $dz_notes );
		if ( ! $dz_post_id ) {
			continue;
		}

		$dz_is_new ? ++$dz_created : ++$dz_updated;
	}

	return array(
		'created' => $dz_created,
		'updated' => $dz_updated,
		'skipped' => 0,
		'notes'   => $dz_notes,
	);
}

/**
 * Imports the nominations circular: dispatches to one function per target
 * CPT (CONTENT_PROMPTS.md PROMPT 3 — "un fichier data-nominations.php
 * unique mais une fonction d'import par CPT pour rester lisible") and
 * aggregates their results.
 *
 * @return array{created:int,updated:int,skipped:int,notes:string[]}
 */
function dz_import_run_nominations() {
	$dz_data = dz_import_get_nominations_data();

	if ( ! array_filter( $dz_data ) ) {
		return array(
			'created' => 0,
			'updated' => 0,
			'skipped' => 0,
			'notes'   => array( __( 'Aucune donnée dans inc/import/data-nominations.php pour le moment (voir CONTENT_PROMPTS.md PROMPT 3).', 'diocese-ziguinchor' ) ),
		);
	}

	$dz_dispatch = array(
		'service_diocesain'     => 'dz_import_run_nominations_service_diocesain',
		'commission_diocesaine' => 'dz_import_run_nominations_commission_diocesaine',
		'mouvement'             => 'dz_import_run_nominations_mouvement',
		'association'           => 'dz_import_run_nominations_association',
	);

	$dz_created = 0;
	$dz_updated = 0;
	$dz_skipped = 0;
	$dz_notes   = array();

	foreach ( $dz_dispatch as $dz_key => $dz_callback ) {
		if ( empty( $dz_data[ $dz_key ] ) ) {
			continue;
		}

		$dz_result = call_user_func( $dz_callback, $dz_data[ $dz_key ] );

		$dz_created += $dz_result['created'];
		$dz_updated += $dz_result['updated'];
		$dz_skipped += $dz_result['skipped'];
		$dz_notes    = array_merge( $dz_notes, $dz_result['notes'] );
	}

	return array(
		'created' => $dz_created,
		'updated' => $dz_updated,
		'skipped' => $dz_skipped,
		'notes'   => $dz_notes,
	);
}

/**
 * PROMPT 4, sections V-VIII: chaplaincies. Same upsert helper as the
 * nominations import (dz_import_upsert_organisation_post() is generic
 * across post types, not nominations-specific) plus the `type_aumonerie`
 * taxonomy term from the entry.
 *
 * @param array $dz_entries
 * @return array{created:int,updated:int,skipped:int,notes:string[]}
 */
function dz_import_run_aumoneries_aumonerie( array $dz_entries ) {
	$dz_created = 0;
	$dz_updated = 0;
	$dz_notes   = array();

	foreach ( $dz_entries as $dz_entry ) {
		list( $dz_post_id, $dz_is_new ) = dz_import_upsert_organisation_post( 'aumonerie', $dz_entry, $dz_notes );
		if ( ! $dz_post_id ) {
			continue;
		}

		if ( ! empty( $dz_entry['type_aumonerie'] ) ) {
			wp_set_object_terms( $dz_post_id, $dz_entry['type_aumonerie'], 'type_aumonerie' );
		}

		$dz_is_new ? ++$dz_created : ++$dz_updated;
	}

	return array(
		'created' => $dz_created,
		'updated' => $dz_updated,
		'skipped' => 0,
		'notes'   => $dz_notes,
	);
}

/**
 * PROMPT 4, point 2: diocesan establishments not already covered by an
 * `aumonerie` entry (see inc/import/data-aumoneries.php and DECISIONS.md for
 * which ones were deliberately left out to avoid duplicating an existing
 * aumonerie fiche). `type_etablissement` is a select field (not a taxonomy,
 * unlike aumonerie — see acf-json/group_dz_cpt_etablissement.json), set the
 * same way as org_responsable/org_membres.
 *
 * @param array $dz_entries
 * @return array{created:int,updated:int,skipped:int,notes:string[]}
 */
function dz_import_run_aumoneries_etablissement( array $dz_entries ) {
	$dz_created = 0;
	$dz_updated = 0;
	$dz_notes   = array();

	foreach ( $dz_entries as $dz_entry ) {
		list( $dz_post_id, $dz_is_new ) = dz_import_upsert_organisation_post( 'etablissement', $dz_entry, $dz_notes );
		if ( ! $dz_post_id ) {
			continue;
		}

		if ( ! empty( $dz_entry['type_etablissement'] ) ) {
			update_field( 'type_etablissement', $dz_entry['type_etablissement'], $dz_post_id );
		}
		if ( ! empty( $dz_entry['contact'] ) ) {
			update_field( 'etablissement_contact', $dz_entry['contact'], $dz_post_id );
		}

		$dz_is_new ? ++$dz_created : ++$dz_updated;
	}

	return array(
		'created' => $dz_created,
		'updated' => $dz_updated,
		'skipped' => 0,
		'notes'   => $dz_notes,
	);
}

/**
 * Imports chaplaincies and diocesan establishments (CONTENT_PROMPTS.md
 * PROMPT 4): dispatches to the two functions above and aggregates their
 * results, same pattern as dz_import_run_nominations().
 *
 * @return array{created:int,updated:int,skipped:int,notes:string[]}
 */
function dz_import_run_aumoneries() {
	$dz_data = dz_import_get_aumoneries_data();

	if ( ! array_filter( $dz_data ) ) {
		return array(
			'created' => 0,
			'updated' => 0,
			'skipped' => 0,
			'notes'   => array( __( 'Aucune donnée dans inc/import/data-aumoneries.php pour le moment (voir CONTENT_PROMPTS.md PROMPT 4).', 'diocese-ziguinchor' ) ),
		);
	}

	$dz_dispatch = array(
		'aumonerie'     => 'dz_import_run_aumoneries_aumonerie',
		'etablissement' => 'dz_import_run_aumoneries_etablissement',
	);

	$dz_created = 0;
	$dz_updated = 0;
	$dz_skipped = 0;
	$dz_notes   = array();

	foreach ( $dz_dispatch as $dz_key => $dz_callback ) {
		if ( empty( $dz_data[ $dz_key ] ) ) {
			continue;
		}

		$dz_result = call_user_func( $dz_callback, $dz_data[ $dz_key ] );

		$dz_created += $dz_result['created'];
		$dz_updated += $dz_result['updated'];
		$dz_skipped += $dz_result['skipped'];
		$dz_notes    = array_merge( $dz_notes, $dz_result['notes'] );
	}

	return array(
		'created' => $dz_created,
		'updated' => $dz_updated,
		'skipped' => $dz_skipped,
		'notes'   => $dz_notes,
	);
}

/**
 * Creates OR updates a native `page` post from an import entry (title +
 * content, optionally a page template). Mirrors
 * dz_import_upsert_organisation_post() (same upsert-not-skip-once semantics,
 * same source_id idempotence) but without dz_import_apply_org_socle(): a
 * plain `page` has no organisational socle field group to fill.
 *
 * @param array    $entry     {source_id, titre, content, template?}
 * @param string[] $dz_notes  By reference; a creation failure is appended here.
 * @return array{0:int,1:bool} [post ID (0 on failure), true if newly created / false if it already existed and was updated]
 */
function dz_import_upsert_page( array $entry, array &$dz_notes ) {
	$dz_post_id = dz_import_find_existing_post( 'page', $entry['source_id'] );
	$dz_is_new  = ! $dz_post_id;

	if ( $dz_is_new ) {
		$dz_post_id = wp_insert_post(
			array(
				'post_type'   => 'page',
				'post_title'  => wp_strip_all_tags( $entry['titre'] ),
				'post_status' => 'publish',
			),
			true
		);

		if ( is_wp_error( $dz_post_id ) ) {
			$dz_notes[] = sprintf(
				/* translators: 1: page title, 2: error message */
				__( "Échec de la création de la page « %1\$s » : %2\$s", 'diocese-ziguinchor' ),
				$entry['titre'],
				$dz_post_id->get_error_message()
			);
			return array( 0, false );
		}

		dz_import_mark_imported( $dz_post_id, $entry['source_id'] );
	}

	wp_update_post(
		array(
			'ID'           => $dz_post_id,
			'post_title'   => wp_strip_all_tags( $entry['titre'] ),
			'post_content' => ! empty( $entry['content'] ) ? wp_kses_post( $entry['content'] ) : '',
		)
	);

	if ( ! empty( $entry['template'] ) ) {
		update_post_meta( $dz_post_id, '_wp_page_template', $entry['template'] );
	}

	return array( $dz_post_id, $dz_is_new );
}

/**
 * PROMPT 5, point 1: fills the "Historique" page with the armoiries
 * document's intro + 6-point text + conclusion (see
 * inc/import/data-armoiries.php). bin/seed-static-pages.php no longer
 * creates this page (see DECISIONS.md) — this is now the only source of
 * truth for it, same as bin/seed-cpt-organisation.php's PROMPT 3bis trim.
 *
 * @param array $dz_entry
 * @return array{created:int,updated:int,skipped:int,notes:string[]}
 */
function dz_import_run_armoiries_historique( array $dz_entry ) {
	$dz_notes = array();

	list( $dz_post_id, $dz_is_new ) = dz_import_upsert_page( $dz_entry, $dz_notes );

	if ( ! $dz_post_id ) {
		return array(
			'created' => 0,
			'updated' => 0,
			'skipped' => 0,
			'notes'   => $dz_notes,
		);
	}

	return array(
		'created' => $dz_is_new ? 1 : 0,
		'updated' => $dz_is_new ? 0 : 1,
		'skipped' => 0,
		'notes'   => $dz_notes,
	);
}

/**
 * PROMPT 5, points 2-3: the dedicated "Nos armoiries" page — sideloads the
 * crest (logo-diocese-ziguinchor.png) as the page's featured image (used as
 * the page's main illustration by page-armoiries.php, and reused in every
 * alternating row rather than ten fabricated close-ups — see DECISIONS.md),
 * then fills the `dz_armoiries_symboles` repeater
 * (group_dz_page_armoiries, inc/acf-fields.php) from the entry's `symboles`.
 *
 * @param array $dz_entry
 * @return array{created:int,updated:int,skipped:int,notes:string[]}
 */
function dz_import_run_armoiries_page( array $dz_entry ) {
	$dz_notes = array();

	$dz_entry['template'] = 'page-armoiries.php';

	list( $dz_post_id, $dz_is_new ) = dz_import_upsert_page( $dz_entry, $dz_notes );

	if ( ! $dz_post_id ) {
		return array(
			'created' => 0,
			'updated' => 0,
			'skipped' => 0,
			'notes'   => $dz_notes,
		);
	}

	if ( ! empty( $dz_entry['image'] ) && ! has_post_thumbnail( $dz_post_id ) ) {
		$dz_image_path = DZ_THEME_DIR . '/' . ltrim( $dz_entry['image'], '/' );

		if ( ! file_exists( $dz_image_path ) ) {
			$dz_notes[] = sprintf(
				/* translators: %s: image file path */
				__( 'Image des armoiries introuvable : %s', 'diocese-ziguinchor' ),
				$dz_entry['image']
			);
		} else {
			$dz_attachment_id = dz_import_sideload_image( $dz_image_path, $dz_post_id, $dz_entry['titre'] );

			if ( is_wp_error( $dz_attachment_id ) ) {
				$dz_notes[] = sprintf(
					/* translators: 1: image file path, 2: error message */
					__( "Échec de l'import de l'image %1\$s : %2\$s", 'diocese-ziguinchor' ),
					$dz_entry['image'],
					$dz_attachment_id->get_error_message()
				);
			} else {
				update_post_meta( $dz_attachment_id, '_wp_attachment_image_alt', $dz_entry['titre'] );
				set_post_thumbnail( $dz_post_id, $dz_attachment_id );
			}
		}
	}

	if ( ! empty( $dz_entry['symboles'] ) ) {
		$dz_rows = array();
		foreach ( $dz_entry['symboles'] as $dz_symbole ) {
			$dz_rows[] = array(
				'dz_armoiries_symbole_titre' => $dz_symbole['titre'],
				'dz_armoiries_symbole_texte' => $dz_symbole['texte'],
			);
		}
		update_field( 'dz_armoiries_symboles', $dz_rows, $dz_post_id );
	}

	return array(
		'created' => $dz_is_new ? 1 : 0,
		'updated' => $dz_is_new ? 0 : 1,
		'skipped' => 0,
		'notes'   => $dz_notes,
	);
}

/**
 * Imports the "À propos"/armoiries content (CONTENT_PROMPTS.md PROMPT 5):
 * dispatches to the two functions above and aggregates their results, same
 * pattern as dz_import_run_nominations()/dz_import_run_aumoneries().
 *
 * @return array{created:int,updated:int,skipped:int,notes:string[]}
 */
function dz_import_run_armoiries() {
	$dz_data = dz_import_get_armoiries_data();

	if ( ! array_filter( $dz_data ) ) {
		return array(
			'created' => 0,
			'updated' => 0,
			'skipped' => 0,
			'notes'   => array( __( 'Aucune donnée dans inc/import/data-armoiries.php pour le moment (voir CONTENT_PROMPTS.md PROMPT 5).', 'diocese-ziguinchor' ) ),
		);
	}

	$dz_dispatch = array(
		'historique'     => 'dz_import_run_armoiries_historique',
		'armoiries_page' => 'dz_import_run_armoiries_page',
	);

	$dz_created = 0;
	$dz_updated = 0;
	$dz_skipped = 0;
	$dz_notes   = array();

	foreach ( $dz_dispatch as $dz_key => $dz_callback ) {
		if ( empty( $dz_data[ $dz_key ] ) ) {
			continue;
		}

		$dz_result = call_user_func( $dz_callback, $dz_data[ $dz_key ] );

		$dz_created += $dz_result['created'];
		$dz_updated += $dz_result['updated'];
		$dz_skipped += $dz_result['skipped'];
		$dz_notes    = array_merge( $dz_notes, $dz_result['notes'] );
	}

	return array(
		'created' => $dz_created,
		'updated' => $dz_updated,
		'skipped' => $dz_skipped,
		'notes'   => $dz_notes,
	);
}

/**
 * PROMPT 1 ("Identité visuelle" — DESIGN_PROMPTS.md PROMPT 12bis, still
 * unchecked in TODO.md Phase 1bis): sets the real crest as the default
 * "Réglages du thème" logo (`dz_logo`, group_dz_theme_settings — see
 * inc/acf-fields.php, already rendered by header.php) and generates a
 * square WordPress site icon (favicon) from the same file.
 *
 * Unlike the other dz_import_run_*() functions, there is no data-*.php file
 * here: this isn't content transcribed from a PDF, just wiring an
 * already-placed theme asset (assets/seed-images/armoiries/) to two
 * WordPress-native settings, so there is nothing to "transcribe".
 *
 * Skip-once semantics (like the hero slides), not an upsert like the
 * organisational CPTs: once a logo/favicon is set — by this import, or
 * manually by an admin through wp-admin/the Customizer — re-running never
 * overwrites it, so a deliberate manual change always wins.
 *
 * @return array{created:int,skipped:int,notes:string[]}
 */
function dz_import_run_logo() {
	$dz_created = 0;
	$dz_skipped = 0;
	$dz_notes   = array();

	$dz_image_path = DZ_THEME_DIR . '/assets/seed-images/armoiries/logo-diocese-ziguinchor.png';

	if ( ! file_exists( $dz_image_path ) ) {
		return array(
			'created' => 0,
			'skipped' => 0,
			'notes'   => array( __( 'Fichier logo-diocese-ziguinchor.png introuvable dans assets/seed-images/armoiries/.', 'diocese-ziguinchor' ) ),
		);
	}

	$dz_existing_logo = dz_get_option( 'dz_logo', array() );
	if ( ! empty( $dz_existing_logo['ID'] ) ) {
		++$dz_skipped;
	} else {
		$dz_logo_id = dz_import_sideload_image( $dz_image_path, 0, __( 'Armoiries du diocèse de Ziguinchor', 'diocese-ziguinchor' ) );

		if ( is_wp_error( $dz_logo_id ) ) {
			$dz_notes[] = sprintf(
				/* translators: %s: error message */
				__( "Échec de l'import du logo : %s", 'diocese-ziguinchor' ),
				$dz_logo_id->get_error_message()
			);
		} else {
			update_post_meta( $dz_logo_id, '_wp_attachment_image_alt', __( 'Armoiries du diocèse de Ziguinchor', 'diocese-ziguinchor' ) );
			update_field( 'dz_logo', $dz_logo_id, 'option' );
			++$dz_created;
		}
	}

	if ( get_option( 'site_icon' ) ) {
		++$dz_skipped;
	} else {
		$dz_favicon_id = dz_import_generate_favicon( $dz_image_path );

		if ( is_wp_error( $dz_favicon_id ) ) {
			$dz_notes[] = sprintf(
				/* translators: %s: error message */
				__( 'Échec de la génération du favicon : %s', 'diocese-ziguinchor' ),
				$dz_favicon_id->get_error_message()
			);
		} else {
			update_option( 'site_icon', $dz_favicon_id );
			++$dz_created;
		}
	}

	return array(
		'created' => $dz_created,
		'skipped' => $dz_skipped,
		'notes'   => $dz_notes,
	);
}

/**
 * Generates a square favicon from a non-square source image (our crest PNG
 * is 6512×6041, not exactly square) via a center-crop, so a browser's
 * square favicon slot doesn't squeeze/distort the shield. Copies the source
 * first (same reasoning as dz_import_sideload_image() — WP_Image_Editor
 * writes its crop back out, never edit the permanent theme asset in place),
 * crops the copy, then sideloads the cropped result as its own media
 * library attachment, kept separate from `dz_logo` (which stays the
 * uncropped original for header/footer display).
 *
 * @param string $file_path Absolute path to the source image.
 * @return int|WP_Error Attachment ID, or a WP_Error on failure.
 */
function dz_import_generate_favicon( $file_path ) {
	$dz_tmp_source = wp_tempnam( basename( $file_path ) );

	if ( ! copy( $file_path, $dz_tmp_source ) ) {
		return new WP_Error(
			'dz_import_copy_failed',
			__( "Impossible de copier l'image source pour générer le favicon.", 'diocese-ziguinchor' )
		);
	}

	$dz_editor = wp_get_image_editor( $dz_tmp_source );

	if ( is_wp_error( $dz_editor ) ) {
		if ( file_exists( $dz_tmp_source ) ) {
			unlink( $dz_tmp_source );
		}
		return $dz_editor;
	}

	$dz_size = $dz_editor->get_size();
	$dz_side = min( $dz_size['width'], $dz_size['height'] );
	$dz_x    = (int) ( ( $dz_size['width'] - $dz_side ) / 2 );
	$dz_y    = (int) ( ( $dz_size['height'] - $dz_side ) / 2 );

	$dz_crop_result = $dz_editor->crop( $dz_x, $dz_y, $dz_side, $dz_side );
	$dz_saved       = is_wp_error( $dz_crop_result ) ? $dz_crop_result : $dz_editor->save();

	if ( file_exists( $dz_tmp_source ) ) {
		unlink( $dz_tmp_source );
	}

	if ( is_wp_error( $dz_saved ) ) {
		return $dz_saved;
	}

	$dz_attachment_id = dz_import_sideload_image( $dz_saved['path'], 0, __( 'Favicon — armoiries du diocèse de Ziguinchor', 'diocese-ziguinchor' ) );

	if ( file_exists( $dz_saved['path'] ) ) {
		unlink( $dz_saved['path'] );
	}

	return $dz_attachment_id;
}
