<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Test d'envoi d'email</h1>";

$to = 'sibymohamed24@gmail.com';
$subject = 'Test simple';
$message = 'Ceci est un test d\'envoi d\'email depuis OMEGA';
$headers = "From: sibymohamed24@gmail.com\r\n";

if (mail($to, $subject, $message, $headers)) {
    echo "<p style='color:green;'>✅ Email envoyé avec succès à $to</p>";
} else {
    echo "<p style='color:red;'>❌ Erreur lors de l'envoi de l'email</p>";
}
