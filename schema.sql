-- =========================================================
-- OMEGA INFORMATIQUE CONSULTING - Plateforme E-commerce
-- Schema de base de donnees MariaDB
-- =========================================================

CREATE DATABASE IF NOT EXISTS ecommerce_design CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ecommerce_design;

-- ---------------------------------------------------------
-- Table : utilisateurs (acces a l'administration)
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('admin','gestionnaire') NOT NULL DEFAULT 'gestionnaire',
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Utilisateur par defaut : admin@omega.com / admin123
INSERT INTO utilisateurs (nom, email, mot_de_passe, role)
VALUES ('Administrateur', 'admin@omega.com', '$2b$10$2k7LBRTBsSNweAKQQqRag.LAJ4XWQkhKGlYX6pFqVYJ9OYyO4rTfO', 'admin')
ON DUPLICATE KEY UPDATE email = email;

-- ---------------------------------------------------------
-- Table : fournisseurs
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS fournisseurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom_entreprise VARCHAR(150) NOT NULL,
    contact_nom VARCHAR(100),
    email VARCHAR(150),
    telephone VARCHAR(30),
    adresse VARCHAR(255),
    ville VARCHAR(100),
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Table : clients
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100),
    email VARCHAR(150),
    telephone VARCHAR(30),
    adresse VARCHAR(255),
    ville VARCHAR(100),
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Table : produits
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS produits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    description TEXT,
    prix DECIMAL(12,2) NOT NULL DEFAULT 0,
    stock INT NOT NULL DEFAULT 0,
    image VARCHAR(255) DEFAULT NULL,
    id_fournisseur INT DEFAULT NULL,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_fournisseur) REFERENCES fournisseurs(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Table : factures
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS factures (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero VARCHAR(30) NOT NULL UNIQUE,
    id_client INT NOT NULL,
    date_facture DATE NOT NULL,
    statut ENUM('brouillon','payee','impayee','annulee') NOT NULL DEFAULT 'impayee',
    total DECIMAL(12,2) NOT NULL DEFAULT 0,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_client) REFERENCES clients(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Table : facture_lignes (lignes de facturation)
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS facture_lignes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_facture INT NOT NULL,
    id_produit INT DEFAULT NULL,
    designation VARCHAR(200) NOT NULL,
    quantite INT NOT NULL DEFAULT 1,
    prix_unitaire DECIMAL(12,2) NOT NULL DEFAULT 0,
    sous_total DECIMAL(12,2) NOT NULL DEFAULT 0,
    FOREIGN KEY (id_facture) REFERENCES factures(id) ON DELETE CASCADE,
    FOREIGN KEY (id_produit) REFERENCES produits(id) ON DELETE SET NULL
) ENGINE=InnoDB;
