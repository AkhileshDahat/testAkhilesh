CREATE TABLE `visitor_master` (
  `visitor_id` int(5) NOT NULL AUTO_INCREMENT,
  `workspace_id` int(5) DEFAULT NULL,
  `teamspace_id` int(5) DEFAULT NULL,
  `vehicle_registration_number` varchar(25) DEFAULT NULL,
  `visitor_full_name` varchar(100) DEFAULT NULL,
  `visitor_identification_number` varchar(100) DEFAULT NULL,
  `visitor_contact_number` varchar(100) DEFAULT NULL,
  `user_id` int(5) DEFAULT NULL,
  `visitor_category_id` int(5) DEFAULT NULL,
  `date_expected` datetime DEFAULT NULL,
  `date_arrival` datetime DEFAULT NULL,
  `date_departed` datetime DEFAULT NULL,
  `total_guests` varchar(100) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  PRIMARY KEY (`visitor_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8