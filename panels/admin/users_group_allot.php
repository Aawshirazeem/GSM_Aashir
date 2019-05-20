<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$validator->formSetAdmin('user_group_edit_54964566h34');
	$paging = new paging();
    
	$group_id = $request->GetInt('group_id');
	$limit= $request->GetInt('limit');
	$offset = $request->GetInt('offset');
	if($limit==0)
	{
		$limit = 20;
	}
	$qLimit = ' limit ' . $offset . ',' . $limit;
	$extraURL = '&limit=' . $limit . '&group_id=' . $group_id ;
	
?>
<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_group_allot_process.do" method="post">
	<div class="lock-to-top">
		<h1><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_user_group_manager')); ?> </h1>
		<div class="btn-group">
			<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>users_group.html" class="btn btn-default"><?php echo '<i class="icon-arrow-left"></i>'; ?> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_back')); ?></a>
			<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_allot')); ?>" class="btn btn-success">
		</div>
	</div>
	 
	  
	 <div class="btn-group">
	  </div>
	  <div class="clear"></div>

			<input name="group_id" type="hidden" class="textbox_fix" id="id" value="<?php echo $group_id?>" />
			<table class="MT5 table table-striped table-hover panel">
				<tr>
					<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_serial')); ?></th>
					<th width="60"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_users')); ?></th>
				</tr>
				<?php
					$sql = 'select um.* , ugd.* 
								from ' . USER_MASTER . ' um
								left join ' . USER_GROUP_DETAIL . ' ugd on(um.id=ugd.user_id and ugd.group_id=' . $group_id . ')
								order by um.username ';
					$query = $mysql->query($sql . $qLimit);
					$pCode = $paging->recordsetNav($sql,CONFIG_PATH_SITE_ADMIN . 'users_group_allot.html',$offset,$limit,$extraURL);
					$i = $offset + 1;
					if($mysql->rowCount($query)>0)
					{
						$rows = $mysql->fetchArray($query);
						foreach($rows as $row)
						{	
							echo '';
							echo '<tr>';
								echo '<td>
											' . (($row['group_id'] == $group_id) ? '<h2 class="TC_R">' . $row['username'] . '</h2>' : $row['username']) . '
											<input type="hidden" name="user_ids[]" value="' . $row['id'] . '" />
									</td>';
								echo '<td><input class="checkbox-inline" type="checkbox" ' . (($row['group_id'] == $group_id) ? 'checked="checked"' : '') . ' name="checkids[]"  value="' . $row['id'] . '"/></td>';
							echo '</tr>';
						}
					}
					else
					{
						echo '<tr><td colspan="6" class="no_record">'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')).'</td></tr>';
					}
				?>
	</table>
<div class="clearfix"></div>	
	<div class="btn-group">
		<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_allot')); ?>" class="btn btn-success">
	</div>
</form>
	<div class="clearfix"></div>
	<div class="float_left">
		<?php echo $pCode;?>
	</div>
	
