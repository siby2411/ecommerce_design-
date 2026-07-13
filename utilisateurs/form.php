<?php
$pageTitle  = isset($_GET['id']) ? 'Modifier un utilisateur' : 'Nouvel utilisateur';
$activePage = 'utilisateurs';
require_once __DIR__ . '/../includes/header.php';

if ($_SESSION['user_role'] !== 'admin') {
    setFlash('danger', 'Accès réservé aux administrateurs.');
    redirect(BASE_URL . '/index.php');
}

$id = (int)($_GET['id'] ?? 0);
$user = null;
if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM utilisateurs WHERE id = ?');
    $stmt->execute([$id]);
    $user = $stmt->fetch();
    if (!$user) {
        setFlash('danger', 'Utilisateur introuvable.');
        redirect(BASE_URL . '/utilisateurs/liste.php');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = $_POST['role'] ?? 'gestionnaire';
    $motdepasse = $_POST['mot_de_passe'] ?? '';

    if ($nom === '' || $email === '') {
        setFlash('danger', 'Nom et email sont obligatoires.');
    } else {
        if ($id) {
            // Modification
            if ($motdepasse !== '') {
                $hash = password_hash($motdepasse, PASSWORD_DEFAULT);
                $pdo->prepare('UPDATE utilisateurs SET nom=?, email=?, mot_de_passe=?, role=? WHERE id=?')->execute([$nom, $email, $hash, $role, $id]);
            } else {
                $pdo->prepare('UPDATE utilisateurs SET nom=?, email=?, role=? WHERE id=?')->execute([$nom, $email, $role, $id]);
            }
            logAction($pdo, 'update', "Utilisateur ID $id modifié");
            setFlash('success', 'Utilisateur modifié.');
        } else {
            // Création
            if ($motdepasse === '') {
                setFlash('danger', 'Le mot de passe est requis pour un nouvel utilisateur.');
            } else {
                $hash = password_hash($motdepasse, PASSWORD_DEFAULT);
                $pdo->prepare('INSERT INTO utilisateurs (nom, email, mot_de_passe, role) VALUES (?,?,?,?)')->execute([$nom, $email, $hash, $role]);
                $newId = $pdo->lastInsertId();
                logAction($pdo, 'create', "Utilisateur ID $newId créé");
                setFlash('success', 'Utilisateur ajouté.');
            }
        }
        redirect(BASE_URL . '/utilisateurs/liste.php');
    }
}
?>
<div class="page-head"><div><h2 style="margin:0;"><?= $id ? 'Modifier' : 'Nouvel' ?> utilisateur</h2></div></div>
<div class="panel">
    <form method="POST">
        <div class="form-group"><label>Nom *</label><input type="text" name="nom" required value="<?= clean($user['nom'] ?? '') ?>"></div>
        <div class="form-group"><label>Email *</label><input type="email" name="email" required value="<?= clean($user['email'] ?? '') ?>"></div>
        <div class="form-group"><label>Mot de passe <?= $id ? '(laisser vide pour conserver)' : '*' ?></label><input type="password" name="mot_de_passe" <?= $id ? '' : 'required' ?>></div>
        <div class="form-group">
            <label>Rôle</label>
            <select name="role">
                <option value="gestionnaire" <?= isset($user) && $user['role'] == 'gestionnaire' ? 'selected' : '' ?>>Gestionnaire</option>
                <option value="admin" <?= isset($user) && $user['role'] == 'admin' ? 'selected' : '' ?>>Administrateur</option>
            </select>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><?= $id ? 'Mettre à jour' : 'Ajouter' ?></button>
            <a href="liste.php" class="btn btn-outline">Annuler</a>
        </div>
    </form>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
