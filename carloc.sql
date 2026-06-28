-- ============================================================
--  CarLoc – Base de données
--  Projet fin de cycle L3 UNCHK
-- ============================================================

CREATE DATABASE IF NOT EXISTS carloc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE carloc;

-- ── Catégories de véhicules ──
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ── Utilisateurs ──
CREATE TABLE IF NOT EXISTS utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(60) NOT NULL,
    prenom VARCHAR(60) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    telephone VARCHAR(20),
    adresse TEXT,
    role ENUM('admin','agent','client') DEFAULT 'client',
    actif TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ── Véhicules ──
CREATE TABLE IF NOT EXISTS vehicules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    immatriculation VARCHAR(20) UNIQUE NOT NULL,
    marque VARCHAR(50) NOT NULL,
    modele VARCHAR(50) NOT NULL,
    annee YEAR NOT NULL,
    couleur VARCHAR(30),
    places INT DEFAULT 5,
    prix_par_jour DECIMAL(10,2) NOT NULL,
    statut ENUM('disponible','loue','maintenance') DEFAULT 'disponible',
    image VARCHAR(255) DEFAULT 'default.jpg',
    description TEXT,
    id_categorie INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_categorie) REFERENCES categories(id) ON DELETE SET NULL
);

-- ── Réservations ──
CREATE TABLE IF NOT EXISTS reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reference VARCHAR(20) UNIQUE NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    nb_jours INT NOT NULL,
    montant_total DECIMAL(10,2) NOT NULL,
    statut ENUM('en_attente','confirmee','annulee','terminee') DEFAULT 'en_attente',
    notes TEXT,
    id_client INT NOT NULL,
    id_vehicule INT NOT NULL,
    id_agent INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_client) REFERENCES utilisateurs(id),
    FOREIGN KEY (id_vehicule) REFERENCES vehicules(id),
    FOREIGN KEY (id_agent) REFERENCES utilisateurs(id) ON DELETE SET NULL
);

-- ── Factures ──
CREATE TABLE IF NOT EXISTS factures (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero VARCHAR(20) UNIQUE NOT NULL,
    date_emission DATE NOT NULL,
    montant_ht DECIMAL(10,2) NOT NULL,
    tva DECIMAL(10,2) NOT NULL,
    montant_ttc DECIMAL(10,2) NOT NULL,
    statut_paiement ENUM('en_attente','paye') DEFAULT 'en_attente',
    mode_paiement ENUM('wave','orange_money','especes','virement') DEFAULT 'especes',
    id_reservation INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_reservation) REFERENCES reservations(id)
);

-- ============================================================
--  Données de démonstration
--  IMPORTANT : Les mots de passe sont générés par setup.php
--  Ne pas insérer les utilisateurs ici pour éviter les
--  problèmes d'échappement des hash bcrypt.
--  Lancer public/setup.php après l'import SQL.
-- ============================================================

INSERT IGNORE INTO categories (nom, description) VALUES
('Citadine', 'Petits véhicules économiques, idéaux pour la ville'),
('Berline', 'Voitures familiales confortables'),
('SUV', 'Véhicules tout-terrain et spacieux'),
('Utilitaire', 'Véhicules de transport et livraison'),
('Luxe', 'Véhicules haut de gamme');

INSERT IGNORE INTO vehicules (immatriculation, marque, modele, annee, couleur, places, prix_par_jour, statut, id_categorie, description) VALUES
('SL-1234-AB', 'Toyota', 'Corolla', 2020, 'Blanc', 5, 25000, 'disponible', 2, 'Berline confortable, idéale pour vos déplacements à Saint-Louis/Ndar'),
('SL-5678-BC', 'Hyundai', 'Tucson', 2021, 'Gris', 5, 40000, 'disponible', 3, 'SUV moderne avec climatisation et GPS'),
('SL-9012-CD', 'Renault', 'Logan', 2019, 'Rouge', 5, 18000, 'disponible', 1, 'Citadine économique, parfaite pour la ville'),
('SL-3456-DE', 'Peugeot', '3008', 2022, 'Noir', 5, 45000, 'disponible', 3, 'SUV premium très spacieux'),
('SL-7890-EF', 'Kia', 'Sportage', 2021, 'Bleu', 5, 38000, 'disponible', 3, 'SUV robuste adapté aux routes sénégalaises'),
('SL-2345-FG', 'Dacia', 'Duster', 2020, 'Orange', 5, 30000, 'disponible', 3, 'SUV économique 4x4');
