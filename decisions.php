<?php
$pageTitle = 'Aide à la décision';
$activePage = 'decisions';
require_once __DIR__ . '/includes/header.php';

// Récupérer les paramètres de filtrage
$period = $_GET['period'] ?? 'month';
$date_debut = $_GET['date_debut'] ?? date('Y-m-01');
$date_fin = $_GET['date_fin'] ?? date('Y-m-d');
$seuil_stock = (int)($_GET['seuil_stock'] ?? 5);

// Construire la condition de date (utiliser date_creation)
$dateCondition = "1=1";
if ($period === 'day') {
    $dateCondition = "f.date_creation >= CURDATE()";
    $date_debut = date('Y-m-d');
    $date_fin = date('Y-m-d');
} elseif ($period === 'week') {
    $dateCondition = "f.date_creation >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
    $date_debut = date('Y-m-d', strtotime('-7 days'));
    $date_fin = date('Y-m-d');
} elseif ($period === 'month') {
    $dateCondition = "f.date_creation >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
    $date_debut = date('Y-m-d', strtotime('-30 days'));
    $date_fin = date('Y-m-d');
} elseif ($period === 'year') {
    $dateCondition = "f.date_creation >= DATE_SUB(CURDATE(), INTERVAL 365 DAY)";
    $date_debut = date('Y-m-d', strtotime('-365 days'));
    $date_fin = date('Y-m-d');
} elseif ($period === 'custom') {
    $dateCondition = "f.date_creation BETWEEN '$date_debut' AND '$date_fin'";
}

// 1. Statistiques globales
$sqlStats = "
    SELECT 
        (SELECT COUNT(*) FROM produits) AS total_produits,
        (SELECT COUNT(*) FROM factures WHERE statut = 'payee') AS total_factures,
        (SELECT COUNT(*) FROM facture_lignes) AS total_articles_vendus,
        (SELECT COALESCE(SUM(sous_total),0) FROM facture_lignes) AS ca_total
";
$statsGlobales = $pdo->query($sqlStats)->fetch();

// 2. Meilleures ventes par produit
$sqlMeilleuresVentes = "
    SELECT 
        p.id,
        p.nom,
        p.prix,
        p.stock,
        c.nom AS categorie,
        COUNT(fl.id) AS nb_ventes,
        COALESCE(SUM(fl.quantite),0) AS quantite_totale,
        COALESCE(SUM(fl.sous_total),0) AS chiffre_affaires
    FROM produits p
    LEFT JOIN categories c ON c.id = p.id_categorie
    LEFT JOIN facture_lignes fl ON fl.id_produit = p.id
    LEFT JOIN factures f ON f.id = fl.id_facture AND f.statut = 'payee'
    WHERE $dateCondition
    GROUP BY p.id
    HAVING chiffre_affaires > 0
    ORDER BY chiffre_affaires DESC
    LIMIT 20
";
$meilleuresVentes = $pdo->query($sqlMeilleuresVentes)->fetchAll();

// 3. Meilleures ventes par catégorie
$sqlVentesParCategorie = "
    SELECT 
        c.id,
        c.nom AS categorie,
        COUNT(DISTINCT p.id) AS nb_produits,
        COALESCE(SUM(fl.quantite),0) AS quantite_totale,
        COALESCE(SUM(fl.sous_total),0) AS chiffre_affaires
    FROM categories c
    LEFT JOIN produits p ON p.id_categorie = c.id
    LEFT JOIN facture_lignes fl ON fl.id_produit = p.id
    LEFT JOIN factures f ON f.id = fl.id_facture AND f.statut = 'payee'
    WHERE $dateCondition
    GROUP BY c.id
    HAVING chiffre_affaires > 0
    ORDER BY chiffre_affaires DESC
";
$ventesParCategorie = $pdo->query($sqlVentesParCategorie)->fetchAll();

