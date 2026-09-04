# TODO

## Phase 0 — Cadrage (préalable, non technique)

- [ ] Collecter le logo, la charte graphique et les couleurs institutionnelles du diocèse
- [ ] Confirmer la structure organisationnelle (secteurs pastoraux / doyennés existent-ils ?)
- [ ] Confirmer le périmètre "Dons" v1 (juste informatif, ou paiement en ligne dès le lancement ?)
- [ ] Obtenir les textes et photos réels (historique du diocèse, mot de l'évêque, liste des paroisses/prêtres)

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

- [x] `front-page.php` — hero slider (repeater ACF `dz_hero_slides`, max 5) + actualités à la une (`WP_Query` sur `post_a_la_une`) ; accès rapides (Paroisses/Prêtres/Sacrements/Dons) pas encore fait — hors périmètre de cette passe, à ajouter avec `category-section`/`latest-posts`
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
- [ ] Revue de sécurité : échappement des sorties, sanitization des entrées, nonces (voir CONVENTIONS.md)

## Phase 7 — Contenu & médias

- [ ] Importer les images du template dans la médiathèque WordPress (ou les nouvelles photos officielles si disponibles — Phase 0)
- [ ] Saisir les premières fiches Paroisses / Prêtres / Sacrements
- [ ] Rédiger/importer les premières actualités
- [ ] Remplacer tous les textes Lorem Ipsum restants

## Phase 8 — QA & mise en production

- [ ] Checklist QA responsive (mobile/tablette/desktop) sur chaque gabarit
- [ ] Vérification `WP_DEBUG` sans notice/warning
- [ ] Test du formulaire de contact en conditions réelles
- [ ] Vérification des performances (poids des sliders/images, lazy loading)
- [ ] Mise en production
