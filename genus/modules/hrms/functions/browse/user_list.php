<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."include/functions/misc/yes_no_image.php");

class UserList {

	public function __construct() {
		$this->c="";
	}

	public function Filter() {
		$c="<form method='get' action='index.php'>\n";
		$c.="<input type='hidden' name='module' value='hrms'>\n";
		$c.="<input type='hidden' name='task' value='user_list'>\n";
		if (ISSET($_GET['search_full_name'])) { $search_full_name=EscapeData($_GET['search_full_name']); } else { $search_full_name=""; }
		$c.="Search: <input type='text' name='search_full_name' value='".$search_full_name."'>\n";
		$c.="</form>\n";
		return $c;
	}

	public function SQLFilter() {
		if (ISSET($_GET['search_full_name'])) {
			return "AND cum.full_name LIKE '%".EscapeData($_GET['search_full_name'])."%'";
		}
	}

	public function ShowUsers() {
		$db=$GLOBALS['db'];

		$sql="SELECT cum.user_id, cum.full_name, cum.login, cum.logged_in, cwrm.role_name
					FROM ".$GLOBALS['database_prefix']."core_space_users cus, ".$GLOBALS['database_prefix']."core_user_master cum,
					".$GLOBALS['database_prefix']."core_workspace_role_master cwrm
					WHERE cus.workspace_id = ".$GLOBALS['workspace_id']."
					AND cus.teamspace_id ".$GLOBALS['teamspace_sql']."
					AND cus.approved = 'y'
					AND cus.user_id = cum.user_id
					".$this->SQLFilter()."
					AND cus.role_id = cwrm.role_id
					ORDER BY cum.full_name
					";

		//echo $sql."<br>";
		$result = $db->Query($sql);
		$this->c.="<table class='plain' border='0'>\n";
			$this->c.="<tr>\n";
				$this->c.="<td class='bold' colspan='3'>User Directory</td>\n";
				$this->c.="<td class='bold' colspan='4'>".$this->Filter()."</td>\n";
			$this->c.="</tr>\n";
			$this->c.="<tr class='colhead'>\n";
				//$this->c.="<td bgcolor='#ffffff'></td>\n";
				$this->c.="<td>Full Name</td>\n";
				$this->c.="<td>Email</td>\n";
				$this->c.="<td>Role</td>\n";
				$this->c.="<td>Logged In</td>\n";
				$this->c.="<td>Edit</td>\n";
				$this->c.="<td>Delete</td>\n";
			$this->c.="</tr>\n";
			if ($db->NumRows($result) > 0) {
				while($row = $db->FetchArray($result)) {
					$this->c.="<tr>\n";
						//$this->c.="<td><img src='bin/binary/staff_photo.php?user_id=".$row['user_id']."' width='48' height='48'></td>\n";
						$this->c.="<td>".$row['full_name']."</td>\n";
						$this->c.="<td>".$row['login']."</td>\n";
						$this->c.="<td>".$row['role_name']."</td>\n";
						$this->c.="<td align='center'>".YesNoImage($row['logged_in'])."</td>\n";
						$this->c.="<td><a href='index.php?module=hrms&task=add_user&user_id=".$row['user_id']."'>Edit</a></td>\n";
						$this->c.="<td><a href='index.php?module=hrms&task=user_list&subtask=delete_user&user_id=".$row['user_id']."'>Remove</a></td>\n";
					$this->c.="</tr>\n";
				}
			}
		$this->c.="</table>\n";
		return $this->c;
	}