// 4. Produits en alerte stock
$sqlAlerteStock = "
    SELECT 
        p.id,
        p.nom,
        p.stock,
        p.prix,
        c.nom AS categorie,
        (SELECT COUNT(*) FROM facture_lignes fl2 WHERE fl2.id_produit = p.id) AS nb_ventes
    FROM produits p
    LEFT JOIN categories c ON c.id = p.id_categorie
    WHERE p.stock <= $seuil_stock
    ORDER BY p.stock ASC
    LIMIT 20
";
$alerteStock = $pdo->query($sqlAlerteStock)->fetchAll();

// 5. Évolution des ventes (graphique)
$sqlEvolution = "
    SELECT 
        DATE(f.date_creation) AS date,
        COALESCE(SUM(fl.sous_total),0) AS total
    FROM factures f
    LEFT JOIN facture_lignes fl ON fl.id_facture = f.id
    WHERE f.statut = 'payee' 
        AND f.date_creation >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
    GROUP BY DATE(f.date_creation)
    ORDER BY date ASC
";
$evolution = $pdo->query($sqlEvolution)->fetchAll();

// 6. Top 5 clients
$sqlTopClients = "
    SELECT 
        cl.id,
        cl.nom,
        cl.prenom,
        cl.telephone,
        COUNT(DISTINCT f.id) AS nb_factures,
        COALESCE(SUM(fl.sous_total),0) AS total_achats
    FROM clients cl
    LEFT JOIN factures f ON f.id_client = cl.id AND f.statut = 'payee'
    LEFT JOIN facture_lignes fl ON fl.id_facture = f.id
    WHERE $dateCondition
    GROUP BY cl.id
    HAVING total_achats > 0
    ORDER BY total_achats DESC
    LIMIT 5
