<?php

namespace Streamflix;

class OpenSubtitlesLinks
{
    /**
     * Mapping des IDs TMDB vers les URLs OpenSubtitles
     * Format: 'tmdb_id' => 'url_opensubtitles'
     */
    private static $links = [];
    
    /**
     * Mapping des titres vers les URLs OpenSubtitles
     */
    private static $titleMapping = null;
    
    /**
     * Charge le mapping des titres depuis le fichier de configuration
     */
    private static function loadTitleMapping()
    {
        if (self::$titleMapping === null) {
            $mappingFile = __DIR__ . '/opensubtitles_mapping.php';
            if (file_exists($mappingFile)) {
                self::$titleMapping = require $mappingFile;
            } else {
                self::$titleMapping = [];
            }
        }
        return self::$titleMapping;
    }

    /**
     * Génère une URL OpenSubtitles basée sur le titre du film
     * Format: https://www.opensubtitles.com/sr/movies/[titre-formaté]
     */
    public static function generateUrl($title, $year = null)
    {
        // Formater le titre pour l'URL (minuscules, espaces remplacés par des tirets)
        $formattedTitle = strtolower($title);
        $formattedTitle = preg_replace('/[^a-z0-9\s-]/', '', $formattedTitle);
        $formattedTitle = preg_replace('/\s+/', '-', trim($formattedTitle));
        
        // Ajouter l'année si fournie
        $url = 'https://www.opensubtitles.com/sr/movies/';
        if ($year) {
            $url .= $year . '-';
        }
        $url .= $formattedTitle;
        
        return $url;
    }

    /**
     * Récupère l'URL OpenSubtitles pour un film
     * Vérifie d'abord le mapping, sinon génère l'URL
     */
    public static function getUrl($tmdbId, $title, $releaseDate = null)
    {
        // Vérifier si on a un mapping spécifique par ID TMDB
        if (isset(self::$links[$tmdbId])) {
            return self::$links[$tmdbId];
        }
        
        // Vérifier le mapping par titre
        $titleMapping = self::loadTitleMapping();
        $titleLower = strtolower(trim($title));
        
        // Chercher une correspondance exacte
        if (isset($titleMapping[$titleLower])) {
            return $titleMapping[$titleLower];
        }
        
        // Chercher une correspondance partielle (contient)
        foreach ($titleMapping as $key => $url) {
            if (strpos($titleLower, $key) !== false || strpos($key, $titleLower) !== false) {
                return $url;
            }
        }
        
        // Sinon, générer l'URL à partir du titre
        $year = $releaseDate ? date('Y', strtotime($releaseDate)) : null;
        return self::generateUrl($title, $year);
    }

    /**
     * Ajouter un mapping personnalisé
     */
    public static function addMapping($tmdbId, $url)
    {
        self::$links[$tmdbId] = $url;
    }
}

