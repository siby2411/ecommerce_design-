<?php
/**
 * Script d'alertes stock - Version corrigée
 */

$basePath = '/root/ecommerce_design';
require_once $basePath . '/cron/config_cron.php';
require_once $basePath . '/cron/sendgrid_connect.php';

// Destinataire Gmail valide
$email_admin = 'sibymohamed24@gmail.com';
$seuil_alerte = 5;
$seuil_critique = 2;

$logDir = '/root/ecommerce_design/logs';
if (!is_dir($logDir)) mkdir($logDir, 0755, true);

$logFile = $logDir . '/stock_alert_sendgrid.log';
file_put_contents($logFile, date('Y-m-d H:i:s') . " - Début du script\n", FILE_APPEND);

try {
    $pdo = getPDO();
} catch (Exception $e) {
    file_put_contents($logFile, "❌ Erreur DB: " . $e->getMessage() . "\n", FILE_APPEND);
    die("❌ Erreur DB\n");
}

$stmt = $pdo->prepare("
    SELECT p.id, p.nom, p.stock, p.prix, c.nom AS categorie,
           (SELECT COUNT(*) FROM facture_lignes WHERE id_produit = p.id) AS nb_ventes
    FROM produits p
    LEFT JOIN categories c ON c.id = p.id_categorie
    WHERE p.stock <= ?
    ORDER BY p.stock ASC
");
$stmt->execute([$seuil_alerte]);
$produits = $stmt->fetchAll();

if (empty($produits)) {
    file_put_contents($logFile, "✅ " . date('Y-m-d H:i:s') . " - Tous les stocks sont corrects\n", FILE_APPEND);
    echo "✅ Tous les stocks sont corrects\n";
    exit(0);
}

$sujet = "⚠️ ALERTE STOCK - " . count($produits) . " produit(s) en alerte";

$message = "<html><head><title>ALERTE STOCK</title>
<style>
body { font-family: Arial; }
h2 { color: #dc2626; }
table { border-collapse: collapse; width: 100%; }
th { background: #2563eb; color: #fff; padding: 10px; border: 1px solid #ddd; }
td { padding: 8px; border: 1px solid #ddd; }
.critique { color: #dc2626; font-weight: bold; }
.alerte { color: #f59e0b; font-weight: bold; }
</style>
</head>
<body>
<h2 style='color:#dc2626;'>⚠️ Alerte Stock Faible</h2>
<p>" . count($produits) . " produit(s) sous le seuil de {$seuil_alerte}</p>
<table>
<tr><th>Produit</th><th>Catégorie</th><th>Stock</th><th>Prix</th><th>Ventes</th><th>Niveau</th></tr>";

foreach ($produits as $p) {
    $class = $p['stock'] <= $seuil_critique ? 'critique' : 'alerte';
    $niveau = $p['stock'] <= $seuil_critique ? '⚠️ CRITIQUE' : '⚡ ALERTE';
    $message .= "<tr>
        <td><strong>" . htmlspecialchars($p['nom']) . "</strong></td>
        <td>" . htmlspecialchars($p['categorie'] ?? 'Non classé') . "</td>
        <td class='{$class}'>{$p['stock']}</td>
        <td>" . number_format($p['prix'], 0, ',', ' ') . " FCFA</td>
        <td>{$p['nb_ventes']}</td>
        <td class='{$class}'>{$niveau}</td>
    </tr>";
}

$message .= "
</table>
<p style='color:#888;font-size:12px;'>OMEGA INFORMATIQUE CONSULTING<br>Date: " . date('d/m/Y H:i:s') . "</p>
</body></html>";

$result = sendEmail($email_admin, $sujet, $message);

if ($result['success']) {
    file_put_contents($logFile, "✅ " . date('Y-m-d H:i:s') . " - Envoyé à {$email_admin}\n", FILE_APPEND);
    echo "✅ Email envoyé à sibymohamed24@gmail.com\n";
} else {
    file_put_contents($logFile, "❌ " . date('Y-m-d H:i:s') . " - " . $result['message'] . "\n", FILE_APPEND);
    echo "❌ " . $result['message'] . "\n";
}
echo "📊 " . count($produits) . " produits en alerte\n";
