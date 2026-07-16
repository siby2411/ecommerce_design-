<?php
/**
 * Serveur WebSocket simple pour les notifications en temps réel
 * Exécuter avec: php ws_server.php
 * Port: 8081
 */

// Vérifier si le WebSocket est supporté
if (!extension_loaded('sockets')) {
    die("❌ Extension 'sockets' non chargée. Installez-la avec: apt-get install php-sockets\n");
}

// Créer un socket serveur
$host = '0.0.0.0';
$port = 8081;

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if (!$socket) {
    die("❌ Impossible de créer le socket: " . socket_strerror(socket_last_error()) . "\n");
}

// Réutiliser l'adresse
socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);

// Lier le socket
if (!socket_bind($socket, $host, $port)) {
    die("❌ Impossible de lier le socket sur $host:$port: " . socket_strerror(socket_last_error()) . "\n");
}

// Écouter les connexions
if (!socket_listen($socket, 5)) {
    die("❌ Impossible d'écouter: " . socket_strerror(socket_last_error()) . "\n");
}

echo "🔌 Serveur WebSocket démarré sur ws://{$host}:{$port}\n";
echo "📡 En attente de connexions...\n\n";

$clients = [];

while (true) {
    // Accepter une nouvelle connexion
    $newSocket = socket_accept($socket);
    if (!$newSocket) {
        continue;
    }

    // Lire les données
    $data = socket_read($newSocket, 1024);
    if ($data === false || trim($data) === '') {
        socket_close($newSocket);
        continue;
    }

    // Récupérer l'adresse du client
    socket_getpeername($newSocket, $clientIp, $clientPort);
    echo "✅ Nouvelle connexion de {$clientIp}:{$clientPort}\n";

    // Stocker le client
    $clients[] = $newSocket;

    // Envoyer un message de bienvenue
    $welcome = json_encode([
        'type' => 'welcome',
        'message' => 'Connecté aux notifications OMEGA',
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    socket_write($newSocket, $welcome . "\n");

    // Lire les messages du client
    while (true) {
        $message = socket_read($newSocket, 1024);
        if ($message === false || trim($message) === '') {
            break;
        }

        echo "📩 Message reçu: " . trim($message) . "\n";

        // Diffuser à tous les clients
        $broadcast = json_encode([
            'type' => 'broadcast',
            'sender' => 'Utilisateur',
            'message' => trim($message),
            'timestamp' => date('Y-m-d H:i:s')
        ]);

        foreach ($clients as $client) {
            if ($client !== $newSocket) {
                socket_write($client, $broadcast . "\n");
            }
        }
    }

    // Supprimer le client déconnecté
    $clients = array_filter($clients, function($c) use ($newSocket) {
        return $c !== $newSocket;
    });
    socket_close($newSocket);
    echo "❌ Client déconnecté\n";
}

socket_close($socket);
