<?php
	defined("_VALID_ACCESS") or die("Restricted Access");

	$supplier->checkLogin();
	$supplier->reject();
	$validator->formSetSupplier('supplier_server_log_33245d3345d2');

	$type = $request->getStr('type');
	$all = $request->getInt('all');
	$all = 0;
	
	
	$showUser = $showCredits = 0;
	$sql = 'select * from ' . SUPPLIER_MASTER . ' where id=' . $supplier->getUserId();
	$query = $mysql->query($sql);
	if($mysql->rowCount($query) > 0)
	{
		$rows = $mysql->fetchArray($query);
		$showUser = $rows[0]['show_user'];
		$showCredits = $rows[0]['show_credits'];
	}

?>

<form action="<?php echo CONFIG_PATH_SITE_SUPPLIER; ?>order_server_log_<?php echo $type; ?>_process.do" enctype="multipart/form-data" method="post" name="frm_inquiry" id="frm_inquiry" class="formSkin noWidth">
	
	<div class="toolbarSkin text_right float_right" style="display:none">
		<?php
			if($all == 0)
			{
				echo '<a href="' . CONFIG_PATH_SITE_SUPPLIER . 'order_server_log.html?all=1" >' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_reply_s_all_order')) .'</a>';
			}
			else
			{
				echo '<a href="' . CONFIG_PATH_SITE_SUPPLIER . 'order_server_log.html">' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_reply_s_m_order')) .'</a>';
			}
		?>
	</div>
	<div class="clear"></div>
	<input type="hidden" value="<?PHP echo $type?>" name="type" id="type">
	<input type="hidden" value="<?PHP echo $all?>" name="all" id="all">

	<div class="row">
		<div class="col-sm-12">	
			<div class="panel MT10">
				<div class="panel-heading">
					<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_reply_log_order')); ?>
					<div class="btn-group btn-group-sm m-b-20 pull-right">
						<a href="<?php echo CONFIG_PATH_SITE_SUPPLIER;?>order_server_log.html?type=pending&all=<?php echo $all;?>" class="btn <?php echo ($type=='pending')? 'btn-primary' : 'btn-default' ; ?> btn-xs"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_pending')); ?></a>
						<a href="<?php echo CONFIG_PATH_SITE_SUPPLIER;?>order_server_log.html?type=locked&all=<?php echo $all;?>" class="btn <?php echo ($type=='locked')? 'btn-primary' : 'btn-default' ; ?> btn-xs"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_locked')); ?></a>
						<a href="<?php echo CONFIG_PATH_SITE_SUPPLIER;?>order_server_log.html?type=accepted&all=<?php echo $all;?>" class="btn <?php echo ($type=='accepted')? 'btn-primary' : 'btn-default' ; ?> btn-xs"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_available')); ?></a> 
						<a href="<?php echo CONFIG_PATH_SITE_SUPPLIER;?>order_server_log.html?type=rejected&all=<?php echo $all;?>" class="btn <?php echo ($type=='rejected')? 'btn-primary' : 'btn-default' ; ?> btn-xs"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_unavailable')); ?></a>
						<a href="<?php echo CONFIG_PATH_SITE_SUPPLIER;?>order_server_log.html?all=<?php echo $all;?>" class="btn <?php echo ($type=='')? 'btn-primary' : 'btn-default' ; ?> btn-xs"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_reply_all_order')); ?></a>
					</div>
				</div>
				<table class="table table-striped table-hover">
					<tr>
						<th width="60"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_reply_s_no')); ?></th>
						<th width="16"></th>
						<?php echo ($type == "pending") ? '<th width="50" style="text-align:center; color:#000077">' .$admin->wordTrans($admin->getUserLang(),$lang->get('lbl_reply_lock')). '</th>' : ''; ?>
						<?php echo ($type == "locked") ? '<th width="50" style="text-align:center">' .$admin->wordTrans($admin->getUserLang(),'Reply Accept'). '</th>' : ''; ?>
						<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_reply_s_name')); ?></th>
						<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_reply_info')); ?></th>
						<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_reply_credit')); ?></th>
						<?php echo ($type == "locked") ? '<th width="50" style="text-align:center;color:#FF0000">' .$admin->wordTrans($admin->getUserLang(),'Reply Reject'). '</th>' : ''; ?>
					</tr>

					<?php
						$paging = new paging();
						$offset = (isset($_GET["offset"])) ? $_GET["offset"] : 0;
						$limit = 40;
						$qLimit = " limit $offset,$limit";
						$extraURL = '&type=' . $type . '&all=' . $all;
		
		
						$qType = '';
		
						switch($type)
						{
							case 'pending':
								$qType = ' and ofsm.status=0 ';
								break;
							case 'locked':
								$qType = ' and ofsm.status=-1 and supplier_id=' . $supplier->getUserId();
								break;
							case 'accepted':
								$qType = ' and ofsm.status=1 and supplier_id=' . $supplier->getUserId();
								break;
							case 'rejected':
								$qType = ' and ofsm.status=2 and supplier_id=' . $supplier->getUserId();
								break;
						}
						if($all != '1' && $type != 'pending')
						{
							$qType .= ' and supplier_id=' . $supplier->getUserId();
						}


		
						$sql = 'select ofsm.*,
									slm.server_log_name,um.username,um.email
									from ' . ORDER_SERVER_LOG_MASTER . ' ofsm
									left join ' . USER_MASTER . ' um on(ofsm.user_id = um.id)
									left join ' . SERVER_LOG_MASTER . ' slm on (slm.id = ofsm.server_log_id)
									where ofsm.server_log_id in (select log_id from ' . SERVER_LOG_SUPPLIER_DETAILS . ' where supplier_id=' . $supplier->getUserId() . ')
									' . $qType . '
									order by ofsm.id DESC';
						//echo $sql;
		
						$query = $mysql->query($sql . $qLimit);
						$strReturn = "";
		
						$pCode = $paging->recordsetNav($sql,CONFIG_PATH_SITE_SUPPLIER . 'order_server_log.html',$offset,$limit,$extraURL);
		
						$i = $offset;

						if($mysql->rowCount($query) > 0)
						{
			
							$subtotal = $grandTotal = 0;
			
							$rows = $mysql->fetchArray($query);
							foreach($rows as $row)
							{
								$i++;
								echo '<tr>';
								echo '<td>' . $i . '</td>';
								echo '<td>';
									echo '<input type="hidden" name="Ids[]" value=' . $row['id'] . '>';
									echo '<input type="hidden" name=username_' . $row['id'] . ' value=' . $row['username'] . '>';
									echo '<input type="hidden" name=server_log_name_' . $row['id'] . ' value=' . $row['server_log_name'] . '>';
									echo '<input type="hidden" name=user_id_' . $row['id'] . ' value=' . $row['user_id'] . '>';
									echo '<input type="hidden" name=email_' . $row['id'] . ' value=' . $row['email'] . '>';
									echo '<input type="hidden" name=credits_' . $row['id'] . ' value=' . $row['credits'] . '>';
									switch($row['status'])
									{
										case 0:
											echo '<span class="label label-default">'.$admin->wordTrans($admin->getUserLang(),$lang->get('lbl_pending')).'</span>';
											break;
										case 1:
											echo '<span class="label label-primary">'.$admin->wordTrans($admin->getUserLang(),$lang->get('lbl_locked')).'</span>';
											break;
										case 2:
											echo '<span class="label label-success">'.$admin->wordTrans($admin->getUserLang(),$lang->get('lbl_available')).'</span>';
											break;
										case 3:
											echo '<span class="label label-danger">'.$admin->wordTrans($admin->getUserLang(),$lang->get('lbl_unavailable')).'</span>';
											break;
									}
								echo '</td>';
								echo ($type == "pending") ? '<td style="text-align:center; background-color:#DDDDFF;"><input type="checkbox" class="checkbox-inline" name="locked_' . $row['id'] . '"></td>' : '';
								echo ($type == "locked") ? '<td style="text-align:center"><input type="checkbox" class="checkbox-inline" name="accept_' . $row['id'] . '"></td>' : '';
							    echo '<td>';
									echo  $row['server_log_name'];
									echo ($showUser == 1) ? '<span class="text-warning">' . $row['username'] . '</span>' : '';
								echo '</td>';
								echo '<td>' . $row['custom_value'] . '</td>';
								echo '<td>';
									echo ($showCredits == 1) ? '<span class="label label-info">' . $row['credits'] . '</span>' : '';
									if($type == 'accepted')
									{
										$grandTotal += $row['credits_purchase'];
										echo '<span class="text-danger">' . $row['credits_purchase'] . '</span>';
									}
								echo '</td>';
								echo ($type == "locked") ? '<td  style="text-align:center; background-color:#FFDDDD;"><input type="checkbox" class="checkbox-inline" name="reject_' . $row['id'] . '"><br /><input type="text" name="un_remarks_' . $row['id'] . '" value="" class="textbox_small"></td>' : '';
								echo '</tr>';
							}
						}
						else
						{
							echo '<tr><td colspan="9" class="no_record">' .$admin->wordTrans($admin->getUserLang(),$lang->get('reply_imi_s_no_order')). '</td></tr>';
						}
					?>
				</table>
			</div>
		<?php
			if($type == 'accepted')
			{
				echo 'Total Profit:' . $grandTotal;
			}
			if($type == 'pending' || $type == 'locked')
			{
				echo '<div class="btn-group"><input type="submit" value="'  . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_reply_p_select')) . '" class="btn btn-success btn-sm"/></div>';
			}
		?>
		</div>
	</div>
	</form>
	<?php echo $pCode;?>
