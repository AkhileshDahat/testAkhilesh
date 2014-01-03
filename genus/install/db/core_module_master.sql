CREATE TABLE `core_module_master` (
  `module_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `dashboard_filename` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `introduction` text,
  `core_module` char(1) DEFAULT 'n',
  `default_module` char(1) DEFAULT 'n',
  `available_teamspaces` char(1) DEFAULT 'n',
  `signup_module` char(1) DEFAULT 'n',
  `available_all_workspaces` char(1) DEFAULT 'n',
  `anonymous_access` char(1) DEFAULT 'n',
  PRIMARY KEY (`module_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1