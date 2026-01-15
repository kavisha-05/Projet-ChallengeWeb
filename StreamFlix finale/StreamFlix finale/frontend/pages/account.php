<?php
// Session déjà démarrée dans index.php
require_once __DIR__ . '/../../backend/config.php';
require_once __DIR__ . '/../../backend/vendor/autoload.php';
require_once __DIR__ . '/../includes/functions.php';

use Streamflix\UserModel;
use Streamflix\SubscriptionModel;

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: ' . route_url('login'));
    exit;
}

$user = $_SESSION['user'];
$userModel = new UserModel();
$subscriptionModel = new SubscriptionModel();

// Récupérer les informations complètes de l'utilisateur
$userInfo = $userModel->getUserById($user['id']);

// Récupérer l'abonnement actif
$activeSubscription = $subscriptionModel->getActiveSubscription($user['id']);

require __DIR__ . '/../includes/header.php';
?>

<div class="account-container">
    <div class="account-content">
        <h1 class="account-title">MON COMPTE</h1>
        
        <div class="account-section">
            <h2 class="section-title">Informations personnelles</h2>
            <div class="account-info">
                <div class="info-row">
                    <span class="info-label">Nom :</span>
                    <span class="info-value"><?= htmlspecialchars($user['nom']) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email :</span>
                    <span class="info-value"><?= htmlspecialchars($user['email']) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Rôle :</span>
                    <span class="info-value"><?= htmlspecialchars(ucfirst($user['role'])) ?></span>
                </div>
            </div>
        </div>
        
        <?php if ($activeSubscription): ?>
        <div class="account-section">
            <h2 class="section-title">Abonnement</h2>
            <div class="subscription-info">
                <div class="info-row">
                    <span class="info-label">Type d'abonnement :</span>
                    <span class="info-value subscription-type"><?= htmlspecialchars(ucfirst($activeSubscription['type'])) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Date de début :</span>
                    <span class="info-value"><?= date('d/m/Y', strtotime($activeSubscription['date_debut'])) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Date de fin :</span>
                    <span class="info-value"><?= date('d/m/Y', strtotime($activeSubscription['date_fin'])) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Statut :</span>
                    <span class="info-value status-active">Actif</span>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="account-section">
            <h2 class="section-title">Abonnement</h2>
            <div class="no-subscription">
                <p>Vous n'avez pas d'abonnement actif.</p>
                <a href="<?= route_url('subscription') ?>" class="subscribe-btn">S'abonner</a>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="account-actions">
            <a href="<?= route_url('purchases') ?>" class="account-btn">Mes commandes</a>
            <a href="<?= route_url('api', ['action' => 'logout']) ?>" class="account-btn logout-btn">Se déconnecter</a>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>

