-- MySQL dump 10.13  Distrib 5.5.9, for linux2.6 (i686)
--
-- Host: localhost    Database: bdsintesi
-- ------------------------------------------------------
-- Server version	5.5.9-log

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
  `DATE` date DEFAULT NULL,
  `USER_ID` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `FKUser` (`USER_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `backups`
--

LOCK TABLES `backups` WRITE;
/*!40000 ALTER TABLE `backups` DISABLE KEYS */;
/*!40000 ALTER TABLE `backups` ENABLE KEYS */;
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
  `PASSWORD` varchar(50) NOT NULL,
  `ADMIN` tinyint(1) NOT NULL,
  `INSTALAT` tinyint(1) NOT NULL,
  `LIMIT` int(10) unsigned NOT NULL COMMENT 'Espacio maximo asignado',
  `DAY_LIMIT` int(10) unsigned NOT NULL COMMENT 'TamaÃ±o Diario Maximo',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `NAME` (`NAME`),
  UNIQUE KEY `NAME_2` (`NAME`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (108975,'kirsley','5caae99531c54bc794f2489f5f2e6f33',1,0,23,23),(116722,'nani','02ea2ae2a237c042285e093e6972eaa9',0,0,101929,1029),(120685,'Nuevo','e26c062fedf6b32834e4de93f9c8b644',0,0,89234,102),(2651487,'Giorgio','5caae99531c54bc794f2489f5f2e6f33',0,0,10100,50),(8499529,'pikachu','4283a71a500fc5c645a1855588888367',0,0,50,20),(324234578,'admin','4283a71a500fc5c645a1855588888367',1,0,123123,12312321);
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

-- Dump completed on 2011-05-17  9:42:49
