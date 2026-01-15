<?php
// Session déjà démarrée dans index.php
require_once __DIR__ . '/../../backend/config.php';
require_once __DIR__ . '/../../backend/vendor/autoload.php';
require_once __DIR__ . '/../includes/functions.php';

use Streamflix\PurchaseModel;

if (!isset($_SESSION['user'])) {
    header('Location: ' . route_url('login'));
    exit;
}

$purchaseModel = new PurchaseModel();
$purchases = $purchaseModel->getUserPurchases($_SESSION['user']['id']);

// Grouper les achats par date de commande
$purchasesByDate = [];
foreach ($purchases as $purchase) {
    $date = date('Y-m-d', strtotime($purchase['date_achat']));
    if (!isset($purchasesByDate[$date])) {
        $purchasesByDate[$date] = [
            'date' => $date,
            'purchases' => [],
            'total' => 0
        ];
    }
    $purchasesByDate[$date]['purchases'][] = $purchase;
    $purchasesByDate[$date]['total'] += floatval($purchase['prix']);
}

// Trier par date décroissante
krsort($purchasesByDate);

require __DIR__ . '/../includes/header.php';
?>

<div class="purchases-container">
    <h1 class="purchases-title">MES COMMANDES</h1>
    
    <?php if (empty($purchasesByDate)): ?>
        <div class="empty-purchases">
            <p>Vous n'avez pas encore passé de commande.</p>
            <a href="<?= route_url() ?>" class="browse-btn">Parcourir les films</a>
        </div>
    <?php else: ?>
        <div class="purchases-list">
            <?php foreach ($purchasesByDate as $order): ?>
                <div class="purchase-order">
                    <div class="order-header">
                        <div class="order-info">
                            <h2>Commande #<?= str_pad($order['purchases'][0]['id_achat'], 8, '0', STR_PAD_LEFT) ?></h2>
                            <p class="order-date"><?= date('d/m/Y à H:i', strtotime($order['date'])) ?></p>
                        </div>
                        <div class="order-total">
                            <span class="total-label">Total :</span>
                            <span class="total-amount"><?= number_format($order['total'], 2, ',', ' ') ?> €</span>
                        </div>
                    </div>
                    
                    <div class="order-items">
                        <?php foreach ($order['purchases'] as $purchase): ?>
                            <div class="order-item">
                                <?php if ($purchase['affiche']): ?>
                                    <img src="<?= htmlspecialchars($purchase['affiche']) ?>" alt="<?= htmlspecialchars($purchase['titre']) ?>" class="order-item-poster">
                                <?php endif; ?>
                                <div class="order-item-info">
                                    <h3><?= htmlspecialchars($purchase['titre']) ?></h3>
                                    <p class="order-item-price"><?= number_format($purchase['prix'], 2, ',', ' ') ?> €</p>
                                    <a href="<?= route_url('watch', ['id' => $purchase['id_film']]) ?>" class="watch-btn">Regarder</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>

