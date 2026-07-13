<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

$produits = $pdo->query('SELECT p.*, f.nom_entreprise AS fournisseur, c.nom AS categorie 
                         FROM produits p 
                         LEFT JOIN fournisseurs f ON f.id = p.id_fournisseur 
                         LEFT JOIN categories c ON c.id = p.id_categorie')->fetchAll();

$headers = ['ID', 'Nom', 'Description', 'Prix', 'Stock', 'Fournisseur', 'Catégorie', 'Date'];
$data = [];
foreach ($produits as $p) {
    $data[] = [$p['id'], $p['nom'], $p['description'], $p['prix'], $p['stock'], $p['fournisseur'] ?? '', $p['categorie'] ?? '', $p['date_creation']];
}
exportCSV($data, 'produits.csv', $headers);
