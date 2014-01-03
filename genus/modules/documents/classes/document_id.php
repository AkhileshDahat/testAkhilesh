<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/documents/classes/category_role_security.php";
require_once $GLOBALS['dr']."modules/documents/functions/misc/log_history.php";

class DocumentID {
	/* CONSTRUCTOR FUNCTION */
	function __construct() {
	/* SETTING PARAMETERS */
	}
	/* SET PARAMETERS */
	public function SetParameters($document_id) {

		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;

		/* CHECKS */
		if (!IS_NUMERIC($document_id)) { $this->Errors("Invalid Document"); return False; }

		/* SET SOME COMMON VARIABLES */
		$this->document_id=$document_id;

		/* CALL THE DOCUMENTID INFORMATION - WE NEED THIS BEFORE RUNNING THE BELOW CHECK ON SECURITY*/
		$this->Info();

		/* PRIVILEGE - NOT IMPLEMENTED */
		//if (!$this->DocumentRoleSecurity()) { $this->Errors("Access to document denied."); return False; }

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		return True;
	}
	/* GET INFORMATION ABOUT THE DOCUMENT ID */
	private function Info() {

		$db=$GLOBALS['db'];

		$sql="SELECT *
					FROM ".$GLOBALS['database_prefix']."v_document_files
					WHERE document_id = '".$this->document_id."'
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);

