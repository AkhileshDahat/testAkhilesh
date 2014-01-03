CREATE TABLE `core_history` (
  `history_id` int(11) NOT NULL AUTO_INCREMENT,
  `workspace_id` int(11) DEFAULT NULL,
  `teamspace_id` int(11) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `log_date` datetime NOT NULL,
  `module` varchar(100) DEFAULT NULL,
  `task` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`history_id`),
  UNIQUE KEY `history_id` (`history_id`)
) ENGINE=InnoDB AUTO_INCREMENT=194 DEFAULT CHARSET=utf8