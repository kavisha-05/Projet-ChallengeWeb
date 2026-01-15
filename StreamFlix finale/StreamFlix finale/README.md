# STREAMFLIX

Site web de streaming de films créé avec PHP et Composer.

## Installation

1. Installer les dépendances avec Composer :
```bash
composer install
```

2. Configurer votre serveur web (Apache avec mod_rewrite activé) pour pointer vers le dossier racine du projet.

3. Le fichier `.htaccess` est déjà configuré pour le routing.

## Structure du projet

- `pages/` - Pages PHP du site
- `includes/` - Header et footer
- `assets/` - CSS, JavaScript et images
- `src/` - Classes PHP (Router, TMDB)
- `api/` - Gestionnaire d'API pour le panier et l'authentification
- `config.php` - Configuration (clé API TMDB)

## Fonctionnalités

- Page d'accueil avec Top 10 des films
- Sections par genre (Comédie, Horreur, Science Fiction)
- Page de détail de film
- Système de panier
- Pages de connexion et inscription
- Page d'abonnement avec 3 plans
- Intégration API TMDB pour récupérer les films

## API TMDB

La clé API est déjà configurée dans `config.php`. Les films sont récupérés depuis l'API TMDB.

## Logo

Placez le logo Streamflix dans `assets/images/logo.png`

