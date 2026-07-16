<?php
require_once __DIR__ . '/config/db.php';

$uploadDir = __DIR__ . '/uploads/produits/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

// Récupérer tous les produits
$stmt = $pdo->query("SELECT id, nom FROM produits ORDER BY id");
$produits = $stmt->fetchAll();

$totalImages = 0;
$totalProduits = 0;

echo "\n🖼️ IMPORTATION DES IMAGES\n";
echo "=========================\n\n";

foreach ($produits as $p) {
    $id = $p['id'];
    $extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif'];
    $imagesTrouvees = [];
    
    // Recherche des images par ID
    foreach ($extensions as $ext) {
        $file = $uploadDir . $id . '.' . $ext;
        if (file_exists($file)) {
            $imagesTrouvees[] = $id . '.' . $ext;
        }
        // Recherche des images multiples
        $idx = 1;
        while (true) {
            $fileMulti = $uploadDir . $id . '-' . $idx . '.' . $ext;
            if (file_exists($fileMulti)) {
                $imagesTrouvees[] = $id . '-' . $idx . '.' . $ext;
                $idx++;
            } else {
                break;
            }
        }
    }
    
    if (!empty($imagesTrouvees)) {
        // Supprimer les anciennes images
        $pdo->prepare("DELETE FROM produits_images WHERE id_produit = ?")->execute([$id]);
        
        // Insérer les nouvelles images
        $ordre = 0;
        foreach ($imagesTrouvees as $img) {
            $stmt2 = $pdo->prepare("INSERT INTO produits_images (id_produit, image, ordre) VALUES (?, ?, ?)");
            $stmt2->execute([$id, $img, $ordre]);
            $ordre++;
            $totalImages++;
        }
        echo "✅ Produit #$id : " . count($imagesTrouvees) . " image(s)\n";
        $totalProduits++;
    }
}

echo "\n📊 RÉSULTAT :\n";
echo "   - $totalProduits produits avec images\n";
echo "   - $totalImages images importées\n";
