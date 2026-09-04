# BUGS CORRIGÉS

*(Aucun bug à ce jour — le projet n'a pas encore démarré son implémentation. Cette section sera alimentée par Claude Code au fil des tâches, au format ci-dessous.)*

- **[Date]** Description du bug corrigé (renvoyer vers l'entrée correspondante dans `DECISIONS.md` si la correction a nécessité une décision technique)

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
