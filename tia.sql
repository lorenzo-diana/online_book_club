-- Progettazione Web 
DROP DATABASE if exists tia; 
CREATE DATABASE tia; 
USE tia; 
-- MySQL dump 10.13  Distrib 5.6.14, for Win64 (x86_64)
--
-- Host: localhost    Database: tia
-- ------------------------------------------------------
-- Server version	5.6.14-log

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
-- Table structure for table `commenti`
--

DROP TABLE IF EXISTS `commenti`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `commenti` (
  `id` varchar(45) NOT NULL,
  `isbn` varchar(13) NOT NULL,
  `commento` varchar(200) DEFAULT NULL,
  `voto` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`,`isbn`),
  KEY `isbn_idx` (`isbn`),
  CONSTRAINT `id` FOREIGN KEY (`id`) REFERENCES `utenti` (`ID`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `isbn` FOREIGN KEY (`isbn`) REFERENCES `libri` (`isbn`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commenti`
--

LOCK TABLES `commenti` WRITE;
/*!40000 ALTER TABLE `commenti` DISABLE KEYS */;
INSERT INTO `commenti` VALUES ('giova@mail.it','0987654321098','Terzo capitolo della Guida.',5),('giova@mail.it','1234567890123','',3),('giova@mail.it','9780132856201','Belle le reti!',3),('giova@mail.it','9788838664328','Bellissimo!',4),('lorenzo@mail.it','9788804397700','un capolavoro, consigliato agli appassionati di storie sui serial killer',5),('lorenzo@mail.it','9788804464631','',0),('lorenzo@mail.it','9788845292613','Un classico per il genere fantasy. Inimitabile.',0),('lorenzo@mail.it','9788860733658','',0),('pippo@mail.it','9780132856201','Molto bello.',1),('pippo@mail.it','9781479160059','',0),('pippo@mail.it','9788804397700','Un libro pieno di suspance.',3),('pippo@mail.it','9788804620839','Bellissimo!',5),('tizio@mail.it','9788804482024','',3),('tizio@mail.it','9788838664328','hjbhj',1),('tizio@mail.it','9788845920240','',0);
/*!40000 ALTER TABLE `commenti` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `libri`
--

DROP TABLE IF EXISTS `libri`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `libri` (
  `isbn` varchar(13) NOT NULL,
  `titolo` varchar(45) DEFAULT NULL,
  `autore` varchar(45) DEFAULT NULL,
  `editore` varchar(45) DEFAULT NULL,
  `anno` int(11) DEFAULT NULL,
  `genere` enum('orror','fantasy','romantico','giallo','avventura','fumetto') DEFAULT NULL,
  `url_img` varchar(45) DEFAULT NULL,
  `timestamp_libro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`isbn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `libri`
--

LOCK TABLES `libri` WRITE;
/*!40000 ALTER TABLE `libri` DISABLE KEYS */;
INSERT INTO `libri` VALUES ('0987654321098','La vita l universo e tutto quanto','Adams','mondadori',1993,'fumetto','./img/vita_universo_e _tutto_quanto.jpg','2013-10-28 09:13:45'),('1234567890123','Tux advenctures','nessuno','miope',0,'avventura','./img/indiana_tux.png','2013-10-28 09:13:45'),('9780132856201','Computer Networking ','Kurose','Addison-Wesley',2012,'romantico','./img/computer_networking.jpg','2013-10-25 08:13:45'),('9781479160059','Il formichiere','Franky Borel','CreateSpace',2012,'orror','./img/form.jpg','2013-11-05 19:10:08'),('9788804397700','red dragon','thomas harris','mondadori',1994,'giallo','./img/rosso.jpg','2013-11-01 11:00:49'),('9788804464631','Guida galattica per gli autostoppisti','Adams','Mondadori',1999,'orror','./img/guida.jpg','2013-10-26 08:13:45'),('9788804482024','Il cavaliere inesistente','Calvino','OPHRYS',1990,'fantasy','./img/cavaliere.jpg','2013-10-27 09:13:45'),('9788804620839','Dirk Gently agenzia investigativa olistica','Douglas Adams','Mondadori',2012,'giallo','./img/agenzia.jpg','2013-11-05 19:06:32'),('9788838664328','Sistemi operativi','Ancilotti','mcgraw hill',2008,'avventura','./img/so.png','2013-10-28 09:10:45'),('9788845292613','il signore degli anelli','j.r.r. tolkien','Bompiani',1950,'fantasy','./img/Sig_anelli.jpg','2013-10-28 09:13:45'),('9788845920240','La generazione romantica','Rosen Zaccagnini','Adelphi',2005,'romantico','./img/generazione.jpg','2013-11-05 19:13:53'),('9788860733658','charly brown','Charles M. Schulz','Dalai Editore',1980,'fumetto','./img/charly.jpg','2013-11-05 18:56:18');
/*!40000 ALTER TABLE `libri` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `suggeriti`
--

DROP TABLE IF EXISTS `suggeriti`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `suggeriti` (
  `id1` varchar(45) NOT NULL,
  `id2` varchar(45) NOT NULL,
  `isbn_libro` varchar(13) NOT NULL,
  PRIMARY KEY (`id1`,`id2`,`isbn_libro`),
  KEY `isbn_idx` (`isbn_libro`),
  KEY `id2_idx` (`id2`),
  CONSTRAINT `id1` FOREIGN KEY (`id1`) REFERENCES `utenti` (`ID`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `id2` FOREIGN KEY (`id2`) REFERENCES `utenti` (`ID`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `isbn_libro` FOREIGN KEY (`isbn_libro`) REFERENCES `libri` (`isbn`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suggeriti`
--

LOCK TABLES `suggeriti` WRITE;
/*!40000 ALTER TABLE `suggeriti` DISABLE KEYS */;
INSERT INTO `suggeriti` VALUES ('lorenzo@mail.it','giova@mail.it','1234567890123'),('tizio@mail.it','pippo@mail.it','9780132856201'),('pippo@mail.it','lorenzo@mail.it','9788804397700'),('lorenzo@mail.it','giova@mail.it','9788804464631'),('giova@mail.it','lorenzo@mail.it','9788804620839');
/*!40000 ALTER TABLE `suggeriti` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `utenti`
--

DROP TABLE IF EXISTS `utenti`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `utenti` (
  `ID` varchar(45) NOT NULL,
  `nome` varchar(45) DEFAULT NULL,
  `cognome` varchar(45) DEFAULT NULL,
  `genere_preferito` enum('orror','fantasy','romantico','giallo','avventura','fumetto') DEFAULT NULL,
  `pass` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `utenti`
--

LOCK TABLES `utenti` WRITE;
/*!40000 ALTER TABLE `utenti` DISABLE KEYS */;
INSERT INTO `utenti` VALUES ('giova@mail.it','giovanni','nino','fantasy','giovanni'),('lorenzo@mail.it','lorenzo','diana','orror','lorenzo'),('pippo@mail.it','pippo','pioppi','romantico','pippo'),('sempronio@mail.it','sempronio','sempre','fumetto','sempronio'),('tizio@mail.it','tizio','ozio','avventura','tizio');
/*!40000 ALTER TABLE `utenti` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-11-05 20:28:16
