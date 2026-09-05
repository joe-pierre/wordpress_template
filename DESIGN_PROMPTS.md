# DESIGN_PROMPTS.md — Prompts de conversion design, page par page

> Ce fichier complète `TASK_PROMPTS.md`. Il détaille, page par page et section par section, la conversion du design HTML du template "Story" en gabarits PHP WordPress. Copier-coller un prompt à la fois dans Claude Code, dans l'ordre proposé — chaque page dépend souvent du socle (header/footer/CPT) déjà en place.
>
> Rappel de méthode (voir `CONVENTIONS.md`) : Claude Code doit **s'inspirer de la structure HTML/CSS/JS du template source** (classes, animations AOS, config Swiper, grilles Bootstrap) mais **ne jamais coder en dur** ce qui doit être administrable (textes, images, listes de contenus) — tout doit passer par la Loop WordPress, un CPT ou un champ ACF, selon `SPEC.md` §3.

---

## PROMPT 0 — Vérification du socle avant de commencer le design

Avant de convertir une page, vérifie que `header.php`, `footer.php`, les CPT (`paroisse`, `pretre`, `evenement`, `sacrement`) et leurs groupes ACF sont bien en place (voir `TODO.md` Phases 1 à 3). Si un CPT ou un champ nécessaire à une page ci-dessous n'existe pas encore, crée-le en respectant les conventions de nommage de `CONVENTIONS.md` avant de continuer. Ne duplique jamais un champ déjà existant — vérifie dans `acf-json/` d'abord.

---

## PROMPT 1 — Header et footer (rappel/affinage)

