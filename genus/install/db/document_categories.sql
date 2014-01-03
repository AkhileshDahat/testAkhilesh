CREATE TABLE `document_categories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `category_name` varchar(50) DEFAULT NULL,
  `category_description` varchar(255) DEFAULT NULL,
  `workspace_id` int(11) DEFAULT NULL,
  `teamspace_id` int(11) DEFAULT NULL,
  `requires_approval` char(1) DEFAULT 'n',
  `locked` char(1) DEFAULT 'n',
  `user_id_locked` int(11) DEFAULT NULL,
  PRIMARY KEY (`category_id`),
  KEY `teamspace_id` (`teamspace_id`),
  KEY `user_id_locked` (`user_id_locked`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1