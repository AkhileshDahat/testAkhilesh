CREATE TABLE `core_space_users` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `workspace_id` int(5) NOT NULL,
  `teamspace_id` int(5) DEFAULT NULL,
  `user_id` int(5) NOT NULL,
  `role_id` int(5) DEFAULT NULL,
  `theme` varchar(20) DEFAULT 'default',
  `approved` char(1) DEFAULT 'n',
  PRIMARY KEY (`id`),
  UNIQUE KEY `workspace_id` (`workspace_id`,`teamspace_id`,`user_id`),
  KEY `FK_workspace_users` (`workspace_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8