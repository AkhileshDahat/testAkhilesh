CREATE TABLE `core_content_master` (
  `content_id` int(5) NOT NULL AUTO_INCREMENT,
  `content_category_name` varchar(25) DEFAULT NULL,
  `content_data` text,
  PRIMARY KEY (`content_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8