<?php
/**
 * Front-page hero slide data — plain data, no logic (CONTENT_PROMPTS.md
 * PROMPT 1). 4 real images (not 5) transcribed from the titles/subtitles
 * validated with the client. The images themselves live in
 * assets/seed-images/hero/ (moved there from assets/img/eveque/ at PROMPT 1
 * — see DECISIONS.md — not a permanent theme asset location, just where the
 * import function below reads them from before sideloading each into the
 * media library).
 *
 * Consumed by dz_import_run_hero() (inc/import/import-tools.php), which
 * sideloads each image into the media library and populates the
 * `dz_front_hero_slides` repeater (group_dz_front_hero, see
 * inc/acf-fields.php) — not a CPT post, so idempotence there is checked
 * per-slide against `source_id` inside the repeater itself, not via
 * `_dz_import_source_id` post meta (that pattern is for the calendrier/
 * nominations imports, which do create real posts).
 *
 * `image` is a path relative to the theme directory (DZ_THEME_DIR), read by
 * dz_import_run_hero() with DZ_THEME_DIR . '/' . $slide['image'].
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
	return array(
		array(
			'source_id' => 'hero-messe',
			'image'     => 'assets/seed-images/hero/messe.jpg',
			'titre'     => __( 'Célébration eucharistique', 'diocese-ziguinchor' ),
			'texte'     => __( "L'Église rassemblée autour de l'autel", 'diocese-ziguinchor' ),
			'lien'      => '',
		),
		array(
			'source_id' => 'hero-consecration',
			'image'     => 'assets/seed-images/hero/consecration.jpg',
			'titre'     => __( 'Au cœur de chaque messe', 'diocese-ziguinchor' ),
			'texte'     => __( 'Le mystère eucharistique, source de toute vie chrétienne', 'diocese-ziguinchor' ),
			'lien'      => '',
		),
		array(
			'source_id' => 'hero-eveque',
			'image'     => 'assets/seed-images/hero/eveque.jpg',
			'titre'     => __( 'Une Église proche des fidèles', 'diocese-ziguinchor' ),
			'texte'     => __( 'Mgr Jean Baptiste Valter Manga à la rencontre des familles', 'diocese-ziguinchor' ),
			'lien'      => '',
		),
		array(
			'source_id' => 'hero-benediction',
			'image'     => 'assets/seed-images/hero/benediction_par_eveque.jpg',
			'titre'     => __( "La bénédiction de l'évêque", 'diocese-ziguinchor' ),
			'texte'     => __( 'Un geste de proximité pastorale', 'diocese-ziguinchor' ),
			'lien'      => '',
		),
	);
}
