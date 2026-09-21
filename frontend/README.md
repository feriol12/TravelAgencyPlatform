# Frontend foundation

Vue 3 + Vite, JavaScript, Vue Router, Pinia et Axios.
Node `^20.19.0 || >=22.12.0` requis.

Depuis `frontend/` :

```powershell
npm install
Copy-Item .env.example .env # uniquement si .env n'existe pas
npm run dev
```

Sous Windows, utiliser `npm.cmd` si PowerShell bloque `npm.ps1`.
Vite affiche son URL locale (habituellement `http://localhost:5173`).

```powershell
npm run build
npm run preview
npm audit
```

`VITE_API_URL=http://localhost:8000` configure l'instance Axios centralisée dans
`src/services/api.js`. Les futurs appels API utiliseront le préfixe `/api/v1`.
Les variables `VITE_*` sont publiques dans le navigateur : aucun secret.
`.env`, `node_modules/` et `dist/` sont ignorés par le `.gitignore` racine.

Les routes `/`, `/client` et `/admin` chargent leurs layouts et vues minimales
à la demande. Elles ne sont pas sécurisées. Pinia est initialisé sans store métier.

`src/services/auth.js` fournit le client minimal Sanctum SPA : `fetchCsrfCookie`,
`login`, `logout`, `fetchCurrentUser` (normalise 401/419 en `null`, sans retry
automatique). Aucun store métier ni page de login finale ; `/dev/auth-smoke`
est une route temporaire de vérification manuelle du flux, à retirer quand une
vraie page de login existera.

Le routeur utilise l'historique HTML5 : un futur hébergement devra renvoyer
`index.html` pour les routes de la SPA. Vite gère ce fallback en développement.
Référence : [Vue Router — history mode](https://router.vuejs.org/guide/essentials/history-mode.html).

Lint: not configured in current foundation. Aucun framework de tests ou bibliothèque UI ajouté.
