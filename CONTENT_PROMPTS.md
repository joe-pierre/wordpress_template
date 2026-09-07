# CONTENT_PROMPTS.md — Prompts d'import de contenu réel

> Ce fichier complète `DESIGN_PROMPTS.md`. Il ne construit plus de structure/gabarits — il **peuple** ceux déjà construits (Prompts 0 à 17) avec du vrai contenu, à partir des documents fournis par le diocèse (`NOMINATIONS_SERVICES_COMMISSIONS_AUMONERIES_2027.pdf`, `CALENDRIER_DIOCESAIN_2027.pdf`, `Copie_de_Armoiries_Diocèse_de_Ziguinchor.pdf`, `hero_images/`).
>
> **Contrainte technique importante à rappeler à Claude Code dans le Prompt 0** : Claude Code travaille sur le dépôt de code local, pas directement sur la base de données du site en ligne. Il ne peut donc pas "créer un article" comme le ferait un rédacteur dans l'admin. La bonne approche est de générer un **mécanisme d'import** (script exécuté une seule fois, depuis l'admin ou en ligne de commande WP-CLI si disponible sur l'hébergement) qui lit des données structurées et crée les contenus via les fonctions WordPress standard (`wp_insert_post`, `update_field` d'ACF). Une fois exécuté sur le vrai site, ce mécanisme peut être désactivé/supprimé — ce n'est pas une fonctionnalité permanente du thème.

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
