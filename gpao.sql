create database 'gpao';

DROP TABLE IF EXISTS `commande`;
CREATE TABLE IF NOT EXISTS `commande` (
  `idCommande` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `done` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idCommande`)
);



DROP TABLE IF EXISTS `commande_produits`;
CREATE TABLE IF NOT EXISTS `commande_produits` (
  `idCommande` int(11) NOT NULL,
  `idProduit` varchar(11) NOT NULL,
  `quantite` int(11) NOT NULL
);


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
) ;

-

DROP TABLE IF EXISTS `fabrication`;
CREATE TABLE IF NOT EXISTS `fabrication` (
  `id_product` varchar(30) NOT NULL,
  `dateDebut` date NOT NULL,
  `dateFin` date NOT NULL,
  `quantity` int(11) NOT NULL
);


DROP TABLE IF EXISTS `materials`;
CREATE TABLE IF NOT EXISTS `materials` (
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `dateCreation` date DEFAULT NULL,
  `creator` int(11) NOT NULL,
  `quantite` int(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`code`)
);


DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `creator` int(11) NOT NULL,
  `dateE` date NOT NULL,
  `quantite` int(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`code`)
);


DROP TABLE IF EXISTS `product_materials`;
CREATE TABLE IF NOT EXISTS `product_materials` (
  `id_product` varchar(255) NOT NULL,
  `id_material` varchar(11) NOT NULL,
  `quantity` double NOT NULL
) ;
COMMIT;