Relis `header.php` et `footer.php` déjà générés. Vérifie que : le menu principal utilise bien le Walker custom limité à 2 niveaux (voir `DECISIONS.md`), les réseaux sociaux et coordonnées viennent de la page d'options ACF "Réglages du thème" (pas de valeurs codées en dur), le logo affiche soit une image (si uploadée dans les réglages) soit le nom du site en fallback texte comme dans le template d'origine. Corrige tout texte encore statique repéré (ex. liens "Company/Services/Support" du footer d'origine, à remplacer par le vrai plan du site du diocèse ou un menu footer WordPress).

## PROMPT 2 — Page d'accueil (`front-page.php`)

*(Déjà lancé — voir échange précédent. Reprendre ce prompt seulement si le travail n'est pas terminé ou doit être repris.)*

Termine la conversion de `index.html` en `front-page.php` en t'appuyant sur `SPEC.md` §3-4 :
1. Hero slider : groupe ACF `group_dz_front_hero`, repeater `slides` (max 5 : titre, texte, image, lien optionnel), en conservant le pattern `<script type="application/json" class="swiper-config">` du template source.
2. Section "Featured Posts" : `WP_Query` sur les `post` avec le champ ACF "à la une" coché (`group_dz_post_a_la_une`), via `template-parts/card-article.php`.
3. Section "accès rapides" (à créer, absente du template d'origine) : 4 blocs cliquables vers Paroisses / Prêtres / Sacrements / Dons, en réutilisant le style des cartes du template (`.blog-card` ou équivalent) plutôt qu'un nouveau design.
4. Gère proprement l'état "aucun contenu" tant que rien n'est saisi (pas d'erreur PHP, section masquée ou message discret).

## PROMPT 3 — À propos du diocèse (`page-about.php`)

Convertis `about.html` en `page-about.php`. Rends dynamiques : le portrait (image mise en avant de la page ou champ ACF image dédié), les deux badges chiffrés ("12 ans d'expérience", "345+ projets" dans le template — à remplacer par des champs ACF génériques du type `chiffre_cle_1_valeur`/`chiffre_cle_1_label` répétés, pilotant PureCounter), le texte principal (contenu WordPress natif de la page, `the_content()`). Adapte les libellés au contexte diocésain (ex. "Année de fondation du diocèse", "Nombre de paroisses") sans placeholder Lorem Ipsum restant dans le code du gabarit lui-même — seules les valeurs par défaut dans l'admin peuvent être vides.

## PROMPT 4 — Actualités : liste, détail, auteur, recherche

Convertis les 4 pages liées aux actualités en réutilisant le type natif `post` (voir `DECISIONS.md`) :
- `single.php` (à partir de `blog-details.html`) : hero image pleine largeur = image mise en avant de l'article, `the_title()`, `the_content()`, méta auteur/date natifs WordPress.
- `archive.php`/`category.php` (à partir de `category.html`) : corrige au passage le pattern déjà signalé pour `the_archive_title()` (voir `BUGS_AND_ROADMAP.md`) si ce n'est pas déjà fait ailleurs. Boucle sur `have_posts()`, réutilise `template-parts/card-article.php`, conserve la pagination du template adaptée à `paginate_links()`.
- `author.php` (à partir de `author-profile.html`) : bio et avatar depuis le profil utilisateur WordPress (`get_the_author_meta`), liste de ses articles en dessous.
- `search.php` (à partir de `search-results.html`) : boucle sur les résultats de recherche natifs, réutilise aussi `card-article.php`. Adapte le texte "We found X results" en français avec le vrai compte (`$wp_query->found_posts`).

## PROMPT 5 — Paroisses (`single-paroisse.php`, `archive-paroisse.php`)

Crée ces deux gabarits (pas d'équivalent HTML direct dans le template — inspire-toi de la structure en grille de `category.html` pour l'archive, et de `about.html`/`blog-details.html` pour la fiche individuelle). Sur la fiche paroisse : nom, adresse, horaires de messes (repeater ACF), photo, description, et le curé responsable affiché via la relation ACF vers `pretre` (avec vérification d'existence avant affichage, voir `CONVENTIONS.md` §Validation). Sur l'archive : grille de cartes (`card-paroisse.php`) avec photo, nom, ville/secteur, lien vers la fiche.

## PROMPT 6 — Prêtres (`single-pretre.php`, `archive-pretre.php`)

Même logique que les paroisses. Fiche prêtre : photo, nom, fonction, biographie, paroisse d'affectation (relation inverse vers `paroisse`), contact. Archive : grille de cartes (`card-pretre.php`), éventuellement filtrable par fonction si le volume de prêtres le justifie (sinon liste simple triée par nom).

## PROMPT 7 — Événements (`single-evenement.php`, `archive-evenement.php`)

Fiche événement : titre, date/heure début-fin, lieu, description, image, relation optionnelle vers `paroisse`. Archive : n'affiche que les événements dont la date de fin est postérieure à aujourd'hui (règle métier `SPEC.md` §4), triés par date croissante, réutilise le style de grille du template (`card-evenement.php`). Ajoute un message clair si aucun événement à venir n'est prévu, plutôt qu'une page vide.

## PROMPT 8 — Sacrements (`single-sacrement.php`)

Fiche sacrement : titre, description (WYSIWYG), conditions/démarches (repeater d'étapes ACF ou simple contenu riche), documents à télécharger (champ fichier répété, liens de téléchargement). Pas d'archive nécessaire si le nombre de sacrements reste faible (~7) — une simple liste peut être intégrée directement sur une page "Sacrements" (`page.php` générique avec liste manuelle des `single-sacrement`, ou boucle `WP_Query` triée manuellement par `menu_order`).

## PROMPT 9 — Contact (`page-contact.php`)

Convertis `contact.html`. Remplace le formulaire natif du template par un shortcode Contact Form 7 (voir `DECISIONS.md`), en adaptant le CSS du plugin pour coller au style `form-floating` d'origine (labels flottants, boutons arrondis). Conserve la carte (intégration Google Maps ou OpenStreetMap selon ce qui est disponible) et le panneau réseaux sociaux, alimentés par la page d'options ACF déjà en place (Prompt 1).

## PROMPT 10 — Dons (`page-dons.php`)

Page informative en v1 (voir `DECISIONS.md` — pas de CPT, pas de paiement en ligne pour l'instant). Champs ACF simples pour les modalités (texte riche, RIB/coordonnées, éventuellement une image ou un logo de moyen de paiement). Prévois la structure HTML de façon à pouvoir insérer un shortcode de paiement plus tard sans refonte (voir `SPEC.md` §8 et `BUGS_AND_ROADMAP.md`).

## PROMPT 11 — Page générique et 404

`page.php` (à partir de `starter-page.html`) : gabarit simple pour toute page sans template spécifique — titre, fil d'ariane, contenu WordPress natif. `404.php` : reprends le design de `404.html` du template, adapte le texte et ajoute un lien de retour à l'accueil et un champ de recherche.

## PROMPT 12bis — Identité visuelle : couleurs et logo officiels

Applique la palette réelle du diocèse (voir `SPEC.md` §10, extraite des armoiries) dans les variables CSS de `main.css` : bleu/azur en couleur principale, or/jaune en accent, vert en usage ponctuel. Intègre `logo-diocese-ziguinchor.png` dans la page d'options ACF "Réglages du thème" (déjà créée au Prompt 1) comme logo par défaut, et génère un favicon à partir de ce même visuel. Ne touche pas à la structure Bootstrap du template, uniquement aux valeurs de couleurs/logo.

## PROMPT 13 — CPT organisationnels : Conseils, Services, Commissions

Enregistre les CPT `conseil`, `service_diocesain`, `commission_diocesaine` (voir `SPEC.md` §3 et `DECISIONS.md`) avec leur socle de champs ACF commun (description, responsable, repeater `membres` : nom + rôle) et les champs spécifiques (`sous_structures` pour `service_diocesain`). Crée pour chacun un couple `single-{cpt}.php`/`archive-{cpt}.php`, en t'inspirant du style de fiche déjà utilisé pour les paroisses/prêtres (photo optionnelle, bloc "Composition" listant le repeater membres). Saisis en contenu de test au moins une entrée par CPT à partir de `NOMINATIONS_SERVICES_COMMISSIONS_AUMONERIES_2027.pdf` (ex. le Conseil épiscopal, l'Économat diocésain).

## PROMPT 14 — CPT organisationnels : Mouvements, Associations, Aumôneries

Enregistre les CPT `mouvement`, `association`, `aumonerie` (ce dernier avec la taxonomie `type_aumonerie` : scolaire/universitaire/santé/carcérale). Même socle de champs que le Prompt 13, plus le champ `aumonier` (relation vers `pretre`) sur `mouvement`. Archive de `aumonerie` filtrable par `type_aumonerie`. Réutilise le style de carte déjà en place pour rester cohérent visuellement avec les autres rubriques.

## PROMPT 15 — Enseignements diocésains et archives épiscopales

Enregistre le CPT `etablissement` (DIDEC, Séminaires et Maisons de formation, Collèges Diocésains, Enseignement Supérieur — champ `type_etablissement` en select, `contact`) et le CPT `ancien_eveque` (photo, période, biographie) pour la sous-rubrique "Archives > Les différents évêques". Pour `ancien_eveque`, l'archive doit s'afficher triée par date de début de mandat (chronologique), pas par date de publication WordPress.

## PROMPT 16 — Taxonomies transverses et pages statiques restantes

1. Crée la taxonomie `doyenne` sur le CPT `paroisse` et vérifie qu'elle apparaît dans l'admin lors de l'édition d'une paroisse.
2. Crée la taxonomie `evenement_type` (Diocésain / Évêque) sur `evenement`, et deux archives distinctes ou un filtre sur `archive-evenement.php` pour séparer "Agenda Diocésain" et "Agenda de l'évêque".
3. Crée les catégories manquantes sur les Actualités natives : Cathéchèses, Communiqués, Nécrologie, Vatican, Diocèse.
4. Crée les pages statiques simples listées dans `SPEC.md` §3 (Mot de l'évêque, Contacts, Évêché, Chancellerie, Historique, L'évêque, Cartographie du diocèse, Vie Consacrée, Prières, Pèlerinages Nationaux, Pèlerinages Diocésains, Devenir bénévole, Secrétariat diocésain) avec `page.php`. Pour "Devenir bénévole", intègre un formulaire Contact Form 7 dédié. Pour "Cartographie du diocèse", prévois un simple embed de carte (Google Maps/OpenStreetMap) en attendant des données géographiques plus précises.

## PROMPT 17 — Mega-menu hybride

Remplace le `Walker_Nav_Menu` custom actuel (limité à 2 niveaux, voir `header.php`) par une implémentation **mega-menu hybride**, fidèle à `Arborescence_PDF.pdf` et à la décision documentée dans `DECISIONS.md` :

1. Pour les rubriques riches (**À propos du diocèse, Services et Commissions, Apostolat des Laïques, Vie de Foi**) : au clic/tap sur l'item de menu principal, ouvre un panneau large positionné sous le header, organisé en colonnes Bootstrap (`.row`/`.col-md-3` ou équivalent), listant tous les enfants et petits-enfants de la rubrique à plat (ex. la colonne "Archives" affiche directement "Histoire du diocèse" et "Les différents évêques" en dessous, sans dropdown imbriqué supplémentaire).
2. Pour les rubriques légères (**Les Conseils de l'évêque, Personnel apostolique, Soutenir le diocèse**) : garde un dropdown Bootstrap standard à 1 niveau, comme c'est déjà le cas actuellement.
3. Sur mobile, le mega-menu doit se replier en accordéon simple (les colonnes deviennent des sections empilées), pas en dropdowns imbriqués.
4. Conserve l'accessibilité clavier (navigation au Tab, fermeture à l'Échap) et les animations AOS légères déjà présentes sur le header, sans ajouter de dépendance JS supplémentaire au-delà de ce qui existe déjà (Bootstrap bundle suffit pour ce composant).
5. Teste particulièrement le rendu mobile avant de considérer la tâche terminée — c'est le point le plus sensible aux régressions sur ce type de composant.

## PROMPT 12 — Revue design globale et responsive

Une fois toutes les pages ci-dessus converties, relis l'ensemble des gabarits et vérifie : cohérence des titres de section et fils d'ariane sur toutes les pages, absence de tout texte Lorem Ipsum ou placeholder du template d'origine dans le code des gabarits eux-mêmes, bon fonctionnement des animations AOS et du menu mobile sur chaque nouveau gabarit créé, affichage correct en mobile/tablette/desktop (breakpoints Bootstrap). Documente toute incohérence trouvée dans `BUGS_AND_ROADMAP.md` avant correction.
