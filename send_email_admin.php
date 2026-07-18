<?php
// Démarrer la session et simuler un utilisateur connecté
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Simuler un utilisateur connecté pour éviter la redirection
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
    $_SESSION['user_nom'] = 'Administrateur';
    $_SESSION['user_email'] = 'sibymohamed24@gmail.com';
    $_SESSION['user_role'] = 'admin';
}

$pageTitle = 'Envoi d\'email';
$activePage = 'send_email';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/cron/sendgrid_connect.php';

$default_to = 'sibymohamed24@gmail.com';
$feedback = '';
$feedback_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_email'])) {
    $to = $_POST['to'] ?? $default_to;
    $subject = $_POST['subject'] ?? 'Message';
    $content = $_POST['content'] ?? '';
    
    if (empty($content)) {
        $feedback = '⚠️ Veuillez saisir un message.';
        $feedback_type = 'danger';
    } else {
        $htmlContent = "<html><body>
            <h2>$subject</h2>
            <p><strong>De :</strong> " . ($_SESSION['user_nom'] ?? 'Utilisateur') . "</p>
            <p><strong>Email :</strong> " . ($_SESSION['user_email'] ?? 'sibymohamed24@gmail.com') . "</p>
            <hr>
            <p>" . nl2br(htmlspecialchars($content)) . "</p>
            <p style='color:#888;font-size:12px;'>OMEGA INFORMATIQUE CONSULTING<br>Date: " . date('d/m/Y H:i:s') . "</p>
        </body></html>";
        
        $result = sendEmail($to, $subject, $htmlContent);
        
        if ($result['success']) {
            $feedback = '✅ Email envoyé avec succès à ' . $to;
            $feedback_type = 'success';
        } else {
            $feedback = '❌ ' . $result['message'];
            $feedback_type = 'danger';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Envoyer un email - OMEGA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-envelope-fill text-primary"></i> Envoyer un email</h2>
        <a href="index.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Retour</a>
    </div>

    <?php if ($feedback): ?>
        <div class="alert alert-<?= $feedback_type ?>"><?= $feedback ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Destinataire</label>
                    <input type="email" name="to" class="form-control" value="sibymohamed24@gmail.com" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Sujet *</label>
                    <input type="text" name="subject" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Message *</label>
                    <textarea name="content" class="form-control" rows="6" required></textarea>
                </div>
                <button type="submit" name="send_email" class="btn btn-primary"><i class="bi bi-send"></i> Envoyer</button>
            </form>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
