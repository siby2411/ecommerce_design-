<?php
$pageTitle = 'Aide à la décision';
$activePage = 'decisions';
require_once __DIR__ . '/includes/header.php';

$pdo = getPDO();

// Statistiques globales
$nbProduits = $pdo->query('SELECT COUNT(*) FROM produits')->fetchColumn();
$nbClients = $pdo->query('SELECT COUNT(*) FROM clients')->fetchColumn();
$nbFactures = $pdo->query('SELECT COUNT(*) FROM factures')->fetchColumn();
$nbAlertesStock = $pdo->query("SELECT COUNT(*) FROM produits WHERE stock <= 5")->fetchColumn();
$caTotal = $pdo->query("SELECT COALESCE(SUM(total),0) FROM factures WHERE statut='payee'")->fetchColumn();

// Produits en alerte stock
$alerteStock = $pdo->query("
    SELECT p.*, c.nom AS categorie
    FROM produits p
    LEFT JOIN categories c ON c.id = p.id_categorie
    WHERE p.stock <= 5
    ORDER BY p.stock ASC
    LIMIT 20
")->fetchAll();

// Top produits vendus
$topProduits = $pdo->query("
    SELECT 
        p.nom,
        p.prix,
        p.stock,
        COUNT(fl.id) AS nb_ventes,
        SUM(fl.sous_total) AS ca
    FROM produits p
    LEFT JOIN facture_lignes fl ON fl.id_produit = p.id
    LEFT JOIN factures f ON f.id = fl.id_facture AND f.statut = 'payee'
    GROUP BY p.id
    HAVING ca > 0
    ORDER BY ca DESC
    LIMIT 10
")->fetchAll();

// Top clients
$topClients = $pdo->query("
    SELECT 
        c.nom,
        c.prenom,
        c.telephone,
        COUNT(f.id) AS nb_factures,
        SUM(f.total) AS total_achats
    FROM clients c
    LEFT JOIN factures f ON f.id_client = c.id AND f.statut = 'payee'
    GROUP BY c.id
    HAVING total_achats > 0
    ORDER BY total_achats DESC
    LIMIT 5
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aide à la décision - OMEGA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border-left: 4px solid #2563eb;
            transition: all 0.3s;
        }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        .stat-card .stat-value { font-size: 28px; font-weight: 700; color: #1a1a2e; }
        .stat-card .stat-label { color: #888; font-size: 14px; }
        .stat-card.success { border-left-color: #16a34a; }
        .stat-card.warning { border-left-color: #f59e0b; }
        .stat-card.danger { border-left-color: #dc2626; }
        .stat-card.purple { border-left-color: #8b5cf6; }
        .badge-stock { padding: 4px 12px; border-radius: 20px; font-weight: 600; font-size: 12px; }
        .badge-stock.critique { background: #dc2626; color: #fff; }
        .badge-stock.alerte { background: #f59e0b; color: #fff; }
        .badge-stock.normal { background: #16a34a; color: #fff; }
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
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-value"><?= number_format($caTotal, 0, ',', ' ') ?> FCFA</div>
                <div class="stat-label">Chiffre d'affaires</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card success">
                <div class="stat-value"><?= $nbProduits ?></div>
                <div class="stat-label">Produits</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card warning">
                <div class="stat-value"><?= $nbClients ?></div>
                <div class="stat-label">Clients</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card danger">
                <div class="stat-value"><?= $nbAlertesStock ?></div>
                <div class="stat-label">Alertes stock</div>
            </div>
        </div>
    </div>

    <!-- Alertes stock -->
    <div class="card shadow-sm mb-4" id="alertes">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="bi bi-exclamation-triangle text-danger"></i> Produits en alerte stock</h5>
        </div>
        <div class="card-body">
            <?php if (empty($alerteStock)): ?>
                <div class="text-center text-success py-4">
                    <i class="bi bi-check-circle" style="font-size:2rem;"></i>
                    <p class="mt-2">Tous les stocks sont à un niveau correct</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr><th>Produit</th><th>Catégorie</th><th>Stock</th><th>Prix</th><th>Statut</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($alerteStock as $p): ?>
                                <tr>
                                    <td><?= clean($p['nom']) ?></td>
                                    <td><?= clean($p['categorie'] ?? 'Non classé') ?></td>
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
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="bi bi-trophy text-warning"></i> Top 10 produits</h5>
        </div>
        <div class="card-body">
            <?php if (empty($topProduits)): ?>
                <div class="text-center text-muted py-4">
                    <i class="bi bi-box" style="font-size:2rem;"></i>
                    <p class="mt-2">Aucune vente enregistrée</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr><th>#</th><th>Produit</th><th>Prix</th><th>Ventes</th><th>CA</th></tr>
                        </thead>
                        <tbody>
                            <?php $rank = 1; foreach ($topProduits as $p): ?>
                                <tr>
                                    <td><?= $rank++ ?></td>
                                    <td><?= clean($p['nom']) ?></td>
                                    <td><?= number_format($p['prix'], 0, ',', ' ') ?> FCFA</td>
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

    <!-- Top clients -->
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="bi bi-crown text-warning"></i> Top 5 clients</h5>
        </div>
        <div class="card-body">
            <?php if (empty($topClients)): ?>
                <div class="text-center text-muted py-4">
                    <i class="bi bi-people" style="font-size:2rem;"></i>
                    <p class="mt-2">Aucun client avec achats</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr><th>#</th><th>Client</th><th>Téléphone</th><th>Factures</th><th>Total</th></tr>
                        </thead>
                        <tbody>
                            <?php $rank = 1; foreach ($topClients as $c): ?>
                                <tr>
                                    <td><?= $rank++ ?></td>
                                    <td><?= clean(($c['prenom'] ?? '') . ' ' . ($c['nom'] ?? '')) ?></td>
                                    <td><?= clean($c['telephone'] ?? '') ?></td>
                                    <td><?= $c['nb_factures'] ?></td>
                                    <td><strong><?= number_format($c['total_achats'], 0, ',', ' ') ?> FCFA</strong></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="text-center text-muted small mt-4">
        OMEGA INFORMATIQUE CONSULTING &copy; <?= date('Y') ?>
    </div>
</div>
</body>
</html>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
