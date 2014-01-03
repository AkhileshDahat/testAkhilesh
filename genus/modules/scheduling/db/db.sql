/*
SQLyog Enterprise - MySQL GUI v4.2 BETA 1
Host - 5.0.37 : Database - scheduling
*********************************************************************
Server version : 5.0.37
*/


create database if not exists `scheduling`;

USE `scheduling`;

/*Table structure for table `resource_booking_details` */

DROP TABLE IF EXISTS `resource_booking_details`;

CREATE TABLE `resource_booking_details` (
  `booking_id` int(5) NOT NULL auto_increment,
  `resource_id` int(5) default NULL,
  `date_time_from` datetime default NULL,
  `date_time_to` datetime default NULL,
  `sequence_id` int(5) default NULL,
  PRIMARY KEY  (`booking_id`),
  KEY `resource_id` (`resource_id`),
  KEY `sequence_id` (`sequence_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `resource_booking_details` */

/*Table structure for table `resource_booking_master` */

DROP TABLE IF EXISTS `resource_booking_master`;

CREATE TABLE `resource_booking_master` (
  `sequence_id` int(5) NOT NULL auto_increment,
  `date_from` date default NULL,
  `date_to` date default NULL,
  `repeat_type` varchar(10) default NULL,
  `repeat_day_of_week` char(3) default NULL,
  `repeat_day_of_month` tinyint(2) default NULL,
  `time_start` time default NULL,
  `time_end` time default NULL,
  `user_id` int(5) default NULL,
  `required_capacity` smallint(3) default NULL,
  `description` varchar(255) default NULL,
  PRIMARY KEY  (`sequence_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `resource_booking_master` */

/*Table structure for table `resource_item_master` */

DROP TABLE IF EXISTS `resource_item_master`;

CREATE TABLE `resource_item_master` (
  `item_id` int(5) NOT NULL auto_increment,
  `item_name` varchar(30) default NULL,
  PRIMARY KEY  (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `resource_item_master` */

insert into `resource_item_master` values (1,'LCD'),(2,'PC'),(3,'OHP'),(4,'PC-Equipped');

/*Table structure for table `resource_items` */

DROP TABLE IF EXISTS `resource_items`;

CREATE TABLE `resource_items` (
  `resource_id` int(5) NOT NULL default '0',
  `item_id` int(5) NOT NULL default '0',
  PRIMARY KEY  (`resource_id`,`item_id`),
  KEY `resource_id` (`resource_id`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `resource_items` */

/*Table structure for table `resource_master` */

DROP TABLE IF EXISTS `resource_master`;

CREATE TABLE `resource_master` (
  `resource_id` int(5) NOT NULL auto_increment,
  `resource_name` varchar(30) default NULL,
  `capacity` smallint(3) default NULL,
  `location` varchar(30) default NULL,
  `booking_priority` tinyint(1) default NULL,
  `time_day_open_mon` time default NULL,
  `time_day_close_mon` time default NULL,
  `time_day_open_tue` time default NULL,
  `time_day_close_tue` time default NULL,
  `time_day_open_wed` time default NULL,
  `time_day_close_wed` time default NULL,
  `time_day_open_thu` time default NULL,
  `time_day_close_thu` time default NULL,
  `time_day_open_fri` time default NULL,
  `time_day_close_fri` time default NULL,
  `time_day_open_sat` time default NULL,
  `time_day_close_sat` time default NULL,
  `time_day_open_sun` time default NULL,
  `time_day_close_sun` time default NULL,
  PRIMARY KEY  (`resource_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `resource_master` */

/*Table structure for table `scheduling_global_reserved_timeslots` */

DROP TABLE IF EXISTS `scheduling_global_reserved_timeslots`;

CREATE TABLE `scheduling_global_reserved_timeslots` (
  `day_of_week` char(3) default NULL,
  `time_from` time default NULL,
  `time_to` time default NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `scheduling_global_reserved_timeslots` */

/*Table structure for table `scheduling_lecturer_reserved_slots` */

DROP TABLE IF EXISTS `scheduling_lecturer_reserved_slots`;

CREATE TABLE `scheduling_lecturer_reserved_slots` (
  `user_id` int(5) NOT NULL,
  `time_start` time default NULL,
  `time_end` time default NULL,
  `day_of_week` char(3) default NULL,
  `workspace_id` int(5) default NULL,
  `teamspace_id` int(5) default NULL,
  PRIMARY KEY  (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `scheduling_lecturer_reserved_slots` */

/*Table structure for table `scheduling_subject_detail` */

DROP TABLE IF EXISTS `scheduling_subject_detail`;

CREATE TABLE `scheduling_subject_detail` (
  `subject_group_id` int(5) NOT NULL auto_increment,
  `subject_id` int(5) default NULL,
  `user_id` int(5) default NULL,
  `duration_hours` tinyint(1) default NULL,
  `date_start` date default NULL,
  `date_end` date default NULL,
  `capacity` smallint(3) default NULL,
  `description` varchar(100) default NULL,
  `sequence_id` int(5) default NULL COMMENT 'this col stores the reservation slot on resources booking',
  `day_of_week` char(3) default NULL,
  `time_start` time default NULL,
  `time_end` time default NULL,
  PRIMARY KEY  (`subject_group_id`),
  KEY `subject_id` (`subject_id`),
  KEY `user_id` (`user_id`),
  KEY `sequence_id` (`sequence_id`),
  CONSTRAINT `scheduling_subject_detail_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `scheduling_subject_master` (`subject_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `scheduling_subject_detail` */

insert into `scheduling_subject_detail` values (1,1,1,1,'2007-07-01','2007-11-30',24,'BITN9',NULL,'mon','13:30:00','14:30:00'),(2,1,2,2,'2007-07-01','2007-11-30',24,'BITN9',NULL,'fri','09:30:00','11:30:00'),(3,2,1,1,'2007-07-01','2007-11-30',15,'BITN4-BMSY4-BITC4',NULL,'thu','09:30:00','10:30:00');

/*Table structure for table `scheduling_subject_item_requirements` */

DROP TABLE IF EXISTS `scheduling_subject_item_requirements`;

CREATE TABLE `scheduling_subject_item_requirements` (
  `subject_id` int(5) default NULL,
  `item_id` int(5) default NULL,
  KEY `item_id` (`item_id`),
  CONSTRAINT `scheduling_subject_item_requirements_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `resource_item_master` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `scheduling_subject_item_requirements` */

insert into `scheduling_subject_item_requirements` values (1,1),(1,2),(1,4);

/*Table structure for table `scheduling_subject_master` */

DROP TABLE IF EXISTS `scheduling_subject_master`;

CREATE TABLE `scheduling_subject_master` (
  `subject_id` int(5) NOT NULL auto_increment,
  `subject_name` varchar(30) default NULL,
  PRIMARY KEY  (`subject_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `scheduling_subject_master` */

insert into `scheduling_subject_master` values (1,'SSWL'),(2,'Linux');
