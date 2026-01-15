# Étapes pour connecter PostgreSQL

## Étape 1 : Vérifier l'extension PostgreSQL dans PHP

Ouvrez un terminal et exécutez :
```bash
php -m | findstr pdo_pgsql
```

Si rien ne s'affiche, vous devez activer l'extension :
1. Trouvez votre fichier `php.ini` : `php --ini`
2. Ouvrez `php.ini` avec un éditeur
3. Cherchez `;extension=pdo_pgsql` et enlevez le `;` pour avoir `extension=pdo_pgsql`
4. Redémarrez votre serveur PHP

## Étape 2 : Configurer les identifiants

Éditez `backend/config.php` et modifiez ces lignes (vers la fin du fichier) :

```php
define('DB_USER', 'postgres'); // Votre nom d'utilisateur PostgreSQL
define('DB_PASS', 'votre_mot_de_passe'); // Votre mot de passe PostgreSQL
```

## Étape 3 : Créer la base de données (si nécessaire)

Ouvrez pgAdmin ou un terminal PostgreSQL et exécutez :

```sql
CREATE DATABASE streamflix;
```

## Étape 4 : Créer les tables

Dans pgAdmin ou via psql, exécutez le contenu du fichier `database/schema.sql`

**Via pgAdmin :**
1. Clic droit sur la base `streamflix` → Query Tool
2. Ouvrez `database/schema.sql`
3. Exécutez (F5)

**Via terminal :**
```bash
psql -U postgres -d streamflix -f database/schema.sql
```

## Étape 5 : Tester la connexion

Exécutez dans le terminal :
```bash
php test-db.php
```

Si vous voyez "✓ Connexion réussie", c'est bon !

## Utilisation dans votre code

```php
use Streamflix\Database;

$db = Database::getInstance();

// Exemple
$users = $db->fetchAll("SELECT * FROM users");
```

