/*
SQLyog - Free MySQL GUI v5.12
Host - 5.0.27 : Database - framework
*********************************************************************
Server version : 5.0.27
*/

SET NAMES utf8;

SET SQL_MODE='';

create database if not exists `framework`;

USE `framework`;

SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';

/*Table structure for table `survey_answer_master` */

DROP TABLE IF EXISTS `survey_answer_master`;

CREATE TABLE `survey_answer_master` (
  `question_id` int(5) NOT NULL default '0',
  `public_password` varchar(10) collate latin1_general_ci default NULL,
  `user_key` varchar(20) collate latin1_general_ci NOT NULL default '',
  `answer` tinyint(1) default NULL,
  PRIMARY KEY  (`question_id`,`user_key`),
  KEY `question_id` (`question_id`),
  KEY `user_key` (`user_key`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

/*Table structure for table `survey_master` */

DROP TABLE IF EXISTS `survey_master`;

CREATE TABLE `survey_master` (
  `survey_id` int(5) NOT NULL auto_increment,
  `user_id` int(5) default NULL,
  `description` text collate latin1_general_ci,
  `workspace_id` int(5) default NULL,
  `teamspace_id` int(5) default NULL,
  `date_open` datetime default NULL,
  `date_closed` datetime default NULL,
  `public_password` varchar(100) collate latin1_general_ci default NULL,
  PRIMARY KEY  (`survey_id`),
  KEY `user_id` (`user_id`),
  KEY `workspace_id` (`workspace_id`),
  KEY `teamspace_id` (`teamspace_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

/*Table structure for table `survey_question_master` */

DROP TABLE IF EXISTS `survey_question_master`;

CREATE TABLE `survey_question_master` (
  `question_id` int(5) NOT NULL auto_increment,
  `survey_id` int(5) default NULL,
  `question` varchar(255) collate latin1_general_ci default NULL,
  PRIMARY KEY  (`question_id`),
  KEY `question_id` (`question_id`),
  KEY `survey_id` (`survey_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

/*Table structure for table `survey_user_keys` */

DROP TABLE IF EXISTS `survey_user_keys`;

CREATE TABLE `survey_user_keys` (
  `user_key` varchar(20) collate latin1_general_ci NOT NULL,
  `survey_id` int(5) default NULL,
  `workspace_id` int(5) default NULL,
  `teamspace_id` int(5) default NULL,
  `datetime_used` datetime default NULL,
  PRIMARY KEY  (`user_key`),
  KEY `survey_id` (`survey_id`),
  KEY `workspace_id` (`workspace_id`),
  KEY `teamspace_id` (`teamspace_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
