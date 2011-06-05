-- MySQL dump 10.13  Distrib 5.1.49, for debian-linux-gnu (i486)
--
-- Host: localhost    Database: bdsintesi
-- ------------------------------------------------------
-- Server version	5.1.49-3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `bdsintesi`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `bdsintesi` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `bdsintesi`;

--
-- Table structure for table `backups`
--

DROP TABLE IF EXISTS `backups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `backups` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FILENAME` varchar(255) DEFAULT NULL,
  `SIZE` int(10) unsigned DEFAULT NULL,
  `TIMEDATE` datetime DEFAULT NULL,
  `USER_ID` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `FKUser` (`USER_ID`),
  CONSTRAINT `FKUser` FOREIGN KEY (`USER_ID`) REFERENCES `user` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `backups`
--

LOCK TABLES `backups` WRITE;
/*!40000 ALTER TABLE `backups` DISABLE KEYS */;
INSERT INTO `backups` VALUES (25,'20110605122726.tar.gz',12,'2011-06-05 12:27:26',1771904),(41,'20110605174501.tar.gz',1984,'2011-06-05 17:45:13',8026034);
/*!40000 ALTER TABLE `backups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `filepath`
--

DROP TABLE IF EXISTS `filepath`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `filepath` (
  `IDF` int(11) NOT NULL AUTO_INCREMENT,
  `FILEPATH` varchar(255) DEFAULT NULL,
  `VALID` tinyint(1) NOT NULL,
  `USER_ID` int(11) DEFAULT NULL,
  PRIMARY KEY (`IDF`),
  UNIQUE KEY `FILEPATH` (`FILEPATH`,`USER_ID`),
  KEY `FKFileUser` (`USER_ID`),
  CONSTRAINT `FKFileUser` FOREIGN KEY (`USER_ID`) REFERENCES `user` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `filepath`
--

LOCK TABLES `filepath` WRITE;
/*!40000 ALTER TABLE `filepath` DISABLE KEYS */;
INSERT INTO `filepath` VALUES (27,'/home/giorgio/sacsex.v.1.5.tar.gz',0,1771904),(28,'/home/kirsley/Escritorio',0,8026034),(29,'/lflflfld',1,8026034);
/*!40000 ALTER TABLE `filepath` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purga`
--

DROP TABLE IF EXISTS `purga`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purga` (
  `ID_PURGA` int(11) NOT NULL AUTO_INCREMENT,
  `VALOR` int(11) DEFAULT NULL,
  `FREQ` tinyint(4) DEFAULT NULL,
  `USER_ID` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID_PURGA`),
  KEY `FKUPU` (`USER_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purga`
--

LOCK TABLES `purga` WRITE;
/*!40000 ALTER TABLE `purga` DISABLE KEYS */;
/*!40000 ALTER TABLE `purga` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1771904,'atarman','3eab530dd60470976758ec1da5dfd04c',0,0,204800,204800),(4478820,'supergio','4283a71a500fc5c645a1855588888367',0,1,51666944,1048576),(8026034,'kirsley','4283a71a500fc5c645a1855588888367',0,1,4294967295,10240000),(324234578,'admin','4283a71a500fc5c645a1855588888367',1,0,123123,12312321);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-06-05 19:12:30
