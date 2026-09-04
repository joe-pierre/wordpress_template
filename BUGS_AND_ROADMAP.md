# BUGS CORRIGÉS

- **[2026-09-04]** `page-about.php`, `page-contact.php` et `page-dons.php` n'avaient pas d'en-tête `Template Name`, rendant invisibles dans l'admin les champs ACF des Tâches 8 et cassant silencieusement le lien "Nous contacter" du footer (voir `DECISIONS.md`).
- **[2026-09-04]** Liens `tel:`/`mailto:` échappés avec `esc_attr()` au lieu de `esc_url()` sur 4 gabarits (8 occurrences) ; 4 liens de partage social de `single.php` renforcés de la même façon (voir `DECISIONS.md`).
- **[2026-09-04]** `template-parts/hero-slider.php` créé en Tâche 1 n'était jamais appelé : le hero de l'accueil était resté codé en dur dans `front-page.php` depuis la Tâche 4. Balisage extrait dans le template-part, appelé via `get_template_part()` (voir `DECISIONS.md`).
- **[2026-09-04]** `menu_position` du CPT `paroisse` (20) entrait en collision avec le menu natif "Pages" de WordPress. Les 4 CPT métier décalés vers des créneaux libres (21–24, voir `DECISIONS.md`).
- **[2026-09-04]** Le formulaire de commentaires (`comments.php`) ne préremplissait pas nom/e-mail/site en cas de réaffichage après une erreur de validation (à la différence du comportement par défaut de `comment_form()`). Ajout de `wp_get_current_commenter()` pour préremplir ces trois champs, avec échappement `esc_attr()`.

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
