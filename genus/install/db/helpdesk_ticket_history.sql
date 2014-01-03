CREATE TABLE `helpdesk_ticket_history` (
  `history_id` int(5) NOT NULL AUTO_INCREMENT,
  `ticket_id` int(5) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `user_id` int(5) DEFAULT NULL,
  `date_logged` datetime DEFAULT NULL,
  PRIMARY KEY (`history_id`)
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8