CREATE TABLE `helpdesk_significance_master` (
  `significance_id` int(5) NOT NULL AUTO_INCREMENT,
  `significance_name` varchar(50) DEFAULT NULL,
  `workspace_id` int(5) DEFAULT NULL,
  `teamspace_id` int(5) DEFAULT NULL,
  PRIMARY KEY (`significance_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8