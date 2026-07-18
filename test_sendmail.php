<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!-- Début test -->";

$pageTitle = 'Test';
require_once __DIR__ . '/includes/header.php';

echo "<!-- Header chargé -->";

require_once __DIR__ . '/cron/sendgrid_connect.php';

echo "<!-- SendGrid chargé -->";

$pdo = getPDO();
echo "<!-- PDO initialisé -->";

$clients = $pdo->query("SELECT id, nom, email FROM clients LIMIT 5")->fetchAll();
echo "<!-- Clients récupérés: " . count($clients) . " -->";

echo "<h1>Test OK</h1>";
echo "<pre>";
print_r($clients);
echo "</pre>";
