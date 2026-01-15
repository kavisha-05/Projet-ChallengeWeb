// Search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const searchClear = document.getElementById('search-clear');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            if (this.value.length > 0) {
                searchClear.style.display = 'block';
            } else {
                searchClear.style.display = 'none';
            }
        });
        
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const query = this.value.trim();
                if (query) {
                    const baseUrl = window.location.origin + window.location.pathname.replace(/\/[^\/]*$/, '/');
                    window.location.href = baseUrl + 'index.php?route=search&q=' + encodeURIComponent(query);
                }
            }
        });
    }
    
    if (searchClear) {
        searchClear.addEventListener('click', function() {
            searchInput.value = '';
            this.style.display = 'none';
        });
    }
});

// Scroll section
function scrollSection(sectionId, direction) {
    const carousel = document.getElementById(sectionId + '-carousel') || document.getElementById(sectionId);
    if (carousel) {
        const scrollAmount = 300;
        carousel.scrollBy({
            left: direction * scrollAmount,
            behavior: 'smooth'
        });
    }
}

// Add to cart
function addToCart(id, title, poster) {
    const baseUrl = window.location.origin + window.location.pathname.replace(/\/[^\/]*$/, '/');
    fetch(baseUrl + 'index.php?route=api&action=addToCart', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'id=' + id + '&title=' + encodeURIComponent(title) + '&poster=' + encodeURIComponent(poster)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update cart count
            const cartCount = document.getElementById('cart-count');
            if (cartCount) {
                cartCount.textContent = data.count;
            }
            alert('Film ajouté au panier !');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// Handle catalogue link scroll
document.addEventListener('DOMContentLoaded', function() {
    // Handle hash navigation (pour le lien Catalogue)
    if (window.location.hash) {
        const target = document.querySelector(window.location.hash);
        if (target) {
            setTimeout(() => {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 100);
        }
    }
});

// Change hero movie
function changeHeroMovie(index, movie) {
    const heroSection = document.getElementById('hero-section');
    if (!heroSection) return;
    
    const backdrop = heroSection.querySelector('.hero-backdrop');
    const title = heroSection.querySelector('.hero-title');
    const rating = heroSection.querySelector('.hero-rating');
    const date = heroSection.querySelector('.hero-date');
    const overview = heroSection.querySelector('.hero-overview');
    const playBtn = heroSection.querySelector('.hero-play-btn');
    const thumbnails = heroSection.querySelectorAll('.hero-thumbnail');
    
    // Update backdrop
    if (backdrop && movie.backdrop_path) {
        backdrop.style.backgroundImage = `url('https://image.tmdb.org/t/p/original${movie.backdrop_path}')`;
    }
    
    // Update content
    if (title) title.textContent = movie.title;
    if (rating) {
        rating.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg> ' + (movie.vote_average ? movie.vote_average.toFixed(1) : '0.0');
    }
    if (date && movie.release_date) {
        const releaseDate = new Date(movie.release_date);
        const months = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];
        const day = releaseDate.getDate();
        const month = months[releaseDate.getMonth()];
        const year = releaseDate.getFullYear();
        date.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z"/></svg> ' + day + ' ' + month + ' ' + year;
    }
    if (overview) {
        overview.textContent = (movie.overview || '').substring(0, 200) + '...';
    }
    if (playBtn) {
        const baseUrl = window.location.origin + window.location.pathname.replace(/\/[^\/]*$/, '/');
        playBtn.onclick = function() {
            window.location.href = baseUrl + 'index.php?route=film&id=' + movie.id;
        };
    }
    
    // Update active thumbnail
    thumbnails.forEach((thumb, i) => {
        if (i === index) {
            thumb.classList.add('active');
        } else {
            thumb.classList.remove('active');
        }
    });
}

// Select subscription plan
function selectPlan(plan, price) {
    const baseUrl = window.location.origin + window.location.pathname.replace(/\/[^\/]*$/, '/');
    window.location.href = baseUrl + 'index.php?route=subscription-confirm&plan=' + plan + '&price=' + price;
}
