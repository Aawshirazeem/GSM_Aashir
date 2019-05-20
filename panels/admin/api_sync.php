<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$validator->formSetAdmin('service_imei_file_edit_14832342');
	
	$id = $request->GetInt('id');
        $chk_services = $request->GetInt('chk_services');
	
	$sql ='select * from ' . API_MASTER . ' where id=' . $mysql->getInt($id) . ' and is_visible=1';
	$query = $mysql->query($sql);
	$rowCount = $mysql->rowCount($query);
	if($rowCount == 0)
	{
		header("location:" . CONFIG_PATH_SITE_ADMIN . "api_list.html?reply=" . urlencode('reply_invalid_id'));
		exit();
	}
	$rows = $mysql->fetchArray($query);
	$row = $rows[0];
	
	$args['id'] = $id;
	$server_id = $row['server_id'];
	$args['key'] = $row['key'];
	$args['username'] = $row['username'];
	$args['password'] = $row['password'];
	$args['url'] = $row['url'];
	
?>
<div class="lock-to-top">
	<h1><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_sync_process')); ?> </h1>
</div>

<div class="panel">
	<div class="panel-body">
		<h4 id="load_head_main"><i class="fa fa-refresh fa-spin"></i> <span id="load_head">--</span></h4>
		<div class="progress progress-striped active progress-xs">
			<div class="progress-bar progress-bar-sync progress-bar-success p"  role="progressbar" aria-valuenow="5" aria-valuemin="0" aria-valuemax="100" style="width:5%"></div>
		</div>
	</div>
</div>

<div id="load_details" class="text_12 TC_W WD60 FC P10" style="background-color:#000000; height:525px; overflow:auto; color:#ffffff; margin-bottom:10px">
<?php ob_flush();?>
<?php
	
	
	/*$response = $api->credits($server_id, $args);
	if($response['credits'] == -1 and $response['msg'] != '')
	{
		echo '<p class="TC_R">Connection Error! ' . $response['msg'] .  '</p>';
	}
	else
	{
		echo '<p class="TC_GR">Connected...</p>';
		$response = $api->credits($server_id, $args);
		echo '<p>Synchronizing unlocking tools...</p>';
		$response = $api->sync_tools($server_id, $args);
		ob_flush();
	}*/
	
	
?>
<?php ob_flush();?>
</div>
<script>
	$(document).ready(function () 
	{
             setPathsAdmin('<?php echo CONFIG_PATH_SITE_ADMIN ?>');
		apiSync(1, <?php echo $id;?>,<?php echo $chk_services;?>);
	});
</script>
<p class="form-group MT10">
	<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>api_list.html" class="btn btn-danger btn-sm" id="btnCancel"> <i class="fa fa-times"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_cancel')); ?></a>
	<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>api_list.html" class="btn btn-primary btn-sm" id="btnBack" style="display:none"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_back')); ?></a>
</p>