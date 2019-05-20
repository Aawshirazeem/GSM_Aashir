<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$tbls = array(
		'nxt_api_error_log' => 'API error log',
		'nxt_stats_admin_login_master' => 'Admin login logs',
		'nxt_stats_user_login_master' => 'User login logs',
		'nxt_user_register_master' => 'New registrations'
	);
?>
<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>utl_system_cleanup_process.do" method="post">
<div class="lock-to-top">
	<h4 class="m-b-20"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_system_clean')); ?> </h4>
<!--	<div class="btn-group">
		<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_clean')); ?>" class="btn btn-success"/>
	</div>-->
</div>
<div class="clear"></div>
	<div class="table-responsive table-bordered">
		<table class="MT5 table table-striped table-hover panel">
		<tr>
			<th width="16"></th>
			<th></th>
			<th width="80" class="text-right"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_rows')); ?></th>
			<th width="80" class="text-right"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_size')); ?></th>
		</tr>
		<?php
			$tempTables = '';
			foreach($tbls as $key=>$value){
				$tempTables .= '"' . $key . '",';
			}

			$tempTables = trim($tempTables , ',');
			
			$sql= 'SELECT table_name, SUM(TABLE_ROWS) as rows,
							round(((data_length + index_length) / 1024 / 1024), 3) as size
							FROM information_schema.TABLES 
							WHERE table_schema = "' . CONFIG_DB_NAME . '"
							AND table_name in (' . $tempTables . ')
						group by table_name';
			$query = $mysql->query($sql);
			if($mysql->rowCount($query) > 0){
				$rows = $mysql->fetchArray($query);
				foreach($rows as $row){
					echo '<tr>';
					echo '<td><label class="c-input c-checkbox"><input type="checkbox" name="tbls[]" value="' . $row['table_name'] . '" class="subSelectLock"><span class="c-indicator c-indicator-success"></span></label></td>';
					echo '<td><h5>' . $tbls[$row['table_name']] . '</h5></td>';
					echo '<td class="text-right">' . $row['rows'] . '</td>';
					echo '<td class="text-right">' . $row['size'] . '</td>';
					echo '</tr>';
				}
			} else {
				echo '<tr><td colspan="8" class="no_record">'. $admin->wordTrans($admin->getUserLang(),'No record found!').'</td></tr>';
			}
		?>
			<tr>
				<td colspan="4">
					<i class="fa fa-level-up"></i>
					<a href="#" value="Lock" class="selectAllBoxesLink"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Check_All')); ?></a> / 
					<a href="#" value="Lock" class="unselectAllBoxesLink"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Uncheck_All')); ?></a>
					<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_clean')); ?>" class="btn btn-success btn-sm"/>
				</td>
			</tr>
		</table>
	</div>	
	</form>