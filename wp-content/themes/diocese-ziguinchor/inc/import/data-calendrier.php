<?php
/**
 * Diocesan calendar data — plain data, no logic (CONTENT_PROMPTS.md
 * PROMPT 0/2). Transcribed from documents/CALENDRIER DIOCESAIN 2027.pdf
 * (pastoral year October 2026 to October 2027, table Dates / Activités /
 * Lieux) — a scan with no text layer (`Creator: HP Scan`, single A4 page),
 * read directly via a 300dpi rasterization (`pdftoppm`), same method as
 * PROMPT 3bis/4.
 *
 * Every row of the table is present below, including the very last one
 * ("1er octobre" 2027, "Ouverture de l'Année Pastorale") which opens the
 * *following* pastoral year — kept because it is a genuine row of the
 * document, not an editorial addition.
 *
 * Transcription rules applied:
 * - `source_id`: `calendrier-<date_debut Y-m-d>-<slug>`, stable and unique
 *   per row (two rows can share the same date — e.g. 2027-01-23 has two
 *   distinct activities — the slug suffix disambiguates them).
 * - `date_debut`/`date_fin`: year always resolved from the row's month/year
 *   section heading (e.g. "OCT/NOV 2026", "JUIN / JUIL 27"), never left
 *   implicit. A range in the PDF ("04-09 octobre") becomes date_debut =
 *   first day 00:00:00, date_fin = last day 23:59:59. A single date means
 *   date_fin = date_debut (23:59:59), per dz_import_run_calendrier()'s own
 *   fallback — set explicitly here anyway for clarity.
 * - `lieu`: free text exactly as written in the PDF (accents/casing kept).
 *   dz_import_run_calendrier() itself checks it against existing `paroisse`
 *   titles and links `evenement_paroisse` automatically when one matches;
 *   the `paroisse` CPT is empty in this repository at the time of writing,
 *   so every row here is expected to stay free text for now — not a bug.
 * - `evenement_type`: omitted (defaults to `diocesain`) except the one row
 *   that is explicitly the bishop's own agenda (his episcopal ordination
 *   anniversary, 21-22 novembre 2026), set to `eveque`.
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
	return array(

		// OCT/NOV 2026
		array(
			'source_id'  => 'calendrier-2026-10-01-ouverture-annee-pastorale',
			'titre'      => __( "Ouverture de l'Année Pastorale", 'diocese-ziguinchor' ),
			'date_debut' => '2026-10-01 00:00:00',
			'date_fin'   => '2026-10-01 23:59:59',
			'lieu'       => __( 'Diocèse de Ziguinchor', 'diocese-ziguinchor' ),
		),
		array(
			'source_id'  => 'calendrier-2026-10-04-jubile-argent-affiniam',
			'titre'      => __( "Ouverture du Jubilé d'Argent de la Paroisse Sainte Thérèse de l'Enfant Jésus d'Affiniam", 'diocese-ziguinchor' ),
			'date_debut' => '2026-10-04 00:00:00',
			'date_fin'   => '2026-10-04 23:59:59',
			'lieu'       => __( "Sainte Thérèse d'Affiniam", 'diocese-ziguinchor' ),
		),
		array(
			'source_id'  => 'calendrier-2026-10-04-retraite-pretres-groupe-1',
			'titre'      => __( 'Retraite des prêtres (1er groupe)', 'diocese-ziguinchor' ),
			'date_debut' => '2026-10-04 00:00:00',
			'date_fin'   => '2026-10-09 23:59:59',
			'lieu'       => __( 'Foyer de Charité de Sindone', 'diocese-ziguinchor' ),
		),
		array(
			'source_id'  => 'calendrier-2026-10-11-retraite-pretres-groupe-2',
			'titre'      => __( 'Retraite des prêtres (2e groupe)', 'diocese-ziguinchor' ),
			'date_debut' => '2026-10-11 00:00:00',
			'date_fin'   => '2026-10-16 23:59:59',
			'lieu'       => __( 'Foyer de Charité de Sindone', 'diocese-ziguinchor' ),
		),
		array(
			'source_id'  => 'calendrier-2026-10-17-nuit-priere-udafcz',
			'titre'      => __( "Nuit de prière de l'UDAFCZ", 'diocese-ziguinchor' ),
			'date_debut' => '2026-10-17 00:00:00',
			'date_fin'   => '2026-10-18 23:59:59',
			'lieu'       => __( "Sanctuaire M. d'Elinkine", 'diocese-ziguinchor' ),
		),
		array(
			'source_id'  => 'calendrier-2026-10-24-messe-ouverture-annee-pastorale-diocesaine',
			'titre'      => __( "Messe d'ouverture de l'année pastorale diocésaine", 'diocese-ziguinchor' ),
			'date_debut' => '2026-10-24 00:00:00',
			'date_fin'   => '2026-10-25 23:59:59',
			'lieu'       => __( 'Séminaire Saint Louis', 'diocese-ziguinchor' ),
		),
		array(
			'source_id'  => 'calendrier-2026-11-18-ag-agents-pastoraux',
			'titre'      => __( 'Assemblée Générale des Agents Pastoraux', 'diocese-ziguinchor' ),
			'date_debut' => '2026-11-18 00:00:00',
			'date_fin'   => '2026-11-18 23:59:59',
			'lieu'       => __( 'Cathédrale SAP', 'diocese-ziguinchor' ),
		),
		array(
			'source_id'  => 'calendrier-2026-11-19-session-formation-cures',
			'titre'      => __( 'Session de formation des Curés et Administrateurs', 'diocese-ziguinchor' ),
			'date_debut' => '2026-11-19 00:00:00',
			'date_fin'   => '2026-11-19 23:59:59',
			'lieu'       => __( 'Grand Séminaire de Brin', 'diocese-ziguinchor' ),
		),
		array(
			'source_id'      => 'calendrier-2026-11-21-anniversaire-ordination-mgr-manga',
			'titre'          => __( "Anniversaire d'Ordination épiscopale de Mgr Jean Baptiste V. MANGA", 'diocese-ziguinchor' ),
			'date_debut'     => '2026-11-21 00:00:00',
			'date_fin'       => '2026-11-22 23:59:59',
			'lieu'           => __( "Sainte Thérèse d'Oussouye", 'diocese-ziguinchor' ),
			'evenement_type' => 'eveque',
		),

		// DEC 2026
		array(
			'source_id'  => 'calendrier-2026-12-04-ag-caritas-diocesaine',
			'titre'      => __( 'Assemblée Générale de la Caritas diocésaine', 'diocese-ziguinchor' ),
			'date_debut' => '2026-12-04 00:00:00',
			'date_fin'   => '2026-12-04 23:59:59',
			'lieu'       => __( 'Siège de Caritas ZG', 'diocese-ziguinchor' ),
		),
		array(
			'source_id'  => 'calendrier-2026-12-05-profession-perpetuelle-fscm',
			'titre'      => __( 'Profession Perpétuelle des Filles du Saint Cœur de Marie', 'diocese-ziguinchor' ),
			'date_debut' => '2026-12-05 00:00:00',
			'date_fin'   => '2026-12-05 23:59:59',
			'lieu'       => __( 'Saint Benoît Néma', 'diocese-ziguinchor' ),
		),
		array(
			'source_id'  => 'calendrier-2026-12-12-pelerinage-reception-sanctuaire-marial',
			'titre'      => __( 'Pèlerinage diocésain – Réception du nouveau Sanctuaire Marial – Ordinations Presbytérales', 'diocese-ziguinchor' ),
			'date_debut' => '2026-12-12 00:00:00',
			'date_fin'   => '2026-12-12 23:59:59',
			'lieu'       => __( "Sanctuaire M. d'Elinkine", 'diocese-ziguinchor' ),
		),
		array(
			'source_id'  => 'calendrier-2026-12-20-cloture-jubile-75-ans-srs-jesus-serviteur',
			'titre'      => __( 'Clôture du Jubilé des 75 ans de présence des Sœurs de Jésus Serviteur', 'diocese-ziguinchor' ),
			'date_debut' => '2026-12-20 00:00:00',
			'date_fin'   => '2026-12-20 23:59:59',
			'lieu'       => __( 'Cathédrale SAP', 'diocese-ziguinchor' ),
		),

		// JANVIER 2027
		array(
			'source_id'  => 'calendrier-2027-01-01-journee-mondiale-priere-paix',
			'titre'      => __( 'Journée mondiale de prière pour la Paix', 'diocese-ziguinchor' ),
			'date_debut' => '2027-01-01 00:00:00',
			'date_fin'   => '2027-01-01 23:59:59',
			'lieu'       => __( 'Doyenné de Ziguinchor', 'diocese-ziguinchor' ),
		),
		array(
			'source_id'  => 'calendrier-2027-01-03-pelerinage-diocesain-enfants',
			'titre'      => __( 'Pèlerinage diocésain des enfants', 'diocese-ziguinchor' ),
			'date_debut' => '2027-01-03 00:00:00',
			'date_fin'   => '2027-01-03 23:59:59',
			'lieu'       => __( "Sanctuaire M. d'Elinkine", 'diocese-ziguinchor' ),
		),
		array(
			'source_id'  => 'calendrier-2027-01-23-pelerinage-national-catechistes',
			'titre'      => __( 'Pèlerinage national des Catéchistes', 'diocese-ziguinchor' ),
			'date_debut' => '2027-01-23 00:00:00',
			'date_fin'   => '2027-01-24 23:59:59',
			'lieu'       => __( 'Sanctuaire M. Ndiaffate (KL)', 'diocese-ziguinchor' ),
		),
		array(
			'source_id'  => 'calendrier-2027-01-23-dimanche-parole-de-dieu',
			'titre'      => __( '3e Dim TO : Dimanche de célébration de la Parole de Dieu', 'diocese-ziguinchor' ),
			'date_debut' => '2027-01-23 00:00:00',
			'date_fin'   => '2027-01-24 23:59:59',
			'lieu'       => __( 'En Paroisse', 'diocese-ziguinchor' ),
		),
		array(
			'source_id'  => 'calendrier-2027-01-30-benediction-eglise-mandina',
			'titre'      => __( "Bénédiction de l'Église de Mandina", 'diocese-ziguinchor' ),
			'date_debut' => '2027-01-30 00:00:00',
			'date_fin'   => '2027-01-30 23:59:59',
			'lieu'       => __( 'Mandina', 'diocese-ziguinchor' ),
		),

		// FÉVRIER 2027
		array(
			'source_id'  => 'calendrier-2027-02-01-journees-vie-consacree',
			'titre'      => __( 'Journées diocésaines de la Vie Consacrée', 'diocese-ziguinchor' ),
			'date_debut' => '2027-02-01 00:00:00',
			'date_fin'   => '2027-02-02 23:59:59',
			'lieu'       => __( 'NDP Tilène', 'diocese-ziguinchor' ),
		),
		array(
			'source_id'  => 'calendrier-2027-02-01-collecte-grands-seminaires',
			'titre'      => __( 'Collecte spéciale au profit des Grands Séminaires du pays', 'diocese-ziguinchor' ),
			'date_debut' => '2027-02-01 00:00:00',
			'date_fin'   => '2027-02-02 23:59:59',
			'lieu'       => __( 'Diocèse de Ziguinchor', 'diocese-ziguinchor' ),
		),
		array(
			'source_id'  => 'calendrier-2027-02-14-appel-decisif-catechumenes',
			'titre'      => __( '1er Dim. de Carême : Appel décisif des Catéchumènes de 3e Année', 'diocese-ziguinchor' ),
			'date_debut' => '2027-02-14 00:00:00',
			'date_fin'   => '2027-02-14 23:59:59',
			'lieu'       => __( 'Cathédrale SAP', 'diocese-ziguinchor' ),
		),
		array(
			'source_id'  => 'calendrier-2027-02-20-pelerinage-scouts-guides',
			'titre'      => __( 'Pèlerinage des Scouts et Guides', 'diocese-ziguinchor' ),
			'date_debut' => '2027-02-20 00:00:00',
			'date_fin'   => '2027-02-21 23:59:59',
			'lieu'       => __( "Sanctuaire M. d'Elinkine", 'diocese-ziguinchor' ),
		),
		array(
			'source_id'  => 'calendrier-2027-02-20-journee-nationale-caritas',
			'titre'      => __( '2e Dimanche de Carême : Journée nationale CARITAS', 'diocese-ziguinchor' ),
			'date_debut' => '2027-02-20 00:00:00',
			'date_fin'   => '2027-02-21 23:59:59',
			'lieu'       => __( 'Diocèse de Ziguinchor', 'diocese-ziguinchor' ),
		),
		array(
			'source_id'  => 'calendrier-2027-02-27-pelerinage-interdiocesain',
			'titre'      => __( '3e Dimanche de Carême : Pèlerinage Interdiocésain', 'diocese-ziguinchor' ),
			'date_debut' => '2027-02-27 00:00:00',
			'date_fin'   => '2027-02-28 23:59:59',
			'lieu'       => __( 'Sanctuaire M. de Temento', 'diocese-ziguinchor' ),
		),

		// MARS 2027
		array(
			'source_id'  => 'calendrier-2027-03-13-jmj',
			'titre'      => __( 'Journées Mondiales de la Jeunesse (JMJ)', 'diocese-ziguinchor' ),
			'date_debut' => '2027-03-13 00:00:00',
			'date_fin'   => '2027-03-14 23:59:59',
			'lieu'       => __( 'Saint Paul de Mandina', 'diocese-ziguinchor' ),
		),
		array(
			'source_id'  => 'calendrier-2027-03-23-messe-chrismale',
			'titre'      => __( 'Messe Chrismale', 'diocese-ziguinchor' ),
			'date_debut' => '2027-03-23 00:00:00',
			'date_fin'   => '2027-03-23 23:59:59',
			'lieu'       => __( 'Cathédrale SAP', 'diocese-ziguinchor' ),
		),

		// AVRIL 2027
		array(
			'source_id'  => 'calendrier-2027-04-18-consecration-eglise-djibock',
			'titre'      => __( "Consécration de l'Eglise de Djibock (Paroisse de Tilène)", 'diocese-ziguinchor' ),
			'date_debut' => '2027-04-18 00:00:00',
			'date_fin'   => '2027-04-18 23:59:59',
			'lieu'       => __( 'Djibock', 'diocese-ziguinchor' ),
		),
		array(
			'source_id'  => 'calendrier-2027-04-24-pelerinage-national-universites',
			'titre'      => __( 'Pèlerinage National des Universités du Sénégal', 'diocese-ziguinchor' ),
			'date_debut' => '2027-04-24 00:00:00',
			'date_fin'   => '2027-04-25 23:59:59',
			'lieu'       => __( "Sanctuaire M. d'Elinkine", 'diocese-ziguinchor' ),
		),

		// MAI 2027
		array(
			'source_id'  => 'calendrier-2027-05-01-pelerinage-legion-marie-familles',
			'titre'      => __( 'Pèlerinage de la Légion de Marie et des Familles', 'diocese-ziguinchor' ),
			'date_debut' => '2027-05-01 00:00:00',
			'date_fin'   => '2027-05-01 23:59:59',
			'lieu'       => __( "Sanctuaire M. d'Elinkine", 'diocese-ziguinchor' ),
		),
		array(
			'source_id'  => 'calendrier-2027-05-02-cloture-jubile-argent-soutou',
			'titre'      => __( "Clôture du Jubilé d'Argent de la Paroisse Saint Joseph de Soutou", 'diocese-ziguinchor' ),
			'date_debut' => '2027-05-02 00:00:00',
			'date_fin'   => '2027-05-02 23:59:59',
			'lieu'       => __( 'Soutou', 'diocese-ziguinchor' ),
		),
		array(
			'source_id'  => 'calendrier-2027-05-02-journee-diocesaine-catechiste',
			'titre'      => __( 'Journée diocésaine du Catéchiste', 'diocese-ziguinchor' ),
			'date_debut' => '2027-05-02 00:00:00',
			'date_fin'   => '2027-05-02 23:59:59',
			'lieu'       => __( 'En Doyenné', 'diocese-ziguinchor' ),
		),
		array(
			'source_id'  => 'calendrier-2027-05-15-pelerinage-national-popenguine',
			'titre'      => __( 'Pèlerinage national de POPENGUINE', 'diocese-ziguinchor' ),
			'date_debut' => '2027-05-15 00:00:00',
			'date_fin'   => '2027-05-17 23:59:59',
			'lieu'       => __( 'Sanctuaire M. Popenguine', 'diocese-ziguinchor' ),
		),
		array(
			'source_id'  => 'calendrier-2027-05-28-ag-udafcz',
			'titre'      => __( "Assemblée Générale de l'UDAFCZ", 'diocese-ziguinchor' ),
			'date_debut' => '2027-05-28 00:00:00',
			'date_fin'   => '2027-05-30 23:59:59',
			'lieu'       => __( 'Sainte Trinité Kadiamor', 'diocese-ziguinchor' ),
		),

		// JUIN / JUIL 2027
		array(
			'source_id'  => 'calendrier-2027-06-02-ag-agents-pastoraux',
			'titre'      => __( 'Assemblée Générale des Agents Pastoraux', 'diocese-ziguinchor' ),
			'date_debut' => '2027-06-02 00:00:00',
			'date_fin'   => '2027-06-02 23:59:59',
			'lieu'       => __( 'Cathédrale SAP', 'diocese-ziguinchor' ),
		),
		array(
			'source_id'  => 'calendrier-2027-06-04-priere-sanctification-pretres',
			'titre'      => __( 'Journée de prière pour la Sanctification des Prêtres (Sol. Sacré-Cœur)', 'diocese-ziguinchor' ),
			'date_debut' => '2027-06-04 00:00:00',
			'date_fin'   => '2027-06-04 23:59:59',
			'lieu'       => __( 'En Doyenné', 'diocese-ziguinchor' ),
		),
		array(
			'source_id'  => 'calendrier-2027-06-05-session-diocesaine-catechistes',
			'titre'      => __( 'Session diocésaine des Catéchistes', 'diocese-ziguinchor' ),
			'date_debut' => '2027-06-05 00:00:00',
			'date_fin'   => '2027-06-06 23:59:59',
			'lieu'       => __( 'Doyenné de Brin', 'diocese-ziguinchor' ),
		),
		array(
			'source_id'  => 'calendrier-2027-06-11-jubile-40-ans-youtou',
			'titre'      => __( 'Jubilé des 40 ans de la Paroisse de Youtou', 'diocese-ziguinchor' ),
			'date_debut' => '2027-06-11 00:00:00',
			'date_fin'   => '2027-06-13 23:59:59',
			'lieu'       => __( 'Youtou', 'diocese-ziguinchor' ),
		),
		array(
			'source_id'  => 'calendrier-2027-06-19-admission-presbyterat',
			'titre'      => __( 'Admission au Presbytérat des Séminaristes de Ziguinchor', 'diocese-ziguinchor' ),
			'date_debut' => '2027-06-19 00:00:00',
			'date_fin'   => '2027-06-19 23:59:59',
			'lieu'       => __( 'Cathédrale SAP', 'diocese-ziguinchor' ),
		),
		array(
			'source_id'  => 'calendrier-2027-07-03-ordinations-diaconales',
			'titre'      => __( 'Ordinations Diaconales (Stagiaires 2027)', 'diocese-ziguinchor' ),
			'date_debut' => '2027-07-03 00:00:00',
			'date_fin'   => '2027-07-03 23:59:59',
			'lieu'       => __( 'Cathédrale SAP', 'diocese-ziguinchor' ),
		),

		// OCTOBRE 2027 (opening of the following pastoral year — see file docblock)
		array(
			'source_id'  => 'calendrier-2027-10-01-ouverture-annee-pastorale',
			'titre'      => __( "Ouverture de l'Année Pastorale", 'diocese-ziguinchor' ),
			'date_debut' => '2027-10-01 00:00:00',
			'date_fin'   => '2027-10-01 23:59:59',
			'lieu'       => __( 'Diocèse de Ziguinchor', 'diocese-ziguinchor' ),
		),

	);
}
