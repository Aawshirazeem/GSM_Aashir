<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
?>

	<section class="panel MT10">
		<div class="panel-heading">
			<?php //$lang->prints('lbl_transections'); ?>
		</div>
		<?php
			$paging = new paging();
			$offset = $request->getInt('offset');
			$i = $offset;
			$i++;
			$limit = 100;
			$qLimit = " limit $offset,$limit";

		?>
		<?php
			//$qType = ($qType == '') ? '' : ' where ' . $qType;
			$qType = '';
			if($id != 0)
			{
				$qType =' where  ctm.user_id = ' . $id .' or ctm.user_id2=' . $id;
                                
 

			}
			$sql = 'select
					ctm.id, ctm.date_time, ctm.credits, ctm.views, ctm.trans_type,ctm.info,ctm.user_id,
					um.username as username1, 
					um2.username as username2,
					date(ctm.date_time) as dt,
					im.id invoice_id
					from ' . CREDIT_TRANSECTION_MASTER . ' ctm
					left join ' . INVOICE_MASTER . ' im on (im.txn_id=ctm.id)
					left join ' . USER_MASTER . ' um on (ctm.user_id=um.id)
					left join ' . USER_MASTER . ' um2 on (ctm.user_id2=um2.id)
					' . $qType . '
					order by ctm.id  DESC ';
                        $result = $mysql->getResult($sql, false, 20, $offset, CONFIG_PATH_SITE_ADMIN . 'users_edit.html?id=3&limit=60#tabs-10', array());
			$query = $mysql->query($sql . $qLimit);
	
			$pCode ='';
			if($mysql->rowCount($query) > 0)
			{
				$transs = $mysql->fetchArray($query);
			?>
		<table class="table table-striped table-hover">
			<tr>
				<th width="10"></th>
				<th width="10"></th>
				<th> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_date')); ?> </th>
				<th> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_invoice')); ?> </th>
                                <th>Transaction</th>
                                <th>Credit Type</th>
				<th class="text-right"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_amount')); ?> </th>
			</tr>
			<?php
				foreach($transs as $trans)
				{
		?>
					<tr <?php //echo ($trans['views'] < 5) ? 'style="color:red;font-weight:bold"' : '';?>>
						<td><small><?php echo $i++ ?></small></td>
						<td><?php if($trans['username2'] != ''){ echo '<i class="icon-arrow-left"></i>'; } ?></td>
						<td><?php echo date("d-M Y H:i", strtotime($trans['date_time']));?></td>
						<td><?php echo ($trans['invoice_id'] != 0) ? ('INV #' . str_pad($trans['invoice_id'],4,'0',0)) : '';?></td>
                                                <td><?php echo $trans['info'];?></td>
						<?php	echo '<td align="center">';
						switch($trans['trans_type'])
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
								if($id == $trans['user_id'])
								{
									echo '<i class="fa fa-arrow-up text-success"></i>';
								}
								else
								{
									echo '<i class="fa fa-arrow-down text-danger"></i>';
								}
								break;
						}
					echo '</td>';?>
					
                                                
                                                <td class="text-right"><?php echo $trans['credits'];?></td>
					</tr>
		<?php
				}
		echo '</table>';
				echo $pCode;
			}
			else
			{
				$msg=$admin->wordTrans($admin->getUserLang(),$lang->get('lbl_no_record_found'));
				echo $graphics->messagebox_warning($msg);
			}
		?><?php //echo $result['PAGINATION'];?>
	</section>

