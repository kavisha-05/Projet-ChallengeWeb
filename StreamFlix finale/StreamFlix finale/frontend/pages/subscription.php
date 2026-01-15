<?php
require __DIR__ . '/../includes/header.php';
?>

<div class="subscription-container">
    <h1 class="subscription-title">Sélectionnez l'offre qui vous convient</h1>
    
    <div class="subscription-plans">
        <div class="plan-card plan-standard-ads">
            <div class="plan-header">
                <h2>Standard avec pub</h2>
                <p class="plan-quality">1080p</p>
            </div>
            <div class="plan-price">7,99 €</div>
            <p class="plan-period">Abonnement mensuel</p>
            <div class="plan-details">
                <p><strong>Qualité vidéo et audio :</strong> Bonne</p>
                <p><strong>Appareils pris en charge :</strong> TV, ordinateur, smartphone, tablette</p>
                <p><strong>Pubs :</strong> Moins que ce que vous pensez</p>
            </div>
            <button class="plan-select-btn" onclick="selectPlan('standard-ads', 7.99)">Sélectionner</button>
        </div>
        
        <div class="plan-card plan-standard">
            <div class="plan-header">
                <h2>Standard</h2>
                <p class="plan-quality">1080p</p>
            </div>
            <div class="plan-price">14,99 €</div>
            <p class="plan-period">Abonnement mensuel</p>
            <div class="plan-details">
                <p><strong>Qualité vidéo et audio :</strong> Bonne</p>
                <p><strong>Appareils pris en charge :</strong> TV, ordinateur, smartphone, tablette</p>
                <p><strong>Pubs :</strong> Sans pub</p>
            </div>
            <button class="plan-select-btn" onclick="selectPlan('standard', 14.99)">Sélectionner</button>
        </div>
        
        <div class="plan-card plan-premium">
            <div class="plan-header">
                <h2>Premium</h2>
                <p class="plan-quality">4K +HDR</p>
            </div>
            <div class="plan-price">21,99 €</div>
            <p class="plan-period">Abonnement mensuel</p>
            <div class="plan-details">
                <p><strong>Qualité vidéo et audio :</strong> Optimale</p>
                <p><strong>Appareils pris en charge :</strong> TV, ordinateur, smartphone, tablette</p>
                <p><strong>Pubs :</strong> Sans pub</p>
            </div>
            <button class="plan-select-btn" onclick="selectPlan('premium', 21.99)">Sélectionner</button>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>

