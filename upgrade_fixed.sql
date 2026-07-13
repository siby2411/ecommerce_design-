USE ecommerce_design;

-- Création des tables (sans erreur si elles existent déjà)
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Ajout de la colonne id_categorie si elle n'existe pas
SET @dbname = 'ecommerce_design';
SET @tablename = 'produits';
SET @columnname = 'id_categorie';
SET @preparedStatement = (SELECT IF(
    (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = @tablename AND COLUMN_NAME = @columnname) = 0,
    CONCAT('ALTER TABLE ', @tablename, ' ADD COLUMN ', @columnname, ' INT DEFAULT NULL;'),
    'SELECT 1;'
));
PREPARE stmt FROM @preparedStatement;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Ajout de la clé étrangère (si elle n'existe pas déjà)
SET @preparedStatement = (SELECT IF(
    (SELECT COUNT(*) FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = @tablename AND CONSTRAINT_NAME = 'produits_ibfk_2') = 0,
    'ALTER TABLE produits ADD FOREIGN KEY (id_categorie) REFERENCES categories(id) ON DELETE SET NULL;',
    'SELECT 1;'
));
PREPARE stmt FROM @preparedStatement;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Ajout des colonnes TVA dans factures
ALTER TABLE factures ADD COLUMN IF NOT EXISTS tva DECIMAL(5,2) DEFAULT 0.00;
ALTER TABLE factures ADD COLUMN IF NOT EXISTS montant_tva DECIMAL(12,2) DEFAULT 0.00;

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
