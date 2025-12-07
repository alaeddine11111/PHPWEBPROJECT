-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : sam. 15 nov. 2025 à 15:27
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestion_stocke`
--

-- --------------------------------------------------------

--
-- Structure de la table `inventories`
--

DROP TABLE IF EXISTS `inventories`;
CREATE TABLE IF NOT EXISTS `inventories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `supplier_name` varchar(150) NOT NULL,
  `contact_person` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `products_count` int DEFAULT '0',
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `inventories`
--

INSERT INTO `inventories` (`id`, `supplier_name`, `contact_person`, `email`, `phone`, `products_count`, `status`, `created_at`) VALUES
(1, 'Global Tech Supplies', 'Jane Doe', 'jane.doe@gts.com', '(123) 456-7890', 42, 'Active', '2025-11-15 12:45:57'),
(2, 'Office Essentials Inc.', 'John Smith', 'j.smith@oei.com', '(987) 654-3210', 15, 'Active', '2025-11-15 12:45:57'),
(3, 'Component Creators', 'Emily White', 'emily@cc.dev', '(555) 123-4567', 112, 'Active', '2025-11-15 12:45:57'),
(4, 'Paper & Print Co.', 'Michael Brown', 'mike.b@ppc.com', '(555) 987-6543', 8, 'Inactive', '2025-11-15 12:45:57'),
(5, 'Innovate Solutions', 'Sarah Green', 'sarah.g@innovate.com', '(444) 555-6666', 76, 'Active', '2025-11-15 12:45:57');

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` varchar(10) NOT NULL,
  `type` varchar(20) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `date_order` date DEFAULT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `items_count` int DEFAULT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `orders`
--

INSERT INTO `orders` (`order_id`, `type`, `status`, `date_order`, `customer_name`, `items_count`) VALUES
('#10524', 'Incoming', 'Shipped', '2023-10-26', 'Global Imports Inc.', 15),
('#10523', 'Outgoing', 'Processing', '2023-10-26', 'Jane Doe', 5),
('#10522', 'Outgoing', 'Delivered', '2023-10-25', 'John Smith', 2),
('#10521', 'Incoming', 'Pending', '2023-10-25', 'Supplier Co.', 120),
('#10520', 'Outgoing', 'Cancelled', '2023-10-24', 'Emily White', 8);

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `product_id` int NOT NULL AUTO_INCREMENT,
  `product_name` varchar(100) NOT NULL,
  `category` varchar(100) NOT NULL,
  `quantity` int NOT NULL,
  `location` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `last_updates` date NOT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `category`, `quantity`, `location`, `status`, `last_updates`, `product_image`) VALUES
(1, 'classic t-shirt', 'clothings', 150, 'warhouse A', 'in stock', '2025-11-19', 'https://share.google/images/xWRG0gZK2KWEsqHQ0'),
(2, 'wirless headphones', 'electronics', 54, 'warhouse B', 'in stock', '2025-10-09', 'https://share.google/images/i7L8IHdyb95OwCFBj'),
(3, 'cermaic coffe mug', 'homeware', 0, 'warhouse A', 'out of stock', '2025-11-18', 'https://share.google/images/3jguKQpHVQmOxwQN0'),
(4, 'yoga mat', 'sports', 10, 'warhouse C', 'low stock', '2025-10-21', 'https://share.google/images/M1T6FxluTfTc0ZQ2F');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
