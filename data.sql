-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 13, 2022 at 01:19 PM
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

--
-- Dumping data for table `commande`
--

INSERT INTO `commande` (`idCommande`, `date`, `done`) VALUES
(15, '2022-06-13', 0),
(14, '2022-06-13', 0),
(13, '2022-06-13', 1);

--
-- Dumping data for table `commande_produits`
--

INSERT INTO `commande_produits` (`idCommande`, `idProduit`, `quantite`) VALUES
(13, 'P1', 12),
(13, 'p3', 4),
(14, 'p2', 12),
(14, 'p4', 2),
(15, 'P1', 12),
(15, 'p2', 124);

--
-- Dumping data for table `employe`
--

INSERT INTO `employe` (`id`, `email`, `password`, `nom`, `prenom`, `date_embauche`, `sexe`, `role`) VALUES
(18, 'admin@gmail.com', 'admin', 'Chakrane', 'Ismail', '2022-06-08', 'H', 'admin'),
(20, 'ingenieur@gmail.com', 'ingenieur', 'Ait Bourhim', 'Hamza', '2022-05-30', 'H', 'ingenieur'),
(21, 'stock@gmail.com', 'stock', 'Hadiche', 'Saad', '2022-06-09', 'H', 'responsable de commandes'),
(22, 'production@gmail.com', 'production', 'Lachguer', 'Soufiane', '2022-06-09', 'H', 'chef de production');

--
-- Dumping data for table `fabrication`
--

INSERT INTO `fabrication` (`id_product`, `dateDebut`, `dateFin`, `quantity`) VALUES
('p2', '2022-06-12', '2022-06-14', 2);

--
-- Dumping data for table `materials`
--

INSERT INTO `materials` (`code`, `name`, `description`, `dateCreation`, `creator`, `quantite`) VALUES
('m2', 'matiere 2', 'ceci est une matiÃ¨re de test 2', '2022-06-13', 20, 120),
('m1', 'matiÃ¨re 1', 'ceci est une matiÃ¨re de test 1', '2022-06-13', 20, 0),
('m3', 'matiere 3', 'ceci est une matiÃ¨re de test 3', '2022-06-13', 20, 28),
('m4', 'matiere 4', 'ceci est un produit de test 4', '2022-06-13', 20, 1961),
('m5', 'matiere 5', 'ceci est un produit de test 5', '2022-06-13', 20, 40),
('m6', 'matiere 6', 'ceci est une matiere premiere de test 6', '2022-06-13', 20, 0);

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`code`, `name`, `description`, `creator`, `dateE`, `quantite`) VALUES
('P1', 'Produit 1', 'description du produit 1', 20, '2022-06-13', 2),
('p2', 'produit 2 ', 'ceci est un produit de test 2', 20, '2022-06-13', 2),
('p3', 'produit 3', 'ceci est un produit de test 3', 20, '2022-06-13', 0),
('p4', 'produit 4', 'ceci est un produit de test 4', 20, '2022-06-13', 2);

--
-- Dumping data for table `product_materials`
--

INSERT INTO `product_materials` (`id_product`, `id_material`, `quantity`) VALUES
('P1', 'm3', 13),
('p2', 'm1', 14),
('p2', 'm3', 2),
('p3', 'm6', 19),
('p3', 'm5', 3),
('p4', 'm4', 13),
('p4', 'm3', 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
