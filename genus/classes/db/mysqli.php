<?php
class MySQLFunctions {

	public $hostname;// HOSTNAME THAT THE OBJECT SHOULD CONNECT TO
  public $username;// USERNAME THAT THE OBJECT SHOULD USE
  public $password;// PASSWORD THAT THE OBJECT SHOULD USE
  public $database;// DATABASE THAT THE OBJECT SHOULD USE
  public $query_num;// COUNTS THE TOTAL QUERIES THAT THE OBJECT HAS DONE. SOME BBS DO THIS. MIGHT BE OF USE FOR OPTIMIZATION

	function set_cred($hostname,$username,$password,$database,$port) { // A METHOD TO SET THE CREDENTIALS TO CONNECT TO THE DATABASE
		$this->hostname=$hostname;
		$this->username=$username;
		$this->password=$password;
		$this->database=$database;
		$this->port=$port;
	}
	function db_connect() { // DATABASE CONNECTION

		//$result = mysqli_connect($this->hostname,$this->username,$this->password,$this->database,$this->port); // DATABASE CONNECTION
		$this->dbconn = mysqli_connect("192.168.1.6","root","","mvh",3306);

		if (!$this->dbconn) {
			echo mysqli_connect_error();
			//echo mysql_connect_error();
			//echo 'Connection to database server at: '.$this->hostname.' failed.';
		 	die();
		 	//return false;
		}
		else {
			return $this->dbconn;
		}
	}
	function db_pconnect() { // PERSISTENT CONNECTION
		$result = mysql_pconnect($this->hostname, $this->username, $this->password);

		if (!$result) {
			echo 'Connection to database server at: '.$this->hostname.' failed.';
			return false;
		}
		return $result;
	}
	function query($query,$query_no="") { // THE METHOD TO EXECUTE QUERIES
  	$result = mysqli_query($this->dbconn,$query) or die(ShowSQLError($query_no,$query));
  	return $result;
  }
  function fetch_array($result) { // A METHOD TO RETURN THE RESULT AS AN ARRAY
  	return mysqli_fetch_array($result);
  }
  function fetch_assoc($result) { // AN ALTERNATIVE METHOD TO RETURN AS AN ASSOCIATIVE ARRAY
  	return mysqli_fetch_assoc($this->dbconn,$result);
  }
  function fetch_row($result) { // AN ALTERNATIVE METHOD TO RETURN ROWS
    $query = mysqli_fetch_row($this->dbconn,$result);
    return $result;
  }
  function return_query_num() { // A METHOD TO RETURN THE QUERY NUMBER
    return $this->query_num;
  }
  function num_rows($result) { // A METHOD TO RETURN THE NUMBER OF ROWS IN A RESULT
  	return mysqli_num_rows($result);
  }
  function affected_rows() { // A METHOD TO DETERMINE HOW MANY ROWS WERE AFFECTED BY THE QUERY
  	return mysqli_affected_rows($this->dbconn);
  }
  function last_insert_id() { // A METHOD TO OBTAIN THE LAST INSERTED AUTOINCREMENT ID
  	return mysqli_insert_id($this->dbconn);
  }
  function start_transaction() { // A METHOD TO START A TRANSACTION
  	mysql_query("set autocommit=0");
  }
  function commit() { // COMMIT
  	mysql_query("commit");
  }
  function rollback() { // ROLLBACK
  	mysql_query("rollback");
  }
}

function ShowSQLError($sql_id,$query="") {
	echo "An error has occured. Report being generated now...<br>";

	echo "Error: ".$sql_id."<br>";
	echo "This is the SQL error:<p>";
	echo mysql_error()."<p>";
	echo "This is the SQL:<p>";
	echo $query."<br>";

	//echo $data."<br>";
	$db=$GLOBALS['db'];
	$sql="INSERT INTO error_sql_data (sql_id, output) VALUES ('".EscapeData($sql_id)."','".EscapeData(mysql_error())."')";
	//echo $sql."<br>";
	//$db->query($sql);
	echo "Report generated. Please go back and continue. The problem will be resolved soon.<br>";
	die();
}
?>