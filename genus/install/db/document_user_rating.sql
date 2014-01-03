CREATE TABLE `document_user_rating` (
  `document_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `rating` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1