<?php
$pageTitle  = isset($_GET['id']) ? 'Modifier un client' : 'Ajouter un client';
$activePage = 'clients';
require_once __DIR__ . '/../includes/header.php';

$id = (int)($_GET['id'] ?? 0);
$client = null;
if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM clients WHERE id = ?');
    $stmt->execute([$id]);
    $client = $stmt->fetch();
    if (!$client) {
        setFlash('danger', 'Client introuvable.');
        redirect(BASE_URL . '/clients/liste.php');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom      = trim($_POST['nom'] ?? '');
    $prenom   = trim($_POST['prenom'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $telephone= trim($_POST['telephone'] ?? '');
    $adresse  = trim($_POST['adresse'] ?? '');
    $ville    = trim($_POST['ville'] ?? '');

    if ($nom === '') {
        setFlash('danger', 'Le nom est obligatoire.');
    } else {
        if ($id) {
            $sql = 'UPDATE clients SET nom=?, prenom=?, email=?, telephone=?, adresse=?, ville=? WHERE id=?';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nom, $prenom, $email, $telephone, $adresse, $ville, $id]);
            setFlash('success', 'Client modifié avec succès.');
        } else {
            $sql = 'INSERT INTO clients (nom, prenom, email, telephone, adresse, ville) VALUES (?,?,?,?,?,?)';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nom, $prenom, $email, $telephone, $adresse, $ville]);
            setFlash('success', 'Client ajouté avec succès.');
        }
        redirect(BASE_URL . '/clients/liste.php');
    }
}
?>
<div class="page-head">
    <div>
        <h2 style="margin:0;"><?= $id ? 'Modifier' : 'Nouveau' ?> client</h2>
    </div>
</div>

<div class="panel">
    <form method="POST">
        <div class="form-grid">
            <div class="form-group">
                <label>Nom *</label>
                <input type="text" name="nom" required value="<?= clean($client['nom'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Prénom</label>
                <input type="text" name="prenom" value="<?= clean($client['prenom'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?= clean($client['email'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Téléphone</label>
                <input type="tel" name="telephone" value="<?= clean($client['telephone'] ?? '') ?>">
            </div>
            <div class="form-group full">
                <label>Adresse</label>
                <input type="text" name="adresse" value="<?= clean($client['adresse'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Ville</label>
                <input type="text" name="ville" value="<?= clean($client['ville'] ?? '') ?>">
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><?= $id ? 'Mettre à jour' : 'Ajouter' ?></button>
            <a href="<?= BASE_URL ?>/clients/liste.php" class="btn btn-outline">Annuler</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
