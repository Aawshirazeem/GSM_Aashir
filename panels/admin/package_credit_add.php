<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$validator->formSetAdmin('user_edit_789971255d2');
	
	$package_id=$request->getInt('package_id');
	
	$sql_spl_imei = 'select tm.*, pd.credits as splCr, igm.group_name, igm.status as groupStatus
						from ' . IMEI_TOOL_MASTER . ' tm
						left join ' . IMEI_PACKAGE_DETAIL . ' pd on (tm.id = pd.tool_id and package_id=' . $package_id . ')
						left join ' . IMEI_GROUP_MASTER . ' igm on(tm.group_id = igm.id)
						order by igm.group_name, tm.tool_name';
	$query_spl_imei = $mysql->query($sql_spl_imei);
	$strReturn = "";
	$i = 1;
	$groupName = "";
?>
<h1><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_unlocking_tool_list')); ?></h1>
	<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>imei_package_special_credit_add_process.do" enctype="multipart/form-data" method="post" name="frm_inquiry" id="frm_inquiry" class="formSkin">
		<input type="hidden" name="package_id" value="<?php echo $package_id ;?>"/>
		<table class="MT5 details text_12 ui-corner-all ui-widget-content WD60 FC">
			<tr>
			  <th width="16"></th>
			  <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_tool')); ?></th>
			  <th style="text-align:right"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_org._cr.')); ?></th>
			  <th style="text-align:right" width="120"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_spl._cr.')); ?></th>
			</tr>
			<?php
				if($mysql->rowCount($query_spl_imei) > 0)
				{
					$rows_spl_imei = $mysql->fetchArray($query_spl_imei);
					foreach($rows_spl_imei as $row_spl_imei)
					{
						if($groupName != $row_spl_imei['group_name'])
						{
							$groupName = $row_spl_imei['group_name'];
							echo '<tr><td colspan="5"><h2>' . (($row_spl_imei['groupStatus'] == '1') ? $mysql->prints($groupName) : '<del>' . $mysql->prints($groupName) . '</del>') . '</h2></td></tr>';
						}
						echo '<tr>';
						echo '<td>' . $i++ . '</td>';
						echo '<td>' . $mysql->prints($row_spl_imei['tool_name']) . '</td>';
						echo '<td class="text_right"><h2>' . $row_spl_imei['credits'] . '</h2></td>';
						echo '<td class="text_right">
								<input type="text" class="form-control text_right ' . (($row_spl_imei['splCr'] != '') ? 'textbox_highlight' : '') . '" style="width:80px" name="spl_' . $row_spl_imei['id'] . '" value="' . $row_spl_imei['splCr'] . '" />
								<input type="hidden" name="org_' . $row_spl_imei['id'] . '" value="' . $row_spl_imei['credits'] . '" />
								<input type="hidden" name="ids[]" value="' . $row_spl_imei['id'] . '" />
							  </td>';
						echo '</tr>';
					}
				}
				else
				{
					echo '<tr><td colspan="6" class="no_record">'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')).'</td></tr>';
				}
			?>
	</table>
	<p class="butSkin">
		<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>imei_package_list.html" style="float:left"><?php echo '<i class="icon-arrow-left"></i>'; ?> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_back')); ?></a>
		<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_credits')); ?>" />
	</p>
</form>
