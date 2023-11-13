-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 13 nov. 2023 à 01:04
-- Version du serveur : 8.0.31
-- Version de PHP : 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `secret_santa`
--

-- --------------------------------------------------------

--
-- Structure de la table `famille`
--

DROP TABLE IF EXISTS `famille`;
CREATE TABLE IF NOT EXISTS `famille` (
  `id_famille` int NOT NULL AUTO_INCREMENT,
  `nom_famille` varchar(50) NOT NULL,
  PRIMARY KEY (`id_famille`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `personne`
--

DROP TABLE IF EXISTS `personne`;
CREATE TABLE IF NOT EXISTS `personne` (
  `id_personne` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `id_famille` int DEFAULT NULL,
  PRIMARY KEY (`id_personne`),
  KEY `id_famille` (`id_famille`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `personne`
--

INSERT INTO `personne` (`id_personne`, `nom`, `id_famille`) VALUES
(16, 'apolline', NULL),
(14, 'Jonh', NULL),
(15, 'sofiane', NULL),
(17, 'alex', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `relation_secret_santa`
--

DROP TABLE IF EXISTS `relation_secret_santa`;
CREATE TABLE IF NOT EXISTS `relation_secret_santa` (
  `id_relation` int NOT NULL AUTO_INCREMENT,
  `id_giver` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `id_receiver` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_relation`),
  UNIQUE KEY `id_giver` (`id_giver`,`id_receiver`),
  KEY `id_receiver` (`id_receiver`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `relation_secret_santa`
--

INSERT INTO `relation_secret_santa` (`id_relation`, `id_giver`, `id_receiver`) VALUES
(1, 'sofiane', 'apolline'),
(2, 'apolline', 'Jonh'),
(3, 'Jonh', 'alex'),
(4, 'alex', 'sofiane');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
