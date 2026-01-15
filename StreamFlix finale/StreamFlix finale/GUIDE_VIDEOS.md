# Guide pour ajouter des URLs vidéo

## Comment ajouter des URLs vidéo pour les films

### Option 1 : Via le fichier de mapping (Recommandé pour les films TMDB)

Éditez le fichier `backend/video_mapping.php` et ajoutez vos URLs :

```php
return [
    // Par ID TMDB
    '12345' => 'https://votre-serveur.com/videos/film1.mp4',
    
    // Par titre exact
    'Predator: Badlands' => 'https://votre-serveur.com/videos/predator-badlands.mp4',
    'Zootopia 2' => 'https://votre-serveur.com/videos/zootopia-2.mp4',
];
```

### Option 2 : Via la base de données

Si vous avez des films dans votre base de données PostgreSQL, vous pouvez ajouter l'URL directement :

```sql
UPDATE Film 
SET url_video = 'https://votre-serveur.com/videos/film.mp4' 
WHERE id_film = 1;
```

### Option 3 : Insérer des films dans la base de données

Pour ajouter un film avec son URL vidéo :

```php
use Streamflix\FilmModel;

$filmModel = new FilmModel();
$filmId = $filmModel->createFilm(
    'Titre du film',
    'Description',
    9.99,
    'https://votre-serveur.com/videos/film.mp4',  // URL vidéo
    'https://image.tmdb.org/t/p/w500/poster.jpg'  // Affiche
);
```

## Ordre de priorité

Le système cherche l'URL vidéo dans cet ordre :
1. **Base de données** (table `Film`, champ `url_video`)
2. **Fichier de mapping** (`backend/video_mapping.php`)
3. **Redirection vers OpenSubtitles** (si aucune URL trouvée)

## Formats vidéo supportés

Le lecteur HTML5 supporte :
- MP4 (recommandé)
- WebM
- OGG

## Exemple d'URL vidéo

```
https://votre-serveur.com/videos/film.mp4
http://localhost/videos/film.mp4
/videos/film.mp4 (chemin relatif)
```

## Note importante

Si aucune URL vidéo n'est trouvée, le système redirige automatiquement vers OpenSubtitles pour que l'utilisateur puisse regarder le film avec sous-titres.

