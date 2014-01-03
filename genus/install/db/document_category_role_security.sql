CREATE TABLE `document_category_role_security` (
  `category_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `browse` char(1) DEFAULT 'y',
  `upload` char(1) DEFAULT 'n',
  `delete_files` char(1) DEFAULT 'n',
  PRIMARY KEY (`category_id`,`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1