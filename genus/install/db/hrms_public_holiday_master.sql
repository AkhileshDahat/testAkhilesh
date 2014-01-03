CREATE TABLE `hrms_public_holiday_master` (
  `date_pub_hol` date NOT NULL,
  `workspace_id` int(11) DEFAULT NULL,
  `teamspace_id` int(11) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`date_pub_hol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8