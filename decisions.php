<?php
$pageTitle = 'Aide à la décision';
$activePage = 'decisions';
require_once __DIR__ . '/includes/header.php';

// Initialiser PDO
$pdo = getPDO();

// Statistiques globales
$stats = [];

// Nombre de produits
$stmt = $pdo->query("SELECT COUNT(*) FROM produits");
$stats['nb_produits'] = $stmt->fetchColumn();

// Nombre de clients
$stmt = $pdo->query("SELECT COUNT(*) FROM clients");
$stats['nb_clients'] = $stmt->fetchColumn();

// Nombre de factures
$stmt = $pdo->query("SELECT COUNT(*) FROM factures");
$stats['nb_factures'] = $stmt->fetchColumn();

// Chiffre d'affaires
$stmt = $pdo->query("SELECT COALESCE(SUM(total),0) FROM factures WHERE statut = 'payee'");
$stats['ca_total'] = $stmt->fetchColumn();

// Alertes stock
$stmt = $pdo->query("SELECT COUNT(*) FROM produits WHERE stock <= 5");
$stats['nb_alertes_stock'] = $stmt->fetchColumn();

// Produits en alerte stock
$stmt = $pdo->query("
    SELECT p.*, c.nom AS categorie
    FROM produits p
    LEFT JOIN categories c ON c.id = p.id_categorie
    WHERE p.stock <= 5
    ORDER BY p.stock ASC
    LIMIT 20
");
$alerteStock = $stmt->fetchAll();

// Top produits
$stmt = $pdo->query("
    SELECT 
        p.nom,
        p.prix,
        p.stock,
        COUNT(fl.id) AS nb_ventes,
        COALESCE(SUM(fl.sous_total),0) AS ca
    FROM produits p
    LEFT JOIN facture_lignes fl ON fl.id_produit = p.id
    LEFT JOIN factures f ON f.id = fl.id_facture AND f.statut = 'payee'
    GROUP BY p.id
    HAVING ca > 0
    ORDER BY ca DESC
    LIMIT 10
");
$topProduits = $stmt->fetchAll();

// Top clients
$stmt = $pdo->query("
    SELECT 
        c.nom,
        c.prenom,
        c.telephone,
        COUNT(f.id) AS nb_factures,
        COALESCE(SUM(f.total),0) AS total_achats
    FROM clients c
    LEFT JOIN factures f ON f.id_client = c.id AND f.statut = 'payee'
    GROUP BY c.id
    HAVING total_achats > 0
    ORDER BY total_achats DESC
    LIMIT 5
");
$topClients = $stmt->fetchAll();

// Évolution des ventes
$stmt = $pdo->query("
    SELECT 
        DATE(f.date_creation) AS date,
        COALESCE(SUM(fl.sous_total),0) AS total
    FROM factures f
    LEFT JOIN facture_lignes fl ON fl.id_facture = f.id
    WHERE f.statut = 'payee' 
        AND f.date_creation >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
    GROUP BY DATE(f.date_creation)
    ORDER BY date ASC
");
$evolution = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aide à la décision - OMEGA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border-left: 4px solid #2563eb;
            transition: all 0.3s;
            margin-bottom: 20px;
        }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        .stat-card .stat-value { font-size: 28px; font-weight: 700; color: #1a1a2e; }
        .stat-card .stat-label { color: #888; font-size: 14px; }
        .stat-card .stat-sub { color: #aaa; font-size: 12px; margin-top: 5px; }
        .stat-card.success { border-left-color: #16a34a; }
        .stat-card.warning { border-left-color: #f59e0b; }
        .stat-card.danger { border-left-color: #dc2626; }
        .stat-card.purple { border-left-color: #8b5cf6; }
        .stat-card.gold { border-left-color: #d4af37; }
        
        .badge-stock { padding: 4px 12px; border-radius: 20px; font-weight: 600; font-size: 12px; }
        .badge-stock.critique { background: #dc2626; color: #fff; }
        .badge-stock.alerte { background: #f59e0b; color: #fff; }
        .badge-stock.normal { background: #16a34a; color: #fff; }
        
        .panel { background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .panel-title { font-size: 18px; font-weight: 700; margin-bottom: 15px; color: #1a1a2e; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f8f9fa; padding: 10px; text-align: left; font-weight: 600; }
        td { padding: 10px; border-bottom: 1px solid #eee; }
        .row { display: flex; flex-wrap: wrap; gap: 20px; }
        .col-md-3 { flex: 1 1 calc(25% - 20px); min-width: 200px; }
        .col-md-6 { flex: 1 1 calc(50% - 20px); min-width: 300px; }
        .col-md-12 { flex: 1 1 100%; }
        .empty-state { text-align: center; padding: 30px; color: #888; }
        @media (max-width: 768px) { .col-md-3 { flex: 1 1 100%; } .col-md-6 { flex: 1 1 100%; } }
    </style>
</head>
<body>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-bar-chart-line text-primary"></i> Aide à la décision</h2>
        <a href="index.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

    <!-- KPIs -->
    <div class="row">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-value"><?= number_format($stats['ca_total'] ?? 0, 0, ',', ' ') ?> FCFA</div>
                <div class="stat-label">Chiffre d'affaires</div>
                <div class="stat-sub">factures payées</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card success">
                <div class="stat-value"><?= (int)($stats['nb_produits'] ?? 0) ?></div>
                <div class="stat-label">Produits</div>
                <div class="stat-sub">au catalogue</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card warning">
                <div class="stat-value"><?= (int)($stats['nb_clients'] ?? 0) ?></div>
                <div class="stat-label">Clients</div>
                <div class="stat-sub">enregistrés</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card danger">
                <div class="stat-value"><?= (int)($stats['nb_alertes_stock'] ?? 0) ?></div>
                <div class="stat-label">Alertes stock</div>
                <div class="stat-sub">produits sous le seuil</div>
            </div>
        </div>
    </div>

    <!-- Graphique évolution -->
    <div class="panel">
        <h2 class="panel-title">Évolution des ventes (30 jours)</h2>
        <div style="height:250px;">
            <canvas id="evolutionChart"></canvas>
        </div>
    </div>

    <div class="row">
        <!-- Alertes stock -->
        <div class="col-md-6">
            <div class="panel">
                <h2 class="panel-title"><i class="bi bi-exclamation-triangle text-danger"></i> Alertes stock</h2>
                <?php if (empty($alerteStock)): ?>
                    <div class="empty-state">
                        <i class="bi bi-check-circle text-success" style="font-size:2rem;"></i>
                        <p class="mt-2">Tous les stocks sont corrects</p>
                    </div>
                <?php else: ?>
                    <div class="table-wrap">
                        <table>
                            <thead><tr><th>Produit</th><th>Stock</th><th>Prix</th><th>Statut</th></tr></thead>
                            <tbody>
                                <?php foreach ($alerteStock as $p): ?>
                                    <tr>
                                        <td><?= clean($p['nom']) ?></td>
                                        <td><strong class="text-danger"><?= $p['stock'] ?></strong></td>
                                        <td><?= number_format($p['prix'], 0, ',', ' ') ?> FCFA</td>
                                        <td>
                                            <?php if ($p['stock'] <= 1): ?>
                                                <span class="badge-stock critique">⚠️ Critique</span>
                                            <?php elseif ($p['stock'] <= 3): ?>
                                                <span class="badge-stock alerte">⚡ Alerte</span>
                                            <?php else: ?>
                                                <span class="badge-stock normal">✅ Normal</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Top produits -->
        <div class="col-md-6">
            <div class="panel">
                <h2 class="panel-title"><i class="bi bi-trophy text-warning"></i> Top produits</h2>
                <?php if (empty($topProduits)): ?>
                    <div class="empty-state">
                        <i class="bi bi-box" style="font-size:2rem;"></i>
                        <p class="mt-2">Aucune vente enregistrée</p>
                    </div>
                <?php else: ?>
                    <div class="table-wrap">
                        <table>
                            <thead><tr><th>#</th><th>Produit</th><th>Ventes</th><th>CA</th></tr></thead>
                            <tbody>
                                <?php $rank = 1; foreach ($topProduits as $p): ?>
                                    <tr>
                                        <td><?= $rank++ ?></td>
                                        <td><?= clean($p['nom']) ?></td>
                                        <td><?= $p['nb_ventes'] ?></td>
                                        <td><strong><?= number_format($p['ca'], 0, ',', ' ') ?> FCFA</strong></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Top clients -->
    <div class="panel">
        <h2 class="panel-title"><i class="bi bi-crown text-warning"></i> Top clients</h2>
        <?php if (empty($topClients)): ?>
            <div class="empty-state">
                <i class="bi bi-people" style="font-size:2rem;"></i>
                <p class="mt-2">Aucun client avec achats</p>
            </div>
        <?php else: ?>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>#</th><th>Client</th><th>Téléphone</th><th>Factures</th><th>Total</th></tr></thead>
                    <tbody>
                        <?php $rank = 1; foreach ($topClients as $c): ?>
                            <tr>
                                <td><?= $rank++ ?></td>
                                <td><?= clean(($c['prenom'] ?? '') . ' ' . ($c['nom'] ?? '')) ?></td>
                                <td><?= clean($c['telephone'] ?? '') ?></td>
                                <td><?= $c['nb_factures'] ?></td>
                                <td><strong><?= number_format($c['total_achats'] ?? 0, 0, ',', ' ') ?> FCFA</strong></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <div class="text-center text-muted small mt-4">
        OMEGA INFORMATIQUE CONSULTING &copy; <?= date('Y') ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const evolutionData = <?= json_encode($evolution) ?>;
    if (evolutionData && evolutionData.length > 0) {
        const ctx = document.getElementById('evolutionChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: evolutionData.map(d => d.date),
                datasets: [{
                    label: 'Ventes (FCFA)',
                    data: evolutionData.map(d => parseFloat(d.total) || 0),
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37,99,235,0.1)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { 
                        beginAtZero: true,
                        ticks: { callback: function(value) { return value.toLocaleString() + ' FCFA'; } }
                    }
                }
            }
        });
    }
});
</script>
</body>
</html>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
