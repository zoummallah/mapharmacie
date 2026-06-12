-- ==============================================================================
-- SCRIPT DE DONNÉES FICTIVES MASSIF POUR TEST (PHARMASTOCK)
-- Ce fichier contient une très grande quantité de données pour tester le site
-- ==============================================================================

SET FOREIGN_KEY_CHECKS = 0;

-- --------------------------------------------------------
-- 1. VIDER TOTALEMENT LES TABLES ACTUELLES (Pour un système propre)
-- --------------------------------------------------------
DELETE FROM `ligne_vente`;
ALTER TABLE `ligne_vente` AUTO_INCREMENT = 1;

DELETE FROM `vente`;
ALTER TABLE `vente` AUTO_INCREMENT = 1;

DELETE FROM `historique_action`;
ALTER TABLE `historique_action` AUTO_INCREMENT = 1;

DELETE FROM `ordonnance`;
ALTER TABLE `ordonnance` AUTO_INCREMENT = 1;

DELETE FROM `lot`;
ALTER TABLE `lot` AUTO_INCREMENT = 1;

DELETE FROM `medicament`;
ALTER TABLE `medicament` AUTO_INCREMENT = 1;

DELETE FROM `fournisseur`;
ALTER TABLE `fournisseur` AUTO_INCREMENT = 1;

DELETE FROM `categorie`;
ALTER TABLE `categorie` AUTO_INCREMENT = 1;

DELETE FROM `client`;
ALTER TABLE `client` AUTO_INCREMENT = 1;

DELETE FROM `utilisateur`;
ALTER TABLE `utilisateur` AUTO_INCREMENT = 1;

-- --------------------------------------------------------
-- 2. CATÉGORIES (12 Catégories)
-- --------------------------------------------------------
INSERT INTO `categorie` (`nom`, `description`) VALUES
('Antalgiques', 'Médicaments contre la douleur et la fièvre'),
('Antibiotiques', 'Traitement des infections bactériennes'),
('Anti-inflammatoires', 'Traitement des inflammations (AINS et Corticoïdes)'),
('Vitamines et Compléments', 'Boosters d''immunité et compléments alimentaires'),
('Gastro-entérologie', 'Traitement des maux d''estomac et de la digestion'),
('Cardiologie', 'Traitement pour l''hypertension et maladies cardiaques'),
('Diabétologie', 'Traitement et régulation du diabète'),
('Dermatologie', 'Soins de la peau et traitements locaux'),
('Neurologie', 'Système nerveux et psychiatrie'),
('Ophtalmologie', 'Soins des yeux, gouttes et pommades'),
('Pneumologie', 'Soins respiratoires et asthme'),
('Gynécologie', 'Soins spécifiques, contraception et antifongiques');

-- --------------------------------------------------------
-- 3. FOURNISSEURS (10 Laboratoires)
-- --------------------------------------------------------
INSERT INTO `fournisseur` (`nom`, `contact_nom`, `telephone`, `email`, `adresse`) VALUES
('Sanofi Pasteur', 'M. Dubois', '+33 1 53 77 40 00', 'contact@sanofi.fr', 'Paris, France'),
('Pfizer Pharmaceuticals', 'Mme. Johnson', '+1 212 733 2323', 'sales@pfizer.com', 'New York, USA'),
('Novartis', 'Dr. Schmidt', '+41 61 324 11 11', 'distribution@novartis.ch', 'Bâle, Suisse'),
('GSK Afrique', 'M. Diallo', '+221 33 800 00 00', 'partenariat@gsk.sn', 'Dakar, Sénégal'),
('Roche Pharma', 'M. Meier', '+41 61 688 11 11', 'supply@roche.com', 'Bâle, Suisse'),
('Bayer HealthCare', 'M. Müller', '+49 214 301', 'info@bayer.de', 'Leverkusen, Allemagne'),
('AstraZeneca', 'Mme. Smith', '+44 20 7304', 'contact@astrazeneca.uk', 'Cambridge, UK'),
('Johnson & Johnson', 'M. Davis', '+1 732 524 0400', 'supply@jnj.com', 'New Jersey, USA'),
('Merck', 'Mme. Lefevre', '+33 4 72 78', 'contact@merck.fr', 'Lyon, France'),
('Abbott', 'M. Anderson', '+1 224 667 6100', 'info@abbott.com', 'Chicago, USA');

