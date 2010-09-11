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
-- Table structure for table `3rd_aaronic`
--
CREATE TABLE `3rd_aaronic` (
  `aaronic` int(16) unsigned NOT NULL auto_increment,
  `name` varchar(60) default NULL,
  `phone` varchar(12) default NULL,
  `email` varchar(120) default NULL,
  `valid` tinyint(1) default NULL,
  PRIMARY KEY  (`aaronic`)
) ENGINE=MyISAM AUTO_INCREMENT=92 DEFAULT CHARSET=latin1;


--
-- Table structure for table `3rd_activity`
--
CREATE TABLE `3rd_activity` (
  `activity` int(16) unsigned NOT NULL auto_increment,
  `assignment` int(16) unsigned NOT NULL,
  `date` date default NULL,
  `notes` text,
  PRIMARY KEY  (`activity`),
  KEY `assignment` (`assignment`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;

--
-- Table structure for table `3rd_appointment`
--
CREATE TABLE `3rd_appointment` (
  `appointment` int(16) unsigned NOT NULL auto_increment,
  `presidency` int(16) unsigned NOT NULL default '0',
  `family` int(16) unsigned default '0',
  `elder` int(16) unsigned default '0',
  `date` date NOT NULL default '0000-00-00',
  `time` time NOT NULL default '00:00:00',
  `location` varchar(120) default NULL,
  `uid` bigint(64) unsigned NOT NULL default '0',
  PRIMARY KEY  (`appointment`)
) ENGINE=MyISAM AUTO_INCREMENT=132 DEFAULT CHARSET=latin1;

--
-- Table structure for table `3rd_assignment`
--
CREATE TABLE `3rd_assignment` (
  `assignment` int(12) unsigned NOT NULL auto_increment,
  `name` varchar(60) NOT NULL,
  `code` varchar(12) default NULL,
  PRIMARY KEY  (`assignment`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `3rd_assignment`
--
LOCK TABLES `3rd_assignment` WRITE;
/*!40000 ALTER TABLE `3rd_assignment` DISABLE KEYS */;
INSERT INTO `3rd_assignment` (`assignment`, `name`, `code`) VALUES (1,'Enrichment Night Babysitting','RS'),(2,'Building Lockup','LU'),(3,'Building Cleaning Coordinator','CC'),(4,'Missionary Splits','MS'),(5,'Stake Farm','SF'),(6,'Loveland Kitchen','LK'),(7,'Moves','MV'),(8,'Temple Kitchen & Laundary','TKL'),(9,'Temple Sealings','TS'),(10,'Temple Initatories','TI');
/*!40000 ALTER TABLE `3rd_assignment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `3rd_attendance`
--
CREATE TABLE `3rd_attendance` (
  `elder` int(16) unsigned NOT NULL default '0',
  `date` date default NULL,
  KEY `elder` (`elder`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `3rd_calling`
--
CREATE TABLE `3rd_calling` (
  `indiv_id` int(16) unsigned default NULL,
  `name` varchar(30) default NULL,
  `organization` varchar(30) default NULL,
  `position` varchar(30) default NULL,
  `sequence` int(16) unsigned default NULL,
  `sustained` varchar(30) default NULL,
  KEY `indiv_id` (`indiv_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `3rd_child`
--
CREATE TABLE `3rd_child` (
  `child` int(16) unsigned NOT NULL auto_increment,
  `family` int(16) unsigned default NULL,
  `name` varchar(30) default NULL,
  `birthday` date default NULL,
  `indiv_id` int(16) unsigned default NULL,
  `valid` tinyint(1) default NULL,
  PRIMARY KEY  (`child`)
) ENGINE=MyISAM AUTO_INCREMENT=260 DEFAULT CHARSET=latin1;

--
-- Table structure for table `3rd_companionship`
--

CREATE TABLE `3rd_companionship` (
  `companionship` int(16) unsigned NOT NULL default '0',
  `elder` int(16) unsigned NOT NULL default '0',
  `aaronic` int(16) unsigned NOT NULL default '0',
  `district` int(16) unsigned default NULL,
  `valid` tinyint(1) default NULL,
  KEY `companionship` (`companionship`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `3rd_district`
--
CREATE TABLE `3rd_district` (
  `district` int(16) unsigned NOT NULL default '0',
  `name` varchar(30) default NULL,
  `supervisor` int(16) unsigned default NULL,
  `valid` tinyint(1) default NULL,
  PRIMARY KEY  (`district`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `3rd_elder`
--
CREATE TABLE `3rd_elder` (
  `elder` int(16) unsigned NOT NULL auto_increment,
  `indiv_id` int(16) unsigned NOT NULL,
  `name` varchar(60) default NULL,
  `phone` varchar(12) default NULL,
  `email` varchar(120) default NULL,
  `priesthood` enum('High Priest','Elder','Priest','Teacher','Deacon','Unordained') DEFAULT NULL,
  `prospective` enum('y','n') DEFAULT 'n',
  `ppi_pri` int(10) unsigned NOT NULL default '1',
  `ppi_notes` varchar(128) default NULL,
  `int_pri` int(10) unsigned default '1',
  `int_notes` varchar(128) default NULL,
  `attending` tinyint(1) default '0',
  `valid` tinyint(1) default NULL,
  PRIMARY KEY  (`elder`)
) ENGINE=MyISAM AUTO_INCREMENT=105 DEFAULT CHARSET=latin1;

--
-- Table structure for table `3rd_family`
--
CREATE TABLE `3rd_family` (
  `family` int(16) unsigned NOT NULL auto_increment,
  `hofh_id` int(16) unsigned NOT NULL default '0',
  `name` varchar(30) NOT NULL default '',
  `name_id` varchar(30) NOT NULL default '',
  `elder_id` int(16) unsigned default '0',
  `companionship` int(16) unsigned default NULL,
  `visit_pri` int(10) unsigned default '1',
  `visit_notes` varchar(128) default NULL,
  `valid` tinyint(1) default NULL,
  PRIMARY KEY  (`family`)
) ENGINE=MyISAM AUTO_INCREMENT=277 DEFAULT CHARSET=latin1;

--
-- Table structure for table `3rd_parent`
--
CREATE TABLE `3rd_parent` (
  `parent` int(16) unsigned NOT NULL auto_increment,
  `family` int(16) unsigned default NULL,
  `name` varchar(30) default NULL,
  `birthday` date default NULL,
  `phone` varchar(12) default NULL,
  `address` varchar(255) default NULL,
  `indiv_id` int(16) unsigned default NULL,
  `valid` tinyint(1) default NULL,
  PRIMARY KEY  (`parent`)
) ENGINE=MyISAM AUTO_INCREMENT=396 DEFAULT CHARSET=latin1;

--
-- Table structure for table `3rd_participation`
--
CREATE TABLE `3rd_participation` (
  `elder` int(16) unsigned NOT NULL default '0',
  `activity` int(16) unsigned default NULL,
  UNIQUE KEY `activity_ndx` (`elder`,`activity`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `3rd_interview`
--
CREATE TABLE `3rd_interview` (
  `interview` int(16) unsigned NOT NULL auto_increment,
  `interviewer` int(16) unsigned default NULL,
  `elder` int(16) unsigned default NULL,
  `aaronic` int(16) unsigned NOT NULL default '0',
  `date` date default NULL,
  `notes` text,
  `interview_type` enum('hti','ppi') NOT NULL DEFAULT 'hti',
  PRIMARY KEY  (`ppi`)
) ENGINE=MyISAM AUTO_INCREMENT=248 DEFAULT CHARSET=latin1;


--
-- Table structure for table `3rd_presidency`
--
CREATE TABLE `3rd_presidency` (
  `presidency` int(16) unsigned NOT NULL auto_increment,
  `elder` int(16) unsigned NOT NULL default '0',
  `district` int(16) unsigned default '0',
  `name` varchar(60) NOT NULL,
  `email` varchar(60) NOT NULL,
  `president` tinyint(1) default '0',
  `counselor` tinyint(1) default '0',
  `secretary` tinyint(1) default '0',
  `eqpres` tinyint(1) default '0',
  `valid` tinyint(1) default '1',
  KEY `presidency` (`presidency`),
  KEY `elder` (`elder`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Table structure for table `3rd_visit`
--
CREATE TABLE `3rd_visit` (
  `visit` int(16) unsigned NOT NULL auto_increment,
  `family` int(16) unsigned default NULL,
  `companionship` int(16) unsigned default NULL,
  `companion1` int(16) unsigned default NULL,
  `companion2` int(16) unsigned default NULL,
  `date` date default NULL,
  `notes` text,
  `visited` enum('y','n','') default NULL,
  `visit_type` enum('presidency','hometeaching') not null default 'hometeaching',
  PRIMARY KEY  (`visit`)
) ENGINE=MyISAM AUTO_INCREMENT=9513 DEFAULT CHARSET=latin1;

--
-- Table structure for table `3rd_willingness`
--
CREATE TABLE `3rd_willingness` (
  `elder` int(16) unsigned NOT NULL,
  `assignment` int(16) unsigned NOT NULL,
  `willing` enum('y','n','') NOT NULL,
  KEY `elder` (`elder`,`assignment`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
