<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$template = new template();
	$id = $request->GetInt('id');
	echo $template->getInvoice($id);
?>