CREATE TABLE `document_file_role_security` (
  `document_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`document_id`,`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1