# SPEC.md — Spécifications fonctionnelles et techniques

## 1. Vue d'ensemble

Conversion du template Bootstrap statique **"Story"** (BootstrapMade, Bootstrap v5.3.7) en un **thème WordPress classique (PHP, non FSE)** pour le site institutionnel du **Diocèse de Ziguinchor**.

Le template source est un thème de blog/magazine générique (contenu Lorem Ipsum, visuels de démo). Le travail consiste à :
- reproduire fidèlement le design (header, footer, sliders, cartes, animations) en gabarits PHP WordPress ;
- remplacer tout le contenu statique par du contenu dynamique administrable (articles, pages, médias) ;
- créer les structures de contenu métier propres à un diocèse (paroisses, prêtres, événements, sacrements, dons) qui n'existent pas dans le template d'origine.

Le site doit être utilisable par des **rédacteurs non techniques** (secrétariat diocésain, communication) sans toucher au code.

## 2. Stack technique

- **CMS** : WordPress (dernière version stable, PHP 8.1+ recommandé, MySQL/MariaDB)
- **Type de thème** : thème classique PHP (`header.php`, `footer.php`, `functions.php`, templates par type de contenu) — pas de Full Site Editing / thème bloc dans une première version
- **Champs personnalisés** : Advanced Custom Fields (ACF) — version Pro si des champs répéteurs/relationnels/pages d'options sont nécessaires (slides du hero, relations Paroisse↔Prêtre), sinon ACF gratuit + limitation des fonctionnalités
- **Formulaires** : Contact Form 7 (remplace le script PHP natif `php-email-form` du template) — voir DECISIONS.md
- **Assets front-end conservés tels quels** (aucun build tool, pas de webpack/vite — les vendors sont chargés en l'état) :
  - Bootstrap 5.3.7 (CSS + JS bundle)
  - Bootstrap Icons
  - AOS (Animate On Scroll)
  - Swiper (hero slider, featured posts slider)
  - PureCounter (compteurs animés)
- **Pas de framework JS front (React/Vue)** : JS vanilla uniquement (`main.js` du template + JS spécifique WordPress si besoin)
- **API** : pas d'API custom nécessaire dans une v1 (site vitrine + back-office WordPress). Si une app mobile ou un widget externe est envisagé plus tard, utiliser la **WP REST API** native, étendue par des endpoints custom sur les CPT (voir section 7).
- **Pas de WebSocket** : le site est un site vitrine, aucun besoin de temps réel (voir section 6).
- **Environnement** : à préciser (hébergement mutualisé WordPress classique / VPS ? — TODO à trancher en Phase 1)

## 3. Modèle de données

### Contenus natifs WordPress réutilisés
| Type natif | Usage |
|---|---|
| `post` (Articles) | **Actualités** du diocèse. Catégories = rubriques (ex. Vie diocésaine, Nominations, Communiqués). Pas de CPT dédié : plus simple pour les rédacteurs, bénéficie nativement de la recherche, des flux RSS, des archives. |
| `page` | Pages institutionnelles (Accueil, À propos/Historique du diocèse, Contact, Sacrements — si traité en page plutôt qu'en CPT, Dons) |
| Utilisateur WordPress (`author.php`) | Peut servir de base pour les profils si les prêtres publient eux-mêmes des actualités (optionnel, à trancher) |

### Custom Post Types à créer
| CPT (slug) | Champs ACF principaux | Relations |
|---|---|---|
| `paroisse` | nom, adresse, ville/secteur, horaires des messes (repeater : jour + heure), téléphone, email, photo, description, coordonnées GPS (pour carte) | relation vers `pretre` (curé responsable, éventuellement vicaires en repeater) |
| `pretre` | nom complet, photo, fonction (curé / vicaire / diacre...), date d'ordination, biographie, paroisse d'affectation, contact | relation vers `paroisse` |
| `evenement` | titre, date/heure début, date/heure fin, lieu (texte libre ou relation `paroisse`), description, image, lien d'inscription (optionnel) | relation optionnelle vers `paroisse` |
| `sacrement` | titre (Baptême, Confirmation, Mariage, etc.), description, conditions/démarches (WYSIWYG ou repeater d'étapes), documents à télécharger (champ fichier répété), contact/paroisse référente | — |

### Taxonomies
- `category` (native) pour les Actualités
- Taxonomie custom `secteur_pastoral` (optionnel) pour regrouper les paroisses par doyenné/secteur, si le diocèse est structuré ainsi — **à confirmer avec le client avant implémentation**

### Page "Dons"
Pas de CPT : une page statique (`page-dons.php`) avec champs ACF pour les modalités (RIB, Mobile Money, etc.) et éventuellement un formulaire Contact Form 7 dédié. Intégration d'un prestataire de paiement en ligne **hors périmètre v1** (à inscrire en ROADMAP).

### Custom Post Types organisationnels (ajoutés suite à `Arborescence_PDF.pdf` et `NOMINATIONS_..._2027.pdf` — voir `DECISIONS.md` "Un Custom Post Type séparé par grande rubrique organisationnelle")

Un CPT par grande rubrique de l'arborescence, tous avec le même socle de champs ACF : `description` (WYSIWYG), `responsable` (texte libre ou relation vers `pretre`), repeater `membres` (nom, rôle/fonction).

| CPT (slug) | Rubrique de l'arborescence | Champs spécifiques en plus du socle |
|---|---|---|
| `conseil` | Les Conseils de l'évêque (Conseil épiscopal, presbytéral, Collège des consulteurs, Affaires économiques, Pastoral Diocésain) | — |
| `service_diocesain` | Services diocésains (Économat, Caritas, ODEC, Apostolat des Laïcs, Coopération Missionnaire, Exorcisme, Cérémoniaires, Formation et Recherche, Communication, Pèlerinages) | repeater `sous_structures` (nom, responsable) pour les entités rattachées type Hôtel Carabane, Librairie Djibékel, Imprimerie du Sud |
| `commission_diocesaine` | Commissions diocésaines (Catéchèse, Cellule d'écoute, Écologie intégrale, Dialogue œcuménique, Justice et Paix, Pastorale de la Famille, Liturgie, Pastorale de la Santé, Pastorale des Vocations, Textes liturgiques en langues locales) | — |
| `mouvement` | Mouvements d'Action Catholique (Coordination des Jeunes, CV/AV, JAC/UJRCS/MARCS, JOC, JEC, Scouts et Guides) | champ `aumonier` (relation `pretre`) |
| `association` | Associations et Groupes d'Apostolat (UDAFC/Z, Légion de Marie, Coordination des Chorales, Renouveau Charismatique, Vie Montante, Équipes Enseignantes, Forces de Défense et Sécurité) | — |
| `aumonerie` | Aumôneries (scolaires, universitaires, santé, maisons d'arrêt) | taxonomie `type_aumonerie` (scolaire / universitaire / santé / carcérale) |
| `etablissement` | Enseignements diocésains (DIDEC, Séminaires et Maisons de formation, Collèges Diocésains, Enseignement Supérieur) | champ `type_etablissement` (select), `contact` |
| `ancien_eveque` | À propos du diocèse > Archives > Les différents évêques | photo, période (dates début/fin), biographie |

### Taxonomies additionnelles

- `doyenne` sur le CPT `paroisse` (remplace la piste `secteur_pastoral` ci-dessus, désormais confirmée par l'arborescence : rubrique "Doyennés et Paroisses")
- `evenement_type` sur le CPT `evenement` (valeurs : Diocésain / Évêque) pour distinguer "Agenda Diocésain" et "Agenda de l'évêque" sans dupliquer le CPT
- Catégories natives supplémentaires sur les Actualités (`post`) : Homélies (déjà en place), Cathéchèses, Communiqués, Nécrologie, Vatican, Diocèse

### Pages statiques simples (gabarit `page.php`, pas de CPT dédié)

Mot de l'évêque, Contacts, Évêché, Chancellerie, Historique, L'évêque, Cartographie du diocèse (carte embarquée), Vie Consacrée, Prières, Pèlerinages Nationaux, Pèlerinages Diocésains, Devenir bénévole (avec formulaire Contact Form 7 dédié), Secrétariat diocésain. *(Economat n'est pas dupliqué ici : déjà couvert par une entrée du CPT `service_diocesain`.)*

## 4. Règles métier critiques

- Un **prêtre** peut être rattaché à **une seule paroisse principale** (relation simple) ; gérer les cas de prêtres sans affectation (ex. retraités, en formation) via un statut ACF plutôt qu'une relation vide.
- Les **événements passés** ne doivent plus apparaître dans les listings "à venir" (filtrage par date dans la requête, pas de suppression).
- Le **hero slider de la page d'accueil** est limité à **5 slides maximum** administrables (repeater ACF avec `max: 5`) pour préserver les performances et éviter les abus éditoriaux.
- Les **actualités "à la une"** (slider `featured-posts`) sont sélectionnées manuellement par les rédacteurs (champ ACF "Mettre à la une" sur l'article), pas automatiquement par date, pour laisser le contrôle éditorial.
- Le **menu de navigation** suit désormais un **mega-menu hybride** : panneau large en colonnes pour les rubriques riches (À propos du diocèse, Services et Commissions, Apostolat des Laïques, Vie de Foi), dropdown simple à 1 niveau pour les rubriques légères (Les Conseils de l'évêque, Personnel apostolique, Soutenir le diocèse) — remplace la règle "2 niveaux de profondeur" ; voir `DECISIONS.md` "Mega-menu hybride".
- Le formulaire de contact envoie un e-mail à une adresse configurable dans les réglages (pas codée en dur), et affiche un message de confirmation sans rechargement de page (AJAX, géré par Contact Form 7).

## 5. Architecture code

```
wp-content/themes/diocese-ziguinchor/
├── style.css                 # en-tête du thème (obligatoire WP) — pas le CSS principal
├── functions.php             # enqueue assets, CPT, ACF, menus, support thème
├── header.php
├── footer.php
├── front-page.php            # accueil (ex index.html)
├── page.php                  # gabarit générique de page (ex starter-page.html)
├── page-about.php            # ex about.html
├── page-contact.php          # ex contact.html
├── page-dons.php
├── single.php                # actualité individuelle (ex blog-details.html)
├── archive.php                # liste d'actualités par catégorie (ex category.html)
├── author.php                # profil (ex author-profile.html)
├── search.php                 # résultats de recherche (ex search-results.html)
├── 404.php
├── single-paroisse.php
├── single-pretre.php
├── single-evenement.php
├── single-sacrement.php
├── archive-paroisse.php / archive-evenement.php  # listings
├── template-parts/
│   ├── card-article.php
│   ├── card-paroisse.php
│   ├── card-pretre.php
│   ├── card-evenement.php
│   └── hero-slider.php
├── inc/
│   ├── cpt-paroisse.php
│   ├── cpt-pretre.php
│   ├── cpt-evenement.php
│   ├── cpt-sacrement.php
│   ├── acf-fields.php        # si définition en PHP plutôt que JSON export
│   └── theme-setup.php
└── assets/
    ├── vendor/                # Bootstrap, AOS, Swiper, PureCounter, Bootstrap Icons — copiés tels quels
    ├── css/main.css           # conservé, adapté progressivement
    └── js/main.js             # conservé, adapté si besoin (sélecteurs dynamiques)
```

## 6. Événements WebSocket

**Non applicable.** Le site est un site vitrine institutionnel sans besoin de mise à jour en temps réel (pas de chat, pas de notifications live). Si un besoin de temps réel apparaît plus tard (ex. suivi live d'un événement diocésain majeur), le réévaluer en ROADMAP plutôt que dans le socle initial.

## 7. Endpoints API

Pas d'API custom nécessaire en v1. WordPress expose nativement la **WP REST API** (`/wp-json/wp/v2/...`) pour les types natifs. Si besoin d'exposer les CPT métier (ex. pour une future app mobile diocésaine) :
- Ajouter `'show_in_rest' => true` lors de l'enregistrement de chaque CPT
- Exposer les champs ACF via `acf/init` + `register_rest_field()` ou le plugin "ACF to REST API"

Aucun endpoint custom prévu pour la v1.

## 8. Extensibilité

Pistes à garder en tête pour ne pas fermer de portes dans l'architecture :
- **Multilingue** (français prioritaire ; possibilité future wolof/diola/portugais selon besoins pastoraux transfrontaliers) → prévoir compatibilité avec WPML ou Polylang dès la structure des CPT (labels traduisibles, pas de texte en dur dans les templates)
- **Dons en ligne** : intégration future d'un prestataire de paiement (Mobile Money Sénégal type Orange Money/Wave, ou Stripe/PayPal) — la page Dons doit être conçue pour accueillir un shortcode/plugin sans refonte complète
- **Calendrier liturgique / événements récurrents** : si le besoin se confirme, envisager un plugin d'événements dédié (ex. The Events Calendar) plutôt que réinventer la récurrence dans le CPT `evenement`
- **Newsletter** : intégration future (Mailchimp/Brevo) sur la page d'accueil ou le footer

## 9. Sécurité et validations

- Toutes les sorties de champs ACF/WP passent par `esc_html()`, `esc_url()`, `esc_attr()` selon le contexte
- Toutes les entrées utilisateur (formulaires) sont validées côté serveur, pas seulement côté JS (`validate.js` du template n'est qu'un confort UX, pas une sécurité)
- Formulaire de contact : protection anti-spam (honeypot Contact Form 7 + éventuellement reCAPTCHA v3)
- Vérification des capacités (`current_user_can()`) pour toute action d'administration custom
- Nonces WordPress (`wp_nonce_field()` / `check_admin_referer()`) pour tout formulaire admin custom
- Pas de clés/API secrets en dur dans le thème : utiliser des constantes dans `wp-config.php` ou les options WordPress
- Sanitization systématique avant écriture en base (`sanitize_text_field`, `sanitize_email`, etc.)

## 10. Identité visuelle

**Reçue — voir `logo-diocese-ziguinchor.png` et `Copie_de_Armoiries_Diocèse_de_Ziguinchor.pdf`.**

- **Logo** : les armoiries officielles du diocèse (écu ogival, croix dorée à double traverse, tiare épiscopale, colombe, épis de riz, pirogue), entourées de l'inscription "SIGILLUM DIOECESIS ZIGUINCHORENSIS" et de l'année MCMLV (1955, érection canonique).
- **Palette de couleurs** extraite des armoiries, à reporter dans les variables CSS du thème (`--accent-color`, `--heading-color`, etc. dans `assets/css/main.css`) :
  - **Bleu/azur** (fond de l'écu — paix, fidélité, symbole marial) : couleur principale
  - **Or/jaune** (croix, épis de riz — vie, bénédiction, autorité spirituelle) : couleur secondaire/accent
  - **Vert** (croix fleuries du pourtour — évangélisation, espérance) : couleur tertiaire, à usage ponctuel (badges, états de succès)
- **Typographies** : rien d'imposé par une charte transmise — conserver Roboto/Montserrat/Raleway du template en v1.
- **Contenu éditorial réutilisable** : le document de présentation des armoiries fournit une explication détaillée de chaque symbole, exploitable pour "À propos > Historique" ou une sous-page "Nos armoiries".
- **Photos officielles** (cathédrale, évêque, paroisses) : toujours à collecter — reste un point ouvert.

*(Statut : logo et couleurs disponibles, implémentation dans `main.css` restante — voir `DESIGN_PROMPTS.md` Prompt 12bis)*

## 11. Écrans

Arborescence réelle du site (`Arborescence_PDF.pdf`), 9 rubriques principales — remplace le résumé fonctionnel simplifié ci-dessous, conservé barré pour traçabilité :

1. **Accueil** — hero slider + actualités à la une + accès rapides ; sous-pages : Mot de l'évêque, Contacts, Évêché, Chancellerie
2. **À propos du diocèse** — Historique, L'évêque, Cartographie du diocèse, Doyennés et Paroisses (`paroisse` + taxonomie `doyenne`), Archives (Histoire du diocèse, Les différents évêques → CPT `ancien_eveque`), Homélies (catégorie), Cathéchèses (catégorie)
3. **Les Conseils de l'évêque** — CPT `conseil`
4. **Personnel apostolique** — Ministres Consacrés (CPT `pretre`), Vie Consacrée (page)
5. **Services et Commissions** — CPT `service_diocesain` et `commission_diocesaine`
6. **Enseignements diocésains** — CPT `etablissement`
7. **Apostolat des Laïques** — Structure du diocèse (page), CPT `mouvement`, `association`, `aumonerie`
8. **Vie de Foi** — Actualités (`post` + catégories : Vatican, Diocèse, Communiqués, Nécrologie), Agenda Diocésain et Agenda de l'évêque (`evenement` + taxonomie `evenement_type`)
9. **Soutenir le diocèse** — Prières, Pèlerinages Nationaux/Diocésains, Devenir bénévole, Faire un don (`page-dons.php`), Secrétariat diocésain, Economat (lien vers la fiche `service_diocesain` correspondante)

Plus, hors arborescence numérotée : **Contact** (formulaire + carte), **Sacrements** (CPT `sacrement`, rattachement dans le menu encore à confirmer avec le diocèse — absent du PDF reçu), **Recherche** / **404**.

~~Résumé fonctionnel simplifié (dépassé) : Accueil, À propos, Actualités, Paroisses, Prêtres, Sacrements, Événements, Dons, Contact, Recherche/404~~

## 12. Tâches

Voir `TODO.md` pour le détail des phases et `TASK_PROMPTS.md` pour les prompts d'exécution destinés à Claude Code.

## 13. Race conditions

Le site n'ayant pas de logique temps réel ni de compteurs concurrents critiques (pas de stock, pas de réservation), les seuls cas de concurrence à surveiller sont :
- **Édition simultanée d'un même contenu** par deux rédacteurs → couvert nativement par le système de verrouillage/révisions de WordPress (« Ce contenu est en cours de modification par... »), aucun développement custom nécessaire
- **Soumissions multiples rapides du formulaire de contact** (double-clic) → gérées côté client par Contact Form 7 (désactivation du bouton pendant l'envoi)

Aucune race condition serveur critique identifiée à ce stade du projet.
