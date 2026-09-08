# CONTENT_PROMPTS.md — Prompts d'import de contenu réel

> Ce fichier complète `DESIGN_PROMPTS.md`. Il ne construit plus de structure/gabarits — il **peuple** ceux déjà construits (Prompts 0 à 17) avec du vrai contenu, à partir des documents fournis par le diocèse (`NOMINATIONS_SERVICES_COMMISSIONS_AUMONERIES_2027.pdf`, `CALENDRIER_DIOCESAIN_2027.pdf`, `Copie_de_Armoiries_Diocèse_de_Ziguinchor.pdf`, `hero_images/`).
>
> **Contrainte technique importante à rappeler à Claude Code dans le Prompt 0** : Claude Code travaille sur le dépôt de code local, pas directement sur la base de données du site en ligne. Il ne peut donc pas "créer un article" comme le ferait un rédacteur dans l'admin. La bonne approche est de générer un **mécanisme d'import** (script exécuté une seule fois, depuis l'admin ou en ligne de commande WP-CLI si disponible sur l'hébergement) qui lit des données structurées et crée les contenus via les fonctions WordPress standard (`wp_insert_post`, `update_field` d'ACF). Une fois exécuté sur le vrai site, ce mécanisme peut être désactivé/supprimé — ce n'est pas une fonctionnalité permanente du thème.

> **Documents sources disponibles dans `documents/`** (à la racine du projet) : `NOMINATIONS_SERVICES_COMMISSIONS_AUMONERIES_2027.pdf`, `CALENDRIER_DIOCESAIN_2027.pdf`, `Copie_de_Armoiries_Diocèse_de_Ziguinchor.pdf`. Avant d'utiliser les données transcrites dans ce fichier (Prompts 2, 3, 3bis, 5), si un outil d'extraction de texte PDF est disponible dans l'environnement, vérifie-les contre le PDF correspondant dans `documents/` et signale tout écart dans `BUGS_AND_ROADMAP.md` plutôt que de corriger silencieusement — ces transcriptions viennent d'une lecture manuelle du document, pas d'une extraction automatique, donc une coquille sur un nom propre est possible. Si aucun outil d'extraction n'est disponible, utilise les données telles quelles et note-le simplement.

---

## PROMPT 0 — Vérification et méthode avant import

Avant de commencer, relis `SPEC.md`, `DECISIONS.md` et confirme que les Prompts 13 à 16 de `DESIGN_PROMPTS.md` (CPT organisationnels, taxonomies) sont bien terminés — l'import de contenu ne peut pas fonctionner sans les CPT/champs cibles déjà enregistrés.

Crée un mécanisme d'import réutilisable avant d'écrire le contenu spécifique des prompts suivants :
1. Un dossier `inc/import/` dans le thème, avec un fichier de données par source (`inc/import/data-nominations.php`, `inc/import/data-calendrier.php`, `inc/import/data-hero.php`) : chacun retourne un tableau PHP structuré, pas de logique, juste les données extraites des PDF/dossier fournis.
2. Une page d'outil admin **"Réglages > Import contenu diocèse"** (capability `manage_options`, nonce, protégée par `if ( ! defined( 'ABSPATH' ) ) exit;` comme le reste du thème), avec un bouton par source de données ("Importer le calendrier", "Importer les nominations", "Importer le hero"). Chaque bouton déclenche une fonction d'import dédiée.
3. **Idempotence obligatoire** : chaque fonction d'import doit vérifier, avant de créer un contenu, qu'un contenu équivalent n'existe pas déjà (par exemple via un champ meta `_dz_import_source_id` unique stocké à la création), pour qu'un clic répété ou un import relancé après correction de données ne duplique rien.
4. Documente ce mécanisme dans `DECISIONS.md` (nouvelle entrée) et coche la tâche correspondante dans `TODO.md`.

## PROMPT 1 — Hero de la page d'accueil : images + textes

4 images réelles (pas 5) sont fournies dans `assets/img/eveque/` (déjà dans le thème, pas un dossier séparé à la racine) : `messe.jpg`, `consecration.jpg`, `eveque.jpg`, `benediction_par_eveque.jpg`. Voici l'ordre et les textes validés côté client (si un fichier `assets/img/eveque/slides.txt` existe et contredit ce tableau, `slides.txt` fait foi — sinon utilise ces valeurs) :

| Ordre | Fichier | Titre | Sous-titre |
|---|---|---|---|
| 1 | `messe.jpg` | Célébration eucharistique | L'Église rassemblée autour de l'autel |
| 2 | `consecration.jpg` | Au cœur de chaque messe | Le mystère eucharistique, source de toute vie chrétienne |
| 3 | `eveque.jpg` | Une Église proche des fidèles | Mgr Jean Baptiste Valter Manga à la rencontre des familles |
| 4 | `benediction_par_eveque.jpg` | La bénédiction de l'évêque | Un geste de proximité pastorale |

1. Copie les 4 images de `assets/img/eveque/` vers `assets/seed-images/hero/` (dossier temporaire de contenu de démarrage, pas un dossier vendor — ne les laisse pas dans `assets/img/eveque/` une fois copiées, pour ne pas garder deux copies dans le thème).
2. Dans `inc/import/data-hero.php`, construis le tableau des 4 slides dans l'ordre ci-dessus (titre, sous-titre, chemin de l'image, lien = vide, aucun lien fourni).
3. La fonction d'import correspondante doit : attacher chaque image à la médiathèque WordPress via `media_handle_sideload()` ou équivalent (pas de simple copie de fichier — il faut un vrai attachment WordPress, avec metadonnées, pour qu'il apparaisse dans la médiathèque et soit utilisable ailleurs), puis peupler le repeater ACF `dz_front_hero_slides` du groupe `group_dz_front_hero` avec ces 4 entrées.
4. `messe.jpg` et `consecration.jpg` sont en format large, `eveque.jpg` et `benediction_par_eveque.jpg` plus carrées/verticales — vérifie visuellement le recadrage de ces deux dernières une fois le slider affiché (le CSS du hero peut recadrer serré en `object-fit: cover`) et signale dans `BUGS_AND_ROADMAP.md` si un recadrage manuel ou un `object-position` spécifique est nécessaire pour ces 2 images.
5. Vérifie le résultat en rechargeant la page d'accueil localement si un environnement de test est disponible, sinon documente clairement dans `BUGS_AND_ROADMAP.md` que la vérification finale doit se faire après déploiement sur le site réel.

