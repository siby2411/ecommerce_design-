<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

// Vérifier si l'utilisateur est connecté
session_start();
if (!isset($_SESSION['user_id'])) {
    die('Accès non autorisé');
}

$period = $_GET['period'] ?? 'month';
$type = $_GET['type'] ?? 'ventes'; // ventes, stock, clients

// Récupérer les données
$pdo = getPDO();

if ($type == 'ventes') {
    $stmt = $pdo->query("
        SELECT 
            p.nom AS produit,
            c.nom AS categorie,
            COALESCE(SUM(fl.quantite),0) AS quantite,
            COALESCE(SUM(fl.sous_total),0) AS total
        FROM produits p
        LEFT JOIN categories c ON c.id = p.id_categorie
        LEFT JOIN facture_lignes fl ON fl.id_produit = p.id
        LEFT JOIN factures f ON f.id = fl.id_facture AND f.statut = 'payee'
        GROUP BY p.id
        HAVING total > 0
        ORDER BY total DESC
        LIMIT 20
    ");
    $data = $stmt->fetchAll();
    $title = "Rapport des meilleures ventes";
} elseif ($type == 'stock') {
    $stmt = $pdo->query("
        SELECT 
            p.nom AS produit,
            c.nom AS categorie,
            p.stock,
            p.prix,
            (SELECT COUNT(*) FROM facture_lignes WHERE id_produit = p.id) AS nb_ventes
        FROM produits p
        LEFT JOIN categories c ON c.id = p.id_categorie
        WHERE p.stock <= 10
        ORDER BY p.stock ASC
    ");
    $data = $stmt->fetchAll();
    $title = "Rapport des stocks faibles";
} else {
    $stmt = $pdo->query("
        SELECT 
            cl.nom,
            cl.prenom,
            cl.telephone,
            COUNT(DISTINCT f.id) AS nb_factures,
            COALESCE(SUM(fl.sous_total),0) AS total
        FROM clients cl
        LEFT JOIN factures f ON f.id_client = cl.id AND f.statut = 'payee'
        LEFT JOIN facture_lignes fl ON fl.id_facture = f.id
        GROUP BY cl.id
        HAVING total > 0
        ORDER BY total DESC
        LIMIT 20
    ");
    $data = $stmt->fetchAll();
    $title = "Rapport des meilleurs clients";
}

// Générer le PDF avec HTML2PDF
header('Content-Type: text/html; charset=utf-8');
header('Content-Disposition: attachment; filename=rapport_' . date('Y-m-d') . '.pdf');

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; padding: 20px; }
        .header { text-align: center; border-bottom: 3px solid #2563eb; padding-bottom: 15px; margin-bottom: 20px; }
        .header h1 { color: #1a1a2e; margin: 0; }
        .header p { color: #888; margin: 5px 0 0 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background: #2563eb; color: #fff; padding: 10px; text-align: left; }
        td { padding: 8px; border-bottom: 1px solid #ddd; }
        .footer { text-align: center; margin-top: 30px; color: #888; font-size: 10px; border-top: 1px solid #ddd; padding-top: 15px; }
        .total { font-weight: bold; font-size: 14px; margin-top: 15px; }
        .badge { padding: 2px 8px; border-radius: 10px; font-size: 10px; }
        .badge-danger { background: #dc2626; color: #fff; }
        .badge-warning { background: #f59e0b; color: #fff; }
        .badge-success { background: #16a34a; color: #fff; }
    </style>
</head>
<body>
    <div class="header">
        <h1>OMEGA INFORMATIQUE CONSULTING</h1>
        <p><?= $title ?> - Généré le <?= date('d/m/Y H:i') ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <?php if ($type == 'ventes'): ?>
                    <th>#</th>
                    <th>Produit</th>
                    <th>Catégorie</th>
                    <th>Quantité</th>
                    <th>Total</th>
                <?php elseif ($type == 'stock'): ?>
                    <th>Produit</th>
                    <th>Catégorie</th>
                    <th>Stock</th>
                    <th>Prix</th>
                    <th>Ventes</th>
                    <th>Statut</th>
                <?php else: ?>
                    <th>#</th>
                    <th>Client</th>
                    <th>Téléphone</th>
                    <th>Factures</th>
                    <th>Total</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php if ($type == 'ventes'): $rank = 1; foreach ($data as $row): ?>
                <tr>
                    <td><?= $rank++ ?></td>
                    <td><?= htmlspecialchars($row['produit']) ?></td>
                    <td><?= htmlspecialchars($row['categorie'] ?? 'Non classé') ?></td>
                    <td><?= $row['quantite'] ?></td>
                    <td><strong><?= number_format($row['total'], 0, ',', ' ') ?> FCFA</strong></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="4" style="text-align:right;font-weight:bold;">Total CA :</td>
                <td><strong><?= number_format(array_sum(array_column($data, 'total')), 0, ',', ' ') ?> FCFA</strong></td>
            </tr>
            <?php elseif ($type == 'stock'): foreach ($data as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['produit']) ?></td>
                    <td><?= htmlspecialchars($row['categorie'] ?? 'Non classé') ?></td>
                    <td><strong><?= $row['stock'] ?></strong></td>
                    <td><?= number_format($row['prix'], 0, ',', ' ') ?> FCFA</td>
                    <td><?= $row['nb_ventes'] ?? 0 ?></td>
                    <td>
                        <?php if ($row['stock'] <= 1): ?>
                            <span class="badge badge-danger">Critique</span>
                        <?php elseif ($row['stock'] <= 3): ?>
                            <span class="badge badge-warning">Alerte</span>
                        <?php else: ?>
                            <span class="badge badge-success">Normal</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php else: $rank = 1; foreach ($data as $row): ?>
                <tr>
                    <td><?= $rank++ ?></td>
                    <td><?= htmlspecialchars(($row['prenom'] ?? '') . ' ' . ($row['nom'] ?? '')) ?></td>
                    <td><?= htmlspecialchars($row['telephone'] ?? '') ?></td>
                    <td><?= $row['nb_factures'] ?></td>
                    <td><strong><?= number_format($row['total'], 0, ',', ' ') ?> FCFA</strong></td>
                </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>OMEGA INFORMATIQUE CONSULTING - <?= date('Y') ?> - Tous droits réservés</p>
    </div>

    <!-- Script pour générer le PDF avec jsPDF (version simplifiée pour l'export) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        window.onload = function() {
            const { jsPDF } = window.jspdf;
            html2canvas(document.body).then(canvas => {
                const imgData = canvas.toDataURL('image/png');
                const pdf = new jsPDF('p', 'mm', 'a4');
                const imgWidth = 210;
                const pageHeight = 297;
                const imgHeight = canvas.height * imgWidth / canvas.width;
                let heightLeft = imgHeight;
                let position = 0;

                pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;

                while (heightLeft > 0) {
                    position = heightLeft - imgHeight;
                    pdf.addPage();
                    pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                    heightLeft -= pageHeight;
                }
                pdf.save('rapport_<?= date('Y-m-d') ?>.pdf');
            });
        };
    </script>
</body>
</html>
