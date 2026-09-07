<?php
/**
 * Diocesan calendar data — plain data, no logic (CONTENT_PROMPTS.md
 * PROMPT 0). Populated at PROMPT 2 by transcribing every row of
 * CALENDRIER_DIOCESAIN_2027.pdf (pastoral year October 2026 to October
 * 2027) — empty for now, PROMPT 0 only builds the reusable import
 * mechanism itself (see inc/import/import-tools.php).
 *
 * Dates must be fully resolved (YYYY-MM-DD) here, never left implicit: the
 * source PDF groups rows under a month heading with the year only stated
 * once (e.g. "OCT/NOV 2026"), so each row's transcription must carry the
 * complete date, not just day/month.
 *
 * Consumed by dz_import_run_calendrier() (inc/import/import-tools.php),
 * which creates one `evenement` post per entry.
 *
 * @return array<int,array{
 *     source_id: string,
 *     titre: string,
 *     date_debut: string,
 *     date_fin: string,
 *     lieu: string,
 *     evenement_type: string,
 * }>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dz_import_get_calendrier_data() {
	return array();
}
