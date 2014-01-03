CREATE TABLE `helpdesk_status_master` (
  `status_id` int(5) NOT NULL AUTO_INCREMENT,
  `status_name` varchar(50) DEFAULT NULL,
  `is_new` char(1) DEFAULT 'n',
  `is_new_default` char(1) DEFAULT 'n',
  `is_pending_approval` char(1) DEFAULT 'n',
  `is_in_progress` char(1) DEFAULT 'n',
  `is_completed` char(1) DEFAULT 'n',
  `is_closed` char(1) DEFAULT 'n',
  `is_deleted` char(1) DEFAULT 'n',
  `workspace_id` int(5) DEFAULT NULL,
  `teamspace_id` int(5) DEFAULT NULL,
  PRIMARY KEY (`status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8