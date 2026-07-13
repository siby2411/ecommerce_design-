<?php
$pageTitle  = 'Clients';
$activePage = 'clients';
require_once __DIR__ . '/../includes/header.php';

$recherche = trim($_GET['q'] ?? '');
if ($recherche !== '') {
    $stmt = $pdo->prepare('SELECT * FROM clients WHERE nom LIKE ? OR prenom LIKE ? OR email LIKE ? ORDER BY date_creation DESC');
    $like = "%$recherche%";
    $stmt->execute([$like, $like, $like]);
} else {
    $stmt = $pdo->query('SELECT * FROM clients ORDER BY date_creation DESC');
}
$clients = $stmt->fetchAll();
?>
<div class="page-head">
    <div>
        <h2 style="margin:0;">Clients</h2>
        <p><?= count($clients) ?> client(s) enregistré(s)</p>
    </div>
    <a href="form.php" class="btn btn-primary">+ Nouveau client</a>
</div>

<div class="panel">
    <form method="GET" action="" style="display:flex;gap:10px;margin-bottom:18px;flex-wrap:wrap;">
        <input type="text" name="q" placeholder="Rechercher un client..." value="<?= clean($recherche) ?>" style="max-width:320px;">
        <button type="submit" class="btn btn-outline">Rechercher</button>
        <?php if ($recherche): ?><a href="liste.php" class="btn btn-outline">Réinitialiser</a><?php endif; ?>
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Ville</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($clients)): ?>
                <tr><td colspan="6" class="empty-state">Aucun client trouvé.</td></tr>
            <?php else: foreach ($clients as $c): ?>
                <tr>
                    <td><strong><?= clean($c['nom']) ?></strong></td>
                    <td><?= clean($c['prenom']) ?></td>
                    <td><?= clean($c['email']) ?></td>
                    <td><?= clean($c['telephone']) ?></td>
                    <td><?= clean($c['ville']) ?></td>
                    <td class="actions-cell">
                        <a href="form.php?id=<?= $c['id'] ?>" class="btn btn-outline btn-sm">Modifier</a>
                        <a href="supprimer.php?id=<?= $c['id'] ?>" class="btn btn-danger btn-sm confirm-delete" data-label="le client <?= clean($c['nom']) ?>">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