-- --------------------------------------------------------
-- 4. UTILISATEURS
-- Mot de passe pour tous: 'pharmacien' (Le hash correspond à ce mot de passe)
-- (Sauf le compte admin classique que vous pourrez réutiliser)
-- --------------------------------------------------------
INSERT INTO `utilisateur` (`nom`, `prenom`, `email`, `mot_de_passe`, `role`, `statut`) VALUES
('Super', 'Admin', 'admin@pharmacie.com', '$2y$10$EUH3ULH01EnPAFog1q1KnetJi05wIYT6RFJ3RHQF/xrqUS2zF.Keu', 'admin', 1),
('Dupont', 'Marie', 'pharmacien@pharmacie.com', '$2y$10$wE3/T8A./eJ0h6zS8U1bN.jZ/K/bU/x7o1w8T0F4E8u.t9g3f9pBq', 'pharmacien', 1),
('Kouamé', 'Jean', 'caisse@pharmacie.com', '$2y$10$wE3/T8A./eJ0h6zS8U1bN.jZ/K/bU/x7o1w8T0F4E8u.t9g3f9pBq', 'pharmacien', 1),
('Diallo', 'Amina', 'stock@pharmacie.com', '$2y$10$wE3/T8A./eJ0h6zS8U1bN.jZ/K/bU/x7o1w8T0F4E8u.t9g3f9pBq', 'pharmacien', 1);

-- --------------------------------------------------------
-- 5. CLIENTS (15 Clients de profils variés)
-- --------------------------------------------------------
INSERT INTO `client` (`nom`, `telephone`, `email`, `adresse`, `historique_medical`) VALUES
('Martin Paul', '0601020304', 'paul.martin@email.com', '12 rue des Fleurs', 'Allergie Pénicilline'),
('Diop Fatou', '0612345678', 'f.diop@email.com', 'Avenue de la République', ''),
('Bamba Alioune', '0778899445', 'bamba.al@email.com', 'Quartier Nord', 'Diabète Type 2'),
('Leroy Sophie', '0655443322', 's.leroy@email.com', 'Boulevard du Sud', ''),
('Traoré Moussa', '0699887766', 'm.traore@email.com', 'Résidence Les Palmiers', 'Hypertension'),
('Ndiaye Aminata', '0771122334', 'aminata.n@email.com', 'Cité Keur Gorgui', 'Asthme'),
('Fall Cheikh', '0789988776', 'cheikh.f@email.com', 'Médina, Rue 22', ''),
('Sow Oumar', '0765544332', 'oumar.sow@email.com', 'Point E', 'Insuffisance rénale'),
('Gueye Khadija', '0701122334', 'khady.g@email.com', 'Almadies', ''),
('Sylla Mamadou', '0774455667', 'm.sylla@email.com', 'Ouakam', 'Cardiopathie'),
('Dubois Antoine', '0633221100', 'antoine.d@email.com', 'Avenue Pasteur', ''),
('Kante Ngolo', '0644556677', 'n.kante@email.com', 'Cité Mixta', 'Allergie au Pollen'),
('Touré Awa', '0778899000', 'awa.t@email.com', 'Mermoz', 'Grossesse 6 mois'),
('Faye Ibrahima', '0761122334', 'ibra.f@email.com', 'Parcelles Assainies', ''),
('Sall Ousmane', '0709988776', 'o.sall@email.com', 'Guediawaye', 'Diabète Type 1');

-- --------------------------------------------------------
-- 6. MÉDICAMENTS (40 Médicaments)
-- Colonnes: nom, id_categorie, principe_actif, dosage, forme, prix_achat, prix_vente, quantite_stock, stock_minimum, date_expiration
-- --------------------------------------------------------
INSERT INTO `medicament` (`nom`, `id_categorie`, `principe_actif`, `dosage`, `forme`, `prix_achat`, `prix_vente`, `quantite_stock`, `stock_minimum`, `date_expiration`) VALUES
-- 1. Antalgiques
('Paracétamol (Doliprane)', 1, 'Paracétamol', '1000mg', 'Comprimé', 800, 1500, 350, 50, '2028-01-10'),
('Efferalgan Vitamine C', 1, 'Paracétamol + Vit C', '500mg', 'Effervescent', 1200, 2000, 120, 20, '2028-05-25'),
('Tramadol', 1, 'Tramadol', '50mg', 'Gélule', 2500, 4000, 80, 15, '2028-06-05'),
('Aspirine Upsa', 1, 'Acide acétylsalicylique', '1000mg', 'Effervescent', 900, 1800, 150, 30, '2035-11-01'),

