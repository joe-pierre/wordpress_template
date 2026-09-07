<?php
/**
 * Diocesan calendar data — plain data, no logic (CONTENT_PROMPTS.md
 * PROMPT 0/2). Meant to be populated by transcribing every row of
 * CALENDRIER_DIOCESAIN_2027.pdf (pastoral year October 2026 to October
 * 2027, table Dates / Activités / Lieux).
 *
 * **Still empty**: CALENDRIER_DIOCESAIN_2027.pdf is not present anywhere in
 * this repository (checked at PROMPT 2 — same recurring constraint as the
 * nominations circular, see DECISIONS.md) — there is nothing to transcribe
 * from yet. dz_import_run_calendrier() (inc/import/import-tools.php) is
 * fully implemented and tested against this exact shape, ready to run as
 * soon as this array is filled in from the real PDF; see BUGS_AND_ROADMAP.md.
 *
 * Transcription rules for whoever fills this in:
 * - `source_id`: stable, unique per row (e.g. a slug of the date + activity),
 *   used for idempotence — never derived from the row's position in the
 *   array (reordering rows must not change existing source_ids).
 * - `date_debut`/`date_fin`: full `Y-m-d H:i:s`, year always resolved (the
 *   PDF states it once per month heading, e.g. "OCT/NOV 2026" — never leave
 *   it implicit here). If the PDF gives a range ("04-09 octobre"), that's
 *   date_debut = first day, date_fin = last day; a single date means
 *   date_fin = date_debut. No time of day is given in the PDF for most
 *   rows: default to `00:00:00` for date_debut and `23:59:59` for date_fin
 *   so the activity stays "upcoming" through its entire last day (see
 *   dz_evenement_archive_query(), inc/cpt-evenement.php).
 * - `lieu`: free text as written in the PDF — dz_import_run_calendrier()
 *   itself checks it against existing `paroisse` titles and links the
 *   `evenement_paroisse` relation automatically when one matches, no need
 *   to resolve that by hand here.
 * - `evenement_type`: omit (defaults to `diocesain`) unless the row is
 *   explicitly the bishop's own agenda — e.g. Mgr Manga's episcopal
 *   ordination anniversary — in which case set it to `eveque`.
 *
 * @return array<int,array{
 *     source_id: string,
 *     titre: string,
 *     date_debut: string,
 *     date_fin: string,
 *     lieu: string,
 *     evenement_type?: string,
 * }>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dz_import_get_calendrier_data() {
	return array();
}
