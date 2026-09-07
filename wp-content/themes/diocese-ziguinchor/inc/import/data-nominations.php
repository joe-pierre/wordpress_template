<?php
/**
 * Nominations circular data — plain data, no logic (CONTENT_PROMPTS.md
 * PROMPT 0). Populated at PROMPT 3 by transcribing
 * NOMINATIONS_SERVICES_COMMISSIONS_AUMONERIES_2027.pdf (Circulaire
 * n°002/2026-2027) sections I-IV — empty for now, PROMPT 0 only builds the
 * reusable import mechanism itself (see inc/import/import-tools.php).
 *
 * One key per target CPT so dz_import_run_nominations()
 * (inc/import/import-tools.php) can dispatch to one import function per
 * CPT and stay readable, per CONTENT_PROMPTS.md PROMPT 3 — `aumonerie`/
 * `etablissement` (sections V-VIII, PROMPT 4) are a separate data file,
 * added when that prompt is implemented.
 *
 * Per entry: source_id (stable, used for idempotence), titre (entity
 * name), responsable (first person listed, with their role), membres
 * (every other person listed, each with their exact role as written in the
 * PDF). `service_diocesain` entries additionally carry sous_structures
 * (name + responsable) for attached entities (e.g. Économat's Hôtel
 * Carabane) instead of separate posts — see SPEC.md §3 and
 * acf-json/group_dz_cpt_service_diocesain.json.
 *
 * @return array<string,array<int,array{
 *     source_id: string,
 *     titre: string,
 *     responsable: string,
 *     membres: array<int,array{nom:string,role:string}>,
 *     sous_structures?: array<int,array{nom:string,responsable:string}>,
 * }>>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dz_import_get_nominations_data() {
	return array(
		'service_diocesain'     => array(),
		'commission_diocesaine' => array(),
		'mouvement'             => array(),
		'association'           => array(),
	);
}
