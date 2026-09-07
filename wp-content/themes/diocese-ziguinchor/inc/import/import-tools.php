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
 * Imports the front-page hero slides (repeater `dz_front_hero_slides`,
 * group_dz_front_hero — see inc/acf-fields.php). Full logic (media sideload
 * + repeater population) added in CONTENT_PROMPTS.md PROMPT 1; for now this
 * safely reports that inc/import/data-hero.php has nothing to import yet.
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

	// Implémentation complète : CONTENT_PROMPTS.md PROMPT 1.
	return array(
		'created' => 0,
		'skipped' => 0,
		'notes'   => array(),
	);
}

/**
 * Imports the diocesan calendar as `evenement` posts. Full logic added in
 * CONTENT_PROMPTS.md PROMPT 2; for now this safely reports that
 * inc/import/data-calendrier.php has nothing to import yet.
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

	// Implémentation complète : CONTENT_PROMPTS.md PROMPT 2.
	return array(
		'created' => 0,
		'skipped' => 0,
		'notes'   => array(),
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