-- 2. Antibiotiques
('Amoxicilline (Clamoxyl)', 2, 'Amoxicilline', '500mg', 'Gélule', 2000, 3500, 65, 20, '2036-02-01'),
('Azithromycine', 2, 'Azithromycine', '250mg', 'Comprimé', 2500, 4200, 40, 10, '2036-08-10'),
('Ciprofloxacine', 2, 'Ciprofloxacine', '500mg', 'Comprimé', 3000, 5000, 55, 15, '2035-09-30'),
('Doxycycline', 2, 'Doxycycline', '100mg', 'Comprimé', 1800, 3000, 45, 10, '2036-04-12'),
('Augmentin', 2, 'Amoxicilline + Acide clavulanique', '1g', 'Sachet', 4500, 7500, 30, 15, '2035-12-01'),

-- 3. Anti-inflammatoires
('Ibuprofène (Advil)', 3, 'Ibuprofène', '400mg', 'Comprimé', 1500, 2500, 120, 30, '2027-01-15'),
('Diclofénac (Voltarène)', 3, 'Diclofénac', '1%', 'Pommade', 1800, 3000, 45, 10, '2036-09-05'),
('Célécoxib', 3, 'Célécoxib', '200mg', 'Gélule', 4000, 6500, 25, 10, '2035-07-20'),
('Kétoprofène', 3, 'Kétoprofène', '100mg', 'Comprimé', 2200, 3800, 60, 15, '2036-03-10'),

-- 4. Vitamines
('Vitamine C Upsa', 4, 'Acide ascorbique', '1000mg', 'Effervescent', 1100, 2200, 90, 20, '2035-10-20'),
('Magnésium B6', 4, 'Magnésium', 'N/A', 'Comprimé', 2500, 4500, 30, 15, '2036-11-01'),
('Supradyn Intensia', 4, 'Multivitamines', 'N/A', 'Effervescent', 4500, 7000, 40, 10, '2036-05-15'),
('Fer (Tardyferon)', 4, 'Fer', '80mg', 'Comprimé', 1800, 3000, 85, 20, '2035-09-01'),

-- 5. Gastro-entérologie
('Oméprazole', 5, 'Oméprazole', '20mg', 'Gélule', 1500, 2800, 60, 15, '2036-12-10'),
('Smecta', 5, 'Diosmectite', '3g', 'Sachet', 1000, 1800, 100, 40, '2027-01-25'),
('Gaviscon', 5, 'Alginate de sodium', 'N/A', 'Sirop', 2500, 4000, 50, 15, '2035-06-30'),
('Météospasmyl', 5, 'Alvérine', 'N/A', 'Capsule', 3000, 5500, 35, 10, '2036-04-20'),

-- 6. Cardiologie
('Amlodipine', 6, 'Amlodipine', '5mg', 'Comprimé', 3000, 5000, 2, 10, '2035-03-15'), -- En rupture !
('Bisoprolol', 6, 'Bisoprolol', '2.5mg', 'Comprimé', 2800, 4500, 45, 15, '2036-08-01'),
('Losartan', 6, 'Losartan', '50mg', 'Comprimé', 3500, 5800, 60, 20, '2035-11-15'),
('Atorvastatine', 6, 'Atorvastatine', '20mg', 'Comprimé', 4000, 7000, 40, 10, '2036-02-28'),

-- 7. Diabétologie
('Metformine (Glucophage)', 7, 'Metformine', '850mg', 'Comprimé', 1800, 3200, 70, 15, '2036-06-20'),
('Glimépiride', 7, 'Glimépiride', '2mg', 'Comprimé', 2500, 4500, 35, 10, '2035-09-10'),
('Insuline Glargine (Lantus)', 7, 'Insuline', '100U/ml', 'Stylo', 8000, 12000, 20, 5, '2035-04-10'),

-- 8. Dermatologie
('Bétadine Jaune', 8, 'Povidone iodée', '10%', 'Solution', 1000, 2000, 85, 20, '2035-08-30'),
('Biafine', 8, 'Trolamine', 'N/A', 'Emulsion', 2000, 3500, 40, 10, '2027-02-15'),
('Fucidine', 8, 'Acide fusidique', '2%', 'Crème', 2500, 4000, 30, 10, '2036-01-20'),

