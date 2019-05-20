<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	
	if(!isset($_POST['id']))
	{
		header("location:" .CONFIG_PATH_SITE_SUPPLIER ."order_imei.html?type=pending&reply=" .urlencode('reply_no_imei_seleted'));
		exit();
	}
	$ids = $_POST['id'];
	print_r($ids);
	exit();
	$imeis = $newIds = '';
	foreach($ids as $id)	
	{
		$newIds .= ($id) . ',';
	}
	$newIds = trim($newIds, ',');
	$sql = 'select
						oim.id, oim.imei, itm.tool_name
					from ' . ORDER_IMEI_MASTER . ' oim
					left join ' . IMEI_TOOL_MASTER . ' itm on(oim.tool_id = itm.id)
				where oim.id in (' . $newIds . ')
				order by itm.tool_name';
	$query = $mysql->query($sql);
	$rows = $mysql->fetchArray($query);
	$toolName = '';
	foreach($rows as $row)
	{
		if($toolName != $row['tool_name'])
		{
			$imeis .= '<span class="tplDetails">';
			$imeis .= ($toolName != '') ? "\n\n\n" : '';
			$imeis .= '=======================================================================' . "\n";
			$imeis .= '<span class="tplDate">Date: ' . date("M j, Y, g:i A") . ' </span><span class="tplServices">Service: ' . $row['tool_name'] . '</span>'. "\n";
			$imeis .= "-----------------------------------------------------------------------" . "\n";
			$imeis .= '</span>';
			$toolName = $row['tool_name'];
		}
		$imeis .= '<span class="tplId">' . $row['id'] . ': </span>' . $row['imei'] . "\n";
	}
?>



	<div class="textbox_fix WD90 hidden" id="imeiPlain" rows="15" style="font-family:monospace;"><?php echo nl2br($imeis);?></div>
	<div class="textbox_fix WD90 hidden" id="imeiPlain2" rows="15" style="font-family:monospace;"><?php echo nl2br($imeis);?></div>

	

<form action="<?php echo CONFIG_PATH_SITE_SUPPLIER; ?>order_imei_pending_final_download.do" method="post" name="frm_customer_edit_login" id="frm_customer_edit_login">
<div class="lock-to-top">
	<h1><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei_jobs')); ?> </h1>
	<div class="btn-group">
		<button type="submit" class="btn btn-success"><i class="icon-download-alt"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_download')); ?></button>
	</div>
</div>
			
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel">
				<div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei_jobs')); ?></div>
				<div class="panel-body">
					
					<div class="form-group">
						<textarea name="imei" class="form-control" id="imei" rows="15" style="font-family:monospace;"></textarea>
					</div>
					<div class="form-group">
						<label class="checkbox-inline"><input type="checkbox" value="1" name="toggleID" id="toggleID" class="loadIMEIs" /> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_order_id')); ?></label>
						<label class="checkbox-inline"><input type="checkbox" value="1" name="toggleDate" id="toggleDate" class="loadIMEIs" checked="checked" /> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_date_time')); ?></label>
						<label class="checkbox-inline"><input type="checkbox" value="1" name="toggleServices" id="toggleServices" class="loadIMEIs" checked="checked" /> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_service')); ?></label>
					</div>
					
				</div> <!-- / panel-body -->
				<div class="panel-footer">
					<button type="submit" value="" class="btn btn-success"><i class="icon-download-alt"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_download')); ?></button>
				</div> <!-- / panel-footer -->
			</div> <!-- / panel -->
		</div> <!-- / col-lg-6 -->
	</div> <!-- / row -->
</form>
		  
		  
		  
		  
		  
<form action="<?php echo CONFIG_PATH_SITE_SUPPLIER; ?>order_imei_pending_final_email.do" target="_blank" class="form-inline" method="post">
	<textarea name="imei2" class="hidden" id="imei2" rows="15" style="font-family:monospace;"></textarea>
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-danger">
				<div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_mail_imeis_to')); ?></div>
				<div class="panel-body">
					
					<div class="form-group">
						<select name="supplier_id" class="form-control">
							<option value="0"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_select_supplier')); ?></option>
							<?php
								$sql_usr = 'select id, username from ' . SUPPLIER_MASTER . ' order by username';
								$query_usr = $mysql->query($sql_usr);
								$rows_usr = $mysql->fetchArray($query_usr);
								foreach($rows_usr as $row_usr)
								{
									echo '<option value="' . $row_usr['id'] . '">' . $mysql->prints($row_usr['username']) . '</option>';
								}
							?>
						</select>
					</div>
					or
					<div class="form-group">
						<input type="text" name="email" id="email" class="form-control" placeholder="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_mail_imeis_to')); ?>" value="" />
					</div>
					
				</div> <!-- / panel-body -->
				<div class="panel-footer">
					<button type="submit" value="" class="btn btn-danger"><i class="icon-mail-forward"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_send_email')); ?></button>
				</div> <!-- / panel-footer -->
			</div> <!-- / panel -->
		</div> <!-- / col-lg-6 -->
	</div> <!-- / row -->
</form>
		  
<script>
$(document).ready(function () 
{
	imeiDownload();
});
</script>