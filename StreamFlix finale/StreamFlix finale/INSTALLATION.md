# Instructions d'installation

## 1. Installer les dépendances

```bash
composer install
```

## 2. Ajouter le logo

Placez le logo Streamflix dans le dossier `assets/images/` sous le nom `logo.png`.

Le logo doit être une image PNG avec le clapperboard rouge et le texte STREAMFLIX comme montré dans les images de référence.

## 3. Configuration du serveur

### Apache
Assurez-vous que `mod_rewrite` est activé. Le fichier `.htaccess` est déjà configuré.

### Nginx
Si vous utilisez Nginx, vous devrez configurer la réécriture d'URL manuellement.

## 4. Accéder au site

Ouvrez votre navigateur et accédez à l'URL de votre serveur web pointant vers ce dossier.

## Fonctionnalités

- ✅ Page d'accueil avec Top 10 des films
- ✅ Sections par genre (Comédie, Horreur, Science Fiction)
- ✅ Page de détail de film
- ✅ Système de panier
- ✅ Pages de connexion et inscription
- ✅ Page d'abonnement
- ✅ Intégration API TMDB
- ✅ Navigation automatique vers les genres depuis "Catalogue"

## Notes

- La clé API TMDB est déjà configurée dans `config.php`
- Le panier utilise les sessions PHP
- L'authentification est simulée (pas de base de données)

