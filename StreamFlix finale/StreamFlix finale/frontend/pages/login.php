<?php
require __DIR__ . '/../includes/header.php';
?>

<div class="auth-container">
    <div class="auth-header">
        <img src="<?= asset_url('images/logo.png') ?>" alt="STREAMFLIX" class="logo-img">
        <h1 class="auth-title">CONNECTEZ-VOUS</h1>
    </div>
    
    <div class="auth-form-container">
        <form class="auth-form" method="POST" action="<?= route_url('api', ['action' => 'login']) ?>">
            <?php if (isset($_GET['error'])): ?>
                <div class="auth-error">
                    <?php if ($_GET['error'] === 'invalid'): ?>
                        Email ou mot de passe incorrect.
                    <?php elseif ($_GET['error'] === 'missing'): ?>
                        Veuillez remplir tous les champs.
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" placeholder="Mot de passe" required>
            </div>
            
            <div class="form-links">
                <a href="#" class="forgot-password">Mot de passe oublié ?</a>
                <a href="<?= route_url('register') ?>" class="no-account">Vous n'avez pas de compte ?</a>
            </div>
            
            <button type="submit" class="auth-submit-btn">Se connecter</button>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>

