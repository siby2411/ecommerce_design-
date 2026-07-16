<?php
// API pour l'historique des emails
$historyFile = '/root/ecommerce_design/logs/email_sent.log';

if (file_exists($historyFile)) {
    $lines = array_reverse(file($historyFile));
    $count = 0;
    foreach ($lines as $line) {
        if ($count >= 10) break;
        echo '<div class="history-item" style="padding:8px;border-bottom:1px solid #eee;font-size:13px;">';
        echo '<span style="color:#888;">' . htmlspecialchars($line) . '</span>';
        echo '</div>';
        $count++;
    }
    if ($count == 0) {
        echo '<div class="text-muted text-center">Aucun historique</div>';
    }
} else {
    echo '<div class="text-muted text-center">Aucun historique</div>';
}