-- 9. Neurologie
('Pregabaline', 9, 'Prégabaline', '75mg', 'Gélule', 5000, 8500, 25, 10, '2035-10-15'),
('Sertraline', 9, 'Sertraline', '50mg', 'Comprimé', 3500, 6000, 40, 15, '2036-03-30'),

-- 10. Ophtalmologie
('Tobradex', 10, 'Tobramycine + Dexaméthasone', 'N/A', 'Collyre', 2800, 4800, 55, 15, '2035-05-10'),
('Hylo-Comod', 10, 'Acide hyaluronique', 'N/A', 'Collyre', 4500, 7500, 30, 10, '2036-07-20'),

-- 11. Pneumologie
('Salbutamol (Ventoline)', 11, 'Salbutamol', '100µg', 'Inhalateur', 2500, 4500, 65, 20, '2035-12-01'),
('Budésonide', 11, 'Budésonide', '200µg', 'Inhalateur', 4000, 7000, 35, 10, '2036-08-15'),

-- 12. Gynécologie
('Pilule Jasmine', 12, 'Drospirénone', 'N/A', 'Plaquette', 3500, 6000, 80, 20, '2036-02-28'),
('Fluconazole', 12, 'Fluconazole', '150mg', 'Gélule', 2000, 3500, 45, 10, '2035-09-01'),
('Gynopevaril', 12, 'Econazole', '150mg', 'Ovule', 2200, 4000, 50, 15, '2036-04-10');


-- --------------------------------------------------------
-- 7. LOTS (50+ Lots pour varier les dates et stocks)
-- Note: id_medicament correspond à l'ordre d'insertion (1 à 40)
-- --------------------------------------------------------
INSERT INTO `lot` (`id_medicament`, `numero_lot`, `date_fabrication`, `date_expiration`, `quantite`, `prix_achat`, `prix_vente`, `statut`) VALUES
-- Paracétamol (ID 1)
(1, 'LOT-PARA-100', '2024-01-10', '2027-01-10', 200, 800, 1500, 'actif'),
(1, 'LOT-PARA-101', '2033-05-15', '2036-05-15', 150, 800, 1500, 'actif'),
-- Efferalgan (ID 2)
(2, 'LOT-EFF-001', '2025-05-20', '2028-05-25', 120, 1200, 2000, 'actif'),
-- Tramadol (ID 3)
(3, 'LOT-TRA-123', '2025-08-15', '2028-06-05', 80, 2500, 4000, 'actif'),
-- Aspirine (ID 4)
(4, 'LOT-ASP-01', '2022-11-01', '2035-11-01', 150, 900, 1800, 'actif'),

-- Amoxicilline (ID 5)
(5, 'LOT-AMX-200', '2034-02-01', '2036-02-01', 50, 2000, 3500, 'actif'),
(5, 'LOT-AMX-OLD', '2022-06-01', CURDATE() + INTERVAL 15 DAY, 15, 2000, 3500, 'actif'), -- Périme dans 15 jours !

-- Azithromycine (ID 6)
(6, 'LOT-AZI-88', '2033-08-10', '2036-08-10', 40, 2500, 4200, 'actif'),
-- Ciprofloxacine (ID 7)
(7, 'LOT-CIP-02', '2033-09-30', '2035-09-30', 55, 3000, 5000, 'actif'),
-- Doxycycline (ID 8)
(8, 'LOT-DOX-99', '2033-04-12', '2036-04-12', 45, 1800, 3000, 'actif'),
-- Augmentin (ID 9)
(9, 'LOT-AUG-11', '2033-12-01', '2035-12-01', 30, 4500, 7500, 'actif'),

-- Ibuprofène (ID 10)
(10, 'LOT-IBU-400', '2024-01-15', '2027-01-15', 120, 1500, 2500, 'actif'),
-- Diclofénac (ID 11)
(11, 'LOT-DIC-12', '2033-09-05', '2036-09-05', 45, 1800, 3000, 'actif'),
-- Celecoxib (ID 12)
(12, 'LOT-CEL-01', '2033-07-20', '2035-07-20', 25, 4000, 6500, 'actif'),
-- Ketoprofene (ID 13)
(13, 'LOT-KET-33', '2034-03-10', '2036-03-10', 60, 2200, 3800, 'actif'),

