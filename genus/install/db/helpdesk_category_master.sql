CREATE TABLE `helpdesk_category_master` (
  `category_id` int(5) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(50) DEFAULT NULL,
  `workspace_id` int(5) DEFAULT NULL,
  `teamspace_id` int(5) DEFAULT NULL,
  `graph_color` varchar(15) DEFAULT 'black',
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8