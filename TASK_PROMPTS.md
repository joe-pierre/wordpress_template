# Tâches à définir

> Chaque tâche correspond à une phase de `TODO.md`. Copier-coller le prompt de la tâche voulue dans Claude Code après le prompt d'amorçage de `CLAUDE.md`.

## TÂCHE 1 — Socle du thème WordPress

Crée l'arborescence complète du thème `diocese-ziguinchor` telle que décrite dans `SPEC.md` §5 (Architecture code). Écris `style.css` (en-tête de thème WordPress obligatoire, avec nom, description, auteur, version) et `functions.php` avec :
- l'enqueue de tous les assets vendor du template d'origine (Bootstrap 5.3.7, Bootstrap Icons, AOS, Swiper, PureCounter, `main.css`, `main.js`) via `wp_enqueue_style`/`wp_enqueue_script`, en respectant le préfixe `dz_` défini dans `CONVENTIONS.md` ;
- l'activation des supports de thème (`post-thumbnails`, `title-tag`, `html5`, `automatic-feed-links`) ;
- l'enregistrement d'un emplacement de menu principal et d'un emplacement de menu footer via `register_nav_menus()`.
Ne code aucune donnée métier (paroisses, prêtres...) dans cette tâche — uniquement le socle technique.

## TÂCHE 2 — Header, footer et menu de navigation

À partir du HTML commun identifié dans le template source (header et footer présents sur toutes les pages), crée `header.php` et `footer.php`. Utilise `wp_nav_menu()` pour le menu principal, avec un `Walker_Nav_Menu` custom limitant l'affichage à 2 niveaux de profondeur (voir `DECISIONS.md` — simplification du "Deep Dropdown"). Crée une page d'options ACF ("Réglages du thème") pour : logo, liens réseaux sociaux, coordonnées de contact, colonnes de liens du footer. N'oublie pas `wp_head()` et `wp_footer()` aux emplacements corrects.

## TÂCHE 3 — Custom Post Types et champs ACF

Enregistre les 4 Custom Post Types définis dans `SPEC.md` §3 (`paroisse`, `pretre`, `evenement`, `sacrement`), chacun dans son propre fichier sous `inc/cpt-{type}.php`, avec labels en français, slugs conformes à `CONVENTIONS.md`. Définis les groupes de champs ACF correspondants (exportés en JSON dans `acf-json/` pour être versionnés avec le code) en respectant les champs et relations décrits dans `SPEC.md` §3. Vérifie que le champ "Mettre à la une" existe bien sur le type natif `post` pour piloter le slider de la page d'accueil (voir `SPEC.md` §4).

## TÂCHE 4 — Page d'accueil (`front-page.php`)

Convertis la structure de `index.html` (hero slider Swiper + section "Featured Posts") en `front-page.php`. Le hero slider doit boucler sur un repeater ACF limité à 5 slides (voir `DECISIONS.md`). La section "Featured Posts" doit boucler sur les articles natifs `post` ayant le champ ACF "à la une" coché, via `WP_Query`. Conserve exactement le pattern de configuration JSON inline Swiper (`<script type="application/json" class="swiper-config">`) tel qu'il existe dans le template source — ne le réécrit pas en JS séparé.

## TÂCHE 5 — Gabarits Actualités (natifs WordPress)

Crée `single.php` (à partir de `blog-details.html`), `archive.php`/`category.php` (à partir de `category.html`), `author.php` (à partir de `author-profile.html`) et `search.php` (à partir de `search-results.html`). Utilise les boucles WordPress natives (`have_posts()`/`the_post()`), `the_post_thumbnail()`, `the_excerpt()`/`the_content()`. Crée le template-part `template-parts/card-article.php` pour éviter la duplication entre `archive.php` et `search.php`.

## TÂCHE 6 — Gabarits métier : Paroisses et Prêtres

Crée `single-paroisse.php`, `archive-paroisse.php`, `single-pretre.php`, `archive-pretre.php`, ainsi que les template-parts `card-paroisse.php` et `card-pretre.php`. Affiche sur la fiche paroisse le curé responsable (relation ACF vers `pretre`) et sur la fiche prêtre sa paroisse d'affectation (relation inverse). Vérifie l'existence des champs relationnels avant affichage pour éviter toute erreur PHP sur une fiche incomplète (voir `CONVENTIONS.md` §Validation).

## TÂCHE 7 — Gabarits métier : Événements et Sacrements

Crée `single-evenement.php`, `archive-evenement.php` (filtré pour n'afficher que les événements à venir — voir règle métier dans `SPEC.md` §4) et `single-sacrement.php`. Crée le template-part `card-evenement.php`.

## TÂCHE 8 — Pages institutionnelles (À propos, Contact, Dons)

Crée `page-about.php` (à partir de `about.html`, en rendant les badges compteurs PureCounter administrables via ACF), `page-contact.php` (formulaire Contact Form 7 intégré au style `form-floating` du template + carte) et `page-dons.php` (informative en v1, voir `SPEC.md` §3). N'implémente aucune intégration de paiement en ligne dans cette tâche (hors périmètre v1, voir `BUGS_AND_ROADMAP.md`).

## TÂCHE 9 — Sécurité et QA

Relis l'ensemble des templates créés dans les tâches précédentes et vérifie systématiquement : échappement des sorties (`esc_html`, `esc_url`, `esc_attr`), sanitization des entrées, présence de `if ( ! defined( 'ABSPATH' ) ) exit;` en tête de chaque fichier PHP hors templates de page WordPress, absence de notice/warning en `WP_DEBUG`. Documente toute correction dans `DECISIONS.md`/`BUGS_AND_ROADMAP.md` selon le format défini dans `CLAUDE.md`.
