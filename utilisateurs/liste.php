<?php
$pageTitle  = 'Utilisateurs';
$activePage = 'utilisateurs';
require_once __DIR__ . '/../includes/header.php';

if ($_SESSION['user_role'] !== 'admin') {
    setFlash('danger', 'Accès réservé aux administrateurs.');
    redirect(BASE_URL . '/index.php');
}

$users = $pdo->query('SELECT id, nom, email, role, date_creation FROM utilisateurs ORDER BY date_creation DESC')->fetchAll();
?>
<div class="page-head">
    <div><h2 style="margin:0;">Utilisateurs</h2><p><?= count($users) ?> compte(s)</p></div>
    <a href="form.php" class="btn btn-primary">+ Nouvel utilisateur</a>
</div>
<div class="panel">
    <div class="table-wrap">
        <table>
            <thead><tr><th>Nom</th><th>Email</th><th>Rôle</th><th>Date</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= clean($u['nom']) ?></td>
                    <td><?= clean($u['email']) ?></td>
                    <td><span class="badge badge-blue"><?= clean($u['role']) ?></span></td>
                    <td><?= formatDate($u['date_creation']) ?></td>
                    <td class="actions-cell">
                        <a href="form.php?id=<?= $u['id'] ?>" class="btn btn-outline btn-sm">Modifier</a>
                        <?php if ($u['id'] != $_SESSION['user_id']): ?>
                            <a href="supprimer.php?id=<?= $u['id'] ?>" class="btn btn-danger btn-sm confirm-delete" data-label="l'utilisateur <?= clean($u['nom']) ?>">Supprimer</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
