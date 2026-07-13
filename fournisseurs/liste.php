<?php
$pageTitle  = 'Fournisseurs';
$activePage = 'fournisseurs';
require_once __DIR__ . '/../includes/header.php';

$recherche = trim($_GET['q'] ?? '');
if ($recherche !== '') {
    $stmt = $pdo->prepare('SELECT * FROM fournisseurs WHERE nom_entreprise LIKE ? OR contact_nom LIKE ? OR email LIKE ? ORDER BY date_creation DESC');
    $like = "%$recherche%";
    $stmt->execute([$like, $like, $like]);
} else {
    $stmt = $pdo->query('SELECT * FROM fournisseurs ORDER BY date_creation DESC');
}
$fournisseurs = $stmt->fetchAll();
?>
<div class="page-head">
    <div>
        <h2 style="margin:0;">Fournisseurs</h2>
        <p><?= count($fournisseurs) ?> fournisseur(s) actif(s)</p>
    </div>
    <a href="form.php" class="btn btn-primary">+ Nouveau fournisseur</a>
</div>

<div class="panel">
    <form method="GET" action="" style="display:flex;gap:10px;margin-bottom:18px;flex-wrap:wrap;">
        <input type="text" name="q" placeholder="Rechercher un fournisseur..." value="<?= clean($recherche) ?>" style="max-width:320px;">
        <button type="submit" class="btn btn-outline">Rechercher</button>
        <?php if ($recherche): ?><a href="liste.php" class="btn btn-outline">Réinitialiser</a><?php endif; ?>
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Entreprise</th>
                    <th>Contact</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Ville</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($fournisseurs)): ?>
                <tr><td colspan="6" class="empty-state">Aucun fournisseur trouvé.</td></tr>
            <?php else: foreach ($fournisseurs as $f): ?>
                <tr>
                    <td><strong><?= clean($f['nom_entreprise']) ?></strong></td>
                    <td><?= clean($f['contact_nom']) ?></td>
                    <td><?= clean($f['email']) ?></td>
                    <td><?= clean($f['telephone']) ?></td>
                    <td><?= clean($f['ville']) ?></td>
                    <td class="actions-cell">
                        <a href="form.php?id=<?= $f['id'] ?>" class="btn btn-outline btn-sm">Modifier</a>
                        <a href="supprimer.php?id=<?= $f['id'] ?>" class="btn btn-danger btn-sm confirm-delete" data-label="le fournisseur <?= clean($f['nom_entreprise']) ?>">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
