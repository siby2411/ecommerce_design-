<?php
$basePath = '/root/ecommerce_design';

echo "📁 Test de connexion\n";
echo "Chemin de base: $basePath\n";

if (file_exists($basePath . '/config/db.php')) {
    echo "✅ config/db.php existe\n";
    require_once $basePath . '/config/db.php';
} else {
    echo "❌ config/db.php n'existe pas\n";
}

if (file_exists($basePath . '/includes/functions.php')) {
    echo "✅ includes/functions.php existe\n";
    require_once $basePath . '/includes/functions.php';
} else {
    echo "❌ includes/functions.php n'existe pas\n";
}

if (function_exists('getPDO')) {
    echo "✅ getPDO() est disponible\n";
    try {
        $pdo = getPDO();
        echo "✅ Connexion à la base de données réussie\n";
    } catch (Exception $e) {
        echo "❌ Erreur: " . $e->getMessage() . "\n";
    }
} else {
    echo "❌ getPDO() n'est pas disponible\n";
}
