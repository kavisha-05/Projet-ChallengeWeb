<?php
require_once __DIR__ . '/../../backend/config.php';
require_once __DIR__ . '/../../backend/vendor/autoload.php';
require_once __DIR__ . '/../includes/functions.php';

use Streamflix\TMDB;
use Streamflix\OpenSubtitlesLinks;

$tmdb = new TMDB(TMDB_API_KEY);
$movieId = $_GET['id'] ?? null;

if (!$movieId) {
    header('Location: ' . route_url());
    exit;
}

$movie = $tmdb->getMovie($movieId);

if (!$movie) {
    header('Location: ' . route_url());
    exit;
}

// Récupérer les crédits (casting et réalisateurs)
$credits = $tmdb->getMovieCredits($movieId);
$directors = [];
$cast = [];

if ($credits) {
    // Récupérer les réalisateurs
    foreach ($credits['crew'] ?? [] as $person) {
        if ($person['job'] === 'Director') {
            $directors[] = $person['name'];
        }
    }
    // Récupérer le casting (10 premiers acteurs)
    $cast = array_slice($credits['cast'] ?? [], 0, 10);
}

// Récupérer des films recommandés
$recommended = $tmdb->getTopMovies(1);

require __DIR__ . '/../includes/header.php';
?>

<div class="film-detail-container">
    <div class="film-poster-section">
        <div class="film-backdrop-wrapper">
            <img src="<?= TMDB_IMAGE_BASE_URL_ORIGINAL . $movie['backdrop_path'] ?>" alt="<?= htmlspecialchars($movie['title']) ?>" class="film-backdrop">
            <div class="film-backdrop-overlay"></div>
        </div>
        <div class="film-content">
            <div class="film-poster-wrapper">
                <img src="<?= TMDB_IMAGE_BASE_URL_ORIGINAL . $movie['poster_path'] ?>" alt="<?= htmlspecialchars($movie['title']) ?>" class="film-poster-large">
            </div>
            <div class="film-info">
                <h1 class="film-title"><?= htmlspecialchars($movie['title']) ?></h1>
                <div class="film-meta">
                    <span class="film-rating">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                        <?= number_format($movie['vote_average'] ?? 0, 1) ?>
                    </span>
                    <span class="film-date">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z"/>
                        </svg>
                        <?= isset($movie['release_date']) ? date('d F Y', strtotime($movie['release_date'])) : 'N/A' ?>
                    </span>
                    <span class="film-duration">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                        </svg>
                        <?= isset($movie['runtime']) ? floor($movie['runtime'] / 60) . 'h' . ($movie['runtime'] % 60) . 'm' : 'N/A' ?>
                    </span>
                </div>
                <div class="film-genres">
                    <?php foreach ($movie['genres'] ?? [] as $genre): ?>
                        <span class="genre-badge"><?= htmlspecialchars($genre['name']) ?></span>
                    <?php endforeach; ?>
                </div>
                <?php if (!empty($directors)): ?>
                    <p class="film-director"><strong>Réalisateur<?= count($directors) > 1 ? 's' : '' ?> :</strong> <?= htmlspecialchars(implode(', ', $directors)) ?></p>
                <?php endif; ?>
                <div class="film-synopsis">
                    <h3>Résumé</h3>
                    <p><?= htmlspecialchars($movie['overview'] ?? 'Aucun résumé disponible.') ?></p>
                </div>
                <?php if (!empty($cast)): ?>
                    <div class="film-cast">
                        <h3>Casting</h3>
                        <div class="cast-list">
                            <?php foreach ($cast as $actor): ?>
                                <div class="cast-member">
                                    <?php if (!empty($actor['profile_path'])): ?>
                                        <img src="<?= TMDB_IMAGE_BASE_URL . $actor['profile_path'] ?>" alt="<?= htmlspecialchars($actor['name']) ?>" class="cast-photo">
                                    <?php else: ?>
                                        <div class="cast-photo-placeholder">
                                            <svg width="40" height="40" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                            </svg>
                                        </div>
                                    <?php endif; ?>
                                    <div class="cast-info">
                                        <p class="cast-name"><?= htmlspecialchars($actor['name']) ?></p>
                                        <p class="cast-character"><?= htmlspecialchars($actor['character'] ?? '') ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="film-actions">
                    <?php
                    $playUrl = OpenSubtitlesLinks::getUrl(
                        $movie['id'],
                        $movie['title'],
                        $movie['release_date'] ?? null
                    );
                    ?>
                    <a href="<?= route_url('watch', ['id' => $movie['id']]) ?>" class="play-button">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                        Lire
                    </a>
                    <button class="add-to-cart-btn" onclick="addToCart(<?= $movie['id'] ?>, '<?= htmlspecialchars(addslashes($movie['title'])) ?>', '<?= htmlspecialchars(addslashes(TMDB_IMAGE_BASE_URL . $movie['poster_path'])) ?>')">
                        Ajouter au panier
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="recommended-section">
        <h3>Recommandations</h3>
        <div class="recommended-movies">
            <?php foreach (array_slice($recommended['results'] ?? [], 0, 3) as $recMovie): ?>
                <a href="<?= route_url('film', ['id' => $recMovie['id']]) ?>" class="recommended-card">
                    <img src="<?= TMDB_IMAGE_BASE_URL . $recMovie['poster_path'] ?>" alt="<?= htmlspecialchars($recMovie['title']) ?>">
                    <p><?= htmlspecialchars($recMovie['title']) ?></p>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>

