<?php
$pageTitle  = 'Détail du produit';
$activePage = 'produits';
require_once __DIR__ . '/../includes/header.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) { setFlash('danger', 'Produit non spécifié.'); redirect(BASE_URL . '/produits/liste.php'); }

$stmt = $pdo->prepare("SELECT p.*, f.nom_entreprise AS fournisseur, c.nom AS categorie FROM produits p LEFT JOIN fournisseurs f ON f.id = p.id_fournisseur LEFT JOIN categories c ON c.id = p.id_categorie WHERE p.id = ?");
$stmt->execute([$id]);
$produit = $stmt->fetch();
if (!$produit) { setFlash('danger', 'Produit introuvable.'); redirect(BASE_URL . '/produits/liste.php'); }

$images = getProductImages($pdo, $id);
?>
<div class="page-head">
    <div><h2 style="margin:0;"><?= clean($produit['nom']) ?></h2></div>
    <a href="liste.php" class="btn btn-outline">Retour</a>
</div>

<div class="panel" style="display:grid; grid-template-columns:1fr 1fr; gap:30px;">
    <div>
        <?php if (!empty($images)): ?>
            <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php foreach ($images as $index => $img): ?>
                        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                            <img src="<?= UPLOAD_URL . clean($img['image']) ?>" class="d-block w-100" style="max-height:400px; object-fit:contain;" alt="Image">
                        </div>
                    <?php endforeach; ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Précédent</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Suivant</span>
                </button>
            </div>
        <?php else: ?>
            <div style="background:#f0f2f5; border-radius:12px; padding:60px; text-align:center; color:#aaa;">Aucune image</div>
        <?php endif; ?>
    </div>
    <div>
        <h3><?= clean($produit['nom']) ?></h3>
        <p style="font-size:1.2rem; color:var(--blue-600); font-weight:bold;"><?= formatPrix($produit['prix']) ?></p>
        <p><strong>Stock :</strong> <?= (int)$produit['stock'] ?> unités</p>
        <p><strong>Catégorie :</strong> <?= clean($produit['categorie'] ?? 'Non classé') ?></p>
        <p><strong>Fournisseur :</strong> <?= clean($produit['fournisseur'] ?? 'Aucun') ?></p>
        <div style="margin-top:20px; border-top:1px solid var(--border); padding-top:15px;">
            <strong>Description</strong>
            <p><?= nl2br(clean($produit['description'] ?? '')) ?></p>
        </div>
        <div style="margin-top:20px;">
            <a href="form.php?id=<?= $produit['id'] ?>" class="btn btn-primary">Modifier</a>
            <a href="supprimer.php?id=<?= $produit['id'] ?>" class="btn btn-danger confirm-delete" data-label="le produit <?= clean($produit['nom']) ?>">Supprimer</a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
