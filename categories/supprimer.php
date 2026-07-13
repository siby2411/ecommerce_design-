<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

$id = (int)($_GET['id'] ?? 0);
if (!$id) { setFlash('danger', 'Aucune catégorie spécifiée.'); redirect(BASE_URL . '/categories/liste.php'); }

$stmt = $pdo->prepare('SELECT id FROM categories WHERE id = ?');
$stmt->execute([$id]);
if ($stmt->fetch()) {
    $pdo->prepare('DELETE FROM categories WHERE id = ?')->execute([$id]);
    logAction($pdo, 'delete', "Catégorie ID $id supprimée");
    setFlash('success', 'Catégorie supprimée.');
} else {
    setFlash('danger', 'Catégorie introuvable.');
}
redirect(BASE_URL . '/categories/liste.php');
