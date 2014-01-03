CREATE TABLE `core_role_master` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(30) DEFAULT NULL,
  `create_max_workspaces` smallint(6) DEFAULT NULL,
  `create_workspace` char(1) DEFAULT 'n',
  `manage_core_workspaces` char(1) DEFAULT 'n',
  `default_role` char(1) DEFAULT 'n',
  `create_workspace_roles` char(1) DEFAULT 'n',
  `browse_master_modules` char(1) DEFAULT 'n',
  `manage_core_users` char(1) DEFAULT 'n',
  `manage_workspace` char(1) DEFAULT 'n',
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1