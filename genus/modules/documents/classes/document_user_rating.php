<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class DocumentUserRating {

	public function SetParameters($document_id) {

		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;

		/* STORE IN CLASS GLOBAL VARIABLE */
		$this->document_id=$document_id;

		/* ENSURE IT'S A NUMBER */
		if (!IS_NUMERIC($this->document_id)) { $this->Errors("Invalid document"); return False; }

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		$this->Info();

		return True;
	}

	public function Info() {

		$db=$GLOBALS['db'];

		$sql="SELECT rating
					FROM ".$GLOBALS['database_prefix']."document_user_rating
					WHERE user_id = ".$_SESSION['user_id']."
					AND document_id = ".$this->document_id."
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				$this->rating=$row["rating"];
			}
		}
		else {
			$this->Errors("Invalid document requesteds");
			return False;
		}
	}
	public function Rate($rating) {

		if (!$this->parameter_check) { $this->Errors("Invalid data"); return False; }

		$db=$GLOBALS['db'];

		$sql="SELECT rating
					FROM ".$GLOBALS['database_prefix']."document_user_rating
					WHERE user_id = ".$_SESSION['user_id']."
					AND document_id = ".$this->document_id."
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {

			$sql="UPDATE ".$GLOBALS['database_prefix']."document_user_rating
						SET rating = ".$rating."
						WHERE document_id = ".$this->document_id."
						AND user_id = ".$_SESSION['user_id']."
						";
			$db->query($sql);
		}
		else {
			$sql="INSERT INTO ".$GLOBALS['database_prefix']."document_user_rating
					(document_id,user_id,rating)
					VALUES (
					'".$this->document_id."',
					'".$_SESSION['user_id']."',
					'".EscapeData($rating)."'
					)";
			$db->query($sql);
		}
	}

	public function GetInfo($v) {
		return $this->$v;
	}

	private function Errors($err) {
		$this->errors.=$err."<br>";
	}

	public function ShowErrors() {
		return $this->errors;
	}
}
?>