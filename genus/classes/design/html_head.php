<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class HTMLHead {

	public function IncludeFile($v) {
		$this->$v=True;
	}

	function DrawHead() {
		$wb=$GLOBALS['wb'];

		$c="<html>\n";
		$c.="<head>\n";
		$c.="<title>".$GLOBALS['site_title']."</title>\n";

		if (ISSET($this->css)) {
			$c.="<link rel='stylesheet' href='".$wb."/css/css_".$GLOBALS['css_theme'].".css' type='text/css'>\n";
		}
		if (ISSET($this->mobilecss)) {
			$c.="<link rel='stylesheet' href='".$wb."/css/mobilecss.css' type='text/css'>\n";
		}
		if (ISSET($this->fusioncharts)) {
			$c.="<link rel='stylesheet' href='".$wb."include/FusionChartsFree/Contents/Style.css' type='text/css'>\n";
			$c.="<script language=\"JavaScript\" src=\"".$wb."include/FusionChartsFree/JSClass/FusionCharts.js\"></script>";
		}
		if (ISSET($this->jscalendar)) {
			$c.="<style type='text/css'>@import url(".$wb."include/jscalendar/calendar-win2k-1.css);</style>\n";
			$c.="<script type='text/javascript' src='".$wb."include/jscalendar/calendar.js'></script>\n";
			$c.="<script type='text/javascript' src='".$wb."include/jscalendar/lang/calendar-en.js'></script>\n";
			$c.="<script type='text/javascript' src='".$wb."include/jscalendar/calendar-setup.js'></script>\n";
		}
		if (ISSET($this->rico2rc2)) {

			$c.="<script src=\"include/rico2rc2/src/rico.js\" type=\"text/javascript\"></script>\n";
			$c.="<link href=\"include/rico2rc2/client/css/demo.css\" type=\"text/css\" rel=\"stylesheet\" />\n";
			$c.="
						<script type='text/javascript'>
				Rico.loadModule('LiveGridAjax','LiveGridMenu','greenHdg.css');

				var orderGrid,buffer;

				Rico.onLoad( function() {
				  var opts = {
				    columnSpecs   : [,,,,,,]
				  };
				  buffer=new Rico.Buffer.AjaxSQL('include/rico2rc2/examples/php/ricoXMLquery.php', {TimeOut:0});
				  orderGrid=new Rico.LiveGrid ('ex2', buffer, opts);
				  orderGrid.menu=new Rico.GridMenu();
				});

				</script>

				<style type='text/css'>
				div.ricoLG_cell {
				  white-space:nowrap;
				}
				</style>
				";
		}
		if (ISSET($this->rico2rc2a)) {

			$c.="<script src=\"include/rico2rc2a/src/rico.js\" type=\"text/javascript\"></script>\n";
			$c.="<link href=\"include/rico2rc2a/client/css/demo.css\" type=\"text/css\" rel=\"stylesheet\" />\n";
			$c.="
						<script type='text/javascript'>
				Rico.loadModule('LiveGridAjax','LiveGridMenu','greenHdg.css');

				var orderGrid,buffer;

				Rico.onLoad( function() {
				  var opts = {
				    columnSpecs   : [,,,,,,]
				  };
				  buffer=new Rico.Buffer.AjaxSQL('include/rico2rc2a/examples/php/ricoXMLquery.php', {TimeOut:0});
				  orderGrid=new Rico.LiveGrid ('ex2', buffer, opts);
				  orderGrid.menu=new Rico.GridMenu();
				});

				</script>

				<style type='text/css'>
				div.ricoLG_cell {
				  white-space:nowrap;
				  height:20px;
				}
				</style>
				";
		}
		if (ISSET($this->rico21)) {

			$c.="<script src=\"include/rico21/src/rico.js\" type=\"text/javascript\"></script>\n";
			$c.="<link href=\"include/rico21/client/css/demo.css\" type=\"text/css\" rel=\"stylesheet\" />\n";
			$c.="
						<script type='text/javascript'>
				Rico.loadModule('LiveGridAjax','LiveGridMenu','iegradient.css');

				var orderGrid,buffer;

				Rico.onLoad( function() {
				  var opts = {
				    columnSpecs   : [,,,,,,]
				  };
				  buffer=new Rico.Buffer.AjaxSQL('include/rico21/examples/php/ricoXMLquery.php', {TimeOut:0});
				  orderGrid=new Rico.LiveGrid ('ex2', buffer, opts);
				  orderGrid.menu=new Rico.GridMenu();
				});

				</script>

				<style type='text/css'>
				div.ricoLG_cell {
				  white-space:nowrap;
				}
				</style>
				";
		}
		if (ISSET($this->rico2rc2edit)) {

			$c .= "<script src=\"include/rico2rc2/src/prototype.js\" type=\"text/javascript\"></script>\n";
			$c .= "<script src=\"include/rico2rc2/src/rico.js\" type=\"text/javascript\"></script>\n";
			$c .= "<link href=\"include/rico2rc2/client/css/demo.css\" type=\"text/css\" rel=\"stylesheet\" />\n";

			$sqltext=".";  // force filtering to "on" in settings box
			//require_once $GLOBALS['dr']."include/rico2rc2/examples/php/applib.php";
			//require_once $GLOBALS['dr']."include/rico2rc2/plugins/php/ricoLiveGridForms.php";
			//require_once $GLOBALS['dr']."include/rico2rc2/examples/php/settings.php";
			//echo "ok1";
			$c .= "
						<script type='text/javascript'>
							Rico.loadModule('LiveGridForms','Calendar','Tree');
			";

			//setStyle();

			$c .= "

				// Results of Rico.loadModule may not be immediately available!
				// In which case, \"new Rico.CalendarControl\" would fail if executed immediately.
				// Therefore, wrap it in a function.
				// ricoLiveGridForms will call orders_FormInit right before grid & form initialization.

				function orders_FormInit() {
				  var cal=new Rico.CalendarControl(\"Cal\");
				  RicoEditControls.register(cal, Rico.imgDir+'calarrow.png');
				  cal.addHoliday(25,12,0,'Christmas','#F55','white');
				  cal.addHoliday(4,7,0,'Independence Day-US','#88F','white');
				  cal.addHoliday(1,1,0,'New Years','#2F2','white');

				  var CustTree=new Rico.TreeControl(\"CustomerTree\",\"CustTree.php\");
				  RicoEditControls.register(CustTree, Rico.imgDir+'dotbutton.gif');
				}
				</script>
				<style type=\"text/css\">
				div.ricoLG_outerDiv thead .ricoLG_cell, div.ricoLG_outerDiv thead td, div.ricoLG_outerDiv thead th {
					height:2.5em;
				}
				.ricoLG_bottom div.ricoLG_cell {
				  white-space:nowrap;
				}
				</style>

				";
		}

		if (ISSET($this->virtual_keyboard)) {
			$c.="<script type='text/javascript' src='".$wb."include/virtual_keyboard/1-vkboard/vkboard.js'></script>\n";
			$c.="<script type='text/javascript' src='".$wb."include/virtual_keyboard/1-vkboard/vkboardconfig.js'></script>\n";
		}

		//if (ISSET($this->ajaxgold)) {
			$c.="<script type='text/javascript' src='".$wb."include/ajaxgold/ajaxgold.js'></script>\n";
				$c.="<script language='Javascript'>
							function callback1(text) {
								document.getElementById(\"targetDiv\").innerHTML = text;
							}
						</script>
						";
		//}
		$c.="</head>\n";
		$c.="<body>\n";
		return $c;
	}

	function DrawBody($content,$icon="images/home/signup_icon.gif") {
		$wb=$GLOBALS['wb'];
		$c="<table align='center' width='780' cellpadding='0' cellspacing='0' border='0' class='plain'>\n";

			/* HEAD */
			$c.="<tr>\n";
				$c.="<td width='20' bgcolor='#3399CC'><img src='".$wb."images/curves/top_left.gif' width='20' height='42'></td>\n";
				$c.="<td width='740' bgcolor='#66CC33'><a href='index.php'>".$GLOBALS['site_logo']."</a></td>\n";
				$c.="<td width='20' bgcolor='#3399CC'><img src='".$wb."images/curves/top_right.gif' width='20' height='42'></td>\n";
			$c.="</tr>\n";

			/* DIVIDER BAR */
			$c.="<tr>\n";
				$c.="<td colspan='3'><br></td>\n";
			$c.="<tr>\n";

			/* CONTENT */

			$c.="<tr>\n";
				$c.="<td colspan='3'>\n";
				$c.="<table class='plain'>\n";
					$c.="<tr class='modulehead'>\n";
						$c.="<td>Greetings from Genus,<br></td>\n";
					$c.="</tr>\n";
					$c.="<tr>\n";
						$c.="<td>".$content."</td>\n";
						$c.="<td width='128'><img src='".$GLOBALS['wb'].$icon."'></td>\n";
					$c.="</tr>\n";
					$c.="<tr>\n";
						$c.="<td>The Genus Project is an online management tool for leave application, stationary, document and visitor management.</td>\n";
					$c.="</tr>\n";
					$c.="<tr class='modulehead'>\n";
						$c.="<td>Sincerely, <br><br>http://code.google.com/p/genusproject/</td>\n";
					$c.="</tr>\n";
				$c.="</table>\n";
				$c.="</td>\n";
			$c.="</tr>\n";

			/* DIVIDER BAR */
			$c.="<tr>\n";
				$c.="<td colspan='3'><br></td>\n";
			$c.="<tr>\n";
		$c.="</table>\n";
			/* FOOT */

		$c.="<div id=\"footer\">\n";
			$c.="<p class=\"right\">&copy;2004-".Date("Y")." </p>\n";
			$c.="<p><a href=\"#\">RSS Feed</a> &middot; <a href=\"#\">Contact</a> &middot; <a href=\"#\">Accessibility</a> &middot; <a href=\"#\">Products</a> &middot; <a href=\"#\">Disclaimer</a> &middot; <a href=\"http://jigsaw.w3.org/css-validator/check/referer\">CSS</a> and <a href=\"http://validator.w3.org/check?uri=referer\">XHTML</a><br /></p>\n";
		$c.="</div>\n";

		return $c;
	}

	function DrawFoot() {
		$c="</body>\n";
		$c.="</html>\n";
		return $c;
	}
}
?>