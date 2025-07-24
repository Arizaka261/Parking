-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 08 juil. 2025 à 11:51
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
-- Base de données : `parking_management`
--

DELIMITER $$
--
-- Procédures
--
DROP PROCEDURE IF EXISTS `InitializeClientPayments`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `InitializeClientPayments` (IN `p_client_id` INT, IN `p_year` INT)   BEGIN
    DECLARE i INT DEFAULT 1;
    DECLARE client_fee DECIMAL(10,2);
    
    -- Récupérer le tarif mensuel du client
    SELECT monthly_fee INTO client_fee FROM clients WHERE id = p_client_id;
    
    -- Créer 12 enregistrements de paiement pour l'année
    WHILE i <= 12 DO
        INSERT IGNORE INTO payments (client_id, year, month, amount, is_paid) 
        VALUES (p_client_id, p_year, i, client_fee, FALSE);
        SET i = i + 1;
    END WHILE;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

DROP TABLE IF EXISTS `clients`;
CREATE TABLE IF NOT EXISTS `clients` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `car_model` varchar(100) DEFAULT NULL,
  `plate_number` varchar(20) NOT NULL,
  `monthly_fee` decimal(10,2) NOT NULL DEFAULT '200000.00',
  `parking_spot` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `plate_number` (`plate_number`),
  KEY `idx_clients_plate` (`plate_number`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `clients`
--

INSERT INTO `clients` (`id`, `name`, `phone`, `car_model`, `plate_number`, `monthly_fee`, `parking_spot`, `created_at`, `updated_at`) VALUES
(2, 'Zakarie', '034 01 437 77', 'Hyundai Getz', '8934 TBN', 30000.00, '1', '2025-07-08 11:20:43', '2025-07-08 11:21:30'),
(3, 'Nidah', '038 42 011 11', 'Ford Ranger', '5874 TCA', 30000.00, '2', '2025-07-08 11:24:03', '2025-07-08 11:24:03'),
(4, 'Sonia', '034 15 415 15', 'Fiat Panda', '2703 TP', 30000.00, '3', '2025-07-08 11:24:37', '2025-07-08 11:24:37');

--
-- Déclencheurs `clients`
--
DROP TRIGGER IF EXISTS `after_client_insert`;
DELIMITER $$
CREATE TRIGGER `after_client_insert` AFTER INSERT ON `clients` FOR EACH ROW BEGIN
    CALL InitializeClientPayments(NEW.id, YEAR(NOW()));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE IF NOT EXISTS `payments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `client_id` int NOT NULL,
  `year` int NOT NULL,
  `month` int NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `is_paid` tinyint(1) DEFAULT '0',
  `payment_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_client_month` (`client_id`,`year`,`month`),
  KEY `idx_payments_client_year` (`client_id`,`year`),
  KEY `idx_payments_year_month` (`year`,`month`)
) ;

--
-- Déchargement des données de la table `payments`
--

INSERT INTO `payments` (`id`, `client_id`, `year`, `month`, `amount`, `is_paid`, `payment_date`, `created_at`, `updated_at`) VALUES
(1, 2, 2025, 1, 20000.00, 0, NULL, '2025-07-08 11:20:43', '2025-07-08 11:20:43'),
(2, 2, 2025, 2, 20000.00, 0, NULL, '2025-07-08 11:20:43', '2025-07-08 11:20:43'),
(3, 2, 2025, 3, 20000.00, 0, NULL, '2025-07-08 11:20:43', '2025-07-08 11:20:43'),
(4, 2, 2025, 4, 20000.00, 0, NULL, '2025-07-08 11:20:43', '2025-07-08 11:20:43'),
(5, 2, 2025, 5, 20000.00, 0, NULL, '2025-07-08 11:20:43', '2025-07-08 11:20:43'),
(6, 2, 2025, 6, 20000.00, 0, NULL, '2025-07-08 11:20:43', '2025-07-08 11:20:43'),
(7, 2, 2025, 7, 20000.00, 1, '2025-07-08 11:23:12', '2025-07-08 11:20:43', '2025-07-08 11:23:12'),
(8, 2, 2025, 8, 20000.00, 0, NULL, '2025-07-08 11:20:43', '2025-07-08 11:20:43'),
(9, 2, 2025, 9, 20000.00, 0, NULL, '2025-07-08 11:20:43', '2025-07-08 11:20:43'),
(10, 2, 2025, 10, 20000.00, 0, NULL, '2025-07-08 11:20:43', '2025-07-08 11:20:43'),
(11, 2, 2025, 11, 20000.00, 0, NULL, '2025-07-08 11:20:43', '2025-07-08 11:20:43'),
(12, 2, 2025, 12, 20000.00, 0, NULL, '2025-07-08 11:20:43', '2025-07-08 11:20:43'),
(13, 3, 2025, 1, 30000.00, 0, NULL, '2025-07-08 11:24:03', '2025-07-08 11:24:03'),
(14, 3, 2025, 2, 30000.00, 0, NULL, '2025-07-08 11:24:03', '2025-07-08 11:24:03'),
(15, 3, 2025, 3, 30000.00, 0, NULL, '2025-07-08 11:24:03', '2025-07-08 11:24:03'),
(16, 3, 2025, 4, 30000.00, 0, NULL, '2025-07-08 11:24:03', '2025-07-08 11:24:03'),
(17, 3, 2025, 5, 30000.00, 0, NULL, '2025-07-08 11:24:03', '2025-07-08 11:24:03'),
(18, 3, 2025, 6, 30000.00, 0, NULL, '2025-07-08 11:24:03', '2025-07-08 11:24:03'),
(19, 3, 2025, 7, 30000.00, 0, NULL, '2025-07-08 11:24:03', '2025-07-08 11:24:03'),
(20, 3, 2025, 8, 30000.00, 0, NULL, '2025-07-08 11:24:03', '2025-07-08 11:24:03'),
(21, 3, 2025, 9, 30000.00, 0, NULL, '2025-07-08 11:24:03', '2025-07-08 11:24:03'),
(22, 3, 2025, 10, 30000.00, 0, NULL, '2025-07-08 11:24:03', '2025-07-08 11:24:03'),
(23, 3, 2025, 11, 30000.00, 0, NULL, '2025-07-08 11:24:03', '2025-07-08 11:24:03'),
(24, 3, 2025, 12, 30000.00, 0, NULL, '2025-07-08 11:24:03', '2025-07-08 11:24:03'),
(25, 4, 2025, 1, 30000.00, 0, NULL, '2025-07-08 11:24:37', '2025-07-08 11:24:37'),
(26, 4, 2025, 2, 30000.00, 0, NULL, '2025-07-08 11:24:37', '2025-07-08 11:24:37'),
(27, 4, 2025, 3, 30000.00, 0, NULL, '2025-07-08 11:24:37', '2025-07-08 11:24:37'),
(28, 4, 2025, 4, 30000.00, 0, NULL, '2025-07-08 11:24:37', '2025-07-08 11:24:37'),
(29, 4, 2025, 5, 30000.00, 0, NULL, '2025-07-08 11:24:37', '2025-07-08 11:24:37'),
(30, 4, 2025, 6, 30000.00, 0, NULL, '2025-07-08 11:24:37', '2025-07-08 11:24:37'),
(31, 4, 2025, 7, 30000.00, 0, NULL, '2025-07-08 11:24:37', '2025-07-08 11:24:37'),
(32, 4, 2025, 8, 30000.00, 0, NULL, '2025-07-08 11:24:37', '2025-07-08 11:24:37'),
(33, 4, 2025, 9, 30000.00, 0, NULL, '2025-07-08 11:24:37', '2025-07-08 11:24:37'),
(34, 4, 2025, 10, 30000.00, 0, NULL, '2025-07-08 11:24:37', '2025-07-08 11:24:37'),
(35, 4, 2025, 11, 30000.00, 0, NULL, '2025-07-08 11:24:37', '2025-07-08 11:24:37'),
(36, 4, 2025, 12, 30000.00, 0, NULL, '2025-07-08 11:24:37', '2025-07-08 11:24:37');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
