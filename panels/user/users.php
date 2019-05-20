<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$type = $request->getStr('type');
?>
<div class="row">
		<div class="col-lg-12">
			<ul class="breadcrumb">
				<li><a href="<?php echo CONFIG_PATH_SITE_USER; ?>dashboard.html"><i class="fa fa-home"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Dashboard')); ?></a></li>
				<li><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Users')); ?></li>
				
			</ul>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6">
			<div class="btn-group">
				<a href="<?php echo CONFIG_PATH_SITE_USER;?>users.html" class="btn <?php echo (($type == '') ? 'btn-primary' : 'btn-default')?>"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_all_users')); ?></a>
				<a href="<?php echo CONFIG_PATH_SITE_USER;?>users.html?type=active" class="btn <?php echo (($type == 'active') ? 'btn-primary' : 'btn-default')?>"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_active')); ?></a>
				<a href="<?php echo CONFIG_PATH_SITE_USER;?>users.html?type=inactive" class="btn <?php echo (($type == 'inactive') ? 'btn-primary' : 'btn-default')?>"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_inactive')); ?></a>
				<a href="<?php echo CONFIG_PATH_SITE_USER;?>users.html?type=suspended" class="btn <?php echo (($type == 'suspended') ? 'btn-primary' : 'btn-default')?>"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_suspend')); ?></a>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="btn-group pull-right">
				<a href="<?php echo CONFIG_PATH_SITE_USER;?>user_add.html" class="btn btn-success"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_new_user')); ?></a>
			</div>
		</div>
	</div>

	<div class="panel MT10">
		<div class="panel-heading">
			<?php //$lang->prints('lbl_user_managers'); ?>
		</div>
		<?php
			$paging = new paging();
			$offset = (isset($_GET["offset"])) ? $_GET["offset"] : 0;
			$limit = 40;
			$qLimit = " limit $offset,$limit";
			$extraURL = '&type=' . $type;
			
			
			$qType = '';
			
			switch($type)
			{
				case 'active':
					$qType = ' status=1 ';
					break;
				case 'inactive':
					$qType = ' status=0 ';
					break;
				case 'suspended':
					$qType = ' status=2 ';
					break;
			}
			
			
			$qType = ($qType == '') ? '' : ' and ' . $qType;

			
			$sql = 'select * from ' . USER_MASTER . '
							where reseller_id=' . $mysql->getInt($member->getUserId()) . $qType . ' order by username';
			$query = $mysql->query($sql . $qLimit);
			$strReturn = "";
			
			$pCode = $paging->recordsetNav($sql,CONFIG_PATH_SITE_USER . 'imei.html',$offset,$limit,$extraURL);
			
			$i = $offset;

			if($mysql->rowCount($query) > 0)
			{?>
			<table class="table table-hover table-striped">
				<tr>
					<th width="16"></th>
					<th width="16"></th>
					<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_user_name')); ?></th>
					<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_email')); ?></th>
					<th width="10%"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_Credit')); ?></th>
					<th width="150"></th>
				</tr>

			<?php
				$rows = $mysql->fetchArray($query);
				foreach($rows as $row)
				{
					$i++;
					echo '<tr>';
					echo '<td>' . $i . '</td>';
					echo '<td>' . $graphics->status($row['status']) . '</td>';
					echo '<td>' . $row['username'] . '</td>';
					echo '<td>' . $row['email'] . '<br /><small><b>' . $row['first_name'] . ' ' . $row['last_name'] . '</b></small></td>';
					echo '<td>' . $row['credits'] . '</td>';
					echo '<td class="text-right" width="50%">
							<div class="btn-group">
								<a href="' . CONFIG_PATH_SITE_USER . 'user_edit.html?id=' . $row['id'] . '" class="btn btn-primary btn-sm">' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_settings')) . '</a>
								<a href="' . CONFIG_PATH_SITE_USER . 'user_credits.html?id=' . $row['id'] . '" class="btn btn-default btn-sm">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_Credit')). '</a>
							        
                                                        </div>
                                                        <div class="btn-group text-left">
									<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
										' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_set_price')) . '
										<span class="caret"></span>
									</button>
									<ul class="dropdown-menu">
										<li><a href="' . CONFIG_PATH_SITE_USER . 'users_imei_service.html?id=' . $row['id'] . '" class="various" data-fancybox-type="iframe" >' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_IMEI_Service_Price')) . '</a></li>
							                        <li><a href="' . CONFIG_PATH_SITE_USER . 'users_file_service.html?id=' . $row['id'] . '" class="various" data-fancybox-type="iframe" >' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_FILE_Service_Price')) . '</a></li>
							                        <li><a href="' . CONFIG_PATH_SITE_USER . 'users_server_service.html?id=' . $row['id'] . '" class="various" data-fancybox-type="iframe" >' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_Server_Log_Price')) . '</a></li>
							                        <li><a href="' . CONFIG_PATH_SITE_USER . 'users_prepaid_service.html?id=' . $row['id'] . '" class="various" data-fancybox-type="iframe" >' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_Prepaid_Log_Price')) . '</a></li>
							
									</ul>
								</div>
						  </td>';
					echo '</tr>';
				}
				?>
				</table>
				<?php
			}
			else
			{ ?>	
				<div class="panel-body">
					<?php echo $graphics->messagebox_warning($admin->wordTrans($admin->getUserLang(),$lang->get('lbl_no_recors'))); ?>
				</div>
			<?php }
		?>
	</div>

	<?php echo $pCode;?>