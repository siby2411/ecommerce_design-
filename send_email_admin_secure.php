<?php
$pageTitle = 'Envoi d\'email sécurisé';
$activePage = 'send_email_secure';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
    $_SESSION['user_nom'] = 'Administrateur';
    $_SESSION['user_email'] = 'sibymohamed24@gmail.com';
    $_SESSION['user_role'] = 'admin';
}

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/config/db_secure.php';
require_once __DIR__ . '/cron/sendgrid_connect.php';

$pdo = getPDO_Secure();

$clients = [];
try {
    $clients = $pdo->query("SELECT id, nom, prenom, email FROM clients WHERE email IS NOT NULL AND email != '' GROUP BY email ORDER BY nom")->fetchAll();
} catch (Exception $e) {}

$fournisseurs = [];
try {
    $fournisseurs = $pdo->query("SELECT id, nom_entreprise AS nom, email FROM fournisseurs WHERE email IS NOT NULL AND email != '' GROUP BY email ORDER BY nom_entreprise")->fetchAll();
} catch (Exception $e) {}

$admin_email = 'sibymohamed24@gmail.com';
$feedback = '';
$feedback_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_email'])) {
    $sender_type = $_POST['sender_type'] ?? 'client';
    $sender_id = (int)($_POST['sender_id'] ?? 0);
    $subject = trim($_POST['subject'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $sender_name = '';
    $sender_email = '';
    $type_label = '';
    
    if ($sender_type === 'client') {
        $type_label = 'Client';
        foreach ($clients as $c) {
            if ((int)$c['id'] === (int)$sender_id) {
                $sender_name = trim(($c['prenom'] ?? '') . ' ' . ($c['nom'] ?? ''));
                $sender_email = $c['email'];
                break;
            }
        }
    } elseif ($sender_type === 'fournisseur') {
        $type_label = 'Fournisseur';
        foreach ($fournisseurs as $f) {
            if ((int)$f['id'] === (int)$sender_id) {
                $sender_name = $f['nom'];
                $sender_email = $f['email'];
                break;
            }
        }
    }
    
    if (empty($sender_email) || $sender_id == 0) {
        $feedback = '⚠️ Veuillez sélectionner un expéditeur valide.';
        $feedback_type = 'danger';
    } elseif (empty($subject)) {
        $feedback = '⚠️ Veuillez saisir un sujet.';
        $feedback_type = 'danger';
    } elseif (empty($content)) {
        $feedback = '⚠️ Veuillez saisir un message.';
        $feedback_type = 'danger';
    } else {
        $htmlContent = "
        <html>
        <head><title>$subject</title></head>
        <body>
            <h2>$subject</h2>
            <p><strong>De :</strong> $sender_name</p>
            <p><strong>Email :</strong> $sender_email</p>
            <p><strong>Type :</strong> $type_label</p>
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
        
        $result = sendEmail($admin_email, $subject, $htmlContent, $sender_email);
        
        if ($result['success']) {
            $feedback = '✅ Email envoyé avec succès à ' . $admin_email;
            $feedback_type = 'success';
            $logEntry = date('Y-m-d H:i:s') . " - $type_label ($sender_name) → Admin - Sujet: $subject\n";
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
    <title>Email sécurisé - OMEGA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-envelope-shield text-primary"></i> Email sécurisé</h2>
        <a href="index.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Retour</a>
    </div>

    <?php if ($feedback): ?>
        <div class="alert alert-<?= $feedback_type ?> alert-dismissible fade show">
            <?= $feedback ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">Type d'expéditeur</label>
                    <select name="sender_type" class="form-select" id="senderType" onchange="toggleSender()">
                        <option value="client">👤 Client</option>
                        <option value="fournisseur">🏢 Fournisseur</option>
                    </select>
                </div>

                <div class="mb-3" id="clientDiv">
                    <label class="form-label">Choisir un client</label>
                    <div class="contact-list">
                        <?php foreach ($clients as $c): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sender_id" value="<?= $c['id'] ?>" id="client_<?= $c['id'] ?>">
                                <label class="form-check-label" for="client_<?= $c['id'] ?>">
                                    <?= clean($c['prenom'] ?? '') ?> <?= clean($c['nom']) ?> (<?= clean($c['email']) ?>)
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="mb-3" id="fournisseurDiv" style="display:none;">
                    <label class="form-label">Choisir un fournisseur</label>
                    <div class="contact-list">
                        <?php foreach ($fournisseurs as $f): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sender_id" value="<?= $f['id'] ?>" id="fournisseur_<?= $f['id'] ?>">
                                <label class="form-check-label" for="fournisseur_<?= $f['id'] ?>">
                                    <?= clean($f['nom']) ?> (<?= clean($f['email']) ?>)
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Destinataire (Administrateur)</label>
                    <input type="email" class="form-control" value="<?= $admin_email ?>" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Sujet *</label>
                    <input type="text" name="subject" class="form-control" placeholder="Saisissez le sujet..." required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Message *</label>
                    <textarea name="content" class="form-control" rows="6" placeholder="Saisissez votre message..." required></textarea>
                </div>

                <button type="submit" name="send_email" class="btn btn-primary w-100 py-2">
                    <i class="bi bi-send"></i> Envoyer
                </button>
            </form>
        </div>
    </div>

    <div class="text-center text-muted small mt-4">
        OMEGA INFORMATIQUE CONSULTING &copy; <?= date('Y') ?>
    </div>
</div>

<script>
function toggleSender() {
    const type = document.getElementById('senderType').value;
    document.getElementById('clientDiv').style.display = type === 'client' ? 'block' : 'none';
    document.getElementById('fournisseurDiv').style.display = type === 'fournisseur' ? 'block' : 'none';
}

document.addEventListener('DOMContentLoaded', toggleSender);

document.querySelector('form').addEventListener('submit', function(e) {
    const selected = document.querySelector('input[name="sender_id"]:checked');
    if (!selected) {
        e.preventDefault();
        alert('⚠️ Veuillez sélectionner un expéditeur.');
        return false;
    }
});
</script>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
