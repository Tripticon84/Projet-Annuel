-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 11 fév. 2025 à 15:20
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `bussinesscare`
--
CREATE DATABASE IF NOT EXISTS `bussinesscare` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `bussinesscare`;

-- --------------------------------------------------------

--
-- Structure de la table `activite`
--

CREATE TABLE `activite` (
  `activite_id` int(11) NOT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `lieu` varchar(255)  DEFAULT NULL,
  `id_devis` int(11) DEFAULT NULL,
  `id_prestataire` int(11)  DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `token` varchar(255) NULL,
  `expiration` datetime NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `association`
--

CREATE TABLE `association` (
  `association_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `autre_frais`
--

CREATE TABLE `autre_frais` (
  `autre_frais_id` int(11) NOT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `montant` decimal(10,2) DEFAULT NULL,
  `date_creation` datetime DEFAULT NULL,
  `id_facture` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `chatbot`
--

CREATE TABLE `chatbot` (
  `question_id` int(11) NOT NULL,
  `question` text DEFAULT NULL,
  `reponse` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `collaborateur`
--

CREATE TABLE `collaborateur` (
  `collaborateur_id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `role` varchar(255) DEFAULT "employe",
  `email` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `id_societe` int(11) DEFAULT NULL,
  `date_creation` datetime DEFAULT NULL,
  `date_activite` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `devis`
--

CREATE TABLE `devis` (
  `devis_id` int(11) NOT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `statut` varchar(255) DEFAULT NULL,
  `montant` decimal(10,2) DEFAULT NULL,
  `is_contract` tinyint(1) DEFAULT NULL,
  `id_societe` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `discute_dans`
--

CREATE TABLE `discute_dans` (
  `id_salon` int(11) NOT NULL,
  `id_collaborateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `evaluation`
--

CREATE TABLE `evaluation` (
  `evaluation_id` int(11) NOT NULL,
  `note` int(11) DEFAULT NULL,
  `commentaire` text DEFAULT NULL,
  `id_collaborateur` int(11) DEFAULT NULL,
  `date_creation` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `evenements`
--

CREATE TABLE `evenements` (
  `evenement_id` int(11) NOT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `lieu` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `facture`
--

CREATE TABLE `facture` (
  `facture_id` int(11) NOT NULL,
  `date_emission` date DEFAULT NULL,
  `montant` decimal(10,2) DEFAULT NULL,
  `statut` varchar(255) DEFAULT NULL,
  `id_devis` int(11) DEFAULT NULL,
  `id_prestataire` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `note_prestataire`
--

CREATE TABLE `note_prestataire` (
  `note_prestataire_id` int(11) NOT NULL,
  `id_prestataire` int(11) DEFAULT NULL,
  `id_evaluation` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `participe_activite`
--

CREATE TABLE `participe_activite` (
  `id_activite` int(11) NOT NULL,
  `id_collaborateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `participe_association`
--

CREATE TABLE `participe_association` (
  `id_association` int(11) NOT NULL,
  `id_collaborateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `participe_evenement`
--

CREATE TABLE `participe_evenement` (
  `id_evenement` int(11) NOT NULL,
  `id_collaborateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `prestataire`
--

CREATE TABLE `prestataire` (
  `prestataire_id` int(11) NOT NULL,
  `email` varchar(255)  NOT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `prenom` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `tarif` decimal(10,2) DEFAULT NULL,
  `date_debut_disponibilite` date DEFAULT NULL,
  `date_fin_disponibilite` date DEFAULT NULL,
  `est_candidat` boolean  NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `salon`
--

CREATE TABLE `salon` (
  `salon_id` int(11) NOT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `signalement`
--

CREATE TABLE `signalement` (
  `signalement_id` int(11) NOT NULL,
  `probleme` text DEFAULT NULL,
  `id_societe` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `societe`
--

CREATE TABLE `societe` (
  `societe_id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `adresse` text DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `date_creation` datetime DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `expiration` datetime NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `activite`
--
ALTER TABLE `activite`
  ADD PRIMARY KEY (`activite_id`),
  ADD KEY `id_devis` (`id_devis`),
  ADD KEY `id_prestataire` (`id_prestataire`);

--
-- Index pour la table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Index pour la table `association`
--
ALTER TABLE `association`
  ADD PRIMARY KEY (`association_id`);

--
-- Index pour la table `autre_frais`
--
ALTER TABLE `autre_frais`
  ADD PRIMARY KEY (`autre_frais_id`),
  ADD KEY `id_facture` (`id_facture`);

--
-- Index pour la table `chatbot`
--
ALTER TABLE `chatbot`
  ADD PRIMARY KEY (`question_id`);

--
-- Index pour la table `collaborateur`
--
ALTER TABLE `collaborateur`
  ADD PRIMARY KEY (`collaborateur_id`),
  ADD KEY `id_societe` (`id_societe`);

--
-- Index pour la table `devis`
--
ALTER TABLE `devis`
  ADD PRIMARY KEY (`devis_id`),
  ADD KEY `id_societe` (`id_societe`);

--
-- Index pour la table `discute_dans`
--
ALTER TABLE `discute_dans`
  ADD PRIMARY KEY (`id_salon`,`id_collaborateur`),
  ADD KEY `id_collaborateur` (`id_collaborateur`);

--
-- Index pour la table `evaluation`
--
ALTER TABLE `evaluation`
  ADD PRIMARY KEY (`evaluation_id`),
  ADD KEY `id_collaborateur` (`id_collaborateur`);

--
-- Index pour la table `evenements`
--
ALTER TABLE `evenements`
  ADD PRIMARY KEY (`evenement_id`);

--
-- Index pour la table `facture`
--
ALTER TABLE `facture`
  ADD PRIMARY KEY (`facture_id`),
  ADD KEY `id_devis` (`id_devis`),
  ADD KEY `id_prestataire` (`id_prestataire`);

--
-- Index pour la table `note_prestataire`
--
ALTER TABLE `note_prestataire`
  ADD PRIMARY KEY (`note_prestataire_id`),
  ADD KEY `id_prestataire` (`id_prestataire`),
  ADD KEY `id_evaluation` (`id_evaluation`);

--
-- Index pour la table `participe_activite`
--
ALTER TABLE `participe_activite`
  ADD PRIMARY KEY (`id_activite`,`id_collaborateur`),
  ADD KEY `id_collaborateur` (`id_collaborateur`);

--
-- Index pour la table `participe_association`
--
ALTER TABLE `participe_association`
  ADD PRIMARY KEY (`id_association`,`id_collaborateur`),
  ADD KEY `id_collaborateur` (`id_collaborateur`);

--
-- Index pour la table `participe_evenement`
--
ALTER TABLE `participe_evenement`
  ADD PRIMARY KEY (`id_evenement`,`id_collaborateur`),
  ADD KEY `id_collaborateur` (`id_collaborateur`);

--
-- Index pour la table `prestataire`
--
ALTER TABLE `prestataire`
  ADD PRIMARY KEY (`prestataire_id`);

--
-- Index pour la table `salon`
--
ALTER TABLE `salon`
  ADD PRIMARY KEY (`salon_id`);

--
-- Index pour la table `signalement`
--
ALTER TABLE `signalement`
  ADD PRIMARY KEY (`signalement_id`),
  ADD KEY `id_societe` (`id_societe`);

--
-- Index pour la table `societe`
--
ALTER TABLE `societe`
  ADD PRIMARY KEY (`societe_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `activite`
--
ALTER TABLE `activite`
  MODIFY `activite_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `association`
--
ALTER TABLE `association`
  MODIFY `association_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `autre_frais`
--
ALTER TABLE `autre_frais`
  MODIFY `autre_frais_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `chatbot`
--
ALTER TABLE `chatbot`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `collaborateur`
--
ALTER TABLE `collaborateur`
  MODIFY `collaborateur_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `devis`
--
ALTER TABLE `devis`
  MODIFY `devis_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `evaluation`
--
ALTER TABLE `evaluation`
  MODIFY `evaluation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `evenements`
--
ALTER TABLE `evenements`
  MODIFY `evenement_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `facture`
--
ALTER TABLE `facture`
  MODIFY `facture_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `note_prestataire`
--
ALTER TABLE `note_prestataire`
  MODIFY `note_prestataire_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `prestataire`
--
ALTER TABLE `prestataire`
  MODIFY `prestataire_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `salon`
--
ALTER TABLE `salon`
  MODIFY `salon_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `signalement`
--
ALTER TABLE `signalement`
  MODIFY `signalement_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `societe`
--
ALTER TABLE `societe`
  MODIFY `societe_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `activite`
--
ALTER TABLE `activite`
  ADD CONSTRAINT `activite_ibfk_1` FOREIGN KEY (`id_devis`) REFERENCES `devis` (`devis_id`),
  ADD CONSTRAINT `activite_ibfk_2` FOREIGN KEY (`id_prestataire`) REFERENCES `prestataire` (`prestataire_id`);


--
-- Contraintes pour la table `autre_frais`
--
ALTER TABLE `autre_frais`
  ADD CONSTRAINT `autre_frais_ibfk_1` FOREIGN KEY (`id_facture`) REFERENCES `facture` (`facture_id`);

--
-- Contraintes pour la table `collaborateur`
--
ALTER TABLE `collaborateur`
  ADD CONSTRAINT `collaborateur_ibfk_1` FOREIGN KEY (`id_societe`) REFERENCES `societe` (`societe_id`);

--
-- Contraintes pour la table `devis`
--
ALTER TABLE `devis`
  ADD CONSTRAINT `devis_ibfk_1` FOREIGN KEY (`id_societe`) REFERENCES `societe` (`societe_id`);

--
-- Contraintes pour la table `discute_dans`
--
ALTER TABLE `discute_dans`
  ADD CONSTRAINT `discute_dans_ibfk_1` FOREIGN KEY (`id_salon`) REFERENCES `salon` (`salon_id`),
  ADD CONSTRAINT `discute_dans_ibfk_2` FOREIGN KEY (`id_collaborateur`) REFERENCES `collaborateur` (`collaborateur_id`);

--
-- Contraintes pour la table `evaluation`
--
ALTER TABLE `evaluation`
  ADD CONSTRAINT `evaluation_ibfk_1` FOREIGN KEY (`id_collaborateur`) REFERENCES `collaborateur` (`collaborateur_id`);

--
-- Contraintes pour la table `facture`
--
ALTER TABLE `facture`
  ADD CONSTRAINT `facture_ibfk_1` FOREIGN KEY (`id_devis`) REFERENCES `devis` (`devis_id`),
  ADD CONSTRAINT `facture_ibfk_2` FOREIGN KEY (`id_prestataire`) REFERENCES `prestataire` (`prestataire_id`);

--
-- Contraintes pour la table `note_prestataire`
--
ALTER TABLE `note_prestataire`
  ADD CONSTRAINT `note_prestataire_ibfk_1` FOREIGN KEY (`id_prestataire`) REFERENCES `prestataire` (`prestataire_id`),
  ADD CONSTRAINT `note_prestataire_ibfk_2` FOREIGN KEY (`id_evaluation`) REFERENCES `evaluation` (`evaluation_id`);

--
-- Contraintes pour la table `participe_activite`
--
ALTER TABLE `participe_activite`
  ADD CONSTRAINT `participe_activite_ibfk_1` FOREIGN KEY (`id_activite`) REFERENCES `activite` (`activite_id`),
  ADD CONSTRAINT `participe_activite_ibfk_2` FOREIGN KEY (`id_collaborateur`) REFERENCES `collaborateur` (`collaborateur_id`);

--
-- Contraintes pour la table `participe_association`
--
ALTER TABLE `participe_association`
  ADD CONSTRAINT `participe_association_ibfk_1` FOREIGN KEY (`id_association`) REFERENCES `association` (`association_id`),
  ADD CONSTRAINT `participe_association_ibfk_2` FOREIGN KEY (`id_collaborateur`) REFERENCES `collaborateur` (`collaborateur_id`);

--
-- Contraintes pour la table `participe_evenement`
--
ALTER TABLE `participe_evenement`
  ADD CONSTRAINT `participe_evenement_ibfk_1` FOREIGN KEY (`id_evenement`) REFERENCES `evenements` (`evenement_id`),
  ADD CONSTRAINT `participe_evenement_ibfk_2` FOREIGN KEY (`id_collaborateur`) REFERENCES `collaborateur` (`collaborateur_id`);

--
-- Contraintes pour la table `signalement`
--
ALTER TABLE `signalement`
  ADD CONSTRAINT `signalement_ibfk_1` FOREIGN KEY (`id_societe`) REFERENCES `societe` (`societe_id`);
COMMIT;

/* Ajout de données */

-- Sociétés (entreprises clients)
INSERT INTO societe (nom, adresse, email, contact_person,telephone,password,date_creation) VALUES
('Renault Group', '13 Quai Alphonse Le Gallo, 92100 Boulogne-Billancourt', 'contact@renault.com', 'Marie Dubois', '0123456789', '3c534fd5e3dce4a0a207354c5a41a4670490f1661aea86d0db72915b939346a5', NOW()),
('AXA Assurances', '25 Avenue Matignon, 75008 Paris', 'entreprises@axa.fr', 'Thomas Moreau', '0234567890', '3c534fd5e3dce4a0a207354c5a41a4670490f1661aea86d0db72915b939346a5', NOW()),
('Carrefour France', '93 Avenue de Paris, 91300 Massy', 'relations@carrefour.com', 'Sophie Lambert', '0345678901', '3c534fd5e3dce4a0a207354c5a41a4670490f1661aea86d0db72915b939346a5', NOW()),
('BNP Paribas', '16 Boulevard des Italiens, 75009 Paris', 'entreprise@bnpparibas.com', 'Philippe Martin', '0456789012', '3c534fd5e3dce4a0a207354c5a41a4670490f1661aea86d0db72915b939346a5', NOW()),
('LOréal Paris', '14 Rue Royale, 75008 Paris', 'contact@loreal.fr', 'Claire Lefevre', '0567890123', '3c534fd5e3dce4a0a207354c5a41a4670490f1661aea86d0db72915b939346a5', NOW());

-- Administrateurs système
INSERT INTO admin (username, password, token, expiration) VALUES
('admin', '3c534fd5e3dce4a0a207354c5a41a4670490f1661aea86d0db72915b939346a5', NULL, NULL),
('superadmin', '3c534fd5e3dce4a0a207354c5a41a4670490f1661aea86d0db72915b939346a5', NULL, NULL);

-- Associations partenaires
INSERT INTO association (name, description) VALUES
('Les Restos du Cœur', 'Association caritative d\'aide alimentaire et d\'insertion sociale'),
('Médecins Sans Frontières', 'Organisation médicale humanitaire internationale'),
('La Croix-Rouge française', 'Association d\'aide humanitaire qui vient en aide aux personnes en difficulté'),
('WWF France', 'Organisation non gouvernementale de protection de l\'environnement');

-- Collaborateurs des entreprises
INSERT INTO collaborateur (nom, prenom, username, role, email, password, telephone, id_societe, date_creation, date_activite) VALUES
('Leroy', 'Jean', 'jleroy', 'manager', 'j.leroy@renault.com', '3c534fd5e3dce4a0a207354c5a41a4670490f1661aea86d0db72915b939346a5', '0612345678', 1, NOW(), '2025-01-05'),
('Dupont', 'Marie', 'mdupont', 'responsable_rh', 'm.dupont@renault.com', '3c534fd5e3dce4a0a207354c5a41a4670490f1661aea86d0db72915b939346a5', '0623456789', 1, NOW(), '2025-01-15'),
('Bernard', 'Sylvie', 'sbernard', 'employe', 's.bernard@axa.fr','3c534fd5e3dce4a0a207354c5a41a4670490f1661aea86d0db72915b939346a5', '0734567890', 2, NOW(), '2025-02-01'),
('Petit', 'Thomas', 'tpetit', 'manager', 't.petit@axa.fr', '3c534fd5e3dce4a0a207354c5a41a4670490f1661aea86d0db72915b939346a5', '0745678901', 2, NOW(), '2025-02-10'),
('Martin', 'Caroline', 'cmartin', 'employe', 'c.martin@carrefour.com', '3c534fd5e3dce4a0a207354c5a41a4670490f1661aea86d0db72915b939346a5', '0656789012', 3, NOW(), '2025-02-20'),
('Durand', 'Michel', 'mdurand', 'directeur', 'm.durand@bnpparibas.com', '3c534fd5e3dce4a0a207354c5a41a4670490f1661aea86d0db72915b939346a5', '0667890123', 4, NOW(), '2025-03-01'),
('Lefebvre', 'Emma', 'elefebvre', 'employe', 'e.lefebvre@loreal.fr', '3c534fd5e3dce4a0a207354c5a41a4670490f1661aea86d0db72915b939346a5', '0678901234', 5, NOW(), '2025-03-15');

-- Devis pour différentes entreprises
INSERT INTO devis (date_debut, date_fin, statut, montant, is_contract, id_societe) VALUES
('2025-03-15', '2025-06-15', 'En cours', 7500.00, 1, 1),
('2025-04-01', '2025-10-31', 'En attente', 12800.00, 0, 2),
('2025-02-20', '2025-05-20', 'Accepté', 5200.00, 1, 3),
('2025-05-01', '2025-08-31', 'En cours', 9750.00, 1, 4),
('2025-03-10', '2025-12-31', 'En attente', 15300.00, 0, 5);

-- Prestataires de services
INSERT INTO prestataire (email, nom, prenom, type, description, tarif, date_debut_disponibilite, date_fin_disponibilite, est_candidat, password) VALUES
('marc.dubois@gmail.com', 'Dubois', 'Marc', 'Coach bien-être', 'Coach certifié spécialisé en gestion du stress et bien-être au travail', 350.00, '2025-01-01', '2025-12-31', 0, '3c534fd5e3dce4a0a207354c5a41a4670490f1661aea86d0db72915b939346a5'),
('sophie.morel@outlook.fr', 'Morel', 'Sophie', 'Psychologue', 'Psychologue du travail avec 10 ans d\'expérience', 280.00, '2025-02-15', '2025-11-30', 0, '3c534fd5e3dce4a0a207354c5a41a4670490f1661aea86d0db72915b939346a5'),
('julien.roux@formateurs.fr', 'Roux', 'Julien', 'Formateur', 'Formateur en management et communication d\'équipe', 320.00, '2025-01-15', '2025-12-15', 0, '3c534fd5e3dce4a0a207354c5a41a4670490f1661aea86d0db72915b939346a5'),
('celine.blanc@gmail.com', 'Blanc', 'Céline', 'Nutritionniste', 'Nutritionniste spécialisée en alimentation au travail', 250.00, '2025-03-01', '2025-09-30', 1, '3c534fd5e3dce4a0a207354c5a41a4670490f1661aea86d0db72915b939346a5'),
('pierre.lemoine@coach-sport.fr', 'Lemoine', 'Pierre', 'Coach sportif', 'Coach sportif spécialisé dans les exercices de bureau', 220.00, '2025-01-01', '2025-12-31', 0, '3c534fd5e3dce4a0a207354c5a41a4670490f1661aea86d0db72915b939346a5');

-- Factures liées aux devis et prestataires
INSERT INTO facture (date_emission, montant, statut, id_devis, id_prestataire) VALUES
('2025-04-15', 2500.00, 'Payée', 1, 1),
('2025-05-01', 3200.00, 'En attente', 2, 2),
('2025-03-20', 1800.00, 'Payée', 3, 3),
('2025-05-15', 2400.00, 'En cours', 4, 5),
('2025-04-10', 3000.00, 'En attente', 5, 4);

-- Autres frais liés aux factures
INSERT INTO autre_frais (nom, montant, id_facture) VALUES
('Matériel pédagogique', 350.00, 1),
('Frais de déplacement', 120.00, 1),
('Location de salle', 500.00, 2),
('Fournitures pour ateliers', 280.00, 3),
('Catering pour session de groupe', 420.00, 4),
('Documentation personnalisée', 150.00, 5);

-- Questions fréquentes pour le chatbot
INSERT INTO chatbot (question, reponse) VALUES
('Comment réserver une activité ?', 'Pour réserver une activité, connectez-vous à votre espace collaborateur, puis naviguez vers la section "Activités". Sélectionnez l\'activité qui vous intéresse et cliquez sur "Réserver".'),
('Quels types d\'activités sont disponibles ?', 'Nous proposons divers types d\'activités pour le bien-être: coaching personnel, ateliers de gestion du stress, séances de yoga, consultations nutritionnelles, team building, et plus encore.'),
('Comment annuler ma participation à une activité ?', 'Pour annuler votre participation, rendez-vous dans "Mes activités", sélectionnez l\'activité concernée et cliquez sur "Annuler ma participation". L\'annulation est possible jusqu\'à 48h avant l\'activité.'),
('Comment évaluer un prestataire ?', 'Après avoir participé à une activité, vous recevrez automatiquement une invitation à évaluer le prestataire. Vous pouvez également vous rendre dans "Mes activités passées" et cliquer sur "Évaluer".'),
('Puis-je proposer une nouvelle activité ?', 'Oui, vous pouvez soumettre vos idées d\'activités à votre responsable RH ou via le formulaire "Suggestions" dans votre espace collaborateur.');

-- Salons de discussion
INSERT INTO salon (nom, description) VALUES
('Bien-être général', 'Discussions sur tous les sujets liés au bien-être en entreprise'),
('Nutrition et santé', 'Échanges sur l\'alimentation saine et les conseils santé'),
('Activité physique', 'Partage de conseils et expériences sur le sport et l\'exercice'),
('Méditation et relaxation', 'Discussions sur les techniques de détente et de méditation'),
('Équilibre vie pro/perso', 'Échanges sur la conciliation vie professionnelle et personnelle');

-- Activités bien-être
INSERT INTO activite (nom, type, date, lieu, id_devis, id_prestataire) VALUES
('Atelier gestion du stress', 'Atelier collectif', '2025-04-20', 'Salle de conférence A - Siège Renault', 1, 1),
('Séances de yoga', 'Cours collectif', '2025-05-10', 'Salle de détente - AXA', 2, 5),
('Consultation nutrition', 'Entretien individuel', '2025-03-25', 'Bureau 302 - Carrefour Massy', 3, 4),
('Team building nature', 'Sortie d\'équipe', '2025-06-05', 'Forêt de Fontainebleau', 4, 3),
('Atelier sommeil', 'Conférence', '2025-04-15', 'Auditorium - L\'Oréal Paris', 5, 2);

-- Évaluations des collaborateurs
INSERT INTO evaluation (note, commentaire, id_collaborateur) VALUES
(5, 'Excellent atelier, très instructif et pratique. Je me sens mieux équipé pour gérer mon stress quotidien.', 1),
(4, 'Bonne séance, dynamique et adaptée à tous les niveaux. Quelques exercices supplémentaires auraient été appréciés.', 3),
(5, 'Consultation très personnalisée, la nutritionniste a su répondre précisément à mes besoins.', 5),
(3, 'Activité intéressante mais trop courte pour en tirer pleinement profit.', 2),
(4, 'Conférence enrichissante avec de nombreux conseils pratiques à appliquer au quotidien.', 7);

-- Événements entreprise
INSERT INTO evenements (nom, date, lieu, type) VALUES
('Journée bien-être', '2025-05-25', 'Campus Renault - Boulogne-Billancourt', 'Journée thématique'),
('Semaine de la santé', '2025-06-15', 'Siège AXA - Paris', 'Semaine spéciale'),
('Challenge pas quotidiens', '2025-04-01', 'Toutes les agences Carrefour', 'Challenge d\'équipe'),
('Conférence Équilibre de vie', '2025-07-10', 'Tour BNP - La Défense', 'Conférence'),
('Ateliers détente', '2025-05-05', 'Centre L\'Oréal - Paris', 'Ateliers pratiques');

-- Notes des prestataires
INSERT INTO note_prestataire (id_prestataire, id_evaluation) VALUES
(1, 1),
(5, 2),
(4, 3),
(3, 4),
(2, 5);

-- Signalements de problèmes
INSERT INTO signalement (probleme, id_societe) VALUES
('Difficulté d\'accès à la plateforme de réservation des activités', 1),
('Prestataire absent lors d\'une séance programmée', 2),
('Matériel insuffisant pour l\'atelier nutrition', 3),
('Problème de coordination entre les différents services', 4),
('Demande d\'activités plus adaptées aux horaires des équipes', 5);

-- Relations salon-collaborateurs (qui discute dans quel salon)
INSERT INTO discute_dans (id_salon, id_collaborateur) VALUES
(1, 1), (1, 2), (1, 3),
(1, 4), (2, 2), (2, 5),
(2, 7), (3, 1), (3, 3),
(3, 6), (4, 4), (4, 5),
(4, 7), (5, 1), (5, 2),
(5, 3), (5, 4), (5, 5);

-- Participations aux activités
INSERT INTO participe_activite (id_activite, id_collaborateur) VALUES
(1, 1), (1, 2),
(2, 3), (2, 4),
(3, 5), (4, 6),
(4, 1), (4, 2),
(5, 7);

-- Participations aux associations
INSERT INTO participe_association (id_association, id_collaborateur) VALUES
(1, 1), (1, 3),
(1, 5), (2, 2),
(2, 4), (3, 6),
(3, 7), (4, 3),
(4, 5), (4, 7);

-- Participations aux événements
INSERT INTO participe_evenement (id_evenement, id_collaborateur) VALUES
(1, 1), (1, 2),
(2, 3), (2, 4),
(3, 5), (3, 6),
(4, 6), (4, 7),
(5, 1), (5, 3),
(5, 7);