## PROMPT 2 — Calendrier diocésain 2026-2027 → CPT `evenement`

Source : `CALENDRIER_DIOCESAIN_2027.pdf` (tableau Dates / Activités / Lieux, année pastorale octobre 2026 à octobre 2027).

1. Dans `inc/import/data-calendrier.php`, retranscris chaque ligne du tableau en une entrée structurée : titre (l'activité), date de début, date de fin (si la ligne du PDF donne une plage comme "04-09 octobre" ou "11-16 octobre" — sinon date de fin = date de début), lieu.
2. Règle de parsing des dates : le PDF est organisé par mois avec l'année implicite (ex. "OCT/NOV 2026", "JANVIER 2027") — reconstruis l'année complète pour chaque date, ne la laisse jamais implicite dans le code.
3. Chaque entrée devient un post `evenement`, avec la taxonomie `evenement_type` positionnée sur "Diocésain" par défaut (aucune de ces activités n'est spécifiquement "Agenda de l'évêque" sauf mention contraire explicite dans le texte, comme l'anniversaire d'ordination épiscopale de Mgr Manga — celle-ci en "Agenda de l'évêque").
4. Si une activité concerne un lieu qui correspond au nom d'une paroisse déjà enregistrée dans le CPT `paroisse`, relie l'événement à cette paroisse (relation optionnelle déjà prévue dans `SPEC.md` §3) ; sinon laisse le champ lieu en texte libre.
5. Vérifie qu'aucun événement passé (antérieur à aujourd'hui au moment de l'import) ne casse la règle métier "événements à venir uniquement" déjà en place sur `archive-evenement.php` — ce n'est pas un bug si un ancien événement n'apparaît pas dans l'agenda public, mais assure-toi qu'il reste bien visible dans l'admin.

## PROMPT 3 — Circulaire de nominations → CPT organisationnels

Source : `NOMINATIONS_SERVICES_COMMISSIONS_AUMONERIES_2027.pdf` (Circulaire n°002/2026-2027).

Cette circulaire couvre 4 des 8 nouveaux CPT (voir `SPEC.md` §3 et `DECISIONS.md`). Traite-les dans cet ordre, un `inc/import/data-nominations.php` unique mais avec une fonction d'import par CPT pour rester lisible :

1. **`service_diocesain`** (section I de la circulaire) : Économat Diocésain, Caritas Ziguinchor, ODEC, Apostolat des Laïcs, Coopération Missionnaire et OPM, Exorcisme, Cérémoniaires diocésains, Formation et Recherche, Communication, Pèlerinages. Pour l'Économat, utilise le champ `sous_structures` pour les entités rattachées (Conseil d'Administration des Domaines agricoles, des Unités de Production, des Instituts d'Enseignement Supérieur, Comité d'étude et de suivi des projets) plutôt que de créer des posts séparés pour chacune.
2. **`commission_diocesaine`** (section II) : Catéchèse, Cellule d'écoute et de sensibilisation, Écologie intégrale, Dialogue œcuménique et interreligieux, Justice et Paix, Pastorale de la Famille, Liturgie, Pastorale de la Santé, Pastorale des Vocations, Mise en valeur des textes liturgiques en langues locales.
3. **`mouvement`** (section III) : Coordination Diocésaine des Jeunes, CV/AV, JAC/UJRCS/MARCS, JOC, JEC, Scouts et Guides. Remplis le champ `aumonier` avec une relation vers la fiche `pretre` correspondante si ce prêtre existe déjà dans le CPT `pretre` ; sinon laisse le champ texte simple et note-le comme point à corriger dans `BUGS_AND_ROADMAP.md`.
4. **`association`** (section IV) : UDAFC/Z, Légion de Marie, Coordination Diocésaine des Chorales, Comité Diocésain du Renouveau Charismatique, Vie Montante, Équipes Enseignantes, Forces de Défense et de Sécurité.

Pour chaque entrée : titre = nom de l'entité, `responsable` = la première personne listée avec son rôle (souvent "Responsable" ou équivalent selon la section), repeater `membres` = toutes les autres personnes listées avec leur rôle exact tel qu'écrit dans le PDF (Adjoint, Conseillère, Secrétaire, etc.).

## PROMPT 3bis — Compléter responsable et membres avec les vraies données de la circulaire

Le PDF `NOMINATIONS_SERVICES_COMMISSIONS_AUMONERIES_2027.pdf` n'étant pas dans le dépôt, les 33 entités créées au Prompt 3 ont `responsable`/`membres` vides. Voici les vraies données extraites du document (Circulaire n°002/2026-2027, faite à Ziguinchor le 1er septembre 2026, signée Mgr Jean Baptiste Valter MANGA), à reporter dans `inc/import/data-nominations.php` puis réimporter (le mécanisme d'idempotence du Prompt 0 doit mettre à jour les entités déjà créées plutôt que d'en recréer — vérifie que `dz_import_find_existing_post()` gère bien la mise à jour, sinon adapte-le).

### `service_diocesain`

1. **Économat Diocésain** — responsable : Abbé Albert TENDENG (Économe diocésain). Membres : Rév. Mgr Fulgence COLY (Vicaire Général, chargé du Temporel et de la Pastorale sociale), M. René Lamine DIEDHIOU (Comptable), M. Pierre Anaw SAMBOU (Secrétaire).
   - `sous_structures` (voir résolution du conflit avec `SPEC.md` §3 ci-dessous) : (A) Conseil d'Administration des Domaines agricoles diocésains — Président M. Benoît SAMBOU, avec M. Paterne DIATTA, M. Casimir Adrien SAMBOU, Abbé Prosper TENDENG, Sœur Elisa DIATTA (IFRZ) ; rattachés : C.P.R.A. d'Affiniam (Abbé Potin BADIANE) et Ferme École de Djibélor (Abbé Alfred TENDENG, Abbé Paul Ignace TENDENG, Abbé René Pierre COLY). (B) Conseil d'Administration des Unités de Production — Président M. Habib Ampa DIENG, avec Abbé Adrien Dominique BADIANE, Abbé Albert TENDENG, Mme Marie Louise FAYE, M. Emmanuel BADJI, M. Eugène NDIAYE, Mme FAURE Marise Françoise Awai TENDENG ; rattachés : Hôtel Carabane (M. Gabriel COLY, Gérant), Librairie Papeterie Djibékel (M. Raymond SAGNA, Gérant), Imprimerie du Sud/ex Néma (M. Fally SAMB, Directeur). (C) Conseil d'Administration des Instituts d'Enseignement Supérieur — Président Pr Salomon SAMBOU, avec Pr Melyan MENDY, Pr Alexandre DIATTA, Dr Alain Christian BASSENE, Dr Marie Clémence FAYE MENDY, Pr Noël Magloire MANGA, M. Paulin NZALE ; rattachés : UCAO/UUZ/ISCG (Abbé Samson Delaka KANTOUSSAN, Abbé Eugène Adigar DIATTA, Sœur Aimée DAÏSSALA WAÏTCHARI) et ISPS (Dr Abbé Michel MENDY, M. Louis BASSENE, Mme SAGNA Jeanne Odette SAMBOU, Mme Rose Gnaba SAMBOU, Mme DRAME Khoudje GOUNDIAM, Mme Viviane A. COLY, M. Denis DIASSY, Abbé Victor Bossé SAGNA). (D) Comité Diocésain d'Étude et de Suivi des Projets — Président Rév. Mgr Fulgence COLY, Secrétaire Frère Matthieu CABO (S.C.), avec Abbé Albert TENDENG, Abbé Faustin DIEME, Abbé Adrien Dominique BADIANE, Abbé Jean Pierre Amaye TENDENG (Chancelier diocésain), M. Paterne DIATTA, Mme Marie Angèle DIATTA.

2. **Caritas Ziguinchor** — responsable : Abbé Adrien Dominique BADIANE (Directeur, Administration Centrale). Membres — Conseil de Gestion : Mgr Fulgence COLY, Abbé Samson D. KANTOUSSAN (Vicaire épiscopal), Abbé Albert TENDENG, Dr Prosper DIEDHIOU (président sortant), M. André Florent BASSENE, Abbé Jean Pierre Amaye TENDENG ; Administration Centrale : M. Alphonse DIEDHIOU (Chef de Bureau), M. Eusébio DASYLVA (Développement), Mme Jeanne Marie SENGHOR RAF (Finance), Mme Louise FAYE (Personnel), Abbé Joseph TENDENG (Aumônier/Urgence), Dr Abbé Michel MENDY (Référent Caritas Santé) ; Appui institutionnel : Mme Ludivine Gabrielle DIEME, M. Degolle MENDY ; Points focaux : M. Athanase DIATTA, M. Degolle MENDY, M. Antoine DIEDHIOU, M. Eusébio DASYLVA.

3. **Office Diocésain de l'Enseignement Catholique (ODEC)** — responsable : Sœur Rose Mama DIOUF (IFRZ, DIDEC et Déclarant Responsable). Membres : M. Abraham SENGHOR (Conseiller pédagogique), M. Jean-Noël DIOUF (Comptable), Mme Léocadie COLY (RH), Mme Jeanne d'Arc MANGA (Secrétaire).

4. **Apostolat des Laïcs – Direction des Œuvres Catholiques** — responsable : Abbé Djimoreu Antoine Alain BADIANE. Membres : Mme Gilberta DIANDY (Secrétaire) ; note : tous les prêtres aumôniers et sœurs conseillères diocésains et décanaux en sont membres de droit (mention générale du texte, ne pas lister nommément).

5. **Coopération Missionnaire et OPM** — responsable : Abbé Faustin DIEME. Adjoints/membres : Abbé Achille DJIHOUNOUCK, Abbé Auguste Oscar J. SAMBOU (Délégué épiscopal Prêtres Fidei Donum en France), Père Édouard DIEDHIOU (SCH.P.).

6. **Exorcisme** — responsable : Abbé Albert DIATTA, en charge des Doyennés de Ziguinchor et Brin. Pas de membres listés.

7. **Cérémoniaires diocésains** — responsable : Abbé Alain Samor SAGNA (Cérémoniaire diocésain). Membre : Abbé Jean Pierre Amaye TENDENG (Adjoint).

8. **Formation et Recherche** — responsable : Abbé Xavier NGANDOUL. Membres : Abbé Christian A. SAGNA, Abbé Prosper TENDENG.

9. **Communication** — responsable : Abbé Jacques Aimé SAGNA. Membres : équipe de prêtres, religieux(ses) et fidèles laïcs communicateurs (mention générale, ne pas lister nommément).

10. **Pèlerinages** — responsable : Abbé Djimoreu Antoine Alain BADIANE (Pèlerinages Diocésains, interdiocésains et nationaux). Délégués CINPEC (membres) : Mme Marie Clémence FAYE MENDY, M. Matthieu SAGNA.

### `commission_diocesaine`

1. **Catéchèse** — responsable : Abbé Prosper TENDENG. Membres : Sœur Nadine Ayosso MANGA (IFRZ), M. Charles Raoul SAGNA (Président du Bureau diocésain des catéchistes), les Aumôniers décanaux.
2. **Cellule d'écoute et de sensibilisation** — responsable : Abbé Xavier NGANDOUL. Membres : Dr Abbé Michel MENDY, Dr Sébastien DIEME, Sœur Cécile Fabiana NDECKY (IFRZ), Sœur Concha Carmen DIATTA (Piariste), Mme SAGNA Jeanne Odette SAMBOU.
3. **Écologie intégrale** — responsable : M. Eusébio DASYLVA. Membres : Abbé Djimoreu Antoine Alain BADIANE, Sœur Rose Mama DIOUF (IFRZ), Commissaire Régional Scout, Commissaire Régionale Guide.
4. **Dialogue Œcuménique et Interreligieux** — responsable : Abbé Samson Delaka KANTOUSSAN (Dialogue avec la Religion Traditionnelle Africaine). Membres : Abbé Jean Augustin SAMBOU (Dialogue Œcuménique), Abbé Célestin SAGNA (Dialogue Islamo-Chrétien), Abbé Paul DIATTA, Abbé Joël Cheikh COLY.
5. **Justice et Paix** — responsable : Abbé Camille Joseph GOMIS. Membre : Sœur Marguerite COLY (IFRZ, Adjointe).
6. **Pastorale de la Famille** — responsable : Abbé Marius MANGA. Membres : Sœur Angèle Marie Ateho LOPY (FSCM, Adjointe), M. Lazare SAGNA.
7. **Liturgie** — responsable : Abbé Saturnin Oscar MANGA. Membres : Abbé Alain Samor SAGNA (Cérémoniaire diocésain), Abbé Jean Pierre Amaye TENDENG (Adjoint), Sœur Thérèse TAMBA (IFRZ), Sœur Marie SYLVA (FSCM), Sœur Aimée DAÏSSALA WATCHARI (ISJ), Sœur Marie Claire DIENE (SJC).
8. **Pastorale de la Santé** — responsable : Dr Abbé Michel MENDY. Membres : Pr Alexandre DIATTA, Pr Noël MANGA, Dr Sébastien DIEME, Dr Marc MANGA, Dr Marie Clémence FAYE MENDY, Abbé Philippe MANGA (Aumônier Hôpital de la Paix), Abbé Patrice DIATTA (Aumônier Hôpital Régional), Abbé Paulin Christian Samson COLY, Sœur Martine DIATTA (ANPSCS), Sœur Cécile DIATTA, Mme Christine MANDIAMY (District sanitaire de Ziguinchor), tous les aumôniers des hôpitaux.
9. **Pastorale des Vocations** — responsable : Abbé Jean DIOUF. Membres : les responsables des maisons de formation, les délégués laïcs des comités de vocation paroissiaux, les séminaristes stagiaires (mentions générales).
10. **Mise en valeur des textes liturgiques en langues locales** — responsable : Abbé Yves NDOUR. Membres : Abbé Saturnin Oscar MANGA, Abbé Victor Bossé SAGNA, M. Alain Christian BASSENE (Linguiste), M. Gustave KAMPAL (Traducteur), M. Jean Christophe DIATTA (Traducteur).

### `mouvement`

1. **Coordination Diocésaine des Jeunes** — aumônier : Abbé Fulgence Luc DIONE. Conseillère : Sr Massilia DIEDHIOU. Note du texte : chaque doyenné choisit son propre aumônier et sa conseillère.
2. **CV/AV** — aumônier : Abbé Auguste Tito COLY. Adjoint : Abbé Oko Marcelin DIASSY. Conseillers : Sœur Eunice Marina Silva SEIDI (FMSS), Sœur Jessica KANTOUSSAN (FSCM), Mme DIATTA Barbara SAMBOU, M. Victor Emmanuel DIEME, M. Isidore DIATTA.
3. **JAC/UJRCS/MARCS** — aumônier : Abbé Alfred TENDENG. Conseillère : Sœur Marie Olga DIATTA (SJC).
4. **Jeunesse Ouvrière Catholique (JOC)** — aumônier : Abbé Alfred TENDENG. Conseillère : Sœur Yolande Georgette Marie Awa TINE (FSCM).
5. **Jeunesse Étudiante Catholique (JEC)** — aumônier : Abbé Théodore COLY. Adjoint : Père Aly Prosper BOISSY (Omi). Conseillers : Sœur Félicité ASSINE (IFRZ), Sœur Sylvia Brigitte Ndiémé FAYE (JS), M. Laurent Armand MENDY, M. Gilbert NUNEZ, M. Gaëtan MARTIN.
6. **Scouts et Guides** — aumônier : Abbé Éric Paulin D. BASSENE. Adjoints : Père Henry SAMBOU (SCH.P.), Abbé William COLY. Conseillers : Sœur Marie SYLVA (FSCM), Sœur Eugénie BABENE (Piariste), Mme Marie Elisabeth DIANDY, Mme Sylvie MALACK, M. Théophile SANE.

Pour le champ `aumonier` (relation vers `pretre`) : cherche une correspondance par nom de famille dans le CPT `pretre` déjà existant via `dz_import_find_pretre_by_title()` (déjà écrite au Prompt 3). Comme signalé dans `BUGS_AND_ROADMAP.md`, si aucune correspondance n'est trouvée, laisse la relation vide et ajoute le nom complet en commentaire dans le contenu de la fiche plutôt que de le perdre silencieusement — c'est le vrai problème de schéma déjà relevé (pas de champ texte de repli sur `mouvement_aumonier`), à corriger si le nombre de cas sans correspondance est élevé une fois l'import fait.

### `association`

1. **UDAFC/Z** — aumônier : Abbé Camille Joseph GOMIS. Conseillères : Sœur Régina Marie Céline SAGNA (FSCM), Sœur Marthe Mélanie DIANDY (Piariste).
2. **Légion de Marie** — aumônier : Abbé Jean Augustin SAMBOU. Adjoint : Abbé Edgar NDECKY. Conseillères : Sœur Marie Madeleine BADIANE (FSCM), Sœur Marie Yvonne SAMBOU (FSCM).
3. **Coordination Diocésaine des Chorales** — aumônier : Abbé Joseph TENDENG. Adjoint : Abbé Paul DIATTA. Conseillère : Sœur Suzanne Marie MANGA (FSCM). Membres : tous les aumôniers et sœurs conseillères décanaux des chorales (mention générale — la "Coordination Décanale des Chorales" citée séparément dans l'arborescence n'a pas d'entité nommée dans la circulaire, chaque doyenné choisissant les siens : ne crée pas de fiche séparée pour elle).
4. **Comité Diocésain du Renouveau Charismatique Catholique** — aumônier : Abbé Albert DIATTA. Adjoint : Abbé Jean Pierre Amaye TENDENG. Conseillères : Sœur Marie Agnès NGANDOUL (FSCM), Sœur Diminga MENDES (Piariste), Sœur Louise Elisabeth NDONG (SJC).
5. **Vie Montante** — aumônier : Abbé Charles Bernard COLY. Conseiller : Frère Matthieu CABO (SC).
6. **Équipes Enseignantes** — aumônier : Abbé Jean de Dieu SAMBOU. Conseillères : Sœur Christèle COLY (PM), Sœur Thérèse TAMBA (IFRZ), Sœur Sophie DIATTA (Piariste).
7. **Forces de Défense et de Sécurité** — aumônier : Abbé Constant Koupaul DIEME. Adjoint : Père André Boucar SENE (Omi). Conseillères : Sœur Martine DIATTA (JS), Sœur Marie Angèle COLY (PM).

### Résolution du conflit "Économat : sous-structures" signalé dans le résumé du Prompt 3

Les deux niveaux sont réels et corrects, à des profondeurs différentes : les 4 conseils A-D sont le niveau direct sous l'Économat (ce que le Prompt 3 a implémenté dans `sous_structures`), et Hôtel Carabane/Librairie Djibékel/Imprimerie du Sud sont un niveau encore en dessous, rattaché au seul Conseil B (Unités de Production) — ce que `SPEC.md` §3 mentionnait de façon raccourcie sans préciser ce rattachement. **Décision à documenter dans `DECISIONS.md`** : ne pas créer un 3ᵉ niveau de repeater imbriqué (complexité disproportionnée pour 3 entités) — mentionne plutôt ces 3 entités rattachées dans le texte descriptif du sous-structure "Conseil d'Administration des Unités de Production" (champ `description` ou une simple liste en fin de texte), pas comme des lignes `sous_structures` séparées au même niveau que les 4 conseils.

## PROMPT 4 — Aumôneries et Enseignements diocésains

Suite de la circulaire (sections V à VIII pour les aumôneries, section citée dans l'arborescence pour les enseignements).

1. **`aumonerie`** avec la taxonomie `type_aumonerie` : scolaires (section V — Collège Saint Charles Lwanga/Lycée Saint Eloi, Collège Sacré-Cœur, Amicale Sainte Thérèse), universitaires (section VI — Coordination des Aumôneries Universitaires, UCAO/UUZ/ISCG, Université Assane Seck, ISM, UVS Ziguinchor, UVS Bignona, ISEG, ISS), santé (section VII — Hôpital de la Paix, Hôpital Régional, Coordination des Agents de Santé, ONG SIDA Service), carcérale (section VIII — Ziguinchor, Bignona/Oussouye).
2. **`etablissement`** : les établissements d'enseignement supérieur diocésains apparaissent en creux dans la circulaire (UCAO/UUZ/ISCG, ISPS, ISM...) — croise avec la rubrique "Enseignements diocésains" de `Arborescence_PDF.pdf` (DIDEC, Séminaires et Maisons de formation, Collèges Diocésains, Enseignement Supérieur) pour éviter les doublons avec les entrées déjà créées comme `aumonerie`. Si un établissement a déjà une fiche `aumonerie` (ex. UCAO/UUZ/ISCG), ne crée pas de doublon dans `etablissement` — un lien entre les deux fiches suffit, ou une seule fiche selon ce qui te semble le plus cohérent (documente ce choix dans `DECISIONS.md`).

## PROMPT 5 — Contenu "À propos" et armoiries

Source : `Copie_de_Armoiries_Diocèse_de_Ziguinchor.pdf`.

1. Remplis le contenu (`the_content()`) de la page "Historique" (ou "À propos du diocèse" si une seule page couvre les deux) avec le texte du document sur l'importance des armoiries pour un diocèse (les 6 points : signe d'unité, témoignage historique, message spirituel, marque d'autorité, outil de communication moderne, symbole de dialogue).
2. Crée une sous-page ou une section dédiée "Nos armoiries" reprenant la description symbole par symbole (croix dorée, pirogue, tiare épiscopale, bouclier, forme de l'écu, épis de riz, colombe, croix fleuries vertes, inscription latine, année MCMLV) — utilise une mise en page en alternance image/texte plutôt qu'un simple bloc de texte, pour rester dans l'esprit visuel du site.
3. Insère le logo/armoiries (`logo-diocese-ziguinchor.png`) en illustration principale de cette page, en plus de sa présence dans le header (déjà fait au Prompt 12bis de `DESIGN_PROMPTS.md`).

## PROMPT 6 — Vérification finale et nettoyage

1. Repasse sur chaque fonction d'import créée aux Prompts 1 à 5 et vérifie qu'aucune ne peut être déclenchée deux fois sans vérification d'idempotence (voir Prompt 0).
2. Vérifie qu'aucune donnée sensible (adresses e-mail/téléphone personnelles des personnes listées dans la circulaire) n'est exposée publiquement sans nécessité — si un rôle n'a pas vocation à afficher de coordonnées directes sur le site public, ne les importe pas même si elles figurent dans le PDF source.
3. Documente dans `TODO.md` (nouvelle Phase, ou complément à la Phase 7 existante) l'état de l'import : ce qui a été importé automatiquement, ce qui reste à saisir manuellement (photos des personnes, biographies détaillées, contenus non couverts par les documents fournis).
4. Une fois l'import validé sur le vrai site après déploiement, la page d'outil admin créée au Prompt 0 peut rester en place (avec ses gardes-fous d'idempotence) plutôt que d'être supprimée — elle pourra resservir si de nouvelles circulaires de nominations sont publiées les années suivantes.