<?php
$pageTitle  = 'Catégories';
$activePage = 'categories';
require_once __DIR__ . '/../includes/header.php';

$categories = $pdo->query('SELECT * FROM categories ORDER BY nom')->fetchAll();
?>
<div class="page-head">
    <div>
        <h2 style="margin:0;">Catégories</h2>
        <p><?= count($categories) ?> catégorie(s)</p>
    </div>
    <a href="form.php" class="btn btn-primary">+ Nouvelle catégorie</a>
</div>

<div class="panel">
    <div class="table-wrap">
        <table>
            <thead><tr><th>Nom</th><th>Description</th><th>Actions</th></tr></thead>
            <tbody>
            <?php if (empty($categories)): ?>
                <tr><td colspan="3" class="empty-state">Aucune catégorie.</td></tr>
            <?php else: foreach ($categories as $c): ?>
                <tr>
                    <td><strong><?= clean($c['nom']) ?></strong></td>
                    <td><?= clean($c['description']) ?></td>
                    <td class="actions-cell">
                        <a href="form.php?id=<?= $c['id'] ?>" class="btn btn-outline btn-sm">Modifier</a>
                        <a href="supprimer.php?id=<?= $c['id'] ?>" class="btn btn-danger btn-sm confirm-delete" data-label="la catégorie <?= clean($c['nom']) ?>">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
