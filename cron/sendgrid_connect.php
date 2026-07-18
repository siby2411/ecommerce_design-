<?php
/**
 * Configuration SendGrid avec PHPMailer
 */

// Charger PHPMailer
$autoloadPaths = [
    '/root/ecommerce_design/vendor/autoload.php',
    __DIR__ . '/../vendor/autoload.php'
];

$loaded = false;
foreach ($autoloadPaths as $path) {
    if (file_exists($path)) {
        require_once $path;
        $loaded = true;
        break;
    }
}

// Si PHPMailer n'est pas installé, utiliser mail() simple
if (!$loaded) {
    function sendEmail($to, $subject, $htmlContent, $from = 'sibymohamed24@gmail.com') {
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: $from\r\n";
        
        if (mail($to, $subject, $htmlContent, $headers)) {
            return ['success' => true, 'message' => 'Email envoyé avec mail()'];
        } else {
            return ['success' => false, 'message' => 'Erreur avec mail()'];
        }
    }
    return;
}

// Configuration SMTP
$smtp_config = [
    'host' => 'smtp.gmail.com',
    'port' => 587,
    'username' => 'sibymohamed24@gmail.com',
    'password' => 'uftpkiqkimnpqutb',
    'encryption' => 'tls'
];

function sendEmail($to, $subject, $htmlContent, $from = 'sibymohamed24@gmail.com') {
    global $smtp_config;
    
    try {
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        
        $mail->isSMTP();
        $mail->Host = $smtp_config['host'];
        $mail->SMTPAuth = true;
        $mail->Username = $smtp_config['username'];
        $mail->Password = $smtp_config['password'];
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $smtp_config['port'];
        $mail->CharSet = 'UTF-8';
        $mail->SMTPDebug = 0;
        $mail->Timeout = 30;
        
        $mail->setFrom($from, 'OMEGA INFORMATIQUE');
        $mail->addAddress($to);
        $mail->addReplyTo($from);
        
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $htmlContent;
        $mail->AltBody = strip_tags($htmlContent);
        
        $mail->send();
        return ['success' => true, 'message' => '✅ Email envoyé avec PHPMailer'];
    } catch (Exception $e) {
        $outputFile = '/root/ecommerce_design/logs/email_' . date('Ymd_His') . '.html';
        file_put_contents($outputFile, $htmlContent);
        return ['success' => false, 'message' => '📧 Email sauvegardé dans: ' . $outputFile . ' - Erreur: ' . $e->getMessage()];
    }
}
