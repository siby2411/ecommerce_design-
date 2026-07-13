<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    setFlash('danger', 'Aucun fournisseur spécifié.');
    redirect(BASE_URL . '/fournisseurs/liste.php');
}

$stmt = $pdo->prepare('SELECT id FROM fournisseurs WHERE id = ?');
$stmt->execute([$id]);
if ($stmt->fetch()) {
    $pdo->prepare('DELETE FROM fournisseurs WHERE id = ?')->execute([$id]);
    setFlash('success', 'Fournisseur supprimé avec succès.');
} else {
    setFlash('danger', 'Fournisseur introuvable.');
}

redirect(BASE_URL . '/fournisseurs/liste.php');
