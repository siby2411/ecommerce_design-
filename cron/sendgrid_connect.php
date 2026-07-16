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

if (!$loaded) {
    die("❌ PHPMailer non trouvé.\n");
}

// Configuration SMTP avec le mot de passe d'application
$smtp_config = [
    'host' => 'smtp.gmail.com',
    'port' => 587,
    'username' => 'sibymohamed24@gmail.com',
    'password' => 'uftpkiqkimnpqutb',
    'encryption' => 'tls'
];

// Destinataire par défaut (adresse Gmail valide)
$default_to = 'sibymohamed24@gmail.com';

// Fonction d'envoi d'email
function sendEmail($to, $subject, $htmlContent, $from = 'sibymohamed24@gmail.com') {
    global $smtp_config;
    
    // Si le domaine n'existe pas, remplacer par Gmail
    if (!filter_var($to, FILTER_VALIDATE_EMAIL) || !str_contains($to, '@gmail.com')) {
        $to = 'sibymohamed24@gmail.com';
    }
    
    return sendEmailWithPHPMailer($to, $subject, $htmlContent, $from);
}

// Envoi avec PHPMailer
function sendEmailWithPHPMailer($to, $subject, $htmlContent, $from) {
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
        return ['success' => false, 'message' => '📧 Email sauvegardé dans: ' . $outputFile];
    }
}
