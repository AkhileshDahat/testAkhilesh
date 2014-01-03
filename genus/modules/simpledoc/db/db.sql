/*
SQLyog Community Edition- MySQL GUI v6.04
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

/*Table structure for table `announcements_master` */

DROP TABLE IF EXISTS `announcements_master`;

CREATE TABLE `announcements_master` (
  `announcement_id` int(5) NOT NULL auto_increment,
  `workspace_id` int(5) default NULL,
  `teamspace_id` int(5) default NULL,
  `title` varchar(255) default NULL,
  `message` text,
  `date_added` datetime default NULL,
  `user_id` int(5) default NULL,
  PRIMARY KEY  (`announcement_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `announcements_master` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
