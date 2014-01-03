CREATE TABLE `core_space_modules` (
  `workspace_id` int(11) NOT NULL,
  `teamspace_id` int(11) DEFAULT NULL,
  `module_id` int(11) NOT NULL,
  `date_start` date DEFAULT NULL,
  `date_end` date DEFAULT NULL,
  `datetime_installed` datetime DEFAULT NULL,
  UNIQUE KEY `workspace_id` (`workspace_id`,`teamspace_id`,`module_id`),
  KEY `FK_core_space_modules` (`workspace_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8