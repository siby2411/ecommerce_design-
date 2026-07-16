<?php
$pageTitle = 'Email rapide';
$activePage = 'quick_email';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/cron/sendgrid_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $to = 'admin@omega-consulting.sn';
    $subject = 'Alerte OMEGA - ' . $_SESSION['user_nom'] ?? 'Utilisateur';
    
    switch ($_POST['action']) {
        case 'stock':
            $subject = '⚠️ Alerte stock - ' . date('d/m/Y');
            $content = "<h2>⚠️ Alerte stock</h2>
            <p>Une alerte stock a été déclenchée par " . ($_SESSION['user_nom'] ?? 'Utilisateur') . "</p>
            <p>Veuillez consulter le tableau de bord pour plus d'informations.</p>";
            break;
        case 'client':
            $subject = '📋 Nouveau client à contacter - ' . date('d/m/Y');
            $content = "<h2>📋 Nouveau client</h2>
            <p>" . ($_SESSION['user_nom'] ?? 'Utilisateur') . " signale un nouveau client à contacter.</p>";
            break;
        case 'facture':
            $subject = '📄 Problème de facturation - ' . date('d/m/Y');
            $content = "<h2>📄 Problème de facturation</h2>
            <p>" . ($_SESSION['user_nom'] ?? 'Utilisateur') . " signale un problème de facturation.</p>";
            break;
        default:
            $content = "<p>Message d'alerte envoyé depuis OMEGA.</p>";
    }
    
    $result = sendEmail($to, $subject, $content);
    $message = $result['success'] ? '✅ Email envoyé' : '❌ ' . $result['message'];
    $type = $result['success'] ? 'success' : 'danger';
    flash($type, $message);
    redirect('quick_email.php');
}
?>
<div class="page-head">
    <div>
        <h2><i class="fas fa-bolt"></i> Email rapide</h2>
        <p>Envoyer un email en un clic</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <form method="post">
            <input type="hidden" name="action" value="stock">
            <button type="submit" class="btn btn-danger btn-lg w-100" style="height:150px;display:flex;flex-direction:column;align-items:center;justify-content:center;">
                <i class="fas fa-box" style="font-size:48px;margin-bottom:10px;"></i>
                <span style="font-size:18px;">⚠️ Alerte stock</span>
                <small style="font-size:12px;">Signaler un problème de stock</small>
            </button>
        </form>
    </div>
    
    <div class="col-md-4">
        <form method="post">
            <input type="hidden" name="action" value="client">
            <button type="submit" class="btn btn-success btn-lg w-100" style="height:150px;display:flex;flex-direction:column;align-items:center;justify-content:center;">
                <i class="fas fa-user-plus" style="font-size:48px;margin-bottom:10px;"></i>
                <span style="font-size:18px;">📋 Nouveau client</span>
                <small style="font-size:12px;">Signaler un nouveau client</small>
            </button>
        </form>
    </div>
    
    <div class="col-md-4">
        <form method="post">
            <input type="hidden" name="action" value="facture">
            <button type="submit" class="btn btn-warning btn-lg w-100" style="height:150px;display:flex;flex-direction:column;align-items:center;justify-content:center;">
                <i class="fas fa-file-invoice" style="font-size:48px;margin-bottom:10px;"></i>
                <span style="font-size:18px;">📄 Problème facture</span>
                <small style="font-size:12px;">Signaler un problème de facturation</small>
            </button>
        </form>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <h5><i class="fas fa-info-circle"></i> Utilisation</h5>
        <ul>
            <li><strong>Alerte stock</strong> : Envoyez un email à l'administration pour signaler un problème de réapprovisionnement.</li>
            <li><strong>Nouveau client</strong> : Signalez un nouveau client qui nécessite une prise de contact.</li>
            <li><strong>Problème facture</strong> : Signalez un problème de facturation.</li>
        </ul>
    </div>
</div>

<style>
.btn-lg { transition: all 0.3s; }
.btn-lg:hover { transform: scale(1.05); }
.card { border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
</style>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
