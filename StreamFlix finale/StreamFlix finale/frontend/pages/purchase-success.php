<?php
// Session déjà démarrée dans index.php
require_once __DIR__ . '/../includes/functions.php';

$purchaseId = $_SESSION['last_purchase_id'] ?? null;
$purchaseDate = $_SESSION['last_purchase_date'] ?? null;

require __DIR__ . '/../includes/header.php';
?>

<div class="purchase-success-container">
    <div class="success-card">
        <div class="success-icon">
            <svg width="80" height="80" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
            </svg>
        </div>
        <h1 class="success-title">Commande confirmée !</h1>
        <p class="success-message">Votre commande a été passée avec succès.</p>
        
        <?php if ($purchaseId): ?>
            <div class="purchase-details">
                <p><strong>Numéro de commande :</strong> #<?= str_pad($purchaseId, 8, '0', STR_PAD_LEFT) ?></p>
                <?php if ($purchaseDate): ?>
                    <p><strong>Date :</strong> <?= date('d/m/Y à H:i', strtotime($purchaseDate)) ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <div class="success-details">
            <p>Vous pouvez maintenant regarder vos films achetés.</p>
            <p>Un email de confirmation a été envoyé à votre adresse.</p>
        </div>
        
        <div class="success-actions">
            <a href="<?= route_url('purchases') ?>" class="success-btn">Voir mes commandes</a>
            <a href="<?= route_url() ?>" class="success-btn secondary">Retour à l'accueil</a>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>

