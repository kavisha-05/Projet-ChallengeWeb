# Police Marykate pour STREAMFLIX

## Option 1 : Utiliser la police depuis un fichier (Recommandé)

Si vous avez le fichier de la police Marykate :

1. **Créez un dossier** `frontend/assets/fonts/`
2. **Placez vos fichiers de police** dans ce dossier :
   - `Marykate-Regular.woff2` (ou `.woff`, `.ttf`)
3. **Décommentez** les lignes dans `assets/css/style.css` (lignes 3-11) :
   ```css
   @font-face {
       font-family: 'Marykate';
       src: url('../fonts/Marykate-Regular.woff2') format('woff2'),
            url('../fonts/Marykate-Regular.woff') format('woff'),
            url('../fonts/Marykate-Regular.ttf') format('truetype');
       font-weight: normal;
       font-style: normal;
   }
   ```

## Option 2 : Police alternative déjà configurée

J'ai configuré des polices alternatives qui s'affichent si Marykate n'est pas disponible :
- **Bebas Neue** (Google Fonts) - Police display moderne
- **Oswald** (Google Fonts) - Police condensée élégante

Ces polices sont déjà chargées et s'afficheront automatiquement.

## Option 3 : Télécharger Marykate

Si vous n'avez pas la police Marykate, vous pouvez :
1. La télécharger depuis un site de polices (dafont.com, fontsquirrel.com, etc.)
2. Suivre l'Option 1 pour l'installer

## Résultat

Le titre "STREAMFLIX" utilisera maintenant :
1. **Marykate** (si disponible)
2. Sinon **Bebas Neue** (police similaire)
3. Sinon **Oswald** (alternative)

Le style inclut aussi :
- `letter-spacing: 2px` pour un espacement élégant
- `text-transform: uppercase` pour garantir les majuscules

