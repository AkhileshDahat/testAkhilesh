CREATE TABLE `helpdesk_ticket_delegation` (
  `ticket_id` int(5) DEFAULT NULL,
  `user_id` int(5) DEFAULT NULL,
  `date_delegated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8