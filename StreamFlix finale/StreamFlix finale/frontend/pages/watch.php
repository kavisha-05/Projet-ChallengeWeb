<?php
// Session déjà démarrée dans index.php
require_once __DIR__ . '/../../backend/config.php';
require_once __DIR__ . '/../../backend/vendor/autoload.php';
require_once __DIR__ . '/../includes/functions.php';

use Streamflix\TMDB;
use Streamflix\FilmModel;
use Streamflix\OpenSubtitlesLinks;

$movieId = $_GET['id'] ?? null;

if (!$movieId) {
    header('Location: ' . route_url());
    exit;
}

$tmdb = new TMDB(TMDB_API_KEY);
$movie = $tmdb->getMovie($movieId);

if (!$movie) {
    header('Location: ' . route_url());
    exit;
}

// Récupérer l'URL de la vidéo
$filmModel = new FilmModel();
$filmFromDb = $filmModel->getFilmById($movieId);
$videoUrl = null;

// 1. Vérifier dans la base de données
if ($filmFromDb && !empty($filmFromDb['url_video'])) {
    $videoUrl = $filmFromDb['url_video'];
}

// 2. Vérifier dans le mapping vidéo
if (!$videoUrl) {
    $videoMapping = [];
    $mappingFile = __DIR__ . '/../../backend/video_mapping.php';
    if (file_exists($mappingFile)) {
        $videoMapping = require $mappingFile;
    }
    
    // Chercher par ID TMDB
    if (isset($videoMapping[$movieId])) {
        $videoUrl = $videoMapping[$movieId];
    }
    // Chercher par titre
    elseif (isset($videoMapping[$movie['title']])) {
        $videoUrl = $videoMapping[$movie['title']];
    }
}

// 3. Si toujours pas d'URL, rediriger vers OpenSubtitles
if (!$videoUrl) {
    $opensubtitlesUrl = OpenSubtitlesLinks::getUrl(
        $movie['id'],
        $movie['title'],
        $movie['release_date'] ?? null
    );
    header('Location: ' . $opensubtitlesUrl);
    exit;
}

// Incrémenter le nombre de vues si le film est dans la DB
if ($filmFromDb) {
    $filmModel->incrementViews($movieId);
}

require __DIR__ . '/../includes/header.php';
?>

<div class="watch-container">
    <div class="watch-header">
        <a href="<?= route_url('film', ['id' => $movieId]) ?>" class="back-btn">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
            </svg>
            Retour
        </a>
        <h1 class="watch-title"><?= htmlspecialchars($movie['title']) ?></h1>
    </div>
    
    <div class="video-player-container">
        <video id="video-player" class="video-player" controls autoplay>
            <source src="<?= htmlspecialchars($videoUrl) ?>" type="video/mp4">
            Votre navigateur ne supporte pas la lecture de vidéos HTML5.
        </video>
    </div>
    
    <div class="watch-info">
        <div class="watch-meta">
            <span class="watch-rating">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
                <?= number_format($movie['vote_average'] ?? 0, 1) ?>
            </span>
            <span class="watch-date">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z"/>
                </svg>
                <?= isset($movie['release_date']) ? date('d F Y', strtotime($movie['release_date'])) : 'N/A' ?>
            </span>
        </div>
        <p class="watch-description"><?= htmlspecialchars($movie['overview'] ?? '') ?></p>
    </div>
</div>

<script>
// Gestion de la lecture vidéo
const video = document.getElementById('video-player');

// Sauvegarder la position de lecture
video.addEventListener('timeupdate', function() {
    localStorage.setItem('video_position_' + <?= $movieId ?>, video.currentTime);
});

// Restaurer la position de lecture
window.addEventListener('load', function() {
    const savedPosition = localStorage.getItem('video_position_' + <?= $movieId ?>);
    if (savedPosition) {
        video.currentTime = parseFloat(savedPosition);
    }
});

// Gestion du plein écran
document.addEventListener('keydown', function(e) {
    if (e.key === 'f' || e.key === 'F') {
        if (video.requestFullscreen) {
            video.requestFullscreen();
        } else if (video.webkitRequestFullscreen) {
            video.webkitRequestFullscreen();
        } else if (video.mozRequestFullScreen) {
            video.mozRequestFullScreen();
        }
    }
});
</script>

<?php require __DIR__ . '/../includes/footer.php'; ?>

