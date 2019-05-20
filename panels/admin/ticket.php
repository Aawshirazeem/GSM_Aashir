<?php

	defined("_VALID_ACCESS") or die("Restricted Access");

	$type = $request->getStr('type');

?>



<div class="m-t-10">

	<h4 class="panel-heading m-b-20">

		<?php
        if($type == "closed"){
			$admin->wordTrans($admin->getUserLang(),$lang->get('lbl_close'));
		}else{
			$admin->wordTrans($admin->getUserLang(),$lang->get('lbl_open'));
		}
		?> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_ticket')); ?>

		<div class="btn-group btn-group-sm pull-right">

			<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>ticket.html?type=open" class="btn btn-xs <?php echo (($type == 'open' or $type == '') ? 'btn-primary' : 'btn-default')?>"><i class="icon-ok"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_open')); ?></a>

			<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>ticket.html?type=closed" class="btn btn-xs <?php echo (($type == 'closed') ? 'btn-primary' : 'btn-default')?>"><i class="icon-remove"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_close')); ?></a>

		</div>

	</h4>
	
	<div class="table-responsive">

	<table class="table table-striped table-hover">

		<tr>

			<th width="60"></th>

	

			<th width="100"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_username')); ?></th>

			<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_ticket_subject')); ?></th>
                        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_DateTime')); ?></th>

			<th width="50"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_posts')); ?></th>

			<th width="250"></th>

		</tr>

		<?php

			$paging = new paging();

			$offset = (isset($_GET["offset"])) ? $_GET["offset"] : 0;

			$limit = 40;

			$qLimit = " limit $offset,$limit";

			$extraURL = '&type=' . $type;

			

			

			$qType = '';

			

			if($type == "closed")

			{

				$qType = ' where tm.status=0 ';

			}

			else

			{

				$qType = ' where tm.status=1 ';			

			}

			

			

			//$qType = ($qType == '') ? '' : ' and ' . $qType;



			

			$sql = 'select tm.*, 

						um.username,

						(select count(id) from ' . TICKET_DETAILS . ' td where tm.ticket_id=td.ticket_id) as total,

						(select user_admin from ' . TICKET_DETAILS . ' td where tm.ticket_id=td.ticket_id order by id DESC limit 1 ) as lastPostType

						from ' . TICKET_MASTER . ' tm

						left join ' . USER_MASTER . ' um on (tm.user_id = um.id)

						' . $qType . '

						order by tm.ticket_id DESC';

			$query = $mysql->query($sql . $qLimit);

			$strReturn = "";

			

			$pCode = $paging->recordsetNav($sql,CONFIG_PATH_SITE_ADMIN . 'ticket.html',$offset,$limit,$extraURL);

			

			$i = $offset;



			if($mysql->rowCount($query) > 0)

			{

				$rows = $mysql->fetchArray($query);

				foreach($rows as $row)

				{
 $finaldate = $admin->datecalculate($row['date_time']);
					$i++;

					echo '<tr>';

						echo '<td>' . $i . '</td>';

						

						echo '<td>' . $row['username'] . '</td>';
                                              

						echo '<td><a href="' . CONFIG_PATH_SITE_ADMIN . 'ticket_details.html?id=' . $row['ticket_id'] . '">' . $row['subject'] . '</a></td>';
  echo '<td>' .$finaldate . '</td>';
						echo '<td>' . $row['total'] . '</td>';

						echo '<td class="text-right">';

						echo '<div class="btn-group">';

								echo '<a href="' . CONFIG_PATH_SITE_ADMIN . 'ticket_details.html?id=' . $row['ticket_id'] . '" class="btn btn-default btn-sm">'. $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_view_ticket')).'</a> ';

								if($row['status'] == '1')

								{

									echo ' <a href="' . CONFIG_PATH_SITE_ADMIN . 'ticket_close.html?id=' . $row['ticket_id'] . '" class="btn btn-danger btn-sm">' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_close_ticket')).'</a> ';

								}

								else

								{

									echo '<a href="' . CONFIG_PATH_SITE_ADMIN . 'ticket_reopen.html?id=' . $row['ticket_id'] . '&dt=' . $row['subject'] . '" class="btn btn-warning btn-sm">' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_re-open_ticket')).'</a> ';

								}

						echo '</div>';

						echo '</td>';

					echo '</tr>';

				}

			}

			else

			{

				echo '<tr><td colspan="7" class="no_record">'. $admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')).'</td></tr>';

			}

		?>

		</table>

	</div>
	
	</div>

<div class="row m-t-20">
	<div class="col-md-6 p-l-0">
    	<div class="TA_C navigation" id="paging">
			<?php  echo $pCode;  ?>
        </div>
    </div>
    <div class="col-md-6">
    	
    </div>
</div>