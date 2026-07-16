<?php
if (session_status() === PHP_SESSION_NONE) session_start();

function clean($data) {
    return htmlspecialchars(trim($data ?? ''), ENT_QUOTES, 'UTF-8');
}

function formatPrix($v) {
    return number_format((float)$v, 2, ',', ' ') . ' FCFA';
}

function formatDate($d) {
    if (!$d) return '-';
    return (new DateTime($d))->format('d/m/Y');
}

function redirect($url) {
    header('Location: ' . $url);
    exit;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) redirect(BASE_URL . '/login.php');
}

function setFlash($type, $msg) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $msg];
}

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $f;
    }
    return null;
}

function uploadProductImage($field) {
    if (empty($_FILES[$field]['name'])) return null;
    $f = $_FILES[$field];
    if ($f['error'] !== UPLOAD_ERR_OK) return null;
    $allowed = ['jpg','jpeg','png','webp','gif'];
    $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed)) {
        setFlash('danger', "Format d'image non autorisé.");
        return false;
    }
    if ($f['size'] > 5*1024*1024) {
        setFlash('danger', "Image > 5 Mo.");
        return false;
    }
    if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0755, true);
    $newName = 'prod_' . uniqid() . '.' . $ext;
    if (move_uploaded_file($f['tmp_name'], UPLOAD_DIR . $newName)) return $newName;
    return false;
}

function deleteProductImage($name) {
    if ($name && file_exists(UPLOAD_DIR . $name)) @unlink(UPLOAD_DIR . $name);
}

function generateFactureNumero($pdo) {
    $year = date('Y');
    $stmt = $pdo->query("SELECT COUNT(*) AS nb FROM factures WHERE numero LIKE 'FAC-$year-%'");
    $nb = (int)$stmt->fetch()['nb'] + 1;
    return sprintf('FAC-%s-%06d', $year, $nb);
}

// ====== LOGS ======
function logAction($pdo, $action, $details = null) {
    $userId = $_SESSION['user_id'] ?? 0;
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $stmt = $pdo->prepare('INSERT INTO logs (id_utilisateur, action, details, ip) VALUES (?, ?, ?, ?)');
    $stmt->execute([$userId, $action, $details, $ip]);
}

// ====== TVA ======
function calculerTVA($montantHT, $taux) {
    return round($montantHT * $taux / 100, 2);
}

// ====== EXPORT CSV ======
function exportCSV($data, $filename, $headers = []) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    $output = fopen('php://output', 'w');
    if (!empty($headers)) fputcsv($output, $headers);
    foreach ($data as $row) fputcsv($output, $row);
    fclose($output);
    exit;
}

// ====== GALERIE D'IMAGES ======

/**
 * Upload de multiples images pour la galerie
 */
function uploadGalleryImages($fileField, $produitId) {
    if (empty($_FILES[$fileField]['name'][0])) return [];
    $allowed = ['jpg','jpeg','png','webp','gif'];
    $uploaded = [];
    $errors = [];
    foreach ($_FILES[$fileField]['tmp_name'] as $key => $tmpName) {
        if ($_FILES[$fileField]['error'][$key] !== UPLOAD_ERR_OK) continue;
        $ext = strtolower(pathinfo($_FILES[$fileField]['name'][$key], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $errors[] = "Format non autorisé : " . $_FILES[$fileField]['name'][$key];
            continue;
        }
        if ($_FILES[$fileField]['size'][$key] > 5*1024*1024) {
            $errors[] = "Fichier trop volumineux : " . $_FILES[$fileField]['name'][$key];
            continue;
        }
        $newName = 'gal_' . $produitId . '_' . uniqid() . '.' . $ext;
        $destination = UPLOAD_DIR . $newName;
        if (move_uploaded_file($tmpName, $destination)) {
            $uploaded[] = $newName;
        }
    }
    if (!empty($errors)) {
        setFlash('warning', implode('<br>', $errors));
    }
    return $uploaded;
}

/**
 * Supprimer une image de la galerie (fichier et entrée BDD)
 */
function deleteGalleryImage($pdo, $imageId) {
    $stmt = $pdo->prepare('SELECT image FROM produits_images WHERE id = ?');
    $stmt->execute([$imageId]);
    $img = $stmt->fetch();
    if ($img) {
        deleteProductImage($img['image']);
        $pdo->prepare('DELETE FROM produits_images WHERE id = ?')->execute([$imageId]);
        return true;
    }
    return false;
}

/**
 * Récupérer les images d'un produit
 */
function getProductImages($pdo, $produitId) {
    $stmt = $pdo->prepare('SELECT * FROM produits_images WHERE id_produit = ? ORDER BY ordre ASC, date_creation ASC');
    $stmt->execute([$produitId]);
    return $stmt->fetchAll();
}


/**
 * Récupérer la première image d'un produit (pour affichage)
 */
function getProductFirstImage($pdo, $produitId) {
    $stmt = $pdo->prepare('SELECT image FROM produits_images WHERE id_produit = ? ORDER BY ordre ASC, date_creation ASC LIMIT 1');
    $stmt->execute([$produitId]);
    $img = $stmt->fetch();
    if ($img) {
        return UPLOAD_URL . $img['image'];
    }
    return null;
}
