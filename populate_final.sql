-- ============================================================
-- PEUPLEMENT DES DONNÉES DE TEST (AVEC DESIGNATION)
-- ============================================================

-- 1. Supprimer les anciennes lignes
DELETE FROM facture_lignes;
ALTER TABLE facture_lignes AUTO_INCREMENT = 1;

-- 2. Insérer des clients (si besoin)
INSERT IGNORE INTO clients (nom, prenom, email, telephone, adresse) VALUES
('Diop', 'Mamadou', 'mamadou.diop@email.com', '771234567', 'Dakar, Senegal'),
('Fall', 'Aminata', 'aminata.fall@email.com', '773456789', 'Pikine, Senegal'),
('Ndiaye', 'Oumar', 'oumar.ndiaye@email.com', '774567890', 'Guédiawaye, Senegal'),
('Sow', 'Fatou', 'fatou.sow@email.com', '775678901', 'Rufisque, Senegal'),
('Ba', 'Moussa', 'moussa.ba@email.com', '776789012', 'Thiès, Senegal'),
('Kane', 'Aissatou', 'aissatou.kane@email.com', '777890123', 'Dakar, Senegal');

-- 3. Insérer des catégories (si besoin)
INSERT IGNORE INTO categories (nom) VALUES
('Ordinateurs & Portables'),
('Smartphones & Téléphonie'),
('Réseaux & Télécommunications'),
('Accessoires Informatiques'),
('Stockage & Mémoire'),
('Périphériques & Équipements');

-- 4. Insérer des produits
INSERT IGNORE INTO produits (nom, prix, stock, id_categorie, description) VALUES
('HP EliteBook 840 G6', 850000, 15, (SELECT id FROM categories WHERE nom='Ordinateurs & Portables'), 'Ordinateur portable professionnel'),
('Dell Latitude 5420', 950000, 8, (SELECT id FROM categories WHERE nom='Ordinateurs & Portables'), 'Ordinateur portable robuste'),
('Lenovo ThinkPad E14', 750000, 12, (SELECT id FROM categories WHERE nom='Ordinateurs & Portables'), 'Ordinateur portable polyvalent'),
('MacBook Air M2', 1200000, 5, (SELECT id FROM categories WHERE nom='Ordinateurs & Portables'), 'Ordinateur ultra-léger Apple'),
('Acer Aspire 5', 650000, 10, (SELECT id FROM categories WHERE nom='Ordinateurs & Portables'), 'Ordinateur portable grand public'),
('iPhone 15 Pro Max', 1500000, 10, (SELECT id FROM categories WHERE nom='Smartphones & Téléphonie'), 'Smartphone haut de gamme Apple'),
('Samsung Galaxy S24 Ultra', 1400000, 8, (SELECT id FROM categories WHERE nom='Smartphones & Téléphonie'), 'Smartphone Android premium'),
('Xiaomi Redmi Note 13 Pro', 350000, 25, (SELECT id FROM categories WHERE nom='Smartphones & Téléphonie'), 'Smartphone milieu de gamme'),
('Tecno Camon 20 Pro', 350000, 20, (SELECT id FROM categories WHERE nom='Smartphones & Téléphonie'), 'Smartphone photo'),
('Infinix Zero 30', 300000, 15, (SELECT id FROM categories WHERE nom='Smartphones & Téléphonie'), 'Smartphone gaming'),
('Routeur TP-Link Archer AX73', 120000, 15, (SELECT id FROM categories WHERE nom='Réseaux & Télécommunications'), 'Routeur WiFi 6'),
('Switch Gigabit 24 ports', 250000, 8, (SELECT id FROM categories WHERE nom='Réseaux & Télécommunications'), 'Switch réseau professionnel'),
('Routeur WiFi 6 Xiaomi AX3000', 80000, 20, (SELECT id FROM categories WHERE nom='Réseaux & Télécommunications'), 'Routeur WiFi 6 entrée de gamme'),
('Modem 4G LTE Huawei', 65000, 12, (SELECT id FROM categories WHERE nom='Réseaux & Télécommunications'), 'Modem 4G portable'),
('Souris Logitech M185', 15000, 30, (SELECT id FROM categories WHERE nom='Accessoires Informatiques'), 'Souris sans fil'),
('Clavier mécanique RGB', 40000, 12, (SELECT id FROM categories WHERE nom='Accessoires Informatiques'), 'Clavier gaming mécanique'),
('Webcam HD 1080p', 25000, 10, (SELECT id FROM categories WHERE nom='Accessoires Informatiques'), 'Webcam pour visioconférence'),
('Casque Gaming HyperX', 55000, 8, (SELECT id FROM categories WHERE nom='Accessoires Informatiques'), 'Casque gaming'),
('SSD NVMe 512GB', 50000, 18, (SELECT id FROM categories WHERE nom='Stockage & Mémoire'), 'SSD haute performance'),
('Disque dur externe 1TB', 45000, 12, (SELECT id FROM categories WHERE nom='Stockage & Mémoire'), 'Disque dur externe portable'),
('Clé USB 64GB', 10000, 40, (SELECT id FROM categories WHERE nom='Stockage & Mémoire'), 'Clé USB haute capacité'),
('SSD NVMe 1TB', 85000, 10, (SELECT id FROM categories WHERE nom='Stockage & Mémoire'), 'SSD haute capacité'),
('Écran 24" Dell', 200000, 8, (SELECT id FROM categories WHERE nom='Périphériques & Équipements'), 'Moniteur professionnel'),
('Imprimante laser HP', 180000, 5, (SELECT id FROM categories WHERE nom='Périphériques & Équipements'), 'Imprimante laser monochrome'),
('Onduleur 600VA', 60000, 10, (SELECT id FROM categories WHERE nom='Périphériques & Équipements'), 'Protection électrique');

