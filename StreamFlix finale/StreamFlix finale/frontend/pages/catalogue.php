<?php
require_once __DIR__ . '/../../backend/config.php';
require_once __DIR__ . '/../../backend/vendor/autoload.php';
require_once __DIR__ . '/../includes/functions.php';

use Streamflix\TMDB;

$tmdb = new TMDB(TMDB_API_KEY);

$genre = $_GET['genre'] ?? 'comedy';
$page = intval($_GET['page'] ?? 1);

$genreMap = [
    'comedy' => ['id' => GENRE_COMEDY, 'name' => 'COMÉDIE'],
    'horror' => ['id' => GENRE_HORROR, 'name' => 'HORREUR'],
    'scifi' => ['id' => GENRE_SCIENCE_FICTION, 'name' => 'SCIENCE FICTION']
];

$currentGenre = $genreMap[$genre] ?? $genreMap['comedy'];
$movies = $tmdb->getMoviesByGenre($currentGenre['id'], $page);

require __DIR__ . '/../includes/header.php';
?>

<div class="catalogue-container">
    <div class="catalogue-header">
        <h1 class="catalogue-title"><?= $currentGenre['name'] ?></h1>
        <p class="catalogue-count"><?= $movies['total_results'] ?? 0 ?> films trouvés</p>
    </div>
    
    <div class="catalogue-grid">
        <?php foreach ($movies['results'] ?? [] as $movie): ?>
            <div class="catalogue-movie-card">
                <a href="<?= route_url('film', ['id' => $movie['id']]) ?>">
                    <img src="<?= TMDB_IMAGE_BASE_URL . $movie['poster_path'] ?>" alt="<?= htmlspecialchars($movie['title']) ?>" class="catalogue-poster">
                    <div class="catalogue-movie-info">
                        <h3><?= htmlspecialchars($movie['title']) ?></h3>
                        <div class="catalogue-movie-meta">
                            <span class="catalogue-rating">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                                <?= number_format($movie['vote_average'] ?? 0, 1) ?>
                            </span>
                            <span class="catalogue-year"><?= isset($movie['release_date']) ? date('Y', strtotime($movie['release_date'])) : 'N/A' ?></span>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
    
    <?php if (($movies['total_pages'] ?? 1) > 1): ?>
        <div class="catalogue-pagination">
            <?php if ($page > 1): ?>
                <a href="<?= route_url('catalogue', ['genre' => $genre, 'page' => $page - 1]) ?>" class="pagination-btn">Précédent</a>
            <?php endif; ?>
            
            <span class="pagination-info">Page <?= $page ?> sur <?= $movies['total_pages'] ?? 1 ?></span>
            
            <?php if ($page < ($movies['total_pages'] ?? 1)): ?>
                <a href="<?= route_url('catalogue', ['genre' => $genre, 'page' => $page + 1]) ?>" class="pagination-btn">Suivant</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>

