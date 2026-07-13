<?php
$pageTitle  = 'Journal des activités';
$activePage = 'logs';
require_once __DIR__ . '/../includes/header.php';

if ($_SESSION['user_role'] !== 'admin') {
    setFlash('danger', 'Accès réservé aux administrateurs.');
    redirect(BASE_URL . '/index.php');
}

$logs = $pdo->query("
    SELECT l.*, u.nom AS utilisateur_nom 
    FROM logs l 
    LEFT JOIN utilisateurs u ON u.id = l.id_utilisateur 
    ORDER BY l.date_creation DESC 
    LIMIT 100
")->fetchAll();
?>
<div class="page-head">
    <div><h2 style="margin:0;">Journal d'activité</h2><p>Dernières actions</p></div>
</div>
<div class="panel">
    <div class="table-wrap">
        <table>
            <thead><tr><th>Utilisateur</th><th>Action</th><th>Détails</th><th>IP</th><th>Date</th></tr></thead>
            <tbody>
            <?php if (empty($logs)): ?>
                <tr><td colspan="5" class="empty-state">Aucune activité enregistrée.</td></tr>
            <?php else: foreach ($logs as $log): ?>
                <tr>
                    <td><?= clean($log['utilisateur_nom'] ?? 'Inconnu') ?></td>
                    <td><span class="badge badge-blue"><?= clean($log['action']) ?></span></td>
                    <td><?= clean($log['details']) ?></td>
                    <td><?= clean($log['ip']) ?></td>
                    <td><?= formatDate($log['date_creation']) . ' ' . date('H:i', strtotime($log['date_creation'])) ?></td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
