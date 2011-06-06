-- phpMyAdmin SQL Dump
-- version 3.3.9.2
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-05-2011 a las 13:02:08
-- Versión del servidor: 5.5.10
-- Versión de PHP: 5.3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `bdsintesi`
--
CREATE DATABASE `bdsintesi` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `bdsintesi`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `backups`
--

CREATE TABLE IF NOT EXISTS `backups` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FILENAME` varchar(255) DEFAULT NULL,
  `SIZE` int(10) unsigned DEFAULT NULL,
  `DATE` date DEFAULT NULL,
  `USER_ID` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `FKUser` (`USER_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- RELACIONES PARA LA TABLA `backups`:
--   `USER_ID`
--       `user` -> `ID`
--

--
-- Volcar la base de datos para la tabla `backups`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `filepath`
--

CREATE TABLE IF NOT EXISTS `filepath` (
  `IDF` int(11) NOT NULL AUTO_INCREMENT,
  `FILEPATH` varchar(255) DEFAULT NULL,
  `VALID` tinyint(1) NOT NULL,
  `USER_ID` int(11) DEFAULT NULL,
  PRIMARY KEY (`IDF`),
  UNIQUE KEY `FILEPATH` (`FILEPATH`,`USER_ID`),
  KEY `FKFileUser` (`USER_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- RELACIONES PARA LA TABLA `filepath`:
--   `USER_ID`
--       `user` -> `ID`
--

--
-- Volcar la base de datos para la tabla `filepath`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `ID` int(11) NOT NULL,
  `NAME` varchar(50) NOT NULL,
  `PASSWORD` varchar(255) NOT NULL,
  `ADMIN` tinyint(1) NOT NULL,
  `INSTALAT` tinyint(1) NOT NULL,
  `MAX_LIMIT` int(10) unsigned NOT NULL COMMENT 'Espacio maximo asignado',
  `DAY_LIMIT` int(10) unsigned NOT NULL COMMENT 'Tamaño Diario Maximo',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `NAME` (`NAME`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `user`
--

INSERT INTO `user` (`ID`, `NAME`, `PASSWORD`, `ADMIN`, `INSTALAT`, `MAX_LIMIT`, `DAY_LIMIT`) VALUES
(2651487, 'Giorgio', '5caae99531c54bc794f2489f5f2e6f33', 0, 0, 10100, 50),
(5998348, 'kirsley', '1c0cd5422dbb158b8f9ffb5b0f432e23', 0, 0, 30, 3000),
(8499529, 'pikachu', '4283a71a500fc5c645a1855588888367', 0, 0, 50, 20),
(324234578, 'admin', '4283a71a500fc5c645a1855588888367', 1, 0, 123123, 12312321);

--
-- Filtros para las tablas descargadas (dump)
--

--
-- Filtros para la tabla `backups`
--
ALTER TABLE `backups`
  ADD CONSTRAINT `FKUser` FOREIGN KEY (`USER_ID`) REFERENCES `user` (`ID`) ON DELETE CASCADE;

--
-- Filtros para la tabla `filepath`
--
ALTER TABLE `filepath`
  ADD CONSTRAINT `FKFileUser` FOREIGN KEY (`USER_ID`) REFERENCES `user` (`ID`) ON DELETE CASCADE;
