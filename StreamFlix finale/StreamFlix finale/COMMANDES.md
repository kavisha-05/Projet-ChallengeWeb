# Commandes pour démarrer le site Streamflix

## Étape 1 : Installer les dépendances (une seule fois)

Ouvrez un terminal dans le dossier du projet et exécutez :

```bash
cd backend
composer install
cd ..
```

## Étape 2 : Démarrer le serveur

**Depuis la racine du projet** (dossier `brouillon2`), exécutez :

```bash
php -S localhost:8000
```

OU double-cliquez sur le fichier `start-server.bat`

## Étape 3 : Ouvrir le navigateur

Allez sur : **http://localhost:8000**

## Résumé des commandes

```bash
# 1. Aller dans le dossier backend
cd backend

# 2. Installer les dépendances (une seule fois)
composer install

# 3. Revenir à la racine
cd ..

# 4. Démarrer le serveur
php -S localhost:8000
```

## Important

- **Vous devez être dans le dossier racine** (`brouillon2`) pour lancer `php -S localhost:8000`
- Le serveur doit rester ouvert dans le terminal
- Pour arrêter le serveur : appuyez sur `Ctrl+C`

