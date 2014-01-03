CREATE TABLE `document_file_approval` (
  `document_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `approved` char(1) DEFAULT 'n'
) ENGINE=InnoDB DEFAULT CHARSET=latin1