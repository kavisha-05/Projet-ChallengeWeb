# Guide Simple - Exécuter le schema.sql

## Étapes à suivre (5 minutes)

### Étape 1 : Ouvrir pgAdmin
- Lancez **pgAdmin** depuis le menu Démarrer
- Si vous n'avez pas pgAdmin, téléchargez-le depuis : https://www.pgadmin.org/download/

### Étape 2 : Se connecter
- Dans pgAdmin, vous verrez votre serveur PostgreSQL dans la liste de gauche
- Cliquez dessus et entrez votre mot de passe (celui que vous avez mis dans `backend/config.php` : `azerty`)
- Si vous n'êtes pas connecté, double-cliquez sur le serveur et entrez le mot de passe

### Étape 3 : Sélectionner la base de données
- Dans l'arborescence de gauche, développez votre serveur PostgreSQL
- Développez **Databases**
- Cliquez sur **streamflix** (la base de données)

### Étape 4 : Ouvrir Query Tool
- **Clic droit** sur **streamflix**
- Cliquez sur **Query Tool** (ou appuyez simplement sur **F5**)

Une nouvelle fenêtre s'ouvre avec un éditeur de texte.

### Étape 5 : Ouvrir le fichier schema.sql
- Dans votre éditeur de code (VS Code, Notepad++, etc.), ouvrez le fichier :
  ```
  database/schema.sql
  ```
- **Sélectionnez TOUT** le contenu (Ctrl+A)
- **Copiez** (Ctrl+C)

### Étape 6 : Coller dans pgAdmin
- Retournez dans pgAdmin (dans la fenêtre Query Tool)
- **Collez** le contenu (Ctrl+V)

### Étape 7 : Exécuter
- Cliquez sur le bouton **▶ Execute** en haut (ou appuyez sur **F5**)

### Étape 8 : Vérifier
- Si tout s'est bien passé, vous verrez des messages de succès en bas
- Vous devriez voir quelque chose comme : `CREATE TABLE` pour chaque table

## ✅ C'est terminé !

Vos tables sont maintenant créées dans PostgreSQL.

## Prochaine étape : Tester la connexion

Exécutez dans votre terminal :
```powershell
php test-db.php
```

Si vous voyez "✓ Connexion réussie", tout fonctionne !

