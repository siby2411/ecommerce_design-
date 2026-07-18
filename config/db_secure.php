<?php
/**
 * Configuration sécurisée pour les modules d'email
 * Ce fichier est indépendant de config/db.php pour ne pas casser les autres modules
 */

define('DB_HOST_SECURE', 'localhost');
define('DB_NAME_SECURE', 'ecommerce_design');
define('DB_USER_SECURE', 'root');
define('DB_PASS_SECURE', '');
define('DB_CHARSET_SECURE', 'utf8mb4');

// Fonction getPDO_Secure() pour obtenir la connexion
function getPDO_Secure() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $dsn = 'mysql:host=' . DB_HOST_SECURE . ';dbname=' . DB_NAME_SECURE . ';charset=' . DB_CHARSET_SECURE;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $pdo = new PDO($dsn, DB_USER_SECURE, DB_PASS_SECURE, $options);
        } catch (PDOException $e) {
            die('Erreur de connexion sécurisée : ' . htmlspecialchars($e->getMessage()));
        }
    }
    return $pdo;
}
