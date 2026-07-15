#!/bin/bash
echo "📦 Restauration de la base de données ecommerce_design"
mysql -u root < ecommerce_design_backup.sql
echo "✅ Base de données restaurée avec succès."
