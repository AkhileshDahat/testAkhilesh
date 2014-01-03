CREATE TABLE `core_teamspace_master` (
  `teamspace_id` int(11) NOT NULL AUTO_INCREMENT,
  `workspace_id` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `icon` varchar(50) DEFAULT 'nuvola/16x16/actions/fileopen.png',
  `date_valid_from` datetime DEFAULT NULL,
  `date_valid_to` datetime DEFAULT NULL,
  PRIMARY KEY (`teamspace_id`),
  KEY `workspace_id` (`workspace_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8