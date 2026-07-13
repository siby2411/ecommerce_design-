<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    setFlash('danger', 'Aucune facture spécifiée.');
    redirect(BASE_URL . '/factures/liste.php');
}

$stmt = $pdo->prepare('SELECT id FROM factures WHERE id = ?');
$stmt->execute([$id]);
if ($stmt->fetch()) {
    $pdo->prepare('DELETE FROM factures WHERE id = ?')->execute([$id]);
    setFlash('success', 'Facture supprimée avec succès.');
} else {
    setFlash('danger', 'Facture introuvable.');
}

redirect(BASE_URL . '/factures/liste.php');
