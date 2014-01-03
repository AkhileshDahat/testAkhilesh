CREATE TABLE `core_space_module_acl` (
  `workspace_id` int(11) NOT NULL,
  `teamspace_id` int(11) DEFAULT NULL,
  `module_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  KEY `FK_core_space_module_acl` (`workspace_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8