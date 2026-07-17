<?php
/**
 * Script d'envoi d'email
 * Utilisation: php send_email_sendgrid.php "destinataire" "sujet" "message"
 */

require_once __DIR__ . '/sendgrid_connect.php';

// Paramètres
$to = $argv[1] ?? 'sibymohamed24@gmail.com';
$subject = $argv[2] ?? 'Test OMEGA ' . date('Y-m-d');
$htmlContent = $argv[3] ?? '<h1>Test</h1><p>Email de test</p>';

// Si l'email n'est pas Gmail, rediriger vers Gmail
if (!str_contains($to, '@gmail.com')) {
    $to = 'sibymohamed24@gmail.com';
}

$result = sendEmail($to, $subject, $htmlContent);

if ($result['success']) {
    echo "✅ " . date('Y-m-d H:i:s') . " - " . $result['message'] . "\n";
    echo "📧 Destinataire: $to\n";
} else {
    echo "❌ " . date('Y-m-d H:i:s') . " - " . $result['message'] . "\n";
}
