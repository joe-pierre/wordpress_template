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

## [CHOIX] Modèle de décision — copier ce format pour les prochaines entrées

**Contexte :** ...
**Symptôme / Problème :** ...
**Cause / Alternatives :** ...
**Fix / Décision :** ...
**Leçon :** ...
**Statut :** ✅ Résolu | 🔵 Choix assumé
