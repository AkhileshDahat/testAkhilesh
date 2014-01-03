/*
SQLyog Community Edition- MySQL GUI v6.05 Beat1
Host - 5.0.37 : Database - framework_mod
*********************************************************************
Server version : 5.0.37
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

create database if not exists `framework_mod`;

USE `framework_mod`;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

/*Table structure for table `project_category_master` */

DROP TABLE IF EXISTS `project_category_master`;

CREATE TABLE `project_category_master` (
  `category_id` int(5) NOT NULL auto_increment,
  `category_name` varchar(50) NOT NULL default '',
  `workspace_id` int(5) NOT NULL,
  `teamspace_id` int(5) default NULL,
  `is_active` char(1) NOT NULL,
  PRIMARY KEY  (`category_id`),
  UNIQUE KEY `category_id` (`category_id`),
  KEY `category_id_2` (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

/*Data for the table `project_category_master` */

insert  into `project_category_master`(`category_id`,`category_name`,`workspace_id`,`teamspace_id`,`is_active`) values (3,'Internal',1,NULL,''),(4,'External',1,NULL,'');

/*Table structure for table `project_master` */

DROP TABLE IF EXISTS `project_master`;

CREATE TABLE `project_master` (
  `project_id` int(5) NOT NULL auto_increment,
  `project_code` varchar(25) NOT NULL,
  `title` varchar(255) NOT NULL default '',
  `description` text NOT NULL,
  `category_id` int(5) NOT NULL,
  `status_id` int(5) NOT NULL default '0',
  `priority_id` int(5) NOT NULL default '0',
  `start_date` date NOT NULL default '0000-00-00',
  `estimated_completion_date` date NOT NULL default '0000-00-00',
  `actual_completion_date` date default '0000-00-00',
  `estimated_cost` double(12,2) NOT NULL default '0.00',
  `actual_cost` double(12,2) NOT NULL default '0.00',
  `workspace_id` int(5) NOT NULL default '0',
  `teamspace_id` int(5) default '0',
  `percentage_completion` tinyint(2) NOT NULL default '0',
  `date_added` datetime default NULL,
  PRIMARY KEY  (`project_id`),
  UNIQUE KEY `project_id` (`project_id`),
  KEY `project_id_2` (`project_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `project_master` */

/*Table structure for table `project_priority_master` */

DROP TABLE IF EXISTS `project_priority_master`;

CREATE TABLE `project_priority_master` (
  `priority_id` int(5) NOT NULL auto_increment,
  `priority_name` varchar(50) NOT NULL default '',
  `workspace_id` int(5) NOT NULL,
  `teamspace_id` int(5) default NULL,
  PRIMARY KEY  (`priority_id`),
  UNIQUE KEY `priority_id` (`priority_id`),
  KEY `priority_id_2` (`priority_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `project_priority_master` */

/*Table structure for table `project_status_master` */

DROP TABLE IF EXISTS `project_status_master`;

CREATE TABLE `project_status_master` (
  `status_id` int(5) NOT NULL auto_increment,
  `status_name` varchar(50) NOT NULL default '',
  `workspace_id` int(5) NOT NULL,
  `teamspace_id` int(5) default NULL,
  `is_active` enum('y','n') NOT NULL default 'n',
  PRIMARY KEY  (`status_id`),
  UNIQUE KEY `status_id` (`status_id`),
  KEY `status_id_2` (`status_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Data for the table `project_status_master` */

insert  into `project_status_master`(`status_id`,`status_name`,`workspace_id`,`teamspace_id`,`is_active`) values (2,'Pending Approval',1,NULL,'n'),(3,'In Progress',1,NULL,'n'),(4,'Completed',1,NULL,'n');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