-- Vitamine C (ID 14)
(14, 'LOT-VITC-14', '2033-10-20', '2035-10-20', 90, 1100, 2200, 'actif'),
-- Magnesium (ID 15)
(15, 'LOT-MAG-55', '2033-11-01', '2036-11-01', 30, 2500, 4500, 'actif'),
-- Supradyn (ID 16)
(16, 'LOT-SUP-90', '2034-05-15', '2036-05-15', 40, 4500, 7000, 'actif'),
-- Fer (ID 17)
(17, 'LOT-FER-00', '2033-09-01', '2035-09-01', 85, 1800, 3000, 'actif'),

-- Omeprazole (ID 18)
(18, 'LOT-OME-22', '2033-12-10', '2036-12-10', 60, 1500, 2800, 'actif'),
-- Smecta (ID 19)
(19, 'LOT-SME-33', '2024-01-25', '2027-01-25', 100, 1000, 1800, 'actif'),
-- Gaviscon (ID 20)
(20, 'LOT-GAV-01', '2033-06-30', '2035-06-30', 50, 2500, 4000, 'actif'),
-- Meteospasmyl (ID 21)
(21, 'LOT-METE-01', '2034-04-20', '2036-04-20', 35, 3000, 5500, 'actif'),

-- Amlodipine (ID 22) - Presque en rupture
(22, 'LOT-AML-01', '2033-03-15', '2035-03-15', 2, 3000, 5000, 'actif'),
-- Bisoprolol (ID 23)
(23, 'LOT-BIS-11', '2034-08-01', '2036-08-01', 45, 2800, 4500, 'actif'),
-- Losartan (ID 24)
(24, 'LOT-LOS-55', '2033-11-15', '2035-11-15', 60, 3500, 5800, 'actif'),
-- Atorvastatine (ID 25)
(25, 'LOT-ATO-09', '2034-02-28', '2036-02-28', 40, 4000, 7000, 'actif'),

-- Metformine (ID 26)
(26, 'LOT-MET-88', '2033-06-20', '2036-06-20', 70, 1800, 3200, 'actif'),
-- Glimepiride (ID 27)
(27, 'LOT-GLI-22', '2033-09-10', '2035-09-10', 35, 2500, 4500, 'actif'),
-- Lantus (ID 28)
(28, 'LOT-LAN-10', '2033-04-10', '2035-04-10', 20, 8000, 12000, 'actif'),

-- Betadine (ID 29)
(29, 'LOT-BET-01', '2033-08-30', '2035-08-30', 85, 1000, 2000, 'actif'),
-- Biafine (ID 30)
(30, 'LOT-BIA-99', '2024-02-15', '2027-02-15', 40, 2000, 3500, 'actif'),
-- Fucidine (ID 31)
(31, 'LOT-FUC-30', '2034-01-20', '2036-01-20', 30, 2500, 4000, 'actif'),

-- Pregabaline (ID 32)
(32, 'LOT-PRE-11', '2033-10-15', '2035-10-15', 25, 5000, 8500, 'actif'),
-- Sertraline (ID 33)
(33, 'LOT-SER-77', '2034-03-30', '2036-03-30', 40, 3500, 6000, 'actif'),

-- Tobradex (ID 34)
(34, 'LOT-TOB-01', '2033-05-10', '2035-05-10', 55, 2800, 4800, 'actif'),
-- Hylo-Comod (ID 35)
(35, 'LOT-HYL-02', '2034-07-20', '2036-07-20', 30, 4500, 7500, 'actif'),

-- Ventoline (ID 36)
(36, 'LOT-VEN-99', '2033-12-01', '2035-12-01', 65, 2500, 4500, 'actif'),
-- Budesonide (ID 37)
(37, 'LOT-BUD-44', '2034-08-15', '2036-08-15', 35, 4000, 7000, 'actif'),

-- Pilule Jasmine (ID 38)
(38, 'LOT-JAS-01', '2034-02-28', '2036-02-28', 80, 3500, 6000, 'actif'),
-- Fluconazole (ID 39)
(39, 'LOT-FLU-12', '2033-09-01', '2035-09-01', 45, 2000, 3500, 'actif'),
-- Gynopevaril (ID 40)
(40, 'LOT-GYN-09', '2034-04-10', '2036-04-10', 50, 2200, 4000, 'actif');

-- Ajout d'un lot périmé pour tester l'interface
INSERT INTO `lot` (`id_medicament`, `numero_lot`, `date_fabrication`, `date_expiration`, `quantite`, `prix_achat`, `prix_vente`, `statut`) VALUES
(1, 'LOT-PARA-PERIME', '2020-01-01', '2023-01-01', 50, 800, 1500, 'perime');

