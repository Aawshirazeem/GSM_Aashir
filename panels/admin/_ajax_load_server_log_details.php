<?php
	// Set flag that this is a parent file
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$admin->checkLogin();
	$admin->reject();
	
	
	$server_log = $request->getInt('server_log');
	
	$sql = 'select slm.*, slsc.credits as splCr
				from ' . SERVER_LOG_MASTER . ' slm
				left join ' . SERVER_LOG_SPL_CREDITS . ' slsc on (slm.id = slsc.log_id and slsc.user_id = ' . $admin->getUserId() . ')
				where slm.id=' . $mysql->getInt($server_log);
	$query = $mysql->query($sql);
	if($mysql->rowCount($query) == 0)
	{
?>
	      <p class="form-group hidden">
	        <label>Custom form-group</label>
	        <input type="text" name="custom" id="custom" class="form-control" value="">
	      </p>
<?php
	}
	else
	{
		$rows = $mysql->fetchArray($query);
		$row = $rows[0];
		$cr = $row['credits'];
		if($row['splCr'] != "")
		{
			$cr = $row['splCr'];
		}

?>
		<div class="clear"></div>
			<div  style="width:80%;margin:0px auto;">
				<div class="ui-widget">
					<br />
					<div class="ui-state-default ui-corner-all text_center" style="padding:5px 5px 5px 5px;"> 
						<font style="font-weight:normal">Credits:</font> <?php echo $cr;?> &nbsp;|&nbsp; <font style="font-weight:normal">Time:</font> <?php echo $row['delivery_time'];?>
					</div>
				</div>
			</div>
			<div  style="width:80%;margin:0px auto;" class="<?php echo (($row['info'] == '') ? ' hidden' : ''); ?>">
				<div class="ui-widget">
					<br />
					<div class="ui-state-highlight ui-corner-all" style="padding:5px 5px 5px 5px;"> 
						<?php echo nl2br($row['info']);?>
					</div>
				</div>
			</div>
          <div class="form-group <?php echo (($row['custom_field_name'] == '') ? ' hidden' : ''); ?>">
            <label><?php echo $row['custom_field_name']?></label>
            <input type="text" name="custom" id="custom" class="form-control" value="<?php echo $row['custom_field_value']?>">
            <p class="help-block"><?php echo $row['custom_field_message']?></p>
          </div> 
<?php
	}
?>
