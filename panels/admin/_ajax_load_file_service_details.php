<?php
	// Set flag that this is a parent file
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$admin->checkLogin();
	$admin->reject();
	

	if($admin->isLogedin())
	{
		echo 'You are not authorized to view this page!';
		exit();
	}
	
	$file_service = $request->getInt('file_service');
	
	$sql = 'select fsm.*, fsc.credits as splCr
				from ' . FILE_SERVICE_MASTER . ' fsm
				left join ' . FILE_SPL_CREDITS . ' fsc on (fsm.id = fsc.service_id and fsc.user_id = ' . $mysql->getInt($admin->getUserId()) . ')
				where id=' . $mysql->getInt($file_service);
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
          <div class="forn-group <?php echo (($row['custom_field_name'] == '') ? ' hidden' : ''); ?>">
            <label><?php echo $row['custom_field_name']?></label>
            <input type="text" name="custom" id="custom" class="form-control" value="<?php echo $row['custom_field_value']?>">
            <p class="help-block"><?php echo $row['custom_field_message']?></p>
          </div> 
<?php
	}
?>
