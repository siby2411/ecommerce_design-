USE ecommerce_design;

-- Catégories
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Ajout colonne id_categorie dans produits
ALTER TABLE produits ADD COLUMN id_categorie INT DEFAULT NULL;
ALTER TABLE produits ADD FOREIGN KEY (id_categorie) REFERENCES categories(id) ON DELETE SET NULL;

-- TVA sur factures
ALTER TABLE factures ADD COLUMN tva DECIMAL(5,2) DEFAULT 0.00;
ALTER TABLE factures ADD COLUMN montant_tva DECIMAL(12,2) DEFAULT 0.00;

-- Journal des activités
CREATE TABLE IF NOT EXISTS logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT NOT NULL,
    action VARCHAR(100) NOT NULL,
    details TEXT,
    ip VARCHAR(45),
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id) ON DELETE CASCADE
) ENGINE=InnoDB;
