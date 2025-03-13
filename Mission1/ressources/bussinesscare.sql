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
-- Structure de la table `activity`
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
  `date_creation` datetime DEFAULT NULL
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
  `id_collaborateur` int(11) DEFAULT NULL
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
  `contact_person` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `activity`
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
-- AUTO_INCREMENT pour la table `activity`
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
-- Contraintes pour la table `activity`
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

INSERT INTO societe (societe_id, nom, adresse, email, contact_person) VALUES (1, 'Société 1', 'Adresse 1', 'societe@example.com', 'Contact Person');
INSERT INTO admin (admin_id, username, password, token, expiration) VALUES (1, 'root@root.com', '3c534fd5e3dce4a0a207354c5a41a4670490f1661aea86d0db72915b939346a5', NULL, NULL);
INSERT INTO association (association_id, name, description) VALUES (1, 'Association 1', 'Description de l\'association 1');
INSERT INTO devis (devis_id, date_debut, date_fin, statut, montant, is_contract, id_societe) VALUES (1, '2025-01-01', '2025-12-31', 'En cours', 1000.00, 1, 1);
INSERT INTO prestataire (prestataire_id, email, nom, prenom, type, description, tarif, date_debut_disponibilite, date_fin_disponibilite, est_candidat, password) VALUES (1, 'prestataire@example.com', 'Nom', 'Prénom', 'Type', 'Description', 100.00, '2025-01-01', '2025-12-31', 1, 'password');
INSERT INTO facture (facture_id, date_emission, montant, statut, id_devis, id_prestataire) VALUES (1, '2025-01-01', 1000.00, 'Payée', 1, 1);
INSERT INTO autre_frais (autre_frais_id, nom, montant, id_facture) VALUES (1, 'Frais 1', 100.00, 1);
INSERT INTO chatbot (question_id, question, reponse) VALUES (1, 'Question 1', 'Réponse 1');
INSERT INTO collaborateur (collaborateur_id, nom, prenom, username, role, email, password, telephone, id_societe, date_creation) VALUES (1, 'Nom', 'Prénom', 'username', 'employe', 'email@example.com', 'password', '1234567890', 1, NOW());
INSERT INTO discute_dans (id_salon, id_collaborateur) VALUES (1, 1);
INSERT INTO evaluation (evaluation_id, note, commentaire, id_collaborateur) VALUES (1, 5, 'Très bien', 1);
INSERT INTO evenements (evenement_id, nom, date, lieu, type) VALUES (1, 'Événement 1', '2025-01-01', 'Lieu 1', 'Type 1');
INSERT INTO note_prestataire (note_prestataire_id, id_prestataire, id_evaluation) VALUES (1, 1, 1);
INSERT INTO participe_activite (id_activite, id_collaborateur) VALUES (1, 1);
INSERT INTO participe_association (id_association, id_collaborateur) VALUES (1, 1);
INSERT INTO participe_evenement (id_evenement, id_collaborateur) VALUES (1, 1);
INSERT INTO salon (salon_id, nom, description) VALUES (1, 'Salon 1', 'Description du salon 1');
INSERT INTO signalement (signalement_id, probleme, id_societe) VALUES (1, 'Problème 1', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
