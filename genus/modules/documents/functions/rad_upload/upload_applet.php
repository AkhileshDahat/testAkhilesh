<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

function UploadApplet($url) {

	global $raduploadplus;

	$c="";
	echo "Rad upload".$GLOBALS['raduploadplus'];
	$useApplet=0;
	$user_agent = $_SERVER['HTTP_USER_AGENT'];

	if(stristr($user_agent,"konqueror") || stristr($user_agent,"macintosh") || stristr($user_agent,"opera")) {
		$useApplet=1;
		if ($GLOBALS['raduploadplus']) {
			$c.='<applet name="Rad Upload Lite" archive="modules/documents/radupload2/dndlite.jar" code="com.radinks.dnd.DNDAppletLite"	width="290"	height="290">';
		}
		else {
			$c.='<applet name="Rad Upload Lite" archive="modules/documents/raduploadplus/dndplus.jar" code="com.radinks.dnd.DNDAppletPlus"  MAYSCRIPT="yes" id="rup" width="290"	height="290">';
		}
	}
	else {
		if(strstr($user_agent,"MSIE")) {
			if ($GLOBALS['raduploadplus']) {
				$c.='<object classid="clsid:8AD9C840-044E-11D1-B3E9-00805F499D93" width= "200" height= "290" style="border-width:0;"  id="rup" codebase="http://java.sun.com/products/plugin/autodl/jinstall-1_4_1-windows-i586.cab#version=1,4,1">';
			}
			else {
				$c.='<object classid="clsid:8AD9C840-044E-11D1-B3E9-00805F499D93" width= "200" height= "290" style="border-width:0;"  id="rup" codebase="http://java.sun.com/products/plugin/autodl/jinstall-1_4_1-windows-i586.cab#version=1,4,1">';
			}

		}
		else {
			$c.='<object type="application/x-java-applet;version=1.4.1" width= "200" height= "290"  id="rup">';
		}
		if ($GLOBALS['raduploadplus']) {
			$c.='<param name="archive" value="modules/documents/raduploadplus/dndplus.jar">
						<param name="code" value="com.radinks.dnd.DNDAppletPlus">
						<param name="name" value="Rad Upload Plus">';
		}
		else {
			$c.='<param name="archive" value="modules/documents/radupload2/dndlite.jar">
						<param name="code" value="com.radinks.dnd.DNDAppletLite">
						<param name="name" value="Rad Upload Lite">';
		}
	}
	$c.='<param name="max_upload" value="1100">';
	$c.='<param name="message" value="Drag and drop your files here. Files that are uploaded can be viewed by clicking on the list button.">';
	$c.="<param name='url' value='".$url."'>";
	//$c.='<param name="url" value="http://192.168.1.6/framework_modules/modules/documents/raduploadplus/upload.php">';


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
