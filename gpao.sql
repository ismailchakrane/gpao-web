-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 12, 2022 at 10:11 PM
-- Server version: 5.7.36
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gpao`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

DROP TABLE IF EXISTS `activities`;
CREATE TABLE IF NOT EXISTS `activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `creator` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `commande`
--

DROP TABLE IF EXISTS `commande`;
CREATE TABLE IF NOT EXISTS `commande` (
  `idCommande` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `done` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idCommande`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `commande`
--

INSERT INTO `commande` (`idCommande`, `date`, `done`) VALUES
(2, '2022-06-12', 0),
(3, '2022-06-12', 0),
(4, '2022-06-12', 0),
(5, '2022-06-12', 0),
(6, '2022-06-12', 0),
(7, '2022-06-12', 0),
(8, '2022-06-12', 0),
(9, '2022-06-12', 0);

-- --------------------------------------------------------

--
-- Table structure for table `commande_produits`
--

DROP TABLE IF EXISTS `commande_produits`;
CREATE TABLE IF NOT EXISTS `commande_produits` (
  `idCommande` int(11) NOT NULL,
  `idProduit` varchar(11) NOT NULL,
  `quantite` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `commande_produits`
--

INSERT INTO `commande_produits` (`idCommande`, `idProduit`, `quantite`) VALUES
(2, 'p1', 15),
(4, 'P1', 15),
(5, 'P1', 3),
(6, 'P1', 5);

-- --------------------------------------------------------

--
-- Table structure for table `employe`
--

DROP TABLE IF EXISTS `employe`;
CREATE TABLE IF NOT EXISTS `employe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `date_embauche` date NOT NULL,
  `sexe` varchar(1) NOT NULL,
  `role` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employe`
--

INSERT INTO `employe` (`id`, `email`, `password`, `nom`, `prenom`, `date_embauche`, `sexe`, `role`) VALUES
(16, 'ingenieur@gmail.com', 'ingenieur', 'AIT BOURHIM', 'Hamza', '2022-06-15', 'M', 'ingenieur'),
(17, 'admin@gmail.com', 'admin', 'Chakrane', 'Ismail', '2022-06-01', 'M', 'admin'),
(18, 'respcmd@gmail.com', 'respcmd', 'Chakrane2', 'Ismail', '2022-06-08', 'H', 'responsable de commandes'),
(19, 'chefdeproduction@gmail.com', 'chefdeproduction', 'ALBATAL', 'Abdellah', '2016-05-08', 'F', 'chef de production');

-- --------------------------------------------------------

--
-- Table structure for table `fabrication`
--

DROP TABLE IF EXISTS `fabrication`;
CREATE TABLE IF NOT EXISTS `fabrication` (
  `id_product` varchar(30) NOT NULL,
  `dateDebut` date NOT NULL,
  `dateFin` date NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `fabrication`
--

INSERT INTO `fabrication` (`id_product`, `dateDebut`, `dateFin`, `quantity`) VALUES
('p2', '2022-06-09', '2022-06-15', 14),
('p1', '2022-06-02', '2022-06-09', 10);

-- --------------------------------------------------------

--
-- Table structure for table `materials`
--

DROP TABLE IF EXISTS `materials`;
CREATE TABLE IF NOT EXISTS `materials` (
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `dateCreation` date DEFAULT NULL,
  `creator` int(11) NOT NULL,
  `quantite` int(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `materials`
--

INSERT INTO `materials` (`code`, `name`, `description`, `dateCreation`, `creator`, `quantite`) VALUES
('m3', 'matiere 3', 'm3', '2022-06-11', 11, 0),
('m2', 'MatiÃ¨re 2', 'ceci est une matiere premiere de test', '2022-06-12', 16, 0);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `creator` int(11) NOT NULL,
  `dateE` date NOT NULL,
  `quantite` int(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`code`, `name`, `description`, `creator`, `dateE`, `quantite`) VALUES
('P1', 'produit 1', 'ceci est un produit de test du produit 1', 16, '2022-06-12', 20);

-- --------------------------------------------------------

--
-- Table structure for table `product_materials`
--

DROP TABLE IF EXISTS `product_materials`;
CREATE TABLE IF NOT EXISTS `product_materials` (
  `id_product` varchar(255) NOT NULL,
  `id_material` varchar(11) NOT NULL,
  `quantity` double NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product_materials`
--

INSERT INTO `product_materials` (`id_product`, `id_material`, `quantity`) VALUES
('P1', 'm3', 112);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
