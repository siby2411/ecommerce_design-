<?php
$pageTitle  = isset($_GET['id']) ? 'Modifier un fournisseur' : 'Ajouter un fournisseur';
$activePage = 'fournisseurs';
require_once __DIR__ . '/../includes/header.php';

$id = (int)($_GET['id'] ?? 0);
$fournisseur = null;
if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM fournisseurs WHERE id = ?');
    $stmt->execute([$id]);
    $fournisseur = $stmt->fetch();
    if (!$fournisseur) {
        setFlash('danger', 'Fournisseur introuvable.');
        redirect(BASE_URL . '/fournisseurs/liste.php');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom      = trim($_POST['nom_entreprise'] ?? '');
    $contact  = trim($_POST['contact_nom'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $telephone= trim($_POST['telephone'] ?? '');
    $adresse  = trim($_POST['adresse'] ?? '');
    $ville    = trim($_POST['ville'] ?? '');

    if ($nom === '') {
        setFlash('danger', 'Le nom de l\'entreprise est obligatoire.');
    } else {
        if ($id) {
            $sql = 'UPDATE fournisseurs SET nom_entreprise=?, contact_nom=?, email=?, telephone=?, adresse=?, ville=? WHERE id=?';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nom, $contact, $email, $telephone, $adresse, $ville, $id]);
            setFlash('success', 'Fournisseur modifié avec succès.');
        } else {
            $sql = 'INSERT INTO fournisseurs (nom_entreprise, contact_nom, email, telephone, adresse, ville) VALUES (?,?,?,?,?,?)';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nom, $contact, $email, $telephone, $adresse, $ville]);
            setFlash('success', 'Fournisseur ajouté avec succès.');
        }
        redirect(BASE_URL . '/fournisseurs/liste.php');
    }
}
?>
<div class="page-head">
    <div>
        <h2 style="margin:0;"><?= $id ? 'Modifier' : 'Nouveau' ?> fournisseur</h2>
    </div>
</div>

<div class="panel">
    <form method="POST">
        <div class="form-grid">
            <div class="form-group full">
                <label>Nom de l'entreprise *</label>
                <input type="text" name="nom_entreprise" required value="<?= clean($fournisseur['nom_entreprise'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Nom du contact</label>
                <input type="text" name="contact_nom" value="<?= clean($fournisseur['contact_nom'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?= clean($fournisseur['email'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Téléphone</label>
                <input type="tel" name="telephone" value="<?= clean($fournisseur['telephone'] ?? '') ?>">
            </div>
            <div class="form-group full">
                <label>Adresse</label>
                <input type="text" name="adresse" value="<?= clean($fournisseur['adresse'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Ville</label>
                <input type="text" name="ville" value="<?= clean($fournisseur['ville'] ?? '') ?>">
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><?= $id ? 'Mettre à jour' : 'Ajouter' ?></button>
            <a href="<?= BASE_URL ?>/fournisseurs/liste.php" class="btn btn-outline">Annuler</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