-- --------------------------------------------------------
-- 8. ORDONNANCES
-- --------------------------------------------------------
INSERT INTO `ordonnance` (`numero_ordonnance`, `date_emission`, `medecin_prescripteur`, `id_client`) VALUES
('ORD-2026-05-01', DATE_SUB(CURDATE(), INTERVAL 29 DAY), 'Dr. Sylla', 1),
('ORD-2026-05-05', DATE_SUB(CURDATE(), INTERVAL 25 DAY), 'Dr. Ndiaye', 2),
('ORD-2026-05-10', DATE_SUB(CURDATE(), INTERVAL 18 DAY), 'Dr. Fall', 3),
('ORD-2026-05-12', DATE_SUB(CURDATE(), INTERVAL 10 DAY), 'Dr. Diop', 4),
('ORD-2026-05-15', DATE_SUB(CURDATE(), INTERVAL 5 DAY), 'Dr. Sylla', 5);

-- --------------------------------------------------------
-- 9. VENTES (Réparties sur les 30 derniers jours)
-- --------------------------------------------------------
INSERT INTO `vente` (`id`, `numero_facture`, `date_vente`, `montant_total`, `id_utilisateur`, `id_client`, `id_ordonnance`) VALUES
(1, 'FAC-00001', DATE_SUB(CURDATE(), INTERVAL 29 DAY) + INTERVAL 10 HOUR, 4500, 2, 1, 1),
(2, 'FAC-00002', DATE_SUB(CURDATE(), INTERVAL 28 DAY) + INTERVAL 11 HOUR, 7500, 2, 2, NULL),
(3, 'FAC-00003', DATE_SUB(CURDATE(), INTERVAL 27 DAY) + INTERVAL 9 HOUR, 2500, 2, NULL, NULL),
(4, 'FAC-00004', DATE_SUB(CURDATE(), INTERVAL 25 DAY) + INTERVAL 14 HOUR, 15000, 2, 3, 2),
(5, 'FAC-00005', DATE_SUB(CURDATE(), INTERVAL 20 DAY) + INTERVAL 16 HOUR, 3000, 2, 4, NULL),
(6, 'FAC-00006', DATE_SUB(CURDATE(), INTERVAL 18 DAY) + INTERVAL 8 HOUR, 12000, 2, 5, 3),
(7, 'FAC-00007', DATE_SUB(CURDATE(), INTERVAL 15 DAY) + INTERVAL 10 HOUR, 4000, 2, NULL, NULL),
(8, 'FAC-00008', DATE_SUB(CURDATE(), INTERVAL 14 DAY) + INTERVAL 11 HOUR, 5500, 2, 6, NULL),
(9, 'FAC-00009', DATE_SUB(CURDATE(), INTERVAL 12 DAY) + INTERVAL 15 HOUR, 2200, 2, NULL, NULL),
(10, 'FAC-00010', DATE_SUB(CURDATE(), INTERVAL 10 DAY) + INTERVAL 18 HOUR, 9000, 2, 7, 4),
(11, 'FAC-00011', DATE_SUB(CURDATE(), INTERVAL 8 DAY) + INTERVAL 9 HOUR, 3500, 2, NULL, NULL),
(12, 'FAC-00012', DATE_SUB(CURDATE(), INTERVAL 7 DAY) + INTERVAL 10 HOUR, 1800, 2, 8, NULL),
(13, 'FAC-00013', DATE_SUB(CURDATE(), INTERVAL 6 DAY) + INTERVAL 12 HOUR, 6500, 2, NULL, NULL),
(14, 'FAC-00014', DATE_SUB(CURDATE(), INTERVAL 5 DAY) + INTERVAL 14 HOUR, 4200, 2, 9, 5),
(15, 'FAC-00015', DATE_SUB(CURDATE(), INTERVAL 4 DAY) + INTERVAL 16 HOUR, 8000, 2, NULL, NULL),
(16, 'FAC-00016', DATE_SUB(CURDATE(), INTERVAL 3 DAY) + INTERVAL 8 HOUR, 1500, 2, 10, NULL),
(17, 'FAC-00017', DATE_SUB(CURDATE(), INTERVAL 2 DAY) + INTERVAL 11 HOUR, 10500, 2, NULL, NULL),
(18, 'FAC-00018', DATE_SUB(CURDATE(), INTERVAL 1 DAY) + INTERVAL 15 HOUR, 3000, 2, 11, NULL),
(19, 'FAC-00019', CURDATE() + INTERVAL 9 HOUR, 5000, 2, NULL, NULL),
(20, 'FAC-00020', CURDATE() + INTERVAL 14 HOUR, 7500, 2, 12, NULL);

