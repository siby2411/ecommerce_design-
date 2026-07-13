<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    setFlash('danger', 'Aucun client spécifié.');
    redirect(BASE_URL . '/clients/liste.php');
}

$stmt = $pdo->prepare('SELECT id FROM clients WHERE id = ?');
$stmt->execute([$id]);
if ($stmt->fetch()) {
    $pdo->prepare('DELETE FROM clients WHERE id = ?')->execute([$id]);
    setFlash('success', 'Client supprimé avec succès.');
} else {
    setFlash('danger', 'Client introuvable.');
}

redirect(BASE_URL . '/clients/liste.php');
