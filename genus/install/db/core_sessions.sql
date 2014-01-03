CREATE TABLE `core_sessions` (
  `session_id` varchar(255) DEFAULT NULL,
  `session_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `session_data` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1