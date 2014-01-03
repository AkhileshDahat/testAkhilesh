CREATE TABLE `core_log` (
  `log_id` int(5) NOT NULL AUTO_INCREMENT,
  `remote_addr` varchar(255) DEFAULT NULL,
  `script_filename` varchar(255) DEFAULT NULL,
  `http_accept_language` varchar(255) DEFAULT NULL,
  `http_user_agent` varchar(255) DEFAULT NULL,
  `http_referrer` varchar(255) DEFAULT NULL,
  `date_hit` datetime DEFAULT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17141 DEFAULT CHARSET=latin1