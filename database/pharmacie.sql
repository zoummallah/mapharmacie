-- Script SQL pour la base de données de gestion de pharmacie (Aligné sur le MLD avec améliorations)

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `pharmacie_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `pharmacie_db`;

-- Table utilisateur
CREATE TABLE `utilisateur` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `nom` VARCHAR(100) NOT NULL,
  `prenom` VARCHAR(100) NOT NULL, -- Ajouté pour plus de cohérence avec le reste
  `email` VARCHAR(100) UNIQUE NOT NULL,
  `mot_de_passe` VARCHAR(255) NOT NULL,
  `role` ENUM('admin', 'pharmacien') DEFAULT 'pharmacien',
  `statut` BOOLEAN DEFAULT TRUE,
  `date_creation` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO `utilisateur` (`nom`, `prenom`, `email`, `mot_de_passe`, `role`) VALUES
('Administrateur', 'System', 'admin@pharmacie.com', '$2y$10$wE3/T8A./eJ0h6zS8U1bN.jZ/K/bU/x7o1w8T0F4E8u.t9g3f9pBq', 'admin');

-- Table categorie
CREATE TABLE `categorie` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `nom` VARCHAR(100) UNIQUE NOT NULL,
  `description` TEXT
) ENGINE=InnoDB;

-- Table fournisseur
CREATE TABLE `fournisseur` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `nom` VARCHAR(100) NOT NULL,
  `telephone` VARCHAR(20),
  `email` VARCHAR(100),
  `adresse` TEXT,
  `contact_nom` VARCHAR(100)
) ENGINE=InnoDB;

-- Table medicament
CREATE TABLE `medicament` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `nom` VARCHAR(100) NOT NULL,
  `id_categorie` INT NOT NULL,
  `principe_actif` VARCHAR(100),
  `dosage` VARCHAR(50),
  `forme` VARCHAR(50),
  `prix_achat` DECIMAL(10,2),
  `prix_vente` DECIMAL(10,2) NOT NULL,
  `quantite_stock` INT DEFAULT 0,
  `stock_minimum` INT DEFAULT 5,
  `date_fabrication` DATE,
  `date_expiration` DATE NOT NULL,
  `lot_numero` VARCHAR(50),
  `est_actif` BOOLEAN DEFAULT TRUE,
  FOREIGN KEY (`id_categorie`) REFERENCES `categorie`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Table de liaison medicament_fournisseur (Amélioration)
CREATE TABLE `medicament_fournisseur` (
  `id_medicament` INT NOT NULL,
  `id_fournisseur` INT NOT NULL,
  PRIMARY KEY (`id_medicament`, `id_fournisseur`),
  FOREIGN KEY (`id_medicament`) REFERENCES `medicament`(`id`) ON DELETE RESTRICT,
  FOREIGN KEY (`id_fournisseur`) REFERENCES `fournisseur`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Table lot (Amélioration)
CREATE TABLE `lot` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `id_medicament` INT NOT NULL,
  `numero_lot` VARCHAR(50) NOT NULL,
  `date_fabrication` DATE,
  `date_expiration` DATE NOT NULL,
  `quantite` INT NOT NULL DEFAULT 0,
  `prix_achat` DECIMAL(10,2) NOT NULL,
  `prix_vente` DECIMAL(10,2) NOT NULL,
  `statut` ENUM('actif', 'epuise', 'perime') DEFAULT 'actif',
  FOREIGN KEY (`id_medicament`) REFERENCES `medicament`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Table client
CREATE TABLE `client` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `nom` VARCHAR(100) NOT NULL,
  `telephone` VARCHAR(20),
  `email` VARCHAR(100),
  `adresse` TEXT,
  `historique_medical` TEXT
) ENGINE=InnoDB;

-- Table ordonnance
CREATE TABLE `ordonnance` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `numero_ordonnance` VARCHAR(50) UNIQUE NOT NULL,
  `date_emission` DATE NOT NULL,
  `date_validite` DATE,
  `medecin_prescripteur` VARCHAR(100) NOT NULL,
  `id_client` INT DEFAULT NULL,
  `fichier_ordonnance` VARCHAR(255),
  FOREIGN KEY (`id_client`) REFERENCES `client`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Table vente
CREATE TABLE `vente` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `numero_facture` VARCHAR(20) UNIQUE NOT NULL,
  `date_vente` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `montant_total` DECIMAL(10,2) NOT NULL,
  `id_utilisateur` INT NOT NULL,
  `id_client` INT,
  `id_ordonnance` INT,
  FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur`(`id`) ON DELETE RESTRICT,
  FOREIGN KEY (`id_client`) REFERENCES `client`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`id_ordonnance`) REFERENCES `ordonnance`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Table ligne_vente
