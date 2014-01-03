<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function CoreLog() {
	$db=$GLOBALS['db'];

	if (ISSET($_SERVER['HTTP_REFERER'])) { $http_referer=$_SERVER['HTTP_REFERER']; } else { $http_referer="";	}
	if (ISSET($_SERVER['HTTP_ACCEPT_LANGUAGE'])) { $http_accept_language=$_SERVER['HTTP_ACCEPT_LANGUAGE']; } else { $http_accept_language="";	}
	if (ISSET($_SERVER['HTTP_USER_AGENT'])) { $http_user_agent=$_SERVER['HTTP_USER_AGENT']; } else { $http_user_agent="";	}

	$sql="INSERT INTO ".$GLOBALS['database_prefix']."core_log (remote_addr,script_filename,http_accept_language,http_user_agent,http_referrer,date_hit)
				VALUES (
				'".EscapeData(VARISSET($_SERVER['REMOTE_ADDR']))."',
				'".EscapeData(VARISSET($_SERVER['SCRIPT_FILENAME']))."',
				'".EscapeData($http_accept_language)."',
				'".EscapeData($http_user_agent)."',
				'".EscapeData($http_referer)."',
				now()
				)";
	$result=$db->Query($sql);
	if ($db->AffectedRows($result) > 0) {
		return True;
	}
	else {
		return False;
	}
}

?>