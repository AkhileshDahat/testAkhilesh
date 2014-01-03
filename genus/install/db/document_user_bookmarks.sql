CREATE TABLE `document_user_bookmarks` (
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`category_id`),
  KEY `FK_document_user_bookmarks` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1