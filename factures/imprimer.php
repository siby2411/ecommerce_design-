<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    die('Facture non spécifiée.');
}

$stmt = $pdo->prepare("
    SELECT f.*, c.nom AS client_nom, c.prenom AS client_prenom, c.adresse, c.telephone, c.email
    FROM factures f
    LEFT JOIN clients c ON c.id = f.id_client
    WHERE f.id = ?
");
$stmt->execute([$id]);
$facture = $stmt->fetch();
if (!$facture) die('Facture introuvable.');

$lignes = $pdo->prepare('SELECT * FROM facture_lignes WHERE id_facture = ?');
$lignes->execute([$id]);
$lignes = $lignes->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture <?= clean($facture['numero']) ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; background: #fff; }
        .invoice-header { display: flex; justify-content: space-between; border-bottom: 2px solid #0b1220; padding-bottom: 20px; }
        .company { font-weight: bold; font-size: 22px; color: #0b1220; }
        .company small { display: block; font-size: 13px; color: #555; }
        .client-info { margin: 20px 0; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        th { background: #f0f2f5; }
        .total { font-size: 18px; font-weight: bold; text-align: right; }
        .footer { margin-top: 30px; border-top: 1px solid #ccc; padding-top: 10px; text-align: center; font-size: 12px; color: #777; }
        .no-print { display: none; }
    </style>
</head>
<body>
    <div class="invoice-header">
        <div>
            <div class="company">OMEGA INFORMATIQUE CONSULTING</div>
            <small>E-commerce matériel informatique</small>
            <small>contact@omega-consulting.com</small>
        </div>
        <div style="text-align:right;">
            <h2>FACTURE</h2>
            <p><strong>N°</strong> <?= clean($facture['numero']) ?></p>
            <p><strong>Date</strong> <?= formatDate($facture['date_facture']) ?></p>
        </div>
    </div>

    <div class="client-info">
        <strong>Client</strong><br>
        <?= clean(trim($facture['client_prenom'] . ' ' . $facture['client_nom'])) ?><br>
        <?= clean($facture['adresse']) ?><br>
        Tél : <?= clean($facture['telephone']) ?> - Email : <?= clean($facture['email']) ?>
    </div>

    <table>
        <thead>
            <tr><th>Désignation</th><th>Qté</th><th>Prix unit.</th><th>Sous-total</th></tr>
        </thead>
        <tbody>
        <?php foreach ($lignes as $l): ?>
            <tr>
                <td><?= clean($l['designation']) ?></td>
                <td><?= (int)$l['quantite'] ?></td>
                <td><?= formatPrix($l['prix_unitaire']) ?></td>
                <td><?= formatPrix($l['sous_total']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr><td colspan="3" style="text-align:right;font-weight:bold;">Total TTC</td><td style="font-weight:bold;font-size:18px;"><?= formatPrix($facture['total']) ?></td></tr>
        </tfoot>
    </table>

    <div class="footer">
        Merci de votre confiance – OMEGA INFORMATIQUE CONSULTING
    </div>

    <script>
        window.onload = function() { window.print(); }
    </script>
</body>
</html>
