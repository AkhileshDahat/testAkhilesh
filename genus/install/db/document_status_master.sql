CREATE TABLE `document_status_master` (
  `status_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `status_name` varchar(25) DEFAULT NULL,
  `is_current` char(1) DEFAULT 'n',
  `is_pending` char(1) DEFAULT 'n',
  `is_archived` char(1) DEFAULT 'n',
  `is_deleted` char(1) DEFAULT 'n',
  `is_rejected` char(1) DEFAULT 'n',
  PRIMARY KEY (`status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1