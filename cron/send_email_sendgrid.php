<?php
/**
 * Script d'envoi d'email avec SendGrid et mot de passe d'application
 * Utilisation: php send_email_sendgrid.php "destinataire" "sujet" "message"
 */

require_once __DIR__ . '/sendgrid_connect.php';

// Paramètres
$to = $argv[1] ?? 'admin@omega-consulting.sn';
$subject = $argv[2] ?? 'Test OMEGA ' . date('Y-m-d');
$htmlContent = $argv[3] ?? '<h1>Test</h1><p>Ceci est un test de SendGrid avec mot de passe d\'application</p>';

// Envoyer l'email
$result = sendEmail($to, $subject, $htmlContent);

// Afficher le résultat
if ($result['success']) {
    echo "✅ " . date('Y-m-d H:i:s') . " - " . $result['message'] . "\n";
    echo "📧 Destinataire: $to\n";
    echo "📝 Sujet: $subject\n";
} else {
    echo "❌ " . date('Y-m-d H:i:s') . " - " . $result['message'] . "\n";
    
    // Fallback: sauvegarder dans un fichier
    $outputFile = '/root/ecommerce_design/logs/email_' . date('Ymd_His') . '.html';
    file_put_contents($outputFile, $htmlContent);
    echo "📧 Email sauvegardé dans: $outputFile\n";
}
