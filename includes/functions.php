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

// ====== NOUVELLES FONCTIONS ======
function logAction($pdo, $action, $details = null) {
    $userId = $_SESSION['user_id'] ?? 0;
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $stmt = $pdo->prepare('INSERT INTO logs (id_utilisateur, action, details, ip) VALUES (?, ?, ?, ?)');
    $stmt->execute([$userId, $action, $details, $ip]);
}

function calculerTVA($montantHT, $taux) {
    return round($montantHT * $taux / 100, 2);
}

function exportCSV($data, $filename, $headers = []) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    $output = fopen('php://output', 'w');
    if (!empty($headers)) fputcsv($output, $headers);
    foreach ($data as $row) fputcsv($output, $row);
    fclose($output);
    exit;
}
