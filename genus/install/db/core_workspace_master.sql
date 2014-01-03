CREATE TABLE `core_workspace_master` (
  `workspace_id` int(5) NOT NULL AUTO_INCREMENT,
  `workspace_code` varchar(10) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT 'blend.png',
  `max_teamspaces` smallint(6) DEFAULT NULL,
  `max_size` smallint(6) DEFAULT NULL,
  `max_users` smallint(6) DEFAULT NULL,
  `date_start` date DEFAULT NULL,
  `date_end` date DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT '0',
  `enterprise` tinyint(1) DEFAULT '0',
  `user_id_added` int(11) DEFAULT NULL,
  `current_size` smallint(6) DEFAULT NULL,
  `current_size_last_run` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`workspace_id`),
  KEY `status_id` (`status_id`),
  KEY `user_id_added` (`user_id_added`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8