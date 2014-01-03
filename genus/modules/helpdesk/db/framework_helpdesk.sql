/*
SQLyog Community Edition- MySQL GUI v6.0 RC 1
Host - 5.0.20-nt : Database - framework
*********************************************************************
Server version : 5.0.20-nt
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

create database if not exists `framework`;

USE `framework_mod`;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

/*Table structure for table `helpdesk_category_delegation` */

DROP TABLE IF EXISTS `helpdesk_category_delegation`;

CREATE TABLE `helpdesk_category_delegation` (
  `category_id` int(5) default NULL,
  `user_id` int(5) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `helpdesk_category_delegation` */

/*Table structure for table `helpdesk_category_master` */

DROP TABLE IF EXISTS `helpdesk_category_master`;

CREATE TABLE `helpdesk_category_master` (
  `category_id` int(5) NOT NULL auto_increment,
  `category_name` varchar(50) default NULL,
  `workspace_id` int(5) default NULL,
  `teamspace_id` int(5) default NULL,
  `graph_color` varchar(15) default 'black',
  PRIMARY KEY  (`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `helpdesk_category_master` */

insert  into `helpdesk_category_master`(`category_id`,`category_name`,`workspace_id`,`teamspace_id`,`graph_color`) values (1,'General',5,NULL,'blue'),(2,'Other',5,NULL,'red'),(5,'Hardware',5,NULL,'black'),(6,'Software',5,NULL,'black'),(7,'Printers',11,NULL,'black'),(8,'PC\'s',11,NULL,'black'),(9,'Network',11,NULL,'black'),(10,'PC',11,25,'black'),(11,'Other',11,25,'black'),(12,'Other',11,26,'black'),(13,'123',45,NULL,'black');

/*Table structure for table `helpdesk_master` */

DROP TABLE IF EXISTS `helpdesk_master`;

CREATE TABLE `helpdesk_master` (
  `master_id` smallint(6) NOT NULL,
  `workspace_id` int(11) default NULL,
  `teamspace_id` int(11) default NULL,
  `short_code` char(3) default NULL,
  `ticket_count` int(11) default NULL,
  `description` varchar(100) default NULL,
  `logo` varchar(255) default NULL,
  `use_approval` char(1) default 'n',
  `use_attachments` char(1) default 'n',
  `use_tags` char(1) default 'n',
  `use_interested_parties` char(1) default 'n',
  `use_custom_locations` char(1) default 'n',
  `use_tasks` char(1) default 'n',
  `use_custom_fields` char(1) default 'n',
  `use_due_date` char(1) default 'n',
  `use_review` char(1) default 'n',
  `use_feedback` char(1) default 'n',
  `email_address_from` varchar(100) default NULL,
  `email_color` varchar(6) default NULL,
  `log_ticket_history` char(1) default 'n',
  `log_helpdesk_history` char(1) default 'n'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `helpdesk_master` */

/*Table structure for table `helpdesk_priority_master` */

DROP TABLE IF EXISTS `helpdesk_priority_master`;

CREATE TABLE `helpdesk_priority_master` (
  `priority_id` int(5) NOT NULL auto_increment,
  `priority_name` varchar(50) default NULL,
  `workspace_id` int(5) default NULL,
  `teamspace_id` int(5) default NULL,
  PRIMARY KEY  (`priority_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `helpdesk_priority_master` */

insert  into `helpdesk_priority_master`(`priority_id`,`priority_name`,`workspace_id`,`teamspace_id`) values (2,'Medium',5,NULL),(7,'High',5,NULL),(8,'Low',11,NULL),(9,'High',11,26),(10,'High',45,NULL),(12,'Low',45,NULL);

/*Table structure for table `helpdesk_significance_master` */

DROP TABLE IF EXISTS `helpdesk_significance_master`;

CREATE TABLE `helpdesk_significance_master` (
  `significance_id` int(5) NOT NULL auto_increment,
  `significance_name` varchar(50) default NULL,
  `workspace_id` int(5) default NULL,
  `teamspace_id` int(5) default NULL,
  PRIMARY KEY  (`significance_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `helpdesk_significance_master` */

insert  into `helpdesk_significance_master`(`significance_id`,`significance_name`,`workspace_id`,`teamspace_id`) values (1,'High',5,NULL),(2,'Medium',5,NULL),(3,'Low',5,NULL);

/*Table structure for table `helpdesk_status_master` */

DROP TABLE IF EXISTS `helpdesk_status_master`;

CREATE TABLE `helpdesk_status_master` (
  `status_id` int(5) NOT NULL auto_increment,
  `status_name` varchar(50) default NULL,
  `is_new` char(1) default 'n',
  `is_new_default` char(1) default 'n',
  `is_pending_approval` char(1) default 'n',
  `is_in_progress` char(1) default 'n',
  `is_completed` char(1) default 'n',
  `is_closed` char(1) default 'n',
  `is_deleted` char(1) default 'n',
  `workspace_id` int(5) default NULL,
  `teamspace_id` int(5) default NULL,
  PRIMARY KEY  (`status_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `helpdesk_status_master` */

insert  into `helpdesk_status_master`(`status_id`,`status_name`,`is_new`,`is_new_default`,`is_pending_approval`,`is_in_progress`,`is_completed`,`is_closed`,`is_deleted`,`workspace_id`,`teamspace_id`) values (1,'New','y','y','n','n','n','n','n',11,NULL),(2,'Pending Approval','n','n','y','n','n','n','n',11,NULL),(3,'In Progress','n','n','n','y','n','n','n',11,NULL),(4,'Completed','n','n','n','n','y','n','n',11,NULL),(5,'Closed','n','n','n','n','n','y','n',11,NULL),(6,'Deleted','n','n','n','n','n','n','y',11,NULL),(9,'New','n','y','n','n','n','n','n',11,26),(10,'In Progress','n','n','n','n','n','n','n',11,26),(11,'Completed','n','n','n','n','n','n','n',11,26),(12,'Closed','n','n','n','n','n','n','n',11,26),(13,'New','n','y','n','n','n','n','n',45,NULL),(14,'Closed','n','n','n','n','n','n','n',45,NULL),(15,'Pending','n','n','n','n','n','n','n',45,NULL);

/*Table structure for table `helpdesk_tag_master` */

DROP TABLE IF EXISTS `helpdesk_tag_master`;

CREATE TABLE `helpdesk_tag_master` (
  `tag_id` int(5) NOT NULL auto_increment,
  `tag_name` varchar(50) default NULL,
  `workspace_id` int(5) default NULL,
  `teamspace_id` int(5) default NULL,
  PRIMARY KEY  (`tag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `helpdesk_tag_master` */

insert  into `helpdesk_tag_master`(`tag_id`,`tag_name`,`workspace_id`,`teamspace_id`) values (1,'User',11,NULL),(2,'System',11,NULL);

/*Table structure for table `helpdesk_ticket_attachments` */

DROP TABLE IF EXISTS `helpdesk_ticket_attachments`;

CREATE TABLE `helpdesk_ticket_attachments` (
  `attachment_id` int(5) NOT NULL auto_increment,
  `ticket_id` int(5) default NULL,
  `filename` varchar(100) default NULL,
  `filesize` varchar(100) default NULL,
  `filetype` varchar(100) default NULL,
  `attachment` longblob,
  PRIMARY KEY  (`attachment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `helpdesk_ticket_attachments` */


/*Table structure for table `helpdesk_ticket_delegation` */

DROP TABLE IF EXISTS `helpdesk_ticket_delegation`;

CREATE TABLE `helpdesk_ticket_delegation` (
  `ticket_id` int(5) default NULL,
  `user_id` int(5) default NULL,
  `date_delegated` datetime default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `helpdesk_ticket_delegation` */

insert  into `helpdesk_ticket_delegation`(`ticket_id`,`user_id`,`date_delegated`) values (27,8,'2006-10-30 00:11:01'),(28,17,'2006-10-30 00:21:40'),(28,8,'2006-10-30 00:21:41'),(28,22,'2006-10-30 00:21:41'),(31,8,'2006-10-30 23:47:21'),(34,8,'2006-11-04 01:07:05'),(47,17,'2006-11-04 12:56:19'),(44,8,'2006-11-04 12:57:05'),(43,8,'2006-11-04 12:57:12'),(58,34,'2006-11-07 00:26:40'),(77,40,'2006-11-20 22:41:32'),(60,40,'2006-11-30 22:32:39'),(79,1,'2007-06-05 00:10:49');

/*Table structure for table `helpdesk_ticket_history` */

DROP TABLE IF EXISTS `helpdesk_ticket_history`;

CREATE TABLE `helpdesk_ticket_history` (
  `history_id` int(5) NOT NULL auto_increment,
  `ticket_id` int(5) default NULL,
  `description` varchar(255) default NULL,
  `user_id` int(5) default NULL,
  `date_logged` datetime default NULL,
  PRIMARY KEY  (`history_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `helpdesk_ticket_tags` */

DROP TABLE IF EXISTS `helpdesk_ticket_tags`;

CREATE TABLE `helpdesk_ticket_tags` (
  `ticket_id` int(5) default NULL,
  `tag_id` int(5) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `helpdesk_ticket_tags` */

insert  into `helpdesk_ticket_tags`(`ticket_id`,`tag_id`) values (63,1);

/*Table structure for table `helpdesk_tickets` */

DROP TABLE IF EXISTS `helpdesk_tickets`;

CREATE TABLE `helpdesk_tickets` (
  `ticket_id` int(5) NOT NULL auto_increment,
  `workspace_id` int(5) default NULL,
  `teamspace_id` int(5) default NULL,
  `category_id` int(5) default NULL,
  `priority_id` int(5) default NULL,
  `location_id` int(5) default NULL,
  `status_id` int(5) default NULL,
  `user_id_logging` int(5) default NULL,
  `user_id_problem` int(5) default NULL,
  `user_problem_name` varchar(255) default NULL,
  `user_problem_contact_tel_no` varchar(255) default NULL,
  `user_problem_contact_email` varchar(255) default NULL,
  `title` varchar(255) default NULL,
  `description` text,
  `technical_description` text,
  `solution` text,
  `technical_solution` text,
  `date_submit` datetime default NULL,
  `date_start_work` datetime default NULL,
  `date_estimated_completion` datetime default NULL,
  `date_complete` datetime default NULL,
  `feedback_id` int(5) default NULL,
  `review_id` int(5) default NULL,
  `feedback_comments` text,
  `review_comments` text,
  `significance_id` int(5) default NULL,
  `estimate_time_spent` int(5) default NULL,
  `actual_time_spent` int(5) default NULL,
  `date_due` datetime default NULL,
  `date_last_edit` datetime default NULL,
  `submit_month` tinyint(2) default NULL,
  `duplicate_ticket` char(1) default 'n',
  PRIMARY KEY  (`ticket_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `helpdesk_tickets` */

insert  into `helpdesk_tickets`(`ticket_id`,`workspace_id`,`teamspace_id`,`category_id`,`priority_id`,`location_id`,`status_id`,`user_id_logging`,`user_id_problem`,`user_problem_name`,`user_problem_contact_tel_no`,`user_problem_contact_email`,`title`,`description`,`technical_description`,`solution`,`technical_solution`,`date_submit`,`date_start_work`,`date_estimated_completion`,`date_complete`,`feedback_id`,`review_id`,`feedback_comments`,`review_comments`,`significance_id`,`estimate_time_spent`,`actual_time_spent`,`date_due`,`date_last_edit`,`submit_month`,`duplicate_ticket`) values (35,5,NULL,1,1,0,1,8,NULL,'','','','1','',NULL,NULL,NULL,'2006-09-04 01:36:00',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,9,'n'),(36,5,NULL,1,1,0,1,8,NULL,'','','','212343','',NULL,NULL,NULL,'2006-09-04 01:36:04',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,9,'n'),(37,5,NULL,1,1,0,1,8,NULL,'','','','3','',NULL,NULL,NULL,'2006-11-04 01:36:10',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,'n'),(38,5,NULL,1,1,0,1,8,NULL,'','','','4','',NULL,NULL,NULL,'2006-10-04 01:36:16',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,10,'n'),(39,5,NULL,1,1,0,1,8,NULL,'','','','5','',NULL,NULL,NULL,'2006-10-04 01:36:19',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,10,'n'),(40,5,NULL,1,1,0,1,8,NULL,'','','','6','',NULL,NULL,NULL,'2006-11-04 01:36:21',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,'n'),(41,5,NULL,1,1,0,1,8,NULL,'','','','7','',NULL,NULL,NULL,'2006-11-04 01:36:24',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,'n'),(42,5,NULL,2,1,0,1,8,NULL,'','','','1','',NULL,NULL,NULL,'2006-11-04 01:48:25',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,'n'),(43,5,NULL,2,1,0,1,8,NULL,'','','','fefsfsd','',NULL,NULL,NULL,'2006-11-04 01:48:29',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,'n'),(44,5,NULL,2,1,0,1,8,NULL,'','','','efwefr34t','',NULL,NULL,NULL,'2006-11-04 01:48:32',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,'n'),(45,5,NULL,1,1,0,1,8,NULL,'','','','fff','',NULL,NULL,NULL,'2006-11-04 01:48:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,'n'),(46,5,NULL,1,1,0,1,8,NULL,'','','','ggg','',NULL,NULL,NULL,'2006-11-04 01:48:37',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,'n'),(47,5,NULL,1,1,0,1,8,NULL,'','','','eee','',NULL,NULL,NULL,'2006-11-04 01:48:40',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,'n'),(48,5,NULL,1,1,0,1,34,NULL,'','','','Printer Model Support','',NULL,NULL,NULL,'2006-11-05 18:53:17',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,'n'),(49,5,NULL,2,3,0,1,34,NULL,'','','','Monitor Problem','',NULL,NULL,NULL,'2006-11-05 18:53:26',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,'n'),(50,5,NULL,1,1,0,1,34,NULL,'','','','Hard Drive Problem','hello world',NULL,NULL,NULL,'2006-11-05 18:53:33',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,'n'),(51,5,NULL,1,1,0,1,34,NULL,'','','','test','',NULL,NULL,NULL,'2006-11-07 00:13:23',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0000-00-00 00:00:00',NULL,11,'n'),(52,5,NULL,1,1,0,1,34,NULL,'','','','test','',NULL,NULL,NULL,'2006-11-07 00:14:43',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,'n'),(53,5,NULL,1,1,0,1,34,NULL,'','','','test1','',NULL,NULL,NULL,'2006-11-07 00:15:00',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2006-11-08 00:14:00',NULL,11,'n'),(54,5,NULL,2,3,0,1,34,34,'1','2','3','test11','122312 hahavxcvc',NULL,NULL,NULL,'2006-11-07 00:15:25',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2006-11-09 08:30:00',NULL,11,'n'),(55,5,NULL,1,1,0,1,34,NULL,'','','','test1','',NULL,NULL,NULL,'2006-11-07 00:16:54',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2006-11-08 00:14:00',NULL,11,'n'),(56,5,NULL,1,1,0,1,34,NULL,'','','','test1','',NULL,NULL,NULL,'2006-11-07 00:17:10',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2006-11-08 00:14:00',NULL,11,'n'),(57,5,NULL,1,1,0,1,34,NULL,'','','','test1','',NULL,NULL,NULL,'2006-11-07 00:17:12',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2006-11-08 00:14:00',NULL,11,'n'),(58,5,NULL,1,1,0,1,34,NULL,'','','','test11','',NULL,NULL,NULL,'2006-11-07 00:17:16',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2006-11-08 00:14:00',NULL,11,'n'),(59,11,NULL,7,8,0,1,40,NULL,'','','','Printer Model Support','',NULL,NULL,NULL,'2006-11-14 23:01:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,'n'),(60,11,NULL,7,8,0,1,40,NULL,'','','','Printer Model Support','',NULL,NULL,NULL,'2006-11-14 23:01:40',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,'n'),(61,11,NULL,7,8,0,1,40,NULL,'','','','Printer Model Support','',NULL,NULL,NULL,'2006-11-14 23:01:41',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,'n'),(62,11,NULL,7,8,0,1,40,NULL,'','','','Printer Model Support','',NULL,NULL,NULL,'2006-11-14 23:01:41',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,'n'),(63,11,NULL,7,8,0,1,40,0,'joe','012','joe@joe.com','Printer Model Support','desc','tech desc','soln','tech soln','2006-11-14 23:01:42','2006-11-19 18:43:00','2006-11-18 18:42:00',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2006-11-20 18:43:00',NULL,11,'n'),(64,11,26,12,9,0,9,40,NULL,'','','','Printer Model Support','',NULL,NULL,NULL,'2006-11-19 23:24:04',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,'n'),(65,11,26,12,9,0,9,40,NULL,'','','','Printer Model Support','',NULL,NULL,NULL,'2006-11-19 23:24:07',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,'n'),(66,11,26,12,9,0,9,40,NULL,'','','','Printer Model Support','',NULL,NULL,NULL,'2006-11-19 23:24:07',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,'n'),(67,11,26,12,9,0,9,40,NULL,'','','','Printer Model Support','',NULL,NULL,NULL,'2006-11-19 23:24:07',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,'n'),(68,11,26,12,9,0,9,40,NULL,'','','','Printer Model Support','',NULL,NULL,NULL,'2006-11-19 23:24:08',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,'n'),(69,11,26,12,9,0,9,40,NULL,'','','','Printer Model Support','',NULL,NULL,NULL,'2006-11-19 23:24:09',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,'n'),(70,11,26,12,9,0,9,40,NULL,'','','','Printer Model Support','',NULL,NULL,NULL,'2006-11-19 23:24:09',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,'n'),(71,11,26,12,9,0,9,40,NULL,'','','','Printer Model Support','',NULL,NULL,NULL,'2006-11-19 23:24:09',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,'n'),(72,11,26,12,9,0,9,40,NULL,'','','','Printer Model Support','',NULL,NULL,NULL,'2006-11-19 23:24:09',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,'n'),(73,11,26,12,9,0,9,40,NULL,'','','','Printer Model Support','',NULL,NULL,NULL,'2006-11-19 23:24:10',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,'n'),(74,11,26,12,9,0,9,40,NULL,'','','','Printer Model Support','',NULL,NULL,NULL,'2006-11-19 23:24:10',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,'n'),(75,11,26,12,9,0,9,40,NULL,'','','','Printer Model Support','',NULL,NULL,NULL,'2006-11-19 23:24:10',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,'n'),(76,11,26,12,9,0,9,40,NULL,'','','','Printer Model Support','',NULL,NULL,NULL,'2006-11-19 23:24:10',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,'n'),(77,11,26,12,9,0,9,40,NULL,'','','','Printer Model Support','',NULL,NULL,NULL,'2006-11-19 23:24:10',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,'n'),(78,11,NULL,9,8,0,1,40,0,'','','','Internet Connection','My internet connection has been down since Friday. Please send someone to investigate!','It appears to be a software problem.','','','2006-11-30 22:53:11','2006-12-02 22:53:00','2006-11-30 22:53:00',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0000-00-00 00:00:00',NULL,11,'n'),(79,45,NULL,13,10,0,15,1,2,'','','','test','test','','','','2007-06-05 00:09:34','0000-00-00 00:00:00','2007-06-06 00:11:00',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2007-06-06 00:09:00',NULL,6,'n');

/*Table structure for table `helpdesk_tickets_locked` */

DROP TABLE IF EXISTS `helpdesk_tickets_locked`;

CREATE TABLE `helpdesk_tickets_locked` (
  `ticket_id` int(5) NOT NULL auto_increment,
  `user_id` int(5) default NULL,
  `date_locked` datetime default NULL,
  PRIMARY KEY  (`ticket_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `helpdesk_tickets_locked` */

insert  into `helpdesk_tickets_locked`(`ticket_id`,`user_id`,`date_locked`) values (78,40,'2006-12-01 23:51:24');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
