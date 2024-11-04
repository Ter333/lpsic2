-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : dim. 03 nov. 2024 à 23:55
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `projet`
--

-- --------------------------------------------------------

--
-- Structure de la table `consultations`
--

DROP TABLE IF EXISTS `consultations`;
CREATE TABLE IF NOT EXISTS `consultations` (
  `id_consultation` int NOT NULL AUTO_INCREMENT,
  `date_consultation` datetime DEFAULT NULL,
  `id_patient` int DEFAULT NULL,
  `id_medecin` int DEFAULT NULL,
  `probleme` text NOT NULL,
  `solution` text NOT NULL,
  PRIMARY KEY (`id_consultation`),
  KEY `id_patient` (`id_patient`),
  KEY `id_medecin` (`id_medecin`)
) ENGINE=MyISAM AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `consultations`
--

INSERT INTO `consultations` (`id_consultation`, `date_consultation`, `id_patient`, `id_medecin`, `probleme`, `solution`) VALUES
(49, '2024-05-16 10:54:55', 1, 1, 'Grippe', 'Repos, hydratation, médicaments antiviraux si nécessaire.'),
(50, '2024-05-16 11:54:55', 2, 1, ' Angine', 'Antibiotiques (si d\'origine bactérienne), repos, gargarismes.'),
(51, '2024-11-12 12:50:48', 3, 1, 'Allergies saisonnières', 'Antihistaminiques, évitement des allergènes, spray nasal.'),
(52, '2024-10-23 19:08:48', 4, 2, ' Hypertension', 'Modifications du mode de vie (alimentation, exercice), médicaments.'),
(53, '2024-11-13 19:59:48', 5, 2, 'Diabète de type 2	', 'Régime alimentaire équilibré, exercice, médicaments hypoglycémiants.'),
(54, '2024-11-20 16:58:48', 6, 3, ' Asthme', 'Inhalateurs bronchodilatateurs, corticostéroïdes, éducation à l\'autogestion.'),
(55, '2024-11-26 19:48:48', 7, 3, 'Mal de dos', 'Physiothérapie, exercices de renforcement, médicaments anti-inflammatoires.'),
(56, '2024-11-12 23:58:48', 8, 1, ' Migraine', 'Médicaments spécifiques contre la migraine, gestion des déclencheurs.'),
(57, '2024-06-19 19:03:48', 9, 4, 'Dépression', 'Thérapie, médicaments antidépresseurs, soutien psychologique.'),
(58, '2024-10-22 09:58:48', 10, 5, ' Anxiété', 'Thérapie cognitivo-comportementale, médicaments anxiolytiques.'),
(59, '2024-09-17 19:17:48', 11, 4, ' Obésité', 'Régime alimentaire, programme d\'exercice, suivi médical régulier.'),
(60, '2024-11-20 15:58:48', 12, 4, ' Gastrite', 'Médicaments anti-acides, changements alimentaires, éviter l\'alcool.'),
(61, '2024-11-20 09:50:48', 13, 5, ' Constipation', 'Augmentation des fibres alimentaires, hydratation, laxatifs si nécessaire.'),
(62, '2024-11-14 17:30:48', 14, 5, 'Insomnie', 'Hygiène du sommeil, thérapie cognitivo-comportementale, médicaments somnifères.'),
(63, '2024-10-22 07:58:48', 15, 3, 'Acné', 'Crèmes topiques, antibiotiques, médicaments hormonaux.'),
(64, '2024-11-20 02:58:48', 16, 2, 'Infection urinaire', 'Antibiotiques, augmentation de l\'hydratation, hygiène.'),
(65, '2024-11-21 01:04:48', 17, 6, 'Dermatite', 'Crèmes hydratantes, corticostéroïdes topiques, éviter les irritants.'),
(66, '2024-10-22 05:58:48', 18, 1, 'Cholestérol élevé', 'Régime alimentaire faible en graisses, exercices, médicaments hypolipidémiants.'),
(67, '2024-11-26 09:58:00', 19, 2, 'Douleurs articulaires', 'Anti-inflammatoires, physiothérapie, exercices doux.'),
(68, '2024-10-30 20:28:48', 20, 5, 'Rhume', 'Repos, hydratation, médicaments en vente libre pour soulager les symptômes.');

-- --------------------------------------------------------

--
-- Structure de la table `medecins`
--