CREATE TABLE `ligne_vente` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `id_vente` INT NOT NULL,
  `id_medicament` INT NOT NULL, -- Pour compatibilité si non utilisation des lots, ou on ajoute id_lot
  `id_lot` INT DEFAULT NULL, -- Amélioration
  `quantite` INT NOT NULL,
  `prix_unitaire` DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (`id_vente`) REFERENCES `vente`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`id_medicament`) REFERENCES `medicament`(`id`) ON DELETE RESTRICT,
  FOREIGN KEY (`id_lot`) REFERENCES `lot`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Table mouvement_stock
CREATE TABLE `mouvement_stock` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `id_medicament` INT NOT NULL,
  `id_lot` INT DEFAULT NULL, -- Amélioration
  `type_mouvement` ENUM('entree', 'sortie', 'retour_fournisseur', 'rebut') NOT NULL,
  `quantite` INT NOT NULL,
  `date_mouvement` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `motif` VARCHAR(255),
  `id_utilisateur` INT NOT NULL,
  FOREIGN KEY (`id_medicament`) REFERENCES `medicament`(`id`) ON DELETE RESTRICT,
  FOREIGN KEY (`id_lot`) REFERENCES `lot`(`id`) ON DELETE RESTRICT,
  FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Table historique_action
CREATE TABLE `historique_action` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `id_utilisateur` INT DEFAULT NULL,
  `action` VARCHAR(50) NOT NULL,
  `entite` VARCHAR(50) NOT NULL,
  `entite_id` INT,
  `details` TEXT,
  `date_action` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Index d'optimisation
CREATE INDEX `idx_medicament_nom` ON `medicament`(`nom`);
CREATE INDEX `idx_lot_statut_exp` ON `lot`(`statut`, `date_expiration`);
CREATE INDEX `idx_vente_date` ON `vente`(`date_vente`);
CREATE INDEX `idx_mouvement_date` ON `mouvement_stock`(`date_mouvement`);
CREATE INDEX `idx_historique_date` ON `historique_action`(`date_action`);

DELIMITER $$

-- Fonction pour calculer la marge brute
CREATE FUNCTION `fn_calculer_marge`(p_prix_vente DECIMAL(10,2), p_prix_achat DECIMAL(10,2), p_qte INT) 
RETURNS DECIMAL(10,2)
DETERMINISTIC
BEGIN
    RETURN (p_prix_vente - p_prix_achat) * p_qte;
END$$

-- Fonction pour vérifier la disponibilité du stock
CREATE FUNCTION `fn_verifier_stock`(p_id_medicament INT, p_id_lot INT, p_qte_demandee INT) 
RETURNS TINYINT(1)
READS SQL DATA
BEGIN
    DECLARE v_stock_dispo INT DEFAULT 0;
    
    IF p_id_lot IS NOT NULL THEN
        SELECT COALESCE(SUM(quantite), 0) INTO v_stock_dispo FROM `lot` WHERE id = p_id_lot;
    ELSE
        SELECT COALESCE(SUM(quantite_stock), 0) INTO v_stock_dispo FROM `medicament` WHERE id = p_id_medicament;
    END IF;
    
    IF v_stock_dispo >= p_qte_demandee THEN
        RETURN 1;
    ELSE
        RETURN 0;
    END IF;
END$$

