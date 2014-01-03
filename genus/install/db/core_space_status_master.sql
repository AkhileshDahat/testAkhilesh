CREATE TABLE `core_space_status_master` (
  `status_id` int(11) NOT NULL AUTO_INCREMENT,
  `status_name` varchar(20) DEFAULT NULL,
  `default_signup` char(1) DEFAULT 'n',
  `is_active` char(1) DEFAULT 'y',
  `is_blocked` char(1) DEFAULT 'n',
  PRIMARY KEY (`status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8