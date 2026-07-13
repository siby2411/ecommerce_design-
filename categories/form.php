<?php
$pageTitle  = isset($_GET['id']) ? 'Modifier une catégorie' : 'Nouvelle catégorie';
$activePage = 'categories';
require_once __DIR__ . '/../includes/header.php';

$id = (int)($_GET['id'] ?? 0);
$categorie = null;
if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM categories WHERE id = ?');
    $stmt->execute([$id]);
    $categorie = $stmt->fetch();
    if (!$categorie) {
        setFlash('danger', 'Catégorie introuvable.');
        redirect(BASE_URL . '/categories/liste.php');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $description = trim($_POST['description'] ?? '');
    if ($nom === '') {
        setFlash('danger', 'Le nom est obligatoire.');
    } else {
        if ($id) {
            $pdo->prepare('UPDATE categories SET nom=?, description=? WHERE id=?')->execute([$nom, $description, $id]);
            logAction($pdo, 'update', "Catégorie ID $id modifiée");
            setFlash('success', 'Catégorie modifiée.');
        } else {
            $pdo->prepare('INSERT INTO categories (nom, description) VALUES (?,?)')->execute([$nom, $description]);
            $newId = $pdo->lastInsertId();
            logAction($pdo, 'create', "Catégorie ID $newId créée");
            setFlash('success', 'Catégorie ajoutée.');
        }
        redirect(BASE_URL . '/categories/liste.php');
    }
}
?>
<div class="page-head"><div><h2 style="margin:0;"><?= $id ? 'Modifier' : 'Nouvelle' ?> catégorie</h2></div></div>
<div class="panel">
    <form method="POST">
        <div class="form-group"><label>Nom *</label><input type="text" name="nom" required value="<?= clean($categorie['nom'] ?? '') ?>"></div>
        <div class="form-group"><label>Description</label><textarea name="description"><?= clean($categorie['description'] ?? '') ?></textarea></div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><?= $id ? 'Mettre à jour' : 'Ajouter' ?></button>
            <a href="liste.php" class="btn btn-outline">Annuler</a>
        </div>
    </form>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