DROP TABLE IF EXISTS `medecins`;
CREATE TABLE IF NOT EXISTS `medecins` (
  `id_medecin` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) DEFAULT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `email` text NOT NULL,
  PRIMARY KEY (`id_medecin`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `medecins`
--

INSERT INTO `medecins` (`id_medecin`, `nom`, `prenom`, `email`) VALUES
(39, 'bien', 'et ', ''),
(1, 'ALI', 'Arthur', 'ali@arthur.com'),
(2, 'GNANSSA', 'Serge', 'gnanssa@serge.com'),
(3, 'AMIDOU', 'Fridos', 'amidou@fridos.com'),
(4, 'ATHON', 'Emile', 'athon@emile.com'),
(5, 'PANIZIM', 'Gracia', 'panizim@gracia.com'),
(6, 'SAMI', 'Florant', 'sami@florant.com');

-- --------------------------------------------------------

--
-- Structure de la table `patients`
--

DROP TABLE IF EXISTS `patients`;
CREATE TABLE IF NOT EXISTS `patients` (
  `id_patient` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) DEFAULT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `taille` int DEFAULT NULL,
  `poids` int DEFAULT NULL,
  `age` int DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `email` text NOT NULL,
  PRIMARY KEY (`id_patient`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `patients`
--

INSERT INTO `patients` (`id_patient`, `nom`, `prenom`, `taille`, `poids`, `age`, `photo`, `email`) VALUES
(1, 'ALFA', 'Matias', 170, 50, 20, 'image_1.jpg', 'alfa@matias.com'),
(2, 'SONDOU', 'Bruno', 150, 40, 16, 'image_2.jpg', 'sondou@bruno.com'),
(3, 'AKOUNDA', 'Parfait', 152, 51, 17, 'image_3.jpg', 'akounda@parfait.com\r\n'),
(4, 'TCHALLA', 'ABel', 120, 40, 10, 'image_4.jpg', 'tchalla@abel.com'),
(5, 'AGOTRA', 'Julie', 190, 70, 50, 'image_5.jpg', 'agotra@julie.com'),
(6, 'KANAZA', 'Desiré', 140, 58, 23, 'image_6.jpg', 'kanaza@desire.com'),
(7, 'TCHAMIE', 'Damiene', 134, 51, 19, 'image_7.jpg', 'tchamie@damiene.com\r\n'),
(20, 'KALAOU', 'Frederic', 180, 60, 40, 'image_8.jpg', 'kalaou@frederic.com'),
(8, 'WARE', 'Fidèle', 171, 56, 42, 'image_9.jpg', 'ware@fidele.com'),
(9, 'MOGLO', 'Gloria', 165, 56, 37, 'image_10.jpg', 'moglo@gloria.com'),
(10, 'KEZIE', 'Audrey', 163, 59, 60, 'image_11.jpg', 'kezie@audrey.com'),
(11, 'MOYE', 'Issac', 185, 67, 76, 'image_12.jpg', 'moye@issac.com'),
(12, 'DALOUBA', 'Jean', 191, 89, 60, 'image_13.jpg', 'dalouba@jean.com'),
(13, 'AGOTRA', 'Pedro', 182, 67, 45, 'image_14.jpg', 'agotra@pedro.com'),
(14, 'MILO', 'Gracia', 130, 50, 22, 'image_15.jpg', 'milo@gracia.com'),
(15, 'KOLOU', 'Réné', 120, 40, 15, 'image_16.jpg', 'kolou@rene.com'),
(16, 'ATCHOLI', 'Greçon', 110, 30, 7, 'image_17.jpg', 'atcholi@grecon.com'),
(17, 'AWI', 'Joyce', 159, 67, 43, 'image_18.jpg', 'awi@joyce.com'),
(18, 'TAKOUDA', 'Honorine', 121, 34, 7, 'image_19.jpg', 'takouda@honorine.com\r\n'),
(19, 'AGALAWAL', 'William', 197, 90, 78, 'image_20.jpg', 'agalawal@william.net');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id_utilisateur` int NOT NULL AUTO_INCREMENT,
  `email` varchar(100) DEFAULT NULL,
  `mot_de_passe` varchar(255) DEFAULT NULL,
  `role` enum('admin','user') DEFAULT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_utilisateur`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id_utilisateur`, `email`, `mot_de_passe`, `role`, `nom`, `prenom`) VALUES
(14, 'ali@arthur.com', '94419b99b12c11133a4dfeccc3e17885974beb48f7827c48239aabfbcad238d8', 'admin', 'ALI', 'Arthur'),
(15, 'gnanssa@serge.com', '2fa5d31a11d11edf71003db0818d0888ac9dac9391ff61d153fa415d5b12461c', 'admin', 'GNANSSA', 'Serge'),
(16, 'amidou@fridos.com', ' 2c097e50674f56af6d48805257ebb033bf6276b65ac1c95cfe6879688eb493b1', 'admin', 'AMIDOU', 'Fridos'),
(17, 'athon@emile.com', '4804b3836b6365dd2e0e57833c366c49daf83a8d20a2c8a15704ec2d988cae01', 'admin', 'ATHON', 'Emile'),
(18, 'panizim@gracia.com', '5e33d9515c0eee8bbda7c7502def532ce3b4abeba7168bcbe29fdad24a6f4042', 'admin', 'PANIZIM', 'Gracia'),
(19, 'sami@florant.com', '24964a2764be8975c0958a4fa05e4040f9f76adb6f50bb8e290349ecc31f7a5f', 'admin', 'SAMI', 'Florant'),
(20, 'alfa@matias.com', 'a405eba78bf2e6db44ebe0b28bbc9cdc449f9ac990d2029c50a15e6853cfdf20', 'user', 'ALFA', 'Matias'),
(21, 'sondou@bruno.com', '87e8636592cdb37ee488294673f46d6af255fe1f64624499074f55a2646176e1', 'user', 'SONDOU', 'Bruno'),
(22, 'akounda@parfait.com', '15e24d35ed689077d6afebd35f62a10bf997b3a34ed1590ba47e46289c739720', 'user', 'AKOUNDA', 'Parfait'),
(23, 'tchalla@abel.com', '0a2e19f620e62e0e3f1f4e81f805c72fefca9c94c45bee7260d1ba46acc5534f', 'user', 'TCHALLA', 'Abel'),
(24, 'agotra@julie.com', '88f6a18235948c634787ff7582e8fd9b4969c7e1b954937d2aac0c2706cd2485', 'user', 'AGOTRA', 'Julie'),
(25, 'kanaza@desire.com', '0f9b31299c0815f51eb657e8c371003cbcfab5fd2f366d24562705ba601846f5', 'user', 'KANAZA', 'Desiré'),
(26, 'tchamie@damiene.com', '01fa1a7be520f7d22f4255af213dd9db932b5229c3ea940d497bf44bdbbaf3cd', 'user', 'TCHAMIE', 'Damiene'),
(27, 'kalaou@frederic.com', '578843e1bad7a7657f798c9a64a00d3ce919260678cdcb124d74043a2e4ce51a', 'user', 'KALAOU', 'Frederic'),
(28, 'ware@fidele.com', '9a438aa6fc3c51f728e7a79561a61715dd7d05bfbd987b9c1e715cdca6e0720e', 'user', 'WARE', 'Fidèle'),
(29, 'moglo@gloria.com', 'dbc685e424e838878be5cb18fcf1d575e336ba1a2750a91269e7c6dd55408baa', 'user', 'MOGLO', 'Gloria'),
(30, 'kezie@audrey.com', 'd286b17f7671e9ea30a543832be82db6476d24dfc864d5780cb5c1df195b10bd', 'user', 'KEZIE', 'Audrey'),
(31, 'moye@issac.com', 'bc0bba2c4e42b0e42233deaae1ead704c90ab5b9d4c1ff7809e8747415dc8711', 'user', 'MOYE', 'Issac'),
(32, 'dalouba@jean.com', 'b4c1a6db0fb602413aa819d47ee2de0ca6a6dd43c9bd6b51c626cf9a3ad2a4c1', 'user', 'DALOUBA', 'Jean'),
(33, 'agotra@pedro.com', '88f6a18235948c634787ff7582e8fd9b4969c7e1b954937d2aac0c2706cd2485', 'user', 'AGOTRA', 'Pedro'),
(34, 'milo@gracia.com', '13adffea089b50908ce4d41d4ee483ea99cc375350fd664461ee979263fae45b', 'user', 'MILO', 'Gracia'),
(35, 'kolou@rene.com', '4b27e44b7e57d14910ee478ad181dfb90324d11108690a7472fe0bd819c85f04', 'user', 'KOLOU', 'Réné'),
(36, 'atcholi@grecon.com', '28a74fb8abe1c100f5b36427f40afb60aae15067a9d42cf9f3233e6fd796ea0f', 'user', 'ATCHOLI', 'Greçon'),
(37, 'awi@joyce.com', '767a4feb8857a7377b71d969dc7b0dc3812302ebc6dce82798514172143760db', 'user', 'AWI', 'Joyce'),
(38, 'takouda@honorine.com', '11a6c6bbd15e4b4f413c3945fb29bea99ade1285721a6482b5023c9bed0c56fd', 'user', 'TAKOUDA', 'Honorine'),
(39, 'agalawal@william.net', '382e86153821f26f255346a77bc9b7a533ad61f1187ce331ec444da2b72408ab', 'user', 'AGALAWA', 'William');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
