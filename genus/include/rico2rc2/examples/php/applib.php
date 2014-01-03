<?php
define( '_VALID_MVH', 1 );
require "../../../../config.php";
require $GLOBALS['dr']."include/rico2rc2/plugins/php/dbClass2.php";
//require "../../plugins/php/dbClass2.php";
//require "D:/Program Files/xampp/htdocs/genus/include/rico2rc2/plugins/php/dbClass2.php";
$appName="genus";
//$appDB="genus";
$appDB = $GLOBALS['database_name'];

function CreateDbClass() {
  global $oDB;
  $oDB = new dbClass();
  //$oDB->Dialect="Oracle";
  //$oDB->Dialect="TSQL";
  //$oDB->Dialect="Access";
}

function OpenDB() {
  CreateDbClass();
  //return $GLOBALS['oDB']->MySqlLogon($GLOBALS['appDB'], "root", "rootroot");
  return $GLOBALS['oDB']->MySqlLogon($GLOBALS['appDB'], $GLOBALS['database_user'], $GLOBALS['database_password']);

  //return $GLOBALS['oDB']->OdbcLogon("northwindDSN","Northwind","userid","password");
  //return $GLOBALS['oDB']->OracleLogon("XE","northwind","password");
}

function OpenApp($title) {
  $_retval=false;
  if (!OpenDB()) {
    return $_retval;
  }
  if (!empty($title)) {
    AppHeader($GLOBALS['appName']."-".$title);
  }
  $GLOBALS['accessRights']="rw";
  // CHECK APPLICATION SECURITY HERE  (in this example, "r" gives read-only access and "rw" gives read/write access)
  if (empty($GLOBALS['accessRights']) || !isset($GLOBALS['accessRights']) || substr($GLOBALS['accessRights'],0,1) != "r") {
    echo "<p class='error'>You do not have permission to access this application";
  }
  else {
    $_retval=true;
  }
  return $_retval;
}

function OpenTableEdit($tabname) {
  $obj= new TableEditClass();
  $obj->SetTableName($tabname);
  $obj->options["XMLprovider"]=$GLOBALS['wb']."include/rico2rc2/examples/php/ricoXMLquery.php";
  //$obj->options["XMLprovider"]="http://192.168.1.9:81/genus/xinclude/rico2rc2/examples/php/ricoXMLquery.php";
  $obj->convertCharSet=true;   // because sample database is ISO-8859-1 encoded
  return $obj;
}

function OpenGridForm($title, $tabname) {
  $_retval=false;
  if (!OpenApp($title)) {
    return $_retval;
  }
  $GLOBALS['oForm']= OpenTableEdit($tabname);
  $CanModify=($GLOBALS['accessRights'] == "rw");
  $GLOBALS['oForm']->options["canAdd"]=$CanModify;
  $GLOBALS['oForm']->options["canEdit"]=$CanModify;
  $GLOBALS['oForm']->options["canDelete"]=$CanModify;
  session_set_cookie_params(60*60);
  $GLOBALS['sqltext']='.';
  return true;
}

function CloseApp() {
  global $oDB;
  if (is_object($oDB)) $oDB->dbClose();
  $oDB=NULL;
  $GLOBALS['oForm']=NULL;
}

function AppHeader($hdg) {
  echo "<h2 class='appHeader'>".str_replace("<dialect>",$GLOBALS['oDB']->Dialect,$hdg)."</h2>";
}
?>

