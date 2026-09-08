# TODO

## Phase 0 — Cadrage (préalable, non technique)

- [x] Collecter le logo, la charte graphique et les couleurs institutionnelles du diocèse (armoiries reçues, voir `SPEC.md` §10)
- [x] Confirmer la structure organisationnelle (doyennés confirmés via l'arborescence — taxonomie `doyenne` sur `paroisse`, remplace `secteur_pastoral`)
- [ ] Confirmer le périmètre "Dons" v1 (juste informatif, ou paiement en ligne dès le lancement ?)
- [ ] Obtenir les photos officielles réelles (cathédrale, évêque, paroisses) — textes déjà en partie disponibles (armoiries, nominations, calendrier)
- [x] Obtenir la vraie arborescence du site (`Arborescence_PDF.pdf`) — a entraîné une révision du menu (mega-menu hybride) et du modèle de données, voir `DECISIONS.md`
- [ ] Clarifier le rattachement de la rubrique "Sacrements" dans l'arborescence officielle (absente du PDF reçu — proposition : sous "Vie de Foi", à valider avec le diocèse)

## Phase 1bis — Nouveaux Custom Post Types organisationnels et mega-menu (suite à l'arborescence réelle et à la circulaire de nominations)

- [x] Enregistrer les CPT `conseil` (`menu_position` 26), `service_diocesain` (27), `commission_diocesaine` (28) — `inc/cpt-conseil.php`, `inc/cpt-service_diocesain.php`, `inc/cpt-commission_diocesaine.php` (voir `SPEC.md` §3, `DECISIONS.md` PROMPT 13)
- [x] Enregistrer les CPT `mouvement` (`menu_position` 29), `association` (30), `aumonerie` (31) — `inc/cpt-mouvement.php`, `inc/cpt-association.php`, `inc/cpt-aumonerie.php` (voir `SPEC.md` §3, `DECISIONS.md` PROMPT 14)
- [x] Enregistrer les CPT restants `etablissement` (`menu_position` 32), `ancien_eveque` (33) — `inc/cpt-etablissement.php`, `inc/cpt-ancien_eveque.php` (voir `SPEC.md` §3, `DECISIONS.md` PROMPT 15)
- [x] Créer le groupe ACF socle commun (responsable, repeater membres) partagé entre `conseil`/`service_diocesain`/`commission_diocesaine` — `acf-json/group_dz_cpt_organisation_socle.json` ; description = éditeur natif (`the_content()`), pas de champ ACF dédié, voir `DECISIONS.md`
- [x] Champ spécifique `sous_structures` pour `service_diocesain` — `acf-json/group_dz_cpt_service_diocesain.json`
- [x] Étendre le groupe socle (règles de localisation) à `mouvement`/`association`/`aumonerie`/`etablissement` — champs spécifiques `mouvement_aumonier` (relation `pretre`, non bidirectionnelle, `acf-json/group_dz_cpt_mouvement.json`), `type_etablissement`/`etablissement_contact` (`acf-json/group_dz_cpt_etablissement.json`). `ancien_eveque` reste hors socle, groupe dédié `acf-json/group_dz_cpt_ancien_eveque.json` (période uniquement — photo = featured image, biographie = `the_content()`) — voir `DECISIONS.md` PROMPT 14/15
- [x] Créer les couples `single-{cpt}.php`/`archive-{cpt}.php` pour `conseil`, `service_diocesain`, `commission_diocesaine` + template-parts partagés `card-organisation.php`/`organisation-composition.php`
- [x] Créer les couples `single-{cpt}.php`/`archive-{cpt}.php` pour `mouvement`, `association`, `aumonerie`, en réutilisant les mêmes template-parts — `archive-aumonerie.php` ajoute des onglets de filtre par `type_aumonerie`
- [x] Créer les couples `single-{cpt}.php`/`archive-{cpt}.php` pour `etablissement` (mêmes template-parts que les autres CPT du socle) et `ancien_eveque` (gabarits dédiés `template-parts/card-ancien-eveque.php` + layout "author-profile" réutilisé de `single-pretre.php`, archive triée chronologiquement par date de début de mandat via `dz_ancien_eveque_archive_query()`)
- [ ] Saisir le contenu réel (responsable + membres) des entrées `conseil`/`service_diocesain`/`commission_diocesaine`/`mouvement`/`association`/`aumonerie`/`etablissement` depuis `NOMINATIONS_SERVICES_COMMISSIONS_AUMONERIES_2027.pdf` — **bloqué : ce PDF n'est pas présent dans ce dépôt**, seul un brouillon structurel (`bin/seed-cpt-organisation.php`, statut `draft`, champs responsable/membres vides) a pu être créé à titre de test des gabarits, pour l'instant limité aux 3 CPT du Prompt 13
- [ ] Saisir le contenu réel des entrées `ancien_eveque` (photo, période, biographie de chaque évêque) — nécessite les archives historiques du diocèse, non fournies dans ce dépôt
- [x] Créer la taxonomie `type_aumonerie` (scolaire/universitaire/santé/carcérale) — `inc/cpt-aumonerie.php`, termes fixes pré-créés à l'`init`, filtrage de l'archive via `?type_aumonerie=<slug>` (pas de route d'archive de taxonomie dédiée, voir `DECISIONS.md`)
- [x] Créer la taxonomie `doyenne` sur `paroisse` — `inc/cpt-paroisse.php`, `dz_register_taxonomy_doyenne()` (`hierarchical`, `show_ui`/`show_admin_column` → apparaît dans la métabox de l'écran d'édition d'une paroisse ; pas de termes pré-créés, liste ouverte, voir `DECISIONS.md` PROMPT 16). Assigner les paroisses existantes reste à faire (pas de contenu réel de paroisses saisi dans ce dépôt)
- [x] Créer la taxonomie `evenement_type` (Diocésain/Évêque) sur `evenement` — `inc/cpt-evenement.php`, mêmes pattern/termes fixes que `type_aumonerie` ; `archive-evenement.php` ajoute des onglets de filtre (Tous / Agenda Diocésain / Agenda de l'évêque), `.archive-filters` (classe renommée depuis `.aumonerie-filters`, désormais partagée par les deux archives)
- [x] Créer les catégories manquantes sur les Actualités : Cathéchèses, Communiqués, Nécrologie, Vatican, Diocèse (Homélies déjà en place) — `inc/categories-actualites.php`, même pattern de seed idempotent que les taxonomies ci-dessus
- [x] Étendre `DZ_Walker_Nav_Menu` (déjà en place, Phase 2) pour supporter le mega-menu hybride sur les 4 rubriques riches, tout en gardant le comportement dropdown 1 niveau déjà testé pour les rubriques légères — voir `DECISIONS.md` "Mega-menu hybride" (PROMPT 17). Bascule par élément de menu via le champ ACF `dz_nav_item_megamenu` (`acf-json/group_dz_nav_menu_item.json`) ; `header.php` passe désormais `depth => 3` à `wp_nav_menu()`. Validé visuellement (desktop + mobile, ouverture/fermeture clic, Échap, clic extérieur) via une maquette HTML statique + Chrome headless, faute d'installation WordPress réelle dans ce dépôt — voir `DECISIONS.md`. **Reste à faire une fois un WordPress réel disponible** : construire le vrai menu "primary" dans wp-admin (Apparence > Menus) à partir d'`Arborescence_PDF.pdf` et cocher la case "Afficher en méga-menu" sur les 4 rubriques riches.
- [x] Créer les pages statiques listées dans `SPEC.md` §3 (Mot de l'évêque, Contacts, Évêché, Chancellerie, Historique, L'évêque, Cartographie du diocèse, Vie Consacrée, Prières, Pèlerinages Nationaux, Pèlerinages Diocésains, Devenir bénévole, Secrétariat diocésain) — `page.php` (gabarit générique désormais implémenté) + `page-cartographie.php` (Template Name dédié, embed carte) ; contenu de test créé par `bin/seed-static-pages.php` (statut `draft`, même logique que `bin/seed-cpt-organisation.php`)
- [ ] Saisir le contenu réel de la circulaire de nominations dans les nouveaux CPT (Économat, Caritas, ODEC, mouvements, associations, aumôneries...)
- [x] Importer le calendrier diocésain 2026-2027 dans le CPT `evenement` — voir Phase 9 PROMPT 2 ci-dessous (40 activités réelles, `inc/import/data-calendrier.php`)
- [x] Appliquer la palette de couleurs des armoiries (bleu/or/vert) dans `assets/css/main.css` — les 3 teintes sont échantillonnées directement dans `logo-diocese-ziguinchor.png` (script Python/Pillow, non versionné) puis assombries en conservant la même teinte (H) pour rester lisibles en texte/blanc sur fond (ratios WCAG calculés) : `--heading-color: #173e4c` (bleu/azur de l'écu, contraste ~11.5:1 sur blanc), `--accent-color`/`--nav-hover-color`/`--nav-dropdown-hover-color: #8c6c2a` (or/jaune de la croix et de la tiare, ~4.9:1 sur blanc), `--bs-success`/`--bs-success-rgb: #2b7a3f` (vert des croix fleuries, ~5.3:1 — retinte uniquement le badge de statut "en cours" déjà utilisé par `single-evenement.php` via `.text-bg-success`, "usage ponctuel" au sens de `SPEC.md` §10, sans toucher au markup Bootstrap). Voir `DECISIONS.md`.
- [x] Intégrer le logo/armoiries officiel dans la page d'options "Réglages du thème" (déjà existante, Phase 2) + favicon — nouvelle fonction `dz_import_run_logo()` dans `inc/import/import-tools.php` (6ᵉ bouton de l'outil "Réglages > Import contenu diocèse", `dz_import_get_sources()`) : sideload de `logo-diocese-ziguinchor.png` comme valeur par défaut du champ `dz_logo` (déjà rendu par `header.php`), puis génération d'un favicon carré (`site_icon`) par recadrage centré via `WP_Image_Editor` (le PNG source, 6512×6041, n'est pas parfaitement carré). Idempotence "skip-once" (comme le hero, pas un upsert) : ne touche jamais un logo/favicon déjà défini, y compris manuellement par un rédacteur. Testé par script PHP autonome (idempotence sur 2 imports + non-écrasement d'une valeur manuelle préexistante, calcul du recadrage carré centré vérifié). Voir `DECISIONS.md`.

## Phase 1 — Socle du thème

- [x] Créer l'arborescence du thème `diocese-ziguinchor` (voir SPEC.md §5) — fichiers de gabarits/inc/template-parts créés en stubs (garde `ABSPATH` + TODO de phase), à implémenter aux phases suivantes
- [x] Écrire `style.css` (en-tête thème obligatoire) et `functions.php` (enqueue des assets vendor : Bootstrap, Bootstrap Icons, AOS, Swiper, PureCounter, `main.css`, `main.js`)
- [x] Copier le dossier `assets/vendor/` et `assets/img/` du template dans le thème (le vendor `php-email-form` n'a volontairement pas été copié, remplacé par Contact Form 7 — voir DECISIONS.md)
- [x] Activer les supports de thème nécessaires (`post-thumbnails`, `title-tag`, `html5`, `automatic-feed-links`) — dans `inc/theme-setup.php`
- [x] Enregistrer l'emplacement de menu principal + menu footer — `register_nav_menus()` dans `inc/theme-setup.php`

## Phase 2 — Header / Footer / Navigation

- [x] Convertir le header commun en `header.php` (`wp_nav_menu()` pour le menu principal)
- [x] Implémenter le Walker custom pour le menu à 2 niveaux (voir DECISIONS.md — simplification du Deep Dropdown) — `inc/class-dz-walker-nav-menu.php`
- [x] Convertir le footer commun en `footer.php` (liens footer via ACF Options, réseaux sociaux via champs ACF Options)
- [x] Créer la page d'options ACF "Réglages du thème" (logo, réseaux sociaux, coordonnées, footer) — `inc/acf-fields.php`, slug `dz-theme-settings`

## Phase 3 — Custom Post Types & ACF

- [x] Enregistrer le CPT `paroisse` + champs ACF (voir SPEC.md §3) — `inc/cpt-paroisse.php`, `acf-json/group_dz_cpt_paroisse.json`
- [x] Enregistrer le CPT `pretre` + champs ACF + relation vers `paroisse` — `inc/cpt-pretre.php`, `acf-json/group_dz_cpt_pretre.json` (relation bidirectionnelle, voir DECISIONS.md)
- [x] Enregistrer le CPT `evenement` + champs ACF — `inc/cpt-evenement.php`, `acf-json/group_dz_cpt_evenement.json`
- [x] Enregistrer le CPT `sacrement` + champs ACF — `inc/cpt-sacrement.php`, `acf-json/group_dz_cpt_sacrement.json`
- [x] Exporter la config ACF en JSON dans le thème (`acf-json/`) pour versionner les champs avec le code
- [x] Champ ACF "Mettre à la une" (`post_a_la_une`) sur le type natif `post`, pour le slider d'actualités de l'accueil — `acf-json/group_dz_post_a_la_une.json`

## Phase 4 — Gabarits de pages

- [x] `front-page.php` — hero slider (repeater ACF `dz_front_hero_slides`, max 5, groupe `group_dz_front_hero` attaché à la page d'accueil statique — remplace `dz_hero_slides` de la page d'options, voir DECISIONS.md) + actualités à la une (`WP_Query` sur `post_a_la_une`) ; états vides gérés proprement pour les deux sections ; accès rapides (Paroisses/Prêtres/Sacrements/Dons) — `template-parts/quick-links.php`, réutilise `.organisation-card` transformée en lien entièrement cliquable, voir `DECISIONS.md` ; **vérifié en conditions réelles sur diocesezig.sn** : les 4 tuiles s'affichent et fonctionnent ; `category-section`/`latest-posts` restent hors périmètre, sans équivalent métier défini
- [x] `page-about.php` — chiffres clés (badges PureCounter) administrables via ACF (`page_template == page-about.php`), contenu principal via `the_content()`
- [x] `page-contact.php` — coordonnées/réseaux (déjà sur la page d'options, Tâche 2) + carte Google Maps (adresse géocodée) + point de montage Contact Form 7 via `the_content()` (plugin/formulaire à installer en Phase 6)
- [x] `page-dons.php` — modalités de don en repeater ACF (RIB, Mobile Money...), 100% informatif, aucune intégration de paiement (voir BUGS_AND_ROADMAP.md)
- [x] `page.php` — gabarit générique pour pages simples (voir `DECISIONS.md` PROMPT 16) ; `page-cartographie.php` ajouté en complément (Template Name dédié, embed carte)
- [x] `single.php` — depuis `blog-details.html` (hero + contenu natif + partage + tags + auteur + commentaires natifs)
- [x] `archive.php` — depuis `category.html` (boucle + pagination native + sidebar `template-parts/sidebar-blog.php`)
- [x] `search.php` — depuis `search-results.html` (boucle + pagination native, sans sidebar comme dans la source)
- [x] `author.php` — depuis `author-profile.html` (données réelles de l'auteur WP, voir DECISIONS.md)
- [x] `404.php` — depuis `404.html` (icône, code, titre, texte, recherche connectée à la vraie recherche WordPress, retour à l'accueil) ; était un stub vide, corrigé au PROMPT 12 (voir `BUGS_AND_ROADMAP.md`)
- [x] `index.php` (gabarit de secours WordPress générique, minimal et sans hypothèse de type de contenu) ; était un stub vide, corrigé au PROMPT 12 (voir `BUGS_AND_ROADMAP.md`)
- [x] `comments.php` + `searchform.php` créés (non listés dans SPEC.md §5, nécessaires pour des commentaires/une recherche natifs fonctionnels — voir DECISIONS.md)
- [x] `template-parts/card-article.php` (partagé entre `archive.php` et `search.php`) et `template-parts/page-title.php` (bannière titre + fil d'Ariane, partagée par tous les gabarits de contenu)

## Phase 5 — Gabarits métier (Paroisses, Prêtres, Événements, Sacrements)

- [x] `single-paroisse.php` + `archive-paroisse.php` (annuaire + fiche paroisse — coordonnées, horaires, carte, curé/vicaires)
- [x] `single-pretre.php` + `archive-pretre.php` (annuaire + fiche prêtre — fonction, ordination, paroisse d'affectation)
- [x] `single-evenement.php` + `archive-evenement.php` (agenda filtré sur événements à venir/en cours — voir SPEC.md §4 et DECISIONS.md ; tri du plus proche au plus lointain)
- [x] `single-sacrement.php` (étapes, documents à télécharger, paroisse référente ; pas d'archive-sacrement.php, cf. SPEC.md)
- [x] Template-parts réutilisables : toutes faites (`card-article.php`, `card-paroisse.php`, `card-pretre.php`, `card-evenement.php`)

## Phase 6 — Formulaires & sécurité

- [x] Installer le plugin Contact Form 7, créer le formulaire et coller son shortcode dans le contenu de la page Contact (le point de montage `the_content()` et le CSS de base — `.wpcf7-form-control` + `.form-floating` — sont déjà en place, voir `page-contact.php` et DECISIONS.md, Tâche 8) ; destinataire du mail forcé dynamiquement vers `dz_contact_email` (page d'options) via `inc/contact-form.php` (`wpcf7_before_send_mail`), voir DECISIONS.md
- [ ] Ajouter la protection anti-spam (honeypot + reCAPTCHA v3 si retenu)
- [x] Revue de sécurité du code du thème (échappement, sanitization, `ABSPATH`, notices/warnings potentielles) — Tâche 9, voir DECISIONS.md et BUGS_AND_ROADMAP.md. Nonces : sans objet, aucun formulaire admin custom dans le thème (commentaires natifs, recherche native, Contact Form 7 gèrent déjà les leurs)

## Phase 7 — Contenu & médias

- [ ] Importer les images du template dans la médiathèque WordPress (ou les nouvelles photos officielles si disponibles — Phase 0)
- [ ] Saisir les premières fiches Paroisses / Prêtres / Sacrements
- [ ] Rédiger/importer les premières actualités
- [ ] Remplacer tous les textes Lorem Ipsum restants

## Phase 8 — QA & mise en production

- [x] Revue design globale de tous les gabarits (PROMPT 12) — cohérence titres/fils d'ariane, absence de Lorem Ipsum/placeholder du template source, classes de grille Bootstrap (`col-lg-4 col-md-6` uniforme sur les 11 archives, `col-lg-8/col-lg-4` uniforme sur les fiches — écart `col-lg-7/col-lg-5` de `single-paroisse.php` justifié par sa grille de clergé, pas une incohérence), présence de `data-aos`, menu mobile non dupliqué hors `header.php`. Revue au niveau du code uniquement (grep systématique + relecture) : pas d'installation WordPress réelle dans ce dépôt pour un rendu/test visuel complet (voir `DECISIONS.md` PROMPT 17 pour la même limite déjà rencontrée) — 2 gabarits stub (`404.php`, `index.php`) et 1 écart d'animation (`page-contact.php`) trouvés et corrigés, voir `BUGS_AND_ROADMAP.md`
- [ ] Checklist QA responsive (mobile/tablette/desktop) sur chaque gabarit — en conditions réelles (navigateur), pas seulement la revue de code du PROMPT 12 ci-dessus
- [ ] Vérification `WP_DEBUG` sans notice/warning sur une vraie installation WordPress (revue statique du code déjà faite en Tâche 9 — reste à confirmer en conditions réelles : aucun environnement WordPress actif dans ce dépôt pour l'exécuter)
- [ ] Test du formulaire de contact en conditions réelles
- [ ] Vérification des performances (poids des sliders/images, lazy loading)
- [ ] Mise en production

## Phase 9 — Import de contenu réel (`CONTENT_PROMPTS.md`)

- [x] PROMPT 0 — Mécanisme d'import réutilisable : dossier `inc/import/` (`data-hero.php`, `data-calendrier.php`, `data-nominations.php` — tableaux PHP vides pour l'instant, aucune logique, voir chaque fichier) + page d'outil admin "Réglages > Import contenu diocèse" (`inc/import/import-tools.php`, `add_options_page`, capability `manage_options`, nonce par bouton, POST-redirect-GET) + idempotence via meta `_dz_import_source_id` (`dz_import_find_existing_post()`/`dz_import_mark_imported()`). Vérifié au préalable : Prompts 13 à 16 de `DESIGN_PROMPTS.md` bien terminés (CPT organisationnels + taxonomies déjà enregistrés). Voir `DECISIONS.md`.
- [x] PROMPT 1 — Hero page d'accueil : 4 images déplacées vers `wp-content/themes/diocese-ziguinchor/assets/seed-images/hero/` (`git mv`, l'ancien `assets/img/eveque/` racine n'était pas dans le thème réellement déployé — voir `DECISIONS.md`), `inc/import/data-hero.php` peuplé, `dz_import_run_hero()` implémentée (sideload via fichier temporaire + repeater `dz_front_hero_slides`, idempotence par sous-champ `dz_front_hero_slide_source_id`). Recadrage des 2 images carrées vérifié par rendu réel du CSS (Chrome headless) : aucun `object-position` spécifique nécessaire. Logique testée par script PHP autonome (11 assertions). Vérification finale en conditions réelles (site déployé) toujours à faire, voir `BUGS_AND_ROADMAP.md`
- [x] PROMPT 2 — Calendrier diocésain 2026-2027 → CPT `evenement` : `documents/CALENDRIER DIOCESAIN 2027.pdf` (scan sans couche de texte, une seule page A4) lu directement via rastérisation 300dpi (même méthode qu'au PROMPT 3bis/4). `inc/import/data-calendrier.php` peuplé des 40 activités réelles (année pastorale octobre 2026 → octobre 2027, dont la ligne de clôture "1er octobre 2027" qui ouvre le cycle suivant), année toujours résolue explicitement à partir de l'en-tête de mois du tableau (jamais laissée implicite). Un seul événement en `evenement_type => eveque` (anniversaire d'ordination épiscopale de Mgr Manga, 21-22 novembre 2026), les 39 autres en "Diocésain" par défaut. `dz_import_run_calendrier()` (déjà implémentée/testée au PROMPT 2 initial) ne nécessitait aucun ajustement — vérifié en la relançant contre les vraies données. Testé par script PHP autonome : intégrité des données (pas de `source_id` dupliqué, dates `Y-m-d H:i:s` valides, `date_fin >= date_debut`), 40 créations puis idempotence totale au 2ᵉ passage, répartition `evenement_type` (39 diocésain / 1 évêque) — toutes assertions passées. Comme prévu, aucun `lieu` ne se relie à une fiche `paroisse` (CPT encore vide) : tout reste en texte libre pour l'instant, pas un bug (voir `DECISIONS.md`).
- [x] PROMPT 3 — Circulaire de nominations → `service_diocesain`/`commission_diocesaine`/`mouvement`/`association` : `inc/import/data-nominations.php` peuplé des 33 entités réelles (10/10/6/7) + 4 sous-structures de l'Économat. `bin/seed-cpt-organisation.php` recentré sur "Conseil épiscopal" uniquement pour éviter un doublon (voir `DECISIONS.md`).
- [x] PROMPT 3bis — `responsable`/`membres` réels transcrits pour les 33 entités (110 lignes de membres, contenu de la Circulaire n°002/2026-2027) ; import passé en mode mise à jour (upsert) — `dz_import_find_existing_post()` inchangée, `dz_import_upsert_organisation_post()` complète les entités déjà créées plutôt que de les ignorer. Aumôniers `mouvement` reliés à une fiche `pretre` quand elle existe, sinon nom conservé dans le contenu de la fiche (voir `BUGS_AND_ROADMAP.md` : la quasi-totalité restent à relier manuellement, le CPT `pretre` étant vide). Vérifié contre le vrai PDF (désormais dans `documents/`, scan sans couche de texte — lu directement page par page) : aucune erreur trouvée, 2 écarts mineurs corrigés + rôles professionnels enrichis, voir `DECISIONS.md`/`BUGS_AND_ROADMAP.md`. 16 assertions via script PHP autonome. Statut `draft` conservé, à relire/publier manuellement
- [x] PROMPT 4 — Aumôneries et enseignements diocésains → `aumonerie`/`etablissement` : `inc/import/data-aumoneries.php` peuplé de 18 aumôneries réelles (3 scolaires/8 universitaires/5 santé/2 carcérales, sections V-VIII de la circulaire) + 1 établissement (ISPS, seul "Enseignement diocésain" de l'arborescence sans fiche `aumonerie` existante — voir `DECISIONS.md` pour DIDEC/Collèges Diocésains/UCAO déjà couverts ailleurs, Séminaires sans donnée). Nouveau bouton "Importer les aumôneries et enseignements" (`dz_import_run_aumoneries()`). Vérifié contre le vrai PDF (sections V-VIII lues directement, `Arborescence_PDF.pdf` via `pdftotext`). 18 assertions via script PHP autonome. Statut `draft`, à relire/publier manuellement
- [x] PROMPT 5 — Contenu "À propos" et armoiries : page "Historique" (déjà créée vide au PROMPT 16, ligne retirée de `bin/seed-static-pages.php` pour éviter le doublon — voir `DECISIONS.md`) remplie avec l'intro + les 6 points + la conclusion du document ; nouvelle page dédiée **"Nos armoiries"** (`page-armoiries.php`, Template Name), alternance image/texte via `.about`/`.about-img` + `.flex-row-reverse` (zéro CSS custom), logo `logo-diocese-ziguinchor.png` en image mise en avant (réutilisée à chaque ligne, une seule photo réelle disponible). Nouveau champ ACF `dz_armoiries_symboles` (repeater, `group_dz_page_armoiries`). `inc/import/data-armoiries.php` peuplé (2 clés : `historique`, `armoiries_page`, 10 symboles), `dz_import_upsert_page()` (helper générique page, sans socle organisationnel) + `dz_import_run_armoiries_historique()`/`dz_import_run_armoiries_page()`/`dz_import_run_armoiries()`, 5ᵉ bouton "Importer le contenu 'À propos' et les armoiries". Vérifié contre le vrai PDF (texte natif, `pdftotext -layout`, aucun écart). Testé par script PHP autonome (idempotence 2 passages, contenu, image mise en avant, 10 lignes de repeater en ordre) — toutes assertions passées
- [x] PROMPT 6 — Vérification finale et nettoyage :
  1. **Idempotence** — audit systématique de tous les points d'écriture des 8 fonctions `dz_import_run_*()` (Prompts 1 à 5) : les 3 seuls appels à `wp_insert_post()` du dossier (`dz_import_run_calendrier()`, `dz_import_upsert_organisation_post()`, `dz_import_upsert_page()`) sont chacun précédés d'un `dz_import_find_existing_post()` bloquant (skip ou upsert selon la fonction), et les 2 appels à `dz_import_sideload_image()` (hero, armoiries) sont chacun protégés par leur propre garde (`in_array()` sur le `source_id` de la slide côté hero, `! has_post_thumbnail()` côté armoiries) — aucun chemin ne peut créer de doublon en cliquant deux fois sur un même bouton. Aucune correction nécessaire, voir `DECISIONS.md`.
  2. **Données personnelles sensibles** — vérifié contre le PDF scanné rastérisé (4 des 11 pages relues : sous-structures de l'Économat, associations/groupes d'apostolat, aumôneries des établissements de santé, page de clôture/signature) : la circulaire de nominations ne contient **aucun numéro de téléphone ni adresse e-mail personnelle**, uniquement noms/titres/rôles/affiliations — rien à exclure a posteriori. Confirmé aussi par `grep` sur les 5 fichiers `inc/import/data-*.php` (aucun motif téléphone/e-mail) et par le schéma ACF du socle organisationnel (`group_dz_cpt_organisation_socle` : `org_responsable` texte libre, `org_membres` = nom + rôle uniquement, aucun sous-champ contact). Le seul champ de contact existant (`etablissement_contact`, fiche ISPS) reste vide faute de donnée dans la source. Voir `DECISIONS.md`.
  3. **Bilan importé/manuel** — voir le nouveau récapitulatif juste en dessous.
  4. **Outil admin** — conservé tel quel après validation (pas de suppression) : ses garde-fous d'idempotence le rendent réutilisable sans risque pour une future circulaire de nominations. Voir `DECISIONS.md`.

### Bilan de l'import (PROMPT 6) — automatique vs. saisie manuelle restante

**Importé automatiquement (bouton admin, idempotent, prêt à être relu/publié) :**
- Hero de la page d'accueil : 4 slides (image + titre + sous-titre + lien), `dz_import_run_hero()`
- 33 entités organisationnelles réelles (10 services diocésains, 10 commissions, 6 mouvements, 7 associations) + 4 sous-structures de l'Économat, `responsable`/`membres` complets (110 lignes), statut `draft`
- 18 aumôneries (3 scolaires/8 universitaires/5 santé/2 carcérales) + 1 établissement (ISPS), statut `draft`
- Contenu texte de la page "Historique" (6 points sur l'importance des armoiries) + nouvelle page "Nos armoiries" (10 symboles, image du blason)
- Logo/favicon par défaut (`dz_import_run_logo()`, PROMPT 1) et palette de couleurs réelle du diocèse dans `assets/css/main.css`
- Calendrier diocésain 2026-2027 : 40 activités réelles (`inc/import/data-calendrier.php`), statut `publish` (contenu déjà public par nature, contrairement aux fiches organisationnelles)

**Reste à saisir manuellement (aucune source fournie dans ce dépôt pour ces données) :**
- Fiches `pretre` (CPT vide) : nécessaire pour relier les ~6 aumôniers de `mouvement` déjà nommés en texte (voir `BUGS_AND_ROADMAP.md`) et pour toute paroisse/fiche prêtre du site
- Fiches `paroisse` (CPT vide) : nécessaire pour que les relations `evenement_paroisse`/`paroisse` du calendrier se rattachent correctement plutôt qu'en texte libre (tous les 40 événements importés restent en `lieu` texte libre pour l'instant — voir `DECISIONS.md`)
- Photos des personnes nommées dans la circulaire (responsables/membres) : aucune photo fournie, les CPT organisationnels n'ont pas de champ photo par membre dans le socle actuel
- Biographies détaillées (évêque, anciens évêques `ancien_eveque`, prêtres) : `SPEC.md` prévoit ces champs mais aucun texte biographique réel n'est disponible dans ce dépôt
- "Séminaires et Maisons de formation" (`etablissement`) : aucun nom d'établissement documenté dans les sources disponibles, catégorie vide
- Toute donnée de contact (téléphone/e-mail) de `paroisse`/`pretre`/`etablissement` : champs existants dans le schéma ACF mais non couverts par la circulaire (qui n'en contient aucune, voir point 2 ci-dessus) — à saisir directement par le secrétariat diocésain via l'admin WordPress une fois les fiches créées
- Relecture éditoriale et passage en `publish` des ~52 fiches organisationnelles/aumôneries créées en `draft` (vérification humaine des noms avant publication publique, cf. `DECISIONS.md`)
