<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$username = $request->GetStr("username");
	$email = $request->GetStr("email");
	$displaySearch = false;
	if($username != '' or $email != ''){
		$displaySearch = true;
	}
?>
<div class="lock-to-top">
	<h4><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Reset_All_Passwords')); ?> </h4>
	<div class="btn-group">
		<a href="javascript:startPassword();" class="btn btn-success btn-sm m-b-20"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Start!')); ?> </a>
	</div>
</div>
	<div class="clear"></div>
	<div id="test"></div>
	<div id="waitSample" class="hidden"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Processing...')); ?> </div>
	<div class="table-responsive">
	<table class="MT5 table table-striped table-hover panel">
	<tr>
		<th width="16"></th>
		<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_username')); ?> </th>
		<th></th>
	</tr>
		<?php
			$sql = 'select * from ' . USER_MASTER . ' order by username';
			$query = $mysql->query($sql);
			$i = 1;
			if($mysql->rowCount($query) > 0){
				$ids = '';
				$rows = $mysql->fetchArray($query);
				foreach($rows as $row){
					echo '<tr id="tr' . $row['id'] . '">';
						echo '<td>' . $i++ . '</td>';
						echo '<td>' . $mysql->prints($row['username']) . '</td>';
						echo '<td id="td_pass_' . $row['id'] . '">...</td>';
					echo '</tr>';
					$ids .= $row['id'] . ', ';
				}
				$ids = trim($ids, ', ');
			} else {
				echo '<tr><td colspan="3" class="no_record">'. $admin->wordTrans($admin->getUserLang(),'No record found!').'</td></tr>';
			}
		?>
	</table>
	</div>

	<script>

		<?php echo 'var arr = [ ' . $ids . ' ];'; ?>

	</script>

