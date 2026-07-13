<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

$factures = $pdo->query("
    SELECT f.numero, f.date_facture, f.statut, f.total, f.tva, f.montant_tva, 
           c.nom AS client_nom, c.prenom AS client_prenom
    FROM factures f
    LEFT JOIN clients c ON c.id = f.id_client
    ORDER BY f.date_creation DESC
")->fetchAll();

$headers = ['Numéro', 'Client', 'Date', 'Statut', 'Total TTC', 'TVA %', 'Montant TVA'];
$data = [];
foreach ($factures as $f) {
    $data[] = [
        $f['numero'],
        trim($f['client_prenom'] . ' ' . $f['client_nom']),
        $f['date_facture'],
        $f['statut'],
        $f['total'],
        $f['tva'],
        $f['montant_tva']
    ];
}
exportCSV($data, 'factures.csv', $headers);
