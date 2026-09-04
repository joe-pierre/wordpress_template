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

## [CHOIX] Relation `paroisse` ↔ `pretre` : un champ bidirectionnel ACF PRO plutôt que deux relations maintenues séparément

**Contexte :** SPEC.md §3 décrit une relation dans les deux sens : `paroisse` → "relation vers pretre (curé responsable, éventuellement vicaires en repeater)" et `pretre` → "relation vers paroisse". SPEC.md §4 précise par ailleurs qu'"un prêtre peut être rattaché à une seule paroisse principale (relation simple)".
**Symptôme / Problème :** Créer deux champs relationnels indépendants (un repeater de vicaires sur `paroisse`, un champ paroisse sur `pretre`) obligerait un rédacteur à mettre à jour deux fiches à chaque changement d'affectation, avec un risque réel de désynchronisation (ex. un prêtre listé comme vicaire d'une paroisse mais dont la fiche indique une autre paroisse d'affectation).
**Cause / Alternatives :** (a) deux relations indépendantes, fidèles à la formulation littérale de SPEC.md §3 ; (b) un seul champ éditable (`pretre_paroisse`, `post_object` simple sur la fiche prêtre) relié en **bidirectionnel** (fonctionnalité ACF PRO 6.1+) à un champ `relationship` en lecture quasi automatique sur la fiche paroisse (`paroisse_pretres`), synchronisé par ACF dans les deux sens.
**Fix / Décision :** Option (b) — voir `acf-json/group_dz_cpt_pretre.json` (`field_dz_pretre_paroisse`, `bidirectional_target: field_dz_paroisse_pretres`) et `acf-json/group_dz_cpt_paroisse.json` (`field_dz_paroisse_pretres`). La distinction curé/vicaire se fait via le champ `pretre_fonction` (filtré côté template lors de l'affichage, Phase 5), pas via deux repeaters séparés. Un prêtre "sans affectation" (retraité, en formation) laisse `pretre_paroisse` vide et utilise le champ `pretre_statut` dédié, conformément à SPEC.md §4.
**Leçon :** Quand une relation a un sens métier "un vers plusieurs" avec un seul côté réellement éditorial (ici : c'est la fiche du prêtre qui déclare son affectation, pas la paroisse qui "choisit" ses prêtres), préférer un champ bidirectionnel à deux champs indépendants — la donnée reste une source de vérité unique tout en restant consultable des deux côtés dans les templates.
**Statut :** 🔵 Choix assumé — nécessite ACF PRO 6.1+ (déjà supposé par SPEC.md §2 pour les champs répéteurs/relationnels)

---

## [CHOIX] Pas de champs ACF dupliquant les champs natifs (titre, contenu, image mise en avant)

**Contexte :** SPEC.md §3 liste, pour chaque CPT, des champs comme "nom", "description", "titre", "image" / "photo" à côté des champs réellement spécifiques (adresse, horaires, relations...).
**Symptôme / Problème :** Ces champs correspondent en réalité aux champs natifs WordPress déjà supportés par les CPT (`post_title`, `post_content` en WYSIWYG, image mise en avant) — créer un champ ACF `paroisse_nom` en plus du titre natif dupliquerait la donnée sans aucun bénéfice.
**Cause / Alternatives :** (a) créer un champ ACF pour chaque entrée de la table SPEC.md §3, y compris nom/description/photo ; (b) réutiliser les champs natifs (titre, éditeur, image mise en avant, déjà activés via `add_theme_support('post-thumbnails')` en Tâche 1) et ne créer des champs ACF que pour les données réellement structurées et propres au CPT.
**Fix / Décision :** Option (b), pour les 4 CPT. `supports => array( 'title', 'editor', 'thumbnail' )` dans chaque `inc/cpt-*.php` couvre nom/titre, description et photo/image ; les groupes ACF (`acf-json/group_dz_cpt_*.json`) ne contiennent que les champs structurés (adresse, horaires, dates, relations, fichiers, étapes...).
**Leçon :** Même logique que la décision "post natif pour les Actualités" : ne pas recréer en ACF ce que WordPress fournit déjà nativement.
**Statut :** 🔵 Choix assumé

---

## [CHOIX] Groupes de champs des CPT en JSON versionné, page d'options en PHP local

**Contexte :** La Tâche 2 a enregistré le groupe de champs de la page d'options ("Réglages du thème") via `acf_add_local_field_group()` dans `inc/acf-fields.php`. La Tâche 3 demande explicitement d'exporter les groupes de champs des CPT en JSON dans `acf-json/`.
**Symptôme / Problème :** Utiliser deux méthodes différentes dans le même thème (PHP local vs JSON) pourrait sembler incohérent à première vue.
**Cause / Alternatives :** (a) tout enregistrer en PHP local, comme la page d'options ; (b) tout exporter en JSON ; (c) garder PHP local pour la page d'options (un seul groupe, déjà en place, pas de bénéfice à l'exporter) et JSON pour les groupes de champs liés aux CPT métier (contenu versionné, plus naturel à faire évoluer via l'admin ACF puis committer le JSON généré).
**Fix / Décision :** Option (c), conforme à l'énoncé de chaque tâche et à SPEC.md §5 (`acf-fields.php # si définition en PHP plutôt que JSON export`, qui laisse le choix au cas par cas). Les fichiers `acf-json/group_dz_cpt_*.json` et `acf-json/group_dz_post_a_la_une.json` sont repris automatiquement par ACF (dossier `acf-json/` du thème actif), sans filtre `acf/settings/load_json` supplémentaire nécessaire.
**Leçon :** Documenter explicitement un choix qui pourrait sinon ressembler à une incohérence involontaire entre deux tâches.
**Statut :** 🔵 Choix assumé

---

## [CHOIX] Slides du hero ajoutées à la page d'options "Réglages du thème" plutôt qu'à un objet Page

**Contexte :** Le repeater ACF des slides du hero (décision "Hero slider... limité à 5 slides") doit être rattaché quelque part pour être édité par un rédacteur.
**Symptôme / Problème :** Une règle de localisation ACF `page_type == front_page` ne fonctionne que si les réglages de lecture WordPress pointent vers une page statique précise ; si le site utilise "Vos derniers articles" comme page d'accueil (ou change de configuration plus tard), le champ deviendrait invisible dans l'admin alors que `front-page.php` continue de s'afficher.
**Cause / Alternatives :** (a) champ rattaché à une page spécifique via une règle de localisation `page_type == front_page` ; (b) champ ajouté à la page d'options globale déjà créée en Tâche 2 ("Réglages du thème"), nouvel onglet "Page d'accueil".
**Fix / Décision :** Option (b) — `field_dz_hero_slides` dans `group_dz_theme_settings` (`inc/acf-fields.php`), lu via `dz_get_option( 'dz_hero_slides' )`. Reste disponible quel que soit le réglage de lecture WordPress, et cohérent avec le principe déjà établi (logo, réseaux sociaux, footer) : la configuration de la structure du site passe par cette page d'options unique.
**Leçon :** Ne pas coupler un champ ACF à un objet Page quand le contenu est en réalité une configuration de gabarit (`front-page.php`), pas un contenu éditorial de page.
**Statut :** 🔵 Choix assumé

---

## [CHOIX] Slides du hero simplifiées (pas de méta auteur/date/temps de lecture/vues)

**Contexte :** Le balisage `blog-hero-item` du template d'origine affiche, pour chaque slide, un auteur, une date, un temps de lecture et un nombre de vues — pertinent pour un article de blog, pas pour une bannière d'accueil institutionnelle.
**Symptôme / Problème :** Reproduire ces 4 champs sur chaque slide obligerait un rédacteur à saisir un "auteur" et des "vues" fictifs pour une bannière annonçant, par exemple, une visite pastorale — une charge de saisie sans aucune valeur pour le diocèse.
**Cause / Alternatives :** (a) répliquer fidèlement tous les champs meta du template de démo ; (b) ne garder que ce qui a un sens pour une bannière (image, badge, titre, lien optionnel).
**Fix / Décision :** Option (b). Le repeater `dz_hero_slides` ne contient que `dz_hero_slide_image`, `dz_hero_slide_badge`, `dz_hero_slide_title`, `dz_hero_slide_link_label` et `dz_hero_slide_link_url`. Le balisage visuel (`.blog-hero-item`, `.blog-hero-content`, `.category`, `.read-more`) est conservé à l'identique ; seul le contenu `.meta` (auteur/date/temps de lecture/vues) du template de démo est abandonné.
**Leçon :** Réutiliser le gabarit visuel du template ne veut pas dire répliquer tous ses champs de contenu factices — adapter le modèle de données au besoin métier réel (voir aussi la décision sur les champs natifs des CPT).
**Statut :** 🔵 Choix assumé

---

## [CHOIX] `front-page.php` limité au hero + "à la une" pour cette passe

**Contexte :** `index.html` contient 4 sections (`blog-hero`, `featured-posts`, `category-section`, `latest-posts`) ; la tâche demandait explicitement de convertir "hero slider + section Featured Posts".
**Symptôme / Problème :** `category-section` et `latest-posts` n'ont pas d'équivalent métier défini dans SPEC.md (pas de notion de "catégories mises en avant" ni de liste "derniers articles" décrite pour l'accueil), et les "accès rapides" (Paroisses/Prêtres/Sacrements/Dons) mentionnés dans SPEC.md §11 ne sont pas non plus présents dans le template d'origine.
**Cause / Alternatives :** (a) implémenter toutes les sections d'un coup, en improvisant un modèle de données pour celles qui n'ont pas de spec précise ; (b) livrer exactement le périmètre demandé (hero + à la une) et laisser `category-section`, `latest-posts` et les accès rapides pour une itération dédiée, une fois leur contenu métier précisé.
**Fix / Décision :** Option (b). `front-page.php` ne contient que les deux sections demandées. `TODO.md` note explicitement ce qui reste à faire sur l'accueil.
**Leçon :** Ne pas anticiper un contenu non spécifié : mieux vaut livrer un périmètre clair et le signaler que d'improviser une structure de données pour une section dont le besoin réel n'est pas encore connu.
**Statut :** 🔵 Choix assumé

---

## [CHOIX] Modèle de décision — copier ce format pour les prochaines entrées

**Contexte :** ...
**Symptôme / Problème :** ...
**Cause / Alternatives :** ...
**Fix / Décision :** ...
**Leçon :** ...
**Statut :** ✅ Résolu | 🔵 Choix assumé