-- Trigger BEFORE pour empêcher une vente sans stock et la vente de produits périmés
CREATE TRIGGER `before_ligne_vente_insert` BEFORE INSERT ON `ligne_vente`
FOR EACH ROW
BEGIN
    DECLARE v_date_exp DATE;

    IF fn_verifier_stock(NEW.id_medicament, NEW.id_lot, NEW.quantite) = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Stock insuffisant pour cette vente';
    END IF;
    
    IF NEW.id_lot IS NOT NULL THEN
        SELECT date_expiration INTO v_date_exp FROM `lot` WHERE id = NEW.id_lot;
    ELSE
        SELECT date_expiration INTO v_date_exp FROM `medicament` WHERE id = NEW.id_medicament;
    END IF;

    IF v_date_exp < CURDATE() THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Impossible de vendre un produit périmé';
    END IF;
END$$

-- Trigger BEFORE pour empêcher une sortie de stock manuelle sans stock
CREATE TRIGGER `before_mouvement_stock_insert` BEFORE INSERT ON `mouvement_stock`
FOR EACH ROW
BEGIN
    IF NEW.type_mouvement != 'entree' AND fn_verifier_stock(NEW.id_medicament, NEW.id_lot, NEW.quantite) = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Stock insuffisant pour ce mouvement';
    END IF;
END$$

-- Trigger lors de l'ajout d'une ligne de vente (Sortie de stock automatique sur LOT et MEDICAMENT)
CREATE TRIGGER `after_ligne_vente_insert` AFTER INSERT ON `ligne_vente`
FOR EACH ROW
BEGIN
    IF NEW.id_lot IS NOT NULL THEN
        UPDATE `lot` SET quantite = quantite - NEW.quantite WHERE id = NEW.id_lot;
        UPDATE `lot` SET statut = 'epuise' WHERE id = NEW.id_lot AND quantite <= 0;
    END IF;
    -- Mettre à jour aussi le stock global du médicament
    UPDATE `medicament` SET quantite_stock = quantite_stock - NEW.quantite WHERE id = NEW.id_medicament;
END$$

-- Trigger AFTER DELETE pour restaurer le stock en cas d'annulation de vente
CREATE TRIGGER `after_ligne_vente_delete` AFTER DELETE ON `ligne_vente`
FOR EACH ROW
BEGIN
    IF OLD.id_lot IS NOT NULL THEN
        UPDATE `lot` SET quantite = quantite + OLD.quantite, statut = IF(quantite + OLD.quantite > 0 AND date_expiration >= CURDATE(), 'actif', statut) WHERE id = OLD.id_lot;
    END IF;
    UPDATE `medicament` SET quantite_stock = quantite_stock + OLD.quantite WHERE id = OLD.id_medicament;
END$$

-- Trigger AFTER UPDATE pour ajuster le stock si la quantité de vente est modifiée
CREATE TRIGGER `after_ligne_vente_update` AFTER UPDATE ON `ligne_vente`
FOR EACH ROW
BEGIN
    DECLARE diff INT;
    SET diff = NEW.quantite - OLD.quantite;
    
    IF NEW.id_lot IS NOT NULL THEN
        UPDATE `lot` SET quantite = quantite - diff WHERE id = NEW.id_lot;
        UPDATE `lot` SET statut = 'epuise' WHERE id = NEW.id_lot AND quantite <= 0;
    END IF;
    UPDATE `medicament` SET quantite_stock = quantite_stock - diff WHERE id = NEW.id_medicament;
END$$

-- Trigger pour gérer les mouvements de stock
CREATE TRIGGER `after_mouvement_stock_insert` AFTER INSERT ON `mouvement_stock`
FOR EACH ROW
BEGIN
    IF NEW.type_mouvement = 'entree' THEN
        IF NEW.id_lot IS NOT NULL THEN
            UPDATE `lot` SET quantite = quantite + NEW.quantite WHERE id = NEW.id_lot;
        END IF;
        UPDATE `medicament` SET quantite_stock = quantite_stock + NEW.quantite WHERE id = NEW.id_medicament;
    ELSE
        IF NEW.id_lot IS NOT NULL THEN
            UPDATE `lot` SET quantite = quantite - NEW.quantite WHERE id = NEW.id_lot;
            UPDATE `lot` SET statut = 'epuise' WHERE id = NEW.id_lot AND quantite <= 0;
        END IF;
        UPDATE `medicament` SET quantite_stock = quantite_stock - NEW.quantite WHERE id = NEW.id_medicament;
    END IF;
END$$
DELIMITER ;

COMMIT;
