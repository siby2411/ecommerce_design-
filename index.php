<?php
$pageTitle  = 'Tableau de bord';
$activePage = 'dashboard';
require_once __DIR__ . '/includes/header.php';

// Statistiques KPI
$nbProduits    = $pdo->query('SELECT COUNT(*) AS nb FROM produits')->fetch()['nb'];
$nbClients     = $pdo->query('SELECT COUNT(*) AS nb FROM clients')->fetch()['nb'];
$nbFournisseurs= $pdo->query('SELECT COUNT(*) AS nb FROM fournisseurs')->fetch()['nb'];
$caTotal       = $pdo->query("SELECT COALESCE(SUM(total),0) AS ca FROM factures WHERE statut = 'payee'")->fetch()['ca'];
$nbFacturesImpayees = $pdo->query("SELECT COUNT(*) AS nb FROM factures WHERE statut = 'impayee'")->fetch()['nb'];

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

<!-- ================================================= -->
<!-- BANDEAU PUBLICITAIRE AVEC L'IMAGE OK1.JPEG        -->
<!-- ================================================= -->
<div class="banner-dashboard">
    <div class="banner-dashboard-content">
        <img src="ok1.jpeg" alt="Promotion OMEGA" loading="lazy">
        <div class="banner-dashboard-text">
            <h3>🔥 Offre Spéciale</h3>
            <p>Découvrez nos produits en promotion</p>
            <span class="promo-badge">- 30%</span>
        </div>
    </div>
</div>

<div class="page-head">
    <div>
        <p>Bienvenue, <?= clean($_SESSION['user_nom']) ?>. Voici la synthèse de votre activité.</p>
    </div>
</div>

<div class="kpi-grid">
    <div class="kpi-card">
        <div class="kpi-label">Produits</div>
        <div class="kpi-value"><?= (int)$nbProduits ?></div>
        <div class="kpi-sub">références au catalogue</div>
    </div>
    <div class="kpi-card green">
        <div class="kpi-label">Clients</div>
        <div class="kpi-value"><?= (int)$nbClients ?></div>
        <div class="kpi-sub">clients enregistrés</div>
    </div>
    <div class="kpi-card gold">
        <div class="kpi-label">Fournisseurs</div>
        <div class="kpi-value"><?= (int)$nbFournisseurs ?></div>
        <div class="kpi-sub">partenaires actifs</div>
    </div>
    <div class="kpi-card orange">
        <div class="kpi-label">Chiffre d'affaires</div>
        <div class="kpi-value" style="font-size:20px;"><?= formatPrix($caTotal) ?></div>
        <div class="kpi-sub"><?= (int)$nbFacturesImpayees ?> facture(s) impayée(s)</div>
    </div>
</div>

<!-- Graphiques -->
<div class="panel">
    <h2 class="panel-title">Statistiques</h2>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
        <div><canvas id="ventesChart" height="200"></canvas></div>
        <div><canvas id="categoriesChart" height="200"></canvas></div>
    </div>
</div>

<div class="panel">
    <h2 class="panel-title">Dernières factures</h2>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Numéro</th><th>Client</th><th>Date</th><th>Statut</th><th>Total</th><th></th></tr>
            </thead>
            <tbody>
            <?php if (empty($dernieresFactures)): ?>
                <tr><td colspan="6" class="empty-state">Aucune facture pour le moment.</td></tr>
            <?php else: foreach ($dernieresFactures as $f): ?>
                <tr>
                    <td><strong><?= clean($f['numero']) ?></strong></td>
                    <td><?= clean(trim($f['client_prenom'] . ' ' . $f['client_nom'])) ?></td>
                    <td><?= formatDate($f['date_facture']) ?></td>
                    <td>
                        <?php
                        $map = ['payee'=>'green','impayee'=>'red','brouillon'=>'gray','annulee'=>'orange'];
                        $color = $map[$f['statut']] ?? 'gray';
                        ?>
                        <span class="badge badge-<?= $color ?>"><?= ucfirst($f['statut']) ?></span>
                    </td>
                    <td><?= formatPrix($f['total']) ?></td>
                    <td><a href="<?= BASE_URL ?>/factures/voir.php?id=<?= $f['id'] ?>" class="btn btn-outline btn-sm">Voir</a></td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="panel">
    <h2 class="panel-title">Alerte stock faible</h2>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Produit</th><th>Stock restant</th><th>Prix</th><th></th></tr></thead>
            <tbody>
            <?php if (empty($produitsFaibleStock)): ?>
                <tr><td colspan="4" class="empty-state">Tous les stocks sont à un niveau correct.</td></tr>
            <?php else: foreach ($produitsFaibleStock as $p): ?>
                <tr>
                    <td><?= clean($p['nom']) ?></td>
                    <td><span class="badge badge-red"><?= (int)$p['stock'] ?> restant(s)</span></td>
                    <td><?= formatPrix($p['prix']) ?></td>
                    <td><a href="<?= BASE_URL ?>/produits/form.php?id=<?= $p['id'] ?>" class="btn btn-outline btn-sm">Gérer</a></td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ventes mensuelles
    const ventesData = <?= json_encode($ventes) ?>;
    const ctx1 = document.getElementById('ventesChart').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: ventesData.map(d => d.mois),
            datasets: [{
                label: 'Ventes (FCFA)',
                data: ventesData.map(d => d.total),
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37,99,235,0.1)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: { display: true, text: 'Ventes mensuelles' }
            }
        }
    });

    // Répartition par catégorie
    const categoriesData = <?= json_encode($categoriesData) ?>;
    const ctx2 = document.getElementById('categoriesChart').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: categoriesData.map(d => d.nom || 'Sans catégorie'),
            datasets: [{
                data: categoriesData.map(d => d.nb),
                backgroundColor: ['#2563eb', '#d4af37', '#16a34a', '#dc2626', '#ea580c', '#8b5cf6']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
                title: { display: true, text: 'Produits par catégorie' }
            }
        }
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
