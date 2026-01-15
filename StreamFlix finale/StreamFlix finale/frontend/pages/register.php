<?php
require __DIR__ . '/../includes/header.php';
?>

<div class="auth-container">
    <div class="auth-header">
        <img src="<?= asset_url('images/logo.png') ?>" alt="STREAMFLIX" class="logo-img">
        <h1 class="auth-title">INSCRIVEZ-VOUS</h1>
    </div>
    
    <div class="auth-form-container">
        <form class="auth-form" method="POST" action="<?= route_url('api', ['action' => 'register']) ?>">
            <?php if (isset($_GET['error'])): ?>
                <div class="auth-error">
                    <?php if ($_GET['error'] === 'password'): ?>
                        Les mots de passe ne correspondent pas.
                    <?php elseif ($_GET['error'] === 'email_exists'): ?>
                        Cet email est déjà utilisé.
                    <?php elseif ($_GET['error'] === 'database'): ?>
                        Erreur lors de l'inscription. Veuillez réessayer.
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <div class="form-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" placeholder="Nom d'utilisateur" required>
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" placeholder="Mot de passe" required>
            </div>
            
            <div class="form-group">
                <label for="password_confirm">Confirmation du mot de passe</label>
                <input type="password" id="password_confirm" name="password_confirm" placeholder="Mot de passe" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Email" required>
            </div>
            
            <button type="submit" class="auth-submit-btn">Créer un compte</button>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>

