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
- [ ] Importer le calendrier diocésain 2026-2027 dans le CPT `evenement`
- [ ] Appliquer la palette de couleurs des armoiries (bleu/or/vert) dans `assets/css/main.css`
- [ ] Intégrer le logo/armoiries officiel dans la page d'options "Réglages du thème" (déjà existante, Phase 2) + favicon

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
- [ ] Créer la taxonomie `secteur_pastoral` si confirmée en Phase 0 (toujours en attente de confirmation client)
- [x] Exporter la config ACF en JSON dans le thème (`acf-json/`) pour versionner les champs avec le code
- [x] Champ ACF "Mettre à la une" (`post_a_la_une`) sur le type natif `post`, pour le slider d'actualités de l'accueil — `acf-json/group_dz_post_a_la_une.json`

## Phase 4 — Gabarits de pages

- [x] `front-page.php` — hero slider (repeater ACF `dz_front_hero_slides`, max 5, groupe `group_dz_front_hero` attaché à la page d'accueil statique — remplace `dz_hero_slides` de la page d'options, voir DECISIONS.md) + actualités à la une (`WP_Query` sur `post_a_la_une`) ; états vides gérés proprement pour les deux sections ; accès rapides (Paroisses/Prêtres/Sacrements/Dons) pas encore fait — hors périmètre de cette passe, à ajouter avec `category-section`/`latest-posts`
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

- [ ] Installer le plugin Contact Form 7, créer le formulaire et coller son shortcode dans le contenu de la page Contact (le point de montage `the_content()` et le CSS de base — `.wpcf7-form-control` + `.form-floating` — sont déjà en place, voir `page-contact.php` et DECISIONS.md, Tâche 8)
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
- [x] PROMPT 2 (mécanisme) / [ ] (données) — Calendrier diocésain 2026-2027 → CPT `evenement` : `dz_import_run_calendrier()` entièrement implémentée et testée (relation `paroisse` automatique par titre, `evenement_type` par défaut "Diocésain", idempotence, événements passés visibles en admin — voir `DECISIONS.md`) ; `inc/import/data-calendrier.php` reste vide, **`CALENDRIER_DIOCESAIN_2027.pdf` absent de ce dépôt** (voir `BUGS_AND_ROADMAP.md`) — transcription bloquée tant que le PDF n'est pas fourni
- [x] PROMPT 3 — Circulaire de nominations → `service_diocesain`/`commission_diocesaine`/`mouvement`/`association` : `inc/import/data-nominations.php` peuplé des 33 entités réelles (10/10/6/7) + 4 sous-structures de l'Économat. `bin/seed-cpt-organisation.php` recentré sur "Conseil épiscopal" uniquement pour éviter un doublon (voir `DECISIONS.md`).
- [x] PROMPT 3bis — `responsable`/`membres` réels transcrits pour les 33 entités (110 lignes de membres, contenu de la Circulaire n°002/2026-2027) ; import passé en mode mise à jour (upsert) — `dz_import_find_existing_post()` inchangée, `dz_import_upsert_organisation_post()` complète les entités déjà créées plutôt que de les ignorer. Aumôniers `mouvement` reliés à une fiche `pretre` quand elle existe, sinon nom conservé dans le contenu de la fiche (voir `BUGS_AND_ROADMAP.md` : la quasi-totalité restent à relier manuellement, le CPT `pretre` étant vide). Vérifié contre le vrai PDF (désormais dans `documents/`, scan sans couche de texte — lu directement page par page) : aucune erreur trouvée, 2 écarts mineurs corrigés + rôles professionnels enrichis, voir `DECISIONS.md`/`BUGS_AND_ROADMAP.md`. 16 assertions via script PHP autonome. Statut `draft` conservé, à relire/publier manuellement
- [ ] `documents/` (PDF sources réels) désormais disponible dans ce dépôt — `CALENDRIER_DIOCESAIN_2027.pdf` pourrait lever le blocage du PROMPT 2 (`inc/import/data-calendrier.php`, toujours vide) dans une prochaine passe ; `Arborescence_PDF.pdf`/`Copie_de_Armoiries_Diocèse_de_Ziguinchor.pdf` concernent le PROMPT 5
- [ ] PROMPT 4 — Aumôneries et enseignements diocésains → `aumonerie`/`etablissement`
- [ ] PROMPT 5 — Contenu "À propos" et armoiries
- [ ] PROMPT 6 — Vérification finale de l'idempotence, exclusion des données personnelles sensibles, bilan import automatique vs. saisie manuelle restante
