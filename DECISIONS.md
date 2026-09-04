# Décisions techniques et bugs résolus

## [CHOIX] Utiliser le type de contenu natif `post` pour les Actualités plutôt qu'un CPT dédié

**Contexte :** Le template source ("Story", BootstrapMade) est un template de blog ; le diocèse a besoin d'une rubrique Actualités.
**Symptôme / Problème :** Créer un CPT `actualite` dupliquerait des fonctionnalités déjà natives (catégories, flux RSS, recherche, archives, auteurs).
**Cause / Alternatives :** Alternative envisagée : CPT `actualite` dédié avec taxonomie custom.
**Fix / Décision :** On garde le type natif `post` avec des catégories WordPress classiques (Vie diocésaine, Nominations, Communiqués, etc.). Plus simple pour les rédacteurs, moins de code à maintenir.
**Leçon :** Ne pas créer un CPT quand un type natif WordPress couvre déjà le besoin.
**Statut :** 🔵 Choix assumé

---

## [CHOIX] Remplacer le formulaire PHP natif du template (`php-email-form`) par Contact Form 7

**Contexte :** Le template `contact.html` embarque un script PHP maison (`assets/vendor/php-email-form/`) pour l'envoi du formulaire, validé côté client par `validate.js`.
**Symptôme / Problème :** Ce script ne s'intègre pas au cycle de vie WordPress (pas de nonce, pas de protection anti-spam native, maintenance à la charge du développeur).
**Cause / Alternatives :** Alternatives : (a) redévelopper un handler custom via `admin-post.php` + `wp_mail()` ; (b) utiliser un plugin de formulaire existant.
**Fix / Décision :** Utiliser **Contact Form 7** (léger, largement adopté, anti-spam intégrable). Adapter son rendu HTML pour coller au style `form-floating` du template via CSS custom, afin de ne pas perdre le design d'origine.
**Leçon :** Ne pas réinventer la gestion de formulaire quand un plugin mature et léger existe déjà.
**Statut :** 🔵 Choix assumé

---

## [CHOIX] Simplifier le menu de navigation à 2 niveaux (suppression du "Deep Dropdown" à 3 niveaux)

**Contexte :** Le menu du template d'origine propose un sous-menu "Pages" contenant lui-même un sous-menu "Deep Dropdown" (3 niveaux de profondeur).
**Symptôme / Problème :** `wp_nav_menu()` gère nativement 2 niveaux ; un 3ème niveau demande un `Walker_Nav_Menu` custom, du CSS et du JS additionnels à maintenir, pour un besoin qui n'a pas de justification métier côté diocèse.
**Cause / Alternatives :** Garder les 3 niveaux (fidélité totale au template) vs. simplifier à 2 niveaux.
**Fix / Décision :** Limiter le menu à 2 niveaux de profondeur. Plus lisible pour les visiteurs, plus simple à administrer pour les rédacteurs, moins de code custom à maintenir.
**Leçon :** La fidélité totale à un template de démonstration n'est pas toujours pertinente ; simplifier quand le besoin métier réel ne le justifie pas.
**Statut :** 🔵 Choix assumé

---

## [CHOIX] Pas de CPT pour la page "Dons" en v1

**Contexte :** Le template d'origine ne contient aucune page équivalente ; c'est une création pure pour le diocèse.
**Symptôme / Problème :** Un CPT serait sur-dimensionné pour une page présentant des modalités de don relativement statiques.
**Cause / Alternatives :** CPT `don` avec plusieurs "modes de don" vs. simple page ACF.
**Fix / Décision :** Une page statique (`page-dons.php`) avec champs ACF pour les modalités. L'intégration d'un prestataire de paiement en ligne est reportée en ROADMAP (hors périmètre v1, à cadrer avec le diocèse en Phase 0).
**Leçon :** Ne pas complexifier le modèle de données pour un besoin encore mal défini ; rester simple et itérer.
**Statut :** 🔵 Choix assumé — à revalider si le besoin de paiement en ligne se confirme

---

## [CHOIX] Hero slider de la page d'accueil limité à 5 slides

