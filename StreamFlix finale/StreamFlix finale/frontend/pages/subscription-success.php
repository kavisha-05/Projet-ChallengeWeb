<?php
// Session déjà démarrée dans index.php
require_once __DIR__ . '/../includes/functions.php';

$plan = $_SESSION['subscription_plan'] ?? '';
$planNames = [
    'standard-ads' => 'Standard avec pub',
    'standard' => 'Standard',
    'premium' => 'Premium'
];
$planName = $planNames[$plan] ?? 'Standard';

require __DIR__ . '/../includes/header.php';
?>

<div class="subscription-success-container">
    <div class="success-card">
        <div class="success-icon">
            <svg width="80" height="80" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
            </svg>
        </div>
        <h1 class="success-title">Abonnement confirmé !</h1>
        <p class="success-message">Votre abonnement <strong><?= htmlspecialchars($planName) ?></strong> a été activé avec succès.</p>
        
        <div class="success-details">
            <p>Vous pouvez maintenant profiter de tous les contenus disponibles sur STREAMFLIX.</p>
            <p>Un email de confirmation a été envoyé à votre adresse.</p>
        </div>
        
        <div class="success-actions">
            <a href="<?= route_url() ?>" class="success-btn">Commencer à regarder</a>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>