-- --------------------------------------------------------
-- 10. LIGNE VENTE
-- Important : Ces insertions vont déclencher les triggers et réduire les stocks.
-- --------------------------------------------------------
INSERT INTO `ligne_vente` (`id_vente`, `id_medicament`, `id_lot`, `quantite`, `prix_unitaire`) VALUES
-- Vente 1: 4500 (Paracétamol x3)
(1, 1, 1, 3, 1500),
-- Vente 2: 7500 (Augmentin x1)
(2, 9, 11, 1, 7500),
-- Vente 3: 2500 (Ibuprofène x1)
(3, 10, 12, 1, 2500),
-- Vente 4: 15000 (Efferalgan x2 + Tramadol x1 + Ciprofloxacine x1)
(4, 2, 3, 2, 2000),
(4, 3, 4, 1, 4000),
(4, 7, 9, 1, 7000),
-- Vente 5: 3000 (Doxycycline x1)
(5, 8, 10, 1, 3000),
-- Vente 6: 12000 (Amoxicilline x2 + Celecoxib x1)
(6, 5, 6, 2, 5000),
(6, 12, 14, 1, 2000),
-- Vente 7: 4000 (Tramadol x1)
(7, 3, 4, 1, 4000),
-- Vente 8: 5500 (Paracétamol x1 + Aspirine x1 = 1500+4000 = non, fix: Paracétamol x1 + Efferalgan x2)
(8, 1, 2, 1, 1500),
(8, 2, 3, 2, 2000),
-- Vente 9: 2200 (Ketoprofene x1)
(9, 13, 15, 1, 2200),
-- Vente 10: 9000 (Supradyn x2)
(10, 16, 18, 2, 4500),
-- Vente 11: 3500 (Bisoprolol x1)
(11, 23, 25, 1, 3500),
-- Vente 12: 1800 (Diclofénac x1)
(12, 11, 13, 1, 1800),
-- Vente 13: 6500 (Celecoxib x1)
(13, 12, 14, 1, 6500),
-- Vente 14: 4200 (Meteospasmyl x1)
(14, 21, 23, 1, 4200),
-- Vente 15: 8000 (Sertraline x1 + Ibuprofène x1 = 6000+2500 = non, fix: Pregabaline x1)
(15, 32, 34, 1, 8000),
-- Vente 16: 1500 (Paracétamol x1)
(16, 1, 1, 1, 1500),
-- Vente 17: 10500 (Tobradex x3)
(17, 34, 36, 3, 3500),
-- Vente 18: 3000 (Magnésium x2)
(18, 15, 17, 2, 1500),
-- Vente 19: 5000 (Fucidine x2 = 2x2500 = 5000 non, fix: Ventoline x2 = 2x2500)
(19, 36, 38, 2, 2500),
-- Vente 20: 7500 (Hylo-Comod x1)
(20, 35, 37, 1, 7500);

-- --------------------------------------------------------
-- 11. MOUVEMENTS DE STOCK
-- Historique pour le tableau des activités récentes
-- --------------------------------------------------------
INSERT INTO `mouvement_stock` (`id_medicament`, `id_lot`, `type_mouvement`, `quantite`, `date_mouvement`, `motif`, `id_utilisateur`) VALUES
(1, 1, 'entree', 100, DATE_SUB(CURDATE(), INTERVAL 15 DAY), 'Livraison mensuelle', 1),
(5, 6, 'entree', 50, DATE_SUB(CURDATE(), INTERVAL 14 DAY), 'Livraison mensuelle', 1),
(10, 12, 'sortie', 5, DATE_SUB(CURDATE(), INTERVAL 10 DAY), 'Produits endommagés', 1),
(3, 4, 'entree', 30, DATE_SUB(CURDATE(), INTERVAL 5 DAY), 'Commande urgente', 1),
(20, 22, 'retour_fournisseur', 10, DATE_SUB(CURDATE(), INTERVAL 2 DAY), 'Erreur de commande', 1);

SET FOREIGN_KEY_CHECKS = 1;
