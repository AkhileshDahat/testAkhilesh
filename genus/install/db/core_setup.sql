CREATE TABLE `core_setup` (
  `id` smallint(6) NOT NULL,
  `max_photo_size` int(11) DEFAULT NULL,
  `signup_max_teamspaces` smallint(6) DEFAULT NULL,
  `signup_max_size` smallint(6) DEFAULT NULL,
  `signup_max_users` smallint(6) DEFAULT NULL,
  `signup_expiry_days` smallint(6) DEFAULT NULL,
  `signup_workspace_logo` varchar(150) DEFAULT NULL,
  `allow_signup` char(1) DEFAULT NULL,
  `allow_forgot_password` char(1) DEFAULT NULL,
  `signup_optional_information` char(1) DEFAULT NULL,
  `signup_contact_information` char(1) DEFAULT NULL,
  `signup_enterprise` char(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1