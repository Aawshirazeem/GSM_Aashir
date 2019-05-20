<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	
	$id = $request->getInt('id');

	$sql_pl = 'select * from ' . PREPAID_LOG_MASTER . ' where id=' . $mysql->getInt($id);
	$query_pl = $mysql->query($sql_pl);
	if($mysql->rowCount($query_pl) == 0)
	{
		header("location:" . CONFIG_PATH_SITE_ADMIN . "prepaid_logs_group.html?reply=" . urlencode('reply_invalid_id'));
		exit();
	}
	$rows_pl = $mysql->fetchArray($query_pl);
	$group_id = $rows_pl[0]['group_id'];
?>
	<ul class="breadcrumb">
		<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><i class="fa fa-home"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
		<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_services')); ?></li>
		<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>prepaid_logs.html"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_prepaid_logs')); ?></a></li>
		<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_prepaid_log_manager')); ?></li>
	</ul>
	<div class="row">
		<div class="col-sm-12">
			<section class="panel MT10">
				<div class="panel-heading">
					<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_prepaid_log_manager')); ?>
					<div class="btn-group pull-right">
						<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>prepaid_logs_un_add.html?id=<?php echo $id?>" class="btn btn-danger btn-xs"> <i class="fa fa-plus"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_add_username/passwords')); ?> </a>
					</div>
				</div>

				<table class="table table-hover table-striped">
				<tr></th>
					<th
					<th width="16"> </th>
					<th width="16"></th>
					<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_prepaid_log_username/password')); ?></th>
					<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_username_allocated_to:')); ?> </th>
					<th>DATE&TIME</th>
                                        <th width="180" class="text-center">Action</th>
				</tr>
				<?php
					$sql= 'select
								plum.*, um.username as uname,
								DATE_FORMAT(plum.date_created, "%d-%b-%Y %k:%i") as dtCreated,
								DATE_FORMAT(plum.date_order, "%d-%b-%Y %k:%i") as dtOrder
							from ' . PREPAID_LOG_UN_MASTER . ' plum
							left join ' . USER_MASTER . ' um on (plum.user_id = um.id)
							where plum.prepaid_log_id = ' . $mysql->getInt($id) . '
							order by plum.id DESC';
					$query = $mysql->query($sql);
					$strReturn = "";
					if($mysql->rowCount($query) > 0)
					{
						$rows = $mysql->fetchArray($query);
						$i = 0;
						foreach($rows as $row)
						{
							$i++;
							echo '<tr>';
							echo '<td>' . $i . '</td>';
							echo '<td>' . $graphics->status($row['status']) . '</td>';
							echo '<td><b>' . ((defined("DEMO")) ? '*****Demo*****' : $row['username']) . '</b></td>';
							echo '<td><b>' . $row['uname'] . '</b></td>';
                                                         $finaldate = $admin->datecalculate($row['dtCreated']);
                                                         $finaldate2="";
                                                          if ($row['dtOrder'] != '0000-00-00 00:00:00' && $row['dtOrder']!=null) {
                                $finaldate2 = $admin->datecalculate($row['dtOrder']);
                            }
                                                         
                                                        //  $finaldate2 = $admin->datecalculate($row['dtOrder']);
							echo '<td><small>' .$finaldate . '<br /><b>' . $finaldate2 . '</b></small></td>';
							echo '<td class="text-right">';
							if($row['status'] == "0")
							{
								echo '<a href="' . CONFIG_PATH_SITE_ADMIN . 'prepaid_logs_un_edit.html?id=' . $row['id'] . '&prepaid_log_id=' . $id . '" class="btn btn-primary btn-sm">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_setting')) . '</a>';
								echo ' <a href="' . CONFIG_PATH_SITE_ADMIN . 'prepaid_logs_un_delete.do?id=' . $row['id'] . '&prepaid_log_id=' . $id . '" class="btn btn-danger btn-sm">Delete</a>';
							}
							echo '</td>';
							echo '</tr>';
						}
					}
					else
					{
						echo '<tr><td colspan="8" class="no_record">'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')).'</td></tr>';
					}
				?>
				</table>
			</section>
		</div>
	</div>
	
