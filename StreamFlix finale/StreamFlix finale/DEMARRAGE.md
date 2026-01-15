# Comment démarrer le site Streamflix

## Prérequis

1. PHP installé (version 7.4 ou supérieure)
2. Composer installé

## Installation

1. **Installer les dépendances Composer** :
   ```bash
   cd backend
   composer install
   ```

2. **Ajouter le logo** :
   Placez le logo Streamflix dans `frontend/assets/images/logo.png`

## Démarrer le serveur

### Option 1 : Utiliser le script batch (Windows)
Double-cliquez sur `start-server.bat` ou exécutez :
```bash
start-server.bat
```

### Option 2 : Commande manuelle
```bash
php -S localhost:8000
```

## Accéder au site

Ouvrez votre navigateur et allez sur :
```
http://localhost:8000
```

## Structure du projet

```
brouillon2/
├── backend/          # Code PHP backend
│   ├── src/         # Classes PHP (Router, TMDB)
│   ├── api/         # Gestionnaire API
│   ├── config.php   # Configuration
│   └── composer.json
├── frontend/         # Interface utilisateur
│   ├── pages/       # Pages PHP
│   ├── includes/    # Header, footer, fonctions
│   └── assets/      # CSS, JS, images
└── index.php        # Point d'entrée
```

## Commandes utiles

- **Démarrer le serveur** : `php -S localhost:8000`
- **Arrêter le serveur** : Appuyez sur `Ctrl+C` dans le terminal
- **Changer le port** : `php -S localhost:8080` (remplacez 8080 par le port souhaité)

