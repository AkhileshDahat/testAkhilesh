CREATE TABLE `core_user_password_recovery` (
  `user_id` int(11) NOT NULL,
  `date_requested` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `secret_string` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1