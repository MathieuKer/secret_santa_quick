-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : lun. 04 déc. 2023 à 14:56
-- Version du serveur : 5.7.39
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
-- Structure de la table `config`
--

CREATE TABLE `config` (
  `id` int(11) NOT NULL,
  `config_name` varchar(300) NOT NULL,
  `config_value` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `config`
--

INSERT INTO `config` (`id`, `config_name`, `config_value`) VALUES
(1, 'is_finished', 'Y');

-- --------------------------------------------------------

--
-- Structure de la table `famille`
--

CREATE TABLE `famille` (
  `id_famille` int(11) NOT NULL,
  `nom_famille` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `famille`
--

INSERT INTO `famille` (`id_famille`, `nom_famille`) VALUES
(75, 'duhem - xavier'),
(77, 'Les vénérables'),
(73, 'Keromnes'),
(74, 'Duhem');

-- --------------------------------------------------------

--
-- Structure de la table `personne`
--

CREATE TABLE `personne` (
  `id_personne` int(11) NOT NULL,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_famille` int(11) DEFAULT NULL,
  `email` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `personne`
--

INSERT INTO `personne` (`id_personne`, `nom`, `id_famille`, `email`) VALUES
(131, 'Audrey', 74, 'mathieu.kero@gmail.com'),
(132, 'Angèle', 74, 'mathieu.kero@gmail.com'),
(130, 'Maman', 73, 'mathieu.kero@gmail.com'),
(122, 'Mathieu', 73, 'mathieu.kero@gmail.com'),
(123, 'Solène', 74, 'mathieu.enix@gmail.com'),
(125, 'xavier', 75, 'mathieu.kero@gmail.com'),
(126, 'blanche', 75, 'mathieu.kero@gmail.com'),
(127, 'Aliénor', 75, 'mathieu.kero@gmail.com'),
(128, 'Sixtime', 75, 'mathieu.kero@gmail.com'),
(129, 'Pauline', 73, 'mathieu.kero@gmail.com'),
(133, 'Fred', 74, 'mathieu.kero@gmail.com'),
(134, 'Corine', 74, 'mathieu.kero@gmail.com'),
(135, 'Mami', 77, 'mathieu.kero@gmail.com'),
(136, 'Papi', 77, 'mathieu.kero@gmail.com'),
(138, 'Aurore', 77, 'mathieu.kero@gmail.com');

-- --------------------------------------------------------

--
-- Structure de la table `relation_secret_santa`
--

CREATE TABLE `relation_secret_santa` (
  `id_relation` int(11) NOT NULL,
  `id_giver` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_receiver` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `relation_secret_santa`
--

INSERT INTO `relation_secret_santa` (`id_relation`, `id_giver`, `id_receiver`) VALUES
(1, 'Aurore', 'Pauline'),
(2, 'Pauline', 'Mami'),
(3, 'Mami', 'Maman'),
(4, 'Maman', 'Papi'),
(5, 'Papi', 'Mathieu'),
(6, 'Mathieu', 'Sixtime'),
(7, 'Sixtime', 'Solène'),
(8, 'Solène', 'xavier'),
(9, 'xavier', 'Corine'),
(10, 'Corine', 'Aliénor'),
(11, 'Aliénor', 'Audrey'),
(12, 'Audrey', 'blanche'),
(13, 'blanche', 'Fred'),
(14, 'Fred', 'Angèle'),
(15, 'Angèle', 'Aurore');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `famille`
--
ALTER TABLE `famille`
  ADD PRIMARY KEY (`id_famille`);

--
-- Index pour la table `personne`
--
ALTER TABLE `personne`
  ADD PRIMARY KEY (`id_personne`),
  ADD KEY `id_famille` (`id_famille`);

--
-- Index pour la table `relation_secret_santa`
--
ALTER TABLE `relation_secret_santa`
  ADD PRIMARY KEY (`id_relation`),
  ADD UNIQUE KEY `id_giver` (`id_giver`,`id_receiver`),
  ADD KEY `id_receiver` (`id_receiver`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `config`
--
ALTER TABLE `config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `famille`
--
ALTER TABLE `famille`
  MODIFY `id_famille` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT pour la table `personne`
--
ALTER TABLE `personne`
  MODIFY `id_personne` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT pour la table `relation_secret_santa`
--
ALTER TABLE `relation_secret_santa`
  MODIFY `id_relation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
