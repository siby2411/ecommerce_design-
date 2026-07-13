<?php
$pageTitle  = isset($_GET['id']) ? 'Modifier un produit' : 'Ajouter un produit';
$activePage = 'produits';
require_once __DIR__ . '/../includes/header.php';

$id = (int)($_GET['id'] ?? 0);
$produit = null;
if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM produits WHERE id = ?');
    $stmt->execute([$id]);
    $produit = $stmt->fetch();
    if (!$produit) {
        setFlash('danger', 'Produit introuvable.');
        redirect(BASE_URL . '/produits/liste.php');
    }
}

$fournisseurs = $pdo->query('SELECT id, nom_entreprise FROM fournisseurs ORDER BY nom_entreprise')->fetchAll();
$categories = $pdo->query('SELECT id, nom FROM categories ORDER BY nom')->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom        = trim($_POST['nom'] ?? '');
    $description= trim($_POST['description'] ?? '');
    $prix       = (float)($_POST['prix'] ?? 0);
    $stock      = (int)($_POST['stock'] ?? 0);
    $idFour     = $_POST['id_fournisseur'] ?? null;
    $idFour     = $idFour ? (int)$idFour : null;
    $idCategorie = $_POST['id_categorie'] ?? null;
    $idCategorie = $idCategorie ? (int)$idCategorie : null;

    if ($nom === '') {
        setFlash('danger', 'Le nom du produit est obligatoire.');
    } else {
        $image = $produit['image'] ?? null;
        if (!empty($_FILES['image']['name'])) {
            $upload = uploadProductImage('image');
            if ($upload === false) {
                redirect(BASE_URL . '/produits/form.php' . ($id ? '?id='.$id : ''));
            } elseif ($upload !== null) {
                if ($image) deleteProductImage($image);
                $image = $upload;
            }
        }

        if ($id) {
            $sql = 'UPDATE produits SET nom=?, description=?, prix=?, stock=?, image=?, id_fournisseur=?, id_categorie=? WHERE id=?';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nom, $description, $prix, $stock, $image, $idFour, $idCategorie, $id]);
            logAction($pdo, 'update', "Produit ID $id modifié");
            setFlash('success', 'Produit modifié avec succès.');
        } else {
            $sql = 'INSERT INTO produits (nom, description, prix, stock, image, id_fournisseur, id_categorie) VALUES (?,?,?,?,?,?,?)';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nom, $description, $prix, $stock, $image, $idFour, $idCategorie]);
            $newId = $pdo->lastInsertId();
            logAction($pdo, 'create', "Produit ID $newId créé");
            setFlash('success', 'Produit ajouté avec succès.');
        }
        redirect(BASE_URL . '/produits/liste.php');
    }
}
?>
<div class="page-head">
    <div>
        <h2 style="margin:0;"><?= $id ? 'Modifier' : 'Nouveau' ?> produit</h2>
    </div>
</div>

<div class="panel">
    <form method="POST" enctype="multipart/form-data">
        <div class="form-grid">
            <div class="form-group">
                <label>Nom *</label>
                <input type="text" name="nom" required value="<?= clean($produit['nom'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Prix (FCFA) *</label>
                <input type="number" step="0.01" name="prix" required value="<?= clean($produit['prix'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Stock *</label>
                <input type="number" name="stock" required value="<?= (int)($produit['stock'] ?? 0) ?>">
            </div>
            <div class="form-group">
                <label>Catégorie</label>
                <select name="id_categorie">
                    <option value="">-- Aucune --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= isset($produit) && $produit['id_categorie'] == $cat['id'] ? 'selected' : '' ?>>
                            <?= clean($cat['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Fournisseur</label>
                <select name="id_fournisseur">
                    <option value="">-- Aucun --</option>
                    <?php foreach ($fournisseurs as $f): ?>
                        <option value="<?= $f['id'] ?>" <?= isset($produit) && $produit['id_fournisseur'] == $f['id'] ? 'selected' : '' ?>>
                            <?= clean($f['nom_entreprise']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group full">
                <label>Description</label>
                <textarea name="description"><?= clean($produit['description'] ?? '') ?></textarea>
            </div>
            <div class="form-group full">
                <label>Image</label>
                <?php if (isset($produit['image']) && $produit['image']): ?>
                    <div class="img-preview-box">
                        <img src="<?= UPLOAD_URL . clean($produit['image']) ?>" alt="Image actuelle">
                    </div>
                <?php endif; ?>
                <div id="imagePreview" class="img-preview-box">Aucune image sélectionnée</div>
                <input type="file" name="image" id="imageInput" accept="image/*">
                <small>Formats acceptés : jpg, jpeg, png, webp, gif (max 5 Mo)</small>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><?= $id ? 'Mettre à jour' : 'Ajouter' ?></button>
            <a href="<?= BASE_URL ?>/produits/liste.php" class="btn btn-outline">Annuler</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
