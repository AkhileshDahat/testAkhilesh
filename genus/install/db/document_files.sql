CREATE TABLE `document_files` (
  `document_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `category_id` int(11) DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL,
  `date_start_publishing` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `date_end_publishing` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_id` int(11) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `filename` varchar(255) DEFAULT NULL,
  `filetype` varchar(50) DEFAULT NULL,
  `filesize` int(11) DEFAULT NULL,
  `attachment` longblob,
  `version_number` smallint(6) DEFAULT NULL,
  `user_id_checked_out` int(11) DEFAULT NULL,
  `date_checked_out` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `download_count` int(11) DEFAULT NULL,
  `user_id_locked` int(11) DEFAULT NULL,
  `date_locked` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `locked` char(1) DEFAULT 'n',
  `checked_out` char(1) DEFAULT 'n',
  `latest_version` char(1) DEFAULT 'n',
  `anonymous_download_key` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`document_id`),
  KEY `category_id` (`category_id`),
  KEY `status_id` (`status_id`),
  KEY `user_id` (`user_id`),
  KEY `user_id_checked_out` (`user_id_checked_out`),
  KEY `user_id_locked` (`user_id_locked`),
  KEY `filesize` (`filesize`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1