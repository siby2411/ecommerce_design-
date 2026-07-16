<?php
$pageTitle = 'Envoi d\'email';
$activePage = 'send_email';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/cron/sendgrid_connect.php';

// Destinataire par défaut
$default_to = 'sibymohamed24@gmail.com';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_email'])) {
    $to = $_POST['to'] ?? $default_to;
    $subject = $_POST['subject'] ?? 'Message de ' . ($_SESSION['user_nom'] ?? 'Utilisateur');
    $content = $_POST['content'] ?? '';
    
    if (empty($content)) {
        flash('danger', '⚠️ Veuillez saisir un message.');
    } else {
        $htmlContent = "
        <html>
        <head><title>$subject</title></head>
        <body>
            <h2>$subject</h2>
            <p><strong>De :</strong> " . ($_SESSION['user_nom'] ?? 'Utilisateur') . "</p>
            <p><strong>Email :</strong> " . ($_SESSION['user_email'] ?? 'non renseigné') . "</p>
            <hr>
            <div style='background:#f8f9fa;padding:15px;border-radius:8px;'>
                " . nl2br(htmlspecialchars($content)) . "
            </div>
            <p style='color:#888;font-size:12px;margin-top:20px;'>
                OMEGA INFORMATIQUE CONSULTING<br>
                Date: " . date('d/m/Y H:i:s') . "
            </p>
        </body>
        </html>";
        
        $result = sendEmail($to, $subject, $htmlContent);
        
        if ($result['success']) {
            flash('success', '✅ Email envoyé avec succès à ' . $to);
            $logEntry = date('Y-m-d H:i:s') . " - " . ($_SESSION['user_nom'] ?? 'Utilisateur') . " a envoyé un email à $to - Sujet: $subject\n";
            file_put_contents('/root/ecommerce_design/logs/email_sent.log', $logEntry, FILE_APPEND);
        } else {
            flash('danger', '❌ ' . $result['message']);
        }
    }
}
?>
<div class="page-head">
    <div>
        <h2><i class="fas fa-envelope"></i> Envoi d'email</h2>
        <p>Envoyez un message à l'administration (sibymohamed24@gmail.com)</p>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="post">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Destinataire</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="email" name="to" class="form-control" value="sibymohamed24@gmail.com" readonly>
                    </div>
                    <div class="form-text">Les emails sont envoyés à l'administrateur principal</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Sujet *</label>
                    <input type="text" name="subject" class="form-control" value="<?= $_POST['subject'] ?? '' ?>" required>
                </div>

                <div class="col-12">
                    <label class="form-label">Message *</label>
                    <textarea name="content" class="form-control" rows="8" required><?= $_POST['content'] ?? '' ?></textarea>
                </div>

                <div class="col-12">
                    <button type="submit" name="send_email" class="btn btn-primary btn-lg">
                        <i class="fas fa-paper-plane"></i> Envoyer
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
