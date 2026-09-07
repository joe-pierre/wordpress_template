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
**Statut :** ⚠️ Remplacée — voir "`group_dz_front_hero` remplace `dz_hero_slides`..." plus bas : une consigne ultérieure a explicitement demandé de rattacher le hero à la page d'accueil malgré la limite décrite ici.

---

## [CHOIX] Slides du hero simplifiées (pas de méta auteur/date/temps de lecture/vues)

**Contexte :** Le balisage `blog-hero-item` du template d'origine affiche, pour chaque slide, un auteur, une date, un temps de lecture et un nombre de vues — pertinent pour un article de blog, pas pour une bannière d'accueil institutionnelle.
**Symptôme / Problème :** Reproduire ces 4 champs sur chaque slide obligerait un rédacteur à saisir un "auteur" et des "vues" fictifs pour une bannière annonçant, par exemple, une visite pastorale — une charge de saisie sans aucune valeur pour le diocèse.
**Cause / Alternatives :** (a) répliquer fidèlement tous les champs meta du template de démo ; (b) ne garder que ce qui a un sens pour une bannière (image, badge, titre, lien optionnel).
**Fix / Décision :** Option (b). Le repeater (renommé `dz_front_hero_slides` dans `group_dz_front_hero`, voir la décision "`group_dz_front_hero` remplace `dz_hero_slides`...") ne contient que image, titre, sous-titre/texte libre et lien optionnel (`dz_front_hero_slide_image`/`_titre`/`_texte`/`_lien`) — pas de badge de catégorie ni de méta factice. Le balisage visuel (`.blog-hero-item`, `.blog-hero-content`, `.read-more`) est conservé à l'identique ; seul le contenu `.meta` (auteur/date/temps de lecture/vues) du template de démo est abandonné.
**Leçon :** Réutiliser le gabarit visuel du template ne veut pas dire répliquer tous ses champs de contenu factices — adapter le modèle de données au besoin métier réel (voir aussi la décision sur les champs natifs des CPT).
**Statut :** 🔵 Choix assumé — noms de champs mis à jour lors du remplacement par `group_dz_front_hero`, principe inchangé

---

## [CHOIX] `front-page.php` limité au hero + "à la une" pour cette passe

