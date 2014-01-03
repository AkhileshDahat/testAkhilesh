CREATE TABLE `core_error_messages` (
  `error_id` int(11) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(50) DEFAULT NULL,
  `page` varchar(100) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `alert_type` varchar(10) DEFAULT NULL,
  `popup` char(1) DEFAULT 'n',
  PRIMARY KEY (`error_id`)
) ENGINE=InnoDB AUTO_INCREMENT=98 DEFAULT CHARSET=latin1