";
$topClients = $pdo->query($sqlTopClients)->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aide à la décision</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; color: #1a1a2e; }
        .container { max-width: 1400px; margin: 0 auto; padding: 20px; }
        .stat-card { background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); transition: all 0.3s; border-left: 4px solid #2563eb; margin-bottom: 20px; }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        .stat-card .stat-value { font-size: 28px; font-weight: 700; color: #1a1a2e; }
        .stat-card .stat-label { font-size: 14px; color: #888; margin-top: 5px; }
        .stat-card .stat-icon { font-size: 24px; opacity: 0.3; float: right; }
        .stat-card.warning { border-left-color: #f59e0b; }
        .stat-card.success { border-left-color: #16a34a; }
        .stat-card.danger { border-left-color: #dc2626; }
        .stat-card.purple { border-left-color: #8b5cf6; }
        .panel { background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .panel-title { font-size: 18px; font-weight: 700; margin-bottom: 15px; color: #1a1a2e; }
        .table-responsive { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f8f9fa; padding: 10px; text-align: left; font-weight: 600; }
        td { padding: 10px; border-bottom: 1px solid #eee; }
        .badge-stock { padding: 4px 12px; border-radius: 20px; font-weight: 600; font-size: 12px; }
        .badge-stock.critique { background: #dc2626; color: #fff; }
        .badge-stock.alerte { background: #f59e0b; color: #fff; }
        .badge-stock.normal { background: #16a34a; color: #fff; }
        .filter-bar { background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 25px; }
        .btn { padding: 8px 16px; border: none; border-radius: 6px; cursor: pointer; }
        .btn-primary { background: #2563eb; color: #fff; }
        .btn-success { background: #16a34a; color: #fff; }
        .row { display: flex; flex-wrap: wrap; gap: 20px; }
        .col-md-3 { flex: 1 1 calc(25% - 20px); min-width: 200px; }
        .col-lg-6 { flex: 1 1 calc(50% - 20px); min-width: 300px; }
        .col-lg-8 { flex: 1 1 calc(66% - 20px); min-width: 400px; }
        .col-lg-4 { flex: 1 1 calc(33% - 20px); min-width: 250px; }
        @media (max-width: 768px) { .col-md-3, .col-lg-6, .col-lg-8, .col-lg-4 { flex: 1 1 100%; } }
    </style>
</head>
<body>
<div class="container">
    <h1><i class="fas fa-chart-line"></i> Aide à la décision</h1>
    <p>Analyse des performances et alertes pour une meilleure gestion</p>

    <!-- Barre de filtrage -->
    <div class="filter-bar">
        <form method="get" style="display:flex;flex-wrap:wrap;gap:10px;align-items:end;">
            <div>
                <label>Période</label>
                <select name="period" onchange="this.form.submit()" style="padding:8px;border-radius:6px;border:1px solid #ddd;">
                    <option value="day" <?= $period == 'day' ? 'selected' : '' ?>>Aujourd'hui</option>
                    <option value="week" <?= $period == 'week' ? 'selected' : '' ?>>7 derniers jours</option>
                    <option value="month" <?= $period == 'month' ? 'selected' : '' ?>>30 derniers jours</option>
                    <option value="year" <?= $period == 'year' ? 'selected' : '' ?>>12 derniers mois</option>
                    <option value="custom" <?= $period == 'custom' ? 'selected' : '' ?>>Personnalisé</option>
                </select>
            </div>
            <?php if ($period == 'custom'): ?>
            <div>
                <label>Date début</label>
                <input type="date" name="date_debut" value="<?= $date_debut ?>" style="padding:8px;border-radius:6px;border:1px solid #ddd;">
            </div>
            <div>
                <label>Date fin</label>
                <input type="date" name="date_fin" value="<?= $date_fin ?>" style="padding:8px;border-radius:6px;border:1px solid #ddd;">
            </div>
            <?php endif; ?>
            <div>
                <label>Seuil stock</label>
                <input type="number" name="seuil_stock" value="<?= $seuil_stock ?>" style="padding:8px;border-radius:6px;border:1px solid #ddd;width:70px;">
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Filtrer</button>
            </div>
        </form>
    </div>

    <!-- Statistiques globales -->
    <div class="row">
        <div class="col-md-3">
            <div class="stat-card">
                <span class="stat-icon"><i class="fas fa-shopping-cart"></i></span>
                <div class="stat-value"><?= number_format($statsGlobales['ca_total'] ?? 0, 0, ',', ' ') ?> FCFA</div>
                <div class="stat-label">Chiffre d'affaires</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card success">
                <span class="stat-icon"><i class="fas fa-box"></i></span>
                <div class="stat-value"><?= $statsGlobales['total_articles_vendus'] ?? 0 ?></div>
                <div class="stat-label">Articles vendus</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card warning">
                <span class="stat-icon"><i class="fas fa-receipt"></i></span>
                <div class="stat-value"><?= $statsGlobales['total_factures'] ?? 0 ?></div>
                <div class="stat-label">Factures payées</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card danger">
                <span class="stat-icon"><i class="fas fa-exclamation-triangle"></i></span>
                <div class="stat-value"><?= count($alerteStock) ?></div>
                <div class="stat-label">Produits en alerte stock</div>
            </div>
        </div>
    </div>

    <!-- Graphique d'évolution -->
    <div class="panel">
        <h2 class="panel-title">Évolution des ventes (30 jours)</h2>
        <div style="height:250px;">
            <canvas id="evolutionChart"></canvas>
        </div>
    </div>

    <div class="row">
        <!-- Meilleures ventes par produit -->
        <div class="col-lg-6">
            <div class="panel">
                <h2 class="panel-title"><i class="fas fa-trophy" style="color:#f59e0b;"></i> Top 20 produits</h2>
                <div class="table-responsive">
                    <table>
                        <thead><tr><th>#</th><th>Produit</th><th>Catégorie</th><th>Qté</th><th>CA</th></tr></thead>
                        <tbody>
                        <?php if (empty($meilleuresVentes)): ?>
                            <tr><td colspan="5" style="text-align:center;padding:20px;color:#888;">Aucune donnée disponible</td></tr>
                        <?php else: $rank = 1; foreach ($meilleuresVentes as $p): ?>
                            <tr>
                                <td><?= $rank++ ?></td>
                                <td><?= htmlspecialchars($p['nom']) ?></td>
                                <td><?= htmlspecialchars($p['categorie'] ?? 'Non classé') ?></td>
                                <td><?= $p['quantite_totale'] ?? 0 ?></td>
                                <td><strong><?= number_format($p['chiffre_affaires'] ?? 0, 0, ',', ' ') ?> FCFA</strong></td>
                            </tr>
                        <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Meilleures ventes par catégorie -->
        <div class="col-lg-6">
            <div class="panel">
                <h2 class="panel-title"><i class="fas fa-tags" style="color:#2563eb;"></i> Ventes par catégorie</h2>
                <div class="table-responsive">
                    <table>
                        <thead><tr><th>#</th><th>Catégorie</th><th>Produits</th><th>Qté</th><th>CA</th></tr></thead>
                        <tbody>
                        <?php if (empty($ventesParCategorie)): ?>
                            <tr><td colspan="5" style="text-align:center;padding:20px;color:#888;">Aucune donnée disponible</td></tr>
                        <?php else: $rank = 1; foreach ($ventesParCategorie as $cat): ?>
                            <tr>
                                <td><?= $rank++ ?></td>
                                <td><?= htmlspecialchars($cat['categorie']) ?></td>
                                <td><?= $cat['nb_produits'] ?></td>
                                <td><?= $cat['quantite_totale'] ?? 0 ?></td>
                                <td><strong><?= number_format($cat['chiffre_affaires'] ?? 0, 0, ',', ' ') ?> FCFA</strong></td>
                            </tr>
                        <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerte stock -->
    <div class="panel">
        <h2 class="panel-title"><i class="fas fa-exclamation-triangle" style="color:#dc2626;"></i> Alerte stock <span style="background:#dc2626;color:#fff;padding:2px 10px;border-radius:20px;font-size:12px;margin-left:10px;"><?= count($alerteStock) ?></span></h2>
        <div class="table-responsive">
            <table>
                <thead><tr><th>Produit</th><th>Catégorie</th><th>Stock</th><th>Prix</th><th>Ventes</th><th>Statut</th></tr></thead>
                <tbody>
                <?php if (empty($alerteStock)): ?>
                    <tr><td colspan="6" style="text-align:center;padding:20px;color:#16a34a;">
                        <i class="fas fa-check-circle"></i> Tous les stocks sont à un niveau correct
                    </td></tr>
                <?php else: foreach ($alerteStock as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['nom']) ?></td>
                        <td><?= htmlspecialchars($p['categorie'] ?? 'Non classé') ?></td>
                        <td><strong><?= $p['stock'] ?></strong></td>
                        <td><?= number_format($p['prix'], 0, ',', ' ') ?> FCFA</td>
                        <td><?= $p['nb_ventes'] ?? 0 ?></td>
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
                <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Top clients -->
    <div class="panel">
        <h2 class="panel-title"><i class="fas fa-crown" style="color:#f59e0b;"></i> Top 5 clients</h2>
        <div class="table-responsive">
            <table>
                <thead><tr><th>#</th><th>Client</th><th>Téléphone</th><th>Factures</th><th>Total</th></tr></thead>
                <tbody>
                <?php if (empty($topClients)): ?>
                    <tr><td colspan="5" style="text-align:center;padding:20px;color:#888;">Aucune donnée disponible</td></tr>
                <?php else: $rank = 1; foreach ($topClients as $c): ?>
                    <tr>
                        <td><?= $rank++ ?></td>
                        <td><?= htmlspecialchars(($c['prenom'] ?? '') . ' ' . ($c['nom'] ?? '')) ?></td>
                        <td><?= htmlspecialchars($c['telephone'] ?? '') ?></td>
                        <td><?= $c['nb_factures'] ?></td>
                        <td><strong><?= number_format($c['total_achats'] ?? 0, 0, ',', ' ') ?> FCFA</strong></td>
                    </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const evolutionData = <?= json_encode($evolution) ?>;
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
                y: { beginAtZero: true, ticks: { callback: function(value) { return value.toLocaleString() + ' FCFA'; } } }
            }
        }
    });
});
</script>
</body>
</html>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
