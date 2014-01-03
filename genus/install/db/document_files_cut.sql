CREATE TABLE `document_files_cut` (
  `user_id` int(11) NOT NULL,
  `document_id` int(11) NOT NULL,
  `workspace_id` int(11) DEFAULT NULL,
  `teamspace_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_id`,`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1