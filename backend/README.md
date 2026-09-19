# Backend local

Fondation Laravel 13.32.0 / PHP 8.3+ / MySQL (8.4.3 vérifié localement).
Aucune API métier ni configuration Sanctum n'est encore implémentée.

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

`phpunit.xml` fixe l'environnement `testing` et la connexion MySQL locale
`127.0.0.1:3306/travel_agency_test`, y compris si le terminal hérite de variables
de développement. Les identifiants sont lus depuis `.env`, sans copie de secret.
Aucun `.env.testing` n'est nécessaire pour cette fondation. C'est le mécanisme
[PHPUnit documenté par Laravel 13](https://github.com/laravel/docs/blob/13.x/testing.md).

Le TestCase refuse une configuration en cache ou une autre cible avant que les
traits de test puissent lancer des migrations. Le test d'isolation interroge
`SELECT DATABASE()` sur la connexion réelle. Les tests actuels ne modifient pas
les données et ne nécessitent aucune table dans la base de test. Les futures
migrations de tests devront rester strictement limitées à cette base.

Les migrations dev actuelles sont uniquement les trois migrations du scaffold.
Ne pas lancer `migrate:fresh` ou `db:wipe` sur la base de développement.
Le fichier SQLite initial a été retiré après vérification de l'absence de données
applicatives ; il n'est plus créé par le script Composer du scaffold.
