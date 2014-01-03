CREATE TABLE `core_space_user_modules` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `workspace_id` int(11) DEFAULT NULL,
  `teamspace_id` int(11) DEFAULT '0',
  `user_id` int(11) DEFAULT NULL,
  `module_id` int(11) DEFAULT NULL,
  `ordering` smallint(6) DEFAULT NULL,
  `location` char(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_key` (`user_id`,`workspace_id`,`teamspace_id`,`module_id`),
  KEY `FK_workspace_user_modules` (`workspace_id`),
  KEY `user_id` (`user_id`),
  KEY `teamspace_id` (`teamspace_id`),
  KEY `module_id` (`module_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8