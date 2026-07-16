<?php
$pageTitle = 'Dashboard personnalisé';
$activePage = 'dashboard_perso';
require_once __DIR__ . '/includes/header.php';

$pdo = getPDO();
$user_id = $_SESSION['user_id'] ?? 0;

// Récupérer les préférences de l'utilisateur
$stmt = $pdo->prepare("
    SELECT widgets, preferences 
    FROM utilisateurs 
    WHERE id = ?
");
$stmt->execute([$user_id]);
$userData = $stmt->fetch();

$widgets = $userData['widgets'] ?? json_encode(['ventes', 'stock', 'clients', 'alertes']);
$widgets = json_decode($widgets, true);

// Sauvegarder les préférences si formulaire soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_widgets'])) {
    $newWidgets = $_POST['widgets'] ?? [];
    $stmt = $pdo->prepare("UPDATE utilisateurs SET widgets = ? WHERE id = ?");
    $stmt->execute([json_encode($newWidgets), $user_id]);
    flash('success', 'Dashboard personnalisé mis à jour.');
    redirect('dashboard_perso.php');
}

// Statistiques pour les widgets
$stats = [
    'nb_produits' => $pdo->query('SELECT COUNT(*) FROM produits')->fetchColumn(),
    'nb_clients' => $pdo->query('SELECT COUNT(*) FROM clients')->fetchColumn(),
    'ca_jour' => $pdo->query("SELECT COALESCE(SUM(total),0) FROM factures WHERE statut='payee' AND DATE(date_creation)=CURDATE()")->fetchColumn(),
    'ca_mois' => $pdo->query("SELECT COALESCE(SUM(total),0) FROM factures WHERE statut='payee' AND MONTH(date_creation)=MONTH(CURRENT_DATE())")->fetchColumn(),
    'nb_alertes_stock' => $pdo->query("SELECT COUNT(*) FROM produits WHERE stock <= 5")->fetchColumn(),
    'nb_commandes_encours' => $pdo->query("SELECT COUNT(*) FROM commandes WHERE statut='en_cours'")->fetchColumn()
];
?>
<div class="page-head">
    <div>
        <h2><i class="fas fa-tachometer-alt"></i> Mon Dashboard</h2>
        <p>Personnalisez votre tableau de bord selon vos besoins</p>
    </div>
    <div>
        <button class="btn btn-primary" onclick="document.getElementById('widgetForm').style.display='block'">
            <i class="fas fa-cog"></i> Personnaliser
        </button>
    </div>
</div>

<!-- Formulaire de personnalisation -->
<div id="widgetForm" style="display:none;background:#fff;padding:20px;border-radius:12px;margin-bottom:20px;box-shadow:0 2px 10px rgba(0,0,0,0.1);">
    <form method="post">
        <h5>Choisissez vos widgets</h5>
        <div class="row">
            <div class="col-md-3">
                <div class="form-check">
                    <input type="checkbox" name="widgets[]" value="ventes" <?= in_array('ventes', $widgets) ? 'checked' : '' ?>>
                    <label>Ventes</label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-check">
                    <input type="checkbox" name="widgets[]" value="stock" <?= in_array('stock', $widgets) ? 'checked' : '' ?>>
                    <label>Stock</label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-check">
                    <input type="checkbox" name="widgets[]" value="clients" <?= in_array('clients', $widgets) ? 'checked' : '' ?>>
                    <label>Clients</label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-check">
                    <input type="checkbox" name="widgets[]" value="alertes" <?= in_array('alertes', $widgets) ? 'checked' : '' ?>>
                    <label>Alertes</label>
                </div>
            </div>
        </div>
        <button type="submit" name="save_widgets" class="btn btn-success mt-2">Enregistrer</button>
        <button type="button" onclick="document.getElementById('widgetForm').style.display='none'" class="btn btn-secondary mt-2">Annuler</button>
    </form>
</div>

<!-- Widgets -->
<div class="row">
    <?php if (in_array('ventes', $widgets)): ?>
    <div class="col-md-6">
        <div class="panel">
            <h2 class="panel-title"><i class="fas fa-shopping-cart" style="color:#2563eb;"></i> Ventes</h2>
            <div class="kpi-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:15px;">
                <div class="stat-card">
                    <div class="stat-label">Aujourd'hui</div>
                    <div class="stat-value"><?= number_format($stats['ca_jour'], 0, ',', ' ') ?> FCFA</div>
                </div>
                <div class="stat-card success">
                    <div class="stat-label">Ce mois</div>
                    <div class="stat-value"><?= number_format($stats['ca_mois'], 0, ',', ' ') ?> FCFA</div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (in_array('stock', $widgets)): ?>
    <div class="col-md-6">
        <div class="panel">
            <h2 class="panel-title"><i class="fas fa-box" style="color:#f59e0b;"></i> Stock</h2>
            <div style="display:flex;justify-content:space-between;padding:10px;">
                <div>
                    <div class="stat-label">Total produits</div>
                    <div class="stat-value"><?= $stats['nb_produits'] ?></div>
                </div>
                <div>
                    <div class="stat-label">Alertes stock</div>
                    <div class="stat-value" style="color:#dc2626;"><?= $stats['nb_alertes_stock'] ?></div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<div class="row">
    <?php if (in_array('clients', $widgets)): ?>
    <div class="col-md-6">
        <div class="panel">
            <h2 class="panel-title"><i class="fas fa-users" style="color:#8b5cf6;"></i> Clients</h2>
            <div class="stat-card" style="border-left-color:#8b5cf6;">
                <div class="stat-value"><?= $stats['nb_clients'] ?></div>
                <div class="stat-label">Total clients enregistrés</div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (in_array('alertes', $widgets)): ?>
    <div class="col-md-6">
        <div class="panel">
            <h2 class="panel-title"><i class="fas fa-bell" style="color:#dc2626;"></i> Alertes</h2>
            <div>
                <div style="display:flex;justify-content:space-between;padding:10px;">
                    <div>
                        <div class="stat-label">Stock critique</div>
                        <div class="stat-value" style="color:#dc2626;"><?= $stats['nb_alertes_stock'] ?></div>
                    </div>
                    <div>
                        <div class="stat-label">Commandes en cours</div>
                        <div class="stat-value" style="color:#f59e0b;"><?= $stats['nb_commandes_encours'] ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
    .panel { background:#fff; border-radius:12px; padding:20px; box-shadow:0 2px 10px rgba(0,0,0,0.05); margin-bottom:20px; }
    .panel-title { font-size:16px; font-weight:700; margin-bottom:15px; color:#1a1a2e; }
    .stat-card { background:#f8f9fa; border-radius:8px; padding:15px; border-left:4px solid #2563eb; }
    .stat-value { font-size:24px; font-weight:700; color:#1a1a2e; }
    .stat-label { font-size:12px; color:#888; margin-bottom:5px; }
    .form-check { margin:8px 0; }
    .form-check input { margin-right:8px; }
</style>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
