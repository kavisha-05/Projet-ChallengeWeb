<?php
require_once __DIR__ . '/../../backend/config.php';
require_once __DIR__ . '/../../backend/vendor/autoload.php';
require_once __DIR__ . '/../includes/functions.php';

use Streamflix\TMDB;

$tmdb = new TMDB(TMDB_API_KEY);

$query = $_GET['q'] ?? '';
$page = intval($_GET['page'] ?? 1);

$results = [];
if (!empty($query)) {
    $results = $tmdb->searchMovies($query, $page);
}

require __DIR__ . '/../includes/header.php';
?>

<div class="search-container-page">
    <div class="search-header">
        <h1 class="search-title">Résultats de recherche</h1>
        <?php if (!empty($query)): ?>
            <p class="search-query">Recherche : "<strong><?= htmlspecialchars($query) ?></strong>"</p>
            <p class="search-count"><?= $results['total_results'] ?? 0 ?> résultats trouvés</p>
        <?php else: ?>
            <p class="search-empty">Veuillez entrer un terme de recherche</p>
        <?php endif; ?>
    </div>
    
    <?php if (!empty($query) && !empty($results['results'])): ?>
        <div class="search-grid">
            <?php foreach ($results['results'] ?? [] as $movie): ?>
                <div class="search-movie-card">
                    <a href="<?= route_url('film', ['id' => $movie['id']]) ?>">
                        <img src="<?= TMDB_IMAGE_BASE_URL . $movie['poster_path'] ?>" alt="<?= htmlspecialchars($movie['title']) ?>" class="search-poster">
                        <div class="search-movie-info">
                            <h3><?= htmlspecialchars($movie['title']) ?></h3>
                            <div class="search-movie-meta">
                                <span class="search-rating">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                    <?= number_format($movie['vote_average'] ?? 0, 1) ?>
                                </span>
                                <span class="search-year"><?= isset($movie['release_date']) ? date('Y', strtotime($movie['release_date'])) : 'N/A' ?></span>
                            </div>
                            <?php if (!empty($movie['overview'])): ?>
                                <p class="search-overview"><?= htmlspecialchars(mb_substr($movie['overview'], 0, 150)) ?>...</p>
                            <?php endif; ?>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        
        <?php if (($results['total_pages'] ?? 1) > 1): ?>
            <div class="search-pagination">
                <?php if ($page > 1): ?>
                    <a href="<?= route_url('search', ['q' => $query, 'page' => $page - 1]) ?>" class="pagination-btn">Précédent</a>
                <?php endif; ?>
                
                <span class="pagination-info">Page <?= $page ?> sur <?= $results['total_pages'] ?? 1 ?></span>
                
                <?php if ($page < ($results['total_pages'] ?? 1)): ?>
                    <a href="<?= route_url('search', ['q' => $query, 'page' => $page + 1]) ?>" class="pagination-btn">Suivant</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php elseif (!empty($query)): ?>
        <div class="search-no-results">
            <p>Aucun résultat trouvé pour "<?= htmlspecialchars($query) ?>"</p>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>

