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
-- Table structure for table `tc_activity`
--
CREATE TABLE `tc_activity` (
  `activity` int(16) unsigned NOT NULL auto_increment,
  `assignment` int(16) unsigned NOT NULL,
  `date` date default NULL,
  `notes` text,
  PRIMARY KEY  (`activity`),
  KEY `assignment` (`assignment`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tc_appointment`
--
CREATE TABLE `tc_appointment` (
  `appointment` int(16) unsigned NOT NULL auto_increment,
  `presidency` int(16) unsigned NOT NULL default '0',
  `family` int(16) unsigned default '0',
  `individual` int(16) unsigned default '0',
  `date` date NOT NULL default '0000-00-00',
  `time` time NOT NULL default '00:00:00',
  `location` varchar(120) default NULL,
  `uid` bigint(64) unsigned NOT NULL default '0',
  PRIMARY KEY  (`appointment`)
) ENGINE=MyISAM AUTO_INCREMENT=132 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tc_assignment`
--
CREATE TABLE `tc_assignment` (
  `assignment` int(12) unsigned NOT NULL auto_increment,
  `name` varchar(60) NOT NULL,
  `abbreviation` varchar(12) default NULL,
  PRIMARY KEY  (`assignment`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tc_assignment`
--
LOCK TABLES `tc_assignment` WRITE;
/*!40000 ALTER TABLE `tc_assignment` DISABLE KEYS */;
INSERT INTO `tc_assignment` (`assignment`, `name`, `abbreviation`) VALUES (1,'Enrichment Night Babysitting','RS'),(2,'Building Lockup','LU'),(3,'Building Cleaning Coordinator','CC'),(4,'Missionary Splits','MS'),(5,'Stake Farm','SF'),(6,'Loveland Kitchen','LK'),(7,'Moves','MV'),(8,'Temple Kitchen & Laundary','TKL'),(9,'Temple Sealings','TS'),(10,'Temple Initatories','TI');
/*!40000 ALTER TABLE `tc_assignment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tc_attendance`
--
CREATE TABLE `tc_attendance` (
  `individual` int(16) unsigned NOT NULL default '0',
  `date` date default NULL,
  KEY `individual` (`individual`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `tc_calling`
--
CREATE TABLE `tc_calling` (
  `name` varchar(30) default NULL,
  `organization` varchar(30) default NULL,
  `position` varchar(30) default NULL,
  `sustained` varchar(30) default NULL,
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `tc_companionship`
--

CREATE TABLE `tc_companionship` (
  `companionship` int(16) unsigned NOT NULL default '0',
  `individual` int(16) unsigned NOT NULL default '0',
  `district` int(16) unsigned default NULL,
  `scheduling_priority` int(16) unsigned default NULL,
  `valid` tinyint(1) default NULL,
  KEY `companionship` (`companionship`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `tc_district`
--
CREATE TABLE `tc_district` (
  `district` int(16) unsigned NOT NULL default '0',
  `name` varchar(30) default NULL,
  `supervisor` int(16) unsigned default NULL,
  `valid` tinyint(1) default NULL,
  PRIMARY KEY  (`district`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `tc_individual`
--
CREATE TABLE `tc_individual` (
  `individual` int(16) unsigned NOT NULL auto_increment,
  `mls_id` int(16) unsigned NOT NULL,
  `name` varchar(60) default NULL,
  `address` varchar(255) default NULL,
  `phone` varchar(12) default NULL,
  `email` varchar(120) default NULL,
  `hh_position` enum('Head of Household','Spouse','Other') DEFAULT 'Other',
  `priesthood` enum('High Priest','Elder','Priest','Teacher','Deacon','Unordained') DEFAULT NULL,
  `steward` enum('High Priest','Elder') DEFAULT NULL,
  `scheduling_priority` int(16) unsigned default NULL,
  `attending` tinyint(1) default '0',
  `valid` tinyint(1) default NULL,
  PRIMARY KEY  (`individual`)
) ENGINE=MyISAM AUTO_INCREMENT=105 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tc_family`
--
CREATE TABLE `tc_family` (
  `family` int(16) unsigned NOT NULL auto_increment,
  `individual` int(16) unsigned default '0',
  `companionship` int(16) unsigned default NULL,
  `scheduling_priority` int(16) unsigned default NULL,
  `valid` tinyint(1) default NULL,
  PRIMARY KEY  (`family`)
) ENGINE=MyISAM AUTO_INCREMENT=277 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tc_participation`
--
CREATE TABLE `tc_participation` (
  `individual` int(16) unsigned NOT NULL default '0',
  `activity` int(16) unsigned default NULL,
  UNIQUE KEY `activity_ndx` (`individual`,`activity`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `tc_interview`
--
CREATE TABLE `tc_interview` (
  `interview` int(16) unsigned NOT NULL auto_increment,
  `interviewer` int(16) unsigned default NULL,
  `individual` int(16) unsigned default NULL,
  `date` date default NULL,
  `notes` text,
  `interview_type` enum('hti','ppi') NOT NULL DEFAULT 'hti',
  PRIMARY KEY  (`interview`)
) ENGINE=MyISAM AUTO_INCREMENT=248 DEFAULT CHARSET=latin1;


--
-- Table structure for table `tc_presidency`
--
CREATE TABLE `tc_presidency` (
  `presidency` int(16) unsigned NOT NULL auto_increment,
  `individual` int(16) unsigned NOT NULL default '0',
  `district` int(16) unsigned default '0',
  `email` varchar(60) NOT NULL,
  `president` tinyint(1) default '0',
  `counselor` tinyint(1) default '0',
  `secretary` tinyint(1) default '0',
  `valid` tinyint(1) default '1',
  KEY `presidency` (`presidency`),
  KEY `individual` (`individual`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tc_visit`
--
CREATE TABLE `tc_visit` (
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
-- Table structure for table `tc_willingness`
--
CREATE TABLE `tc_willingness` (
  `individual` int(16) unsigned NOT NULL,
  `assignment` int(16) unsigned NOT NULL,
  `willing` enum('y','n','') NOT NULL,
  KEY `individual` (`individual`,`assignment`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `tc_scheduling_priority`
--
CREATE TABLE `tc_scheduling_priority` (
  `scheduling_priority` INT( 16 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  `priority` INT( 10 ) UNSIGNED NOT NULL DEFAULT '30',
  `notes` VARCHAR( 128 ) NOT NULL DEFAULT ''
) ENGINE = MYISAM ;

--
-- Table structure for table `tc_email_list`
--
CREATE TABLE `tc_email_list` (
  `email_list` INT( 16 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  `name` VARCHAR( 128 ) NULL DEFAULT NULL
) ENGINE = MYISAM ;

--
-- Table structure for table `tc_email_list_membership`
--
CREATE TABLE `tc_email_list_membership` (
  `individual` INT( 16 ) UNSIGNED NULL DEFAULT NULL ,
  `email_list` INT( 16 ) UNSIGNED NULL DEFAULT NULL
) ENGINE = MYISAM ;

--
-- Table structure for table `tc_accomplishment`
--
CREATE TABLE `tc_accomplishment` (
  `accomplishment` INT( 16 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  `individual` INT( 16 ) UNSIGNED NULL DEFAULT NULL ,
  `date` DATE NULL DEFAULT NULL ,
  `task` INT( 16 ) UNSIGNED NULL DEFAULT NULL ,
  `note` VARCHAR( 128 ) NOT NULL
) ENGINE = MYISAM ;

--
-- Table structure for table `tc_task`
--
CREATE TABLE `tc_task` (
  `task` INT( 16 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  `name` VARCHAR( 128 ) NOT NULL ,
  `description` VARCHAR( 128 ) NOT NULL
) ENGINE = MYISAM ;
