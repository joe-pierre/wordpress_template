# CLAUDE.md — Contexte et prompt d'amorçage

## Contexte du projet

Conversion du template Bootstrap statique **"Story"** (BootstrapMade) en **thème WordPress classique** pour le site du **Diocèse de Ziguinchor**. Le template source est un thème de blog générique (contenu Lorem Ipsum) ; il n'existe aucune page "Paroisses", "Prêtres", "Sacrements" ou "Dons" dans le HTML d'origine — ces structures sont à créer entièrement via des Custom Post Types WordPress + ACF, en réutilisant les gabarits visuels (cartes, sliders, grilles) déjà présents dans le template.

Le thème doit rester utilisable par des **rédacteurs non techniques** (secrétariat diocésain) sans toucher au code : toute donnée variable (menus, textes, images, slides, listes de paroisses/prêtres/événements) doit passer par l'admin WordPress ou ACF, jamais rester codée en dur dans les templates PHP.

## Prompt d'amorçage (à exécuter au début de chaque session)

Au début de chaque session, lis dans cet ordre :
1. `SPEC.md` — spécifications fonctionnelles et techniques
2. `CONVENTIONS.md` — règles de codage
3. `TODO.md` — état d'avancement des tâches
4. `DECISIONS.md` — décisions techniques et bugs résolus
5. `CODE_SNAPSHOT.md` — snapshot du code actuel

Puis résume en 5 points :
- Ce que fait le projet
- La stack technique utilisée
- L'état d'avancement actuel
- Les conventions importantes à respecter
- Les décisions clés déjà prises

Avant de commencer une nouvelle tâche, vérifier qu'elle ne contredit pas une entrée de `DECISIONS.md` (ex. ne pas recréer un CPT pour les actualités, ne pas redévelopper un handler de formulaire custom, ne pas réintroduire un menu à 3 niveaux) sans le signaler explicitement et l'inscrire comme nouvelle décision si le contexte a changé.

## À la fin de chaque tâche

- Mets à jour `DECISIONS.md` si une décision technique a été prise ou un bug résolu (utiliser le format `[RÉSOLU|CHOIX]` déjà en place)
- Mets à jour `TODO.md` pour cocher les tâches accomplies et ajouter les suivantes si de nouvelles sous-tâches apparaissent
- Mets à jour `BUGS_AND_ROADMAP.md` si un bug a été corrigé ou une idée d'amélioration identifiée
- Régénère/actualise `CODE_SNAPSHOT.md` si le script de génération est disponible (ne jamais l'éditer à la main)

---

## Formats des fichiers Markdown modifiables

### Format `TODO.md`

````markdown
# TODO

## Phase X — Nom de la phase
- [ ] Tâche à faire
- [x] Tâche accomplie
````

### Format `DECISIONS.md`

````markdown
## [RÉSOLU|CHOIX] Titre de la décision

**Contexte :** ...
**Symptôme / Problème :** ...
**Cause / Alternatives :** ...
**Fix / Décision :** ...
**Leçon :** ...
**Statut :** ✅ Résolu | 🔵 Choix assumé
````

### Format `BUGS_AND_ROADMAP.md`

````markdown
# BUGS CORRIGÉS
- **[Date]** Description du bug corrigé

# ROADMAP (idées / améliorations futures)
- Idée ou amélioration à envisager
````
