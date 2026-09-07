<?php
/**
 * Nominations circular data — plain data, no logic (CONTENT_PROMPTS.md
 * PROMPT 0/3). Transcribed from NOMINATIONS_SERVICES_COMMISSIONS_
 * AUMONERIES_2027.pdf (Circulaire n°002/2026-2027), sections I-IV.
 *
 * **Structure only, no personal data**: NOMINATIONS_SERVICES_COMMISSIONS_
 * AUMONERIES_2027.pdf is not present anywhere in this repository (checked
 * at PROMPT 3 — same recurring constraint already documented for the
 * calendrier import, see DECISIONS.md), so the actual "responsable"/
 * "membres" (real people's names and roles) cannot be transcribed. What
 * *is* already reliably documented — the entities' real names themselves,
 * enumerated in CONTENT_PROMPTS.md PROMPT 3 (which mirrors SPEC.md §3) —
 * is filled in below, exactly like bin/seed-cpt-organisation.php (PROMPT
 * 13) did for the 3 CPTs it seeded: real title, empty responsable/membres,
 * no invented person. Fill `responsable`/`membres` (and `aumonier` for
 * `mouvement`, `sous_structures` responsables for `service_diocesain`) in
 * from the PDF once it is available.
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
 * (name + responsable) for attached entities (e.g. Économat's committees)
 * instead of separate posts — see SPEC.md §3 and
 * acf-json/group_dz_cpt_service_diocesain.json. `mouvement` entries
 * additionally carry `aumonier` (a person's name, matched at import time
 * against existing `pretre` post titles — see dz_import_find_pretre_by_
 * title() in inc/import/import-tools.php; left empty here since no name is
 * known yet).
 *
 * @return array<string,array<int,array{
 *     source_id: string,
 *     titre: string,
 *     responsable: string,
 *     membres: array<int,array{nom:string,role:string}>,
 *     sous_structures?: array<int,array{nom:string,responsable:string}>,
 *     aumonier?: string,
 * }>>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dz_import_get_nominations_data() {
	return array(
		'service_diocesain'     => array(
			array(
				'source_id'       => 'service-economat',
				'titre'           => __( 'Économat Diocésain', 'diocese-ziguinchor' ),
				'responsable'     => '',
				'membres'         => array(),
				'sous_structures' => array(
					array(
						'nom'         => __( "Conseil d'Administration des Domaines agricoles", 'diocese-ziguinchor' ),
						'responsable' => '',
					),
					array(
						'nom'         => __( "Conseil d'Administration des Unités de Production", 'diocese-ziguinchor' ),
						'responsable' => '',
					),
					array(
						'nom'         => __( "Conseil d'Administration des Instituts d'Enseignement Supérieur", 'diocese-ziguinchor' ),
						'responsable' => '',
					),
					array(
						'nom'         => __( 'Comité d\'étude et de suivi des projets', 'diocese-ziguinchor' ),
						'responsable' => '',
					),
				),
			),
			array(
				'source_id'   => 'service-caritas',
				'titre'       => __( 'Caritas Ziguinchor', 'diocese-ziguinchor' ),
				'responsable' => '',
				'membres'     => array(),
			),
			array(
				'source_id'   => 'service-odec',
				'titre'       => __( 'ODEC', 'diocese-ziguinchor' ),
				'responsable' => '',
				'membres'     => array(),
			),
			array(
				'source_id'   => 'service-apostolat-laics',
				'titre'       => __( 'Apostolat des Laïcs', 'diocese-ziguinchor' ),
				'responsable' => '',
				'membres'     => array(),
			),
			array(
				'source_id'   => 'service-cooperation-missionnaire',
				'titre'       => __( 'Coopération Missionnaire et OPM', 'diocese-ziguinchor' ),
				'responsable' => '',
				'membres'     => array(),
			),
			array(
				'source_id'   => 'service-exorcisme',
				'titre'       => __( 'Exorcisme', 'diocese-ziguinchor' ),
				'responsable' => '',
				'membres'     => array(),
			),
			array(
				'source_id'   => 'service-ceremoniaires',
				'titre'       => __( 'Cérémoniaires diocésains', 'diocese-ziguinchor' ),
				'responsable' => '',
				'membres'     => array(),
			),
			array(
				'source_id'   => 'service-formation-recherche',
				'titre'       => __( 'Formation et Recherche', 'diocese-ziguinchor' ),
				'responsable' => '',
				'membres'     => array(),
			),
			array(
				'source_id'   => 'service-communication',
				'titre'       => __( 'Communication', 'diocese-ziguinchor' ),
				'responsable' => '',
				'membres'     => array(),
			),
			array(
				'source_id'   => 'service-pelerinages',
				'titre'       => __( 'Pèlerinages', 'diocese-ziguinchor' ),
				'responsable' => '',
				'membres'     => array(),
			),
		),
		'commission_diocesaine' => array(
			array(
				'source_id'   => 'commission-catechese',
				'titre'       => __( 'Catéchèse', 'diocese-ziguinchor' ),
				'responsable' => '',
				'membres'     => array(),
			),
			array(
				'source_id'   => 'commission-cellule-ecoute',
				'titre'       => __( "Cellule d'écoute et de sensibilisation", 'diocese-ziguinchor' ),
				'responsable' => '',
				'membres'     => array(),
			),
			array(
				'source_id'   => 'commission-ecologie-integrale',
				'titre'       => __( 'Écologie intégrale', 'diocese-ziguinchor' ),
				'responsable' => '',
				'membres'     => array(),
			),
			array(
				'source_id'   => 'commission-dialogue-oecumenique',
				'titre'       => __( 'Dialogue œcuménique et interreligieux', 'diocese-ziguinchor' ),
				'responsable' => '',
				'membres'     => array(),
			),
			array(
				'source_id'   => 'commission-justice-paix',
				'titre'       => __( 'Justice et Paix', 'diocese-ziguinchor' ),
				'responsable' => '',
				'membres'     => array(),
			),
			array(
				'source_id'   => 'commission-pastorale-famille',
				'titre'       => __( 'Pastorale de la Famille', 'diocese-ziguinchor' ),
				'responsable' => '',
				'membres'     => array(),
			),
			array(
				'source_id'   => 'commission-liturgie',
				'titre'       => __( 'Liturgie', 'diocese-ziguinchor' ),
				'responsable' => '',
				'membres'     => array(),
			),
			array(
				'source_id'   => 'commission-pastorale-sante',
				'titre'       => __( 'Pastorale de la Santé', 'diocese-ziguinchor' ),
				'responsable' => '',
				'membres'     => array(),
			),
			array(
				'source_id'   => 'commission-pastorale-vocations',
				'titre'       => __( 'Pastorale des Vocations', 'diocese-ziguinchor' ),
				'responsable' => '',
				'membres'     => array(),
			),
			array(
				'source_id'   => 'commission-textes-liturgiques-langues',
				'titre'       => __( 'Mise en valeur des textes liturgiques en langues locales', 'diocese-ziguinchor' ),
				'responsable' => '',
				'membres'     => array(),
			),
		),
		'mouvement'             => array(
			array(
				'source_id'   => 'mouvement-coordination-jeunes',
				'titre'       => __( 'Coordination Diocésaine des Jeunes', 'diocese-ziguinchor' ),
				'responsable' => '',
				'membres'     => array(),
				'aumonier'    => '',
			),
			array(
				'source_id'   => 'mouvement-cvav',
				'titre'       => __( 'CV/AV', 'diocese-ziguinchor' ),
				'responsable' => '',
				'membres'     => array(),
				'aumonier'    => '',
			),
			array(
				'source_id'   => 'mouvement-jac-ujrcs-marcs',
				'titre'       => __( 'JAC/UJRCS/MARCS', 'diocese-ziguinchor' ),
				'responsable' => '',
				'membres'     => array(),
				'aumonier'    => '',
			),
			array(
				'source_id'   => 'mouvement-joc',
				'titre'       => __( 'JOC', 'diocese-ziguinchor' ),
				'responsable' => '',
				'membres'     => array(),
				'aumonier'    => '',
			),
			array(
				'source_id'   => 'mouvement-jec',
				'titre'       => __( 'JEC', 'diocese-ziguinchor' ),
				'responsable' => '',
				'membres'     => array(),
				'aumonier'    => '',
			),
			array(
				'source_id'   => 'mouvement-scouts-guides',
				'titre'       => __( 'Scouts et Guides', 'diocese-ziguinchor' ),
				'responsable' => '',
				'membres'     => array(),
				'aumonier'    => '',
			),
		),
		'association'           => array(
			array(
				'source_id'   => 'association-udafcz',
				'titre'       => __( 'UDAFC/Z', 'diocese-ziguinchor' ),
				'responsable' => '',
				'membres'     => array(),
			),
			array(
				'source_id'   => 'association-legion-de-marie',
				'titre'       => __( 'Légion de Marie', 'diocese-ziguinchor' ),
				'responsable' => '',
				'membres'     => array(),
			),
			array(
				'source_id'   => 'association-coordination-chorales',
				'titre'       => __( 'Coordination Diocésaine des Chorales', 'diocese-ziguinchor' ),
				'responsable' => '',
				'membres'     => array(),
			),
			array(
				'source_id'   => 'association-renouveau-charismatique',
				'titre'       => __( 'Comité Diocésain du Renouveau Charismatique', 'diocese-ziguinchor' ),
				'responsable' => '',
				'membres'     => array(),
			),
			array(
				'source_id'   => 'association-vie-montante',
				'titre'       => __( 'Vie Montante', 'diocese-ziguinchor' ),
				'responsable' => '',
				'membres'     => array(),
			),
			array(
				'source_id'   => 'association-equipes-enseignantes',
				'titre'       => __( 'Équipes Enseignantes', 'diocese-ziguinchor' ),
				'responsable' => '',
				'membres'     => array(),
			),
			array(
				'source_id'   => 'association-forces-defense-securite',
				'titre'       => __( 'Forces de Défense et de Sécurité', 'diocese-ziguinchor' ),
				'responsable' => '',
				'membres'     => array(),
			),
		),
	);
}
