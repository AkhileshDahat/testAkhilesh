CREATE TABLE `helpdesk_ticket_attachments` (
  `attachment_id` int(5) NOT NULL AUTO_INCREMENT,
  `ticket_id` int(5) DEFAULT NULL,
  `filename` varchar(100) DEFAULT NULL,
  `filesize` varchar(100) DEFAULT NULL,
  `filetype` varchar(100) DEFAULT NULL,
  `attachment` longblob,
  PRIMARY KEY (`attachment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8