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
- [ ] Créer la taxonomie `doyenne` sur `paroisse` et l'assigner aux paroisses existantes
- [ ] Créer la taxonomie `evenement_type` (Diocésain/Évêque) sur `evenement`
- [ ] Créer les catégories manquantes sur les Actualités : Cathéchèses, Communiqués, Nécrologie, Vatican, Diocèse (Homélies déjà en place)
- [ ] Étendre `DZ_Walker_Nav_Menu` (déjà en place, Phase 2) pour supporter le mega-menu hybride sur les 4 rubriques riches, tout en gardant le comportement dropdown 1 niveau déjà testé pour les rubriques légères — voir `DECISIONS.md` "Mega-menu hybride"
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
- [ ] `page.php` — gabarit générique pour pages simples
- [x] `single.php` — depuis `blog-details.html` (hero + contenu natif + partage + tags + auteur + commentaires natifs)
- [x] `archive.php` — depuis `category.html` (boucle + pagination native + sidebar `template-parts/sidebar-blog.php`)
- [x] `search.php` — depuis `search-results.html` (boucle + pagination native, sans sidebar comme dans la source)
- [x] `author.php` — depuis `author-profile.html` (données réelles de l'auteur WP, voir DECISIONS.md)
- [ ] `404.php`
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

- [ ] Checklist QA responsive (mobile/tablette/desktop) sur chaque gabarit
- [ ] Vérification `WP_DEBUG` sans notice/warning sur une vraie installation WordPress (revue statique du code déjà faite en Tâche 9 — reste à confirmer en conditions réelles : aucun environnement WordPress actif dans ce dépôt pour l'exécuter)
- [ ] Test du formulaire de contact en conditions réelles
- [ ] Vérification des performances (poids des sliders/images, lazy loading)
- [ ] Mise en production
