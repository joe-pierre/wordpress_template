# BUGS CORRIGÉS

- **[2026-09-04]** `page-about.php`, `page-contact.php` et `page-dons.php` n'avaient pas d'en-tête `Template Name`, rendant invisibles dans l'admin les champs ACF des Tâches 8 et cassant silencieusement le lien "Nous contacter" du footer (voir `DECISIONS.md`).
- **[2026-09-04]** Liens `tel:`/`mailto:` échappés avec `esc_attr()` au lieu de `esc_url()` sur 4 gabarits (8 occurrences) ; 4 liens de partage social de `single.php` renforcés de la même façon (voir `DECISIONS.md`).
- **[2026-09-04]** `template-parts/hero-slider.php` créé en Tâche 1 n'était jamais appelé : le hero de l'accueil était resté codé en dur dans `front-page.php` depuis la Tâche 4. Balisage extrait dans le template-part, appelé via `get_template_part()` (voir `DECISIONS.md`).
- **[2026-09-04]** `menu_position` du CPT `paroisse` (20) entrait en collision avec le menu natif "Pages" de WordPress. Les 4 CPT métier décalés vers des créneaux libres (21–24, voir `DECISIONS.md`).
- **[2026-09-04]** Le formulaire de commentaires (`comments.php`) ne préremplissait pas nom/e-mail/site en cas de réaffichage après une erreur de validation (à la différence du comportement par défaut de `comment_form()`). Ajout de `wp_get_current_commenter()` pour préremplir ces trois champs, avec échappement `esc_attr()`.
- **[2026-09-05]** Sur `archive.php` (pages de catégorie/étiquette), le titre affiché par `template-parts/page-title.php` montrait les balises HTML brutes de `get_the_archive_title()` (ex. `Catégorie : <span>Homélies</span>`) au lieu d'un rendu stylé, car le titre passait dans `esc_html()`. Remplacé par `wp_kses_post()` sur `$dz_title` dans `template-parts/page-title.php` (h1 uniquement) : le balisage sûr renvoyé par `get_the_archive_title()`/`get_the_archive_description()` s'affiche correctement tout en restant assaini contre le HTML dangereux. Le fil d'ariane (`$dz_breadcrumb`) continue de passer par `wp_strip_all_tags()` en amont dans `archive.php`, donc reste en texte brut sans changement.

---

# ROADMAP (idées / améliorations futures)

Idées identifiées pendant le cadrage, volontairement hors périmètre de la v1 (voir `SPEC.md` §8 Extensibilité et `DECISIONS.md` pour le raisonnement) :

- **Dons en ligne** : intégration d'un prestataire de paiement (Orange Money, Wave, ou Stripe/PayPal selon la cible des donateurs — diaspora vs. local)
- **Multilingue** : ajout du wolof, du diola et/ou du portugais via WPML ou Polylang, selon les besoins pastoraux transfrontaliers (proximité Guinée-Bissau)
- **Calendrier liturgique / événements récurrents** : évaluer un plugin dédié (ex. The Events Calendar) si le CPT `evenement` simple devient limitant
- **Newsletter** : intégration Mailchimp/Brevo pour les actualités diocésaines
- **Espace intranet clergé** : zone réservée aux prêtres/secrétariat (hors périmètre site public)
- **Application mobile ou widget externe** : nécessiterait d'exposer les CPT via la WP REST API (voir `SPEC.md` §7 — l'architecture actuelle ne ferme pas cette porte)
- **Recherche géographique de paroisses** : carte interactive avec géolocalisation, si le nombre de paroisses le justifie
- **Cartographie du diocèse enrichie** : `page-cartographie.php` (PROMPT 16) n'affiche pour l'instant qu'un simple embed Google Maps de l'adresse générale du diocèse, en attendant des données géographiques plus précises (SPEC.md §3) — à terme, une carte affichant un marqueur par paroisse (en s'appuyant sur `paroisse_localisation`, déjà saisi par paroisse, voir `acf-json/group_dz_cpt_paroisse.json`)