**Contexte :** Le slider hero (`index.html`, Swiper) n'a pas de limite dans le template d'origine.
**Symptôme / Problème :** Sans limite, un rédacteur pourrait ajouter un nombre excessif de slides, dégradant les performances de la page d'accueil.
**Cause / Alternatives :** Repeater ACF sans limite vs. avec `max`.
**Fix / Décision :** Limiter le repeater ACF des slides à 5 éléments maximum (`max: 5`).
**Leçon :** Anticiper les usages éditoriaux dès la conception des champs, pas seulement après un problème constaté.
**Statut :** 🔵 Choix assumé

---

## [CHOIX] Arborescence du thème créée en stubs dès la Tâche 1 (socle)

**Contexte :** SPEC.md §5 décrit l'arborescence complète du thème (gabarits de page, template-parts, fichiers `inc/`), mais la Tâche 1 ne porte que sur le socle technique (`style.css`, `functions.php`, assets vendor, supports de thème, menus).
**Symptôme / Problème :** Créer uniquement `style.css`/`functions.php` sans le reste de l'arborescence aurait laissé le thème incomplet par rapport à SPEC.md §5, alors qu'implémenter le contenu de chaque gabarit dépasserait le périmètre de la Tâche 1 (et empièterait sur les Phases 2 à 5).
**Cause / Alternatives :** (a) ne créer que `style.css`/`functions.php` et différer le reste ; (b) créer tous les fichiers de SPEC.md §5 en stubs minimaux (garde `if ( ! defined( 'ABSPATH' ) ) exit;` + commentaire `TODO (Phase X)`) sans aucune logique métier.
**Fix / Décision :** Option (b). Tous les fichiers de gabarits (`header.php`, `footer.php`, `front-page.php`, `single-*.php`, `archive-*.php`, `template-parts/*.php`), ainsi que `inc/cpt-*.php` et `inc/acf-fields.php`, existent déjà comme stubs vides à compléter phase par phase. Seuls `functions.php` et `inc/theme-setup.php` contiennent une implémentation réelle (enqueue des assets vendor, supports de thème, `register_nav_menus()`). Un `index.php` de secours (non listé explicitement dans SPEC.md §5 mais requis par WordPress pour qu'un thème soit valide) a également été ajouté.
**Leçon :** Créer la structure complète en stubs référencés (`TODO (Phase X)`) permet de respecter l'architecture cible dès le départ sans anticiper de code métier hors périmètre de la tâche en cours.
**Statut :** 🔵 Choix assumé

---

## [CHOIX] Vendor `php-email-form` non copié dans le thème

**Contexte :** Le template d'origine (`assets/vendor/php-email-form/`) est déjà remplacé par Contact Form 7 (voir décision "Remplacer le formulaire PHP natif...").
**Symptôme / Problème :** Copier ce vendor dans le thème sans jamais l'enqueuer serait du code mort dès le socle.
**Cause / Alternatives :** Copier tous les dossiers de `assets/vendor/` tels quels vs. exclure ceux déjà remplacés par une décision actée.
**Fix / Décision :** `php-email-form` n'est pas copié dans `wp-content/themes/diocese-ziguinchor/assets/vendor/`. Seuls Bootstrap, Bootstrap Icons, AOS, Swiper et PureCounter y sont présents, conformément à la liste d'assets à enqueuer de la Tâche 1.
**Leçon :** Ne pas porter dans le thème un vendor déjà remplacé par une décision actée, même si le template d'origine le contenait.
**Statut :** 🔵 Choix assumé

---

## [CHOIX] Double garde-fou pour la limite de profondeur du menu (2 niveaux)

**Contexte :** La décision "Simplifier le menu de navigation à 2 niveaux" (ci-dessus) fixe la règle métier ; la Tâche 2 demande explicitement un `Walker_Nav_Menu` custom pour l'appliquer.
**Symptôme / Problème :** `wp_nav_menu( array( 'depth' => 2, ... ) )` seul suffit déjà techniquement à empêcher WordPress Core de descendre à un 3ᵉ niveau ; construire aussi la logique dans le Walker est redondant en théorie.
**Cause / Alternatives :** (a) se reposer uniquement sur l'argument `depth` de `wp_nav_menu()` ; (b) appliquer la limite aussi dans `start_lvl()`/`start_el()` du Walker custom.
**Fix / Décision :** Les deux : `depth => 2` **et** un Walker (`DZ_Walker_Nav_Menu`, `inc/class-dz-walker-nav-menu.php`) qui refuse explicitement de générer le 3ᵉ niveau, quelle que soit la valeur de `depth` passée par erreur plus tard. Le Walker reproduit aussi le balisage exact du template d'origine (span + icône `bi-chevron-down` sur les éléments avec sous-menu, classe `dropdown` sur le `<li>`, classe `active` sur le lien courant) nécessaire au JS (`main.js`) et au CSS (`main.css`) existants.
**Leçon :** Quand une règle métier est actée (2 niveaux max), l'appliquer à la fois par configuration et par code rend l'erreur de configuration future inoffensive.
**Statut :** ✅ Résolu (testé avec un jeu de données synthétique à 3 niveaux : le 3ᵉ niveau n'apparaît jamais dans la sortie)

---

## [CHOIX] Une seule liste "Réseaux sociaux" partagée entre header et footer

**Contexte :** Dans le template d'origine, le header affiche 4 icônes sociales (Twitter, Facebook, Instagram, LinkedIn) et le footer en affiche 5 différentes (Facebook, Instagram, LinkedIn, Twitter, Dribbble), toutes en `href="#"` factices — aucune des deux listes n'a de sens métier propre.
**Symptôme / Problème :** Répliquer deux listes différentes forcerait à créer deux champs ACF répéteurs distincts (un pour le header, un pour le footer) pour un contenu qui, dans la réalité du diocèse, est le même (les comptes officiels du diocèse).
**Cause / Alternatives :** (a) deux champs répéteurs ACF séparés (fidélité totale au template de démo) ; (b) un seul champ répéteur `dz_social_links` sur la page d'options, réutilisé dans les deux emplacements via `template-parts/social-links.php`.
**Fix / Décision :** Option (b). Un seul repeater `dz_social_links` (choix de plateforme + URL), rendu par un template-part commun appelé avec une classe de conteneur différente (`header-social-links` vs `social-links mt-4`) pour conserver le style visuel de chaque emplacement.
**Leçon :** Ne pas dupliquer un champ ACF quand la donnée réelle (les comptes sociaux du diocèse) est unique, même si le template de démo affichait deux listes différentes sans rapport.
**Statut :** 🔵 Choix assumé

---

## [CHOIX] Colonnes de liens du footer limitées à 3 (repeater ACF `max: 3`)

**Contexte :** Le footer du template affiche 3 colonnes de liens (`Company`/`Services`/`Support`) dans une grille Bootstrap `col-6 col-md-4` × 3 à l'intérieur d'un `col-lg-6`.
**Symptôme / Problème :** Un repeater ACF `dz_footer_columns` sans limite permettrait à un rédacteur d'ajouter une 4ᵉ colonne ou plus, cassant la mise en page (la grille n'est prévue que pour 3).
**Cause / Alternatives :** Repeater sans `max` vs. avec `max: 3`, à l'image de la décision déjà prise pour le hero slider (`max: 5`).
**Fix / Décision :** `max: 3` sur `dz_footer_columns` (`inc/acf-fields.php`). Chaque colonne a elle-même un sous-repeater `dz_footer_column_links` sans limite (une liste de liens dans une colonne ne casse pas la grille, seule la largeur en colonnes est contrainte).
**Leçon :** Même logique que le hero slider : anticiper les contraintes de mise en page Bootstrap dès la définition des champs ACF plutôt que de les découvrir après un problème d'affichage.
**Statut :** 🔵 Choix assumé

---

## [CHOIX] Modèle de décision — copier ce format pour les prochaines entrées

**Contexte :** ...
**Symptôme / Problème :** ...
**Cause / Alternatives :** ...
**Fix / Décision :** ...
**Leçon :** ...
**Statut :** ✅ Résolu | 🔵 Choix assumé
