<?php
require_once __DIR__ . '/config/db.php';

$uploadDir = __DIR__ . '/uploads/produits/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

// Couleurs pour les placeholders
$colors = ['#2563eb', '#d4af37', '#16a34a', '#dc2626', '#ea580c', '#8b5cf6', '#06b6d4', '#ec4899', '#14b8a6', '#f59e0b'];

// Récupérer les produits
$stmt = $pdo->query("SELECT id, nom FROM produits");
$produits = $stmt->fetchAll();

echo "\n🎨 GÉNÉRATION DE PLACEHOLDERS\n";
echo "=============================\n\n";

$total = 0;
foreach ($produits as $p) {
    $id = $p['id'];
    $imgPath = $uploadDir . $id . '.png';
    
    // Vérifier si une image existe déjà
    $hasImage = $pdo->prepare("SELECT COUNT(*) FROM produits_images WHERE id_produit = ?");
    $hasImage->execute([$id]);
    if ($hasImage->fetchColumn() > 0) {
        echo "⏭️ Produit #$id : déjà des images\n";
        continue;
    }
    
    // Créer une image placeholder
    $size = 300;
    $img = imagecreatetruecolor($size, $size);
    $color = $colors[$id % count($colors)];
    list($r, $g, $b) = sscanf($color, '#%02x%02x%02x');
    $bg = imagecolorallocate($img, $r, $g, $b);
    imagefill($img, 0, 0, $bg);
    
    // Ajouter un texte
    $white = imagecolorallocate($img, 255, 255, 255);
    $text = substr($p['nom'], 0, 30);
    
    // Centrer le texte
    $fontSize = 4;
    $textWidth = imagefontwidth($fontSize) * strlen($text);
    $textHeight = imagefontheight($fontSize);
    $x = ($size - $textWidth) / 2;
    $y = ($size - $textHeight) / 2;
    imagestring($img, $fontSize, $x, $y, $text, $white);
    
    // Ajouter l'ID en bas
    $idText = "ID: " . $id;
    $idWidth = imagefontwidth($fontSize) * strlen($idText);
    imagestring($img, $fontSize, ($size - $idWidth) / 2, $size - 30, $idText, $white);
    
    imagepng($img, $imgPath);
    imagedestroy($img);
    
    // Insérer dans la base
    $stmt2 = $pdo->prepare("INSERT INTO produits_images (id_produit, image, ordre) VALUES (?, ?, 0)");
    $stmt2->execute([$id, $id . '.png']);
    
    echo "✅ Produit #$id : placeholder généré\n";
    $total++;
}

echo "\n📊 $total placeholders générés.\n";
