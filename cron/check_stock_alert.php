<?php
/**
 * Script à exécuter quotidiennement (cron)
 * Vérifie les stocks faibles et envoie des alertes par email
 * Version avec fallback fichier
 */

// Utiliser la configuration spécifique pour le cron
require_once __DIR__ . '/config_cron.php';

// Configuration email
$email_admin = 'admin@omega-consulting.sn';
$seuil_alerte = 5;
$seuil_critique = 2;

// Fichier de log
$logFile = '/root/ecommerce_design/logs/stock_alert.log';
$logMessage = date('Y-m-d H:i:s') . " - Début du script\n";
file_put_contents($logFile, $logMessage, FILE_APPEND);

try {
    $pdo = getPDO();
} catch (Exception $e) {
    $error = "❌ Erreur de connexion DB: " . $e->getMessage() . "\n";
    file_put_contents($logFile, $error, FILE_APPEND);
    die($error);
}

// Récupérer les produits en alerte
$stmt = $pdo->prepare("
    SELECT 
        p.id,
        p.nom,
        p.stock,
        p.prix,
        c.nom AS categorie,
        (SELECT COUNT(*) FROM facture_lignes WHERE id_produit = p.id) AS nb_ventes
    FROM produits p
    LEFT JOIN categories c ON c.id = p.id_categorie
    WHERE p.stock <= ?
    ORDER BY p.stock ASC
");
$stmt->execute([$seuil_alerte]);
$produits = $stmt->fetchAll();

if (empty($produits)) {
    $msg = "✅ " . date('Y-m-d H:i:s') . " - Tous les stocks sont à un niveau correct.\n";
    file_put_contents($logFile, $msg, FILE_APPEND);
    echo $msg;
    exit(0);
}

// Construire le message
$sujet = "⚠️ ALERTE STOCK - " . count($produits) . " produit(s) en dessous du seuil";

$message = "
<html>
<head><title>ALERTE STOCK</title></head>
<body>
    <h2 style='color:#dc2626;'>⚠️ Alerte Stock Faible</h2>
    <p>" . count($produits) . " produit(s) sont en dessous du seuil de {$seuil_alerte} unités.</p>
    <table border='1' cellpadding='8' style='border-collapse:collapse;width:100%;'>
        <tr style='background:#f8f9fa;'>
            <th>Produit</th>
            <th>Catégorie</th>
            <th>Stock</th>
            <th>Prix</th>
            <th>Ventes</th>
            <th>Niveau</th>
        </tr>";

foreach ($produits as $p) {
    $niveau = $p['stock'] <= $seuil_critique ? "⚠️ CRITIQUE" : "⚡ ALERTE";
    $color = $p['stock'] <= $seuil_critique ? '#dc2626' : '#f59e0b';
    $message .= "
        <tr>
            <td><strong>" . htmlspecialchars($p['nom']) . "</strong></td>
            <td>" . htmlspecialchars($p['categorie'] ?? 'Non classé') . "</td>
            <td style='text-align:center;font-weight:bold;color:{$color};'>{$p['stock']}</td>
            <td>" . number_format($p['prix'], 0, ',', ' ') . " FCFA</td>
            <td style='text-align:center;'>{$p['nb_ventes']}</td>
            <td style='color:{$color};font-weight:bold;'>{$niveau}</td>
        </tr>";
}

$message .= "
    </table>
    <p style='margin-top:20px;'>
        <a href='http://localhost:9006/produits.php' style='background:#2563eb;color:#fff;padding:10px 20px;text-decoration:none;border-radius:5px;'>
            🔗 Voir les produits
        </a>
    </p>
    <p style='color:#888;font-size:12px;margin-top:30px;'>
        Ce message a été envoyé automatiquement par OMEGA INFORMATIQUE CONSULTING.<br>
        Date: " . date('d/m/Y H:i:s') . "
    </p>
</body>
</html>";

// Envoyer l'email
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
$headers .= "From: OMEGA INFORMATIQUE <alertes@omega-consulting.sn>\r\n";

// Tentative d'envoi par mail()
$mailSent = false;

if (function_exists('mail')) {
    $mailSent = mail($email_admin, $sujet, $message, $headers);
}

// Fallback: sauvegarder dans un fichier si l'email échoue
$outputFile = '/root/ecommerce_design/logs/alert_email_' . date('Ymd') . '.html';

if (!$mailSent) {
    // Sauvegarder l'email dans un fichier
    file_put_contents($outputFile, $message);
    $msg = "📧 Email sauvegardé dans: " . $outputFile . "\n";
    file_put_contents($logFile, $msg, FILE_APPEND);
    echo $msg;
    
    // Afficher les produits en console
    echo "📊 " . count($produits) . " produit(s) en alerte:\n";
    foreach ($produits as $p) {
        echo "   - " . $p['nom'] . " (Stock: " . $p['stock'] . ")\n";
    }
} else {
    $msg = "✅ " . date('Y-m-d H:i:s') . " - Alerte envoyée à {$email_admin}\n";
    $msg .= "📊 " . count($produits) . " produit(s) en alerte\n";
    file_put_contents($logFile, $msg, FILE_APPEND);
    echo $msg;
}
