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

/*Table structure for table `sms_message_detail` */

DROP TABLE IF EXISTS `sms_message_detail`;

CREATE TABLE `sms_message_detail` (
  `sms_id` int(5) default NULL,
  `user_id` int(5) default NULL,
  `date_sent` datetime default NULL,
  `status` char(10) default 'pending'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `sms_message_detail` */

insert  into `sms_message_detail`(`sms_id`,`user_id`,`date_sent`,`status`) values (6,1,'2007-07-08 07:42:44','pending'),(7,1,'2007-07-08 07:43:02','pending'),(8,1,'2007-07-08 07:43:42','pending'),(8,2,'2007-07-08 07:43:42','pending'),(9,1,'2007-07-08 07:44:08','pending'),(9,2,'2007-07-08 07:44:08','pending'),(10,1,'2007-07-08 07:45:13','pending'),(10,2,'2007-07-08 07:45:13','pending'),(11,1,'2007-07-08 07:45:25','pending'),(11,2,'2007-07-08 07:45:25','pending'),(12,1,'2007-07-08 07:45:31','pending'),(12,2,'2007-07-08 07:45:31','pending'),(13,1,'2007-07-08 07:45:31','pending'),(13,2,'2007-07-08 07:45:31','pending'),(14,1,'2007-07-08 07:45:31','pending'),(14,2,'2007-07-08 07:45:31','pending'),(15,1,'2007-07-08 07:45:32','pending'),(15,2,'2007-07-08 07:45:32','pending'),(16,1,'2007-07-08 07:45:32','pending'),(16,2,'2007-07-08 07:45:32','pending'),(17,1,'2007-07-08 07:45:32','pending'),(17,2,'2007-07-08 07:45:32','pending'),(18,1,'2007-07-08 07:45:32','pending'),(18,2,'2007-07-08 07:45:32','pending'),(19,1,'2007-07-08 07:45:33','pending'),(19,2,'2007-07-08 07:45:33','pending'),(20,1,'2007-07-08 07:46:46','pending'),(20,2,'2007-07-08 07:46:46','pending'),(21,1,'2007-07-08 07:47:25','pending'),(21,2,'2007-07-08 07:47:25','pending'),(21,3,'2007-07-08 07:47:25','pending'),(22,1,'2007-07-08 19:14:00','pending'),(22,2,'2007-07-08 19:14:00','pending'),(22,3,'2007-07-08 19:14:00','pending'),(23,1,'2007-07-08 19:20:46','pending'),(23,2,'2007-07-08 19:20:46','pending'),(23,3,'2007-07-08 19:20:46','pending'),(24,1,'2007-07-08 19:21:04','pending'),(24,2,'2007-07-08 19:21:04','pending'),(24,3,'2007-07-08 19:21:04','pending'),(25,1,'2007-07-08 19:21:19','pending'),(25,2,'2007-07-08 19:21:19','pending'),(25,3,'2007-07-08 19:21:19','pending'),(26,1,'2007-07-08 19:21:41','pending'),(26,2,'2007-07-08 19:21:41','pending'),(26,3,'2007-07-08 19:21:41','pending'),(27,1,'2007-07-08 19:21:59','pending'),(27,2,'2007-07-08 19:21:59','pending'),(27,3,'2007-07-08 19:21:59','pending'),(28,1,'2007-07-08 19:22:00','pending'),(28,2,'2007-07-08 19:22:00','pending'),(28,3,'2007-07-08 19:22:00','pending'),(29,1,'2007-07-08 19:22:00','pending'),(29,2,'2007-07-08 19:22:00','pending'),(29,3,'2007-07-08 19:22:00','pending'),(30,1,'2007-07-08 19:22:00','pending'),(30,2,'2007-07-08 19:22:00','pending'),(30,3,'2007-07-08 19:22:00','pending'),(31,1,'2007-07-08 19:22:01','pending'),(31,2,'2007-07-08 19:22:01','pending'),(31,3,'2007-07-08 19:22:01','pending');

/*Table structure for table `sms_message_master` */

DROP TABLE IF EXISTS `sms_message_master`;

CREATE TABLE `sms_message_master` (
  `sms_id` int(5) NOT NULL auto_increment,
  `workspace_id` int(5) default NULL,
  `teamspace_id` int(5) default NULL,
  `message` varchar(255) collate latin1_general_ci default NULL,
  `user_id` int(5) default NULL,
  `date_sent` datetime default NULL,
  `sent_all_users_workspace` char(1) collate latin1_general_ci default NULL,
  PRIMARY KEY  (`sms_id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

/*Data for the table `sms_message_master` */

insert  into `sms_message_master`(`sms_id`,`workspace_id`,`teamspace_id`,`message`,`user_id`,`date_sent`,`sent_all_users_workspace`) values (1,1,NULL,'test',1,'2007-07-08 07:33:19',''),(2,1,NULL,'test',1,'2007-07-08 07:33:54',''),(3,1,NULL,'test',1,'2007-07-08 07:34:24','y'),(4,1,NULL,'test',1,'2007-07-08 07:42:23','y'),(5,1,NULL,'test',1,'2007-07-08 07:42:34','y'),(6,1,NULL,'test',1,'2007-07-08 07:42:44','y'),(7,1,NULL,'test',1,'2007-07-08 07:43:02','y'),(8,1,NULL,'test',1,'2007-07-08 07:43:42','y'),(9,1,NULL,'test',1,'2007-07-08 07:44:08','y'),(10,1,NULL,'test',1,'2007-07-08 07:45:13','y'),(11,1,NULL,'test',1,'2007-07-08 07:45:25','y'),(12,1,NULL,'test',1,'2007-07-08 07:45:31','y'),(13,1,NULL,'test',1,'2007-07-08 07:45:31','y'),(14,1,NULL,'test',1,'2007-07-08 07:45:31','y'),(15,1,NULL,'test',1,'2007-07-08 07:45:32','y'),(16,1,NULL,'test',1,'2007-07-08 07:45:32','y'),(17,1,NULL,'test',1,'2007-07-08 07:45:32','y'),(18,1,NULL,'test',1,'2007-07-08 07:45:32','y'),(19,1,NULL,'test',1,'2007-07-08 07:45:33','y'),(20,1,NULL,'test',1,'2007-07-08 07:46:46','y'),(21,1,NULL,'wefsdfsd',1,'2007-07-08 07:47:25','y'),(22,1,NULL,'fwefwe',1,'2007-07-08 19:14:00','y'),(23,1,NULL,'etst',1,'2007-07-08 19:20:46','y'),(24,1,NULL,'etst',1,'2007-07-08 19:21:04','y'),(25,1,NULL,'etst',1,'2007-07-08 19:21:19','y'),(26,1,NULL,'etst',1,'2007-07-08 19:21:41','y'),(27,1,NULL,'etst',1,'2007-07-08 19:21:59','y'),(28,1,NULL,'etst',1,'2007-07-08 19:22:00','y'),(29,1,NULL,'etst',1,'2007-07-08 19:22:00','y'),(30,1,NULL,'etst',1,'2007-07-08 19:22:00','y'),(31,1,NULL,'etst',1,'2007-07-08 19:22:01','y');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
