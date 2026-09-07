<?php
/**
 * Front-page hero slide data — plain data, no logic (CONTENT_PROMPTS.md
 * PROMPT 0). Populated at PROMPT 1 from the 4 real images in
 * assets/img/eveque/ (messe.jpg, consecration.jpg, eveque.jpg,
 * benediction_par_eveque.jpg) and their validated titles/subtitles — empty
 * for now, PROMPT 0 only builds the reusable import mechanism itself (see
 * inc/import/import-tools.php).
 *
 * Consumed by dz_import_run_hero() (inc/import/import-tools.php), which
 * will sideload each image into the media library and populate the
 * `dz_front_hero_slides` repeater (group_dz_front_hero, see
 * inc/acf-fields.php) — not a CPT post, so idempotence there is checked
 * per-slide against `source_id` inside the repeater itself, not via
 * `_dz_import_source_id` post meta (that pattern is for the calendrier/
 * nominations imports, which do create real posts).
 *
 * @return array<int,array{
 *     source_id: string,
 *     image: string,
 *     titre: string,
 *     texte: string,
 *     lien: string,
 * }>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dz_import_get_hero_data() {
	return array();
}
