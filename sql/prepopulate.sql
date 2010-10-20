-- MySQL dump 10.11
--
-- Host: localhost    Database: phpgroupware
-- ------------------------------------------------------

--
-- Current Database: `phpgroupware`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `phpgroupware` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `phpgroupware`;

--
-- Dumping data for table `tc_assignment`
--
LOCK TABLES `tc_assignment` WRITE;
/*!40000 ALTER TABLE `tc_assignment` DISABLE KEYS */;
INSERT INTO `tc_assignment` (`assignment`, `name`, `abbreviation`) VALUES (1,'Enrichment Night Babysitting','RS'),(2,'Building Lockup','LU'),(3,'Building Cleaning Coordinator','CC'),(4,'Missionary Splits','MS'),(5,'Stake Farm','SF'),(6,'Loveland Kitchen','LK'),(7,'Moves','MV'),(8,'Temple Kitchen & Laundary','TKL'),(9,'Temple Sealings','TS'),(10,'Temple Initatories','TI');
/*!40000 ALTER TABLE `tc_assignment` ENABLE KEYS */;
UNLOCK TABLES;

