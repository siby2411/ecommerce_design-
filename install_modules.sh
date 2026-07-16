#!/bin/bash
echo "🚀 Installation des modules OMEGA"

# 1. Créer les dossiers nécessaires
mkdir -p /root/ecommerce_design/logs
mkdir -p /root/ecommerce_design/cron

# 2. Vérifier que le script d'alertes existe
if [ -f /root/ecommerce_design/cron/check_stock_alert.php ]; then
    echo "✅ Script d'alertes email : OK"
else
    echo "❌ Script d'alertes email manquant"
fi

# 3. Ajouter le cron pour les alertes stock
(crontab -l 2>/dev/null; echo "0 8 * * * php /root/ecommerce_design/cron/check_stock_alert.php >> /root/ecommerce_design/logs/stock_alert.log 2>&1") | crontab -
echo "✅ Cron ajouté (exécution quotidienne à 8h)"

# 4. Démarrer le serveur WebSocket en arrière-plan
if [ -f /root/ecommerce_design/ws_server.php ]; then
    nohup php /root/ecommerce_design/ws_server.php > /root/ecommerce_design/logs/ws_server.log 2>&1 &
    echo "✅ Serveur WebSocket démarré"
else
    echo "⚠️ Serveur WebSocket non trouvé"
fi

echo ""
echo "✅ Installation terminée !"
echo "🔗 Accédez aux modules :"
echo "   - Export PDF : /export_pdf.php?type=ventes"
echo "   - Prévisions : /predictions.php"
echo "   - Dashboard personnalisé : /dashboard_perso.php"
echo "   - Notifications : /notifications.php"
echo ""
echo "📧 Test d'alerte email :"
echo "   php /root/ecommerce_design/cron/check_stock_alert.php"
