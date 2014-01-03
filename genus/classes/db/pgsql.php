<?php
class pgsql {

	function Connect($hostname,$port,$database,$username,$password) { // DATABASE CONNECTION */

		$conn_string="host=".$hostname." port=".$port." dbname=".$database." user=".$username." password=".$password;
		//echo $conn_string;
		$this->result = pg_connect($conn_string);
		if (!$this->result) {
			//echo 'Connection to database server at: '.$hostname.' failed.';
		 	return false;
		}
		else {
			//echo "ok";
			return $this->result;
		}
	}

	function Query($query,$query_no="") { /* THE METHOD TO EXECUTE QUERIES */
  	$result = pg_query($this->result,$query) or die(ShowSQLError($query_no,$query));
  	return $result;
  }
  function FetchArray($result) { /* A METHOD TO RETURN THE RESULT AS AN ARRAY */
  	return pg_fetch_array($result);
  }

  function NumRows($result) { /* A METHOD TO RETURN THE NUMBER OF ROWS IN A RESULT */
  	return pg_num_rows($result);
  }

  function AffectedRows($result) { /* A METHOD TO DETERMINE HOW MANY ROWS WERE DELETED OR UPDATED */
  	return pg_affected_rows($result);
  }
  
  function LastInsertId($sequence) { // A METHOD TO OBTAIN THE LAST INSERTED AUTOINCREMENT ID
  	$db=$GLOBALS['db'];
		$sql="SELECT currval('".$GLOBALS['database_prefix'].$sequence."') AS currval";
		//echo $sql;
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				return $row['currval'];
			}
		}
		else {
			return False;
		}
  }

  function Begin() { /* A METHOD TO START A TRANSACTION */
  	$sql="BEGIN WORK";
  	$this->Query($sql);
  }

  function Commit() { /* A METHOD TO COMMIT A TRANSACTION */
  	$sql="COMMIT";
  	$this->Query($sql);
  }

  function Rollback() { /* A METHOD TO ROLLBACK A TRANSACTION */
  	$sql="ROLLBACK";
  	$this->Query($sql);
  }
}

function ShowSQLError($sql_id,$query="") {
	echo "An error has occured. Report being generated now...<br>";
	if ($_SERVER['HTTP_HOST'] != "idesk.sunway.edu.my") {
		echo "Error: ".$sql_id."<br>";
		echo "This is the SQL error:<p>";
		echo mysql_error()."<p>";
		echo "This is the SQL:<p>";
		echo $query."<br>";
	}
	//echo $data."<br>";
	$db=$GLOBALS['db'];
	//$sql="INSERT INTO error_sql_data (sql_id, output) VALUES ('".EscapeData($sql_id)."','".EscapeData(mysql_error())."')";
	//echo $sql."<br>";
	//$db->Query($sql);
	echo "Report generated. Please go back and continue. The problem will be resolved soon.<br>";
	die();
}
?>