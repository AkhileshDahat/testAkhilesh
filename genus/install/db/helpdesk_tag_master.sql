CREATE TABLE `helpdesk_tag_master` (
  `tag_id` int(5) NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(50) DEFAULT NULL,
  `workspace_id` int(5) DEFAULT NULL,
  `teamspace_id` int(5) DEFAULT NULL,
  PRIMARY KEY (`tag_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8