	function ShowUsersAjax() {
		$c="";

		$sqltext="SELECT login,full_name,logged_in,concat('<a href=index.php?module=hrms&task=add_user&user_id=',user_id,'>Edit</a>') as edit FROM framework.core_user_master";
		$_SESSION['ex3']=$sqltext;
		require $GLOBALS['wb']."include/rico/examples/php/chklang.php";
		require $GLOBALS['wb']."include/rico/examples/php/settings.php";


		$c.="<script src=\"".$GLOBALS['wb']."include/rico/src/rico.js\" type=\"text/javascript\"></script>\n";
		$c.="<link href=\"".$GLOBALS['wb']."include/rico/examples/client/css/demo.css\" type=\"text/css\" rel=\"stylesheet\" />\n";
		$c.="<script type='text/javascript'>\n";
			$c.="Rico.loadModule('LiveGridAjax');\n";
			//$c.="Rico.loadModule('LiveGridMenu');\n";

		/* PHP */
		//$c.=setStyle();
		$c.="Rico.include('greenHdg.css');";
		//$c.=setLang();

		/* JS */
		$c.="var ex3,buffer,lastVal=[];\n";

		$c.="Rico.onLoad( function() {\n";
	  $c.="var opts = {\n";
	    $c.="frozenColumns : 1,\n";
	    $c.="canFilterDefault: false,\n";
	    $c.="columnSpecs   : [,,,],\n";
	    $c.="headingRow    : 1\n";
	  $c.="};\n";
	  $c.="buffer=new Rico.Buffer.AjaxSQL('ricoXMLquery.php', {TimeOut:0});\n";
	  $c.="ex3=new Rico.LiveGrid ('ex3', buffer, opts);\n";
	  //$c.="ex3.menu=new Rico.GridMenu(".GridSettingsMenu().");\n";
		$c.="});\n";

		$c.="function keyfilter(txtbox,idx) {\n";
	  	$c.="if (typeof lastVal[idx] != 'string') lastVal[idx]='';\n";
	  	$c.="if (lastVal[idx]==txtbox.value) return;\n";
	  	$c.="lastVal[idx]=txtbox.value;\n";
	  	$c.="Rico.writeDebugMsg(\"keyfilter: \"+idx+' '+txtbox.value);\n";
	  	$c.="if (txtbox.value=='')\n";
	    	$c.="ex3.columns[idx].setUnfiltered();\n";
	  	$c.="else\n";
	    	$c.="ex3.columns[idx].setFilter('LIKE',txtbox.value+'*',Rico.TableColumn.USERFILTER,function() {txtbox.value='';});\n";
		$c.="}\n";
		$c.="</script>\n";

		$c.="<style type=\"text/css\">\n";
		$c.="input { font-weight:normal;font-size:8pt;}\n";
		$c.="th div.ricoLG_cell { height:1.5em; }  /* the text boxes require a little more height than normal */\n";
		$c.="</style>\n";

		/* PHP */
		//require "menu.php";
		//$c.="<table id='explanation' border='0' cellpadding='0' cellspacing='5' style='clear:both'><tr valign='top'><td>";
		//GridSettingsForm();

		//$c.="</td><td>This grid demonstrates how filters can be applied as the user types.\n";
		//$c.="</td></tr></table>\n";

		$c.="<p class=\"ricoBookmark\"><span id=\"ex3_bookmark\">&nbsp;</span></p>\n";
		$c.="<table id=\"ex3\" class=\"ricoLiveGrid\" cellspacing=\"0\" cellpadding=\"0\">\n";
		$c.="<colgroup>\n";
			$c.="<col style=\"width:250px;\" >\n";
			$c.="<col style=\"width:150px;\" >\n";
			$c.="<col style=\"width:90px;\">\n";
			$c.="<col style=\"width:90px;\">\n";
		$c.="</colgroup>\n";
		$c.="<thead>\n";
	  $c.="<tr id=\"ex3_main\">\n";
		  $c.="<th class=\"ricoFrozen\">Login</th>\n";
		  $c.="<th>Name</th>\n";
		  $c.="<th>Logged In</th>\n";
		  $c.="<th>Edit</th>\n";
	  $c.="</tr>\n";
	  $c.="<tr class=\"dataInput\">\n";
		  $c.="<th class=\"ricoFrozen\"><input type=\"text\" onkeyup=\"keyfilter(this,0)\" size=\"15\"></th>\n";
		  $c.="<th><input type=\"text\" onkeyup=\"keyfilter(this,1)\" size=\"15\"></th>\n";
		  $c.="<th><input type=\"text\" onkeyup=\"keyfilter(this,2)\" size=\"1\"></th>\n";
		  $c.="<th>&nbsp;</th>\n";
	  $c.="</tr>\n";
		$c.="</thead>\n";
		$c.="</table>\n";

		$c.="<!--\n";
		$c.="<textarea id=\"ex3_debugmsgs\" rows=\"5\" cols=\"80\" style=\"font-size:smaller;\"></textarea>\n";
		$c.="-->\n";

		return $c;
	}
}

function UserList($type,$id="") {
	$obj_ul=new UserList;
	return $obj_ul->ShowUsers();
}
?>