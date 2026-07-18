<?php
$pageTitle = 'Tableau de bord';
$activePage = 'dashboard';

// Utiliser db_secure.php pour la connexion PDO
require_once __DIR__ . '/config/db_secure.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/functions.php';

// Utiliser getPDO_Secure()
$pdo = getPDO_Secure();

// Statistiques KPI
$nbProduits = $pdo->query('SELECT COUNT(*) AS nb FROM produits')->fetch()['nb'] ?? 0;
$nbClients = $pdo->query('SELECT COUNT(*) AS nb FROM clients')->fetch()['nb'] ?? 0;
$nbFournisseurs = $pdo->query('SELECT COUNT(*) AS nb FROM fournisseurs')->fetch()['nb'] ?? 0;
$nbFactures = $pdo->query('SELECT COUNT(*) AS nb FROM factures')->fetch()['nb'] ?? 0;
$nbAlertesStock = $pdo->query("SELECT COUNT(*) FROM produits WHERE stock <= 5")->fetchColumn() ?? 0;
$caTotal = $pdo->query("SELECT COALESCE(SUM(total),0) AS ca FROM factures WHERE statut = 'payee'")->fetch()['ca'] ?? 0;

// Dernières factures
$dernieresFactures = $pdo->query("
    SELECT f.*, c.nom AS client_nom, c.prenom AS client_prenom
    FROM factures f
    LEFT JOIN clients c ON c.id = f.id_client
    ORDER BY f.date_creation DESC
    LIMIT 6
")->fetchAll();

$produitsFaibleStock = $pdo->query("SELECT * FROM produits WHERE stock <= 5 ORDER BY stock ASC LIMIT 5")->fetchAll();

// Données pour graphiques
$ventes = $pdo->query("SELECT DATE_FORMAT(date_facture, '%Y-%m') AS mois, SUM(total) AS total FROM factures WHERE statut='payee' GROUP BY mois ORDER BY mois LIMIT 12")->fetchAll();
$categoriesData = $pdo->query("SELECT c.nom, COUNT(p.id) AS nb FROM categories c LEFT JOIN produits p ON p.id_categorie = c.id GROUP BY c.id")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - OMEGA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #f0f2f5; font-family: 'Segoe UI', sans-serif; padding: 20px; }
        .container { max-width: 1400px; margin: 0 auto; }
        .header { background: linear-gradient(135deg, #0f0c29, #302b63); color: #fff; padding: 20px; border-radius: 12px; margin-bottom: 20px; }
        .header h1 { font-size: 1.8rem; font-weight: 700; margin: 0; }
        .header h1 span { color: #f7971e; }
        
        .actions-rapides { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 20px; }
        .btn-action { padding: 12px 24px; border-radius: 10px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s; border: none; font-size: 14px; cursor: pointer; }
        .btn-action:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(0,0,0,0.2); color: #fff; }
        .btn-email { background: #2563eb; color: #fff; }
        .btn-email:hover { background: #1d4fd0; color: #fff; }
        .btn-secure { background: #16a34a; color: #fff; }
        .btn-secure:hover { background: #15803d; color: #fff; }
        .btn-quick { background: #f59e0b; color: #fff; }
        .btn-quick:hover { background: #d97706; color: #fff; }
        .btn-decision { background: #8b5cf6; color: #fff; }
        .btn-decision:hover { background: #7c3aed; color: #fff; }
        .btn-alert { background: #dc2626; color: #fff; }
        .btn-alert:hover { background: #b91c1c; color: #fff; }

        .kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px; }
        .kpi-card { background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border-left: 4px solid #2563eb; }
        .kpi-card .kpi-value { font-size: 28px; font-weight: 700; color: #1a1a2e; }
        .kpi-card .kpi-label { color: #888; font-size: 14px; }
        .kpi-card.green { border-left-color: #16a34a; }
        .kpi-card.gold { border-left-color: #d4af37; }
        .kpi-card.orange { border-left-color: #f59e0b; }
        .kpi-card.red { border-left-color: #dc2626; }
        
        .panel { background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .panel-title { font-size: 18px; font-weight: 700; margin-bottom: 15px; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f8f9fa; padding: 10px; text-align: left; font-weight: 600; }
        td { padding: 10px; border-bottom: 1px solid #eee; }
        .badge { padding: 3px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-green { background: #16a34a; color: #fff; }
        .badge-red { background: #dc2626; color: #fff; }
        .badge-gray { background: #888; color: #fff; }
        .badge-orange { background: #f59e0b; color: #fff; }
        .empty-state { text-align: center; padding: 20px; color: #888; }
        @media (max-width: 768px) { .kpi-grid { grid-template-columns: repeat(2, 1fr); } }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1><span>OMEGA</span> INFORMATIQUE CONSULTING</h1>
                <p style="color:#aaa;margin:0;">Tableau de bord</p>
            </div>
            <div>
                <span style="background:rgba(255,255,255,0.1);padding:5px 15px;border-radius:20px;">
                    <i class="bi bi-person"></i> <?= $_SESSION['user_nom'] ?? 'Administrateur' ?>
                </span>
                <a href="logout.php" class="btn btn-sm btn-outline-light ms-2">Déconnexion</a>
            </div>
        </div>
    </div>

    <!-- Actions rapides avec le bouton Email sécurisé -->
    <div class="actions-rapides">
        <a href="send_email_admin.php" class="btn-action btn-email">
            <i class="bi bi-envelope"></i> Envoyer un email
        </a>
        <a href="send_email_admin_secure.php" class="btn-action btn-secure">
            <i class="bi bi-envelope-shield"></i> Email sécurisé
        </a>
        <a href="quick_email.php" class="btn-action btn-quick">
            <i class="bi bi-bolt"></i> Email rapide
        </a>
        <a href="decisions.php" class="btn-action btn-decision">
            <i class="bi bi-bar-chart-line"></i> Aide à la décision
        </a>
        <?php if ($nbAlertesStock > 0): ?>
        <a href="decisions.php#alertes" class="btn-action btn-alert">
            <i class="bi bi-exclamation-triangle"></i> Alertes stock (<?= $nbAlertesStock ?>)
        </a>
        <?php endif; ?>
        <a href="catalogue.php" class="btn-action" style="background:#6b7280;color:#fff;">
            <i class="bi bi-grid-3x3-gap"></i> Catalogue
        </a>
    </div>

    <!-- KPIs -->
    <div class="kpi-grid">
        <div class="kpi-card"><div class="kpi-value"><?= $nbProduits ?></div><div class="kpi-label">Produits</div></div>
        <div class="kpi-card green"><div class="kpi-value"><?= $nbClients ?></div><div class="kpi-label">Clients</div></div>
        <div class="kpi-card gold"><div class="kpi-value"><?= $nbFournisseurs ?></div><div class="kpi-label">Fournisseurs</div></div>
        <div class="kpi-card orange"><div class="kpi-value"><?= number_format($caTotal, 0, ',', ' ') ?> FCFA</div><div class="kpi-label">Chiffre d'affaires</div></div>
        <div class="kpi-card red"><div class="kpi-value"><?= $nbAlertesStock ?></div><div class="kpi-label">Alertes stock</div></div>
    </div>

    <!-- Graphiques -->
    <div class="panel">
        <h2 class="panel-title">Statistiques</h2>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
            <div><canvas id="ventesChart" height="200"></canvas></div>
            <div><canvas id="categoriesChart" height="200"></canvas></div>
        </div>
    </div>

    <!-- Dernières factures -->
    <div class="panel">
        <h2 class="panel-title">Dernières factures</h2>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Numéro</th><th>Client</th><th>Date</th><th>Statut</th><th>Total</th></tr></thead>
                <tbody>
                <?php if (empty($dernieresFactures)): ?>
                    <tr><td colspan="5" class="empty-state">Aucune facture</td></tr>
                <?php else: foreach ($dernieresFactures as $f): ?>
                    <tr>
                        <td><strong><?= clean($f['numero']) ?></strong></td>
                        <td><?= clean(trim(($f['client_prenom'] ?? '') . ' ' . ($f['client_nom'] ?? ''))) ?></td>
                        <td><?= formatDate($f['date_facture'] ?? $f['date_creation'] ?? '') ?></td>
                        <td><span class="badge badge-<?= ($f['statut'] ?? 'brouillon') == 'payee' ? 'green' : 'gray' ?>"><?= ucfirst($f['statut'] ?? 'Brouillon') ?></span></td>
                        <td><?= number_format($f['total'] ?? 0, 0, ',', ' ') ?> FCFA</td>
                    </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Alerte stock faible -->
    <div class="panel">
        <h2 class="panel-title">Alerte stock faible</h2>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Produit</th><th>Stock</th><th>Prix</th></tr></thead>
                <tbody>
                <?php if (empty($produitsFaibleStock)): ?>
                    <tr><td colspan="3" class="empty-state">Tous les stocks sont corrects</td></tr>
                <?php else: foreach ($produitsFaibleStock as $p): ?>
                    <tr>
                        <td><?= clean($p['nom']) ?></td>
                        <td><span class="badge badge-red"><?= (int)$p['stock'] ?></span></td>
                        <td><?= number_format($p['prix'], 0, ',', ' ') ?> FCFA</td>
                    </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div style="text-align:center;color:#888;font-size:12px;border-top:1px solid #ddd;padding:20px 0;margin-top:20px;">
        OMEGA INFORMATIQUE CONSULTING &copy; <?= date('Y') ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ventesData = <?= json_encode($ventes) ?>;
    if (ventesData.length > 0) {
        new Chart(document.getElementById('ventesChart'), {
            type: 'line',
            data: { labels: ventesData.map(d => d.mois), datasets: [{ label: 'Ventes (FCFA)', data: ventesData.map(d => d.total), borderColor: '#2563eb', fill: true, tension: 0.3 }] },
            options: { responsive: true, plugins: { legend: { display: false } } }
        });
    }
    const categoriesData = <?= json_encode($categoriesData) ?>;
    if (categoriesData.length > 0) {
        new Chart(document.getElementById('categoriesChart'), {
            type: 'doughnut',
            data: { labels: categoriesData.map(d => d.nom || 'Sans catégorie'), datasets: [{ data: categoriesData.map(d => d.nb), backgroundColor: ['#2563eb', '#d4af37', '#16a34a', '#dc2626', '#ea580c', '#8b5cf6'] }] },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
        });
    }
});
</script>
</body>
</html>