-- 5. Insérer des factures (si pas déjà fait)
INSERT IGNORE INTO factures (numero, id_client, date_facture, date_creation, statut, total) VALUES
('FACT-2024-07-001', 1, '2024-07-01 10:30:00', NOW(), 'payee', 850000),
('FACT-2024-07-002', 2, '2024-07-02 11:15:00', NOW(), 'payee', 1400000),
('FACT-2024-07-003', 3, '2024-07-03 09:00:00', NOW(), 'payee', 40000),
('FACT-2024-07-004', 1, '2024-07-04 14:30:00', NOW(), 'payee', 250000),
('FACT-2024-07-005', 4, '2024-07-05 16:45:00', NOW(), 'payee', 50000),
('FACT-2024-07-006', 5, '2024-07-06 08:20:00', NOW(), 'payee', 1200000),
('FACT-2024-07-007', 2, '2024-07-07 10:00:00', NOW(), 'payee', 350000),
('FACT-2024-07-008', 3, '2024-07-08 13:30:00', NOW(), 'payee', 950000),
('FACT-2024-07-009', 1, '2024-07-09 09:45:00', NOW(), 'payee', 750000),
('FACT-2024-07-010', 4, '2024-07-10 15:00:00', NOW(), 'payee', 250000),
('FACT-2024-07-011', 5, '2024-07-11 11:30:00', NOW(), 'payee', 350000),
('FACT-2024-07-012', 2, '2024-07-12 08:00:00', NOW(), 'payee', 50000),
('FACT-2024-07-013', 3, '2024-07-13 12:00:00', NOW(), 'payee', 120000),
('FACT-2024-07-014', 1, '2024-07-14 10:30:00', NOW(), 'payee', 1500000),
('FACT-2024-07-015', 4, '2024-07-15 14:00:00', NOW(), 'payee', 45000),
('FACT-2024-07-016', 6, '2024-07-16 09:30:00', NOW(), 'payee', 650000),
('FACT-2024-07-017', 2, '2024-07-17 16:00:00', NOW(), 'payee', 80000),
('FACT-2024-07-018', 3, '2024-07-18 11:00:00', NOW(), 'payee', 300000),
('FACT-2024-07-019', 5, '2024-07-19 14:30:00', NOW(), 'payee', 500000),
('FACT-2024-07-020', 1, '2024-07-20 09:00:00', NOW(), 'payee', 1000000);

-- 6. Insérer les lignes de factures (AVEC designation)
INSERT INTO facture_lignes (id_facture, id_produit, designation, quantite, prix_unitaire, sous_total) VALUES
(1, 1, 'HP EliteBook 840 G6', 1, 850000, 850000),
(2, 7, 'Samsung Galaxy S24 Ultra', 1, 1400000, 1400000),
(3, 16, 'Clavier mécanique RGB', 1, 40000, 40000),
(4, 12, 'Switch Gigabit 24 ports', 1, 250000, 250000),
(5, 19, 'SSD NVMe 512GB', 1, 50000, 50000),
(6, 4, 'MacBook Air M2', 1, 1200000, 1200000),
(7, 8, 'Xiaomi Redmi Note 13 Pro', 1, 350000, 350000),
(8, 2, 'Dell Latitude 5420', 1, 950000, 950000),
(9, 3, 'Lenovo ThinkPad E14', 1, 750000, 750000),
(10, 12, 'Switch Gigabit 24 ports', 1, 250000, 250000),
(11, 9, 'Tecno Camon 20 Pro', 1, 350000, 350000),
(12, 19, 'SSD NVMe 512GB', 1, 50000, 50000),
(13, 11, 'Routeur TP-Link Archer AX73', 1, 120000, 120000),
(14, 6, 'iPhone 15 Pro Max', 1, 1500000, 1500000),
(15, 20, 'Disque dur externe 1TB', 1, 45000, 45000),
(16, 5, 'Acer Aspire 5', 1, 650000, 650000),
(17, 13, 'Routeur WiFi 6 Xiaomi AX3000', 1, 80000, 80000),
(18, 25, 'Onduleur 600VA', 1, 60000, 60000),
(18, 16, 'Clavier mécanique RGB', 1, 40000, 40000),
(19, 2, 'Dell Latitude 5420', 1, 950000, 950000),
(19, 15, 'Souris Logitech M185', 2, 15000, 30000),
(20, 6, 'iPhone 15 Pro Max', 1, 1500000, 1500000),
(20, 19, 'SSD NVMe 512GB', 1, 50000, 50000);

-- 7. Mettre à jour les stocks
UPDATE produits SET stock = stock - 2 WHERE id IN (1, 2, 3, 4, 6, 7, 8);
UPDATE produits SET stock = stock - 1 WHERE id IN (5, 9, 11, 12, 13, 16, 19, 20, 25);
UPDATE produits SET stock = stock - 3 WHERE id IN (15);

-- 8. Vérification finale
SELECT 
    (SELECT COUNT(*) FROM factures) AS nb_factures,
    (SELECT COUNT(*) FROM facture_lignes) AS nb_lignes,
    (SELECT COUNT(*) FROM clients) AS nb_clients,
    (SELECT COUNT(*) FROM produits) AS nb_produits;
