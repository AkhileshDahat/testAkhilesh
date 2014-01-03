CREATE TABLE `core_space_task_acl` (
  `role_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `task` varchar(50) NOT NULL,
  `access` char(1) DEFAULT 'n',
  `workspace_id` int(5) DEFAULT NULL,
  `teamspace_id` int(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8