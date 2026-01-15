# Connexion à PostgreSQL - Guide étape par étape

## Étape 1 : Installer l'extension PostgreSQL pour PHP

### Windows
L'extension `pdo_pgsql` doit être activée dans votre `php.ini` :
1. Ouvrez votre fichier `php.ini`
2. Cherchez la ligne `;extension=pdo_pgsql`
3. Décommentez-la (enlevez le `;`) : `extension=pdo_pgsql`
4. Redémarrez votre serveur PHP

### Vérifier l'installation
```bash
php -m | findstr pdo_pgsql
```

## Étape 2 : Configurer les identifiants de connexion

Éditez le fichier `backend/config.php` et modifiez les constantes :

```php
define('DB_HOST', 'localhost');
define('DB_PORT', '5432');
define('DB_NAME', 'streamflix');
define('DB_USER', 'votre_utilisateur'); // Par défaut 'postgres'
define('DB_PASS', 'votre_mot_de_passe'); // Votre mot de passe PostgreSQL
```

## Étape 3 : Créer la base de données (si pas déjà fait)

Connectez-vous à PostgreSQL et exécutez :

```sql
CREATE DATABASE streamflix;
```

## Étape 4 : Créer les tables

Exécutez le script SQL dans `database/schema.sql` :

```bash
psql -U postgres -d streamflix -f database/schema.sql
```

Ou via pgAdmin :
1. Ouvrez pgAdmin
2. Connectez-vous à votre serveur
3. Sélectionnez la base `streamflix`
4. Exécutez le contenu de `database/schema.sql`

## Étape 5 : Tester la connexion

Créez un fichier de test `test-db.php` :

```php
<?php
require_once __DIR__ . '/backend/vendor/autoload.php';
require_once __DIR__ . '/backend/config.php';

use Streamflix\Database;

try {
    $db = Database::getInstance();
    echo "Connexion réussie à PostgreSQL !";
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
```

Exécutez : `php test-db.php`

## Utilisation dans le code

```php
use Streamflix\Database;

$db = Database::getInstance();

// Exemple : Récupérer un utilisateur
$user = $db->fetchOne("SELECT * FROM users WHERE username = ?", ['john']);

// Exemple : Insérer un utilisateur
$db->query(
    "INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)",
    ['john', 'john@example.com', password_hash('password', PASSWORD_DEFAULT)]
);
```

## Commandes utiles PostgreSQL

```bash
# Se connecter à PostgreSQL
psql -U postgres -d streamflix

# Lister les tables
\dt

# Voir la structure d'une table
\d users

# Quitter
\q
```

