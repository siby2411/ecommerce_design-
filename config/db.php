<?php
/**
 * OMEGA INFORMATIQUE CONSULTING
 * Connexion a la base de donnees (PDO / MariaDB)
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'ecommerce_design');
define('DB_USER', 'root');
define('DB_PASS', '');  // Laissez vide si pas de mot de passe
define('DB_CHARSET', 'utf8mb4');

// BASE_URL vide pour le serveur PHP intégré (php -S)
define('BASE_URL', '');
define('UPLOAD_DIR', __DIR__ . '/../assets/uploads/produits/');
define('UPLOAD_URL', BASE_URL . '/assets/uploads/produits/');

try {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    die('Erreur de connexion : ' . htmlspecialchars($e->getMessage()));
}
