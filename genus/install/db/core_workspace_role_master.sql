CREATE TABLE `core_workspace_role_master` (
  `workspace_id` int(11) DEFAULT NULL,
  `teamspace_id` int(11) DEFAULT NULL,
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(30) DEFAULT NULL,
  `default_role` char(1) DEFAULT 'n',
  `create_teamspace` char(1) DEFAULT 'n',
  `manage_workspaces` char(1) DEFAULT 'n',
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8