-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 04, 2026 at 02:54 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `formulario`
--

-- --------------------------------------------------------

--
-- Table structure for table `formulario_clientes`
--

CREATE TABLE `formulario_clientes` (
  `id` int NOT NULL,
  `razon_social` varchar(255) NOT NULL,
  `domicilio` varchar(255) NOT NULL,
  `poblacion` varchar(100) NOT NULL,
  `colonia` varchar(100) NOT NULL,
  `cp` varchar(10) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `rfc` varchar(13) NOT NULL,
  `pagina_web` varchar(255) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `doc_licencia_sanitaria` varchar(255) DEFAULT NULL,
  `doc_aviso_responsableSanitario` varchar(255) DEFAULT NULL,
  `doc_aviso_funcionamiento` varchar(255) DEFAULT NULL,
  `doc_ine_responsableSanitario` varchar(255) DEFAULT NULL,
  `doc_ine_representanteLegal` varchar(255) DEFAULT NULL,
  `doc_comprobante_domicilio` varchar(255) DEFAULT NULL,
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `formulario_clientes`
--
ALTER TABLE `formulario_clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rfc` (`rfc`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `formulario_clientes`
--
ALTER TABLE `formulario_clientes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
