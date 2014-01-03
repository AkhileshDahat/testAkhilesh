<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."classes/form/create_form.php";
require_once $GLOBALS['dr']."include/functions/upload/upload_file.php";

require_once $GLOBALS['dr']."modules/hrms/classes/new_user.php";
require_once $GLOBALS['dr']."modules/hrms/functions/defaults/role_id_default.php";
require_once $GLOBALS['dr']."modules/workspace/functions/defaults/default_workspace_role_id.php";

function LoadTask() {

	$c="";
	$db=$GLOBALS['db'];

	/* CREATION OF THE IMPORT FORM FOR CSV */
	$form=new CreateForm;
	$form->SetCredentials("index.php?module=hrms&task=import_users","post","import_users","enctype='multipart/form-data'","","plain_border");
	$form->BreakCell("Importing CSV file");
	$form->File("userfile");
	$form->Submit("Import Now","CSV");
	$form->DescriptionCell("","Format of .csv file: username;password;firstname;lastname;timezone");
	$c.=$form->DrawForm();

	/* CREATION OF THE IMPORT FORM FOR EXCEL XML */
	$form=new CreateForm;
	$form->SetCredentials("index.php?module=hrms&task=import_users","post","import_users","enctype='multipart/form-data'","","plain_border");
	$form->BreakCell("Importing XML file");
	$form->File("userfile");
	$form->Submit("Import Now","XML");
	//$form->DescriptionCell("","Format of .csv file: username;password;firstname;lastname;timezone");
	$c.=$form->DrawForm();

	/* FORM PROCESSING */
	if (ISSET($_POST['CSV'])) {

		/* FIXED PARAMETERS */
		$import_dir=$GLOBALS['dr']."modules/hrms/upload/import_users/";
		$import_file=$GLOBALS['ui']->WorkspaceID().".csv";
		$import_dir_file=$import_dir.$import_file;
		$role_id_default=RoleIDDefault();
		$default_workspace_role_id=DefaultWorkspaceRoleID($GLOBALS['ui']->WorkspaceID());

		$c.="Processing Now...<br>\n";
		if (UploadFile("userfile",$import_dir_file,$import_dir_file)) { /* UPLOAD THE FILE TO THE SERVER */
			$c.="Processing file...<br>\n";
			$row = 0;
			$handle = fopen($import_dir_file, "r");
			while (($data = fgetcsv($handle, 1000, ";",Chr(34))) !== FALSE) {
				$num = count($data);
				$row++;
				if ($num==5) {
					//$c.="<p> $num fields in line $row: <br /></p>\n";
				  for ($c=0; $c < $num; $c++) {
				  	//$c.=$data[$c] . "<br />\n";
				  }
				  $nu=new NewUser;
				  $db->Begin();
				  $result_add_user=$nu->AddUser($role_id_default,$data[0],$data[1],$data[2],$data[3],$data[4]);

				  if ($result_add_user) {
				  	$result_add_workspace=$nu->AddWorkspaceID($nu->GetUserID($data[0]),$GLOBALS['ui']->WorkspaceID(),$default_workspace_role_id);
						if ($result_add_workspace) {
				  		$c.=$data[0]." added successfully<br>";
				  		$db->Commit();
				  	}
				  	else {
				  		$c.=$data[0]." failed<br>";
				  		$c.=$nu->ShowErrors();
				  		$db->Rollback();
				  	}
				  }
				  else {
				  	$c.=$data[0]." failed<br>";
				  	$c.=$nu->ShowErrors();
				  	$db->Rollback();
				  }
				}
				else {
					$c.="Skipping Row $row because of invalid number of parameters<br>\n";
				}
			}
			fclose($handle);

		}
		else {
			$c.="Upload Failed<br>";
		}
	}

	return $c;
}
?>