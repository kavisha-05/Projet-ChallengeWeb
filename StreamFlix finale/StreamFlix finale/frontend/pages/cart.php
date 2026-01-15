<?php
// Session déjà démarrée dans index.php
require_once __DIR__ . '/../../backend/config.php';
require_once __DIR__ . '/../../backend/vendor/autoload.php';
require_once __DIR__ . '/../includes/functions.php';

use Streamflix\CartModel;

// Récupérer le panier depuis la session
// Note: Pour l'instant, on utilise la session car les films TMDB ne sont pas encore dans la base de données
$cart = $_SESSION['cart'] ?? [];

// Si l'utilisateur est connecté et qu'on veut utiliser la DB plus tard, on peut synchroniser
// Pour l'instant, on utilise uniquement la session pour tous les utilisateurs

require __DIR__ . '/../includes/header.php';
?>

<div class="cart-container">
    <div class="cart-content">
        <div class="cart-items">
            <h2 class="cart-title">PANIER</h2>
            
            <?php if (empty($cart)): ?>
                <div class="empty-cart">
                    <p>Votre panier est vide.</p>
                    <a href="<?= route_url() ?>" class="continue-shopping">Continuer vos achats</a>
                </div>
            <?php else: ?>
                <?php foreach ($cart as $item): ?>
                    <div class="cart-item" data-id="<?= $item['id'] ?>">
                        <img src="<?= htmlspecialchars($item['poster']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" class="cart-item-poster">
                        <div class="cart-item-info">
                            <h3><?= htmlspecialchars($item['title']) ?></h3>
                            <p class="cart-item-format">Format: Streaming</p>
                            <div class="cart-item-actions">
                                <button class="quantity-btn" onclick="updateQuantity(<?= $item['id'] ?>, 1)">+ 1</button>
                                <span class="quantity">Quantité: <?= $item['quantity'] ?? 1 ?></span>
                                <div class="item-links">
                                    <a href="#" onclick="removeFromCart(<?= $item['id'] ?>); return false;">Supprimer</a>
                                    <span>|</span>
                                    <a href="#">Mettre de côté</a>
                                    <span>|</span>
                                    <a href="#">Partager</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <div class="cart-subtotal">
                    <p>Sous-total (<?= count($cart) ?> article<?= count($cart) > 1 ? 's' : '' ?>) : <?= number_format(count($cart) * 9.99, 2, ',', ' ') ?> €</p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="cart-summary">
            <h3>Résumé de la commande</h3>
            <div class="summary-subtotal">
                <p>Sous-total (<?= count($cart) ?> article<?= count($cart) > 1 ? 's' : '' ?>) : <?= number_format(count($cart) * 9.99, 2, ',', ' ') ?> €</p>
            </div>
            <?php if (!empty($cart)): ?>
                <img src="<?= htmlspecialchars($cart[0]['poster']) ?>" alt="Aperçu" class="summary-poster">
            <?php endif; ?>
            <div class="cart-summary-actions">
                <a href="<?= route_url() ?>" class="continue-shopping-btn">Continuer mes achats</a>
                <a href="<?= route_url('checkout') ?>" class="checkout-btn" <?= empty($cart) ? 'style="pointer-events: none; opacity: 0.5;"' : '' ?>>Passer la commande</a>
            </div>
        </div>
    </div>
</div>

<script>
function removeFromCart(id) {
    fetch('<?= route_url('api', ['action' => 'removeFromCart']) ?>&id=' + id, {
        method: 'POST'
    }).then(() => {
        location.reload();
    });
}

function updateQuantity(id, change) {
    fetch('<?= route_url('api', ['action' => 'updateCart']) ?>&id=' + id + '&change=' + change, {
        method: 'POST'
    }).then(() => {
        location.reload();
    });
}
</script>

<?php require __DIR__ . '/../includes/footer.php'; ?>

