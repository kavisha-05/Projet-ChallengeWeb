<?php
// Session déjà démarrée dans index.php
require_once __DIR__ . '/../../backend/config.php';
require_once __DIR__ . '/../../backend/vendor/autoload.php';
require_once __DIR__ . '/../includes/functions.php';

// Récupérer le panier depuis la session
// Note: Pour l'instant, on utilise la session car les films TMDB ne sont pas encore dans la base de données
$cart = $_SESSION['cart'] ?? [];
$total = 0;

// Calculer le total
foreach ($cart as &$item) {
    if (!isset($item['price'])) {
        $item['price'] = 9.99; // Prix par défaut
    }
    $total += $item['price'] * ($item['quantity'] ?? 1);
}

// Rediriger vers le panier si vide
if (empty($cart)) {
    header('Location: ' . route_url('cart'));
    exit;
}

require __DIR__ . '/../includes/header.php';
?>

<div class="checkout-container">
    <div class="checkout-content">
        <div class="checkout-items">
            <h2 class="checkout-title">RÉCAPITULATIF DE LA COMMANDE</h2>
            
            <div class="checkout-items-list">
                <?php foreach ($cart as $item): ?>
                    <div class="checkout-item">
                        <?php if ($item['poster']): ?>
                            <img src="<?= htmlspecialchars($item['poster']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" class="checkout-item-poster">
                        <?php endif; ?>
                        <div class="checkout-item-info">
                            <h3><?= htmlspecialchars($item['title']) ?></h3>
                            <p class="checkout-item-price"><?= number_format($item['price'], 2, ',', ' ') ?> €</p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="checkout-total">
                <div class="total-row">
                    <span>Sous-total :</span>
                    <span><?= number_format($total, 2, ',', ' ') ?> €</span>
                </div>
                <div class="total-row total-final">
                    <span>Total :</span>
                    <span><?= number_format($total, 2, ',', ' ') ?> €</span>
                </div>
            </div>
        </div>
        
        <div class="checkout-payment">
            <h2 class="checkout-title">INFORMATIONS DE PAIEMENT</h2>
            
            <form method="POST" action="<?= route_url('api', ['action' => 'processPurchase']) ?>" class="checkout-form">
                <input type="hidden" name="total" value="<?= $total ?>">
                <input type="hidden" name="items" value="<?= htmlspecialchars(json_encode(array_map(function($item) { return $item['id']; }, $cart))) ?>">
                
                <div class="form-group">
                    <label for="card_number">Numéro de carte</label>
                    <input type="text" id="card_number" name="card_number" placeholder="1234 5678 9012 3456" maxlength="19" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="expiry">Date d'expiration</label>
                        <input type="text" id="expiry" name="expiry" placeholder="MM/AA" maxlength="5" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="cvv">CVV</label>
                        <input type="text" id="cvv" name="cvv" placeholder="123" maxlength="3" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="card_name">Nom sur la carte</label>
                    <input type="text" id="card_name" name="card_name" placeholder="Nom complet" required>
                </div>
                
                <div class="checkout-actions">
                    <a href="<?= route_url('cart') ?>" class="cancel-checkout-btn">Retour au panier</a>
                    <button type="submit" class="confirm-purchase-btn">Confirmer et payer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Format card number
document.getElementById('card_number')?.addEventListener('input', function(e) {
    let value = e.target.value.replace(/\s/g, '');
    let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
    e.target.value = formattedValue;
});

// Format expiry date
document.getElementById('expiry')?.addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length >= 2) {
        value = value.substring(0, 2) + '/' + value.substring(2, 4);
    }
    e.target.value = value;
});
</script>

<?php require __DIR__ . '/../includes/footer.php'; ?>

