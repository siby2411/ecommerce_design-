<?php
echo "Test PHPMailer\n";
echo "=============\n";

$autoloadPaths = [
    '/root/ecommerce_design/vendor/autoload.php',
    __DIR__ . '/../vendor/autoload.php'
];

foreach ($autoloadPaths as $path) {
    if (file_exists($path)) {
        echo "✅ Chargement: $path\n";
        require_once $path;
    } else {
        echo "❌ Fichier non trouvé: $path\n";
    }
}

if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
    echo "✅ PHPMailer chargé avec succès\n";
} else {
    echo "❌ PHPMailer non chargé\n";
}
