<?php
/**
 * Nominations circular data — plain data, no logic (CONTENT_PROMPTS.md
 * PROMPT 0/3/3bis). Transcribed from NOMINATIONS_SERVICES_COMMISSIONS_
 * AUMONERIES_2027.pdf (Circulaire n°002/2026-2027, faite à Ziguinchor le
 * 1er septembre 2026, signée Mgr Jean Baptiste Valter MANGA), sections I-IV
 * — real names transcribed directly into the CONTENT_PROMPTS.md PROMPT 3bis
 * prompt text itself (the PDF file is still not present in this
 * repository, but its content was provided verbatim for this transcription
 * — see DECISIONS.md).
 *
 * `responsable`/`membres` follow the general rule: first person listed (with
 * their role) = responsable, everyone else = membres with their exact role
 * as written in the circular. Where the circular only gives a collective/
 * general mention (e.g. "tous les aumôniers décanaux") without naming
 * anyone, that goes into `note` (free text, becomes the post's own
 * the_content()) rather than being force-fit into the membres repeater.
 *
 * `service_diocesain`'s Économat carries a 2-level structure in the
 * circular (4 conseils/comités directly under it, themselves with a
 * President + members, and — for 2 of the 4 — further entities rattachées
 * below that). `service_diocesain_sous_structures` only has 2 sub-fields
 * (nom, responsable, see acf-json/group_dz_cpt_service_diocesain.json) —
 * deliberately not extended to a 3rd repeater level for 3-4 rattached
 * entities (see DECISIONS.md): the "responsable" text of each sous-structure
 * folds in its full membership and, where relevant, its own entités
 * rattachées as plain text.
 *
 * `mouvement`'s `aumonier` is matched against the `pretre` CPT at import
 * time (dz_import_find_pretre_by_title(), inc/import/import-tools.php) —
 * kept here as the chaplain's plain name, not a resolved relation id.
 *
 * @return array<string,array<int,array{
 *     source_id: string,
 *     titre: string,
 *     responsable: string,
 *     membres: array<int,array{nom:string,role:string}>,
 *     sous_structures?: array<int,array{nom:string,responsable:string}>,
 *     aumonier?: string,
 *     note?: string,
 * }>>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dz_import_get_nominations_data() {
	return array(
		'service_diocesain'     => array(
			array(
				'source_id'   => 'service-economat',
				'titre'       => __( 'Économat Diocésain', 'diocese-ziguinchor' ),
				'responsable' => __( 'Abbé Albert TENDENG (Économe diocésain)', 'diocese-ziguinchor' ),
				'membres'     => array(
					array(
						'nom'  => __( 'Rév. Mgr Fulgence COLY', 'diocese-ziguinchor' ),
						'role' => __( 'Vicaire Général, chargé du Temporel et de la Pastorale sociale', 'diocese-ziguinchor' ),
					),
					array(
						'nom'  => __( 'M. René Lamine DIEDHIOU', 'diocese-ziguinchor' ),
						'role' => __( 'Comptable', 'diocese-ziguinchor' ),
					),
					array(
						'nom'  => __( 'M. Pierre Anaw SAMBOU', 'diocese-ziguinchor' ),
						'role' => __( 'Secrétaire', 'diocese-ziguinchor' ),
					),
				),
				'sous_structures' => array(
					array(
						'nom'         => __( "Conseil d'Administration des Domaines agricoles", 'diocese-ziguinchor' ),
						'responsable' => __( "Président M. Benoît SAMBOU (Ancien Ministre d'État), avec M. Paterne DIATTA (Agronome), M. Casimir Adrien SAMBOU (Agronome), Abbé Prosper TENDENG, Sœur Elisa DIATTA (IFRZ). Rattachés : C.P.R.A. d'Affiniam (Abbé Potin BADIANE, Chargé d'exploitation), Ferme École de Djibélor (Abbé Alfred TENDENG, Directeur ; Abbé Paul Ignace TENDENG, Adjoint ; Abbé René Pierre COLY, Collaborateur).", 'diocese-ziguinchor' ),
					),
					array(
						'nom'         => __( "Conseil d'Administration des Unités de Production", 'diocese-ziguinchor' ),
						'responsable' => __( 'Président M. Habib Ampa DIENG (Inspecteur des Douanes), avec Abbé Adrien Dominique BADIANE (Directeur de Caritas), Abbé Albert TENDENG (Économe diocésain), Mme Marie Louise FAYE (Inspectrice du Tourisme), M. Emmanuel BADJI (Comptable), M. Eugène NDIAYE (Entrepreneur), Mme FAURE Marise Françoise Awai TENDENG (Assistante de direction). Rattachés : Hôtel Carabane (M. Gabriel COLY, Gérant), Librairie Papeterie Djibékel (M. Raymond SAGNA, Gérant), Imprimerie du Sud/ex Néma (M. Fally SAMB, Directeur).', 'diocese-ziguinchor' ),
					),
					array(
						'nom'         => __( "Conseil d'Administration des Instituts d'Enseignement Supérieur", 'diocese-ziguinchor' ),
						'responsable' => __( "Président Pr Salomon SAMBOU (UASZ), avec Pr Melyan MENDY (UASZ), Pr Alexandre DIATTA (UASZ), Dr Alain Christian BASSENE (UCAD), Dr Marie Clémence FAYE MENDY (Ophtalmologue), Pr Noël Magloire MANGA (Infectiologue), M. Paulin NZALE (Spécialiste en passation de marchés publics). Rattachés : UCAO/UUZ/ISCG, Mgr Maixent COLY (Abbé Samson Delaka KANTOUSSAN, Directeur de l'ISCG ; Abbé Eugène Adigar DIATTA, Aumônier ; Sœur Aimée DAÏSSALA WAÏTCHARI, ISJ, Conseillère), Institut Supérieur de Promotion de la Santé/ISPS (Dr Abbé Michel MENDY, Directeur Général ; M. Louis BASSENE, Directeur des Études et Chef de la scolarité ; Mme SAGNA Jeanne Odette SAMBOU, Conseillère pédagogique et Chargée de programme de la filière Sage-femme d'État ; Mme Rose Gnaba SAMBOU, Surveillante Générale et Responsable de la filière Infirmier d'état ; Mme DRAME Khoudje GOUNDIAM, Responsable de la délégation médicale ; Mme Viviane A. COLY, Comptable ; M. Denis DIASSY, Assistant du Chef de la scolarité et Responsable informatique ; Abbé Victor Bossé SAGNA, Aumônier).", 'diocese-ziguinchor' ),
					),
					array(
						'nom'         => __( "Comité Diocésain d'Étude et de Suivi des Projets", 'diocese-ziguinchor' ),
						'responsable' => __( 'Président Rév. Mgr Fulgence COLY, Secrétaire Frère Matthieu CABO (S.C.), avec Abbé Albert TENDENG, Abbé Faustin DIEME, Abbé Adrien Dominique BADIANE, Abbé Jean Pierre Amaye TENDENG (Chancelier diocésain), M. Paterne DIATTA, Mme Marie Angèle DIATTA.', 'diocese-ziguinchor' ),
					),
				),
			),
			array(
				'source_id'   => 'service-caritas',
				'titre'       => __( 'Caritas Ziguinchor', 'diocese-ziguinchor' ),
				'responsable' => __( 'Abbé Adrien Dominique BADIANE (Directeur, Administration Centrale)', 'diocese-ziguinchor' ),
				'membres'     => array(
					array( 'nom' => __( 'Mgr Fulgence COLY', 'diocese-ziguinchor' ), 'role' => __( 'Conseil de Gestion', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Abbé Samson D. KANTOUSSAN', 'diocese-ziguinchor' ), 'role' => __( 'Vicaire épiscopal, Conseil de Gestion', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Abbé Albert TENDENG', 'diocese-ziguinchor' ), 'role' => __( 'Conseil de Gestion', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Dr Prosper DIEDHIOU', 'diocese-ziguinchor' ), 'role' => __( 'Président sortant, Conseil de Gestion', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'M. André Florent BASSENE', 'diocese-ziguinchor' ), 'role' => __( 'Conseil de Gestion', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Abbé Jean Pierre Amaye TENDENG', 'diocese-ziguinchor' ), 'role' => __( 'Conseil de Gestion', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'M. Alphonse DIEDHIOU', 'diocese-ziguinchor' ), 'role' => __( 'Chef de Bureau, Administration Centrale', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'M. Eusébio DASYLVA', 'diocese-ziguinchor' ), 'role' => __( 'Responsable du Service Développement, Administration Centrale ; Point focal, Référent et Spécialiste en Environnement et Droit Humanitaire', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Mme Jeanne Marie SENGHOR RAF', 'diocese-ziguinchor' ), 'role' => __( 'Responsable du Service Finance, Administration Centrale', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Mme Louise FAYE', 'diocese-ziguinchor' ), 'role' => __( 'Chargée du Personnel, Administration Centrale', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Abbé Joseph TENDENG', 'diocese-ziguinchor' ), 'role' => __( 'Aumônier et Responsable du Service Urgence et Socio Pastoral, Administration Centrale', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Dr Abbé Michel MENDY', 'diocese-ziguinchor' ), 'role' => __( 'Référent Caritas Santé, Administration Centrale', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Mme Ludivine Gabrielle DIEME', 'diocese-ziguinchor' ), 'role' => __( 'Assistante de Direction et Chargée de la communication interne, Appui institutionnel', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'M. Degolle MENDY', 'diocese-ziguinchor' ), 'role' => __( "Chargé du suivi évaluation et du partenariat avec la fondation Jean Paul II pour le Sahel, Appui institutionnel ; Point focal, Référent et Spécialiste en Sauvegarde et des questions liées à la migration", 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'M. Athanase DIATTA', 'diocese-ziguinchor' ), 'role' => __( 'Point focal, Référent et Spécialiste en Agroécologie', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'M. Antoine DIEDHIOU', 'diocese-ziguinchor' ), 'role' => __( 'Point focal, Référent et Spécialiste en Agroécologie, Foresterie, Changement Climatique', 'diocese-ziguinchor' ) ),
				),
				'note'        => __( 'Le poste de Responsable des Exploitations (Administration Centrale) reste à nommer.', 'diocese-ziguinchor' ),
			),
			array(
				'source_id'   => 'service-odec',
				'titre'       => __( "Office Diocésain de l'Enseignement Catholique (ODEC)", 'diocese-ziguinchor' ),
				'responsable' => __( 'Sœur Rose Mama DIOUF (IFRZ, DIDEC et Déclarant Responsable)', 'diocese-ziguinchor' ),
				'membres'     => array(
					array( 'nom' => __( 'M. Abraham SENGHOR', 'diocese-ziguinchor' ), 'role' => __( 'Conseiller pédagogique', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'M. Jean-Noël DIOUF', 'diocese-ziguinchor' ), 'role' => __( 'Comptable', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Mme Léocadie COLY', 'diocese-ziguinchor' ), 'role' => __( 'RH', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( "Mme Jeanne d'Arc MANGA", 'diocese-ziguinchor' ), 'role' => __( 'Secrétaire', 'diocese-ziguinchor' ) ),
				),
			),
			array(
				'source_id'   => 'service-apostolat-laics',
				'titre'       => __( 'Apostolat des Laïcs – Direction des Œuvres Catholiques', 'diocese-ziguinchor' ),
				'responsable' => __( 'Abbé Djimoreu Antoine Alain BADIANE', 'diocese-ziguinchor' ),
				'membres'     => array(
					array( 'nom' => __( 'Mme Gilberta DIANDY', 'diocese-ziguinchor' ), 'role' => __( 'Secrétaire', 'diocese-ziguinchor' ) ),
				),
				'note'        => __( 'Tous les prêtres aumôniers et sœurs conseillères diocésains et décanaux sont membres de droit de ce service (mention générale de la circulaire).', 'diocese-ziguinchor' ),
			),
			array(
				'source_id'   => 'service-cooperation-missionnaire',
				'titre'       => __( 'Coopération Missionnaire et OPM', 'diocese-ziguinchor' ),
				'responsable' => __( 'Abbé Faustin DIEME', 'diocese-ziguinchor' ),
				'membres'     => array(
					array( 'nom' => __( 'Abbé Achille DJIHOUNOUCK', 'diocese-ziguinchor' ), 'role' => __( 'Adjoint', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Abbé Auguste Oscar J. SAMBOU', 'diocese-ziguinchor' ), 'role' => __( 'Adjoint, Délégué épiscopal Prêtres Fidei Donum en France', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Père Édouard DIEDHIOU', 'diocese-ziguinchor' ), 'role' => __( 'Adjoint (SCH.P.)', 'diocese-ziguinchor' ) ),
				),
			),
			array(
				'source_id'   => 'service-exorcisme',
				'titre'       => __( 'Exorcisme', 'diocese-ziguinchor' ),
				'responsable' => __( 'Abbé Albert DIATTA (en charge des Doyennés de Ziguinchor et Brin)', 'diocese-ziguinchor' ),
				'membres'     => array(),
			),
			array(
				'source_id'   => 'service-ceremoniaires',
				'titre'       => __( 'Cérémoniaires diocésains', 'diocese-ziguinchor' ),
				'responsable' => __( 'Abbé Alain Samor SAGNA (Cérémoniaire diocésain)', 'diocese-ziguinchor' ),
				'membres'     => array(
					array( 'nom' => __( 'Abbé Jean Pierre Amaye TENDENG', 'diocese-ziguinchor' ), 'role' => __( 'Adjoint', 'diocese-ziguinchor' ) ),
				),
			),
			array(
				'source_id'   => 'service-formation-recherche',
				'titre'       => __( 'Formation et Recherche', 'diocese-ziguinchor' ),
				'responsable' => __( 'Abbé Xavier NGANDOUL', 'diocese-ziguinchor' ),
				'membres'     => array(
					array( 'nom' => __( 'Abbé Christian A. SAGNA', 'diocese-ziguinchor' ), 'role' => '' ),
					array( 'nom' => __( 'Abbé Prosper TENDENG', 'diocese-ziguinchor' ), 'role' => '' ),
				),
			),
			array(
				'source_id'   => 'service-communication',
				'titre'       => __( 'Communication', 'diocese-ziguinchor' ),
				'responsable' => __( 'Abbé Jacques Aimé SAGNA', 'diocese-ziguinchor' ),
				'membres'     => array(),
				'note'        => __( 'Une équipe de prêtres, religieux(ses) et fidèles laïcs communicateurs accompagne ce service (mention générale de la circulaire, sans liste nominative).', 'diocese-ziguinchor' ),
			),
			array(
				'source_id'   => 'service-pelerinages',
				'titre'       => __( 'Pèlerinages', 'diocese-ziguinchor' ),
				'responsable' => __( 'Abbé Djimoreu Antoine Alain BADIANE (Pèlerinages Diocésains, interdiocésains et nationaux)', 'diocese-ziguinchor' ),
				'membres'     => array(
					array( 'nom' => __( 'Mme Marie Clémence FAYE MENDY', 'diocese-ziguinchor' ), 'role' => __( 'Déléguée CINPEC', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'M. Matthieu SAGNA', 'diocese-ziguinchor' ), 'role' => __( 'Délégué CINPEC', 'diocese-ziguinchor' ) ),
				),
			),
		),
		'commission_diocesaine' => array(
			array(
				'source_id'   => 'commission-catechese',
				'titre'       => __( 'Catéchèse', 'diocese-ziguinchor' ),
				'responsable' => __( 'Abbé Prosper TENDENG', 'diocese-ziguinchor' ),
				'membres'     => array(
					array( 'nom' => __( 'Sœur Nadine Ayosso MANGA', 'diocese-ziguinchor' ), 'role' => __( 'IFRZ', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'M. Charles Raoul SAGNA', 'diocese-ziguinchor' ), 'role' => __( 'Président du Bureau diocésain des catéchistes', 'diocese-ziguinchor' ) ),
				),
				'note'        => __( 'Les aumôniers décanaux de la catéchèse sont membres de droit de cette commission (mention générale de la circulaire).', 'diocese-ziguinchor' ),
			),
			array(
				'source_id'   => 'commission-cellule-ecoute',
				'titre'       => __( "Cellule d'écoute et de sensibilisation", 'diocese-ziguinchor' ),
				'responsable' => __( 'Abbé Xavier NGANDOUL', 'diocese-ziguinchor' ),
				'membres'     => array(
					array( 'nom' => __( 'Dr Abbé Michel MENDY', 'diocese-ziguinchor' ), 'role' => '' ),
					array( 'nom' => __( 'Dr Sébastien DIEME', 'diocese-ziguinchor' ), 'role' => '' ),
					array( 'nom' => __( 'Sœur Cécile Fabiana NDECKY', 'diocese-ziguinchor' ), 'role' => __( 'IFRZ', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Sœur Concha Carmen DIATTA', 'diocese-ziguinchor' ), 'role' => __( 'Piariste', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Mme SAGNA Jeanne Odette SAMBOU', 'diocese-ziguinchor' ), 'role' => '' ),
				),
			),
			array(
				'source_id'   => 'commission-ecologie-integrale',
				'titre'       => __( 'Écologie intégrale', 'diocese-ziguinchor' ),
				'responsable' => __( 'M. Eusébio DASYLVA', 'diocese-ziguinchor' ),
				'membres'     => array(
					array( 'nom' => __( 'Abbé Djimoreu Antoine Alain BADIANE', 'diocese-ziguinchor' ), 'role' => '' ),
					array( 'nom' => __( 'Sœur Rose Mama DIOUF', 'diocese-ziguinchor' ), 'role' => __( 'IFRZ', 'diocese-ziguinchor' ) ),
				),
				'note'        => __( 'Membres de droit : le Commissaire Régional Scout et la Commissaire Régionale Guide (fonctions citées sans nom dans la circulaire).', 'diocese-ziguinchor' ),
			),
			array(
				'source_id'   => 'commission-dialogue-oecumenique',
				'titre'       => __( 'Dialogue Œcuménique et Interreligieux', 'diocese-ziguinchor' ),
				'responsable' => __( 'Abbé Samson Delaka KANTOUSSAN (Dialogue avec la Religion Traditionnelle Africaine)', 'diocese-ziguinchor' ),
				'membres'     => array(
					array( 'nom' => __( 'Abbé Jean Augustin SAMBOU', 'diocese-ziguinchor' ), 'role' => __( 'Dialogue Œcuménique', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Abbé Célestin SAGNA', 'diocese-ziguinchor' ), 'role' => __( 'Dialogue Islamo-Chrétien', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Abbé Paul DIATTA', 'diocese-ziguinchor' ), 'role' => '' ),
					array( 'nom' => __( 'Abbé Joël Cheikh COLY', 'diocese-ziguinchor' ), 'role' => '' ),
				),
			),
			array(
				'source_id'   => 'commission-justice-paix',
				'titre'       => __( 'Justice et Paix', 'diocese-ziguinchor' ),
				'responsable' => __( 'Abbé Camille Joseph GOMIS', 'diocese-ziguinchor' ),
				'membres'     => array(
					array( 'nom' => __( 'Sœur Marguerite COLY', 'diocese-ziguinchor' ), 'role' => __( 'IFRZ, Adjointe', 'diocese-ziguinchor' ) ),
				),
			),
			array(
				'source_id'   => 'commission-pastorale-famille',
				'titre'       => __( 'Pastorale de la Famille', 'diocese-ziguinchor' ),
				'responsable' => __( 'Abbé Marius MANGA', 'diocese-ziguinchor' ),
				'membres'     => array(
					array( 'nom' => __( 'Sœur Angèle Marie Ateho LOPY', 'diocese-ziguinchor' ), 'role' => __( 'FSCM, Adjointe', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'M. Lazare SAGNA', 'diocese-ziguinchor' ), 'role' => '' ),
				),
			),
			array(
				'source_id'   => 'commission-liturgie',
				'titre'       => __( 'Liturgie', 'diocese-ziguinchor' ),
				'responsable' => __( 'Abbé Saturnin Oscar MANGA', 'diocese-ziguinchor' ),
				'membres'     => array(
					array( 'nom' => __( 'Abbé Alain Samor SAGNA', 'diocese-ziguinchor' ), 'role' => __( 'Cérémoniaire diocésain', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Abbé Jean Pierre Amaye TENDENG', 'diocese-ziguinchor' ), 'role' => __( 'Adjoint', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Sœur Thérèse TAMBA', 'diocese-ziguinchor' ), 'role' => __( 'IFRZ', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Sœur Marie SYLVA', 'diocese-ziguinchor' ), 'role' => __( 'FSCM', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Sœur Aimée DAÏSSALA WATCHARI', 'diocese-ziguinchor' ), 'role' => __( 'ISJ', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Sœur Marie Claire DIENE', 'diocese-ziguinchor' ), 'role' => __( 'SJC', 'diocese-ziguinchor' ) ),
				),
			),
			array(
				'source_id'   => 'commission-pastorale-sante',
				'titre'       => __( 'Pastorale de la Santé', 'diocese-ziguinchor' ),
				'responsable' => __( 'Dr Abbé Michel MENDY', 'diocese-ziguinchor' ),
				'membres'     => array(
					array( 'nom' => __( 'Pr Alexandre DIATTA', 'diocese-ziguinchor' ), 'role' => '' ),
					array( 'nom' => __( 'Pr Noël MANGA', 'diocese-ziguinchor' ), 'role' => '' ),
					array( 'nom' => __( 'Dr Sébastien DIEME', 'diocese-ziguinchor' ), 'role' => '' ),
					array( 'nom' => __( 'Dr Marc MANGA', 'diocese-ziguinchor' ), 'role' => '' ),
					array( 'nom' => __( 'Dr Marie Clémence FAYE MENDY', 'diocese-ziguinchor' ), 'role' => '' ),
					array( 'nom' => __( 'Abbé Philippe MANGA', 'diocese-ziguinchor' ), 'role' => __( 'Aumônier Hôpital de la Paix', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Abbé Patrice DIATTA', 'diocese-ziguinchor' ), 'role' => __( 'Aumônier Hôpital Régional', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Abbé Paulin Christian Samson COLY', 'diocese-ziguinchor' ), 'role' => '' ),
					array( 'nom' => __( 'Sœur Martine DIATTA', 'diocese-ziguinchor' ), 'role' => __( 'ANPSCS', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Sœur Cécile DIATTA', 'diocese-ziguinchor' ), 'role' => '' ),
					array( 'nom' => __( 'Mme Christine MANDIAMY', 'diocese-ziguinchor' ), 'role' => __( 'District sanitaire de Ziguinchor', 'diocese-ziguinchor' ) ),
				),
				'note'        => __( 'Tous les aumôniers des hôpitaux du diocèse sont membres de droit de cette commission (mention générale de la circulaire).', 'diocese-ziguinchor' ),
			),
			array(
				'source_id'   => 'commission-pastorale-vocations',
				'titre'       => __( 'Pastorale des Vocations', 'diocese-ziguinchor' ),
				'responsable' => __( 'Abbé Jean DIOUF', 'diocese-ziguinchor' ),
				'membres'     => array(),
				'note'        => __( 'Les responsables des maisons de formation, les délégués laïcs des comités de vocation paroissiaux et les séminaristes stagiaires y sont associés (mention générale de la circulaire, sans liste nominative).', 'diocese-ziguinchor' ),
			),
			array(
				'source_id'   => 'commission-textes-liturgiques-langues',
				'titre'       => __( 'Mise en valeur des textes liturgiques en langues locales', 'diocese-ziguinchor' ),
				'responsable' => __( 'Abbé Yves NDOUR', 'diocese-ziguinchor' ),
				'membres'     => array(
					array( 'nom' => __( 'Abbé Saturnin Oscar MANGA', 'diocese-ziguinchor' ), 'role' => '' ),
					array( 'nom' => __( 'Abbé Victor Bossé SAGNA', 'diocese-ziguinchor' ), 'role' => '' ),
					array( 'nom' => __( 'M. Alain Christian BASSENE', 'diocese-ziguinchor' ), 'role' => __( 'Linguiste', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'M. Gustave KAMPAL', 'diocese-ziguinchor' ), 'role' => __( 'Traducteur', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'M. Jean Christophe DIATTA', 'diocese-ziguinchor' ), 'role' => __( 'Traducteur', 'diocese-ziguinchor' ) ),
				),
			),
		),
		'mouvement'             => array(
			array(
				'source_id'   => 'mouvement-coordination-jeunes',
				'titre'       => __( 'Coordination Diocésaine des Jeunes', 'diocese-ziguinchor' ),
				'responsable' => __( 'Abbé Fulgence Luc DIONE (Aumônier)', 'diocese-ziguinchor' ),
				'membres'     => array(
					array( 'nom' => __( 'Sr Massilia DIEDHIOU', 'diocese-ziguinchor' ), 'role' => __( 'Conseillère', 'diocese-ziguinchor' ) ),
				),
				'aumonier'    => __( 'Abbé Fulgence Luc DIONE', 'diocese-ziguinchor' ),
				'note'        => __( 'Chaque doyenné choisit son propre aumônier et sa conseillère (précision de la circulaire).', 'diocese-ziguinchor' ),
			),
			array(
				'source_id'   => 'mouvement-cvav',
				'titre'       => __( 'CV/AV', 'diocese-ziguinchor' ),
				'responsable' => __( 'Abbé Auguste Tito COLY (Aumônier)', 'diocese-ziguinchor' ),
				'membres'     => array(
					array( 'nom' => __( 'Abbé Oko Marcelin DIASSY', 'diocese-ziguinchor' ), 'role' => __( 'Adjoint', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Sœur Eunice Marina Silva SEIDI', 'diocese-ziguinchor' ), 'role' => __( 'Conseillère, FMSS', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Sœur Jessica KANTOUSSAN', 'diocese-ziguinchor' ), 'role' => __( 'Conseillère, FSCM', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Mme DIATTA Barbara SAMBOU', 'diocese-ziguinchor' ), 'role' => __( 'Conseillère', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'M. Victor Emmanuel DIEME', 'diocese-ziguinchor' ), 'role' => __( 'Conseiller', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'M. Isidore DIATTA', 'diocese-ziguinchor' ), 'role' => __( 'Conseiller', 'diocese-ziguinchor' ) ),
				),
				'aumonier'    => __( 'Abbé Auguste Tito COLY', 'diocese-ziguinchor' ),
			),
			array(
				'source_id'   => 'mouvement-jac-ujrcs-marcs',
				'titre'       => __( 'JAC/UJRCS/MARCS', 'diocese-ziguinchor' ),
				'responsable' => __( 'Abbé Alfred TENDENG (Aumônier)', 'diocese-ziguinchor' ),
				'membres'     => array(
					array( 'nom' => __( 'Sœur Marie Olga DIATTA', 'diocese-ziguinchor' ), 'role' => __( 'Conseillère, SJC', 'diocese-ziguinchor' ) ),
				),
				'aumonier'    => __( 'Abbé Alfred TENDENG', 'diocese-ziguinchor' ),
			),
			array(
				'source_id'   => 'mouvement-joc',
				'titre'       => __( 'Jeunesse Ouvrière Catholique (JOC)', 'diocese-ziguinchor' ),
				'responsable' => __( 'Abbé Alfred TENDENG (Aumônier)', 'diocese-ziguinchor' ),
				'membres'     => array(
					array( 'nom' => __( 'Sœur Yolande Georgette Marie Awa TINE', 'diocese-ziguinchor' ), 'role' => __( 'Conseillère, FSCM', 'diocese-ziguinchor' ) ),
				),
				'aumonier'    => __( 'Abbé Alfred TENDENG', 'diocese-ziguinchor' ),
			),
			array(
				'source_id'   => 'mouvement-jec',
				'titre'       => __( 'Jeunesse Étudiante Catholique (JEC)', 'diocese-ziguinchor' ),
				'responsable' => __( 'Abbé Théodore COLY (Aumônier)', 'diocese-ziguinchor' ),
				'membres'     => array(
					array( 'nom' => __( 'Père Aly Prosper BOISSY', 'diocese-ziguinchor' ), 'role' => __( 'Adjoint (Omi)', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Sœur Félicité ASSINE', 'diocese-ziguinchor' ), 'role' => __( 'Conseillère, IFRZ', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Sœur Sylvia Brigitte Ndiémé FAYE', 'diocese-ziguinchor' ), 'role' => __( 'Conseillère, JS', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'M. Laurent Armand MENDY', 'diocese-ziguinchor' ), 'role' => __( 'Conseiller', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'M. Gilbert NUNEZ', 'diocese-ziguinchor' ), 'role' => __( 'Conseiller', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'M. Gaëtan MARTIN', 'diocese-ziguinchor' ), 'role' => __( 'Conseiller', 'diocese-ziguinchor' ) ),
				),
				'aumonier'    => __( 'Abbé Théodore COLY', 'diocese-ziguinchor' ),
			),
			array(
				'source_id'   => 'mouvement-scouts-guides',
				'titre'       => __( 'Scouts et Guides', 'diocese-ziguinchor' ),
				'responsable' => __( 'Abbé Éric Paulin D. BASSENE (Aumônier)', 'diocese-ziguinchor' ),
				'membres'     => array(
					array( 'nom' => __( 'Père Henry SAMBOU', 'diocese-ziguinchor' ), 'role' => __( 'Adjoint (SCH.P.)', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Abbé William COLY', 'diocese-ziguinchor' ), 'role' => __( 'Adjoint', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Sœur Marie SYLVA', 'diocese-ziguinchor' ), 'role' => __( 'Conseillère, FSCM', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Sœur Eugénie BABENE', 'diocese-ziguinchor' ), 'role' => __( 'Conseillère, Piariste', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Mme Marie Elisabeth DIANDY', 'diocese-ziguinchor' ), 'role' => __( 'Conseillère', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Mme Sylvie MALACK', 'diocese-ziguinchor' ), 'role' => __( 'Conseillère', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'M. Théophile SANE', 'diocese-ziguinchor' ), 'role' => __( 'Conseiller', 'diocese-ziguinchor' ) ),
				),
				'aumonier'    => __( 'Abbé Éric Paulin D. BASSENE', 'diocese-ziguinchor' ),
			),
		),
		'association'           => array(
			array(
				'source_id'   => 'association-udafcz',
				'titre'       => __( 'UDAFC/Z', 'diocese-ziguinchor' ),
				'responsable' => __( 'Abbé Camille Joseph GOMIS (Aumônier)', 'diocese-ziguinchor' ),
				'membres'     => array(
					array( 'nom' => __( 'Sœur Régina Marie Céline SAGNA', 'diocese-ziguinchor' ), 'role' => __( 'Conseillère, FSCM', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Sœur Marthe Mélanie DIANDY', 'diocese-ziguinchor' ), 'role' => __( 'Conseillère, Piariste', 'diocese-ziguinchor' ) ),
				),
			),
			array(
				'source_id'   => 'association-legion-de-marie',
				'titre'       => __( 'Légion de Marie', 'diocese-ziguinchor' ),
				'responsable' => __( 'Abbé Jean Augustin SAMBOU (Aumônier)', 'diocese-ziguinchor' ),
				'membres'     => array(
					array( 'nom' => __( 'Abbé Edgar NDECKY', 'diocese-ziguinchor' ), 'role' => __( 'Adjoint', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Sœur Marie Madeleine BADIANE', 'diocese-ziguinchor' ), 'role' => __( 'Conseillère, FSCM', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Sœur Marie Yvonne SAMBOU', 'diocese-ziguinchor' ), 'role' => __( 'Conseillère, FSCM', 'diocese-ziguinchor' ) ),
				),
			),
			array(
				'source_id'   => 'association-coordination-chorales',
				'titre'       => __( 'Coordination Diocésaine des Chorales', 'diocese-ziguinchor' ),
				'responsable' => __( 'Abbé Joseph TENDENG (Aumônier)', 'diocese-ziguinchor' ),
				'membres'     => array(
					array( 'nom' => __( 'Abbé Paul DIATTA', 'diocese-ziguinchor' ), 'role' => __( 'Adjoint', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Sœur Suzanne Marie MANGA', 'diocese-ziguinchor' ), 'role' => __( 'Conseillère, FSCM', 'diocese-ziguinchor' ) ),
				),
				'note'        => __( "Tous les aumôniers et sœurs conseillères décanaux des chorales en sont membres de droit. La circulaire liste séparément une « Coordination Décanale des Chorales » (IV.4), mais sans aumônier ni conseillère nommés — chaque doyenné choisit les siens, comme pour la Coordination Diocésaine des Jeunes : pas de fiche séparée pour elle.", 'diocese-ziguinchor' ),
			),
			array(
				'source_id'   => 'association-renouveau-charismatique',
				'titre'       => __( 'Comité Diocésain du Renouveau Charismatique Catholique', 'diocese-ziguinchor' ),
				'responsable' => __( 'Abbé Albert DIATTA (Aumônier)', 'diocese-ziguinchor' ),
				'membres'     => array(
					array( 'nom' => __( 'Abbé Jean Pierre Amaye TENDENG', 'diocese-ziguinchor' ), 'role' => __( 'Adjoint', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Sœur Marie Agnès NGANDOUL', 'diocese-ziguinchor' ), 'role' => __( 'Conseillère, FSCM', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Sœur Diminga MENDES', 'diocese-ziguinchor' ), 'role' => __( 'Conseillère, Piariste', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Sœur Louise Elisabeth NDONG', 'diocese-ziguinchor' ), 'role' => __( 'Conseillère, SJC', 'diocese-ziguinchor' ) ),
				),
			),
			array(
				'source_id'   => 'association-vie-montante',
				'titre'       => __( 'Vie Montante', 'diocese-ziguinchor' ),
				'responsable' => __( 'Abbé Charles Bernard COLY (Aumônier)', 'diocese-ziguinchor' ),
				'membres'     => array(
					array( 'nom' => __( 'Frère Matthieu CABO', 'diocese-ziguinchor' ), 'role' => __( 'Conseiller (SC)', 'diocese-ziguinchor' ) ),
				),
			),
			array(
				'source_id'   => 'association-equipes-enseignantes',
				'titre'       => __( 'Équipes Enseignantes', 'diocese-ziguinchor' ),
				'responsable' => __( 'Abbé Jean de Dieu SAMBOU (Aumônier)', 'diocese-ziguinchor' ),
				'membres'     => array(
					array( 'nom' => __( 'Sœur Christèle COLY', 'diocese-ziguinchor' ), 'role' => __( 'Conseillère, PM', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Sœur Thérèse TAMBA', 'diocese-ziguinchor' ), 'role' => __( 'Conseillère, IFRZ', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Sœur Sophie DIATTA', 'diocese-ziguinchor' ), 'role' => __( 'Conseillère, Piariste', 'diocese-ziguinchor' ) ),
				),
			),
			array(
				'source_id'   => 'association-forces-defense-securite',
				'titre'       => __( 'Forces de Défense et de Sécurité', 'diocese-ziguinchor' ),
				'responsable' => __( 'Abbé Constant Koupaul DIEME (Aumônier)', 'diocese-ziguinchor' ),
				'membres'     => array(
					array( 'nom' => __( 'Père André Boucar SENE', 'diocese-ziguinchor' ), 'role' => __( 'Adjoint (Omi)', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Sœur Martine DIATTA', 'diocese-ziguinchor' ), 'role' => __( 'Conseillère, JS', 'diocese-ziguinchor' ) ),
					array( 'nom' => __( 'Sœur Marie Angèle COLY', 'diocese-ziguinchor' ), 'role' => __( 'Conseillère, PM', 'diocese-ziguinchor' ) ),
				),
			),
		),
	);
}
