<?php
$pageTitle  = 'Produits';
$activePage = 'produits';
require_once __DIR__ . '/../includes/header.php';

$recherche = trim($_GET['q'] ?? '');
if ($recherche !== '') {
    $stmt = $pdo->prepare("
        SELECT p.*, f.nom_entreprise AS fournisseur_nom, c.nom AS categorie_nom
        FROM produits p
        LEFT JOIN fournisseurs f ON f.id = p.id_fournisseur
        LEFT JOIN categories c ON c.id = p.id_categorie
        WHERE p.nom LIKE ? OR p.description LIKE ?
        ORDER BY p.date_creation DESC
    ");
    $like = "%$recherche%";
    $stmt->execute([$like, $like]);
} else {
    $stmt = $pdo->query("
        SELECT p.*, f.nom_entreprise AS fournisseur_nom, c.nom AS categorie_nom
        FROM produits p
        LEFT JOIN fournisseurs f ON f.id = p.id_fournisseur
        LEFT JOIN categories c ON c.id = p.id_categorie
        ORDER BY p.date_creation DESC
    ");
}
$produits = $stmt->fetchAll();
?>
<div class="page-head">
    <div>
        <h2 style="margin:0;">Catalogue produits</h2>
        <p><?= count($produits) ?> produit(s) au total</p>
    </div>
    <div style="display:flex;gap:8px;">
        <a href="form.php" class="btn btn-primary">+ Nouveau produit</a>
        <a href="export.php" class="btn btn-outline">Exporter CSV</a>
    </div>
</div>

<div class="panel">
    <form method="GET" action="" style="display:flex;gap:10px;margin-bottom:18px;flex-wrap:wrap;">
        <input type="text" name="q" placeholder="Rechercher un produit..." value="<?= clean($recherche) ?>" style="max-width:320px;">
        <button type="submit" class="btn btn-outline">Rechercher</button>
        <?php if ($recherche): ?><a href="liste.php" class="btn btn-outline">Réinitialiser</a><?php endif; ?>
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Nom</th>
                    <th>Catégorie</th>
                    <th>Fournisseur</th>
                    <th>Prix</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($produits)): ?>
                <tr><td colspan="7" class="empty-state">Aucun produit trouvé.</td></tr>
            <?php else: foreach ($produits as $p): ?>
                <tr>
                    <td>
                        <?php if ($p['image']): ?>
                            <img class="table-img" src="<?= UPLOAD_URL . clean($p['image']) ?>" alt="<?= clean($p['nom']) ?>">
                        <?php else: ?>
                            <div class="table-img" style="display:flex;align-items:center;justify-content:center;">📦</div>
                        <?php endif; ?>
                    </td>
                    <td><strong><?= clean($p['nom']) ?></strong></td>
                    <td><?= clean($p['categorie_nom'] ?? '-') ?></td>
                    <td><?= clean($p['fournisseur_nom'] ?? '-') ?></td>
                    <td><?= formatPrix($p['prix']) ?></td>
                    <td>
                        <?php if ($p['stock'] <= 5): ?>
                            <span class="badge badge-red"><?= (int)$p['stock'] ?></span>
                        <?php else: ?>
                            <span class="badge badge-green"><?= (int)$p['stock'] ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="actions-cell">
                        <a href="detail.php?id=<?= $p['id'] ?>" class="btn btn-outline btn-sm">Voir</a>
                        <a href="form.php?id=<?= $p['id'] ?>" class="btn btn-outline btn-sm">Modifier</a>
                        <a href="supprimer.php?id=<?= $p['id'] ?>" class="btn btn-danger btn-sm confirm-delete" data-label="le produit <?= clean($p['nom']) ?>">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
