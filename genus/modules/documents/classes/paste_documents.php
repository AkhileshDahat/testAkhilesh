<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

class PasteDocuments {

	function __construct() {

	}
	public function SetParameters($category_id) {

		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;

		/* CHECKS */
		if (!IS_NUMERIC($category_id)) { $this->Errors("Invalid Category"); return False; }

		/* SET SOME COMMON VARIABLES */
		$this->category_id=$category_id;

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		return True;
	}

	public function Paste() {

		$db=$GLOBALS['db'];

		$sql="SELECT dfc.document_id, df.category_id, df.filename
					FROM ".$GLOBALS['database_prefix']."document_files_cut dfc, ".$GLOBALS['database_prefix']."document_files df
					WHERE dfc.user_id = '".$_SESSION['user_id']."'
					AND dfc.workspace_id = ".$GLOBALS['workspace_id']."
					AND dfc.teamspace_id ".$GLOBALS['teamspace_sql']."
					AND dfc.document_id = df.document_id
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);

		if ($db->NumRows($result) > 0) {
			while($row=$db->FetchArray($result)) {
				$sql_update="UPDATE ".$GLOBALS['database_prefix']."document_files
										SET category_id = ".$this->category_id.",
										locked = 'n',
										date_locked = null
										WHERE filename = '".$row['filename']."'
										AND category_id = ".$row['category_id']."
										";
				$result_update = $db->Query($sql_update);
				if ($db->AffectedRows($result_update) == 0) {
					$this->Errors("Unable to move document/s");
					return False;
				}
			}
		}
		/* DELETE THE CLIPBOARD CONTENTS */
		$sql_del_clipboard="DELETE FROM ".$GLOBALS['database_prefix']."document_files_cut
												WHERE user_id = '".$_SESSION['user_id']."'
												AND workspace_id = ".$GLOBALS['workspace_id']."
												AND teamspace_id ".$GLOBALS['teamspace_sql']."
												";
		//echo $sql."<br>";
		$result = $db->Query($sql_del_clipboard);

		/* UPDATE USER SETTINGS */
		$sql_del_clipboard="UPDATE ".$GLOBALS['database_prefix']."document_user_settings
												SET total_cut_documents = 0
												WHERE user_id = '".$_SESSION['user_id']."'
												AND workspace_id = ".$GLOBALS['workspace_id']."
												AND teamspace_id ".$GLOBALS['teamspace_sql']."
												";
		//echo $sql."<br>";
		$result = $db->Query($sql_del_clipboard);

		/* RETURN TRUE HERE AS THIS IS A MULTIPLE UPDATE FUNCTION, ANY ERRORS GET A FALSE ABOVE */
		return True;
	}

	private function Errors($err) {
		$this->errors.=$err."<br>";
	}

	public function ShowErrors() {
		return $this->errors;
	}
}
?>