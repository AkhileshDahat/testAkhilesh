CREATE TABLE `document_user_settings` (
  `user_id` int(11) NOT NULL,
  `col_size` tinyint(1) DEFAULT NULL,
  `col_owner` char(1) DEFAULT 'n',
  `col_rating` char(1) DEFAULT 'n',
  `show_rad_upload` char(1) DEFAULT 'n',
  `total_cut_documents` smallint(6) DEFAULT '0',
  `workspace_id` int(11) DEFAULT NULL,
  `teamspace_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1