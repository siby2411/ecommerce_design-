<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

if ($_SESSION['user_role'] !== 'admin') {
    setFlash('danger', 'Accès réservé aux administrateurs.');
    redirect(BASE_URL . '/index.php');
}

$id = (int)($_GET['id'] ?? 0);
if (!$id) { setFlash('danger', 'Aucun utilisateur spécifié.'); redirect(BASE_URL . '/utilisateurs/liste.php'); }
if ($id == $_SESSION['user_id']) { setFlash('danger', 'Vous ne pouvez pas vous supprimer vous-même.'); redirect(BASE_URL . '/utilisateurs/liste.php'); }

$stmt = $pdo->prepare('SELECT id FROM utilisateurs WHERE id = ?');
$stmt->execute([$id]);
if ($stmt->fetch()) {
    $pdo->prepare('DELETE FROM utilisateurs WHERE id = ?')->execute([$id]);
    logAction($pdo, 'delete', "Utilisateur ID $id supprimé");
    setFlash('success', 'Utilisateur supprimé.');
} else {
    setFlash('danger', 'Utilisateur introuvable.');
}
redirect(BASE_URL . '/utilisateurs/liste.php');
