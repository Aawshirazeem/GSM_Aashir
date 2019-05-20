<?php
	$template = new template();
	
	$member->checkLogin();
	$member->reject();
	
	$id = $request->GetInt('id');
	
	$sql = 'select id from ' . INVOICE_MASTER . ' where id=' . $id . ' and paid_status=1 and user_id=' . $member->getUserId();
	$query = $mysql->query($sql);
	if($mysql->rowCount($query) > 0)
	{
		echo $template->getInvoice($id);
	}
	else
	{
		echo '<h1 class="text-danger text-center">You are not authorized to view this Invoice!</h1>';
	}
?>