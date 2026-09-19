# Travel Agency Platform

Plateforme web mono-agence de gestion et de suivi des procédures de voyage.

## Architecture

- `backend/` : Laravel API
- `frontend/` : Vue 3 + Vite
- `docs/` : documentation validée du projet
- `AGENTS.md` : règles communes obligatoires pour les agents IA

## État actuel

Le projet est en phase de baseline repository. Les spécifications fonctionnelles,
l'architecture, le modèle de données et la gouvernance IA ont été validés.

Avant toute implémentation importante, lire :

1. `AGENTS.md`
2. `docs/ai/PROJECT_CONTEXT.md`
3. `docs/ai/CURRENT_STATE.md`
4. la carte Trello active
5. les documents fonctionnels/architecture concernés

## Branches prévues

- `main`
- `develop`
- `feature/*`
- `fix/*`
- `refactor/*`
- `docs/*`

## Important

Ne pas commencer les modules métier avant validation du baseline :
Laravel, Vue, MySQL, documentation, Git et tests de base.
