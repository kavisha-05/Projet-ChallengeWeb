<?php
require_once __DIR__ . '/../../backend/config.php';
require_once __DIR__ . '/../../backend/vendor/autoload.php';
require_once __DIR__ . '/../includes/functions.php';

use Streamflix\TMDB;
use Streamflix\OpenSubtitlesLinks;

$tmdb = new TMDB(TMDB_API_KEY);

// Récupérer les films populaires pour la section hero
$featuredMovies = $tmdb->getTopMovies(1);
$heroMovies = array_slice($featuredMovies['results'] ?? [], 0, 6);
$currentHeroMovie = $heroMovies[0] ?? null;

// Récupérer le Top 10 des films
$topMovies = $tmdb->getTopMovies(1);
$top10 = array_slice($topMovies['results'] ?? [], 0, 10);

// Récupérer les films par genre
$comedyMovies = $tmdb->getMoviesByGenre(GENRE_COMEDY, 1);
$horrorMovies = $tmdb->getMoviesByGenre(GENRE_HORROR, 1);
$scifiMovies = $tmdb->getMoviesByGenre(GENRE_SCIENCE_FICTION, 1);

require __DIR__ . '/../includes/header.php';
?>

<div class="home-container">
    <!-- Hero Section -->
    <?php if ($currentHeroMovie): ?>
    <section class="hero-section" id="hero-section">
        <div class="hero-backdrop" style="background-image: url('<?= TMDB_IMAGE_BASE_URL_ORIGINAL . $currentHeroMovie['backdrop_path'] ?>');">
            <div class="hero-overlay"></div>
        </div>
        <div class="hero-content-wrapper">
            <div class="hero-content">
                <div class="hero-info">
                    <h1 class="hero-title"><?= htmlspecialchars($currentHeroMovie['title']) ?></h1>
                    <div class="hero-meta">
                        <span class="hero-rating">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                            <?= number_format($currentHeroMovie['vote_average'] ?? 0, 1) ?>
                        </span>
                        <span class="hero-date">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z"/>
                            </svg>
                            <?= isset($currentHeroMovie['release_date']) ? date('d F Y', strtotime($currentHeroMovie['release_date'])) : 'N/A' ?>
                        </span>
                    </div>
                    <p class="hero-overview"><?= htmlspecialchars(mb_substr($currentHeroMovie['overview'] ?? '', 0, 200)) ?>...</p>
                    <div class="hero-actions">
                        <?php
                        $playUrl = OpenSubtitlesLinks::getUrl(
                            $currentHeroMovie['id'],
                            $currentHeroMovie['title'],
                            $currentHeroMovie['release_date'] ?? null
                        );
                        ?>
                        <a href="<?= route_url('watch', ['id' => $currentHeroMovie['id']]) ?>" class="hero-play-btn">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                            Lire
                        </a>
                    </div>
                </div>
            </div>
            <div class="hero-thumbnails-container">
                <div class="hero-thumbnails">
                    <?php foreach ($heroMovies as $index => $movie): ?>
                        <div class="hero-thumbnail <?= $index === 0 ? 'active' : '' ?>" 
                             onclick="changeHeroMovie(<?= $index ?>, <?= htmlspecialchars(json_encode($movie)) ?>)">
                            <img src="<?= TMDB_IMAGE_BASE_URL . $movie['poster_path'] ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Top 10 Section -->
    <section class="top10-section">
        <div class="section-header">
            <h2 class="section-title">TOP 10</h2>
            <span class="section-subtitle">DES FILMS ACTUELS</span>
            <div class="section-nav">
                <button class="nav-arrow prev" onclick="scrollSection('top10', -1)">‹</button>
                <button class="nav-arrow next" onclick="scrollSection('top10', 1)">›</button>
            </div>
        </div>
        <div class="movies-carousel" id="top10">
            <?php foreach ($top10 as $index => $movie): ?>
                <div class="movie-card top10-card">
                    <span class="top10-number"><?= $index + 1 ?></span>
                    <a href="<?= route_url('film', ['id' => $movie['id']]) ?>">
                        <img src="<?= TMDB_IMAGE_BASE_URL . $movie['poster_path'] ?>" alt="<?= htmlspecialchars($movie['title']) ?>" class="movie-poster">
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Comédie Section -->
    <section class="genre-section" id="comedy">
        <div class="section-header">
            <div class="section-title-group">
                <svg class="genre-icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 64 64" fill="currentColor">
                    <path d="M62 32C62 15.43 48.57 2 32 2S2 15.43 2 32c0 2.49.309 4.907.88 7.221c-2.49 4.892.696 10.032 5.058 10.699C13.407 57.252 22.149 62 32 62c9.853 0 18.593-4.748 24.062-12.08c4.361-.668 7.545-5.807 5.057-10.697c.572-2.315.881-4.732.881-7.223M32 4.5c15.164 0 27.5 12.336 27.5 27.5c0 1.552-.135 3.071-.383 4.553c-5.41-5.293-15.186-5.543-15.186-5.543s.029 1.197.264 2.996H19.803c.236-1.799.264-2.996.264-2.996s-9.771.25-15.183 5.543A27.6 27.6 0 0 1 4.5 32C4.5 16.836 16.837 4.5 32 4.5M18.499 40c.443-1.395.762-2.765.99-4H44.51c.229 1.235.549 2.605.99 4zm21.214 10.664c-2.229.957-4.799 1.518-7.712 1.518c-2.914 0-5.484-.561-7.713-1.518c2.152-1.031 4.711-1.662 7.713-1.662s5.562.631 7.712 1.662M32 59.5c-8.443 0-16.007-3.828-21.056-9.836c1.195-.396 2.401-1.152 3.543-2.354q.488-.514.92-1.08C18.222 50.436 23.592 54 32 54c8.406 0 13.777-3.563 16.591-7.769q.433.565.918 1.079c1.145 1.201 2.352 1.959 3.547 2.354C48.008 55.672 40.443 59.5 32 59.5"/>
                    <path d="M14.858 29.607c1.802-1.901 3.957-2.658 6.207-2.658s4.404.757 6.207 2.658c.479.505 1.438-.424 1.254-.938C26.667 23.558 23.866 21 21.065 21s-5.602 2.558-7.46 7.669c-.184.515.774 1.443 1.253.938m21.869 0c1.803-1.901 3.957-2.658 6.207-2.658s4.404.757 6.207 2.658c.479.505 1.438-.424 1.254-.938C48.535 23.558 45.734 21 42.934 21s-5.602 2.558-7.461 7.669c-.184.515.775 1.443 1.254.938"/>
                </svg>
                <h2 class="section-title">COMÉDIE</h2>
            </div>
            <a href="<?= route_url('catalogue') ?>#comedy" class="see-all-link">Voir Tout</a>
            <div class="section-nav">
                <button class="nav-arrow prev" onclick="scrollSection('comedy', -1)">‹</button>
                <button class="nav-arrow next" onclick="scrollSection('comedy', 1)">›</button>
            </div>
        </div>
        <div class="movies-carousel" id="comedy-carousel">
            <?php foreach (array_slice($comedyMovies['results'] ?? [], 0, 10) as $movie): ?>
                <div class="movie-card">
                    <a href="<?= route_url('film', ['id' => $movie['id']]) ?>">
                        <img src="<?= TMDB_IMAGE_BASE_URL . $movie['poster_path'] ?>" alt="<?= htmlspecialchars($movie['title']) ?>" class="movie-poster">
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Horreur Section -->
    <section class="genre-section" id="horror">
        <div class="section-header">
            <div class="section-title-group">
                <svg class="genre-icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 64 64" fill="currentColor">
                    <path d="m64 38.771l-7.453 1.475A26.9 26.9 0 0 0 59 29.001c0-4.433-1.08-8.607-2.976-12.297c2.069-2.488 3.257-7.521-.29-16.704c0 0-1.698 5.223-5.554 9.041A26.9 26.9 0 0 0 32 2a26.9 26.9 0 0 0-18.181 7.042C9.963 5.224 8.268 0 8.268 0C4.72 9.182 5.905 14.216 7.975 16.704A26.84 26.84 0 0 0 5 29.001c0 4.014.883 7.82 2.454 11.244L0 38.771s2.725 1.871 2.725 5.328C2.725 48.05 0 53.525 0 53.525s2.384-.366 5.008-.366c2.285 0 4.748.278 5.974 1.313C13.567 56.654 15 64 15 64s2.717-5 6.382-5c3.938 0 5.313 2.886 5.313 2.886l.774-6.271c1.475.247 2.986.385 4.531.385s3.056-.138 4.53-.386l.775 6.271s1.375-2.886 5.313-2.886C46.283 59 49 64 49 64s1.434-7.346 4.018-9.528c1.226-1.034 3.692-1.313 5.974-1.313c2.625 0 5.009.366 5.009.366c-.002-.002-2.726-5.476-2.726-9.427C61.274 40.642 64 38.771 64 38.771M55.147 6.81c.748 3.962.139 6.219-.427 7.354c-.743 1.5-1.774 1.935-2.181 2.052a1.7 1.7 0 0 1-.448.061c-.955 0-1.976-.73-2.319-1.664c-.436-1.162.417-2.219 1.205-2.901c1.722-1.488 3.103-3.24 4.17-4.902m-46.295.002c1.07 1.662 2.449 3.413 4.17 4.899c.789.683 1.641 1.739 1.208 2.901c-.347.934-1.365 1.664-2.32 1.664c-.154 0-.308-.021-.448-.061c-.408-.119-1.439-.552-2.183-2.052c-.562-1.135-1.172-3.39-.427-7.351M3.594 41.011l4.706.931q.577 1.052 1.24 2.043L2.805 50.72c.719-1.981 1.421-4.46 1.421-6.622a7.8 7.8 0 0 0-.632-3.087m12.082 19.495c-.73-2.475-1.961-5.691-3.725-7.18c-1.328-1.121-3.599-1.667-6.943-1.667c-.823 0-1.607.037-2.322.085l8.452-5.604a27.1 27.1 0 0 0 6.608 5.788l-1.915 8.414c-.05.055-.106.109-.155.164m9.909-1.87c-1.021-.634-2.397-1.136-4.203-1.136c-1.467 0-2.771.572-3.873 1.356l2.712-5.557a26.8 26.8 0 0 0 5.771 2.021zM7.5 29.001c0-3.837.895-7.466 2.474-10.702c.271.131.541.244.798.318q.564.16 1.139.158c3.452 0 6.799-4.166 3.697-7.966C19.953 6.891 25.702 4.5 32 4.5s12.047 2.39 16.392 6.31c-3.098 3.799.246 7.966 3.7 7.966q.575.001 1.139-.158c.256-.074.526-.187.796-.318a24.3 24.3 0 0 1 2.474 10.701C56.5 42.511 45.509 53.5 32 53.5S7.5 42.511 7.5 29.001M42.618 57.5c-1.804 0-3.181.502-4.203 1.136l-.408-3.314a27 27 0 0 0 5.772-2.021l2.712 5.557c-1.102-.786-2.406-1.358-3.873-1.358m16.373-5.841c-3.344 0-5.611.546-6.939 1.667c-1.764 1.488-2.998 4.705-3.728 7.18c-.05-.055-.105-.108-.156-.163l-1.915-8.413a27.2 27.2 0 0 0 6.608-5.788l8.452 5.603a34 34 0 0 0-2.322-.086m-4.531-7.672a27 27 0 0 0 1.24-2.045l4.706-.932a7.8 7.8 0 0 0-.632 3.088c0 2.103.68 4.586 1.412 6.614z"/>
                    <path d="M17.069 37.407c0 6.676 5.233 13.768 14.931 13.768c9.696 0 14.929-7.092 14.929-13.768v-.748h-29.86zm5.891.416h22.603l-2.261 4.133l-2.262-4.133l-2.259 4.133l-2.261-4.133l-2.262 4.133L32 37.823l-2.259 4.133l-2.261-4.133l-2.262 4.133zl-2.261 4.133l-2.26-4.133zm25.535-14.839a31 31 0 0 1 3.996-.634c-1.482-.969-3.306-1.357-5.094-1.35a12.8 12.8 0 0 0-5.232 1.195c-1.634.75-3.134 1.855-4.301 3.221c-1.163 1.367-2.029 3.026-2.222 4.792a29 29 0 0 1 3.057-2.68a.5.5 0 0 0 .042.229a5.457 5.457 0 0 0 7.248 2.639a5.456 5.456 0 0 0 2.634-7.249a.5.5 0 0 0-.128-.163m-23.236 4.773a.54.54 0 0 0 .039-.23a29 29 0 0 1 3.055 2.682c-.188-1.766-1.056-3.422-2.216-4.792a12.6 12.6 0 0 0-4.302-3.221a12.8 12.8 0 0 0-5.232-1.195c-1.791-.008-3.612.381-5.095 1.35c1.434.139 2.738.344 3.995.634a.5.5 0 0 0-.128.163a5.456 5.456 0 0 0 2.634 7.249a5.46 5.46 0 0 0 7.25-2.64"/>
                </svg>
                <h2 class="section-title">HORREUR</h2>
            </div>
            <a href="<?= route_url('catalogue', ['genre' => 'horror']) ?>" class="see-all-link">Voir Tout</a>
            <div class="section-nav">
                <button class="nav-arrow prev" onclick="scrollSection('horror', -1)">‹</button>
                <button class="nav-arrow next" onclick="scrollSection('horror', 1)">›</button>
            </div>
        </div>
        <div class="movies-carousel" id="horror-carousel">
            <?php foreach (array_slice($horrorMovies['results'] ?? [], 0, 10) as $movie): ?>
                <div class="movie-card">
                    <a href="<?= route_url('film', ['id' => $movie['id']]) ?>">
                        <img src="<?= TMDB_IMAGE_BASE_URL . $movie['poster_path'] ?>" alt="<?= htmlspecialchars($movie['title']) ?>" class="movie-poster">
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Science Fiction Section -->
    <section class="genre-section" id="scifi">
        <div class="section-header">
            <div class="section-title-group">
                <svg class="genre-icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 64 64" fill="currentColor">
                    <path d="M32 2C15.791 2 5 13.123 5 30.607C5 54.065 32 62 32 62s27-7.935 27-31.393C59 13.123 48.207 2 32 2m12.143 50.996C39.039 56.739 33.848 58.73 32 59.369c-4.086-1.415-24.5-9.46-24.5-28.762C7.5 14.748 17.117 4.5 32 4.5s24.5 10.248 24.5 26.107c0 8.841-4.158 16.374-12.357 22.389"/>
                    <path d="M42.303 45.452c-.311 0-.662.176-1.301.494c-1.527.765-4.371 2.187-9.004 2.187c-4.629 0-7.475-1.422-9.002-2.187c-.637-.318-.988-.494-1.299-.494c-.334 0-.672.229-.672.743c0 2.525 5.334 4.913 10.973 4.913c5.643 0 10.975-2.388 10.975-4.913c0-.513-.336-.743-.67-.743M26.084 37.71c1.633-1.607 1.688-4.359.43-7.146l.016.014c-.613-1.629-1.662-3.048-2.848-4.292a17.8 17.8 0 0 0-4.084-3.167a17.5 17.5 0 0 0-4.799-1.848c-1.666-.375-3.387-.502-5.074-.271c1.542.576 3.023 1.102 4.462 1.699c-.847.188-1.597.562-2.19 1.146c-2.482 2.443-1.338 7.524 2.553 11.354c3.889 3.826 9.055 4.951 11.534 2.511m-7.883-8.965c-1.99-1.247-3.076-3.086-2.42-4.106c.662-1.021 2.814-.837 4.807.409c1.996 1.247 3.078 3.086 2.418 4.106c-.656 1.022-2.807.838-4.805-.409m31.609-6.046c1.44-.597 2.921-1.122 4.462-1.699c-1.688-.23-3.404-.105-5.072.271c-1.67.377-3.299.992-4.799 1.847a17.7 17.7 0 0 0-4.082 3.166c-1.189 1.246-2.236 2.665-2.85 4.293l.015-.013c-1.258 2.786-1.204 5.539.429 7.146c2.48 2.442 7.646 1.317 11.535-2.512c3.891-3.828 5.033-8.91 2.555-11.354c-.595-.583-1.346-.958-2.193-1.145m-4.017 6.046c-1.994 1.246-4.146 1.43-4.807.407c-.656-1.02.426-2.858 2.42-4.104c1.996-1.247 4.146-1.431 4.809-.408c.656 1.02-.428 2.857-2.422 4.105"/>
                </svg>
                <h2 class="section-title">SCIENCE FICTION</h2>
            </div>
            <a href="<?= route_url('catalogue', ['genre' => 'scifi']) ?>" class="see-all-link">Voir Tout</a>
            <div class="section-nav">
                <button class="nav-arrow prev" onclick="scrollSection('scifi', -1)">‹</button>
                <button class="nav-arrow next" onclick="scrollSection('scifi', 1)">›</button>
            </div>
        </div>
        <div class="movies-carousel" id="scifi-carousel">
            <?php foreach (array_slice($scifiMovies['results'] ?? [], 0, 10) as $movie): ?>
                <div class="movie-card">
                    <a href="<?= route_url('film', ['id' => $movie['id']]) ?>">
                        <img src="<?= TMDB_IMAGE_BASE_URL . $movie['poster_path'] ?>" alt="<?= htmlspecialchars($movie['title']) ?>" class="movie-poster">
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>

<script>
// Scroll automatique vers les genres si route=catalogue
<?php if (isset($_GET['route']) && $_GET['route'] === 'catalogue'): ?>
window.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        document.getElementById('comedy')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }, 100);
});
<?php endif; ?>
</script>

<?php require __DIR__ . '/../includes/footer.php'; ?>

