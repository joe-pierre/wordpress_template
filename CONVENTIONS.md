# CONVENTIONS.md — Règles de codage

## Langue

- **Contenu éditorial** (textes affichés aux visiteurs, labels admin, messages d'erreur utilisateur) : **français**.
- **Code** (noms de fonctions, variables, commentaires techniques) : **anglais**, à l'exception des commentaires qui expliquent une règle métier propre au diocèse (ceux-là peuvent rester en français pour rester compréhensibles par un rédacteur ou un futur mainteneur local).
- Tous les textes affichés doivent passer par des fonctions traduisibles (`__()`, `_e()`, `esc_html__()`) même si le multilingue n'est pas activé en v1, pour ne pas fermer la porte à WPML/Polylang plus tard (voir SPEC.md §8).

## Nommage

- **Text domain** du thème : `diocese-ziguinchor`
- **Préfixe de toutes les fonctions/hooks custom** : `dz_` (ex. `dz_register_cpt_paroisse()`, `dz_enqueue_assets()`) — évite les collisions avec les plugins
- **Slugs des CPT** : `paroisse`, `pretre`, `evenement`, `sacrement` (singulier, minuscule, sans accent)
- **Slugs des taxonomies** : `secteur_pastoral` (si utilisée)
- **Fichiers de template-parts** : `card-{type}.php`, `loop-{type}.php` (ex. `card-paroisse.php`, `loop-evenements.php`)
- **Classes CSS** : on conserve la nomenclature du template d'origine (`kebab-case`, ex. `.blog-hero`, `.category-postst`) pour toute section réutilisée telle quelle ; toute nouvelle section métier custom (paroisses, prêtres...) suit le même style pour rester cohérente (`.paroisse-card`, `.pretre-profile`)
- **Champs ACF** : `snake_case`, préfixés par le nom du CPT quand ambigu (ex. `paroisse_horaires_messes`, `pretre_date_ordination`)

## Réponses API

Pas d'API custom en v1 (voir SPEC.md §7). Si des endpoints REST custom sont ajoutés :
- Toujours retourner du JSON structuré avec un statut HTTP cohérent (`200`, `400`, `404`, `500`)
- Format d'erreur standard WordPress : `WP_Error` avec code, message, et `status` dans `data`
- Ne jamais exposer de données sensibles (emails de contact interne, IDs internes non nécessaires) dans les réponses publiques

## Validation

- Toute donnée entrante (formulaires, requêtes) doit être **sanitizée à l'entrée** (`sanitize_text_field`, `sanitize_email`, `absint`, `wp_kses_post` pour le HTML autorisé) et **échappée à la sortie** (`esc_html`, `esc_attr`, `esc_url`)
- Ne jamais faire confiance à une validation JS seule (`validate.js` du template = confort UX uniquement)
- Les champs ACF de type relation/repeater doivent être vérifiés avant affichage (`have_rows()`, `if ($field) : ... endif;`) pour éviter les erreurs PHP si un contenu est incomplet

## Broadcasting

**Non applicable** — pas de WebSocket ni de diffusion temps réel dans ce projet (voir SPEC.md §6).

## Tests

- Pas de suite de tests automatisés prévue en v1 (site vitrine à faible logique métier). À la place :
  - **Checklist de QA manuelle** avant chaque mise en production (voir modèle dans `BUGS_AND_ROADMAP.md`)
  - Vérification systématique en mode `WP_DEBUG = true` en environnement de dev pour repérer notices/warnings PHP avant livraison
  - Test responsive (mobile/tablette/desktop) sur chaque gabarit modifié, car le template d'origine est fortement dépendant de breakpoints Bootstrap
- Si le projet grossit (formulaires métier complexes, paiement en ligne), réévaluer l'ajout de tests PHPUnit ciblés sur les fonctions critiques (`inc/`)

## Sécurité

- Vérification de `ABSPATH` en tête de chaque fichier PHP du thème (`if ( ! defined( 'ABSPATH' ) ) exit;`)
- `current_user_can()` avant toute action d'écriture déclenchée depuis l'admin ou un formulaire custom
- Nonces sur tout formulaire non géré par un plugin (Contact Form 7 gère déjà ses propres nonces)
- Ne jamais afficher de message d'erreur PHP détaillé en production (`WP_DEBUG_DISPLAY = false` en prod)
- Mise à jour régulière des plugins (ACF, Contact Form 7) — à documenter dans une procédure de maintenance (hors périmètre code)

## Partials / Frontend

- Toute section HTML répétée (carte article, carte paroisse, carte prêtre, item d'événement) devient un **template-part** dédié dans `template-parts/`, appelé via `get_template_part()` avec des arguments (`$args`) plutôt que dupliquée dans plusieurs fichiers
- Les scripts vendor (Bootstrap, AOS, Swiper, PureCounter) sont enregistrés une seule fois dans `functions.php` via `wp_enqueue_script`/`wp_enqueue_style`, jamais rechargés en inline dans les templates
- La configuration JSON inline des sliders Swiper (`<script type="application/json" class="swiper-config">`) est **conservée telle quelle** dans les template-parts concernés — c'est le pattern natif du template et il fonctionne sans modification sous WordPress
- Pas de mélange logique métier / affichage dans les template-parts : la récupération des données (requêtes, ACF) reste dans le template appelant ou dans `inc/`, le template-part se contente d'afficher ce qu'on lui passe
