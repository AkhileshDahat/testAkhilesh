<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function UploadApplet($url) {

	$c="";

	$useApplet=0;
	$user_agent = $_SERVER['HTTP_USER_AGENT'];

	if(stristr($user_agent,"konqueror") || stristr($user_agent,"macintosh") || stristr($user_agent,"opera")) {
		$useApplet=1;
		$c.='<applet name="Rad Upload Lite" archive="modules/documents/raduploadplus/dndplus.jar" code="com.radinks.dnd.DNDAppletLite"	width="290"	height="290">';
	}
	else {
		if(strstr($user_agent,"MSIE")) {
			$c.='<object classid="clsid:8AD9C840-044E-11D1-B3E9-00805F499D93" width= "200" height= "290" style="border-width:0;"  id="rup" name="rup" codebase="http://java.sun.com/products/plugin/autodl/jinstall-1_4_1-windows-i586.cab#version=1,4,1">';

		}
		else {
			$c.='<object type="application/x-java-applet;version=1.4.1" width= "200" height= "290"  id="rup" name="rup">';
		}
		$c.='<param name="archive" value="modules/documents/raduploadplus/dndplus.jar">
					<param name="code" value="com.radinks.dnd.DNDAppletPlus">
					<param name="name" value="Rad Upload Plus">
					';

	}
	$c.="<param name='max_upload' value='7000'>";
	$c.="<param name='message' value='Drag and drop your files here. Files that are uploaded can be viewed by clicking on the refresh button.'>";
	$c.="<param name='url' value='".$url."'>";

	if(isset($_SERVER['PHP_AUTH_USER'])) {
		printf('<param name="chap" value="%s">',
		base64_encode($_SERVER['PHP_AUTH_USER'].":".$_SERVER['PHP_AUTH_PW']));
	}
	if($useApplet == 1)	{
		$c.='</applet>';
	}
	else {
	 $c.='</object>';
	}

	return $c;
}
?>
