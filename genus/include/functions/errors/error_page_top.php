<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function ErrorPageTop($success,$content) {
	if ($success=="fail") {
		$img="cancel.png";
	}
	else {
		$img="apply.png";
	}

	return "<div class='table_dotted'><img src='images/nuvola/22x22/actions/".$img."'> ".$content."</div>\n";

	return $s;
}
?>