**Contexte :** `index.html` contient 4 sections (`blog-hero`, `featured-posts`, `category-section`, `latest-posts`) ; la tâche demandait explicitement de convertir "hero slider + section Featured Posts".
**Symptôme / Problème :** `category-section` et `latest-posts` n'ont pas d'équivalent métier défini dans SPEC.md (pas de notion de "catégories mises en avant" ni de liste "derniers articles" décrite pour l'accueil), et les "accès rapides" (Paroisses/Prêtres/Sacrements/Dons) mentionnés dans SPEC.md §11 ne sont pas non plus présents dans le template d'origine.
**Cause / Alternatives :** (a) implémenter toutes les sections d'un coup, en improvisant un modèle de données pour celles qui n'ont pas de spec précise ; (b) livrer exactement le périmètre demandé (hero + à la une) et laisser `category-section`, `latest-posts` et les accès rapides pour une itération dédiée, une fois leur contenu métier précisé.
**Fix / Décision :** Option (b). `front-page.php` ne contient que les deux sections demandées. `TODO.md` note explicitement ce qui reste à faire sur l'accueil.
**Leçon :** Ne pas anticiper un contenu non spécifié : mieux vaut livrer un périmètre clair et le signaler que d'improviser une structure de données pour une section dont le besoin réel n'est pas encore connu.
**Statut :** 🔵 Choix assumé

---

## [CHOIX] Contenu factice du template abandonné au profit de données WordPress réelles (single/author)

**Contexte :** `blog-details.html` et `author-profile.html` embarquent des blocs riches mais entièrement fictifs : compteurs "Awards"/"Followers" (PureCounter), badge "Featured Author"/"Top Contributor", "Areas of Expertise" (tags), liens GitHub/Twitter/dev.to inventés, bouton "Subscribe to Newsletter" non fonctionnel, réactions "Helpful/Insightful/Save" avec des compteurs figés, et une barre de progression de lecture + sommaire (`sidebar-navigation`) calée sur des ancres de section fixes (`#overview`, `#diagnostics`...) propres à cet unique article de démonstration.
**Symptôme / Problème :** Aucun de ces éléments n'a d'équivalent dans le modèle de données WordPress natif ni dans SPEC.md ; les reproduire obligerait soit à inventer de nouveaux champs (utilisateur ACF, meta de réactions) hors périmètre, soit à laisser des données figées (donc mensongères) en production — ce que CLAUDE.md interdit explicitement ("aucune donnée variable codée en dur").
**Cause / Alternatives :** (a) répliquer fidèlement tout le balisage, y compris les données fictives ; (b) ne garder que les blocs pour lesquels WordPress a une donnée réelle et fonctionnelle, et supprimer le reste.
**Fix / Décision :** Option (b), appliquée à `single.php` et `author.php` :
- gardé et rendu réel : avatar (`get_avatar()`), nom, biographie (`get_the_author_meta('description')`), nombre d'articles (`count_user_posts()`), site web (`user_url`), liste des autres articles de l'auteur (`get_posts()` filtré par `author`), nombre de commentaires (`get_comments_number()`), partage social (liens de partage réels vers Twitter/Facebook/LinkedIn/e-mail, générés à partir de `get_permalink()`) ;
- supprimé : compteurs Awards/Followers, expertise-tags, badges "vérifié", liens sociaux inventés, bouton newsletter (déjà en ROADMAP, non construit), boutons de réaction, sommaire à ancres fixes et barre de progression de lecture (`sidebar-navigation`, sans JS de support dans `main.js` — décoratif même dans le template source).
**Leçon :** Un template de démonstration mélange souvent contenu réutilisable et enrobage marketing fictif ; ne convertir que ce qui a une source de données réelle, plutôt que de figer des faux chiffres en dur.
**Statut :** 🔵 Choix assumé

---

## [CHOIX] Une seule carte article (`card-article.php`) pour `archive.php` et `search.php`, malgré deux maquettes différentes dans le template source

**Contexte :** `category.html` (2 colonnes, avec sidebar) et `search-results.html` (3 colonnes, sans sidebar) utilisent chacun une maquette de carte différente : la première n'affiche que catégorie + date + titre, la seconde ajoute une photo d'auteur et son nom.
**Symptôme / Problème :** La tâche demande explicitement un template-part `card-article.php` unique pour éviter la duplication entre les deux gabarits ; il faut donc choisir une seule maquette.
**Cause / Alternatives :** (a) garder les deux maquettes, dupliquées dans chaque gabarit ; (b) adopter la maquette la plus complète (celle de `search-results.html`, avec auteur) comme carte unique, réutilisée dans les deux contextes avec une largeur de colonne différente (`col-lg-6` dans `archive.php`, `col-lg-4` dans `search.php`).
**Fix / Décision :** Option (b) — voir `template-parts/card-article.php`. La largeur de colonne (Bootstrap grid) reste définie dans le gabarit appelant, pas dans le template-part, pour rester réutilisable dans les deux mises en page.
**Leçon :** Quand une tâche impose explicitement la déduplication, choisir la maquette source la plus riche en information plutôt que la plus simple, pour ne perdre aucune donnée utile en unifiant.
**Statut :** 🔵 Choix assumé

---

## [CHOIX] `comments.php` et `searchform.php` ajoutés bien que non listés dans SPEC.md §5

**Contexte :** `blog-details.html` contient une section commentaires (avec réponses imbriquées) et un formulaire ; `category.html` contient un widget de recherche. WordPress fournit des mécanismes natifs pour les deux (`comments_template()` + `wp_list_comments()`/`comment_form()`, et `get_search_form()`), mais leur rendu par défaut ne correspond pas au balisage/CSS du template (ex. `get_search_form()` natif produit `input[type=search]` + `input[type=submit]`, alors que le CSS cible `.search-widget form input[type=text]` et `form button`).
**Symptôme / Problème :** Sans ces deux fichiers, soit les commentaires/la recherche ne s'affichent pas du tout, soit ils s'affichent avec le rendu WordPress par défaut, visuellement incohérent avec le reste du thème.
**Cause / Alternatives :** (a) ne pas fournir ces fichiers, laisser les valeurs par défaut de WordPress ; (b) ajouter `comments.php` et `searchform.php` — fichiers standards reconnus automatiquement par `comments_template()` et `get_search_form()`, non explicités dans SPEC.md §5 mais nécessaires pour respecter la consigne "utiliser les boucles natives" avec le rendu visuel du template.
**Fix / Décision :** Option (b). Les boutons "J'aime"/"Partager" par commentaire du template source (sans logique JS ni donnée associée, y compris dans le fichier HTML d'origine) ne sont pas reproduits ; seul `comment_reply_link()` (réponse imbriquée native) est conservé.
**Leçon :** Une consigne "template-part pour éviter la duplication" ou "boucles natives" implique parfois de créer les fichiers de convention WordPress correspondants (`comments.php`, `searchform.php`) même quand SPEC.md ne les énumère pas explicitement — SPEC.md §5 n'est pas exhaustif sur les fichiers de convention WordPress.
**Statut :** 🔵 Choix assumé

---

## [CHOIX] `template-parts/page-title.php` ajouté (bannière titre + fil d'Ariane commun)

**Contexte :** Le bloc `.page-title` (titre + sous-titre + fil d'Ariane `Accueil > Page actuelle`) est identique dans `about.html`, `contact.html`, `starter-page.html`, `blog-details.html`, `category.html`, `author-profile.html` et `search-results.html` — seuls le titre et le texte varient.
**Symptôme / Problème :** Cette tâche implémente à elle seule 4 gabarits ayant besoin de ce bloc ; le dupliquer 4 fois enfreint directement CONVENTIONS.md ("Toute section HTML répétée devient un template-part").
**Cause / Alternatives :** (a) dupliquer le balisage dans chaque gabarit ; (b) créer `template-parts/page-title.php`, appelé avec `$args` (`title`, `subtitle`, `breadcrumb`).
**Fix / Décision :** Option (b), bien que non explicitement demandé par l'énoncé de la tâche (qui ne mentionne que `card-article.php`) — appliqué la même logique de déduplication à un bloc au moins aussi répété. Sera réutilisé par les futurs gabarits de pages (Phase 4 restante) et métier (Phase 5).
**Leçon :** Suivre l'esprit de CONVENTIONS.md au-delà de la liste explicite de template-parts citée dans une tâche, quand la duplication concernée est aussi évidente.
**Statut :** 🔵 Choix assumé

---

## [CHOIX] `.paroisse-card` créée sur mesure, `.team`/`.contact` réutilisées telles quelles

**Contexte :** Le template source ne contient aucun gabarit visuel équivalent à une fiche/carte de paroisse. En revanche, `about.html` a une section "Team" (`.team .team-member`) qui correspond visuellement à une fiche prêtre, et `contact.html` a des tuiles d'information + une carte Google Maps embarquée (`.contact .info-card`, `.contact .map-container`) qui correspondent exactement à un bloc "coordonnées de paroisse".
**Symptôme / Problème :** CONVENTIONS.md demande à la fois de conserver la nomenclature du template pour toute section réutilisée telle quelle, et de suivre le même style (`kebab-case`, ex. `.paroisse-card`) pour toute nouvelle section métier qui n'a pas d'équivalent.
**Cause / Alternatives :** (a) forcer la réutilisation d'une classe existante sans rapport visuel (ex. `.blog-card`, scoping `#featured-posts`) pour la carte d'annuaire des paroisses ; (b) créer une nouvelle classe `.paroisse-card` minimale, cohérente avec les variables CSS déjà définies (`--heading-color`, `--accent-color`, `--surface-color`), uniquement là où aucun composant existant ne correspond.
**Fix / Décision :** Option (b) pour `card-paroisse.php` (nouvelle classe `.paroisse-card`, ajoutée en fin de `assets/css/main.css` du thème). Pour tout le reste des gabarits Paroisses/Prêtres, réutilisation à l'identique des classes existantes : `.team`/`.team-member`/`.member-image`/`.member-info`/`.social-overlay` (fiche et annuaire des prêtres), `.contact`/`.contact-info-panel`/`.info-card`/`.map-container` (coordonnées + carte de la paroisse), `.author-profile`/`.author-card`/`.designation`/`.author-content` (fiche prêtre individuelle, réutilisation de la page "Author Profile" du template — une fiche de prêtre a la même forme qu'un profil d'auteur : photo, nom, rôle, biographie, contact).
**Leçon :** Chercher d'abord un composant visuel existant qui correspond structurellement (même sans rapport sémantique avec son nom d'origine) avant de créer une nouvelle classe ; ne créer du CSS neuf que pour ce qui n'a vraiment aucun équivalent.
**Statut :** 🔵 Choix assumé

---

## [CHOIX] Carte de localisation en simple `iframe` `google.com/maps?q=lat,lng&output=embed`, sans clé API

**Contexte :** Le champ ACF `paroisse_localisation` (type `google_map`) fournit une latitude/longitude par paroisse ; `contact.html` intègre une carte via une URL d'embed Google Maps `.../maps/embed?pb=...` généré depuis l'interface "Partager > Intégrer une carte" de Google, propre à un lieu précis et non reproductible dynamiquement à partir d'une simple latitude/longitude.
**Symptôme / Problème :** Le format d'embed `pb=` du template source est un blob encodé propre à un lieu, non générable dynamiquement pour chaque paroisse depuis ses seules coordonnées GPS. Un embed JavaScript "propre" (Google Maps JavaScript API) nécessiterait une clé API à configurer et facturer, hors périmètre technique de cette tâche.
**Cause / Alternatives :** (a) Google Maps JavaScript API avec clé API (facturation, configuration wp-config additionnelle) ; (b) format d'URL d'embed simplifié `https://www.google.com/maps?q={lat},{lng}&output=embed`, sans clé API, fonctionnant directement dans une balise `<iframe>`.
**Fix / Décision :** Option (b) — voir `single-paroisse.php`. Le bloc carte ne s'affiche que si latitude et longitude sont toutes les deux renseignées (vérification avant affichage, cf. CONVENTIONS.md §Validation).
**Leçon :** Un embed Google Maps basique par coordonnées ne nécessite pas de clé API ; la réserver pour plus tard uniquement si un besoin de carte interactive plus riche (marqueurs multiples, style personnalisé) apparaît.
**Statut :** 🔵 Choix assumé

---

## [CHOIX] `archive-pretre.php` créé (annuaire des prêtres), absent de SPEC.md §5 mais requis par SPEC.md §11 et TODO.md

**Contexte :** SPEC.md §5 (architecture) ne liste pas `archive-pretre.php` dans l'arborescence, alors que SPEC.md §11 (écrans) prévoit explicitement "Prêtres (liste + détail) — annuaire du clergé" et TODO.md Phase 5 mentionne `archive-pretre.php`.
**Symptôme / Problème :** Suivre littéralement l'arborescence de SPEC.md §5 laisserait les prêtres sans page de liste, alors que l'annuaire du clergé est un écran explicitement prévu ailleurs dans le même document.
**Cause / Alternatives :** (a) ignorer l'écart et ne pas créer `archive-pretre.php` ; (b) le créer, en le signalant comme un oubli probable de la section architecture plutôt qu'un choix de scope délibéré.
**Fix / Décision :** Option (b) — déjà anticipé dans DECISIONS.md (Tâche 3) et TODO.md. `has_archive => true` était déjà actif sur le CPT `pretre` depuis la Tâche 3 ; seul le fichier de template manquait.
**Leçon :** SPEC.md §5 n'est pas exhaustif ; croiser avec §11 (Écrans) et TODO.md avant de conclure qu'un gabarit n'est pas nécessaire.
**Statut :** ✅ Résolu

---

## [RÉSOLU] Filtrage des événements passés implémenté via `meta_query` sur l'archive, sans suppression

**Contexte :** SPEC.md §4 : "Les événements passés ne doivent plus apparaître dans les listings 'à venir' (filtrage par date dans la requête, pas de suppression)." Tâche 7 demande explicitement ce filtrage sur `archive-evenement.php`.
**Symptôme / Problème :** Un événement peut avoir une date de fin optionnelle (`evenement_date_fin`) ; se baser uniquement sur la date de début pour décider "passé/à venir" ferait disparaître de l'agenda un événement multi-jours dès son jour de début, alors qu'il est encore en cours.
**Cause / Alternatives :** (a) filtrer uniquement sur `evenement_date_debut >= maintenant` ; (b) filtrer sur `evenement_date_fin >= maintenant` OU (`evenement_date_fin` absent ET `evenement_date_debut >= maintenant`), ce qui couvre à la fois les événements à venir et ceux actuellement en cours.
**Fix / Décision :** Option (b), implémentée via un hook `pre_get_posts` (`dz_evenement_archive_query()` dans `inc/cpt-evenement.php`) qui ne s'applique qu'à la requête principale de l'archive `evenement` en front-end (jamais dans `wp-admin`, où les rédacteurs doivent pouvoir gérer les événements passés). Tri du plus proche au plus lointain (`orderby = meta_value` sur `evenement_date_debut`, `order = ASC`). Aucune suppression : les événements passés restent en base et restent accessibles via leur URL directe (`single-evenement.php` affiche alors un badge "Terminé").
**Leçon :** Une règle "n'afficher que les événements à venir" doit tenir compte des événements en cours (date de fin optionnelle), pas seulement comparer la date de début à la date du jour.
**Statut :** ✅ Résolu

---

## [CHOIX] `.evenement-card` créée sur mesure ; `.about .feature-item` et `.contact .info-card` réutilisées pour les sacrements

**Contexte :** Comme pour les paroisses/prêtres (Tâche 6), ni les événements ni les sacrements n'ont d'équivalent visuel direct dans le template source.
**Symptôme / Problème :** Même arbitrage qu'en Tâche 6 entre réutilisation forcée d'un composant sans rapport et création d'une classe minimale cohérente.
**Cause / Alternatives :** voir la décision équivalente de la Tâche 6 ("`.paroisse-card` créée sur mesure...").
**Fix / Décision :**
- `card-evenement.php` : nouvelle classe `.evenement-card` (image + badge date + titre + lieu), ajoutée à la suite de `.paroisse-card` dans `assets/css/main.css` du thème.
- `single-sacrement.php` : les étapes (`sacrement_etapes`) réutilisent `.about .feature-item` (about.html), avec un numéro d'étape à la place de l'icône (nouvelle règle `.feature-icon .step-number`, 6 lignes de CSS) ; la paroisse référente réutilise `.contact .info-card` (3ᵉ réutilisation de ce composant après la Tâche 6, confirmant sa pertinence comme "tuile d'information" générique du thème) ; les documents à télécharger utilisent le composant natif Bootstrap `.list-group` (aucun équivalent dans le template source, et Bootstrap est déjà chargé — pas besoin de CSS supplémentaire) ; le badge de statut d'un événement (à venir/en cours/terminé) utilise les classes Bootstrap `.badge`/`.text-bg-*`, également sans CSS supplémentaire.
**Leçon :** Avant de créer une nouvelle classe CSS, vérifier aussi si un composant Bootstrap déjà chargé (badge, list-group, card) couvre le besoin — pas seulement les classes propres au template "Story".
**Statut :** 🔵 Choix assumé

---

## [CHOIX] Contenu de `page-about.php` : `the_content()` pour le texte, ACF seulement pour les 2 badges chiffrés

**Contexte :** `about.html` a une section "About" avec un titre, un paragraphe, 2 blocs `.feature-item` (icône + titre + texte), une `.check-list` et un bouton CTA, en plus des deux badges compteurs (`experience-badge`/`projects-badge`) explicitement visés par la Tâche 8.
**Symptôme / Problème :** Reproduire fidèlement `.feature-item`/`.check-list` obligerait soit à saisir du HTML brut dans l'éditeur (contraire à "utilisable par des rédacteurs non techniques sans toucher au code"), soit à créer des champs ACF pour un contenu dont la structure exacte (nombre de feature-items, longueur du check-list) n'est pas connue avant la collecte de contenu réel (Phase 0, toujours en attente).
**Cause / Alternatives :** (a) champs ACF/repeater pour chaque bloc du template (feature-items, check-list, CTA) ; (b) laisser tout le texte de la colonne de droite (titre, paragraphes, listes, liens) dans l'éditeur natif de la page (`the_content()`), et ne créer des champs ACF que pour ce qui ne peut pas exister dans un éditeur classique : les deux badges chiffrés positionnés en absolu sur l'image.
**Fix / Décision :** Option (b), conforme à la formulation de la tâche ("en rendant **les badges compteurs** PureCounter administrables via ACF" — pas le reste du contenu). Les deux badges (`dz_about_badge_bottom`/`dz_about_badge_top`, type `group` avec nombre/suffixe/légende) sont des champs distincts et non un repeater, car leurs positions CSS (`.experience-badge` en bas à gauche, `.projects-badge` en haut à droite, styles différents) sont fixes dans le template, pas une liste extensible. La section "Team" d'`about.html` (collègues fictifs) n'est pas reprise : elle fait doublon avec `archive-pretre.php` (Tâche 6) et n'apparaît pas dans la description de l'écran "À propos" de SPEC.md §11.
**Leçon :** Ne créer des champs ACF que pour ce que l'éditeur classique ne peut pas produire (positionnement CSS spécifique, valeurs numériques pilotant une animation JS) ; laisser le texte libre au contenu natif de la page.
**Statut :** 🔵 Choix assumé

---

## [CHOIX] Formulaire Contact Form 7 monté via `the_content()`, sans ID de formulaire codé en dur

**Contexte :** Contact Form 7 n'est pas encore installé (Phase 6, TODO.md) ; la Tâche 8 demande néanmoins d'intégrer "un formulaire Contact Form 7... au style form-floating du template" dans `page-contact.php`.
**Symptôme / Problème :** Coder en dur un shortcode `[contact-form-7 id="X"]` dans le template PHP est fragile : l'ID du formulaire est généré par CF7 à la création et diffère d'une installation à l'autre ; ce n'est de toute façon pas comme cela que CF7 est conçu pour être utilisé (le shortcode s'insère normalement dans le contenu d'une page, pas dans le code du thème).
**Cause / Alternatives :** (a) `echo do_shortcode( '[contact-form-7 id="..."]' )` avec un ID codé en dur ou stocké dans une option ; (b) laisser `the_content()` de la page Contact être le point de montage : une fois CF7 installé (Phase 6), le shortcode du formulaire est simplement collé dans le contenu de cette page depuis l'admin, sans toucher au code.
**Fix / Décision :** Option (b). `page-contact.php` appelle `the_content()` à l'intérieur du bloc `.form-container` réutilisé de `contact.html`. Un style de base cible directement les classes propres à CF7 (`.wpcf7-form-control`, `.wpcf7-not-valid-tip`, `.wpcf7-response-output`) pour un rendu correct même sans configuration supplémentaire ; les règles `.form-floating .form-control` déjà présentes s'appliqueront automatiquement si le formulaire CF7 est construit en enveloppant chaque champ dans une div `.form-floating` (recommandé, à faire en Phase 6 — note laissée dans le commentaire du template et dans TODO.md).
**Leçon :** Ne jamais coder en dur l'identifiant d'un contenu qui n'existe pas encore au moment du développement du thème (ID de formulaire CF7, ID de page...) ; prévoir le point d'insertion natif WordPress (`the_content()`) à la place.
**Statut :** 🔵 Choix assumé

---

## [CHOIX] Carte du contact géocodée depuis l'adresse texte, réutilisation de `.info-card`/`.social-links-panel`

**Contexte :** Les coordonnées de contact (téléphone, e-mail, adresse) et les réseaux sociaux sont déjà administrables depuis la Tâche 2 (page d'options "Réglages du thème"), mais n'étaient encore affichés nulle part sur le site.
**Symptôme / Problème :** L'adresse est stockée en simple texte (`textarea`), pas en `google_map` (type utilisé uniquement pour les paroisses, Tâche 3) ; il n'existe donc pas de latitude/longitude pour la carte de la page Contact.
**Cause / Alternatives :** (a) ajouter un champ `google_map` dédié sur la page d'options, en plus du champ adresse texte existant (risque de duplication : deux champs à maintenir pour la même information) ; (b) géocoder l'adresse texte existante directement dans l'URL d'embed (`https://www.google.com/maps?q={adresse}&output=embed`, cf. décision équivalente pour les paroisses, Tâche 6).
**Fix / Décision :** Option (b) — aucune donnée dupliquée, l'adresse reste éditable à un seul endroit. `page-contact.php` réutilise aussi `template-parts/social-links.php` (Tâche 2) pour le panneau "Suivez-nous", et le composant `.info-card` (Tâche 6) pour les tuiles adresse/e-mail/téléphone.
**Leçon :** Avant d'ajouter un nouveau champ ACF, vérifier si un champ existant peut être réutilisé pour un nouveau besoin d'affichage (ici : texte libre suffisant pour un géocodage Google Maps basique, pas besoin de coordonnées précises).
**Statut :** 🔵 Choix assumé

---

## [RÉSOLU] `page-about.php`/`page-contact.php`/`page-dons.php` sans en-tête `Template Name` — champs ACF et lien du footer cassés

**Contexte :** Revue de sécurité et QA (Tâche 9). Les groupes de champs ACF de `page-about.php` et `page-dons.php` (Tâche 8) utilisent une règle de localisation `page_template == page-about.php` / `page-dons.php` ; le CTA du footer utilise `dz_get_page_url_by_template( 'page-contact.php' )` (Tâche 2), qui cherche une page dont la meta `_wp_page_template` vaut `page-contact.php`.
**Symptôme / Problème :** Aucun des trois fichiers n'avait de commentaire d'en-tête `Template Name:`. Sans cet en-tête, WordPress ne propose PAS le gabarit dans le sélecteur "Attributs de la page" de l'admin — un rédacteur n'a donc aucun moyen de l'assigner à une page, et la meta `_wp_page_template` correspondante n'est jamais écrite en base. Conséquence en cascade : les champs ACF des Tâches 8 (badges "À propos", modalités "Dons") ne seraient jamais visibles dans l'admin, quelle que soit la page créée, et le lien "Nous contacter" du footer retomberait silencieusement sur la page d'accueil (`dz_get_page_url_by_template()` ne trouvant aucune page correspondante).
**Cause / Alternatives :** Aucune — c'est un oubli pur et simple lors de la création de ces trois fichiers (Tâche 8), non détecté à l'époque faute d'installation WordPress réelle pour le vérifier. Trouvé en croisant le mécanisme de localisation ACF (`page_template`) avec la façon dont WordPress associe réellement un gabarit à une page.
**Fix / Décision :** Ajout d'un en-tête `Template Name:` dans le docblock de tête de chacun des trois fichiers (`À propos`, `Contact`, `Dons`). Aucun changement de logique métier.
**Leçon :** Une règle de localisation ACF `page_template` ou une recherche par `_wp_page_template` ne fonctionnent que si le gabarit est explicitement sélectionnable dans l'admin (en-tête `Template Name`) — le simple nommage `page-{slug}.php` suffit à WordPress pour l'affichage (hiérarchie de gabarits par slug), mais pas pour peupler cette metadonnée.
**Statut :** ✅ Résolu

---

## [RÉSOLU] Liens `tel:`/`mailto:` échappés avec `esc_attr()` au lieu de `esc_url()`

**Contexte :** Revue de sécurité et QA (Tâche 9), vérification systématique de l'échappement des sorties (CONVENTIONS.md §Validation).
**Symptôme / Problème :** Sur `page-contact.php`, `single-pretre.php`, `single-paroisse.php` et `template-parts/card-pretre.php` (8 occurrences), les liens `href="tel:..."` / `href="mailto:..."` n'échappaient que la partie numéro/adresse avec `esc_attr()`, le préfixe `tel:`/`mailto:` restant un littéral PHP hors de toute fonction d'échappement. Sans faille exploitable ici (le préfixe est un littéral non contrôlable par l'utilisateur, `esc_attr()` bloque déjà l'évasion d'attribut), mais non conforme à la règle explicite de CONVENTIONS.md ("esc_url() ... selon le contexte") et moins robuste en cas de refactor futur.
**Cause / Alternatives :** (a) laisser tel quel (pas de faille avérée) ; (b) échapper l'URL complète (`'tel:' . $numero`) avec `esc_url()`, conformément à la convention "toute sortie de type URL passe par esc_url()".
**Fix / Décision :** Option (b), appliquée aux 8 occurrences. `single.php` : les 4 liens de partage social (Twitter/Facebook/LinkedIn/e-mail) ont aussi été refactorés pour construire l'URL complète dans une variable PHP puis l'échapper une seule fois avec `esc_url()`, au lieu d'un `rawurlencode()` dispersé sur plusieurs balises `<?php ?>` sans échappement final de l'attribut.
**Leçon :** Toujours échapper l'attribut `href` dans son ensemble avec `esc_url()`, même quand une partie de sa valeur est un littéral non contrôlable par l'utilisateur — plus robuste et surtout plus lisible qu'un mélange de fonctions d'échappement à l'intérieur d'un même attribut.
**Statut :** ✅ Résolu

---

## [RÉSOLU] `template-parts/hero-slider.php` créé en Tâche 1 jamais appelé (mort depuis la Tâche 4)

**Contexte :** SPEC.md §5 liste `template-parts/hero-slider.php` dans l'architecture cible (créé en stub, Tâche 1). La Tâche 4 a implémenté le hero slider directement dans `front-page.php`, sans jamais appeler ce template-part ni le supprimer.
**Symptôme / Problème :** Fichier mort dans le dépôt, en contradiction avec CONVENTIONS.md ("Toute section HTML répétée devient un template-part dédié... appelé via `get_template_part()`") et avec l'architecture déclarée dans SPEC.md — repéré lors du balayage des stubs restants (Tâche 9).
**Cause / Alternatives :** (a) supprimer le stub inutilisé, en admettant que le hero reste inline dans `front-page.php` ; (b) extraire réellement le balisage du hero (déjà écrit dans `front-page.php`) vers ce template-part, appelé avec `$args['slides']`, conformément à ce que le fichier était censé être depuis le départ.
**Fix / Décision :** Option (b). `front-page.php` calcule toujours `dz_get_option( 'dz_hero_slides', array() )` (récupération de donnée = responsabilité du gabarit appelant, cf. CONVENTIONS.md) et le passe en argument à `get_template_part( 'template-parts/hero-slider', null, array( 'slides' => ... ) )` ; le template-part se contente d'afficher. Le bloc JSON `swiper-config` reste identique au template source (vérifié par comparaison octet à octet après refactor).
**Leçon :** Vérifier, à la fin d'un enchaînement de tâches, qu'aucun fichier prévu dans l'architecture ne reste un stub mort parce qu'une tâche ultérieure a réimplémenté son contenu ailleurs par inadvertance.
**Statut :** ✅ Résolu

---

## [RÉSOLU] Collision `menu_position` entre le CPT `paroisse` (20) et le menu natif "Pages"

**Contexte :** Revue de sécurité et QA (Tâche 9). Les 4 CPT métier (Tâche 3) utilisaient `menu_position` 20, 21, 22, 23.
**Symptôme / Problème :** 20 est la position réservée par WordPress Core au menu natif "Pages" (5=Articles, 10=Média, 20=Pages, 25=Commentaires, 60=Apparence...). Enregistrer le CPT `paroisse` sur ce même créneau peut produire un ordre d'affichage imprévisible entre "Pages" et "Paroisses" dans le menu d'administration.
**Cause / Alternatives :** Décalage des 4 CPT vers des créneaux libres.
**Fix / Décision :** `paroisse` → 21, `pretre` → 22, `evenement` → 23, `sacrement` → 24 (créneaux libres entre "Pages" (20) et "Commentaires" (25), voir `inc/cpt-*.php`).
**Leçon :** Vérifier la liste des `menu_position` réservées par WordPress Core avant d'enregistrer un CPT (5, 10, 20, 25, 60, 65, 70, 75, 80, 99).
**Statut :** ✅ Résolu

---

## [CHOIX] `group_dz_front_hero` remplace `dz_hero_slides` : le hero repasse sur la page d'accueil, à la demande explicite du client

**Contexte :** La Tâche 4 avait délibérément mis le repeater des slides du hero sur la page d'options "Réglages du thème" (`dz_hero_slides`, dans `group_dz_theme_settings`) plutôt que sur l'objet Page servant de page d'accueil, précisément pour ne pas dépendre du réglage "Vos derniers articles" vs. "Une page statique" dans Réglages > Lecture (voir la décision "Slides du hero ajoutées à la page d'options..."). Une nouvelle consigne demande explicitement de créer `group_dz_front_hero`, "associé à la page d'accueil".
**Symptôme / Problème :** Cette consigne contredit directement le choix de la Tâche 4. Conformément à CLAUDE.md ("vérifier qu'une nouvelle tâche ne contredit pas une entrée de DECISIONS.md ... et l'inscrire comme nouvelle décision si le contexte a changé"), le changement est appliqué mais documenté ici plutôt que fait silencieusement.
**Cause / Alternatives :** (a) garder `dz_hero_slides` sur la page d'options ; (b) créer `group_dz_front_hero`, localisé via la règle ACF native `page_type == front_page` (cible la page définie comme page d'accueil statique dans Réglages > Lecture, quel que soit son ID).
**Fix / Décision :** Option (b), à la demande explicite et documentée du client. `dz_hero_slides` et l'onglet "Page d'accueil" sont retirés de `group_dz_theme_settings` (Tâche 4) ; `group_dz_front_hero` (repeater `dz_front_hero_slides`, max 5 : image, titre, sous-titre/texte, lien optionnel) est ajouté avec la règle `page_type == front_page`. La limite de la Tâche 4 reste vraie et est traitée comme un état vide géré, pas comme une erreur : `dz_get_front_hero_slides()` (`inc/acf-fields.php`) renvoie un tableau vide si Réglages > Lecture n'est pas configuré sur "Une page statique" (ou si aucune page n'y est assignée), et `template-parts/hero-slider.php` n'affiche alors simplement pas la section — cohérent avec la consigne "état vide propre, pas de section cassée".
**Leçon :** Quand une consigne ultérieure contredit explicitement une décision déjà actée, l'appliquer si elle est explicite et volontaire, mais toujours documenter la substitution (raison de l'ancien choix, raison du nouveau) plutôt que de laisser DECISIONS.md désynchronisé du code.
**Statut :** 🔵 Choix assumé — remplace la décision "Slides du hero ajoutées à la page d'options..." (Tâche 4)

---

## [CHOIX] "Featured Posts" de l'accueil garde son propre balisage (`.blog-card`), sans réutiliser `card-article.php`

**Contexte :** Consigne : réutiliser `template-parts/card-article.php` pour la boucle "à la une" de l'accueil "si disponible".
**Symptôme / Problème :** `card-article.php` (Tâche 5) utilise le balisage `.post-img`/`.post-category`/`.title`/`.post-meta`, conçu pour la grille statique d'`archive.php`/`search.php`. La section "Featured Posts" de `index.html` utilise un balisage entièrement différent (`.blog-card`/`.blog-image`/`.category-badge`/`.blog-content`/`.blog-footer`/`.reading-time`) à l'intérieur d'un slider Swiper, avec son propre CSS scopé sous `#featured-posts .blog-card` — les classes de `card-article.php` n'ont aucune règle CSS dans ce contexte et s'afficheraient sans style.
**Cause / Alternatives :** (a) forcer `card-article.php` dans le slider "à la une" au prix d'un rendu cassé (aucun CSS ne correspond) ; (b) garder le balisage `.blog-card` déjà en place dans `front-page.php` (Tâche 4), qui reproduit fidèlement `index.html` et utilise déjà des données réelles (`WP_Query` sur `post_a_la_une`, auteur/date/extrait/temps de lecture réels).
**Fix / Décision :** Option (b) — la clause "si disponible" de la consigne est lue littéralement : le template-part n'est pas réellement réutilisable ici sans casser l'affichage, donc il n'est pas forcé. Pas de changement de code sur cette section (déjà conforme depuis la Tâche 4), seule la clarification est actée ici.
**Leçon :** "Réutiliser un template-part si disponible" ne veut pas dire l'imposer partout où un contenu de même nature (ici : un article) apparaît — seulement là où le balisage/CSS visés correspondent réellement à la section reconvertie.
**Statut :** 🔵 Choix assumé

---

## [CHOIX] Mega-menu hybride — remplace la décision "menu à 2 niveaux" et son implémentation (Walker + double garde-fou)

**Contexte :** Réception après coup de `Arborescence_PDF.pdf`, la vraie carte du site voulue par le diocèse (non disponible au moment des décisions "Simplifier le menu de navigation à 2 niveaux" et "Double garde-fou pour la limite de profondeur du menu", ci-dessus). Cette arborescence compte 9 rubriques principales, certaines avec jusqu'à 9 enfants et un 3ᵉ niveau réel (ex. "À propos du diocèse" > "Archives" > "Histoire du diocèse" / "Les différents évêques").
**Symptôme / Problème :** Les deux décisions ci-dessus ont été prises et implémentées (avec tests) *avant* que la vraie arborescence du client soit connue, sur la base du menu générique du template ("Deep Dropdown" à 3 niveaux sans contenu réel). Elles ne couvrent pas ce cas réel, et un simple retour à "3 niveaux en cascade" poserait un problème d'usage (survol perdu au trackpad, empilement d'accordéons imbriqués sur mobile pour les rubriques les plus riches).
**Cause / Alternatives :** (a) étendre `DZ_Walker_Nav_Menu` (`inc/class-dz-walker-nav-menu.php`) à 3 niveaux de dropdowns en cascade ; (b) mega-menu partout ; (c) mega-menu (panneau large en colonnes, affichant enfants et petits-enfants à plat) uniquement pour les rubriques riches, dropdown simple à 1 niveau ailleurs.
**Fix / Décision :** Option (c), validée par le client. **Mega-menu** pour : À propos du diocèse, Services et Commissions, Apostolat des Laïques, Vie de Foi. **Dropdown simple à 1 niveau** (réutilise le comportement déjà implémenté et testé du Walker actuel, simplement sans le 3ᵉ niveau qui n'existera jamais sur ces rubriques) pour : Les Conseils de l'évêque, Personnel apostolique, Soutenir le diocèse. `DZ_Walker_Nav_Menu` devra être étendu (pas remplacé) pour détecter les rubriques "riches" (par slug ou par un champ ACF sur l'item de menu) et déclencher le rendu mega-menu à la place du `start_lvl()` standard. **Remplace la règle métier de SPEC.md §4 "menu limité à 2 niveaux".**
**Leçon :** Une décision de simplification prise sans le vrai plan du site doit être révisée dès que ce plan est connu, même si elle a déjà été implémentée et testée — un test qui passe ne valide que la conformité à la règle du moment, pas la pertinence de cette règle une fois de nouvelles informations disponibles.
**Statut :** ✅ Résolu — implémentée au Prompt 17, voir l'entrée "Implémentation du mega-menu hybride" ci-dessous

---

## [CHOIX] Un Custom Post Type séparé par grande rubrique organisationnelle (Conseils, Services, Commissions, Mouvements, Associations, Aumôneries, Enseignements)

**Contexte :** `NOMINATIONS_SERVICES_COMMISSINS_AUMONERIES_2027.pdf` révèle une structure organisationnelle riche partageant un même schéma (responsable + membres) sur plusieurs rubriques distinctes de `Arborescence_PDF.pdf` : Les Conseils de l'évêque, Services diocésains, Commissions diocésaines, Mouvements d'Action Catholique, Associations et Groupes d'Apostolat, Aumôneries, Enseignements diocésains.
**Symptôme / Problème :** Il fallait choisir entre un seul CPT générique avec taxonomie de type, un CPT par rubrique, ou de simples pages statiques — aucun des 4 CPT existants (`paroisse`, `pretre`, `evenement`, `sacrement`) ne couvre ce besoin.
**Cause / Alternatives :** Un CPT générique unique aurait limité le nombre de types de contenu à enregistrer, au prix d'une taxonomie de type à gérer en plus et d'un écran d'archive générique moins parlant pour les rédacteurs (qui verraient un seul menu "Organes diocésains" au lieu d'un menu par rubrique reconnaissable).
**Fix / Décision :** Le client a choisi un **CPT séparé par grande rubrique** : `conseil`, `service_diocesain`, `commission_diocesaine`, `mouvement`, `association`, `aumonerie` (+ taxonomie `type_aumonerie` : scolaire/universitaire/santé/carcérale), `etablissement`. Plus le CPT `ancien_eveque` pour "Archives > Les différents évêques". Socle de champs ACF commun à créer : description (WYSIWYG), responsable (texte ou relation vers `pretre` en réutilisant le pattern bidirectionnel déjà en place pour paroisse↔prêtre si pertinent), repeater `membres` (nom, rôle). Voir `SPEC.md` §3 pour le détail par CPT. Pour les `menu_position`, appliquer la même vérification déjà faite pour les 4 CPT existants (créneaux réservés WordPress Core : 5, 10, 20, 25, 60, 65, 70, 75, 80, 99) — les positions 26 à 32 sont libres et suffisent aux 8 nouveaux CPT.
**Leçon :** Quand l'arborescence du site distingue clairement des rubriques dans sa navigation, refléter cette distinction dans les CPT facilite la vie des rédacteurs, même si cela duplique un peu de structure technique — cohérent avec la logique déjà suivie pour les 4 CPT existants du projet.
**Statut :** 🔵 Choix assumé — 8 CPT enregistrés (Prompts 13-15), taxonomies transverses et pages statiques restantes traitées (Prompt 16) ; reste la saisie du contenu réel (nominations, doyennés, pages statiques) une fois les documents sources fournis

---

## [CHOIX] Socle ACF des CPT organisationnels en un seul groupe partagé (`group_dz_cpt_organisation_socle`), pas dupliqué par CPT

**Contexte :** PROMPT 13 enregistre les 3 premiers CPT organisationnels (`conseil`, `service_diocesain`, `commission_diocesaine`), qui partagent exactement le même socle de champs (responsable, repeater membres) d'après `SPEC.md` §3 et la décision "Un Custom Post Type séparé par grande rubrique organisationnelle". 5 autres CPT du même socle suivront (Prompts 14-15).
**Symptôme / Problème :** Créer un groupe ACF séparé par CPT (`group_dz_cpt_conseil`, `group_dz_cpt_service_diocesain`, ...) dupliquerait 3 fois (bientôt 8) des champs strictement identiques — toute évolution du socle (ex. ajouter un champ "mandat/durée") demanderait de répéter la modification dans chaque fichier JSON.
**Cause / Alternatives :** (a) un groupe ACF par CPT, fidèle à la granularité "un fichier JSON par CPT" déjà en place pour paroisse/pretre/evenement/sacrement ; (b) un seul groupe socle (`group_dz_cpt_organisation_socle`), avec des règles de localisation `post_type == X` combinées en OR pour les CPT concernés, plus un groupe séparé et minimal par CPT uniquement pour ses champs réellement spécifiques (ex. `service_diocesain_sous_structures`).
**Fix / Décision :** Option (b). `acf-json/group_dz_cpt_organisation_socle.json` (champs `org_responsable`, repeater `org_membres`) cible `conseil` OR `service_diocesain` OR `commission_diocesaine` ; `acf-json/group_dz_cpt_service_diocesain.json` ne contient que le repeater `service_diocesain_sous_structures`. Les noms de champs du socle ne sont pas préfixés par un nom de CPT (`org_*`, pas `conseil_responsable`) puisqu'ils ne sont précisément pas propres à un seul CPT — lecture assumée de la règle de nommage de `CONVENTIONS.md` ("préfixés par le nom du CPT **quand ambigu**") : ici le préfixe `org_` lève l'ambiguïté autrement, sans répéter le nom de chaque CPT. Étendre ce même groupe (ajout de règles de localisation) sera le point d'entrée pour `mouvement`/`association`/`aumonerie`/`etablissement` (Prompts 14-15) ; `ancien_eveque` n'a pas ce socle (champs propres : photo, période, biographie, voir `SPEC.md` §3) et aura son propre groupe.
**Leçon :** Quand plusieurs CPT partagent un socle de champs identique par construction (pas par coïncidence), un seul groupe ACF avec des règles de localisation combinées évite la duplication de configuration, même si les CPT eux-mêmes restent enregistrés séparément (cohérent avec le principe déjà appliqué : ne pas dupliquer un champ déjà existant).
**Statut :** 🔵 Choix assumé

---

## [CHOIX] `responsable` en simple champ texte, pas de relation obligatoire vers `pretre`

**Contexte :** `SPEC.md` §3 décrit le champ comme "texte libre ou relation vers pretre" ; la décision "Un Custom Post Type séparé..." envisageait de réutiliser le pattern bidirectionnel paroisse↔pretre "si pertinent".
**Symptôme / Problème :** Contrairement à une paroisse (toujours desservie par un clergé), un conseil/service/commission diocésain peut être dirigé par un laïc (ex. un économat tenu par un laïc, une commission animée par un responsable non-prêtre) — forcer une relation `post_object` vers le CPT `pretre` échouerait ou obligerait à créer une fiche `pretre` factice pour un responsable laïc.
**Cause / Alternatives :** (a) champ `relationship`/`post_object` vers `pretre`, bidirectionnel comme paroisse↔pretre ; (b) simple champ texte libre (`org_responsable`), qui couvre indifféremment clergé et laïcs, sans synchronisation à maintenir.
**Fix / Décision :** Option (b). Pas de champ relationnel en v1 pour ce socle : `org_responsable` est un champ texte (voir `acf-json/group_dz_cpt_organisation_socle.json`). Aucun besoin métier documenté de lister, côté fiche d'un prêtre, tous les organes qu'il dirige (contrairement à "quelle paroisse dessert ce prêtre", qui est une question réelle posée par `SPEC.md` §4) — si ce besoin apparaît, une relation pourra être ajoutée en complément du texte libre, pas à sa place.
**Leçon :** Le pattern bidirectionnel de paroisse↔pretre est justifié par un besoin métier précis (un prêtre dessert une seule paroisse, affichée des deux côtés) ; ne pas le reproduire par réflexe partout où un "responsable" apparaît si le texte libre couvre déjà tous les cas réels (y compris les responsables laïcs).
**Statut :** 🔵 Choix assumé — à revoir si le diocèse confirme que tous les responsables de ces 3 CPT sont systématiquement des prêtres déjà fichés

---

## [CHOIX] `.organisation-card` créée sur mesure pour la grille d'archive ; `.info-card` écarté

**Contexte :** Les archives `archive-conseil.php`/`archive-service_diocesain.php`/`archive-commission_diocesaine.php` ont besoin d'une carte de grille, comme `.paroisse-card`/`.evenement-card` avant elles (Tâches 6-7).
**Symptôme / Problème :** `.info-card` (déjà réutilisé 3 fois pour les tuiles de coordonnées/paroisse référente) semblait à première vue un candidat naturel de réutilisation. Mais son CSS (`.contact .info-card`) n'a de sens que posé sur le fond dégradé sombre de `.contact-info-panel` (fond blanc semi-transparent 10%, texte `--contrast-color` clair) : utilisé seul sur le fond clair d'une grille d'archive, il serait quasiment illisible (texte clair sur fond quasi blanc).
**Cause / Alternatives :** (a) forcer `.info-card` en dehors de son panneau sombre d'origine ; (b) créer `.organisation-card`, sur le même moule que `.paroisse-card` (photo optionnelle en haut, corps avec titre + méta), avec un bloc icône de repli quand la photo est absente (`photo optionnelle`, contrairement à paroisse/événement où l'image est quasi systématique).
**Fix / Décision :** Option (b) — `template-parts/card-organisation.php`, classe `.organisation-card` ajoutée à la suite de `.evenement-card` dans `assets/css/main.css` du thème. `.info-card` reste réservé à son usage actuel : une tuile à l'intérieur d'un `.contact-info-panel` (paroisse, prêtre, sacrement, et maintenant le bloc "Responsable" de `template-parts/organisation-composition.php`, qui respecte ce même couplage).
**Leçon :** Avant de réutiliser un composant existant, vérifier aussi le contexte visuel dont il dépend (fond de section, couleur de texte), pas seulement sa structure HTML — un composant qui "a la bonne forme" peut rester inutilisable hors de son fond d'origine.
**Statut :** 🔵 Choix assumé

---

## [CHOIX] Contenu de test des CPT organisationnels limité à des brouillons structurels — noms réels non saisis (PDF absent du dépôt)

**Contexte :** PROMPT 13 demande de saisir au moins une entrée par CPT à partir de `NOMINATIONS_SERVICES_COMMISSIONS_AUMONERIES_2027.pdf` (ex. Conseil épiscopal, Économat diocésain).
**Symptôme / Problème :** Ce PDF n'est physiquement présent nulle part dans ce dépôt (vérifié) ; son contenu détaillé (noms et rôles réels des responsables/membres) n'est connu qu'au travers du résumé déjà consigné dans `SPEC.md`/`DECISIONS.md` (noms des rubriques uniquement, ex. "Économat, Caritas, ODEC..."), pas les personnes qui les dirigent.
**Cause / Alternatives :** (a) inventer des noms de responsables/membres plausibles pour peupler l'exemple ; (b) créer uniquement la structure (titre réel de la rubrique, statut brouillon, note éditoriale explicite "à compléter"), en laissant vide tout champ qui exposerait une donnée personnelle non vérifiée.
**Fix / Décision :** Option (b), pour rester conforme à SPEC.md §9 (pas de contenu mensonger en production) et par prudence générale sur l'exactitude de données nominatives concernant une institution réelle. `bin/seed-cpt-organisation.php` (nouveau, hors du thème — script `wp eval-file` à exécuter une fois un WordPress réel disponible, cf. TODO.md Phase 8) crée 3 entrées en statut `draft` : "Conseil épiscopal" (`conseil`), "Économat" (`service_diocesain`, avec une sous-structure "Hôtel Carabane" — nom réel documenté dans `SPEC.md` §3, pas inventé), "Catéchèse" (`commission_diocesaine`) ; les champs `org_responsable`/`org_membres`/`service_diocesain_sous_structure_responsable` restent vides plutôt que remplis de noms fictifs. Objectif atteint : vérifier que les gabarits `single-*.php`/`archive-*.php` fonctionnent avec du vrai contenu WordPress, sans publier de fausses informations nominatives.
**Leçon :** "Saisir un contenu de test à partir d'un document source" suppose que ce document soit réellement accessible ; à défaut, mieux vaut un brouillon honnête et incomplet qu'un exemple plausible mais inventé sur une institution réelle.
**Statut :** 🔵 Choix assumé — à compléter dès que `NOMINATIONS_SERVICES_COMMISSIONS_AUMONERIES_2027.pdf` est fourni dans le dépôt

---

## [CHOIX] `mouvement_aumonier` en relation `post_object` simple, non bidirectionnelle

**Contexte :** PROMPT 14 enregistre `mouvement`/`association`/`aumonerie` sur le même socle que PROMPT 13 (`group_dz_cpt_organisation_socle`), plus le champ `aumonier` (relation `pretre`) sur `mouvement` explicitement demandé par `SPEC.md` §3 — contrairement à `org_responsable`, qui reste volontairement un texte libre (voir "`responsable` en simple champ texte" ci-dessus).
**Symptôme / Problème :** Le pattern bidirectionnel paroisse↔prêtre (`field_dz_pretre_paroisse` / `field_dz_paroisse_pretres`) existe déjà dans le thème ; fallait-il le reproduire ici, en ajoutant un champ miroir (ex. `pretre_aumonerie_mouvements`) sur `group_dz_cpt_pretre.json` ?
**Cause / Alternatives :** (a) relation bidirectionnelle comme paroisse↔prêtre, avec un champ `relationship` ajouté côté fiche prêtre listant les mouvements dont il est aumônier ; (b) relation `post_object` simple côté `mouvement` uniquement, sans rien changer à `group_dz_cpt_pretre.json`.
**Fix / Décision :** Option (b) — `field_dz_mouvement_aumonier` (`acf-json/group_dz_cpt_mouvement.json`), `post_object` vers `pretre`, valeur unique, sans `bidirectional`. Même raisonnement que la décision "`responsable` en simple champ texte" : aucun besoin métier documenté de lister, côté fiche d'un prêtre, les mouvements dont il est aumônier (contrairement à "quelle paroisse dessert ce prêtre", posé par `SPEC.md` §4). `SPEC.md` §3 demande une relation (donc pas de simple texte, un aumônier est toujours un prêtre fiché), mais pas de synchronisation à double sens.
**Leçon :** "Relation vers `pretre`" ne veut pas automatiquement dire "bidirectionnelle" : le pattern paroisse↔prêtre reste réservé aux cas où une question réelle se pose des deux côtés de la relation.
**Statut :** 🔵 Choix assumé — à revoir si le diocèse demande un jour "quels mouvements cet aumônier encadre-t-il ?" côté fiche prêtre

---

## [CHOIX] `type_aumonerie` : taxonomie hiérarchique à termes fixes pré-créés, filtrage d'archive sans route de taxonomie dédiée

**Contexte :** PROMPT 14 demande la taxonomie `type_aumonerie` (scolaire/universitaire/santé/carcérale) sur `aumonerie`, avec une archive filtrable par ce type.
**Symptôme / Problème :** Trois choix à trancher : (1) taxonomie hiérarchique (façon catégorie, cases à cocher) ou façon étiquette (texte libre) ; (2) termes créés à la main par un rédacteur ou pré-remplis par le thème ; (3) filtrage via la route native `/type_aumonerie/<slug>/` (nécessitant un gabarit `taxonomy-type_aumonerie.php` dédié) ou via un paramètre sur `archive-aumonerie.php` existant.
**Cause / Alternatives :** Une taxonomie façon étiquette laisserait un rédacteur créer des variantes du même type (ex. "Santé" vs "santé" vs "Hôpitaux") ; une route de taxonomie native sans gabarit dédié retomberait sur `archive.php` (gabarit blog générique, cartes `.card-article`), rompant la cohérence visuelle demandée ("réutilise le style de carte déjà en place").
**Fix / Décision :** Taxonomie hiérarchique (`hierarchical => true`, UI à cases à cocher comme les catégories natives) avec ses 4 termes (`scolaire`, `universitaire`, `sante`, `carcerale`) pré-créés par `dz_seed_type_aumonerie_terms()` (hook `init`, idempotent via `term_exists()`) — un rédacteur ne peut donc que cocher parmi ces 4 termes, jamais en inventer un nouveau. Pas de route d'archive dédiée (`rewrite => false`) : le filtrage se fait sur `archive-aumonerie.php` lui-même via `?type_aumonerie=<slug>` (liens d'onglets `nav-pills`, nouvelle classe `.aumonerie-filters` dans `main.css`) et `dz_aumonerie_archive_query()` (`pre_get_posts`, même pattern que `dz_evenement_archive_query()` déjà en place pour l'agenda), pour que toute vue filtrée ou non passe par le même gabarit et la même `.organisation-card`.
**Leçon :** Une taxonomie à valeurs fixes et peu nombreuses (type/catégorie métier, pas un tag libre) gagne à être pré-remplie plutôt que laissée vide pour le rédacteur — cohérent avec l'esprit "pas de code en dur, mais pas non plus de saisie libre là où une valeur métier est fermée par nature".
**Statut :** 🔵 Choix assumé

---

## [CHOIX] `etablissement_contact` en simple champ texte, pas de champs téléphone/e-mail séparés

**Contexte :** PROMPT 15 enregistre `etablissement` sur le socle organisationnel + deux champs spécifiques : `type_etablissement` (select) et `contact`. `paroisse`/`pretre` ont déjà chacun deux champs dédiés `telephone`/`email` pour leurs coordonnées.
**Symptôme / Problème :** `SPEC.md` §3 ne nomme qu'un seul champ, `contact`, pour `etablissement` — contrairement à `paroisse_telephone`/`paroisse_email` qui sont explicitement deux champs distincts dans la même spec.
**Cause / Alternatives :** (a) reproduire le couple `telephone`/`email` de paroisse/prêtre par cohérence de structure ; (b) un seul champ texte libre `etablissement_contact`, fidèle à la formulation exacte de `SPEC.md` §3.
**Fix / Décision :** Option (b) — `field_dz_etablissement_contact` (`acf-json/group_dz_cpt_etablissement.json`), type `text`, sans format imposé (peut contenir un nom de correspondant, un téléphone, un e-mail, ou une combinaison). `SPEC.md` ne distingue pas ici téléphone et e-mail comme il le fait pour paroisse/prêtre ; forcer cette même structure aurait ajouté une contrainte de saisie non demandée (voir la logique déjà suivie pour `org_responsable`, un texte libre plutôt qu'une relation non demandée).
**Leçon :** Ne pas généraliser un pattern de champ (ici `telephone`/`email` séparés) à un nouveau CPT simplement parce qu'il existe déjà ailleurs dans le thème — suivre la formulation du besoin telle que `SPEC.md` la donne pour ce CPT précis.
**Statut :** 🔵 Choix assumé — à revoir si les rédacteurs demandent un format de coordonnées structuré (tri, lien `tel:`/`mailto:` cliquable) pour `etablissement`

---

## [CHOIX] `ancien_eveque` hors du socle organisationnel ; tri d'archive chronologique ascendant (plus ancien en premier)

**Contexte :** PROMPT 15 enregistre `ancien_eveque` pour "Archives > Les différents évêques" (photo, période, biographie), avec une exigence explicite : l'archive doit être triée par date de début de mandat, pas par date de publication WordPress.
**Symptôme / Problème :** Deux décisions à trancher : (1) `ancien_eveque` partage-t-il le socle `responsable`/`membres` comme les 7 autres CPT organisationnels ? (2) "trié... chronologique" veut-il dire du plus ancien mandat au plus récent (ASC), ou l'inverse (DESC, le mandat le plus récent en tête, plus courant pour une page "Archives" consultée de nos jours) ?
**Cause / Alternatives :** Pour (1) : `SPEC.md` §3 décrit `ancien_eveque` avec des champs propres (photo, période, biographie) sans mentionner responsable/membres, contrairement aux 7 autres lignes du même tableau — confirmé par la décision "Socle ACF des CPT organisationnels..." ci-dessus, qui l'exclut déjà explicitement du socle. Pour (2) : "chronologique" est lu ici dans son sens le plus littéral (suite historique, du plus ancien au plus récent), plutôt que "les plus récents d'abord" (habituel pour un fil d'actualité, mais pas pour une frise historique).
**Fix / Décision :** Pas de `group_dz_cpt_organisation_socle` pour `ancien_eveque` — groupe dédié `acf-json/group_dz_cpt_ancien_eveque.json` avec seulement `ancien_eveque_date_debut` (obligatoire, sert au tri) et `ancien_eveque_date_fin` (optionnel). Photo = image mise en avant (featured image, comme `pretre`/`conseil`...), biographie = `the_content()` (éditeur natif, même logique que tous les autres CPT du thème). Tri d'archive : `dz_ancien_eveque_archive_query()` (`inc/cpt-ancien_eveque.php`, hook `pre_get_posts`, même pattern que `dz_evenement_archive_query()`/`dz_pretre_archive_query()` déjà en place) — `meta_key => ancien_eveque_date_debut`, `orderby => meta_value`, `order => ASC`.
**Leçon :** Un mot comme "chronologique" a deux lectures usuelles opposées (frise historique vs fil d'actualité) ; le choix ASC ici est documenté précisément pour pouvoir être inversé en une ligne (`order => DESC`) si le client, en recette, attend plutôt "l'évêque le plus récent en premier".
**Statut :** 🔵 Choix assumé — sens du tri (ASC) à confirmer en recette avec le diocèse

---

## [CHOIX] `doyenne` : taxonomie ouverte, sans termes pré-créés, contrairement à `type_aumonerie`/`evenement_type`

**Contexte :** PROMPT 16 demande la taxonomie `doyenne` sur `paroisse` ("remplace `secteur_pastoral`", `SPEC.md` §3), avec pour seule exigence de vérifier qu'elle apparaît dans l'admin à l'édition d'une paroisse.
**Symptôme / Problème :** Les deux précédentes taxonomies du thème (`type_aumonerie`, PROMPT 14 ; `evenement_type`, ci-dessous) ont toutes deux leurs termes pré-créés par le code (`dz_seed_..._terms()`), parce que `SPEC.md` en donne la liste fermée et exacte. Fallait-il faire pareil pour `doyenne` ?
**Cause / Alternatives :** (a) pré-créer une liste de doyennés, par extrapolation ou en inventant des noms plausibles ; (b) ne rien pré-créer, laisser les rédacteurs créer les termes eux-mêmes via l'UI standard (façon catégories).
**Fix / Décision :** Option (b) — `dz_register_taxonomy_doyenne()` (`inc/cpt-paroisse.php`), `hierarchical => true` (UI à cases à cocher, cohérent avec le reste du thème) mais aucun terme pré-créé. Contrairement à "scolaire/universitaire/santé/carcérale" ou "Diocésain/Évêque", aucun document du dépôt ne donne la liste réelle des doyennés du diocèse de Ziguinchor ; en inventer une reviendrait à publier une donnée institutionnelle non vérifiée (même principe que "Contenu de test des CPT organisationnels..." ci-dessus : pas de contenu plausible mais inventé sur une institution réelle).
**Leçon :** Pré-créer les termes d'une taxonomie est la bonne pratique pour resserrer la saisie **quand la liste fermée est documentée** (`SPEC.md`) — pas un réflexe à appliquer à toute nouvelle taxonomie, sous peine d'inventer une donnée réelle non vérifiée.
**Statut :** 🔵 Choix assumé — à revoir dès que la liste réelle des doyennés est fournie par le diocèse (pré-créer les termes à ce moment-là)

---

## [CHOIX] `evenement_type` : mêmes pattern/`?evenement_type=<slug>` que `type_aumonerie`, classe CSS `.archive-filters` généralisée

**Contexte :** PROMPT 16 demande la taxonomie `evenement_type` (Diocésain/Évêque) sur `evenement`, avec "deux archives distinctes ou un filtre sur `archive-evenement.php`" pour séparer les deux agendas.
**Symptôme / Problème :** Choix entre deux gabarits d'archive distincts (`archive-evenement.php` scindé, ou deux CPT/query différents) et un filtre unique sur l'archive existante — exactement le même choix déjà tranché pour `type_aumonerie` (PROMPT 14, voir "`type_aumonerie` : taxonomie hiérarchique..." ci-dessus).
**Cause / Alternatives :** Reproduire ce même choix ((a) route de taxonomie native, nécessitant un `taxonomy-evenement_type.php` dédié pour ne pas retomber sur `archive.php` ; (b) filtre `?evenement_type=<slug>` sur `archive-evenement.php` existant, sans nouvelle route).
**Fix / Décision :** Option (b), par cohérence avec `type_aumonerie` — `dz_register_taxonomy_evenement_type()`/`dz_seed_evenement_type_terms()`/l'extension de `dz_evenement_archive_query()` (`inc/cpt-evenement.php`), onglets "Tous / Agenda Diocésain / Agenda de l'évêque" sur `archive-evenement.php`. Le filtre s'ajoute au `meta_query` "à venir/en cours" déjà en place (indépendants, aucun conflit). Le H1 de la page reste statique ("Agenda", au lieu de l'ancien "Agenda diocésain" — devenu ambigu une fois "Agenda Diocésain" réutilisé comme libellé d'onglet), seuls les onglets et la liste changent — même logique que `archive-aumonerie.php`, dont le titre "Aumôneries" ne change pas non plus selon le filtre actif. La classe CSS `.aumonerie-filters` (PROMPT 14) est renommée `.archive-filters` et partagée par les deux archives plutôt que dupliquée.
**Leçon :** Une fois un pattern établi pour un besoin (ici : filtrer une archive de CPT par une taxonomie à valeurs fixes sans casser la cohérence visuelle), le réappliquer tel quel au prochain cas identique évite une divergence de gabarits sans réelle justification métier — y compris jusqu'au nom de la classe CSS partagée.
**Statut :** 🔵 Choix assumé

---

## [CHOIX] Pages statiques : `page.php` générique + `page-cartographie.php` dédié ; "Contacts" distinct de "Contact"

**Contexte :** PROMPT 16 demande de créer les 13 pages statiques listées dans `SPEC.md` §3, avec `page.php` (jusque-là un stub, voir TODO.md Phase 4) comme gabarit commun, sauf "Cartographie du diocèse" (embed carte) et "Devenir bénévole" (formulaire Contact Form 7 dédié).
**Symptôme / Problème :** Trois points à trancher : (1) "Devenir bénévole" a-t-il besoin d'un gabarit dédié comme `page-contact.php`, ou le `page.php` générique suffit-il ? (2) comment intégrer une carte pour "Cartographie du diocèse" sans redemander une adresse déjà saisie ailleurs ? (3) `SPEC.md` §3 liste "Contacts" parmi ces pages statiques, alors qu'une page "Contact" (formulaire + carte) existe déjà (`page-contact.php`, Tâche 8) — même page, ou deux pages distinctes ?
**Cause / Alternatives :** Pour (1) : contrairement à la page Contact (qui affiche aussi les coordonnées/carte structurées de la page d'options), "Devenir bénévole" n'a besoin que d'un texte d'intro + un shortcode CF7 — les deux passent déjà par `the_content()`, donc `page.php` seul suffit, comme pour n'importe quelle page WordPress standard. Pour (2) : soit un nouveau champ ACF "adresse" redemandé sur cette page, soit réutiliser `dz_contact_address` (page d'options, déjà utilisé par `page-contact.php`). Pour (3) : `SPEC.md` §11 place "Contacts" comme sous-page d'Accueil et cite séparément "Contact (formulaire + carte)" "hors arborescence numérotée" — ce sont donc deux entrées distinctes dans le document source, pas un doublon de rédaction.
**Fix / Décision :** (1) Pas de gabarit dédié pour "Devenir bénévole" : `page.php` générique, shortcode CF7 à coller dans le contenu de la page en wp-admin, exactement comme déjà documenté pour la page Contact. (2) `page-cartographie.php` (nouveau `Template Name`) réutilise `dz_get_option( 'dz_contact_address' )` pour construire son embed Google Maps, plutôt que de dupliquer ce champ. (3) "Contacts" est traitée comme une page distincte de "Contact" dans `bin/seed-static-pages.php` (13 pages, dont "Contacts"), en s'appuyant sur la lecture de `SPEC.md` §11 ci-dessus.
**Leçon :** Une même chaîne ("Contact"/"Contacts") peut désigner deux pages différentes dans un document de spécification rédigé progressivement (arborescence ajoutée après coup, §11) — vérifier le contexte complet (ici : la page apparaît sous deux rubriques distinctes du même document) avant de fusionner par réflexe deux entrées qui semblent être des doublons.
**Statut :** 🔵 Choix assumé — la distinction Contacts/Contact est à confirmer avec le diocèse dès que possible (le contenu réel de "Contacts" clarifiera probablement son rôle exact)

---

## [CHOIX] Implémentation du mega-menu hybride : bascule par champ ACF sur l'élément de menu, `wp_nav_menu( depth => 3 )`, panneau click/tap-driven

**Contexte :** PROMPT 17 demande de remplacer `DZ_Walker_Nav_Menu` (limité à 2 niveaux) par une implémentation méga-menu hybride pour les 4 rubriques riches (À propos du diocèse, Services et Commissions, Apostolat des Laïques, Vie de Foi), en gardant le dropdown standard pour les 3 rubriques légères — voir la décision "Mega-menu hybride" ci-dessus, qui n'en posait que le principe.
**Symptôme / Problème :** Trois choix d'implémentation restaient ouverts : (1) comment détecter qu'un élément de premier niveau doit s'afficher en méga-menu — par correspondance de slug/libellé, ou par un champ sur l'élément lui-même ? (2) `wp_nav_menu()` est appelé avec `depth => 2` (`header.php`) : les petits-enfants (Archives > Histoire du diocèse / Les différents évêques) ne sont même pas walkés par WordPress à cette profondeur — comment les rendre "à plat" sous leur colonne sans réintroduire un dropdown imbriqué ? (3) le dropdown standard existant est hover-déclenché sur desktop ; le méga-menu doit lui être clic/tap-déclenché à toutes les tailles (énoncé du prompt) — comment faire cohabiter les deux déclencheurs sur le même Walker/CSS/JS sans dupliquer tout le composant ?
**Cause / Alternatives :** Pour (1) : (a) correspondance de slug/libellé sur l'item ("À propos du diocèse" en dur dans le Walker) — fragile si le libellé change, dupliquerait la liste des 4 rubriques dans le code alors qu'elle vit déjà dans l'arborescence éditée en wp-admin ; (b) champ ACF booléen sur l'élément de menu (location `nav_menu_item == all`, standard ACF Pro), coché par le rédacteur au cas par cas. Pour (2) : passer `depth => 3` change le comportement pour TOUTES les rubriques, y compris les légères — il fallait donc que le Walker lui-même continue à ignorer la profondeur 2 pour elles. Pour (3) : (a) dupliquer un second Walker/CSS/JS entièrement séparé pour le méga-menu ; (b) réutiliser le même marquage visuel/déclencheur `.toggle-dropdown` (icône chevron) déjà géré par `main.js`, avec une classe de `<li>` différente (`mega-menu-parent` au lieu de `dropdown`) pour que le CSS de survol du dropdown standard ne s'applique jamais au méga-menu, plus un second gestionnaire de clic ciblant tout le lien déclencheur (pas seulement l'icône), puisque l'énoncé demande explicitement "clic/tap sur l'item de menu principal".
**Fix / Décision :**
- (1) Option (b) — `acf-json/group_dz_nav_menu_item.json` (champ `dz_nav_item_megamenu`, `true_false`, location `nav_menu_item == all`), lu par `DZ_Walker_Nav_Menu::walk()` via `get_field( 'dz_nav_item_megamenu', $element->ID )` (gardé par `function_exists('get_field')`).
- (2) `header.php` passe désormais `'depth' => 3` (au lieu de 2) : WordPress walke maintenant les petits-enfants, mais `DZ_Walker_Nav_Menu::start_el()`/`end_el()` continuent de les ignorer (`return` anticipé) sauf pour les branches méga-menu — même profondeur autorisée pour tout le monde, seul le rendu diffère selon `$this->dz_in_megamenu` (calculé une fois par élément de premier niveau dans `start_el`, valide pour toute sa descendance grâce à l'ordre de parcours en profondeur garanti par `Walker::display_element()`, voir le docblock de la classe).
- (3) Option (b) — `class="mega-menu-parent"` (pas `dropdown`) sur le `<li>` de premier niveau ; icône chevron `.toggle-dropdown` réutilisée telle quelle (même gestionnaire `main.js` existant continue de fonctionner, puisqu'il cible la classe, pas le contexte dropdown/méga-menu) ; nouveau gestionnaire `.navmenu .mega-menu-parent > a` (tout le lien) qui reproduit exactement la même paire de classes `[.active sur le lien] / [.dropdown-active sur le panneau]` que le gestionnaire de l'icône, pour que les deux déclencheurs convergent vers le même état, plus fermeture au clic extérieur et à l'Échap (avec retour du focus sur le déclencheur). Le gestionnaire générique "fermer le menu mobile à tout clic sur `#navmenu a`" (préexistant) est explicitement exclu pour les liens `.mega-menu-parent > a`, sans quoi il fermerait tout le menu mobile avant que le méga-menu ait pu s'ouvrir (il est enregistré plus tôt dans `main.js`, donc s'exécuterait avant le nouveau gestionnaire malgré son `stopImmediatePropagation()`).
**Leçon :** Un composant qui doit cohabiter avec un système existant (ici : le dropdown standard, son CSS de survol, son gestionnaire de clic sur le chevron, et le gestionnaire générique de fermeture du menu mobile) demande de vérifier explicitement, pour CHAQUE règle CSS/JS générique déjà en place, si son sélecteur/écouteur est assez large pour capturer aussi le nouveau composant par erreur — un sélecteur générique comme `.navmenu ul` ou `#navmenu a` ne "sait" pas qu'il existe un méga-menu, et l'attrapera silencieusement sauf exclusion explicite (voir aussi le bug ci-dessous, trouvé en testant).
**Statut :** ✅ Résolu

---

## [RÉSOLU] Bug — `.navmenu ul`/`.navmenu li` génériques polluaient les nouvelles listes/colonnes du méga-menu

**Contexte :** PROMPT 17, en construisant/testant une maquette HTML statique du méga-menu (`.mega-menu-columns`, `.mega-menu-sublist`) reproduisant fidèlement la sortie du Walker, faute de WordPress réel dans ce dépôt pour tester en conditions réelles (voir DECISIONS.md, contrainte déjà rencontrée plusieurs fois).
**Symptôme / Problème :** Deux régressions visuelles trouvées par capture d'écran Chrome headless (desktop 1440px et mobile 390px) : (1) sur desktop, les éléments plats d'une colonne à petits-enfants ("Archives" > "Histoire du diocèse"/"Les différents évêques") s'affichaient collés sur une seule ligne au lieu de s'empiler, et les colonnes de hauteurs différentes étaient centrées verticalement au lieu d'être alignées en haut ; (2) sur mobile, `.mega-menu-columns` et `.mega-menu-sublist` s'affichaient comme des panneaux flottants superposés (fond blanc, ombre, décalés de l'écran) au lieu de s'empiler normalement dans l'accordéon.
**Cause / Alternatives :** `.mega-menu-columns` et `.mega-menu-sublist` sont tous deux de simples `<ul>` — la règle générique déjà en place `.navmenu ul { display: flex; align-items: center; ... }` (desktop) et `.navmenu ul { position: absolute; inset: 60px 20px 20px 20px; background: ...; box-shadow: ...; z-index: ...; display: none; ... }` (mobile, pensée pour le `<ul>` racine du menu déroulant plein écran) s'appliquent à N'IMPORTE QUEL `<ul>` imbriqué dans `.navmenu`, méga-menu compris. La première passe n'avait réinitialisé que `display` (et, sur desktop, ni `align-items`), oubliant que la règle mobile fixe aussi `position`/`inset`/`background`/`box-shadow`/`z-index` — propriétés non couvertes par ma première correction, d'où la persistance du bug (2) après avoir corrigé le (1).
**Fix / Décision :** Réinitialisation complète (pas seulement `display`) de `.mega-menu-columns`/`.mega-menu-sublist` dans les deux blocs media query : `position: static; inset: auto; background: transparent; box-shadow: none; border-radius: 0; z-index: auto; overflow: visible;` en plus de `display`/`align-items` déjà corrigés — voir `assets/css/main.css`, sections "Mega-menu (méga-menu hybride)".
**Leçon :** Un bug de fuite de style via un sélecteur générique existant se corrige rarement en un seul passage si on ne réinitialise qu'UNE propriété observée visuellement (ici `display`) — mieux vaut lister explicitement TOUTES les propriétés déclarées par la règle générique incriminée et les réinitialiser d'un bloc, plutôt que de corriger propriété par propriété au fil des régressions constatées.
**Statut :** ✅ Résolu

---

## [CHOIX] Validation du méga-menu sans WordPress réel : maquette HTML statique + Chrome headless

**Contexte :** PROMPT 17 demande explicitement de "tester particulièrement le rendu mobile avant de considérer la tâche terminée" — mais ce dépôt n'a toujours pas d'installation WordPress/MySQL réelle (contrainte déjà documentée pour PROMPT 13 et suivants), donc aucun vrai menu wp-admin à cliquer.
**Symptôme / Problème :** Sans rendu réel, impossible de vérifier honnêtement que le Walker produit le bon balisage et que le CSS/JS se comportent correctement aux deux points de rupture (desktop ≥1200px, mobile <1200px) — une simple relecture de code n'aurait pas trouvé le bug de fuite de style documenté ci-dessus (trouvé uniquement par capture d'écran).
**Cause / Alternatives :** (a) ne tester que par relecture de code, comme pour les gabarits PHP des prompts précédents (acceptable là où aucun rendu visuel complexe n'est en jeu) ; (b) construire une page HTML statique reproduisant exactement la sortie attendue du Walker (mêmes classes, même arborescence de test), chargée avec les vrais fichiers `main.css`/`main.js`/Bootstrap du thème, et la faire rendre par un vrai moteur de rendu (Chrome headless, disponible sur cette machine) pour capturer des captures d'écran à plusieurs largeurs et états (fermé, ouvert, Échap, clic extérieur, accordéon mobile).
**Fix / Décision :** Option (b). Maquette de test (non versionnée dans le thème, fichier temporaire de session) simulant la sortie de `DZ_Walker_Nav_Menu` pour un arbre représentatif (1 rubrique méga avec une colonne à petits-enfants "Archives", 1 dropdown léger, des liens simples), pilotée par des paramètres d'URL (`?mega=1`, `?mobilenav=1`, `?escape=1`, `?outside=1`...) actionnant les mêmes gestionnaires `main.js` réels, capturée via `google-chrome --headless=new --screenshot`. A permis de confirmer visuellement : ouverture/fermeture du panneau au clic, alignement des colonnes, rendu à plat des petits-enfants, accordéon mobile empilé (pas de dropdown imbriqué), fermeture à l'Échap avec retour du focus, fermeture au clic extérieur — et de détecter le bug de fuite de style ci-dessus avant de considérer la tâche terminée.
**Leçon :** Face à une contrainte récurrente ("pas de WordPress réel dans ce dépôt"), le niveau de rigueur de la vérification doit rester proportionné à la nature du changement — un template PHP texte-only s'évalue à la lecture, un composant d'interaction CSS/JS avec plusieurs points de rupture responsive ne peut être validé honnêtement qu'en le faisant réellement rendre par un navigateur, même sans WordPress derrière.
**Statut :** 🔵 Choix assumé — à refaire (ou remplacer par un vrai test manuel) dès qu'une installation WordPress réelle existe, avec le vrai menu construit en wp-admin

---

## [CHOIX] Revue design globale (PROMPT 12) : méthode et périmètre des corrections

**Contexte :** PROMPT 12 demande de relire l'ensemble des gabarits déjà convertis pour vérifier cohérence des titres/fils d'ariane, absence de Lorem Ipsum/placeholder du template source dans le code, bon fonctionnement des animations AOS et du menu mobile, et affichage correct aux 3 tailles Bootstrap — en documentant toute incohérence trouvée dans `BUGS_AND_ROADMAP.md` avant correction.
**Symptôme / Problème :** Deux limites à respecter pour rester honnête sur ce qui a réellement été vérifié : (1) toujours pas de WordPress réel dans ce dépôt (contrainte récurrente, voir PROMPT 13 et suivants) — impossible de "voir" chaque page rendue dans un vrai navigateur avec du vrai contenu ; (2) le périmètre exact de la revue ("l'ensemble des gabarits") doit être interprété — jusqu'où remonter (les gabarits de la Tâche 2 comme `header.php`/`footer.php`, déjà en place avant les prompts business, en font-ils partie) ?
**Cause / Alternatives :** Pour (1) : (a) ne rien vérifier sans rendu réel ; (b) vérification systématique par recherche de motifs (`grep`) sur l'ensemble des fichiers — présence de `get_header()`/`get_footer()`, de `template-parts/page-title`, de `data-aos`, de textes Lorem Ipsum/placeholder, cohérence des classes de grille Bootstrap — complétée par une relecture ciblée des gabarits à plus fort trafic (`front-page.php`, `archive.php`, `search.php`). Pour (2) : traiter chaque incohérence trouvée au cas par cas selon qu'elle touche un gabarit "nouvellement créé" par les prompts business (dans le périmètre direct de la demande) ou un fichier de fondation de la Tâche 2 déjà testé (documenté en ROADMAP plutôt que corrigé dans cette passe, pour ne pas rouvrir un composant partagé déjà validé sans demande explicite).
**Fix / Décision :** Option (b) pour (1). Pour (2) : `404.php`/`index.php` (stubs vides depuis leur création en Tâche 1/Phase 4, jamais retouchés) et l'écart `data-aos` de `page-contact.php` (gabarit business de la Tâche 8) sont corrigés dans cette passe — voir `BUGS_AND_ROADMAP.md`. Le `<h1>` en double (fallback logo de `header.php` sans image configurée, cumulé avec le `<h1>` de chaque page) est documenté en ROADMAP mais **pas corrigé** : hérité du template source (`starter-page.html`/`404.html` ont le même défaut), localisé dans `header.php` (Tâche 2, hors périmètre "gabarits nouvellement créés"), et sa correction toucherait aussi le sélecteur CSS `.header .logo h1` — un changement plus large que ce que cette revue vise à couvrir.
**Note méthodologique (AOS) :** en construisant la maquette de test pour `404.php` (même méthode que PROMPT 17), la capture d'écran Chrome headless montrait une section entièrement blanche malgré un balisage correct — piste initiale d'un bug réel. Investigation (`--dump-dom` après exécution, plutôt que `--screenshot`) : la classe `.aos-animate` est bien appliquée par `AOS.init()` (appelé sur `window.load` dans `main.js`), donc le mécanisme d'animation fonctionne correctement ; c'est uniquement la capture d'écran `--screenshot` qui devançait le repaint déclenché par le `setTimeout` interne d'AOS — un artefact de l'outil de test, pas un bug du thème. Documenté ici pour éviter qu'une future session ne rouvre cette fausse piste sans republier le même diagnostic.
**Leçon :** "Relire l'ensemble des gabarits" pour une revue de cohérence ne veut pas dire "tout retoucher" : une incohérence trouvée dans un fichier de fondation déjà testé mérite d'être documentée pour traçabilité, mais sa correction peut légitimement attendre une demande explicite plutôt que d'élargir le périmètre d'une passe de revue.
**Statut :** ✅ Résolu

---

## [CHOIX] Mécanisme d'import de contenu réutilisable (`CONTENT_PROMPTS.md` PROMPT 0)

**Contexte :** `CONTENT_PROMPTS.md` (nouveau document, complète `DESIGN_PROMPTS.md`) introduit une deuxième vague de prompts : peupler les CPT/champs déjà enregistrés (Prompts 13-16 de `DESIGN_PROMPTS.md`, terminés — voir les entrées correspondantes ci-dessus) avec le vrai contenu du diocèse, à partir de PDF/dossiers fournis (`NOMINATIONS_SERVICES_COMMISSIONS_AUMONERIES_2027.pdf`, `CALENDRIER_DIOCESAIN_2027.pdf`, `Copie_de_Armoiries_Diocèse_de_Ziguinchor.pdf`, `assets/img/eveque/`). Contrainte technique explicitement rappelée par le document lui-même : ce dépôt est le code du thème, pas la base de données d'un site en ligne — impossible de "créer un article" comme le ferait un rédacteur dans l'admin ; il faut un mécanisme qui, une fois exécuté sur le vrai site, crée le contenu via les fonctions WordPress standard.
**Symptôme / Problème :** PROMPT 0 demande de construire ce mécanisme (dossier de données `inc/import/`, page d'outil admin à boutons, idempotence via meta) **avant** d'écrire le contenu spécifique des Prompts 1-5 — trois choix de conception à trancher : (1) où stocker les données transcrites (fichiers PHP retournant un tableau, JSON, CPT brouillon...) ; (2) comment déclencher l'import (page d'outil admin, script `wp eval-file` comme `bin/seed-*.php` des prompts précédents, WP-CLI) ; (3) quelle granularité pour l'idempotence (une vérification par import entier, ou par entrée individuelle).
**Cause / Alternatives :** Pour (1) : le prompt impose explicitement des fichiers PHP retournant un tableau ("pas de logique, juste les données") plutôt que du JSON ou un CPT brouillon — plus simple à committer/versionner avec le code, et lisible en revue de code sans outil supplémentaire. Pour (2) : (a) script `wp eval-file` comme `bin/seed-cpt-organisation.php`/`bin/seed-static-pages.php` (Prompts 13/16) — nécessite un accès shell/WP-CLI à l'hébergement, pas garanti ; (b) page d'outil admin cliquable, accessible à quiconque a les droits `manage_options`, sans dépendance à WP-CLI. Le prompt demande explicitement (b). Pour (3) : une vérification granulaire par entrée (chaque nomination, chaque événement du calendrier, chaque slide) plutôt que par lot entier, pour qu'une correction ponctuelle d'une seule ligne de données (ex. une date de calendrier corrigée) puisse être ré-importée sans dupliquer les entrées déjà correctes du même lot.
**Fix / Décision :**
- Dossier `inc/import/` : `data-hero.php`/`data-calendrier.php`/`data-nominations.php`, chacun une simple fonction `dz_import_get_*_data()` retournant un tableau (vide pour l'instant — PROMPT 0 construit uniquement le mécanisme, le contenu réel arrive aux Prompts 1-3) ; forme documentée en docblock pour que les Prompts 1-3 n'aient qu'à remplir le tableau sans redéfinir sa structure. `data-nominations.php` a une clé par CPT cible (`service_diocesain`/`commission_diocesaine`/`mouvement`/`association`) dès maintenant, pour que PROMPT 3 ("un fichier unique mais une fonction d'import par CPT") s'appuie dessus sans réorganisation.
- Page d'outil : `inc/import/import-tools.php`, `add_options_page()` → "Réglages > Import contenu diocèse", capability `manage_options` vérifiée à la fois par WordPress (paramètre de `add_options_page`) et explicitement dans le callback (défense en profondeur, cohérent avec `inc/acf-fields.php`). Un `<form>`/bouton par source, nonce dédié par source (`dz_import_{clé}`) plutôt qu'un nonce unique partagé, pour qu'un bouton ne puisse pas être rejoué avec le nonce d'un autre. Traitement en POST-redirect-GET (`admin_init`, `wp_safe_redirect` + transient de résultat) pour qu'un rafraîchissement de la page de résultat ne re-déclenche jamais l'import.
- Idempotence : `dz_import_find_existing_post( $post_type, $source_id )`/`dz_import_mark_imported( $post_id, $source_id )`, meta `_dz_import_source_id` (exactement le nom suggéré par le prompt) — une entrée par CPT+source_id, vérifiée avant tout `wp_insert_post()`. `post_status => any` dans la recherche, pour qu'un post déjà importé mais mis en brouillon/corbeille manuellement ne soit pas recréé. Le hero (pas un CPT, un repeater ACF sur la page d'accueil) suivra le même principe mais à l'intérieur du repeater lui-même (vérifier un `source_id` par slide avant d'ajouter une ligne) plutôt que via ce meta post — voir `inc/import/data-hero.php`.
- Fonctions `dz_import_run_hero()`/`dz_import_run_calendrier()`/`dz_import_run_nominations()` déjà créées et câblées aux boutons, mais volontairement minimales (rapportent juste "aucune donnée pour le moment" tant que les fichiers `data-*.php` sont vides) — la logique de création par entrée (sideload média + repeater pour le hero, `wp_insert_post` + ACF pour calendrier/nominations) est ajoutée aux Prompts 1-3, sans avoir à retoucher la page d'outil/le nonce/la redirection déjà en place.
**Leçon :** "Construire le mécanisme avant le contenu" (PROMPT 0) se traduit concrètement par : la plomberie (page, nonce, capability, redirection, idempotence) doit être complète et testable dès cette passe, même avec des données vides — les fonctions de création par entrée peuvent rester des points d'extension clairement délimités, remplis un par un dans les prompts suivants, sans jamais avoir besoin de revenir sur la plomberie elle-même.
**Statut :** ✅ Résolu — mécanisme en place ; contenu réel des 3 sources à ajouter aux Prompts 1-3 de `CONTENT_PROMPTS.md`

---

**Contexte :** ...
**Symptôme / Problème :** ...
**Cause / Alternatives :** ...
**Fix / Décision :** ...
**Leçon :** ...
**Statut :** ✅ Résolu | 🔵 Choix assumé
