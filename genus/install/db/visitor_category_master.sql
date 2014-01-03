CREATE TABLE `visitor_category_master` (
  `category_id` int(5) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(50) DEFAULT NULL,
  `workspace_id` int(5) DEFAULT NULL,
  `teamspace_id` int(5) DEFAULT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8