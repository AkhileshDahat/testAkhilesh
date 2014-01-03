<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MVH' ) or die( 'Direct Access to this location is not allowed.' );

require_once $dr."classes/form/create_form.php"; /* FORM CREATION CLASS */

/* CREATION OF THE IMPORT FORM */
$form=new CreateForm;
$form->SetCredentials("index.php?module=helpdesk&task=submit_ticket","post","submit_ticket");
$form->Header("crystalclear/48x48/apps/web.png","Submit a new helpdesk case");
$form->Input("Title","title","","","","","30");
$form->TextArea("Description","description",10,50,"");

$form->Submit("Add Now","FormSubmit");
echo $form->DrawForm();
?>