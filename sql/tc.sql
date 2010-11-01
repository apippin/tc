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
CREATE TABLE /*!42501 IF NOT EXISTS*/ `tc_activity` (
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
CREATE TABLE /*!42501 IF NOT EXISTS*/ `tc_appointment` (
  `appointment` int(16) unsigned NOT NULL auto_increment,
  `leader` int(16) unsigned NOT NULL default '0',
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
CREATE TABLE /*!42501 IF NOT EXISTS*/ `tc_assignment` (
  `assignment` int(12) unsigned NOT NULL auto_increment,
  `name` varchar(60) NOT NULL,
  `abbreviation` varchar(12) default NULL,
  PRIMARY KEY  (`assignment`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tc_attendance`
--
CREATE TABLE /*!42501 IF NOT EXISTS*/ `tc_attendance` (
  `individual` int(16) unsigned NOT NULL default '0',
  `date` date default NULL,
  KEY `individual` (`individual`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `tc_calling`
--
CREATE TABLE /*!42501 IF NOT EXISTS*/ `tc_calling` (
  `individual` int(16) unsigned default '0',
  `organization` varchar(30) default NULL,
  `position` varchar(30) default NULL,
  `sustained` varchar(30) default NULL,
  KEY `individual` (`individual`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `tc_companion`
--
CREATE TABLE /*!42501 IF NOT EXISTS*/ `tc_companion` (
  `companion` INT( 16 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  `individual` INT( 16 ) UNSIGNED NOT NULL ,
  `companionship` INT( 16 ) UNSIGNED NOT NULL ,
  `scheduling_priority` INT( 16 ) UNSIGNED NOT NULL ,
  `valid` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE = MYISAM ;

--
-- Table structure for table `tc_companionship`
--
CREATE TABLE /*!42501 IF NOT EXISTS*/ `tc_companionship` (
  `companionship` INT( 16 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  `mls_id` INT( 16 ) UNSIGNED NULL DEFAULT NULL ,
  `district` INT( 16 ) UNSIGNED NULL DEFAULT NULL ,
  `type` ENUM( 'H', 'P' ) NOT NULL DEFAULT 'H' ,
  `valid` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE = MYISAM ;

--
-- Table structure for table `tc_district`
--
CREATE TABLE /*!42501 IF NOT EXISTS*/ `tc_district` (
  `district` int(16) unsigned NOT NULL default '0',
  `leader` int(16) unsigned default NULL,
  `valid` tinyint(1) default NULL,
  PRIMARY KEY  (`district`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `tc_individual`
--
CREATE TABLE /*!42501 IF NOT EXISTS*/ `tc_individual` (
  `individual` int(16) unsigned NOT NULL auto_increment,
  `mls_id` int(16) unsigned NOT NULL,
  `name` varchar(60) default NULL,
  `fullname` varchar(60) default NULL,
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
CREATE TABLE /*!42501 IF NOT EXISTS*/ `tc_family` (
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
CREATE TABLE /*!42501 IF NOT EXISTS*/ `tc_participation` (
  `individual` int(16) unsigned NOT NULL default '0',
  `activity` int(16) unsigned default NULL,
  UNIQUE KEY `activity_ndx` (`individual`,`activity`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `tc_interview`
--
CREATE TABLE /*!42501 IF NOT EXISTS*/ `tc_interview` (
  `interview` int(16) unsigned NOT NULL auto_increment,
  `interviewer` int(16) unsigned default NULL,
  `individual` int(16) unsigned default NULL,
  `date` date default NULL,
  `notes` text,
  `type` enum('H','P') NOT NULL DEFAULT 'H',
  PRIMARY KEY  (`interview`)
) ENGINE=MyISAM AUTO_INCREMENT=248 DEFAULT CHARSET=latin1;


--
-- Table structure for table `tc_leader`
--
CREATE TABLE /*!42501 IF NOT EXISTS*/ `tc_leader` (
  `leader` int(16) unsigned NOT NULL auto_increment,
  `individual` int(16) unsigned NOT NULL default '0',
  `email` varchar(60) NOT NULL,
  `type` enum( 'P', 'C', 'S', 'D' ) NOT NULL,
  `valid` tinyint(1) default '1',
  KEY `leader` (`leader`),
  KEY `individual` (`individual`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tc_visit`
--
CREATE TABLE /*!42501 IF NOT EXISTS*/ `tc_visit` (
  `visit` int(16) unsigned NOT NULL auto_increment,
  `family` int(16) unsigned default NULL,
  `companionship` int(16) unsigned default NULL,
  `date` date default NULL,
  `notes` text,
  `visited` enum('y','n','') default NULL,
  `type` enum('P','H') not null default 'H',
  PRIMARY KEY  (`visit`)
) ENGINE=MyISAM AUTO_INCREMENT=9513 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tc_willingness`
--
CREATE TABLE /*!42501 IF NOT EXISTS*/ `tc_willingness` (
  `individual` int(16) unsigned NOT NULL,
  `assignment` int(16) unsigned NOT NULL,
  `willing` enum('y','n','') NOT NULL,
  KEY `individual` (`individual`,`assignment`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `tc_scheduling_priority`
--
CREATE TABLE /*!42501 IF NOT EXISTS*/ `tc_scheduling_priority` (
  `scheduling_priority` INT( 16 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  `priority` INT( 10 ) UNSIGNED NOT NULL DEFAULT '30',
  `notes` VARCHAR( 128 ) NOT NULL DEFAULT ''
) ENGINE = MYISAM ;

--
-- Table structure for table `tc_email_list`
--
CREATE TABLE /*!42501 IF NOT EXISTS*/ `tc_email_list` (
  `email_list` INT( 16 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  `name` VARCHAR( 128 ) NULL DEFAULT NULL
) ENGINE = MYISAM ;

--
-- Table structure for table `tc_email_list_membership`
--
CREATE TABLE /*!42501 IF NOT EXISTS*/ `tc_email_list_membership` (
  `individual` INT( 16 ) UNSIGNED NULL DEFAULT NULL ,
  `email_list` INT( 16 ) UNSIGNED NULL DEFAULT NULL
) ENGINE = MYISAM ;

--
-- Table structure for table `tc_accomplishment`
--
CREATE TABLE /*!42501 IF NOT EXISTS*/ `tc_accomplishment` (
  `accomplishment` INT( 16 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  `individual` INT( 16 ) UNSIGNED NULL DEFAULT NULL ,
  `date` DATE NULL DEFAULT NULL ,
  `task` INT( 16 ) UNSIGNED NULL DEFAULT NULL ,
  `notes` VARCHAR( 128 ) NOT NULL
) ENGINE = MYISAM ;

--
-- Table structure for table `tc_task`
--
CREATE TABLE /*!42501 IF NOT EXISTS*/ `tc_task` (
  `task` INT( 16 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  `name` VARCHAR( 128 ) NOT NULL ,
  `description` VARCHAR( 128 ) NOT NULL
) ENGINE = MYISAM ;

--
-- Table structure for table `tc_district_sandbox`
--
CREATE TABLE /*!42501 IF NOT EXISTS*/ `tc_district_sandbox` (
  `district` INT( 16 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `leader` INT( 16 ) UNSIGNED NULL DEFAULT NULL
) ENGINE = MYISAM ;

--
-- Table structure for table `tc_companion_sandbox`
--
CREATE TABLE /*!42501 IF NOT EXISTS*/ `tc_companion_sandbox` (
  `companion` INT( 16 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  `individual` INT( 16 ) UNSIGNED NOT NULL ,
  `companionship` INT( 16 ) UNSIGNED NOT NULL
) ENGINE = MYISAM ;

--
-- Table structure for table `tc_companionship_sandbox`
--
CREATE TABLE /*!42501 IF NOT EXISTS*/ `tc_companionship_sandbox` (
  `companionship` INT( 16 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  `tc_companionship` INT( 16 ) UNSIGNED NOT NULL ,
  `district` INT( 16 ) UNSIGNED NULL DEFAULT NULL
) ENGINE = MYISAM ;

--
-- Table structure for table `tc_family_sandbox`
--
CREATE TABLE /*!42501 IF NOT EXISTS*/ `tc_family_sandbox` (
  `family` int(16) unsigned NOT NULL auto_increment,
  `tc_family` int(16) unsigned default '0',
  `individual` INT( 16 ) UNSIGNED NOT NULL ,
  `companionship` int(16) unsigned default NULL,
  PRIMARY KEY  (`family`)
) ENGINE = MyISAM ;

