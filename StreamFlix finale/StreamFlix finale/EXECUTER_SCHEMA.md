# Comment exécuter le schema.sql dans PostgreSQL

## Option 1 : Via pgAdmin (Recommandé - Plus simple)

1. **Ouvrez pgAdmin**
2. **Connectez-vous** à votre serveur PostgreSQL
3. **Sélectionnez** la base de données `streamflix` dans l'arborescence de gauche
4. **Clic droit** sur `streamflix` → **Query Tool** (ou appuyez sur F5)
5. **Ouvrez** le fichier `database/schema.sql` dans un éditeur de texte
6. **Copiez tout le contenu** du fichier
7. **Collez** dans la fenêtre Query Tool de pgAdmin
8. **Exécutez** en cliquant sur le bouton ▶ (ou appuyez sur F5)

## Option 2 : Trouver psql et l'ajouter au PATH

### Trouver où se trouve psql :

```powershell
# Chercher psql sur votre système
Get-ChildItem -Path "C:\Program Files" -Recurse -Filter "psql.exe" -ErrorAction SilentlyContinue | Select-Object FullName
```

### Ajouter au PATH temporairement :

```powershell
# Remplacez le chemin par celui trouvé ci-dessus
$env:Path += ";C:\Program Files\PostgreSQL\15\bin"
psql -U postgres -d streamflix -f database/schema.sql
```

### Ajouter au PATH définitivement :

1. Ouvrez **Variables d'environnement** (Win + R → `sysdm.cpl` → Onglet Avancé)
2. Cliquez sur **Variables d'environnement**
3. Sélectionnez **Path** dans Variables système
4. Cliquez sur **Modifier**
5. Ajoutez le chemin : `C:\Program Files\PostgreSQL\15\bin` (remplacez 15 par votre version)

## Option 3 : Utiliser le chemin complet directement

```powershell
# Remplacez 15 par votre version de PostgreSQL
& "C:\Program Files\PostgreSQL\15\bin\psql.exe" -U postgres -d streamflix -f database/schema.sql
```

## Option 4 : Script PowerShell automatique

Utilisez le script `executer-schema.ps1` que je vais créer pour vous.

