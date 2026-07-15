#!/bin/bash
echo "Installation de la galerie d'images..."

# Ajout de la table produits_images
mysql -u root -e "USE ecommerce_design; CREATE TABLE IF NOT EXISTS produits_images ( id INT AUTO_INCREMENT PRIMARY KEY, id_produit INT NOT NULL, image VARCHAR(255) NOT NULL, ordre INT DEFAULT 0, date_creation DATETIME DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY (id_produit) REFERENCES produits(id) ON DELETE CASCADE ) ENGINE=InnoDB;"

# Ajout des fonctions à includes/functions.php (à la fin)
cat >> includes/functions.php << 'EOFF'
/**
 * Upload de multiples images pour la galerie
 */
function uploadGalleryImages($fileField, $produitId) {
    if (empty($_FILES[$fileField]['name'][0])) return [];
    $allowed = ['jpg','jpeg','png','webp','gif'];
    $uploaded = [];
    $errors = [];
    foreach ($_FILES[$fileField]['tmp_name'] as $key => $tmpName) {
        if ($_FILES[$fileField]['error'][$key] !== UPLOAD_ERR_OK) continue;
        $ext = strtolower(pathinfo($_FILES[$fileField]['name'][$key], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) { $errors[] = "Format non autorisé : " . $_FILES[$fileField]['name'][$key]; continue; }
        if ($_FILES[$fileField]['size'][$key] > 5*1024*1024) { $errors[] = "Fichier trop volumineux : " . $_FILES[$fileField]['name'][$key]; continue; }
        $newName = 'gal_' . $produitId . '_' . uniqid() . '.' . $ext;
        $destination = UPLOAD_DIR . $newName;
        if (move_uploaded_file($tmpName, $destination)) $uploaded[] = $newName;
    }
    if (!empty($errors)) setFlash('warning', implode('<br>', $errors));
    return $uploaded;
}
function deleteGalleryImage($pdo, $imageId) {
    $stmt = $pdo->prepare('SELECT image FROM produits_images WHERE id = ?');
    $stmt->execute([$imageId]);
    $img = $stmt->fetch();
    if ($img) { deleteProductImage($img['image']); $pdo->prepare('DELETE FROM produits_images WHERE id = ?')->execute([$imageId]); return true; }
    return false;
}
function getProductImages($pdo, $produitId) {
    $stmt = $pdo->prepare('SELECT * FROM produits_images WHERE id_produit = ? ORDER BY ordre ASC, date_creation ASC');
    $stmt->execute([$produitId]);
    return $stmt->fetchAll();
}
EOFF

# Remplacer produits/form.php par la version avec galerie
cat > produits/form.php << 'EOFF'
<?php
$pageTitle  = isset($_GET['id']) ? 'Modifier un produit' : 'Ajouter un produit';
$activePage = 'produits';
require_once __DIR__ . '/../includes/header.php';

$id = (int)($_GET['id'] ?? 0);
$produit = null;
$images = [];
if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM produits WHERE id = ?');
    $stmt->execute([$id]);
    $produit = $stmt->fetch();
    if (!$produit) {
        setFlash('danger', 'Produit introuvable.');
        redirect(BASE_URL . '/produits/liste.php');
    }
    $images = getProductImages($pdo, $id);
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
            setFlash('success', 'Produit modifié.');
        } else {
            $sql = 'INSERT INTO produits (nom, description, prix, stock, image, id_fournisseur, id_categorie) VALUES (?,?,?,?,?,?,?)';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nom, $description, $prix, $stock, $image, $idFour, $idCategorie]);
            $id = $pdo->lastInsertId();
            logAction($pdo, 'create', "Produit ID $id créé");
            setFlash('success', 'Produit ajouté.');
        }

        // Upload galerie
        if (!empty($_FILES['galerie']['name'][0])) {
            $uploaded = uploadGalleryImages('galerie', $id);
            foreach ($uploaded as $img) {
                $pdo->prepare('INSERT INTO produits_images (id_produit, image) VALUES (?, ?)')->execute([$id, $img]);
            }
            if (!empty($uploaded)) setFlash('success', 'Images de galerie ajoutées.');
        }

        // Suppression d'images cochées
        if (isset($_POST['delete_images']) && is_array($_POST['delete_images'])) {
            foreach ($_POST['delete_images'] as $imgId) {
                deleteGalleryImage($pdo, (int)$imgId);
            }
            setFlash('success', 'Images supprimées.');
        }

        redirect(BASE_URL . '/produits/liste.php');
    }
}
?>
<div class="page-head">
    <div><h2 style="margin:0;"><?= $id ? 'Modifier' : 'Nouveau' ?> produit</h2></div>
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
                        <option value="<?= $cat['id'] ?>" <?= isset($produit) && $produit['id_categorie'] == $cat['id'] ? 'selected' : '' ?>><?= clean($cat['nom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Fournisseur</label>
                <select name="id_fournisseur">
                    <option value="">-- Aucun --</option>
                    <?php foreach ($fournisseurs as $f): ?>
                        <option value="<?= $f['id'] ?>" <?= isset($produit) && $produit['id_fournisseur'] == $f['id'] ? 'selected' : '' ?>><?= clean($f['nom_entreprise']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group full">
                <label>Description</label>
                <textarea name="description"><?= clean($produit['description'] ?? '') ?></textarea>
            </div>
            <div class="form-group full">
                <label>Image principale</label>
                <?php if (isset($produit['image']) && $produit['image']): ?>
                    <div class="img-preview-box"><img src="<?= UPLOAD_URL . clean($produit['image']) ?>" alt="Image actuelle"></div>
                <?php endif; ?>
                <div id="imagePreview" class="img-preview-box">Aucune image</div>
                <input type="file" name="image" id="imageInput" accept="image/*">
                <small>jpg, jpeg, png, webp, gif (max 5 Mo)</small>
            </div>
        </div>

        <!-- Galerie -->
        <div style="margin-top:30px; border-top:1px solid var(--border); padding-top:20px;">
            <h3>Galerie d'images</h3>
            <?php if (!empty($images)): ?>
                <div style="display:flex; flex-wrap:wrap; gap:12px; margin-bottom:15px;">
                    <?php foreach ($images as $img): ?>
                        <div style="position:relative; width:100px; height:100px; border:1px solid var(--border); border-radius:8px; overflow:hidden;">
                            <img src="<?= UPLOAD_URL . clean($img['image']) ?>" style="width:100%; height:100%; object-fit:cover;">
                            <label style="position:absolute; top:4px; right:4px; background:rgba(255,0,0,0.8); color:#fff; border-radius:50%; width:22px; height:22px; display:flex; align-items:center; justify-content:center; font-size:12px; cursor:pointer;">
                                <input type="checkbox" name="delete_images[]" value="<?= $img['id'] ?>" style="display:none;"> ✕
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
                <p><small>Cochez une image puis soumettez pour la supprimer.</small></p>
            <?php else: ?>
                <p>Aucune image dans la galerie.</p>
            <?php endif; ?>
            <div class="form-group">
                <label>Ajouter des images (plusieurs fichiers)</label>
                <input type="file" name="galerie[]" multiple accept="image/*">
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><?= $id ? 'Mettre à jour' : 'Ajouter' ?></button>
            <a href="liste.php" class="btn btn-outline">Annuler</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
EOFF

# Créer produits/detail.php
cat > produits/detail.php << 'EOFF'
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
EOFF

# Mettre à jour produits/liste.php pour ajouter le bouton "Voir"
sed -i '/<td class="actions-cell">/,/<\/td>/ s|</a>|</a> <a href="detail.php?id=<?= $p[\'id\'] ?>" class="btn btn-outline btn-sm">Voir</a>|' produits/liste.php

echo "✅ Installation terminée. Redémarrez le serveur."
