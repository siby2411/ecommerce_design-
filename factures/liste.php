<?php
$pageTitle  = 'Factures';
$activePage = 'factures';
require_once __DIR__ . '/../includes/header.php';

$stmt = $pdo->query("
    SELECT f.*, c.nom AS client_nom, c.prenom AS client_prenom
    FROM factures f
    LEFT JOIN clients c ON c.id = f.id_client
    ORDER BY f.date_creation DESC
");
$factures = $stmt->fetchAll();
?>
<div class="page-head">
    <div>
        <h2 style="margin:0;">Factures</h2>
        <p><?= count($factures) ?> facture(s) au total</p>
    </div>
    <a href="form.php" class="btn btn-primary">+ Nouvelle facture</a>
</div>

<div class="panel">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Numéro</th>
                    <th>Client</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($factures)): ?>
                <tr><td colspan="6" class="empty-state">Aucune facture créée.</td></tr>
            <?php else: foreach ($factures as $f): ?>
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
                    <td class="actions-cell">
                        <a href="voir.php?id=<?= $f['id'] ?>" class="btn btn-outline btn-sm">Voir</a>
                        <a href="form.php?id=<?= $f['id'] ?>" class="btn btn-outline btn-sm">Modifier</a>
                        <a href="supprimer.php?id=<?= $f['id'] ?>" class="btn btn-danger btn-sm confirm-delete" data-label="la facture <?= clean($f['numero']) ?>">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
