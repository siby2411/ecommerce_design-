<?php
$pageTitle = 'Prévisions de ventes';
$activePage = 'predictions';
require_once __DIR__ . '/includes/header.php';

$pdo = getPDO();

// Récupérer les données historiques
$stmt = $pdo->query("
    SELECT 
        DATE(f.date_creation) AS date,
        COALESCE(SUM(fl.sous_total),0) AS total,
        COUNT(DISTINCT f.id) AS nb_factures
    FROM factures f
    LEFT JOIN facture_lignes fl ON fl.id_facture = f.id
    WHERE f.statut = 'payee' AND f.date_creation >= DATE_SUB(CURDATE(), INTERVAL 90 DAY)
    GROUP BY DATE(f.date_creation)
    ORDER BY date ASC
");
$historique = $stmt->fetchAll();

// Calcul des tendances
$dates = [];
$ventes = [];
foreach ($historique as $row) {
    $dates[] = $row['date'];
    $ventes[] = (float)$row['total'];
}

// Calcul de la moyenne mobile (7 jours)
$moyenneMobile = [];
$size = count($ventes);
for ($i = 0; $i < $size; $i++) {
    $somme = 0;
    $count = 0;
    for ($j = max(0, $i - 6); $j <= $i; $j++) {
        $somme += $ventes[$j];
        $count++;
    }
    $moyenneMobile[] = $count > 0 ? $somme / $count : 0;
}

// Prévision simple (régression linéaire)
function linearRegression($x, $y) {
    $n = count($x);
    if ($n == 0) return ['slope' => 0, 'intercept' => 0];
    $sumX = array_sum($x);
    $sumY = array_sum($y);
    $sumXY = 0;
    $sumX2 = 0;
    for ($i = 0; $i < $n; $i++) {
        $sumXY += $x[$i] * $y[$i];
        $sumX2 += $x[$i] * $x[$i];
    }
    $slope = ($n * $sumXY - $sumX * $sumY) / ($n * $sumX2 - $sumX * $sumX);
    $intercept = ($sumY - $slope * $sumX) / $n;
    return ['slope' => $slope, 'intercept' => $intercept];
}

$indices = range(0, count($ventes) - 1);
$regression = linearRegression($indices, $ventes);

// Prévoir les 7 prochains jours
$previsions = [];
for ($i = 1; $i <= 7; $i++) {
    $nextIndex = count($ventes) + $i - 1;
    $previsions[] = [
        'jour' => date('Y-m-d', strtotime("+$i days")),
        'prevision' => max(0, $regression['slope'] * $nextIndex + $regression['intercept'])
    ];
}

// Statistiques
$caTotal = array_sum($ventes);
$moyenneJournaliere = $size > 0 ? $caTotal / $size : 0;
$tendance = $regression['slope'] > 0 ? '📈 Hausse' : ($regression['slope'] < 0 ? '📉 Baisse' : '➡️ Stable');

// Meilleurs produits prévus
$stmt = $pdo->query("
    SELECT 
        p.id,
        p.nom,
        p.prix,
        COALESCE(SUM(fl.quantite),0) AS nb_ventes,
        COALESCE(SUM(fl.sous_total),0) AS ca
    FROM produits p
    LEFT JOIN facture_lignes fl ON fl.id_produit = p.id
    LEFT JOIN factures f ON f.id = fl.id_facture AND f.statut = 'payee'
    GROUP BY p.id
    HAVING ca > 0
    ORDER BY ca DESC
    LIMIT 10
");
$meilleursProduits = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prévisions - OMEGA</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; color: #1a1a2e; }
        .container { max-width: 1400px; margin: 0 auto; padding: 20px; }
        .panel { background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .panel-title { font-size: 18px; font-weight: 700; margin-bottom: 15px; color: #1a1a2e; }
        .stat-card { background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border-left: 4px solid #2563eb; }
        .stat-card .stat-value { font-size: 28px; font-weight: 700; color: #1a1a2e; }
        .stat-card .stat-label { font-size: 14px; color: #888; margin-top: 5px; }
        .stat-card.warning { border-left-color: #f59e0b; }
        .stat-card.success { border-left-color: #16a34a; }
        .stat-card.danger { border-left-color: #dc2626; }
        .stat-card.purple { border-left-color: #8b5cf6; }
        .row { display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 20px; }
        .col-md-3 { flex: 1 1 calc(25% - 20px); min-width: 200px; }
        .col-md-6 { flex: 1 1 calc(50% - 20px); min-width: 300px; }
        .col-md-4 { flex: 1 1 calc(33% - 20px); min-width: 250px; }
        .col-lg-8 { flex: 1 1 calc(66% - 20px); min-width: 400px; }
        .col-lg-4 { flex: 1 1 calc(33% - 20px); min-width: 250px; }
        @media (max-width: 768px) { .col-md-3, .col-md-6, .col-md-4, .col-lg-8, .col-lg-4 { flex: 1 1 100%; } }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f8f9fa; padding: 10px; text-align: left; font-weight: 600; }
        td { padding: 10px; border-bottom: 1px solid #eee; }
        .badge { padding: 2px 10px; border-radius: 12px; font-size: 12px; font-weight: 600; }
        .badge-success { background: #16a34a; color: #fff; }
        .badge-warning { background: #f59e0b; color: #fff; }
        .badge-danger { background: #dc2626; color: #fff; }
        .badge-info { background: #2563eb; color: #fff; }
    </style>
</head>
<body>
<div class="container">
    <h1><i class="fas fa-chart-line"></i> Prévisions de ventes</h1>
    <p>Analyse prédictive et tendances</p>

    <div class="row">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-value"><?= number_format($caTotal, 0, ',', ' ') ?> FCFA</div>
                <div class="stat-label">CA total (90 jours)</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card success">
                <div class="stat-value"><?= number_format($moyenneJournaliere, 0, ',', ' ') ?> FCFA</div>
                <div class="stat-label">Moyenne journalière</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card warning">
                <div class="stat-value"><?= $tendance ?></div>
                <div class="stat-label">Tendance</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card purple">
                <div class="stat-value"><?= count($historique) ?> jours</div>
                <div class="stat-label">Historique analysé</div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="panel">
        <h2 class="panel-title">Historique et prévisions</h2>
        <div style="height:300px;">
            <canvas id="predictionChart"></canvas>
        </div>
    </div>

    <div class="row">
        <!-- Prévisions des 7 prochains jours -->
        <div class="col-md-6">
            <div class="panel">
                <h2 class="panel-title">📊 Prévisions 7 jours</h2>
                <div style="height:200px;">
                    <canvas id="forecastChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Meilleurs produits prévus -->
        <div class="col-md-6">
            <div class="panel">
                <h2 class="panel-title">🏆 Top produits prévus</h2>
                <div class="table-responsive">
                    <table>
                        <thead><tr><th>Produit</th><th>Prix</th><th>Ventes</th><th>CA</th></tr></thead>
                        <tbody>
                        <?php foreach ($meilleursProduits as $p): ?>
                            <tr>
                                <td><?= htmlspecialchars($p['nom']) ?></td>
                                <td><?= number_format($p['prix'], 0, ',', ' ') ?> FCFA</td>
                                <td><?= $p['nb_ventes'] ?></td>
                                <td><strong><?= number_format($p['ca'], 0, ',', ' ') ?> FCFA</strong></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="panel">
        <h2 class="panel-title">📌 Recommandations</h2>
        <div style="background:#f8f9fa;padding:15px;border-radius:8px;">
            <?php if ($regression['slope'] > 0): ?>
                <p>✅ <strong>Tendance haussière</strong> - Augmentez vos stocks pour les produits les plus vendus.</p>
                <p>📊 Votre chiffre d'affaires devrait augmenter de <?= number_format($regression['slope'] * 30, 0, ',', ' ') ?> FCFA sur les 30 prochains jours.</p>
            <?php elseif ($regression['slope'] < 0): ?>
                <p>⚠️ <strong>Tendance baissière</strong> - Considérez des promotions ou des actions marketing.</p>
                <p>📉 Votre chiffre d'affaires pourrait diminuer de <?= number_format(abs($regression['slope'] * 30), 0, ',', ' ') ?> FCFA sur les 30 prochains jours.</p>
            <?php else: ?>
                <p>➡️ <strong>Tendance stable</strong> - Maintenez vos efforts actuels.</p>
            <?php endif; ?>
            <p>🔍 Produits à réapprovisionner : <?= count(array_filter($meilleursProduits, fn($p) => $p['nb_ventes'] > 5)) ?> produits ont des ventes significatives.</p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Données historiques et prévisions
    const historique = <?= json_encode($historique) ?>;
    const previsions = <?= json_encode($previsions) ?>;
    const moyenneMobile = <?= json_encode($moyenneMobile) ?>;
    
    // Graphique d'évolution
    const ctx1 = document.getElementById('predictionChart').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: historique.map(d => d.date),
            datasets: [
                {
                    label: 'Ventes réelles',
                    data: historique.map(d => parseFloat(d.total)),
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37,99,235,0.1)',
                    fill: true,
                    tension: 0.3
                },
                {
                    label: 'Moyenne mobile (7j)',
                    data: moyenneMobile,
                    borderColor: '#f59e0b',
                    borderDash: [5, 5],
                    fill: false,
                    tension: 0.3,
                    pointRadius: 0
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'top' } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { callback: function(value) { return value.toLocaleString() + ' FCFA'; } }
                }
            }
        }
    });

    // Prévisions 7 jours
    const ctx2 = document.getElementById('forecastChart').getContext('2d');
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: previsions.map(d => d.jour),
            datasets: [{
                label: 'Prévision (FCFA)',
                data: previsions.map(d => Math.round(d.prevision)),
                backgroundColor: '#2563eb',
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { callback: function(value) { return value.toLocaleString(); } }
                }
            }
        }
    });
});
</script>
</body>
</html>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
