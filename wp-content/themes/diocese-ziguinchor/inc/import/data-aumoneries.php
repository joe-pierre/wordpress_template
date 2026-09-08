<?php
/**
 * Chaplaincies (`aumonerie`) and diocesan establishments (`etablissement`)
 * data — plain data, no logic (CONTENT_PROMPTS.md PROMPT 4). Transcribed
 * from NOMINATIONS_SERVICES_COMMISSIONS_AUMONERIES_2027.pdf, sections
 * V-VIII (now in documents/, an unOCR'd scan — read directly, same method
 * as PROMPT 3bis, see DECISIONS.md), cross-referenced against
 * documents/Arborescence PDF.pdf's "6. Enseignements diocésains" rubric
 * (DIDEC, Séminaires et Maisons de formation, Collèges Diocésains,
 * Enseignement Supérieur).
 *
 * `aumonerie` entries follow the same shape as data-nominations.php
 * (source_id, titre, responsable, membres, optional note), plus
 * `type_aumonerie` (one of the 4 term slugs already seeded by
 * dz_seed_type_aumonerie_terms(), inc/cpt-aumonerie.php: scolaire,
 * universitaire, sante, carcerale). Where the circular names no individual
 * aumônier (a collective "NB: équipe pastorale du lieu" note instead),
 * `responsable`/`membres` stay empty and the note explains why.
 *
 * `etablissement` has only ONE entry here (ISPS) — see DECISIONS.md
 * "Aumôneries et établissements (PROMPT 4)" for the full reasoning: DIDEC
 * turns out to be Sœur Rose Mama DIOUF's own title within the already-
 * existing ODEC (service_diocesain) entry, not a separate institution;
 * Séminaires et Maisons de formation has no named institution anywhere in
 * the source documents available; Collèges Diocésains (Collège Saint
 * Charles Lwanga/Lycée Saint Éloi, Collège Sacré-Cœur) and UCAO/UUZ/ISCG
 * already have their own `aumonerie` entry below and are deliberately not
 * duplicated into `etablissement` — only ISPS is both diocesan-administered
 * (see the Économat's "Conseil d'Administration des Instituts
 * d'Enseignement Supérieur" sous-structure, inc/import/data-nominations.php)
 * and has no `aumonerie` entry of its own in the circular, so it alone
 * becomes an `etablissement` post here.
 *
 * @return array{
 *     aumonerie: array<int,array{
 *         source_id: string,
 *         titre: string,
 *         type_aumonerie: string,
 *         responsable: string,
 *         membres: array<int,array{nom:string,role:string}>,
 *         note?: string,
 *     }>,
 *     etablissement: array<int,array{
 *         source_id: string,
 *         titre: string,
 *         type_etablissement: string,
 *         contact: string,
 *         responsable: string,
 *         membres: array<int,array{nom:string,role:string}>,
 *         note?: string,
 *     }>,
 * }
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dz_import_get_aumoneries_data() {
	return array(
		'aumonerie'     => array(
			// V. Les Aumôneries Scolaires.
			array(
				'source_id'      => 'aumonerie-college-lwanga-eloi',
				'titre'          => __( 'Collège Saint Charles Lwanga et Lycée Saint Éloi', 'diocese-ziguinchor' ),
				'type_aumonerie' => 'scolaire',
				'responsable'    => __( 'Raoul DIATTA (Séminariste stagiaire, Aumônier)', 'diocese-ziguinchor' ),
				'membres'        => array(
					array( 'nom' => __( "Sœur Jeanne d'Arc B. DIATTA", 'diocese-ziguinchor' ), 'role' => __( 'FSCM, Conseillère', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Sœur Aimée DAÏSSALA WAÏTCHARI', 'diocese-ziguinchor' ), 'role' => __( 'ISJ, Conseillère', 'diocese-ziguinchor' ) ),
				),
			),
			array(
				'source_id'      => 'aumonerie-college-sacre-coeur',
				'titre'          => __( 'Collège Sacré-Cœur', 'diocese-ziguinchor' ),
				'type_aumonerie' => 'scolaire',
				'responsable'    => __( 'Abbé Luc André DIATTA (Aumônier)', 'diocese-ziguinchor' ),
				'membres'        => array(),
			),
			array(
				'source_id'      => 'aumonerie-amicale-ste-therese',
				'titre'          => __( 'Amicale Sainte Thérèse du Lycée de Djibock et Foyer Sainte Paula Montal', 'diocese-ziguinchor' ),
				'type_aumonerie' => 'scolaire',
				'responsable'    => '',
				'membres'        => array(),
				'note'           => __( "L'Équipe Pastorale de Tilène en assure l'aumônerie en collaboration avec les religieuses en service dans la paroisse (aucun aumônier nommé individuellement dans la circulaire).", 'diocese-ziguinchor' ),
			),
			// VI. Les Aumôneries des Universités et des Instituts Supérieurs.
			array(
				'source_id'      => 'aumonerie-coordination-universitaires',
				'titre'          => __( 'Coordination des Aumôneries Universitaires', 'diocese-ziguinchor' ),
				'type_aumonerie' => 'universitaire',
				'responsable'    => __( 'Abbé Jacques Aimé SAGNA (Aumônier)', 'diocese-ziguinchor' ),
				'membres'        => array(
					array( 'nom' => __( 'Sœur Aimée DAÏSSALA WAÏTCHARI', 'diocese-ziguinchor' ), 'role' => __( 'ISJ, Conseillère', 'diocese-ziguinchor' ) ),
				),
			),
			array(
				'source_id'      => 'aumonerie-ucao-uuz-iscg',
				'titre'          => __( 'UCAO/UUZ/ISCG Mgr Maixent COLY', 'diocese-ziguinchor' ),
				'type_aumonerie' => 'universitaire',
				'responsable'    => __( 'Abbé Eugène Adigar DIATTA (Aumônier)', 'diocese-ziguinchor' ),
				'membres'        => array(
					array( 'nom' => __( 'Sœur Aimée DAÏSSALA WAÏTCHARI', 'diocese-ziguinchor' ), 'role' => __( 'ISJ, Conseillère', 'diocese-ziguinchor' ) ),
				),
				'note'           => __( "Établissement administré par le Conseil d'Administration des Instituts d'Enseignement Supérieur de l'Économat Diocésain (voir la fiche Économat Diocésain, service_diocesain) — Abbé Samson Delaka KANTOUSSAN y est Directeur de l'ISCG. Pas de fiche etablissement séparée pour ne pas dupliquer celle-ci, voir DECISIONS.md.", 'diocese-ziguinchor' ),
			),
			array(
				'source_id'      => 'aumonerie-uasz',
				'titre'          => __( "Université d'État Assane Seck (UASZ)", 'diocese-ziguinchor' ),
				'type_aumonerie' => 'universitaire',
				'responsable'    => __( 'Abbé Jacques Aimé SAGNA (Aumônier)', 'diocese-ziguinchor' ),
				'membres'        => array(
					array( 'nom' => __( 'Sœur Aimée DAÏSSALA WAÏTCHARI', 'diocese-ziguinchor' ), 'role' => __( 'ISJ, Conseillère', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Sœur Marinette Sonia Marie DIEME', 'diocese-ziguinchor' ), 'role' => __( 'FSCM, Conseillère', 'diocese-ziguinchor' ) ),
				),
			),
			array(
				'source_id'      => 'aumonerie-ism',
				'titre'          => __( 'Institut Supérieur de Management (ISM)', 'diocese-ziguinchor' ),
				'type_aumonerie' => 'universitaire',
				'responsable'    => __( 'Abbé Jean Gabriel SAMBOU (Aumônier)', 'diocese-ziguinchor' ),
				'membres'        => array(
					array( 'nom' => __( 'Sœur Eunice Marina Silva SEIDI', 'diocese-ziguinchor' ), 'role' => __( 'FMSS, Conseillère', 'diocese-ziguinchor' ) ),
				),
			),
			array(
				'source_id'      => 'aumonerie-uvs-ziguinchor',
				'titre'          => __( 'Université Virtuelle du Sénégal (UVS Ziguinchor)', 'diocese-ziguinchor' ),
				'type_aumonerie' => 'universitaire',
				'responsable'    => __( 'Abbé Jean Gabriel SAMBOU (Aumônier)', 'diocese-ziguinchor' ),
				'membres'        => array(
					array( 'nom' => __( 'Sœur Yolande Georgette Marie Awa TINE', 'diocese-ziguinchor' ), 'role' => __( 'FSCM, Conseillère', 'diocese-ziguinchor' ) ),
				),
			),
			array(
				'source_id'      => 'aumonerie-uvs-bignona',
				'titre'          => __( 'Université Virtuelle du Sénégal (UVS Bignona) + BTS Agricole', 'diocese-ziguinchor' ),
				'type_aumonerie' => 'universitaire',
				'responsable'    => __( 'Abbé Yves NDOUR (Aumônier)', 'diocese-ziguinchor' ),
				'membres'        => array(),
			),
			array(
				'source_id'      => 'aumonerie-iseg',
				'titre'          => __( "Institut Supérieur d'Entreprenariat et de Gestion (ISEG)", 'diocese-ziguinchor' ),
				'type_aumonerie' => 'universitaire',
				'responsable'    => __( 'Abbé Jacques Aimé SAGNA (Aumônier)', 'diocese-ziguinchor' ),
				'membres'        => array(
					array( 'nom' => __( 'Sœur Aimée DAÏSSALA WAÏTCHARI', 'diocese-ziguinchor' ), 'role' => __( 'ISJ, Conseillère', 'diocese-ziguinchor' ) ),
				),
			),
			array(
				'source_id'      => 'aumonerie-iss',
				'titre'          => __( 'Institut Santé Service (ISS)', 'diocese-ziguinchor' ),
				'type_aumonerie' => 'universitaire',
				'responsable'    => __( 'Abbé Jacques Aimé SAGNA (Aumônier)', 'diocese-ziguinchor' ),
				'membres'        => array(
					array( 'nom' => __( 'Sœur Lotchio Marie Louise KROU', 'diocese-ziguinchor' ), 'role' => __( 'ISJ, Conseillère', 'diocese-ziguinchor' ) ),
				),
			),
			// VII. Les Aumôneries des Établissements de Santé.
			array(
				'source_id'      => 'aumonerie-hopital-paix',
				'titre'          => __( 'Hôpital de la Paix et District Sanitaire', 'diocese-ziguinchor' ),
				'type_aumonerie' => 'sante',
				'responsable'    => __( 'Abbé Philippe MANGA (Aumônier)', 'diocese-ziguinchor' ),
				'membres'        => array(
					array( 'nom' => __( 'Sœur Rose Mireille DIEME', 'diocese-ziguinchor' ), 'role' => __( 'FSCM, Conseillère', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Sœur Jessica KANTOUSSAN', 'diocese-ziguinchor' ), 'role' => __( 'FSCM, Conseillère', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Mme Martine AGBOTON', 'diocese-ziguinchor' ), 'role' => __( 'Conseillère', 'diocese-ziguinchor' ) ),
				),
			),
			array(
				'source_id'      => 'aumonerie-hopital-regional',
				'titre'          => __( 'Hôpital Régional', 'diocese-ziguinchor' ),
				'type_aumonerie' => 'sante',
				'responsable'    => __( 'Abbé Patrice DIATTA (Aumônier)', 'diocese-ziguinchor' ),
				'membres'        => array(
					array( 'nom' => __( 'Sœur Cécile Fabiana NDECKY', 'diocese-ziguinchor' ), 'role' => __( 'IFRZ, Conseillère', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Sœur Pauline MENDY', 'diocese-ziguinchor' ), 'role' => __( 'IFRZ, Conseillère', 'diocese-ziguinchor' ) ),
				),
			),
			array(
				'source_id'      => 'aumonerie-coordination-agents-sante',
				'titre'          => __( 'Coordination Diocésaine des Agents de Santé Catholiques', 'diocese-ziguinchor' ),
				'type_aumonerie' => 'sante',
				'responsable'    => __( 'Abbé Patrice DIATTA (Aumônier)', 'diocese-ziguinchor' ),
				'membres'        => array(
					array( 'nom' => __( 'Sœur Cécile Fabiana NDECKY', 'diocese-ziguinchor' ), 'role' => __( 'IFRZ, Conseillère', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Dr Marc Anibo MANGA', 'diocese-ziguinchor' ), 'role' => __( 'HPZ, Président du bureau', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Jacques François SAMBOU', 'diocese-ziguinchor' ), 'role' => __( 'HRZ, Vice-président du bureau', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Dr Paulin SAMBOU', 'diocese-ziguinchor' ), 'role' => __( 'HPZ, Secrétaire Général du bureau', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Joelle BANDAGNY', 'diocese-ziguinchor' ), 'role' => __( 'HPZ, Trésorière du bureau', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Geneviève DIOMPY', 'diocese-ziguinchor' ), 'role' => __( 'HPZ, Commission organisation', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Léon DIEDHIOU', 'diocese-ziguinchor' ), 'role' => __( 'HRZ, Commission liturgique', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Hyacinthe DIATTA', 'diocese-ziguinchor' ), 'role' => __( 'HRZ, Relations extérieures et communication', 'diocese-ziguinchor' ) ),
				),
			),
			array(
				'source_id'      => 'aumonerie-sida-service',
				'titre'          => __( 'ONG Santé, Service et Développement (SIDA Service)', 'diocese-ziguinchor' ),
				'type_aumonerie' => 'sante',
				'responsable'    => __( 'Dr Abbé Michel MENDY (Aumônier)', 'diocese-ziguinchor' ),
				'membres'        => array(
					array( 'nom' => __( 'Sœur Cécile DIATTA', 'diocese-ziguinchor' ), 'role' => __( 'JS, Conseillère', 'diocese-ziguinchor' ) ),
				),
			),
			array(
				'source_id'      => 'aumonerie-autre-centre-medical',
				'titre'          => __( 'Tout Autre Centre Médical', 'diocese-ziguinchor' ),
				'type_aumonerie' => 'sante',
				'responsable'    => '',
				'membres'        => array(),
				'note'           => __( "L'aumônerie de tout autre centre médical est assurée par l'équipe pastorale du lieu d'implantation (aucun aumônier nommé individuellement dans la circulaire).", 'diocese-ziguinchor' ),
			),
			// VIII. Les Aumôneries des Maisons d'Arrêt.
			array(
				'source_id'      => 'aumonerie-maison-arret-ziguinchor',
				'titre'          => __( "Maison d'Arrêt de Ziguinchor", 'diocese-ziguinchor' ),
				'type_aumonerie' => 'carcerale',
				'responsable'    => __( 'Abbé Charles Bernard COLY (Aumônier)', 'diocese-ziguinchor' ),
				'membres'        => array(
					array( 'nom' => __( 'Frère Matthieu CABO', 'diocese-ziguinchor' ), 'role' => __( 'SC, Conseiller', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Sœur Hortense TENDENG', 'diocese-ziguinchor' ), 'role' => __( 'FSCM, Conseillère', 'diocese-ziguinchor' ) ),
				),
			),
			array(
				'source_id'      => 'aumonerie-maisons-arret-bignona-oussouye',
				'titre'          => __( "Maisons d'Arrêt de Bignona et Oussouye", 'diocese-ziguinchor' ),
				'type_aumonerie' => 'carcerale',
				'responsable'    => '',
				'membres'        => array(),
				'note'           => __( "L'aumônerie est assurée par les équipes pastorales du lieu d'implantation (aucun aumônier nommé individuellement dans la circulaire).", 'diocese-ziguinchor' ),
			),
		),
		'etablissement' => array(
			array(
				'source_id'           => 'etablissement-isps',
				'titre'               => __( 'Institut Supérieur de Promotion de la Santé (ISPS)', 'diocese-ziguinchor' ),
				'type_etablissement'  => 'enseignement_superieur',
				'contact'             => '',
				'responsable'         => __( 'Dr Abbé Michel MENDY (Directeur Général)', 'diocese-ziguinchor' ),
				'membres'             => array(
					array( 'nom' => __( 'M. Louis BASSENE', 'diocese-ziguinchor' ), 'role' => __( 'Directeur des Études et Chef de la scolarité', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Mme SAGNA Jeanne Odette SAMBOU', 'diocese-ziguinchor' ), 'role' => __( "Conseillère pédagogique et Chargée de programme de la filière Sage-femme d'État", 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Mme Rose Gnaba SAMBOU', 'diocese-ziguinchor' ), 'role' => __( "Surveillante Générale et Responsable de la filière Infirmier d'état", 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Mme DRAME Khoudje GOUNDIAM', 'diocese-ziguinchor' ), 'role' => __( 'Responsable de la délégation médicale', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Mme Viviane A. COLY', 'diocese-ziguinchor' ), 'role' => __( 'Comptable', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'M. Denis DIASSY', 'diocese-ziguinchor' ), 'role' => __( 'Assistant du Chef de la scolarité et Responsable informatique', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Abbé Victor Bossé SAGNA', 'diocese-ziguinchor' ), 'role' => __( 'Aumônier', 'diocese-ziguinchor' ) ),
				),
				'note'                => __( "Établissement administré par le Conseil d'Administration des Instituts d'Enseignement Supérieur de l'Économat Diocésain (voir la fiche Économat Diocésain, service_diocesain). Pas de fiche aumonerie séparée pour cet établissement dans la circulaire — c'est ce qui distingue ISPS d'UCAO/UUZ/ISCG (qui en a une, voir DECISIONS.md).", 'diocese-ziguinchor' ),
			),
		),
	);
}
