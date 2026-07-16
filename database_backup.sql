/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.14-MariaDB, for debian-linux-gnu (aarch64)
--
-- Host: localhost    Database: ecommerce_design
-- ------------------------------------------------------
-- Server version	10.11.14-MariaDB-0ubuntu0.24.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `date_creation` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES
(1,'Ordinateurs & Portables',NULL,'2026-07-15 04:41:05'),
(2,'Smartphones & Téléphonie',NULL,'2026-07-15 04:41:05'),
(3,'Réseaux & Télécommunications',NULL,'2026-07-15 04:41:05'),
(4,'Accessoires Informatiques',NULL,'2026-07-15 04:41:05'),
(5,'Stockage & Mémoire',NULL,'2026-07-15 04:41:05'),
(6,'Périphériques & Équipements',NULL,'2026-07-15 04:41:05'),
(7,'Télécommunications & Antennes',NULL,'2026-07-15 04:41:05'),
(8,'Gaming & Divertissement',NULL,'2026-07-15 04:41:05'),
(9,'Pièces détachées & Réparation',NULL,'2026-07-15 04:41:05'),
(10,'Solutions professionnelles',NULL,'2026-07-15 04:41:05'),
(11,'Ordinateurs & Portables',NULL,'2026-07-16 01:17:11'),
(12,'Smartphones & Téléphonie',NULL,'2026-07-16 01:17:11'),
(13,'Réseaux & Télécommunications',NULL,'2026-07-16 01:17:11'),
(14,'Accessoires Informatiques',NULL,'2026-07-16 01:17:11'),
(15,'Stockage & Mémoire',NULL,'2026-07-16 01:17:11'),
(16,'Ordinateurs & Portables',NULL,'2026-07-16 01:24:08'),
(17,'Smartphones & Téléphonie',NULL,'2026-07-16 01:24:08'),
(18,'Réseaux & Télécommunications',NULL,'2026-07-16 01:24:08'),
(19,'Accessoires Informatiques',NULL,'2026-07-16 01:24:08'),
(20,'Stockage & Mémoire',NULL,'2026-07-16 01:24:08'),
(21,'Périphériques & Équipements',NULL,'2026-07-16 01:24:08'),
(22,'Ordinateurs & Portables',NULL,'2026-07-16 01:27:16'),
(23,'Smartphones & Téléphonie',NULL,'2026-07-16 01:27:16'),
(24,'Réseaux & Télécommunications',NULL,'2026-07-16 01:27:16'),
(25,'Accessoires Informatiques',NULL,'2026-07-16 01:27:16'),
(26,'Stockage & Mémoire',NULL,'2026-07-16 01:27:16'),
(27,'Périphériques & Équipements',NULL,'2026-07-16 01:27:16'),
(28,'Ordinateurs & Portables',NULL,'2026-07-16 01:29:04'),
(29,'Smartphones & Téléphonie',NULL,'2026-07-16 01:29:04'),
(30,'Réseaux & Télécommunications',NULL,'2026-07-16 01:29:04'),
(31,'Accessoires Informatiques',NULL,'2026-07-16 01:29:04'),
(32,'Stockage & Mémoire',NULL,'2026-07-16 01:29:04'),
(33,'Périphériques & Équipements',NULL,'2026-07-16 01:29:04'),
(34,'Ordinateurs & Portables',NULL,'2026-07-16 01:30:49'),
(35,'Smartphones & Téléphonie',NULL,'2026-07-16 01:30:49'),
(36,'Réseaux & Télécommunications',NULL,'2026-07-16 01:30:49'),
(37,'Accessoires Informatiques',NULL,'2026-07-16 01:30:49'),
(38,'Stockage & Mémoire',NULL,'2026-07-16 01:30:49'),
(39,'Périphériques & Équipements',NULL,'2026-07-16 01:30:49');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `telephone` varchar(30) DEFAULT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `ville` varchar(100) DEFAULT NULL,
  `date_creation` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clients`
--

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
INSERT INTO `clients` VALUES
(1,'Diop','Mamadou','mamadou.diop@email.com','771234567','Dakar, Senegal',NULL,'2026-07-16 01:17:11'),
(2,'Fall','Aminata','aminata.fall@email.com','773456789','Pikine, Senegal',NULL,'2026-07-16 01:17:11'),
(3,'Ndiaye','Oumar','oumar.ndiaye@email.com','774567890','Guédiawaye, Senegal',NULL,'2026-07-16 01:17:11'),
(4,'Sow','Fatou','fatou.sow@email.com','775678901','Rufisque, Senegal',NULL,'2026-07-16 01:17:11'),
(5,'Ba','Moussa','moussa.ba@email.com','776789012','Thiès, Senegal',NULL,'2026-07-16 01:17:11'),
(6,'Kane','Aissatou','aissatou.kane@email.com','777890123','Dakar, Senegal',NULL,'2026-07-16 01:17:11'),
(7,'Diop','Mamadou','mamadou.diop@email.com','771234567','Dakar, Senegal',NULL,'2026-07-16 01:24:08'),
(8,'Fall','Aminata','aminata.fall@email.com','773456789','Pikine, Senegal',NULL,'2026-07-16 01:24:08'),
(9,'Ndiaye','Oumar','oumar.ndiaye@email.com','774567890','Guédiawaye, Senegal',NULL,'2026-07-16 01:24:08'),
(10,'Sow','Fatou','fatou.sow@email.com','775678901','Rufisque, Senegal',NULL,'2026-07-16 01:24:08'),
(11,'Ba','Moussa','moussa.ba@email.com','776789012','Thiès, Senegal',NULL,'2026-07-16 01:24:08'),
(12,'Kane','Aissatou','aissatou.kane@email.com','777890123','Dakar, Senegal',NULL,'2026-07-16 01:24:08'),
(13,'Diop','Mamadou','mamadou.diop@email.com','771234567','Dakar, Senegal',NULL,'2026-07-16 01:27:16'),
(14,'Fall','Aminata','aminata.fall@email.com','773456789','Pikine, Senegal',NULL,'2026-07-16 01:27:16'),
(15,'Ndiaye','Oumar','oumar.ndiaye@email.com','774567890','Guédiawaye, Senegal',NULL,'2026-07-16 01:27:16'),
(16,'Sow','Fatou','fatou.sow@email.com','775678901','Rufisque, Senegal',NULL,'2026-07-16 01:27:16'),
(17,'Ba','Moussa','moussa.ba@email.com','776789012','Thiès, Senegal',NULL,'2026-07-16 01:27:16'),
(18,'Kane','Aissatou','aissatou.kane@email.com','777890123','Dakar, Senegal',NULL,'2026-07-16 01:27:16'),
(19,'Diop','Mamadou','mamadou.diop@email.com','771234567','Dakar, Senegal',NULL,'2026-07-16 01:29:04'),
(20,'Fall','Aminata','aminata.fall@email.com','773456789','Pikine, Senegal',NULL,'2026-07-16 01:29:04'),
(21,'Ndiaye','Oumar','oumar.ndiaye@email.com','774567890','Guédiawaye, Senegal',NULL,'2026-07-16 01:29:04'),
(22,'Sow','Fatou','fatou.sow@email.com','775678901','Rufisque, Senegal',NULL,'2026-07-16 01:29:04'),
(23,'Ba','Moussa','moussa.ba@email.com','776789012','Thiès, Senegal',NULL,'2026-07-16 01:29:04'),
(24,'Kane','Aissatou','aissatou.kane@email.com','777890123','Dakar, Senegal',NULL,'2026-07-16 01:29:04'),
(25,'Diop','Mamadou','mamadou.diop@email.com','771234567','Dakar, Senegal',NULL,'2026-07-16 01:30:49'),
(26,'Fall','Aminata','aminata.fall@email.com','773456789','Pikine, Senegal',NULL,'2026-07-16 01:30:49'),
(27,'Ndiaye','Oumar','oumar.ndiaye@email.com','774567890','Guédiawaye, Senegal',NULL,'2026-07-16 01:30:49'),
(28,'Sow','Fatou','fatou.sow@email.com','775678901','Rufisque, Senegal',NULL,'2026-07-16 01:30:49'),
(29,'Ba','Moussa','moussa.ba@email.com','776789012','Thiès, Senegal',NULL,'2026-07-16 01:30:49'),
(30,'Kane','Aissatou','aissatou.kane@email.com','777890123','Dakar, Senegal',NULL,'2026-07-16 01:30:49');
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `facture_lignes`
--

DROP TABLE IF EXISTS `facture_lignes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `facture_lignes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_facture` int(11) NOT NULL,
  `id_produit` int(11) DEFAULT NULL,
  `designation` varchar(200) NOT NULL,
  `quantite` int(11) NOT NULL DEFAULT 1,
  `prix_unitaire` decimal(12,2) NOT NULL DEFAULT 0.00,
  `sous_total` decimal(12,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`),
  KEY `id_facture` (`id_facture`),
  KEY `id_produit` (`id_produit`),
  CONSTRAINT `facture_lignes_ibfk_1` FOREIGN KEY (`id_facture`) REFERENCES `factures` (`id`) ON DELETE CASCADE,
  CONSTRAINT `facture_lignes_ibfk_2` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `facture_lignes`
--

LOCK TABLES `facture_lignes` WRITE;
/*!40000 ALTER TABLE `facture_lignes` DISABLE KEYS */;
INSERT INTO `facture_lignes` VALUES
(1,1,1,'HP EliteBook 840 G6',1,850000.00,850000.00),
(2,2,7,'Samsung Galaxy S24 Ultra',1,1400000.00,1400000.00),
(3,3,16,'Clavier mécanique RGB',1,40000.00,40000.00),
(4,4,12,'Switch Gigabit 24 ports',1,250000.00,250000.00),
(5,5,19,'SSD NVMe 512GB',1,50000.00,50000.00),
(6,6,4,'MacBook Air M2',1,1200000.00,1200000.00),
(7,7,8,'Xiaomi Redmi Note 13 Pro',1,350000.00,350000.00),
(8,8,2,'Dell Latitude 5420',1,950000.00,950000.00),
(9,9,3,'Lenovo ThinkPad E14',1,750000.00,750000.00),
(10,10,12,'Switch Gigabit 24 ports',1,250000.00,250000.00),
(11,11,9,'Tecno Camon 20 Pro',1,350000.00,350000.00),
(12,12,19,'SSD NVMe 512GB',1,50000.00,50000.00),
(13,13,11,'Routeur TP-Link Archer AX73',1,120000.00,120000.00),
(14,14,6,'iPhone 15 Pro Max',1,1500000.00,1500000.00),
(15,15,20,'Disque dur externe 1TB',1,45000.00,45000.00),
(16,16,5,'Acer Aspire 5',1,650000.00,650000.00),
(17,17,13,'Routeur WiFi 6 Xiaomi AX3000',1,80000.00,80000.00),
(18,18,25,'Onduleur 600VA',1,60000.00,60000.00),
(19,18,16,'Clavier mécanique RGB',1,40000.00,40000.00),
(20,19,2,'Dell Latitude 5420',1,950000.00,950000.00),
(21,19,15,'Souris Logitech M185',2,15000.00,30000.00),
(22,20,6,'iPhone 15 Pro Max',1,1500000.00,1500000.00),
(23,20,19,'SSD NVMe 512GB',1,50000.00,50000.00);
/*!40000 ALTER TABLE `facture_lignes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `factures`
--

DROP TABLE IF EXISTS `factures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `factures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numero` varchar(30) NOT NULL,
  `id_client` int(11) NOT NULL,
  `date_facture` date NOT NULL,
  `statut` enum('brouillon','payee','impayee','annulee') NOT NULL DEFAULT 'impayee',
  `total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `date_creation` datetime DEFAULT current_timestamp(),
  `tva` decimal(5,2) DEFAULT 0.00,
  `montant_tva` decimal(12,2) DEFAULT 0.00,
  PRIMARY KEY (`id`),
  UNIQUE KEY `numero` (`numero`),
  KEY `id_client` (`id_client`),
  CONSTRAINT `factures_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `factures`
--

LOCK TABLES `factures` WRITE;
/*!40000 ALTER TABLE `factures` DISABLE KEYS */;
INSERT INTO `factures` VALUES
(1,'FACT-2024-07-001',1,'2024-07-01','payee',850000.00,'2026-07-16 01:29:04',0.00,0.00),
(2,'FACT-2024-07-002',2,'2024-07-02','payee',1400000.00,'2026-07-16 01:29:04',0.00,0.00),
(3,'FACT-2024-07-003',3,'2024-07-03','payee',40000.00,'2026-07-16 01:29:04',0.00,0.00),
(4,'FACT-2024-07-004',1,'2024-07-04','payee',250000.00,'2026-07-16 01:29:04',0.00,0.00),
(5,'FACT-2024-07-005',4,'2024-07-05','payee',50000.00,'2026-07-16 01:29:04',0.00,0.00),
(6,'FACT-2024-07-006',5,'2024-07-06','payee',1200000.00,'2026-07-16 01:29:04',0.00,0.00),
(7,'FACT-2024-07-007',2,'2024-07-07','payee',350000.00,'2026-07-16 01:29:04',0.00,0.00),
(8,'FACT-2024-07-008',3,'2024-07-08','payee',950000.00,'2026-07-16 01:29:04',0.00,0.00),
(9,'FACT-2024-07-009',1,'2024-07-09','payee',750000.00,'2026-07-16 01:29:04',0.00,0.00),
(10,'FACT-2024-07-010',4,'2024-07-10','payee',250000.00,'2026-07-16 01:29:04',0.00,0.00),
(11,'FACT-2024-07-011',5,'2024-07-11','payee',350000.00,'2026-07-16 01:29:04',0.00,0.00),
(12,'FACT-2024-07-012',2,'2024-07-12','payee',50000.00,'2026-07-16 01:29:04',0.00,0.00),
(13,'FACT-2024-07-013',3,'2024-07-13','payee',120000.00,'2026-07-16 01:29:04',0.00,0.00),
(14,'FACT-2024-07-014',1,'2024-07-14','payee',1500000.00,'2026-07-16 01:29:04',0.00,0.00),
(15,'FACT-2024-07-015',4,'2024-07-15','payee',45000.00,'2026-07-16 01:29:04',0.00,0.00),
(16,'FACT-2024-07-016',6,'2024-07-16','payee',650000.00,'2026-07-16 01:29:04',0.00,0.00),
(17,'FACT-2024-07-017',2,'2024-07-17','payee',80000.00,'2026-07-16 01:29:04',0.00,0.00),
(18,'FACT-2024-07-018',3,'2024-07-18','payee',300000.00,'2026-07-16 01:29:04',0.00,0.00),
(19,'FACT-2024-07-019',5,'2024-07-19','payee',500000.00,'2026-07-16 01:29:04',0.00,0.00),
(20,'FACT-2024-07-020',1,'2024-07-20','payee',1000000.00,'2026-07-16 01:29:04',0.00,0.00);
/*!40000 ALTER TABLE `factures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fournisseurs`
--

DROP TABLE IF EXISTS `fournisseurs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `fournisseurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom_entreprise` varchar(150) NOT NULL,
  `contact_nom` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `telephone` varchar(30) DEFAULT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `ville` varchar(100) DEFAULT NULL,
  `date_creation` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fournisseurs`
--

LOCK TABLES `fournisseurs` WRITE;
/*!40000 ALTER TABLE `fournisseurs` DISABLE KEYS */;
INSERT INTO `fournisseurs` VALUES
(1,'Omega informatique CONSULTING','Sivy','sibymohamed24@gmail.com','77654','Dk','Dk','2026-07-15 02:04:05');
/*!40000 ALTER TABLE `fournisseurs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int(11) NOT NULL,
  `action` varchar(100) NOT NULL,
  `details` text DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `date_creation` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_utilisateur` (`id_utilisateur`),
  CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logs`
--

LOCK TABLES `logs` WRITE;
/*!40000 ALTER TABLE `logs` DISABLE KEYS */;
INSERT INTO `logs` VALUES
(1,1,'create','Catégorie ID 1 créée','127.0.0.1','2026-07-15 02:02:57'),
(2,1,'create','Produit ID 1 créé','127.0.0.1','2026-07-15 02:05:28'),
(3,1,'update','Produit ID 1 modifié','127.0.0.1','2026-07-15 02:12:12'),
(4,1,'create','Catégorie ID 2 créée','127.0.0.1','2026-07-15 02:21:23'),
(5,1,'create','Produit ID 2 créé','127.0.0.1','2026-07-15 02:22:37'),
(6,1,'update','Produit ID 1 modifié','127.0.0.1','2026-07-15 15:35:36'),
(7,1,'update','Produit ID 2 modifié','127.0.0.1','2026-07-15 15:38:13'),
(8,1,'update','Produit ID 3 modifié','127.0.0.1','2026-07-15 15:40:21'),
(9,1,'update','Produit ID 4 modifié','127.0.0.1','2026-07-15 15:41:43'),
(10,1,'update','Produit ID 5 modifié','127.0.0.1','2026-07-15 15:42:45'),
(11,1,'update','Produit ID 6 modifié','127.0.0.1','2026-07-15 15:43:36'),
(12,1,'update','Produit ID 7 modifié','127.0.0.1','2026-07-15 15:44:51'),
(13,1,'update','Produit ID 43 modifié','127.0.0.1','2026-07-15 15:45:55'),
(14,1,'update','Produit ID 31 modifié','127.0.0.1','2026-07-15 15:48:20'),
(15,1,'update','Produit ID 32 modifié','127.0.0.1','2026-07-15 15:49:56'),
(16,1,'update','Produit ID 8 modifié','127.0.0.1','2026-07-15 15:51:17'),
(17,1,'update','Produit ID 9 modifié','127.0.0.1','2026-07-15 15:52:09'),
(18,1,'update','Produit ID 10 modifié','127.0.0.1','2026-07-15 15:53:06'),
(19,1,'update','Produit ID 11 modifié','127.0.0.1','2026-07-15 15:54:01'),
(20,1,'update','Produit ID 44 modifié','127.0.0.1','2026-07-15 15:56:10'),
(21,1,'update','Produit ID 38 modifié','127.0.0.1','2026-07-15 15:57:28'),
(22,1,'update','Produit ID 41 modifié','127.0.0.1','2026-07-15 15:58:26'),
(23,1,'update','Produit ID 39 modifié','127.0.0.1','2026-07-15 15:59:20'),
(24,1,'update','Produit ID 42 modifié','127.0.0.1','2026-07-15 16:00:44'),
(25,1,'update','Produit ID 47 modifié','127.0.0.1','2026-07-15 16:01:42'),
(26,1,'update','Produit ID 37 modifié','127.0.0.1','2026-07-15 16:02:28'),
(27,1,'update','Produit ID 36 modifié','127.0.0.1','2026-07-15 16:03:20'),
(28,1,'update','Produit ID 48 modifié','127.0.0.1','2026-07-15 16:05:24'),
(29,1,'update','Produit ID 40 modifié','127.0.0.1','2026-07-15 16:07:03'),
(30,1,'update','Produit ID 62 modifié','127.0.0.1','2026-07-15 16:09:17'),
(31,1,'update','Produit ID 63 modifié','127.0.0.1','2026-07-15 16:09:57'),
(32,1,'update','Produit ID 64 modifié','127.0.0.1','2026-07-15 16:11:15'),
(33,1,'update','Produit ID 65 modifié','127.0.0.1','2026-07-15 16:14:44'),
(34,1,'update','Produit ID 66 modifié','127.0.0.1','2026-07-15 16:15:41'),
(35,1,'update','Produit ID 82 modifié','127.0.0.1','2026-07-15 16:17:03'),
(36,1,'update','Produit ID 45 modifié','127.0.0.1','2026-07-15 16:18:17'),
(37,1,'update','Produit ID 43 modifié','127.0.0.1','2026-07-15 16:19:35'),
(38,1,'update','Produit ID 81 modifié','127.0.0.1','2026-07-15 16:21:43'),
(39,1,'update','Produit ID 12 modifié','127.0.0.1','2026-07-15 16:22:42'),
(40,1,'update','Produit ID 13 modifié','127.0.0.1','2026-07-15 16:23:16'),
(41,1,'update','Produit ID 14 modifié','127.0.0.1','2026-07-15 16:23:45'),
(42,1,'update','Produit ID 14 modifié','127.0.0.1','2026-07-15 16:24:30'),
(43,1,'update','Produit ID 15 modifié','127.0.0.1','2026-07-15 16:25:34'),
(44,1,'update','Produit ID 24 modifié','127.0.0.1','2026-07-15 16:27:11'),
(45,1,'update','Produit ID 25 modifié','127.0.0.1','2026-07-15 16:28:13'),
(46,1,'update','Produit ID 16 modifié','127.0.0.1','2026-07-15 16:29:18'),
(47,1,'update','Produit ID 17 modifié','127.0.0.1','2026-07-15 16:30:12'),
(48,1,'update','Produit ID 18 modifié','127.0.0.1','2026-07-15 16:31:06'),
(49,1,'update','Produit ID 49 modifié','127.0.0.1','2026-07-15 16:32:11'),
(50,1,'update','Produit ID 19 modifié','127.0.0.1','2026-07-15 16:33:05'),
(51,1,'update','Produit ID 20 modifié','127.0.0.1','2026-07-15 16:33:53'),
(52,1,'update','Produit ID 46 modifié','127.0.0.1','2026-07-15 16:35:14'),
(53,1,'update','Produit ID 34 modifié','127.0.0.1','2026-07-15 16:37:14'),
(54,1,'update','Produit ID 21 modifié','127.0.0.1','2026-07-15 16:38:01'),
(55,1,'update','Produit ID 22 modifié','127.0.0.1','2026-07-15 16:38:53'),
(56,1,'update','Produit ID 23 modifié','127.0.0.1','2026-07-15 16:39:34'),
(57,1,'update','Produit ID 26 modifié','127.0.0.1','2026-07-15 16:40:27'),
(58,1,'update','Produit ID 27 modifié','127.0.0.1','2026-07-15 16:41:11'),
(59,1,'update','Produit ID 28 modifié','127.0.0.1','2026-07-15 16:42:02'),
(60,1,'update','Produit ID 29 modifié','127.0.0.1','2026-07-15 16:42:56'),
(61,1,'update','Produit ID 30 modifié','127.0.0.1','2026-07-15 16:43:51'),
(62,1,'update','Produit ID 33 modifié','127.0.0.1','2026-07-15 16:44:39'),
(63,1,'update','Produit ID 50 modifié','127.0.0.1','2026-07-15 16:45:21'),
(64,1,'update','Produit ID 35 modifié','127.0.0.1','2026-07-15 16:46:06'),
(65,1,'update','Produit ID 69 modifié','127.0.0.1','2026-07-15 19:01:07'),
(66,1,'update','Produit ID 88 modifié','127.0.0.1','2026-07-15 19:03:14'),
(67,1,'update','Produit ID 67 modifié','127.0.0.1','2026-07-15 19:05:13'),
(68,1,'update','Produit ID 71 modifié','127.0.0.1','2026-07-15 19:07:03'),
(69,1,'update','Produit ID 68 modifié','127.0.0.1','2026-07-15 19:07:43'),
(70,1,'update','Produit ID 95 modifié','127.0.0.1','2026-07-15 19:09:34'),
(71,1,'update','Produit ID 90 modifié','127.0.0.1','2026-07-15 19:10:17'),
(72,1,'update','Produit ID 51 modifié','127.0.0.1','2026-07-15 19:10:53'),
(73,1,'update','Produit ID 52 modifié','127.0.0.1','2026-07-15 19:11:40'),
(74,1,'update','Produit ID 53 modifié','127.0.0.1','2026-07-15 19:12:35'),
(75,1,'update','Produit ID 54 modifié','127.0.0.1','2026-07-15 19:13:17'),
(76,1,'update','Produit ID 55 modifié','127.0.0.1','2026-07-15 19:14:04'),
(77,1,'update','Produit ID 56 modifié','127.0.0.1','2026-07-15 19:14:31'),
(78,1,'update','Produit ID 57 modifié','127.0.0.1','2026-07-15 19:15:06'),
(79,1,'update','Produit ID 58 modifié','127.0.0.1','2026-07-15 19:15:37'),
(80,1,'update','Produit ID 59 modifié','127.0.0.1','2026-07-15 19:16:12'),
(81,1,'update','Produit ID 60 modifié','127.0.0.1','2026-07-15 19:16:48'),
(82,1,'update','Produit ID 72 modifié','127.0.0.1','2026-07-15 19:21:12'),
(83,1,'update','Produit ID 73 modifié','127.0.0.1','2026-07-15 19:21:50'),
(84,1,'update','Produit ID 74 modifié','127.0.0.1','2026-07-15 19:22:21'),
(85,1,'update','Produit ID 61 modifié','127.0.0.1','2026-07-15 19:22:54'),
(86,1,'update','Produit ID 76 modifié','127.0.0.1','2026-07-15 19:23:56'),
(87,1,'update','Produit ID 77 modifié','127.0.0.1','2026-07-15 19:27:07'),
(88,1,'update','Produit ID 70 modifié','127.0.0.1','2026-07-15 19:27:47'),
(89,1,'update','Produit ID 78 modifié','127.0.0.1','2026-07-15 19:29:35'),
(90,1,'update','Produit ID 80 modifié','127.0.0.1','2026-07-15 19:31:46'),
(91,1,'update','Produit ID 75 modifié','127.0.0.1','2026-07-15 19:32:20'),
(92,1,'update','Produit ID 79 modifié','127.0.0.1','2026-07-15 19:32:56'),
(93,1,'update','Produit ID 84 modifié','127.0.0.1','2026-07-15 19:34:45'),
(94,1,'update','Produit ID 85 modifié','127.0.0.1','2026-07-15 19:38:07'),
(95,1,'update','Produit ID 86 modifié','127.0.0.1','2026-07-15 19:38:38'),
(96,1,'update','Produit ID 96 modifié','127.0.0.1','2026-07-15 19:39:41'),
(97,1,'update','Produit ID 83 modifié','127.0.0.1','2026-07-15 19:40:14'),
(98,1,'update','Produit ID 89 modifié','127.0.0.1','2026-07-15 19:41:39'),
(99,1,'update','Produit ID 92 modifié','127.0.0.1','2026-07-15 19:42:10'),
(100,1,'update','Produit ID 93 modifié','127.0.0.1','2026-07-15 19:44:25'),
(101,1,'update','Produit ID 94 modifié','127.0.0.1','2026-07-15 19:45:32'),
(102,1,'update','Produit ID 97 modifié','127.0.0.1','2026-07-15 19:46:39'),
(103,1,'create','Facture ID 22 créée','127.0.0.1','2026-07-16 01:19:36');
/*!40000 ALTER TABLE `logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produits`
--

DROP TABLE IF EXISTS `produits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `produits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `prix` decimal(12,2) NOT NULL DEFAULT 0.00,
  `stock` int(11) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `id_fournisseur` int(11) DEFAULT NULL,
  `date_creation` datetime DEFAULT current_timestamp(),
  `id_categorie` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_fournisseur` (`id_fournisseur`),
  KEY `id_categorie` (`id_categorie`),
  CONSTRAINT `produits_ibfk_1` FOREIGN KEY (`id_fournisseur`) REFERENCES `fournisseurs` (`id`) ON DELETE SET NULL,
  CONSTRAINT `produits_ibfk_2` FOREIGN KEY (`id_categorie`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=213 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produits`
--

LOCK TABLES `produits` WRITE;
/*!40000 ALTER TABLE `produits` DISABLE KEYS */;
INSERT INTO `produits` VALUES
(1,'Ordinateur Portable HP EliteBook 840 G6','',850000.00,10,'prod_6a57a8c848a80.jpeg',NULL,'2026-07-15 04:45:34',1),
(2,'Ordinateur Portable Dell Latitude 5420','',950000.00,6,'prod_6a57a965cb09e.jpeg',NULL,'2026-07-15 04:45:34',1),
(3,'Ordinateur Portable Lenovo ThinkPad E14','',750000.00,13,'prod_6a57a9e590a0b.jpeg',NULL,'2026-07-15 04:45:34',1),
(4,'Ordinateur Portable Acer Aspire 5','',650000.00,8,'prod_6a57aa372bfe8.jpeg',NULL,'2026-07-15 04:45:34',1),
(5,'Ordinateur Portable Asus Vivobook 15','',550000.00,19,'prod_6a57aa75cc35c.jpeg',NULL,'2026-07-15 04:45:34',1),
(6,'MacBook Air M2 13\"','',1200000.00,3,'prod_6a57aaa89b07a.jpeg',NULL,'2026-07-15 04:45:34',1),
(7,'MacBook Pro M3 14\"','',1800000.00,1,'prod_6a57aaf3220de.jpeg',NULL,'2026-07-15 04:45:34',1),
(8,'Ordinateur Portable Huawei MateBook D15','',600000.00,5,'prod_6a57ac7518346.jpeg',NULL,'2026-07-15 04:45:34',1),
(9,'Mini PC Intel NUC','',400000.00,5,'prod_6a57aca9a237d.jpeg',NULL,'2026-07-15 04:45:34',1),
(10,'Station de travail Dell Precision 5560','',1500000.00,4,'prod_6a57ace2caa88.jpeg',NULL,'2026-07-15 04:45:34',1),
(11,'iPhone 15 Pro Max 256GB','',1500000.00,9,'prod_6a57ad19049a1.jpeg',NULL,'2026-07-15 04:45:34',2),
(12,'iPhone 15 Pro 128GB','',1200000.00,14,'prod_6a57b3d24d777.jpeg',NULL,'2026-07-15 04:45:34',2),
(13,'iPhone 14 128GB','',900000.00,19,'prod_6a57b3f4e2e1b.jpeg',NULL,'2026-07-15 04:45:34',2),
(14,'Samsung Galaxy S24 Ultra','',1400000.00,8,'prod_6a57b4114bcff.jpeg',NULL,'2026-07-15 04:45:34',2),
(15,'Samsung Galaxy S24','',1000000.00,9,'prod_6a57b47e9670a.jpeg',NULL,'2026-07-15 04:45:34',2),
(16,'Samsung Galaxy A55 5G','',500000.00,24,'prod_6a57b55e38543.jpeg',NULL,'2026-07-15 04:45:34',2),
(17,'Xiaomi Redmi Note 13 Pro','',350000.00,30,'prod_6a57b594d4e1c.jpeg',NULL,'2026-07-15 04:45:34',2),
(18,'Xiaomi 14T Pro','',700000.00,15,'prod_6a57b5ca3cbc3.jpeg',NULL,'2026-07-15 04:45:34',2),
(19,'Google Pixel 8 Pro','',1100000.00,4,'prod_6a57b641756b3.jpeg',NULL,'2026-07-15 04:45:34',2),
(20,'OnePlus 12','',950000.00,6,'prod_6a57b671501d4.jpeg',NULL,'2026-07-15 04:45:34',2),
(21,'Tecno Camon 20 Pro','',350000.00,20,'prod_6a57b7698bd9d.jpeg',NULL,'2026-07-15 04:45:34',2),
(22,'Tecno Spark 20','',180000.00,35,'prod_6a57b79d4fd02.jpeg',NULL,'2026-07-15 04:45:34',2),
(23,'Infinix Zero 30','',300000.00,25,'prod_6a57b7c5f3aca.jpeg',NULL,'2026-07-15 04:45:34',2),
(24,'Routeur WiFi TP-Link Archer AX73','',120000.00,15,'prod_6a57b4df8934f.jpeg',NULL,'2026-07-15 04:45:34',3),
(25,'Routeur WiFi 6 Xiaomi AX3000','',80000.00,19,'prod_6a57b51d99cde.webp',NULL,'2026-07-15 04:45:34',3),
(26,'Switch Gigabit 24 ports','',250000.00,8,'prod_6a57b7fb61729.jpeg',NULL,'2026-07-15 04:45:34',3),
(27,'Switch Gigabit 8 ports','',80000.00,15,'prod_6a57b8276d25d.jpeg',NULL,'2026-07-15 04:45:34',3),
(28,'Modem 4G LTE Huawei E5577','',65000.00,12,'prod_6a57b85abd04e.jpeg',NULL,'2026-07-15 04:45:34',3),
(29,'Routeur 5G Huawei 5G CPE Pro','',450000.00,5,'prod_6a57b8908ca41.jpeg',NULL,'2026-07-15 04:45:34',3),
(30,'Point d\'accès Ubiquiti UniFi AP','',180000.00,10,'prod_6a57b8c75fe8f.jpeg',NULL,'2026-07-15 04:45:34',3),
(31,'Câble réseau Cat6 5m','',5000.00,50,'prod_6a57abc466fa2.webp',NULL,'2026-07-15 04:45:34',3),
(32,'Câble réseau Cat6 10m','',8000.00,40,'prod_6a57ac249568f.webp',NULL,'2026-07-15 04:45:34',3),
(33,'Fibre optique LC-LC 3m','',12000.00,20,'prod_6a57b8f7aeb3b.jpeg',NULL,'2026-07-15 04:45:34',3),
(34,'Boîtier PTO fibre optique','',25000.00,15,'prod_6a57b73a3cb92.jpeg',NULL,'2026-07-15 04:45:34',3),
(35,'Routeur MikroTik hAP ac2','',150000.00,8,'prod_6a57b94ed47cd.jpeg',NULL,'2026-07-15 04:45:34',3),
(36,'Souris sans fil Logitech M185','',15000.00,30,'prod_6a57af48b6446.jpeg',NULL,'2026-07-15 04:45:34',4),
(37,'Souris Gaming Logitech G402','',35000.00,15,'prod_6a57af14e54f7.jpeg',NULL,'2026-07-15 04:45:34',4),
(38,'Clavier mécanique RGB','',40000.00,12,'prod_6a57ade8cbdb5.jpeg',NULL,'2026-07-15 04:45:34',4),
(39,'Clavier sans fil Logitech K380','',30000.00,20,'prod_6a57ae58f1171.jpeg',NULL,'2026-07-15 04:45:34',4),
(40,'Webcam HD 1080p','',25000.00,10,'prod_6a57b0271e99c.jpeg',NULL,'2026-07-15 04:45:34',4),
(41,'Casque Gaming HyperX Cloud','',55000.00,8,'prod_6a57ae2232a62.webp',NULL,'2026-07-15 04:45:34',4),
(42,'Écouteurs Bluetooth Sony WH-1000XM5','',250000.00,5,'prod_6a57aeac29d69.jpeg',NULL,'2026-07-15 04:45:34',4),
(43,'Câble HDMI 2m','',5000.00,50,'prod_6a57ab332ba86.jpeg',NULL,'2026-07-15 04:45:34',4),
(44,'Câble USB-C 1.5m','',3000.00,60,'prod_6a57ad9a08a8c.jpeg',NULL,'2026-07-15 04:45:34',4),
(45,'Chargeur USB-C 65W','',15000.00,25,'prod_6a57b2c97536b.jpeg',NULL,'2026-07-15 04:45:34',4),
(46,'Batterie externe 20000mAh','',25000.00,18,'prod_6a57b6c2d6a25.jpeg',NULL,'2026-07-15 04:45:34',4),
(47,'Hub USB-C 7 ports','',35000.00,10,'prod_6a57aee6c2f8e.jpeg',NULL,'2026-07-15 04:45:34',4),
(48,'Station d\'accueil Dell','',120000.00,6,'prod_6a57afc43674c.jpeg',NULL,'2026-07-15 04:45:34',4),
(49,'SSD NVMe 256GB','',35000.00,20,'prod_6a57b60bbfd98.jpeg',NULL,'2026-07-15 04:45:34',5),
(50,'SSD NVMe 512GB','',50000.00,18,'prod_6a57b92160c37.jpeg',NULL,'2026-07-15 04:45:34',5),
(51,'SSD NVMe 1TB','',85000.00,15,'prod_6a57db3d136e7.jpeg',NULL,'2026-07-15 04:45:34',5),
(52,'SSD SATA 256GB','',30000.00,25,'prod_6a57db6c1cadf.jpeg',NULL,'2026-07-15 04:45:34',5),
(53,'Disque dur externe 1TB','',45000.00,12,'prod_6a57dba3c69c6.jpeg',NULL,'2026-07-15 04:45:34',5),
(54,'Disque dur externe 2TB','',70000.00,8,'prod_6a57dbcdb36bc.jpeg',NULL,'2026-07-15 04:45:34',5),
(55,'Clé USB 32GB','',7000.00,50,'prod_6a57dbfc85ed4.jpeg',NULL,'2026-07-15 04:45:34',5),
(56,'Clé USB 64GB','',10000.00,40,'prod_6a57dc17a81f0.jpeg',NULL,'2026-07-15 04:45:34',5),
(57,'Carte SD 128GB','',15000.00,30,'prod_6a57dc3a63706.jpeg',NULL,'2026-07-15 04:45:34',5),
(58,'Carte SD 256GB','',25000.00,20,'prod_6a57dc59f02e2.jpeg',NULL,'2026-07-15 04:45:34',5),
(59,'Mémoire RAM DDR4 8GB','',25000.00,15,'prod_6a57dc7c15ba7.jpeg',NULL,'2026-07-15 04:45:34',5),
(60,'Mémoire RAM DDR4 16GB','',45000.00,10,'prod_6a57dca04ac69.jpeg',NULL,'2026-07-15 04:45:34',5),
(61,'Mémoire RAM DDR5 16GB','',60000.00,8,'prod_6a57de0e1fbd9.jpeg',NULL,'2026-07-15 04:45:34',5),
(62,'Écran 24\" Dell','',200000.00,8,'prod_6a57b0acf2d9d.jpeg',NULL,'2026-07-15 04:45:34',6),
(63,'Écran 27\" Samsung','',250000.00,6,'prod_6a57b0d512c7b.jpeg',NULL,'2026-07-15 04:45:34',6),
(64,'Écran 32\" Gaming','',350000.00,4,'prod_6a57b123326c4.jpeg',NULL,'2026-07-15 04:45:34',6),
(65,'Imprimante laser HP LaserJet','',180000.00,5,'prod_6a57b1f452a54.jpeg',NULL,'2026-07-15 04:45:34',6),
(66,'Imprimante multifonction Epson','',250000.00,3,'prod_6a57b22dcbe47.jpeg',NULL,'2026-07-15 04:45:34',6),
(67,'Scanner A4','',100000.00,5,'prod_6a57d9e9ce8f3.jpeg',NULL,'2026-07-15 04:45:34',6),
(68,'Onduleur 600VA','',60000.00,10,'prod_6a57da7f1ad5a.jpeg',NULL,'2026-07-15 04:45:34',6),
(69,'Onduleur 1200VA','',120000.00,6,'prod_6a57d8f302ffe.jpeg',NULL,'2026-07-15 04:45:34',6),
(70,'Multiprise parafoudre','',10000.00,30,'prod_6a57df33e35b5.jpeg',NULL,'2026-07-15 04:45:34',6),
(71,'Tablette graphique Wacom','',90000.00,4,'prod_6a57da57c359c.jpeg',NULL,'2026-07-15 04:45:34',6),
(72,'Antenne WiFi 2.4GHz','',15000.00,12,'prod_6a57dda85ba30.jpeg',NULL,'2026-07-15 04:45:34',7),
(73,'Antenne extérieure 5G','',80000.00,5,'prod_6a57ddcec029c.jpeg',NULL,'2026-07-15 04:45:34',7),
(74,'Parabole 60cm','',40000.00,8,'prod_6a57dded79a59.jpeg',NULL,'2026-07-15 04:45:34',7),
(75,'Boîtier de connexion fibre','',15000.00,15,'prod_6a57e0447b71a.jpeg',NULL,'2026-07-15 04:45:34',7),
(76,'Amplificateur signal 4G','',55000.00,6,'prod_6a57de4c276c5.webp',NULL,'2026-07-15 04:45:34',7),
(77,'Antenne directionnelle','',30000.00,10,'prod_6a57df0b54805.jpeg',NULL,'2026-07-15 04:45:34',7),
(78,'Câble coaxial RG6 10m','',8000.00,25,'prod_6a57df9f30874.jpeg',NULL,'2026-07-15 04:45:34',7),
(79,'Connecteur F','',1000.00,100,'prod_6a57e06891b09.jpeg',NULL,'2026-07-15 04:45:34',7),
(80,'Manette Xbox Series X','',55000.00,10,'prod_6a57e022b51d2.jpeg',NULL,'2026-07-15 04:45:34',8),
(81,'Manette PlayStation 5','',60000.00,8,'prod_6a57b39760c83.jpeg',NULL,'2026-07-15 04:45:34',8),
(82,'Casque VR Meta Quest 3','',600000.00,3,'prod_6a57b27fadd45.jpeg',NULL,'2026-07-15 04:45:34',8),
(83,'Volant Gaming Logitech G29','',250000.00,4,'prod_6a57e21ea599c.jpeg',NULL,'2026-07-15 04:45:34',8),
(84,'Tapis de souris Gaming XL','',15000.00,20,'prod_6a57e0d50e817.jpeg',NULL,'2026-07-15 04:45:34',8),
(85,'Support écran 2 bras','',40000.00,6,'prod_6a57e19fd058e.jpeg',NULL,'2026-07-15 04:45:34',8),
(86,'Ventilateur CPU','',8000.00,15,'prod_6a57e1be31fba.jpeg',NULL,'2026-07-15 04:45:34',9),
(88,'Batterie smartphone','',15000.00,20,'prod_6a57d972c0297.jpeg',NULL,'2026-07-15 04:45:34',9),
(89,'Écran de remplacement','',50000.00,5,'prod_6a57e2731cf90.jpeg',NULL,'2026-07-15 04:45:34',9),
(90,'Adaptateur secteur 19V','',12000.00,10,'prod_6a57db19eba1b.jpeg',NULL,'2026-07-15 04:45:34',9),
(92,'Serveur Dell PowerEdge T40','',1200000.00,3,'prod_6a57e29217cf6.jpeg',NULL,'2026-07-15 04:45:34',10),
(93,'NAS Synology 2 baies','',350000.00,4,'prod_6a57e319ccb5c.jpeg',NULL,'2026-07-15 04:45:34',10),
(94,'NAS Synology 4 baies','',600000.00,2,'prod_6a57e35cdaeca.png',NULL,'2026-07-15 04:45:34',10),
(95,'Firewall MikroTik','',180000.00,3,'prod_6a57daee31a36.jpeg',NULL,'2026-07-15 04:45:34',10),
(96,'Switch PoE 24 ports','',300000.00,4,'prod_6a57e1fdb1518.jpeg',NULL,'2026-07-15 04:45:34',10),
(97,'Routeur Cisco 1921','',750000.00,2,'prod_6a57e39f06955.jpeg',NULL,'2026-07-15 04:45:34',10),
(99,'HP EliteBook 840 G6',NULL,850000.00,15,NULL,NULL,'2026-07-16 01:17:11',NULL),
(100,'Dell Latitude 5420',NULL,950000.00,8,NULL,NULL,'2026-07-16 01:17:11',NULL),
(101,'Lenovo ThinkPad E14',NULL,750000.00,12,NULL,NULL,'2026-07-16 01:17:11',NULL),
(102,'MacBook Air M2',NULL,1200000.00,5,NULL,NULL,'2026-07-16 01:17:11',NULL),
(103,'iPhone 15 Pro Max',NULL,1500000.00,10,NULL,NULL,'2026-07-16 01:17:11',NULL),
(104,'Samsung Galaxy S24 Ultra',NULL,1400000.00,8,NULL,NULL,'2026-07-16 01:17:11',NULL),
(105,'Xiaomi Redmi Note 13 Pro',NULL,350000.00,25,NULL,NULL,'2026-07-16 01:17:11',NULL),
(106,'Tecno Camon 20 Pro',NULL,350000.00,20,NULL,NULL,'2026-07-16 01:17:11',NULL),
(107,'Routeur TP-Link Archer AX73',NULL,120000.00,15,NULL,NULL,'2026-07-16 01:17:11',NULL),
(108,'Switch Gigabit 24 ports',NULL,250000.00,8,NULL,NULL,'2026-07-16 01:17:11',NULL),
(109,'Souris Logitech M185',NULL,15000.00,30,NULL,NULL,'2026-07-16 01:17:11',NULL),
(110,'Clavier mécanique RGB',NULL,40000.00,12,NULL,NULL,'2026-07-16 01:17:11',NULL),
(111,'SSD NVMe 512GB',NULL,50000.00,18,NULL,NULL,'2026-07-16 01:17:11',NULL),
(112,'Disque dur externe 1TB',NULL,45000.00,12,NULL,NULL,'2026-07-16 01:17:11',NULL),
(113,'HP EliteBook 840 G6','Ordinateur portable professionnel',850000.00,15,NULL,NULL,'2026-07-16 01:24:08',NULL),
(114,'Dell Latitude 5420','Ordinateur portable robuste',950000.00,8,NULL,NULL,'2026-07-16 01:24:08',NULL),
(115,'Lenovo ThinkPad E14','Ordinateur portable polyvalent',750000.00,12,NULL,NULL,'2026-07-16 01:24:08',NULL),
(116,'MacBook Air M2','Ordinateur ultra-léger Apple',1200000.00,5,NULL,NULL,'2026-07-16 01:24:08',NULL),
(117,'Acer Aspire 5','Ordinateur portable grand public',650000.00,10,NULL,NULL,'2026-07-16 01:24:08',NULL),
(118,'iPhone 15 Pro Max','Smartphone haut de gamme Apple',1500000.00,10,NULL,NULL,'2026-07-16 01:24:08',NULL),
(119,'Samsung Galaxy S24 Ultra','Smartphone Android premium',1400000.00,8,NULL,NULL,'2026-07-16 01:24:08',NULL),
(120,'Xiaomi Redmi Note 13 Pro','Smartphone milieu de gamme',350000.00,25,NULL,NULL,'2026-07-16 01:24:08',NULL),
(121,'Tecno Camon 20 Pro','Smartphone photo',350000.00,20,NULL,NULL,'2026-07-16 01:24:08',NULL),
(122,'Infinix Zero 30','Smartphone gaming',300000.00,15,NULL,NULL,'2026-07-16 01:24:08',NULL),
(123,'Routeur TP-Link Archer AX73','Routeur WiFi 6',120000.00,15,NULL,NULL,'2026-07-16 01:24:08',NULL),
(124,'Switch Gigabit 24 ports','Switch réseau professionnel',250000.00,8,NULL,NULL,'2026-07-16 01:24:08',NULL),
(125,'Routeur WiFi 6 Xiaomi AX3000','Routeur WiFi 6 entrée de gamme',80000.00,20,NULL,NULL,'2026-07-16 01:24:08',NULL),
(126,'Modem 4G LTE Huawei','Modem 4G portable',65000.00,12,NULL,NULL,'2026-07-16 01:24:08',NULL),
(127,'Souris Logitech M185','Souris sans fil',15000.00,30,NULL,NULL,'2026-07-16 01:24:08',NULL),
(128,'Clavier mécanique RGB','Clavier gaming mécanique',40000.00,12,NULL,NULL,'2026-07-16 01:24:08',NULL),
(129,'Webcam HD 1080p','Webcam pour visioconférence',25000.00,10,NULL,NULL,'2026-07-16 01:24:08',NULL),
(130,'Casque Gaming HyperX','Casque gaming',55000.00,8,NULL,NULL,'2026-07-16 01:24:08',NULL),
(131,'SSD NVMe 512GB','SSD haute performance',50000.00,18,NULL,NULL,'2026-07-16 01:24:08',NULL),
(132,'Disque dur externe 1TB','Disque dur externe portable',45000.00,12,NULL,NULL,'2026-07-16 01:24:08',NULL),
(133,'Clé USB 64GB','Clé USB haute capacité',10000.00,40,NULL,NULL,'2026-07-16 01:24:08',NULL),
(134,'SSD NVMe 1TB','SSD haute capacité',85000.00,10,NULL,NULL,'2026-07-16 01:24:08',NULL),
(135,'Écran 24\" Dell','Moniteur professionnel',200000.00,8,NULL,NULL,'2026-07-16 01:24:08',NULL),
(136,'Imprimante laser HP','Imprimante laser monochrome',180000.00,5,NULL,NULL,'2026-07-16 01:24:08',NULL),
(137,'Onduleur 600VA','Protection électrique',60000.00,10,NULL,NULL,'2026-07-16 01:24:08',NULL),
(138,'HP EliteBook 840 G6','Ordinateur portable professionnel',850000.00,15,NULL,NULL,'2026-07-16 01:27:16',NULL),
(139,'Dell Latitude 5420','Ordinateur portable robuste',950000.00,8,NULL,NULL,'2026-07-16 01:27:16',NULL),
(140,'Lenovo ThinkPad E14','Ordinateur portable polyvalent',750000.00,12,NULL,NULL,'2026-07-16 01:27:16',NULL),
(141,'MacBook Air M2','Ordinateur ultra-léger Apple',1200000.00,5,NULL,NULL,'2026-07-16 01:27:16',NULL),
(142,'Acer Aspire 5','Ordinateur portable grand public',650000.00,10,NULL,NULL,'2026-07-16 01:27:16',NULL),
(143,'iPhone 15 Pro Max','Smartphone haut de gamme Apple',1500000.00,10,NULL,NULL,'2026-07-16 01:27:16',NULL),
(144,'Samsung Galaxy S24 Ultra','Smartphone Android premium',1400000.00,8,NULL,NULL,'2026-07-16 01:27:16',NULL),
(145,'Xiaomi Redmi Note 13 Pro','Smartphone milieu de gamme',350000.00,25,NULL,NULL,'2026-07-16 01:27:16',NULL),
(146,'Tecno Camon 20 Pro','Smartphone photo',350000.00,20,NULL,NULL,'2026-07-16 01:27:16',NULL),
(147,'Infinix Zero 30','Smartphone gaming',300000.00,15,NULL,NULL,'2026-07-16 01:27:16',NULL),
(148,'Routeur TP-Link Archer AX73','Routeur WiFi 6',120000.00,15,NULL,NULL,'2026-07-16 01:27:16',NULL),
(149,'Switch Gigabit 24 ports','Switch réseau professionnel',250000.00,8,NULL,NULL,'2026-07-16 01:27:16',NULL),
(150,'Routeur WiFi 6 Xiaomi AX3000','Routeur WiFi 6 entrée de gamme',80000.00,20,NULL,NULL,'2026-07-16 01:27:16',NULL),
(151,'Modem 4G LTE Huawei','Modem 4G portable',65000.00,12,NULL,NULL,'2026-07-16 01:27:16',NULL),
(152,'Souris Logitech M185','Souris sans fil',15000.00,30,NULL,NULL,'2026-07-16 01:27:16',NULL),
(153,'Clavier mécanique RGB','Clavier gaming mécanique',40000.00,12,NULL,NULL,'2026-07-16 01:27:16',NULL),
(154,'Webcam HD 1080p','Webcam pour visioconférence',25000.00,10,NULL,NULL,'2026-07-16 01:27:16',NULL),
(155,'Casque Gaming HyperX','Casque gaming',55000.00,8,NULL,NULL,'2026-07-16 01:27:16',NULL),
(156,'SSD NVMe 512GB','SSD haute performance',50000.00,18,NULL,NULL,'2026-07-16 01:27:16',NULL),
(157,'Disque dur externe 1TB','Disque dur externe portable',45000.00,12,NULL,NULL,'2026-07-16 01:27:16',NULL),
(158,'Clé USB 64GB','Clé USB haute capacité',10000.00,40,NULL,NULL,'2026-07-16 01:27:16',NULL),
(159,'SSD NVMe 1TB','SSD haute capacité',85000.00,10,NULL,NULL,'2026-07-16 01:27:16',NULL),
(160,'Écran 24\" Dell','Moniteur professionnel',200000.00,8,NULL,NULL,'2026-07-16 01:27:16',NULL),
(161,'Imprimante laser HP','Imprimante laser monochrome',180000.00,5,NULL,NULL,'2026-07-16 01:27:16',NULL),
(162,'Onduleur 600VA','Protection électrique',60000.00,10,NULL,NULL,'2026-07-16 01:27:16',NULL),
(163,'HP EliteBook 840 G6','Ordinateur portable professionnel',850000.00,15,NULL,NULL,'2026-07-16 01:29:04',NULL),
(164,'Dell Latitude 5420','Ordinateur portable robuste',950000.00,8,NULL,NULL,'2026-07-16 01:29:04',NULL),
(165,'Lenovo ThinkPad E14','Ordinateur portable polyvalent',750000.00,12,NULL,NULL,'2026-07-16 01:29:04',NULL),
(166,'MacBook Air M2','Ordinateur ultra-léger Apple',1200000.00,5,NULL,NULL,'2026-07-16 01:29:04',NULL),
(167,'Acer Aspire 5','Ordinateur portable grand public',650000.00,10,NULL,NULL,'2026-07-16 01:29:04',NULL),
(168,'iPhone 15 Pro Max','Smartphone haut de gamme Apple',1500000.00,10,NULL,NULL,'2026-07-16 01:29:04',NULL),
(169,'Samsung Galaxy S24 Ultra','Smartphone Android premium',1400000.00,8,NULL,NULL,'2026-07-16 01:29:04',NULL),
(170,'Xiaomi Redmi Note 13 Pro','Smartphone milieu de gamme',350000.00,25,NULL,NULL,'2026-07-16 01:29:04',NULL),
(171,'Tecno Camon 20 Pro','Smartphone photo',350000.00,20,NULL,NULL,'2026-07-16 01:29:04',NULL),
(172,'Infinix Zero 30','Smartphone gaming',300000.00,15,NULL,NULL,'2026-07-16 01:29:04',NULL),
(173,'Routeur TP-Link Archer AX73','Routeur WiFi 6',120000.00,15,NULL,NULL,'2026-07-16 01:29:04',NULL),
(174,'Switch Gigabit 24 ports','Switch réseau professionnel',250000.00,8,NULL,NULL,'2026-07-16 01:29:04',NULL),
(175,'Routeur WiFi 6 Xiaomi AX3000','Routeur WiFi 6 entrée de gamme',80000.00,20,NULL,NULL,'2026-07-16 01:29:04',NULL),
(176,'Modem 4G LTE Huawei','Modem 4G portable',65000.00,12,NULL,NULL,'2026-07-16 01:29:04',NULL),
(177,'Souris Logitech M185','Souris sans fil',15000.00,30,NULL,NULL,'2026-07-16 01:29:04',NULL),
(178,'Clavier mécanique RGB','Clavier gaming mécanique',40000.00,12,NULL,NULL,'2026-07-16 01:29:04',NULL),
(179,'Webcam HD 1080p','Webcam pour visioconférence',25000.00,10,NULL,NULL,'2026-07-16 01:29:04',NULL),
(180,'Casque Gaming HyperX','Casque gaming',55000.00,8,NULL,NULL,'2026-07-16 01:29:04',NULL),
(181,'SSD NVMe 512GB','SSD haute performance',50000.00,18,NULL,NULL,'2026-07-16 01:29:04',NULL),
(182,'Disque dur externe 1TB','Disque dur externe portable',45000.00,12,NULL,NULL,'2026-07-16 01:29:04',NULL),
(183,'Clé USB 64GB','Clé USB haute capacité',10000.00,40,NULL,NULL,'2026-07-16 01:29:04',NULL),
(184,'SSD NVMe 1TB','SSD haute capacité',85000.00,10,NULL,NULL,'2026-07-16 01:29:04',NULL),
(185,'Écran 24\" Dell','Moniteur professionnel',200000.00,8,NULL,NULL,'2026-07-16 01:29:04',NULL),
(186,'Imprimante laser HP','Imprimante laser monochrome',180000.00,5,NULL,NULL,'2026-07-16 01:29:04',NULL),
(187,'Onduleur 600VA','Protection électrique',60000.00,10,NULL,NULL,'2026-07-16 01:29:04',NULL),
(188,'HP EliteBook 840 G6','Ordinateur portable professionnel',850000.00,15,NULL,NULL,'2026-07-16 01:30:49',NULL),
(189,'Dell Latitude 5420','Ordinateur portable robuste',950000.00,8,NULL,NULL,'2026-07-16 01:30:49',NULL),
(190,'Lenovo ThinkPad E14','Ordinateur portable polyvalent',750000.00,12,NULL,NULL,'2026-07-16 01:30:49',NULL),
(191,'MacBook Air M2','Ordinateur ultra-léger Apple',1200000.00,5,NULL,NULL,'2026-07-16 01:30:49',NULL),
(192,'Acer Aspire 5','Ordinateur portable grand public',650000.00,10,NULL,NULL,'2026-07-16 01:30:49',NULL),
(193,'iPhone 15 Pro Max','Smartphone haut de gamme Apple',1500000.00,10,NULL,NULL,'2026-07-16 01:30:49',NULL),
(194,'Samsung Galaxy S24 Ultra','Smartphone Android premium',1400000.00,8,NULL,NULL,'2026-07-16 01:30:49',NULL),
(195,'Xiaomi Redmi Note 13 Pro','Smartphone milieu de gamme',350000.00,25,NULL,NULL,'2026-07-16 01:30:49',NULL),
(196,'Tecno Camon 20 Pro','Smartphone photo',350000.00,20,NULL,NULL,'2026-07-16 01:30:49',NULL),
(197,'Infinix Zero 30','Smartphone gaming',300000.00,15,NULL,NULL,'2026-07-16 01:30:49',NULL),
(198,'Routeur TP-Link Archer AX73','Routeur WiFi 6',120000.00,15,NULL,NULL,'2026-07-16 01:30:49',NULL),
(199,'Switch Gigabit 24 ports','Switch réseau professionnel',250000.00,8,NULL,NULL,'2026-07-16 01:30:49',NULL),
(200,'Routeur WiFi 6 Xiaomi AX3000','Routeur WiFi 6 entrée de gamme',80000.00,20,NULL,NULL,'2026-07-16 01:30:49',NULL),
(201,'Modem 4G LTE Huawei','Modem 4G portable',65000.00,12,NULL,NULL,'2026-07-16 01:30:49',NULL),
(202,'Souris Logitech M185','Souris sans fil',15000.00,30,NULL,NULL,'2026-07-16 01:30:49',NULL),
(203,'Clavier mécanique RGB','Clavier gaming mécanique',40000.00,12,NULL,NULL,'2026-07-16 01:30:49',NULL),
(204,'Webcam HD 1080p','Webcam pour visioconférence',25000.00,10,NULL,NULL,'2026-07-16 01:30:49',NULL),
(205,'Casque Gaming HyperX','Casque gaming',55000.00,8,NULL,NULL,'2026-07-16 01:30:49',NULL),
(206,'SSD NVMe 512GB','SSD haute performance',50000.00,18,NULL,NULL,'2026-07-16 01:30:49',NULL),
(207,'Disque dur externe 1TB','Disque dur externe portable',45000.00,12,NULL,NULL,'2026-07-16 01:30:49',NULL),
(208,'Clé USB 64GB','Clé USB haute capacité',10000.00,40,NULL,NULL,'2026-07-16 01:30:49',NULL),
(209,'SSD NVMe 1TB','SSD haute capacité',85000.00,10,NULL,NULL,'2026-07-16 01:30:49',NULL),
(210,'Écran 24\" Dell','Moniteur professionnel',200000.00,8,NULL,NULL,'2026-07-16 01:30:49',NULL),
(211,'Imprimante laser HP','Imprimante laser monochrome',180000.00,5,NULL,NULL,'2026-07-16 01:30:49',NULL),
(212,'Onduleur 600VA','Protection électrique',60000.00,10,NULL,NULL,'2026-07-16 01:30:49',NULL);
/*!40000 ALTER TABLE `produits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produits_images`
--

DROP TABLE IF EXISTS `produits_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `produits_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_produit` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `ordre` int(11) DEFAULT 0,
  `date_creation` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_produit` (`id_produit`),
  CONSTRAINT `produits_images_ibfk_1` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=373 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produits_images`
--

LOCK TABLES `produits_images` WRITE;
/*!40000 ALTER TABLE `produits_images` DISABLE KEYS */;
INSERT INTO `produits_images` VALUES
(10,1,'1.png',0,'2026-07-15 04:55:30'),
(11,2,'2.png',0,'2026-07-15 04:55:30'),
(12,3,'3.png',0,'2026-07-15 04:55:30'),
(13,4,'4.png',0,'2026-07-15 04:55:30'),
(14,5,'5.png',0,'2026-07-15 04:55:30'),
(15,6,'6.png',0,'2026-07-15 04:55:30'),
(16,7,'7.png',0,'2026-07-15 04:55:30'),
(17,8,'8.png',0,'2026-07-15 04:55:30'),
(18,9,'9.png',0,'2026-07-15 04:55:30'),
(19,10,'10.png',0,'2026-07-15 04:55:30'),
(20,11,'11.png',0,'2026-07-15 04:55:30'),
(21,12,'12.png',0,'2026-07-15 04:55:30'),
(22,13,'13.png',0,'2026-07-15 04:55:30'),
(23,14,'14.png',0,'2026-07-15 04:55:30'),
(24,15,'15.png',0,'2026-07-15 04:55:30'),
(25,16,'16.png',0,'2026-07-15 04:55:30'),
(26,17,'17.png',0,'2026-07-15 04:55:30'),
(27,18,'18.png',0,'2026-07-15 04:55:30'),
(28,19,'19.png',0,'2026-07-15 04:55:30'),
(29,20,'20.png',0,'2026-07-15 04:55:30'),
(30,21,'21.png',0,'2026-07-15 04:55:30'),
(31,22,'22.png',0,'2026-07-15 04:55:30'),
(32,23,'23.png',0,'2026-07-15 04:55:30'),
(33,24,'24.png',0,'2026-07-15 04:55:30'),
(34,25,'25.png',0,'2026-07-15 04:55:31'),
(35,26,'26.png',0,'2026-07-15 04:55:31'),
(36,27,'27.png',0,'2026-07-15 04:55:31'),
(37,28,'28.png',0,'2026-07-15 04:55:31'),
(38,29,'29.png',0,'2026-07-15 04:55:31'),
(39,30,'30.png',0,'2026-07-15 04:55:31'),
(40,31,'31.png',0,'2026-07-15 04:55:31'),
(41,32,'32.png',0,'2026-07-15 04:55:31'),
(42,33,'33.png',0,'2026-07-15 04:55:31'),
(43,34,'34.png',0,'2026-07-15 04:55:31'),
(44,35,'35.png',0,'2026-07-15 04:55:31'),
(45,36,'36.png',0,'2026-07-15 04:55:31'),
(46,37,'37.png',0,'2026-07-15 04:55:31'),
(47,38,'38.png',0,'2026-07-15 04:55:31'),
(48,39,'39.png',0,'2026-07-15 04:55:31'),
(49,40,'40.png',0,'2026-07-15 04:55:31'),
(50,41,'41.png',0,'2026-07-15 04:55:31'),
(51,42,'42.png',0,'2026-07-15 04:55:31'),
(52,43,'43.png',0,'2026-07-15 04:55:31'),
(53,44,'44.png',0,'2026-07-15 04:55:31'),
(54,45,'45.png',0,'2026-07-15 04:55:31'),
(55,46,'46.png',0,'2026-07-15 04:55:31'),
(56,47,'47.png',0,'2026-07-15 04:55:31'),
(57,48,'48.png',0,'2026-07-15 04:55:31'),
(58,49,'49.png',0,'2026-07-15 04:55:31'),
(59,50,'50.png',0,'2026-07-15 04:55:31'),
(60,51,'51.png',0,'2026-07-15 04:55:31'),
(61,52,'52.png',0,'2026-07-15 04:55:31'),
(62,53,'53.png',0,'2026-07-15 04:55:31'),
(63,54,'54.png',0,'2026-07-15 04:55:31'),
(64,55,'55.png',0,'2026-07-15 04:55:31'),
(65,56,'56.png',0,'2026-07-15 04:55:32'),
(66,57,'57.png',0,'2026-07-15 04:55:32'),
(67,58,'58.png',0,'2026-07-15 04:55:32'),
(68,59,'59.png',0,'2026-07-15 04:55:32'),
(69,60,'60.png',0,'2026-07-15 04:55:32'),
(70,61,'61.png',0,'2026-07-15 04:55:32'),
(71,62,'62.png',0,'2026-07-15 04:55:32'),
(72,63,'63.png',0,'2026-07-15 04:55:32'),
(73,64,'64.png',0,'2026-07-15 04:55:32'),
(74,65,'65.png',0,'2026-07-15 04:55:32'),
(75,66,'66.png',0,'2026-07-15 04:55:32'),
(76,67,'67.png',0,'2026-07-15 04:55:32'),
(77,68,'68.png',0,'2026-07-15 04:55:32'),
(78,69,'69.png',0,'2026-07-15 04:55:32'),
(79,70,'70.png',0,'2026-07-15 04:55:32'),
(80,71,'71.png',0,'2026-07-15 04:55:32'),
(81,72,'72.png',0,'2026-07-15 04:55:32'),
(82,73,'73.png',0,'2026-07-15 04:55:32'),
(83,74,'74.png',0,'2026-07-15 04:55:32'),
(84,75,'75.png',0,'2026-07-15 04:55:32'),
(85,76,'76.png',0,'2026-07-15 04:55:32'),
(86,77,'77.png',0,'2026-07-15 04:55:32'),
(87,78,'78.png',0,'2026-07-15 04:55:32'),
(88,79,'79.png',0,'2026-07-15 04:55:32'),
(89,80,'80.png',0,'2026-07-15 04:55:32'),
(90,81,'81.png',0,'2026-07-15 04:55:32'),
(91,82,'82.png',0,'2026-07-15 04:55:32'),
(92,83,'83.png',0,'2026-07-15 04:55:32'),
(93,84,'84.png',0,'2026-07-15 04:55:32'),
(94,85,'85.png',0,'2026-07-15 04:55:32'),
(95,86,'86.png',0,'2026-07-15 04:55:33'),
(97,88,'88.png',0,'2026-07-15 04:55:33'),
(98,89,'89.png',0,'2026-07-15 04:55:33'),
(99,90,'90.png',0,'2026-07-15 04:55:33'),
(101,92,'92.png',0,'2026-07-15 04:55:33'),
(102,93,'93.png',0,'2026-07-15 04:55:33'),
(103,94,'94.png',0,'2026-07-15 04:55:33'),
(104,95,'95.png',0,'2026-07-15 04:55:33'),
(105,96,'96.png',0,'2026-07-15 04:55:33'),
(106,97,'97.png',0,'2026-07-15 04:55:33'),
(108,1,'gal_1_6a57a8c858ccb.jpeg',0,'2026-07-15 15:35:36'),
(109,1,'gal_1_6a57a8c859727.jpeg',0,'2026-07-15 15:35:36'),
(110,2,'gal_2_6a57a965ccbc8.jpeg',0,'2026-07-15 15:38:13'),
(111,2,'gal_2_6a57a965cd507.jpeg',0,'2026-07-15 15:38:13'),
(112,3,'gal_3_6a57a9e592c9b.jpeg',0,'2026-07-15 15:40:21'),
(113,3,'gal_3_6a57a9e593662.jpeg',0,'2026-07-15 15:40:21'),
(114,4,'gal_4_6a57aa372e426.jpeg',0,'2026-07-15 15:41:43'),
(115,4,'gal_4_6a57aa372f029.jpeg',0,'2026-07-15 15:41:43'),
(116,5,'gal_5_6a57aa75cdf35.jpeg',0,'2026-07-15 15:42:45'),
(117,5,'gal_5_6a57aa75ce894.jpeg',0,'2026-07-15 15:42:45'),
(118,6,'gal_6_6a57aaa89c908.jpeg',0,'2026-07-15 15:43:36'),
(119,6,'gal_6_6a57aaa89d257.jpeg',0,'2026-07-15 15:43:36'),
(120,7,'gal_7_6a57aaf323b1e.jpeg',0,'2026-07-15 15:44:51'),
(121,7,'gal_7_6a57aaf324b63.jpeg',0,'2026-07-15 15:44:51'),
(122,7,'gal_7_6a57aaf3252fb.jpeg',0,'2026-07-15 15:44:51'),
(123,43,'gal_43_6a57ab332d837.jpeg',0,'2026-07-15 15:45:55'),
(124,43,'gal_43_6a57ab332e17a.jpeg',0,'2026-07-15 15:45:55'),
(125,31,'gal_31_6a57abc469636.webp',0,'2026-07-15 15:48:20'),
(126,31,'gal_31_6a57abc46a041.jpeg',0,'2026-07-15 15:48:20'),
(127,32,'gal_32_6a57ac24973dc.webp',0,'2026-07-15 15:49:56'),
(128,32,'gal_32_6a57ac249873e.jpeg',0,'2026-07-15 15:49:56'),
(129,8,'gal_8_6a57ac7519bef.jpeg',0,'2026-07-15 15:51:17'),
(130,8,'gal_8_6a57ac751a4a4.jpeg',0,'2026-07-15 15:51:17'),
(131,9,'gal_9_6a57aca9a3d3b.jpeg',0,'2026-07-15 15:52:09'),
(132,9,'gal_9_6a57aca9a471c.jpeg',0,'2026-07-15 15:52:09'),
(133,9,'gal_9_6a57aca9a50d9.jpeg',0,'2026-07-15 15:52:09'),
(134,10,'gal_10_6a57ace2cce9d.jpeg',0,'2026-07-15 15:53:06'),
(135,10,'gal_10_6a57ace2cda94.jpeg',0,'2026-07-15 15:53:06'),
(136,10,'gal_10_6a57ace2ce6ce.jpeg',0,'2026-07-15 15:53:06'),
(137,11,'gal_11_6a57ad19066a9.jpeg',0,'2026-07-15 15:54:01'),
(138,11,'gal_11_6a57ad190710b.jpeg',0,'2026-07-15 15:54:01'),
(139,44,'gal_44_6a57ad9a0a720.webp',0,'2026-07-15 15:56:10'),
(140,44,'gal_44_6a57ad9a0b209.jpeg',0,'2026-07-15 15:56:10'),
(141,38,'gal_38_6a57ade8cd52a.jpeg',0,'2026-07-15 15:57:28'),
(142,38,'gal_38_6a57ade8cde9d.jpeg',0,'2026-07-15 15:57:28'),
(143,41,'gal_41_6a57ae2233fda.webp',0,'2026-07-15 15:58:26'),
(144,41,'gal_41_6a57ae2234998.webp',0,'2026-07-15 15:58:26'),
(145,39,'gal_39_6a57ae58f2d4a.jpeg',0,'2026-07-15 15:59:21'),
(146,39,'gal_39_6a57ae58f389b.jpeg',0,'2026-07-15 15:59:21'),
(147,42,'gal_42_6a57aeac2b9e6.jpeg',0,'2026-07-15 16:00:44'),
(148,42,'gal_42_6a57aeac2c4dc.jpeg',0,'2026-07-15 16:00:44'),
(149,42,'gal_42_6a57aeac2ce8b.webp',0,'2026-07-15 16:00:44'),
(150,47,'gal_47_6a57aee6c4cd6.webp',0,'2026-07-15 16:01:42'),
(151,47,'gal_47_6a57aee6c58b8.webp',0,'2026-07-15 16:01:42'),
(152,47,'gal_47_6a57aee6c6211.jpeg',0,'2026-07-15 16:01:42'),
(153,37,'gal_37_6a57af14e7344.jpeg',0,'2026-07-15 16:02:28'),
(154,37,'gal_37_6a57af14e7f14.jpeg',0,'2026-07-15 16:02:28'),
(155,37,'gal_37_6a57af14e8704.jpeg',0,'2026-07-15 16:02:28'),
(156,36,'gal_36_6a57af48b7e9e.jpeg',0,'2026-07-15 16:03:20'),
(157,36,'gal_36_6a57af48b8850.jpeg',0,'2026-07-15 16:03:20'),
(158,36,'gal_36_6a57af48b90fe.jpeg',0,'2026-07-15 16:03:20'),
(159,48,'gal_48_6a57afc4382fa.jpeg',0,'2026-07-15 16:05:24'),
(160,48,'gal_48_6a57afc438ce4.jpeg',0,'2026-07-15 16:05:24'),
(161,40,'gal_40_6a57b027207b3.jpeg',0,'2026-07-15 16:07:03'),
(162,40,'gal_40_6a57b0272136e.jpeg',0,'2026-07-15 16:07:03'),
(163,40,'gal_40_6a57b02721c88.jpeg',0,'2026-07-15 16:07:03'),
(164,62,'gal_62_6a57b0ad0067b.jpeg',0,'2026-07-15 16:09:17'),
(165,62,'gal_62_6a57b0ad01212.jpeg',0,'2026-07-15 16:09:17'),
(166,62,'gal_62_6a57b0ad01c2d.jpeg',0,'2026-07-15 16:09:17'),
(167,63,'gal_63_6a57b0d5144b4.jpeg',0,'2026-07-15 16:09:57'),
(168,63,'gal_63_6a57b0d515100.jpeg',0,'2026-07-15 16:09:57'),
(169,64,'gal_64_6a57b12334679.jpeg',0,'2026-07-15 16:11:15'),
(170,64,'gal_64_6a57b1233512f.jpeg',0,'2026-07-15 16:11:15'),
(171,64,'gal_64_6a57b12335d93.jpeg',0,'2026-07-15 16:11:15'),
(172,65,'gal_65_6a57b1f455dec.jpeg',0,'2026-07-15 16:14:44'),
(173,66,'gal_66_6a57b22dcd7bc.jpeg',0,'2026-07-15 16:15:41'),
(174,66,'gal_66_6a57b22dce22d.jpeg',0,'2026-07-15 16:15:41'),
(175,82,'gal_82_6a57b27fafe4e.jpeg',0,'2026-07-15 16:17:03'),
(176,82,'gal_82_6a57b27fb070a.webp',0,'2026-07-15 16:17:03'),
(177,82,'gal_82_6a57b27fb0c9d.webp',0,'2026-07-15 16:17:03'),
(178,45,'gal_45_6a57b2c977968.jpeg',0,'2026-07-15 16:18:17'),
(179,45,'gal_45_6a57b2c9784ec.jpeg',0,'2026-07-15 16:18:17'),
(180,45,'gal_45_6a57b2c978f79.jpeg',0,'2026-07-15 16:18:17'),
(181,43,'gal_43_6a57b31796c48.jpeg',0,'2026-07-15 16:19:35'),
(182,81,'gal_81_6a57b39762467.jpeg',0,'2026-07-15 16:21:43'),
(183,81,'gal_81_6a57b39762d52.jpeg',0,'2026-07-15 16:21:43'),
(184,81,'gal_81_6a57b3976375d.jpeg',0,'2026-07-15 16:21:43'),
(185,81,'gal_81_6a57b397640dc.jpeg',0,'2026-07-15 16:21:43'),
(186,12,'gal_12_6a57b3d24f48e.jpeg',0,'2026-07-15 16:22:42'),
(187,12,'gal_12_6a57b3d251f57.jpeg',0,'2026-07-15 16:22:42'),
(188,12,'gal_12_6a57b3d2527de.jpeg',0,'2026-07-15 16:22:42'),
(189,13,'gal_13_6a57b3f4e4646.jpeg',0,'2026-07-15 16:23:16'),
(190,13,'gal_13_6a57b3f4e4fac.jpeg',0,'2026-07-15 16:23:16'),
(191,13,'gal_13_6a57b3f4e59e1.jpeg',0,'2026-07-15 16:23:16'),
(192,14,'gal_14_6a57b43e68612.jpeg',0,'2026-07-15 16:24:30'),
(193,14,'gal_14_6a57b43e68e9d.jpeg',0,'2026-07-15 16:24:30'),
(194,14,'gal_14_6a57b43e69688.jpeg',0,'2026-07-15 16:24:30'),
(195,15,'gal_15_6a57b47e98136.jpeg',0,'2026-07-15 16:25:34'),
(196,15,'gal_15_6a57b47e98c70.jpeg',0,'2026-07-15 16:25:34'),
(197,15,'gal_15_6a57b47e995d7.jpeg',0,'2026-07-15 16:25:34'),
(198,24,'gal_24_6a57b4df8b22b.webp',0,'2026-07-15 16:27:11'),
(199,24,'gal_24_6a57b4df8c972.webp',0,'2026-07-15 16:27:11'),
(200,24,'gal_24_6a57b4df8d4ad.jpeg',0,'2026-07-15 16:27:11'),
(201,25,'gal_25_6a57b51d9b5df.jpeg',0,'2026-07-15 16:28:13'),
(202,25,'gal_25_6a57b51d9bbb2.jpeg',0,'2026-07-15 16:28:13'),
(203,16,'gal_16_6a57b55e39d25.jpeg',0,'2026-07-15 16:29:18'),
(204,16,'gal_16_6a57b55e3a543.jpeg',0,'2026-07-15 16:29:18'),
(205,16,'gal_16_6a57b55e3aca6.jpeg',0,'2026-07-15 16:29:18'),
(206,17,'gal_17_6a57b594d6657.jpeg',0,'2026-07-15 16:30:12'),
(207,17,'gal_17_6a57b594d6f8b.jpeg',0,'2026-07-15 16:30:12'),
(208,17,'gal_17_6a57b594d77e6.jpeg',0,'2026-07-15 16:30:12'),
(209,18,'gal_18_6a57b5ca3ea98.jpeg',0,'2026-07-15 16:31:06'),
(210,18,'gal_18_6a57b5ca3f505.jpeg',0,'2026-07-15 16:31:06'),
(211,18,'gal_18_6a57b5ca3fb77.jpeg',0,'2026-07-15 16:31:06'),
(212,49,'gal_49_6a57b60bc15e8.jpeg',0,'2026-07-15 16:32:11'),
(213,49,'gal_49_6a57b60bc2009.jpeg',0,'2026-07-15 16:32:11'),
(214,19,'gal_19_6a57b64177517.jpeg',0,'2026-07-15 16:33:05'),
(215,19,'gal_19_6a57b6417819c.jpeg',0,'2026-07-15 16:33:05'),
(216,19,'gal_19_6a57b64178da0.jpeg',0,'2026-07-15 16:33:05'),
(217,19,'gal_19_6a57b64179966.jpeg',0,'2026-07-15 16:33:05'),
(218,20,'gal_20_6a57b67151897.jpeg',0,'2026-07-15 16:33:53'),
(219,20,'gal_20_6a57b67152108.jpeg',0,'2026-07-15 16:33:53'),
(220,20,'gal_20_6a57b67152beb.jpeg',0,'2026-07-15 16:33:53'),
(221,46,'gal_46_6a57b6c2d8c6a.jpeg',0,'2026-07-15 16:35:14'),
(222,46,'gal_46_6a57b6c2d95f9.jpeg',0,'2026-07-15 16:35:14'),
(223,34,'gal_34_6a57b73a3e599.jpeg',0,'2026-07-15 16:37:14'),
(224,34,'gal_34_6a57b73a3ef2b.jpeg',0,'2026-07-15 16:37:14'),
(225,34,'gal_34_6a57b73a3f822.jpeg',0,'2026-07-15 16:37:14'),
(226,21,'gal_21_6a57b7698de8d.jpeg',0,'2026-07-15 16:38:01'),
(227,21,'gal_21_6a57b7698e6b4.jpeg',0,'2026-07-15 16:38:01'),
(228,21,'gal_21_6a57b7698ecfc.jpeg',0,'2026-07-15 16:38:01'),
(229,22,'gal_22_6a57b79d51a73.jpeg',0,'2026-07-15 16:38:53'),
(230,22,'gal_22_6a57b79d522b1.jpeg',0,'2026-07-15 16:38:53'),
(231,22,'gal_22_6a57b79d52b99.jpeg',0,'2026-07-15 16:38:53'),
(232,22,'gal_22_6a57b79d5360e.jpeg',0,'2026-07-15 16:38:53'),
(233,23,'gal_23_6a57b7c601360.jpeg',0,'2026-07-15 16:39:34'),
(234,23,'gal_23_6a57b7c601b8f.jpeg',0,'2026-07-15 16:39:34'),
(235,23,'gal_23_6a57b7c602436.jpeg',0,'2026-07-15 16:39:34'),
(236,26,'gal_26_6a57b7fb6313d.jpeg',0,'2026-07-15 16:40:27'),
(237,26,'gal_26_6a57b7fb63917.jpeg',0,'2026-07-15 16:40:27'),
(238,26,'gal_26_6a57b7fb6432d.webp',0,'2026-07-15 16:40:27'),
(239,27,'gal_27_6a57b8276ed78.jpeg',0,'2026-07-15 16:41:11'),
(240,27,'gal_27_6a57b8276f673.jpeg',0,'2026-07-15 16:41:11'),
(241,27,'gal_27_6a57b82770060.jpeg',0,'2026-07-15 16:41:11'),
(242,28,'gal_28_6a57b85abe76f.jpeg',0,'2026-07-15 16:42:02'),
(243,28,'gal_28_6a57b85abf0b3.jpeg',0,'2026-07-15 16:42:02'),
(244,28,'gal_28_6a57b85abf967.jpeg',0,'2026-07-15 16:42:02'),
(245,29,'gal_29_6a57b8908e710.webp',0,'2026-07-15 16:42:56'),
(246,29,'gal_29_6a57b8908f22d.webp',0,'2026-07-15 16:42:56'),
(247,29,'gal_29_6a57b8908fb5b.jpeg',0,'2026-07-15 16:42:56'),
(248,30,'gal_30_6a57b8c7623cf.jpeg',0,'2026-07-15 16:43:51'),
(249,30,'gal_30_6a57b8c762ebd.jpeg',0,'2026-07-15 16:43:51'),
(250,30,'gal_30_6a57b8c763b0f.webp',0,'2026-07-15 16:43:51'),
(251,33,'gal_33_6a57b8f7b128b.jpeg',0,'2026-07-15 16:44:39'),
(252,33,'gal_33_6a57b8f7b1b1a.jpeg',0,'2026-07-15 16:44:39'),
(253,33,'gal_33_6a57b8f7b2359.jpeg',0,'2026-07-15 16:44:39'),
(254,33,'gal_33_6a57b8f7b2d6e.webp',0,'2026-07-15 16:44:39'),
(255,33,'gal_33_6a57b8f7b347e.webp',0,'2026-07-15 16:44:39'),
(256,50,'gal_50_6a57b9216279f.jpeg',0,'2026-07-15 16:45:21'),
(257,50,'gal_50_6a57b921633b9.jpeg',0,'2026-07-15 16:45:21'),
(258,50,'gal_50_6a57b92163d51.jpeg',0,'2026-07-15 16:45:21'),
(259,35,'gal_35_6a57b94ed5fc7.jpeg',0,'2026-07-15 16:46:06'),
(260,35,'gal_35_6a57b94ed6875.jpeg',0,'2026-07-15 16:46:06'),
(261,35,'gal_35_6a57b94ed6fa3.jpeg',0,'2026-07-15 16:46:06'),
(262,69,'gal_69_6a57d8f317c19.jpeg',0,'2026-07-15 19:01:07'),
(263,69,'gal_69_6a57d8f3181ce.jpeg',0,'2026-07-15 19:01:07'),
(264,69,'gal_69_6a57d8f31881f.jpeg',0,'2026-07-15 19:01:07'),
(265,69,'gal_69_6a57d8f318ca4.jpeg',0,'2026-07-15 19:01:07'),
(266,88,'gal_88_6a57d972c5d88.jpeg',0,'2026-07-15 19:03:14'),
(267,88,'gal_88_6a57d972c665a.jpeg',0,'2026-07-15 19:03:14'),
(268,88,'gal_88_6a57d972c700e.jpeg',0,'2026-07-15 19:03:14'),
(269,88,'gal_88_6a57d972c753a.jpeg',0,'2026-07-15 19:03:14'),
(270,67,'gal_67_6a57d9e9d0690.jpeg',0,'2026-07-15 19:05:13'),
(271,67,'gal_67_6a57d9e9d0dfe.jpeg',0,'2026-07-15 19:05:13'),
(272,67,'gal_67_6a57d9e9d12f4.jpeg',0,'2026-07-15 19:05:13'),
(273,67,'gal_67_6a57d9e9d1965.jpeg',0,'2026-07-15 19:05:13'),
(274,71,'gal_71_6a57da57c5422.jpeg',0,'2026-07-15 19:07:03'),
(275,71,'gal_71_6a57da57c5c0f.jpeg',0,'2026-07-15 19:07:03'),
(276,71,'gal_71_6a57da57c6431.jpeg',0,'2026-07-15 19:07:03'),
(277,68,'gal_68_6a57da7f1c6ae.jpeg',0,'2026-07-15 19:07:43'),
(278,68,'gal_68_6a57da7f1d0ee.jpeg',0,'2026-07-15 19:07:43'),
(279,68,'gal_68_6a57da7f1db28.jpeg',0,'2026-07-15 19:07:43'),
(280,95,'gal_95_6a57daee34085.jpeg',0,'2026-07-15 19:09:34'),
(281,95,'gal_95_6a57daee345ea.jpeg',0,'2026-07-15 19:09:34'),
(282,95,'gal_95_6a57daee34b91.jpeg',0,'2026-07-15 19:09:34'),
(283,90,'gal_90_6a57db19ed2d5.jpeg',0,'2026-07-15 19:10:17'),
(284,90,'gal_90_6a57db19edb50.jpeg',0,'2026-07-15 19:10:18'),
(285,90,'gal_90_6a57db19ee2b1.jpeg',0,'2026-07-15 19:10:18'),
(286,51,'gal_51_6a57db3d15631.jpeg',0,'2026-07-15 19:10:53'),
(287,51,'gal_51_6a57db3d15d9e.jpeg',0,'2026-07-15 19:10:53'),
(288,51,'gal_51_6a57db3d1647c.jpeg',0,'2026-07-15 19:10:53'),
(289,52,'gal_52_6a57db6c1e23c.jpeg',0,'2026-07-15 19:11:40'),
(290,52,'gal_52_6a57db6c1e9dc.jpeg',0,'2026-07-15 19:11:40'),
(291,53,'gal_53_6a57dba3c8489.jpeg',0,'2026-07-15 19:12:35'),
(292,53,'gal_53_6a57dba3c8f13.jpeg',0,'2026-07-15 19:12:35'),
(293,54,'gal_54_6a57dbcdb52a2.jpeg',0,'2026-07-15 19:13:17'),
(294,54,'gal_54_6a57dbcdb5c03.jpeg',0,'2026-07-15 19:13:17'),
(295,54,'gal_54_6a57dbcdb6454.jpeg',0,'2026-07-15 19:13:17'),
(296,55,'gal_55_6a57dbfc878fe.jpeg',0,'2026-07-15 19:14:04'),
(297,55,'gal_55_6a57dbfc88097.jpeg',0,'2026-07-15 19:14:04'),
(298,56,'gal_56_6a57dc17a9b81.jpeg',0,'2026-07-15 19:14:31'),
(299,56,'gal_56_6a57dc17aa42f.jpeg',0,'2026-07-15 19:14:31'),
(300,57,'gal_57_6a57dc3a65261.jpeg',0,'2026-07-15 19:15:06'),
(301,57,'gal_57_6a57dc3a65c11.jpeg',0,'2026-07-15 19:15:06'),
(302,57,'gal_57_6a57dc3a6838a.jpeg',0,'2026-07-15 19:15:06'),
(303,58,'gal_58_6a57dc59f1b51.jpeg',0,'2026-07-15 19:15:37'),
(304,58,'gal_58_6a57dc59f2353.jpeg',0,'2026-07-15 19:15:37'),
(305,59,'gal_59_6a57dc7c170a6.jpeg',0,'2026-07-15 19:16:12'),
(306,59,'gal_59_6a57dc7c178d5.jpeg',0,'2026-07-15 19:16:12'),
(307,59,'gal_59_6a57dc7c1802c.jpeg',0,'2026-07-15 19:16:12'),
(308,60,'gal_60_6a57dca04c766.jpeg',0,'2026-07-15 19:16:48'),
(309,60,'gal_60_6a57dca04cf5f.jpeg',0,'2026-07-15 19:16:48'),
(310,60,'gal_60_6a57dca04d968.jpeg',0,'2026-07-15 19:16:48'),
(311,72,'gal_72_6a57dda8650be.jpeg',0,'2026-07-15 19:21:12'),
(312,72,'gal_72_6a57dda86576f.jpeg',0,'2026-07-15 19:21:12'),
(313,72,'gal_72_6a57dda865f47.jpeg',0,'2026-07-15 19:21:12'),
(314,73,'gal_73_6a57ddcec1d7b.jpeg',0,'2026-07-15 19:21:50'),
(315,73,'gal_73_6a57ddcec26b7.jpeg',0,'2026-07-15 19:21:50'),
(316,74,'gal_74_6a57dded7b2da.jpeg',0,'2026-07-15 19:22:21'),
(317,74,'gal_74_6a57dded7bc95.jpeg',0,'2026-07-15 19:22:21'),
(318,74,'gal_74_6a57dded7c518.jpeg',0,'2026-07-15 19:22:21'),
(319,61,'gal_61_6a57de0e218c8.jpeg',0,'2026-07-15 19:22:54'),
(320,61,'gal_61_6a57de0e22025.jpeg',0,'2026-07-15 19:22:54'),
(321,61,'gal_61_6a57de0e229b5.jpeg',0,'2026-07-15 19:22:54'),
(322,76,'gal_76_6a57de4c292cc.jpeg',0,'2026-07-15 19:23:56'),
(323,76,'gal_76_6a57de4c29ac0.jpeg',0,'2026-07-15 19:23:56'),
(324,76,'gal_76_6a57de4c2a1c4.jpeg',0,'2026-07-15 19:23:56'),
(325,77,'gal_77_6a57df0b5f74c.jpeg',0,'2026-07-15 19:27:07'),
(326,77,'gal_77_6a57df0b5fda0.jpeg',0,'2026-07-15 19:27:07'),
(327,77,'gal_77_6a57df0b60761.jpeg',0,'2026-07-15 19:27:07'),
(328,70,'gal_70_6a57df33e5059.jpeg',0,'2026-07-15 19:27:47'),
(329,70,'gal_70_6a57df33e5712.jpeg',0,'2026-07-15 19:27:47'),
(330,78,'gal_78_6a57df9f33a58.jpeg',0,'2026-07-15 19:29:35'),
(331,78,'gal_78_6a57df9f3412d.jpeg',0,'2026-07-15 19:29:35'),
(332,78,'gal_78_6a57df9f3498a.jpeg',0,'2026-07-15 19:29:35'),
(333,80,'gal_80_6a57e022b7ff4.jpeg',0,'2026-07-15 19:31:46'),
(334,80,'gal_80_6a57e022b8773.jpeg',0,'2026-07-15 19:31:46'),
(335,80,'gal_80_6a57e022b8fec.jpeg',0,'2026-07-15 19:31:46'),
(336,75,'gal_75_6a57e0447d5fd.jpeg',0,'2026-07-15 19:32:20'),
(337,75,'gal_75_6a57e0447df3b.jpeg',0,'2026-07-15 19:32:20'),
(338,79,'gal_79_6a57e06893739.jpeg',0,'2026-07-15 19:32:56'),
(339,79,'gal_79_6a57e0689404a.jpeg',0,'2026-07-15 19:32:56'),
(340,79,'gal_79_6a57e06894915.jpeg',0,'2026-07-15 19:32:56'),
(341,79,'gal_79_6a57e068950bd.jpeg',0,'2026-07-15 19:32:56'),
(342,84,'gal_84_6a57e0d50feb3.webp',0,'2026-07-15 19:34:45'),
(343,84,'gal_84_6a57e0d51063c.jpeg',0,'2026-07-15 19:34:45'),
(344,84,'gal_84_6a57e0d510ddc.jpeg',0,'2026-07-15 19:34:45'),
(345,84,'gal_84_6a57e0d51140a.jpeg',0,'2026-07-15 19:34:45'),
(346,85,'gal_85_6a57e19fd2e3f.jpeg',0,'2026-07-15 19:38:07'),
(347,85,'gal_85_6a57e19fd376b.jpeg',0,'2026-07-15 19:38:07'),
(348,85,'gal_85_6a57e19fd3ee8.jpeg',0,'2026-07-15 19:38:07'),
(349,86,'gal_86_6a57e1be33918.jpeg',0,'2026-07-15 19:38:38'),
(350,86,'gal_86_6a57e1be342c7.jpeg',0,'2026-07-15 19:38:38'),
(351,86,'gal_86_6a57e1be34e72.jpeg',0,'2026-07-15 19:38:38'),
(352,96,'gal_96_6a57e1fdb2e05.webp',0,'2026-07-15 19:39:41'),
(353,96,'gal_96_6a57e1fdb3597.webp',0,'2026-07-15 19:39:41'),
(354,96,'gal_96_6a57e1fdb3e17.jpeg',0,'2026-07-15 19:39:41'),
(355,83,'gal_83_6a57e21ea6f99.jpeg',0,'2026-07-15 19:40:14'),
(356,83,'gal_83_6a57e21ea770c.jpeg',0,'2026-07-15 19:40:14'),
(357,83,'gal_83_6a57e21ea8110.jpeg',0,'2026-07-15 19:40:14'),
(358,89,'gal_89_6a57e2731e97a.jpeg',0,'2026-07-15 19:41:39'),
(359,89,'gal_89_6a57e2731f435.jpeg',0,'2026-07-15 19:41:39'),
(360,89,'gal_89_6a57e2731fe29.jpeg',0,'2026-07-15 19:41:39'),
(361,92,'gal_92_6a57e2921968a.jpeg',0,'2026-07-15 19:42:10'),
(362,92,'gal_92_6a57e29219ee9.jpeg',0,'2026-07-15 19:42:10'),
(363,93,'gal_93_6a57e319cebe5.jpeg',0,'2026-07-15 19:44:25'),
(364,93,'gal_93_6a57e319cf514.jpeg',0,'2026-07-15 19:44:25'),
(365,93,'gal_93_6a57e319cfc05.png',0,'2026-07-15 19:44:25'),
(366,94,'gal_94_6a57e35cdc225.jpeg',0,'2026-07-15 19:45:32'),
(367,94,'gal_94_6a57e35cdc8d7.jpeg',0,'2026-07-15 19:45:32'),
(368,94,'gal_94_6a57e35cdce29.jpeg',0,'2026-07-15 19:45:32'),
(369,97,'gal_97_6a57e39f081de.jpeg',0,'2026-07-15 19:46:39'),
(370,97,'gal_97_6a57e39f089ef.webp',0,'2026-07-15 19:46:39'),
(371,97,'gal_97_6a57e39f093b1.webp',0,'2026-07-15 19:46:39'),
(372,97,'gal_97_6a57e39f09b4c.jpeg',0,'2026-07-15 19:46:39');
/*!40000 ALTER TABLE `produits_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('admin','gestionnaire') NOT NULL DEFAULT 'gestionnaire',
  `date_creation` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `utilisateurs`
--

LOCK TABLES `utilisateurs` WRITE;
/*!40000 ALTER TABLE `utilisateurs` DISABLE KEYS */;
INSERT INTO `utilisateurs` VALUES
(1,'Administrateur','admin@omega.com','$2b$10$2k7LBRTBsSNweAKQQqRag.LAJ4XWQkhKGlYX6pFqVYJ9OYyO4rTfO','admin','2026-07-13 13:24:56');
/*!40000 ALTER TABLE `utilisateurs` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-07-16  1:43:26
