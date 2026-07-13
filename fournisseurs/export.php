<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

$fournisseurs = $pdo->query('SELECT * FROM fournisseurs ORDER BY nom_entreprise')->fetchAll();
$headers = ['ID', 'Entreprise', 'Contact', 'Email', 'Téléphone', 'Adresse', 'Ville', 'Date'];
$data = [];
foreach ($fournisseurs as $f) {
    $data[] = [$f['id'], $f['nom_entreprise'], $f['contact_nom'], $f['email'], $f['telephone'], $f['adresse'], $f['ville'], $f['date_creation']];
}
exportCSV($data, 'fournisseurs.csv', $headers);
