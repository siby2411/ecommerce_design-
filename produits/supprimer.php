<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    setFlash('danger', 'Aucun produit spécifié.');
    redirect(BASE_URL . '/produits/liste.php');
}

$stmt = $pdo->prepare('SELECT image FROM produits WHERE id = ?');
$stmt->execute([$id]);
$produit = $stmt->fetch();

if ($produit) {
    // Supprimer l'image
    if ($produit['image']) deleteProductImage($produit['image']);
    $pdo->prepare('DELETE FROM produits WHERE id = ?')->execute([$id]);
    setFlash('success', 'Produit supprimé avec succès.');
} else {
    setFlash('danger', 'Produit introuvable.');
}

redirect(BASE_URL . '/produits/liste.php');
