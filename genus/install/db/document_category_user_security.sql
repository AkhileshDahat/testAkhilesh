CREATE TABLE `document_category_user_security` (
  `category_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `acl_upload` char(1) DEFAULT 'n',
  `acl_lock` char(1) DEFAULT 'n',
  `acl_unlock` char(1) DEFAULT 'n',
  `acl_move` char(1) DEFAULT 'n',
  `acl_paste` char(1) DEFAULT 'n',
  `acl_delete` char(1) DEFAULT 'n',
  `acl_delete_version` char(1) DEFAULT 'n'
) ENGINE=InnoDB DEFAULT CHARSET=latin1