		if ($db->NumRows($result) > 0) {
			while($row=$db->FetchArray($result)) {
				/* HERE WE CALL THE FIELDS AND SET THEM INTO DYNAMIC VARIABLES */
				$arr_cols=$db->GetColumns($result);
				for ($i=1;$i<count($arr_cols);$i++) {
					$col_name=$arr_cols[$i];
					$this->$col_name=$row[$col_name];
				}
			}
		}
	}
	/* THIS IS USED IN DOWNLOAD PAGES - BECAUSE WE RETURN THE DOCUMENT WHICH CAN BE HUGE */
	public function DocumentAttachment() {

		if ($this->parameter_check) {

			$db=$GLOBALS['db'];

			$sql="SELECT attachment
						FROM ".$GLOBALS['database_prefix']."document_files
						WHERE document_id = '".$this->document_id."'
						";
			//echo $sql."<br>";
			$result = $db->Query($sql);
			if ($db->NumRows($result) > 0) {
				while($row = $db->FetchArray($result)) {
					if ($GLOBALS['database_type'] == "postgres") {
						return pg_unescape_bytea($row["attachment"]);
					}
					else {
						return $row["attachment"];
					}
				}
			}
		}
	}
	/* GET A COLUMN NAME FROM THE ARRAY */
	function GetColVal($col_name) {
		return $this->$col_name;
	}
	/* INTERNAL SECURITY CHECK - NEED TO CHECK */
	private function DocumentSecurity() {
		/* CALL THE OBJECT IN THE CATEGORY SECURITY CLASS */
		$cs=new CategoryRoleSecurity;
		//echo $this->GetColVal("category_id");
		$cs->SetParameters($this->GetColVal("category_id"),$GLOBALS['wui']->RoleID());
		$result=$cs->CategoryRoleExists();
		if (!$result) {
			//echo "error on category security parameter check";
			$this->Errors($cs->ShowErrors());
			return False;
		}
		else {
			return True;
		}
	}
	/* CHECK OUT DOCUMENT */
	public function CheckOut() {

		if ($this->parameter_check) {

			/* DATABASE CONNECTION */
			$db=$GLOBALS['db'];

			/* DESCRIPTION MUST ALWAYS COME FIRST BEFORE WE DELETE THE RECORDS */
			$description=GetColumnValue("filename","document_files","document_id",$this->document_id,""). " - checked out";

			$sql="UPDATE ".$GLOBALS['database_prefix']."document_files
						SET checked_out = 'y',
						user_id_checked_out = ".$_SESSION['user_id'].",
						date_checked_out = now()
						WHERE document_id = ".$this->document_id."
						";
			//echo $sql;
			$result=$db->Query($sql);
			if ($db->AffectedRows($result) > 0) {
				/* CALL THE UNLOCK FEATURE */
				$this->UnLockDocument();
				/* CALL THE UNDO CUT FEATURE */
				$this->DocumentUndoCut();
				/* LOG THE HISTORY */
				LogDocumentFileHistory($this->GetColVal("filename"),$this->GetColVal("category_id"),$this->GetColVal("version_number"),$description);
				return True;
			}
			else {
				return False;
			}
		}
		else {
			$this->Errors("Parameter check failed");
			return False;
		}
	}
	/* HERE WE LOCK A DOCUMENT */
	public function LockDocument() {

		if ($this->parameter_check) {

			/* DATABASE CONNECTION */
			$db=$GLOBALS['db'];

			$sql="UPDATE ".$GLOBALS['database_prefix']."document_files
						SET locked = 'y',
						user_id_locked = ".$_SESSION['user_id'].",
						date_locked = now()
						WHERE document_id = ".$this->document_id."
						";
			$result=$db->Query($sql);
			if ($db->AffectedRows($result) > 0) {
				LogDocumentFileHistory($this->GetColVal("filename"),$this->GetColVal("category_id"),$this->GetColVal("version_number"),"Locked");
				return True;
			}
			else {
				return False;
			}
		}
		else {
			$this->Errors("Parameter check failed");
			return False;
		}
	}
	/* HERE WE UNLOCK A DOCUMENT */
	public function UnLockDocument() {

		if ($this->parameter_check) {

			/* DATABASE CONNECTION */
			$db=$GLOBALS['db'];

			$sql="UPDATE ".$GLOBALS['database_prefix']."document_files
						SET locked = 'n',
						user_id_locked = null,
						date_locked = null
						WHERE document_id = ".$this->document_id."
						";
			//echo $sql;
			$result=$db->Query($sql);
			if ($db->AffectedRows($result) > 0) {
				$this->DocumentUndoCut();
				LogDocumentFileHistory($this->GetColVal("filename"),$this->GetColVal("category_id"),$this->GetColVal("version_number"),"Unlocked");
				return True;
			}
			else {
				return False;
			}
		}
		else {
			$this->Errors("Parameter check failed");
			return False;
		}
	}
	/* HERE WE CUT A DOCUMENT */
	public function CutDocument() {

		if ($this->parameter_check) {

			/* DATABASE CONNECTION */
			$db=$GLOBALS['db'];
			if (RowExists("document_files_cut","document_id",$this->document_id,"AND user_id=".$_SESSION['user_id'])) {
				$this->Errors("Document already cut");
				return False;
			}

			/* PROCEED TO INSERT */
			$sql="INSERT INTO ".$GLOBALS['database_prefix']."document_files_cut
						(user_id,document_id,workspace_id,teamspace_id)
						VALUES (
						".$_SESSION['user_id'].",
						".$this->document_id.",
						".$GLOBALS['workspace_id'].",
						".$GLOBALS['teamspace_id']."
						)
						";
			$result=$db->Query($sql);
			if ($result) {
				$this->LockDocument();
				LogDocumentFileHistory($this->GetColVal("filename"),$this->GetColVal("category_id"),$this->GetColVal("version_number"),"Cut in preparation for move");

				/* UPDATE THE USER'S TOTAL DOCUMENTS CUT IN THE SETTINGS TABLE */
				$sql_update_cut="UPDATE ".$GLOBALS['database_prefix']."document_user_settings
												SET total_cut_documents = total_cut_documents +1
												WHERE user_id = ".$_SESSION['user_id']."
												AND workspace_id = ".$GLOBALS['workspace_id']."
												AND teamspace_id ".$GLOBALS['teamspace_sql']."
												";
				//echo $sql_update_cut."<br>";
				$db->Query($sql_update_cut);

				return True;
			}
			else {
				return False;
			}
		}
		else {
			$this->Errors("Parameter check failed");
			return False;
		}
	}
	/* THIS FUNCTION DETERMINES IF THE DOCUMENT ID HAS BEEN CUT BY THE LOGGED ON USER */
	public function DocumentUndoCut() {

		if ($this->parameter_check) {
			$db=$GLOBALS['db'];

			$sql="DELETE FROM ".$GLOBALS['database_prefix']."document_files_cut
						WHERE document_id = ".$this->document_id."
						AND user_id = ".$_SESSION['user_id']."
						";
			//echo $sql."<br>";
			$result = $db->Query($sql);
			if ($db->AffectedRows($result) > 0) {
				return True;
			}
			else {
				return False;
			}
		}
		else {
			$this->Errors("Parameter check failed");
			return False;
		}
	}
	/* DELETE THE LATEST VERSION OF A DOCUMENT */
	public function DeleteVersion() {

		if ($this->parameter_check) {

			/* DATABASE CONNECTION */
			$db=$GLOBALS['db'];

			/* DESCRIPTION MUST ALWAYS COME FIRST BEFORE WE DELETE THE RECORDS er */
			$description=GetColumnValue("filename","document_files","document_id",$this->document_id,""). " - version deleted";

			/* PROCESS LOGGING BEFORE DELETION TO PROTECT FOREIGN KEY CONSTRAINT */
			LogDocumentFileHistory($this->GetColVal("filename"),$this->GetColVal("category_id"),$this->GetColVal("version_number"),$description);

			/* AVOID SUB QUERY HERE FOR MYSQL */
			$sql="SELECT document_id, version_number
						FROM ".$GLOBALS['database_prefix']."document_files
						WHERE filename = '".$this->GetColVal("filename")."'
						AND category_id = ".$this->GetColVal("category_id")."
						ORDER BY version_number DESC
						LIMIT 1
						";
			//echo $sql."<br>";
			$result = $db->Query($sql);
			if ($db->NumRows($result) > 0) {
				while($row = $db->FetchArray($result)) {
				 	$document_id_delete = $row['document_id'];
				 	/* THE VERSION TO UPDATE TO THE LATEST */
				 	$document_id_latest_version=($row['version_number']-1);
			  }
			}

			/* DELETE DOCUMENT */
			$sql="DELETE FROM ".$GLOBALS['database_prefix']."document_files
						WHERE document_id IN (
							".$document_id_delete."
						)
						";
			//echo $sql."<br>";
			$result=$db->Query($sql);
			if ($db->AffectedRows($result) > 0) {
				/* UPDATE THE PREVIOUS VERSION TO THE LATEST VERSION */
				$sql_upd="UPDATE ".$GLOBALS['database_prefix']."document_files
									SET latest_version = 'y',
									checked_out = 'n'
									WHERE document_id IN (
										".$document_id_latest_version."
									)
									";
				//echo $sql_upd."<br>";
				$result_upd=$db->Query($sql_upd);
				if ($db->AffectedRows($result_upd) > 0) {
					return True;
				}
				else {
					$this->Errors("Delete version failed");
					return False;
				}
			}
			else {
				return False;
			}
		}
		else {
			$this->Errors("Parameter check failed");
			return False;
		}
	}
	/* DELETE THE ENTIRE FILE AND ALL OTHER VERSIONS */
	public function DeleteFile() {

		if ($this->parameter_check) {

			/* DATABASE CONNECTION */
			$db=$GLOBALS['db'];

			/* DESCRIPTION MUST ALWAYS COME FIRST BEFORE WE DELETE THE RECORDS */
			$description=GetColumnValue("filename","document_files","document_id",$this->document_id,""). " - deleted";

			/* LOG FIRST */
			LogDocumentFileHistory($this->GetColVal("filename"),$this->GetColVal("category_id"),$this->GetColVal("version_number"),$description);

			$sql="DELETE FROM ".$GLOBALS['database_prefix']."document_files
						WHERE filename = '".$this->GetColVal("filename")."'
						";
			$result=$db->Query($sql);
			if ($db->AffectedRows($result) > 0) {
				return True;
			}
			else {
				return False;
			}
		}
		else {
			$this->Errors("Parameter check failed");
			return False;
		}
	}
	/* APPROVE OR REJECT A DOCUMENT */
	public function ApproveRejectDocument($approve) {

		/* PARAMETER CHECK */
		if (!$this->parameter_check) {
			$this->Errors("Parameter check failed");
			return false;
		}

		/* GET THE APPROVAL VALUES */
		if ($approve=="y") {
			$description="Document Approved";
			$sql_up="y";
			$sql_status="is_current";
		}
		else {
			$description="Document Rejected";
			$sql_up="n";
			$sql_status="is_rejected";
		}

		/* GET THE STATUS ID */
		$status_id=GetColumnValue("status_id","document_status_master",$sql_status,"y","");

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		/* UPDATE THE INDIVIDUAL APPROVAL FIST */
		$sql="UPDATE ".$GLOBALS['database_prefix']."document_file_approval
					SET approved = 'y'
					WHERE document_id = ".$this->document_id."
					AND user_id = ".$_SESSION['user_id']."
					";
		//echo $sql."<br>";
		$result=$db->Query($sql);
		if ($db->AffectedRows($result) == 0) {
			$this->Errors("Unable to update user approval");
			return false;
		}

		/* CHECK HOW MANY APPROVERS TO GO */
		if ($this->RemainingApprovals() == 0) {

			/* UPDATE THE MASTER DOCUMENT DOCUMENT */
			$sql="UPDATE ".$GLOBALS['database_prefix']."document_files
						SET status_id = ".$status_id."
						WHERE document_id = ".$this->document_id."
						";
			//echo $sql."<br>";
			$result=$db->Query($sql);
			if ($db->AffectedRows($result) == 0) {
				$this->Errors("Unable to update file approval");
				return False;
			}

			/* LOG FILE HISTORY */
			LogDocumentFileHistory($this->GetColVal("filename"),$this->GetColVal("category_id"),$this->GetColVal("version_number"),$description);
			//echo "Approved";
		}
		else {
			//echo "Still nmore approvals to go";
		}

		/* SUCCESS */
		return True;
	}
	/* COUNT HOW MANY USERS STILL NEED TO APPROVE THE DOCUMENT */
	public function RemainingApprovals() {

		/* PARAMETER CHECK */
		if (!$this->parameter_check) {
			$this->Errors("Parameter check failed");
			return false;
		}

		$db=$GLOBALS['db'];

		$sql="SELECT count(*) as total
					FROM ".$GLOBALS['database_prefix']."document_file_approval
					WHERE document_id = ".$this->document_id."
					AND approved = 'n'
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				return $row['total'];
			}
		}
		else {
			return 0;
		}
	}
	/* THIS FUNCTION DETERMINES IF THE DOCUMENT ID HAS BEEN CUT BY THE LOGGED ON USER NEEDS WORK */
	public function DocumentIsCut($document_id) {

		$db=$GLOBALS['db'];

		$sql="SELECT 'x'
					FROM ".$GLOBALS['database_prefix']."document_files_cut
					WHERE document_id = ".$document_id."
					AND user_id = ".$_SESSION['user_id']."
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			return True;
		}
		else {
			return False;
		}
	}
	/* DISPLAY THE DOCUMENT RATING AS A STRING FOR HTML */
	function DisplayDocumentRating() {

	$wb=$GLOBALS['wb'];

	$c="";

	if (RowExists("document_user_rating","document_id",$this->document_id,"AND user_id=".$_SESSION['user_id'])) {
		$rating=GetColumnValue("rating","document_user_rating","document_id",$this->document_id,"AND user_id=".$_SESSION['user_id']);

		for ($i=1;$i<=5;$i++) {
			if ($i<=$rating) { $rating_img="full.gif"; } else { $rating_img="empty.gif"; }
			$c.="<img id='".$this->document_id."_".$i."' src='".$wb."images/stars/".$rating_img."' onClick=\"document.getElementById('".$this->document_id."_".$i."').src='modules/documents/bin/rating/full.php?document_id=".$this->document_id."&rating=".$i."'\" width='16' height='16' border='0' alt='Rate ".$i."' title='Rate ".$i."'>";
		}
		echo "<br>";
	}
	else {
		for ($i=1;$i<=5;$i++) {
			$c.="<a onMouseOver=\"document.getElementById('".$this->document_id."_".$i."').src='".$wb."images/stars/full.gif'\" onMouseOut=\"document.getElementById('".$this->document_id."_".$i."').src='".$wb."images/stars/empty.gif'\"><img id='".$this->document_id."_".$i."' src='".$wb."images/stars/empty.gif' onClick=\"document.getElementById('".$this->document_id."_".$i."').src='modules/documents/bin/rating/full.php?document_id=".$this->document_id."&rating=".$i."'\" width='16' height='16' border='0' alt='Rate ".$i."' title='Rate ".$i."'></a>";
		}
	}
	return $c;
}
	/* ERRORS FUNCTION */
	private function Errors($err) {
		$this->errors.=$err."<br>";
	}
	/* PUBLIC FUNCTION TO SHOW ERRORS IN THIS DOCUMENT */
	public function ShowErrors() {
		return $this->errors;
	}
}
?>