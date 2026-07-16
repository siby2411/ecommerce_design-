<?php
$pageTitle = 'Envoi d\'email';
$activePage = 'send_email';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/cron/sendgrid_connect.php';

// Destinataire par défaut
$default_to = 'sibymohamed24@gmail.com';

// Message de feedback
$feedback = '';
$feedback_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_email'])) {
    $to = $_POST['to'] ?? $default_to;
    $subject = $_POST['subject'] ?? 'Message de ' . ($_SESSION['user_nom'] ?? 'Utilisateur');
    $content = $_POST['content'] ?? '';
    
    if (empty($content)) {
        $feedback = '⚠️ Veuillez saisir un message.';
        $feedback_type = 'danger';
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
            $feedback = '✅ Email envoyé avec succès à ' . $to;
            $feedback_type = 'success';
            $logEntry = date('Y-m-d H:i:s') . " - " . ($_SESSION['user_nom'] ?? 'Utilisateur') . " a envoyé un email à $to - Sujet: $subject\n";
            file_put_contents('/root/ecommerce_design/logs/email_sent.log', $logEntry, FILE_APPEND);
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
        <a href="index.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

    <?php if ($feedback): ?>
        <div class="alert alert-<?= $feedback_type ?> alert-dismissible fade show" role="alert">
            <?= $feedback ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Destinataire</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="email" name="to" class="form-control" value="sibymohamed24@gmail.com" readonly>
                    </div>
                    <div class="form-text text-muted">Les emails sont envoyés à l'administrateur principal</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Sujet *</label>
                    <input type="text" name="subject" class="form-control" value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Message *</label>
                    <textarea name="content" class="form-control" rows="6" required><?= htmlspecialchars($_POST['content'] ?? '') ?></textarea>
                </div>

                <button type="submit" name="send_email" class="btn btn-primary btn-lg">
                    <i class="bi bi-send"></i> Envoyer
                </button>
            </form>
        </div>
    </div>

    <!-- Modèles rapides -->
    <div class="card shadow-sm mt-4">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="bi bi-copy"></i> Modèles rapides</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <button class="btn btn-outline-danger w-100" onclick="loadTemplate('stock')">
                        <i class="bi bi-exclamation-triangle"></i> Alerte stock
                    </button>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-outline-success w-100" onclick="loadTemplate('client')">
                        <i class="bi bi-person-plus"></i> Nouveau client
                    </button>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-outline-warning w-100" onclick="loadTemplate('facture')">
                        <i class="bi bi-file-earmark-text"></i> Problème facture
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center text-muted small mt-4">
        OMEGA INFORMATIQUE CONSULTING &copy; <?= date('Y') ?>
    </div>
</div>

<script>
function loadTemplate(type) {
    const templates = {
        stock: {
            subject: '⚠️ Alerte stock - Réapprovisionnement nécessaire',
            content: 'Bonjour,\n\nJe vous signale que certains produits sont en dessous du seuil de stock.\n\nProduits concernés :\n- MacBook Pro : 1 unité\n- Routeur Cisco : 2 unités\n- NAS Synology : 2 unités\n\nMerci de procéder au réapprovisionnement.\n\nCordialement,\n' + (<?= json_encode($_SESSION['user_nom'] ?? 'Utilisateur') ?>)
        },
        client: {
            subject: '📋 Nouveau client à contacter',
            content: 'Bonjour,\n\nUn nouveau client nécessite une prise de contact.\n\nNom : \nTéléphone : \nEmail : \n\nMerci de le contacter rapidement.\n\nCordialement,\n' + (<?= json_encode($_SESSION['user_nom'] ?? 'Utilisateur') ?>)
        },
        facture: {
            subject: '📄 Problème de facturation',
            content: 'Bonjour,\n\nJe rencontre un problème avec la facture suivante :\n\nNuméro : \nClient : \nMontant : \nProblème : \n\nMerci de bien vouloir vérifier.\n\nCordialement,\n' + (<?= json_encode($_SESSION['user_nom'] ?? 'Utilisateur') ?>)
        }
    };
    
    const tpl = templates[type];
    if (tpl) {
        document.querySelector('input[name="subject"]').value = tpl.subject;
        document.querySelector('textarea[name="content"]').value = tpl.content;
    }
}
</script>
</body>
</html>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
