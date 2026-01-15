<?php
// Session déjà démarrée dans index.php
require_once __DIR__ . '/../includes/functions.php';

$plan = $_GET['plan'] ?? '';
$price = $_GET['price'] ?? '';

$planNames = [
    'standard-ads' => 'Standard avec pub',
    'standard' => 'Standard',
    'premium' => 'Premium'
];

$planName = $planNames[$plan] ?? 'Standard';

require __DIR__ . '/../includes/header.php';
?>

<div class="subscription-confirm-container">
    <div class="confirm-card">
        <h1 class="confirm-title">Confirmer votre abonnement</h1>
        
        <div class="confirm-plan-info">
            <h2><?= htmlspecialchars($planName) ?></h2>
            <p class="confirm-price"><?= number_format($price, 2, ',', ' ') ?> € / mois</p>
        </div>
        
        <div class="confirm-details">
            <h3>Récapitulatif</h3>
            <div class="confirm-summary">
                <div class="summary-row">
                    <span>Plan sélectionné :</span>
                    <span><?= htmlspecialchars($planName) ?></span>
                </div>
                <div class="summary-row">
                    <span>Prix mensuel :</span>
                    <span><?= number_format($price, 2, ',', ' ') ?> €</span>
                </div>
                <div class="summary-row total">
                    <span>Total à payer :</span>
                    <span><?= number_format($price, 2, ',', ' ') ?> €</span>
                </div>
            </div>
        </div>
        
        <form method="POST" action="<?= route_url('api', ['action' => 'subscribe']) ?>" class="confirm-form">
            <input type="hidden" name="plan" value="<?= htmlspecialchars($plan) ?>">
            <input type="hidden" name="price" value="<?= htmlspecialchars($price) ?>">
            
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
            
            <div class="confirm-actions">
                <a href="<?= route_url('subscription') ?>" class="cancel-btn">Annuler</a>
                <button type="submit" class="confirm-submit-btn">Confirmer et payer</button>
            </div>
        </form>
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

