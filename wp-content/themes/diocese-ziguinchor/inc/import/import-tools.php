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

/**
 * Data source key => admin button label + dispatcher callback. The single
 * place that ties a button on the admin page to the function it runs.
 *
 * @return array<string,array{label:string,callback:callable}>
 */
function dz_import_get_sources() {
	return array(
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
			<?php esc_html_e( "Outil d'import ponctuel : lit les données déjà transcrites dans inc/import/data-*.php et crée le contenu WordPress correspondant. Chaque bouton peut être cliqué plusieurs fois sans risque, y compris après une nouvelle tentative suite à une correction des données : le contenu déjà importé n'est jamais dupliqué.", 'diocese-ziguinchor' ); ?>
		</p>

		<?php if ( is_array( $dz_results ) ) : ?>
			<div class="notice notice-info">
				<p>
					<?php
					echo esc_html(
						sprintf(
							/* translators: 1: number of items created, 2: number of items already imported (skipped) */
							__( '%1$d élément(s) créé(s), %2$d déjà importé(s) (ignorés).', 'diocese-ziguinchor' ),
							(int) ( $dz_results['created'] ?? 0 ),
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
 * Imports the nominations circular into service_diocesain/commission_
 * diocesaine/mouvement/association. Full logic (one function per CPT) added
 * in CONTENT_PROMPTS.md PROMPT 3; for now this safely reports that
 * inc/import/data-nominations.php has nothing to import yet.
 *
 * @return array{created:int,skipped:int,notes:string[]}
 */
function dz_import_run_nominations() {
	$dz_data = dz_import_get_nominations_data();

	if ( ! array_filter( $dz_data ) ) {
		return array(
			'created' => 0,
			'skipped' => 0,
			'notes'   => array( __( 'Aucune donnée dans inc/import/data-nominations.php pour le moment (voir CONTENT_PROMPTS.md PROMPT 3).', 'diocese-ziguinchor' ) ),
		);
	}

	// Implémentation complète : CONTENT_PROMPTS.md PROMPT 3.
	return array(
		'created' => 0,
		'skipped' => 0,
		'notes'   => array(),
	);
}
