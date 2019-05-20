<?php
	defined("_VALID_ACCESS") or die("Restricted Access");

	$type = $request->getStr('type');
	
?>
<div class="panel">
	<div class="panel-heading">
		<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_credit_statements')); ?>
	</div>
                   <div class="col-sm-4">
                        <a href="<?php echo CONFIG_PATH_SITE_USER; ?>credits_history.html" class="btn btn-default btn-md waves-effect waves-light m-b-30">All</a>
                        <a href="<?php echo CONFIG_PATH_SITE_USER; ?>credits_history_added.html" class="btn btn-primary btn-md waves-effect waves-light m-b-30">Credit Added</a>
                        <a href="<?php echo CONFIG_PATH_SITE_USER; ?>credits_history_revoked.html" class="btn btn-warning btn-md waves-effect waves-light m-b-30">Credit Revoked</a>
                    
                     </div>
	<table class="table table-striped table-hover">
		<tr>
			<th width="60"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_trans._id')); ?></th>
			<th width="60"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_order_id')); ?></th>
			<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_transection')); ?></th>
			<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_time')); ?></th>
			<th width="16" style="text-align:center"></th>
			<th width="100" style="text-align:center"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_credits')); ?></th>
			<th width="100" style="text-align:center"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_net')); ?></th>
		</tr>
		<tr class="searchPanel hidden">
			<td></td>
			<td></td>
			<td><input type="text" class="textbox_small" name="username" id="username" value="" /></td>
			<td class="toolbarSkin text_center" style="text-align:center">
				<input type="submit" value="Search" /><input type="button" class="showHideSearch" value="Cancel" />
			</td>
		</tr>
		<?php
			$paging = new paging();
			$offset = (isset($_GET["offset"])) ? $_GET["offset"] : 0;
			$limit = 40;
			$qLimit = " limit $offset,$limit";
			$extraURL = '&type=' . $type;
			
			
			
			$sql = 'select ctm.* , um.username as username1, um2.username as username2
					from ' . CREDIT_TRANSECTION_MASTER . ' ctm
					left join ' . USER_MASTER . ' um on (ctm.user_id=um.id)
					left join ' . USER_MASTER . ' um2 on (ctm.user_id2=um2.id)
					where (user_id=' . $member->getUserID() . ' or user_id2=' . $member->getUserID() . ') and ctm.info="Credits Revoked by Admin" 
					order by ctm.id DESC';
			$result = $mysql->getResult($sql, false, 20, $offset, CONFIG_PATH_SITE_USER . 'credits_history_revoked.html', array());
			//echo $sql;
                       // var_dump($result);
                       // exit;
                        $strReturn = "";
			
			$i = $offset;

			if($result['COUNT'])
			{
				foreach($result['RESULT'] as $row)
				{
					$i++;
					echo '<tr>';
					echo '<td align="center">' . $row['id'] . '<br /></td>';
					echo '<td align="center">';
						echo ($row['order_id_imei'] != '0') ? $row['order_id_imei'] : '';
						echo ($row['order_id_file'] != '0') ? $row['order_id_file'] : '';
						echo ($row['order_id_server'] != '0') ? $row['order_id_server'] : '';
					echo '</td>';
					echo '<td><b>' . $row['info'] . '</b><br />';
					switch($row['trans_type'])
					{
						case 6:
							echo (($member->getUserID() == $row['user_id']) ? $row['username2'] : $row['username1']);
							break;
					}
					echo '</td>';
                                        //timezone                  
                                         $dtDateTime = new DateTime($row['date_time'] , new DateTimeZone($admin->timezone()));
                                         $dtDateTime->setTimezone(new DateTimeZone($member->timezone()));
                                         $dtDateTime=$dtDateTime->format('d-M-Y H:i');
                                         //end
					echo '<td>' . date("d-M Y H:i", strtotime($dtDateTime)) . '</td>';
					//echo '<td align="center"><u>' . (($member->getUserID() == $row['user_id']) ? $row['credits_acc'] : $row['credits_acc_2']) . '</u></td>';
					//echo '<td align="center">'. (($member->getUserID() == $row['user_id']) ? $row['credits_acc_process'] : $row['credits_acc_process_2']) . '</td>';
					//echo '<td align="center">' . (($member->getUserID() == $row['user_id']) ? $row['credits_acc_used'] : $row['credits_acc_used_2']) . '</td>';
					
					echo '<td align="center">';
						switch($row['trans_type'])
						{
							case 0:
								echo '<i class="fa fa-plus-circle text-success"></i>';
								break;
							case 1:
								echo '<i class="fa fa-plus-circle text-success"></i>';
								break;
							case 2:
								echo '<i class="fa fa-minus-circle text-danger"></i>';
								break;
							case 3:
								echo '<i class="fa fa-minus-circle text-danger"></i>';
								break;
							case 6:
								if($member->getUserID() == $row['user_id'])
								{
									echo '<i class="fa fa-arrow-up text-success"></i>';
								}
								else
								{
									echo '<i class="fa fa-arrow-down text-danger"></i>';
								}
								break;
						}
					echo '</td>';
					
					echo '<td align="center"><span class="badge bg-inverse">' . $row['credits'] . '</span></td>';
					echo '<td align="center">
								<b>' . (($member->getUserID() == $row['user_id']) ? $row['credits_after'] : $row['credits_after_2']) . '</b>
							</td>';
					echo '</tr>';
				}
			}
			else
			{
				echo '<tr><td colspan="11" class="no_record">No record found!</td></tr>';
			}
		?>
	</table>
	<?php echo $result['PAGINATION'];?>
</div>