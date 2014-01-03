CREATE TABLE `helpdesk_priority_master` (
  `priority_id` int(5) NOT NULL AUTO_INCREMENT,
  `priority_name` varchar(50) DEFAULT NULL,
  `workspace_id` int(5) DEFAULT NULL,
  `teamspace_id` int(5) DEFAULT NULL,
  PRIMARY KEY (`priority_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8