# Backend local

Fondation Laravel 13.32.0 / PHP 8.3+ / MySQL (8.4.3 vérifié localement).
Sanctum SPA (cookie/session) est configuré ; aucune API métier n'est encore implémentée.

Depuis `backend/` :

```powershell
composer install
Copy-Item .env.example .env # uniquement si .env n'existe pas
php artisan key:generate
```

Configurer les identifiants locaux dans `.env` seulement. Créer sur le serveur
MySQL local les bases `travel_agency_dev` et `travel_agency_test` (utf8mb4,
utf8mb4_unicode_ci), sans supprimer de base existante.

```powershell
php artisan config:clear
php artisan migrate
php artisan serve --host=127.0.0.1 --port=8000
```

## Vérifications

```powershell
php artisan config:clear
php artisan test
composer validate --strict
composer audit
php vendor/bin/pint --test
```

`phpunit.xml` impose l'environnement `testing` et le driver MySQL, neutralise
`DB_URL` et le socket, et propose par défaut `127.0.0.1:3306/travel_agency_test`.
Les variables de processus `DB_HOST`, `DB_PORT` et `DB_DATABASE` permettent de
surcharger ces valeurs pour un worktree ou la CI, sans modifier le code source.
Les identifiants sont lus depuis `.env`, sans copie de secret.
Aucun `.env.testing` n'est nécessaire pour cette fondation. C'est le mécanisme
[PHPUnit documenté par Laravel 13](https://github.com/laravel/docs/blob/13.x/testing.md).

Exemple dans un terminal PowerShell dédié aux tests, avec une base déjà provisionnée :

```powershell
$env:DB_HOST = '127.0.0.1'
$env:DB_PORT = '3307'
$env:DB_DATABASE = 'travel_agency_test_worktree2'
php artisan test
```

Fermer ce terminal après les tests pour ne pas réutiliser ces variables en développement.
La convention autorisée est `travel_agency_test` ou `travel_agency_test_<suffix>`
(suffixe non vide composé de lettres ASCII, chiffres ou underscores).
Une valeur héritée telle que `travel_agency_dev` est refusée, pas remplacée.

Le TestCase refuse une configuration en cache ou une base hors convention avant que les
traits de test puissent lancer des migrations. Le test d'isolation interroge
`SELECT DATABASE()` sur la connexion réelle et vérifie sa correspondance avec la
configuration, la convention de test et l'exclusion de la base dev. Les tests actuels ne modifient pas
les données et ne nécessitent aucune table dans la base de test. Les futures
migrations de tests devront rester strictement limitées à cette base.

Les migrations dev actuelles sont uniquement les trois migrations du scaffold
plus la migration `personal_access_tokens` fournie par Sanctum (table publiée,
non utilisée par le flux SPA cookie/session actuel).
Ne pas lancer `migrate:fresh` ou `db:wipe` sur la base de développement.
Le fichier SQLite initial a été retiré après vérification de l'absence de données
applicatives ; il n'est plus créé par le script Composer du scaffold.

## Authentification Sanctum SPA

Endpoints disponibles sous `/api/v1/auth` : `POST login`, `POST logout` (protégé),
`GET me` (protégé). Le handshake CSRF se fait via `GET /sanctum/csrf-cookie`
avant tout appel state-changing.

Variables `.env` requises pour le développement local (déjà dans `.env.example`) :

```
SANCTUM_STATEFUL_DOMAINS=localhost:5173,127.0.0.1:5173
CORS_ALLOWED_ORIGINS=http://localhost:5173,http://127.0.0.1:5173
```

Ces deux listes doivent rester cohérentes avec l'origine réelle du serveur Vite
(`http://localhost:5173` ou `http://127.0.0.1:5173`) : un mismatch entre les deux
casse silencieusement le CORS pour l'origine non couverte.
