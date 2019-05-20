<style>
  #gsmDetails:hover { cursor:pointer; }
  .gsmDetailsInner span { font-weight:bold; min-width:115px; float:left !important; }
</style>
<?php
	defined("_VALID_ACCESS") or die("Restricted Access");

	$type = $request->getStr('type');
	
	if($service_file == "0")
	{
		echo "<h1>".$admin->wordTrans($admin->getUserLang(),$lang->get('lbl_you_are_authorize_to_view_this_page!'))."</h1>";
		return;
	}
	$crM = $objCredits->getMemberCredits();
	$prefix = $crM['prefix'];
	$suffix = $crM['suffix'];
	$rate = $crM['rate'];
        $reply = $request->getStr('reply');
//  echo $reply;
$msg = '';
switch ($reply) {
    case 'reply_file_type_not_supported':
        $msg = 'File Type Not Supported';
        break;
    case 'reply_no_file_selected':
        $msg = 'No File Selected';
        break;

    case 'reply_insuffi_credit':
        $msg = 'No Credit';
        break;
}
include("_message.php");
?>
<h3><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_file_service_orders')); ?></h3>
<div class="row">
	<div class=" card-box col-sm-12">
		<div class="btn-group">
			<a href="<?php echo CONFIG_PATH_SITE_USER;?>files.html?type=pending" class="btn <?php echo ($type == 'pending') ? 'btn btn-primary btn-custom waves-effect waves-light' : 'btn btn-default btn-custom waves-effect waves-light'; ?>"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_New_Order')); ?></a>
			<a href="<?php echo CONFIG_PATH_SITE_USER;?>files.html?type=avail" class="btn <?php echo ($type == 'avail') ? 'btn btn-primary btn-custom waves-effect waves-light' : 'btn btn-default btn-custom waves-effect waves-light'; ?>"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_Completed')); ?></a>
			<a href="<?php echo CONFIG_PATH_SITE_USER;?>files.html?type=rejected" class="btn <?php echo ($type == 'rejected') ? 'btn btn-primary btn-custom waves-effect waves-light' : 'btn btn-default btn-custom waves-effect waves-light'; ?>"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_rejected')); ?></a>
			<a href="<?php echo CONFIG_PATH_SITE_USER;?>files.html" class="btn <?php echo ($type == '') ? 'btn btn-primary btn-custom waves-effect waves-light' : 'btn btn-default btn-custom waves-effect waves-light'; ?>"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_all_orders')); ?></a>
		</div>
           
            <div class="btn-group" style="float: right">
			<a href="<?php echo CONFIG_PATH_SITE_USER;?>file_submit.html" class="btn btn-success"><i class="icon-plus"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_order_new_file_service')); ?></a>
		</div>
	
	</div>
	
</div>

	
	<div class="clear"></div>
        <div class="panel MT10 table-responsive">
	<table class="table table-hover table-striped table-bordered">
	<tr>
		
            <th></th>
                <th class="text-center"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_Order')); ?>#</th>
               
		<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_file_service')); ?></th>
                 <th class="text-center"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_File')); ?></th>
		<!--th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_file_name')); ?></th-->
               
		<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_unlock_code')); ?></th>
                <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_notes')); ?></th>
                 <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Date')); ?></th>
		<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_credits')); ?></th>
                <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Action')); ?></th>
	</tr>
	<?php
		$paging = new paging();
		$offset = (isset($_GET["offset"])) ? $_GET["offset"] : 0;
		$limit = 40;
		$qLimit = " limit $offset,$limit";
		$extraURL = '&type=' . $type;
		
		
		$qType = '';
		
		switch($type)
		{
			case 'pending':
				$qType = ' (im.status=0 or im.status=-1) ';
				break;

			case 'avail':
				$qType = ' im.status=1 ';
				break;
			case 'rejected':
				$qType = ' im.status=2 ';
				break;
		}
		
		
		$qType = ($qType == '') ? '' : ' and ' . $qType;

		
		$sql = 'select im.*, slm.service_name
					from ' . ORDER_FILE_SERVICE_MASTER . ' im
					left join ' . FILE_SERVICE_MASTER . ' slm on (slm.id = im.file_service_id)
					where 
					im.user_id=' . $member->getUserId() . '
					' . $qType . ' order by im.id DESC';
		$query = $mysql->query($sql . $qLimit);
		$strReturn = "";
		
		$pCode = $paging->recordsetNav($sql,CONFIG_PATH_SITE_USER . 'files.html',$offset,$limit,$extraURL);
		
		$i = $offset;

		if($mysql->rowCount($query) > 0)
		{
			$rows = $mysql->fetchArray($query);
			foreach($rows as $row)
			{
				$i++;
                                
                                
                                   
                                    $finaldate1 = $member->datecalculate($row['date_time'] );
                                 $finaldate2='';
                             
                    if ($row['reply_date_time'] != '0000-00-00 00:00:00' && $row['reply_date_time'] !=NULL) {
                        
                          $finaldate2 = $member->datecalculate($row['reply_date_time']);
                       
                    }
if($finaldate2=="")
                        $finaldate2="NA";
switch($row['status'])
					{
						
						case 0:
							$tempstatus=$admin->wordTrans($admin->getUserLang(),$lang->get('com_New_Order'));
							break;
                                                    case -1:
							$tempstatus=$admin->wordTrans($admin->getUserLang(),$lang->get('com_In_Process'));
							break;
						case 1:
							$tempstatus=$admin->wordTrans($admin->getUserLang(),$lang->get('com_Completed'));
							break;
						case 2:
							$tempstatus=$admin->wordTrans($admin->getUserLang(),$lang->get('com_rejected'));
							break;
					}
                                
				echo '<tr>';
                                $order_reply=$row['unlock_code'];
                                if($row['reply']!="")
                                    $order_reply=$row['reply'];
                                
                                if($order_reply=="")
                                    $order_reply="NA";
                                echo '<td>
								<a id="gsmDetails" data-toggle="collapse" data-target="#collapse-example-' . $row['id'] . '" aria-expanded="true" aria-controls="collapse-example-1"><i class="fa fa-plus" style="margin:8px 0 0 12px"></i></a>
								<div id="collapse-example-' . $row['id'] . '" class="collapse out gsmDetailsInner" aria-expanded="true">
									<div class="card card-block"> 
										<p><span>Order :</span>' . $row['id'] . '</p>
									
										<p><span>Code :</span>' .$order_reply. '</p>
										<p><span>Submitted On :</span>'.$finaldate1.'</p>
										<p><span>Replied On :</span> '.$finaldate2.'</p>
                                                                                    <p><span>Price :</span> ' . $objCredits->printCredits($row['credits'], $prefix, $suffix). '</p>
                                                                                        <p><span>Status:</span>'.$tempstatus.'</p>
                                                                                            <p><span>User Notes:</span>'.$row['remarks'].'</p>
                                                                                    
									</div>
								</div>
						  </td>';
				//echo '<td>' . $i . '</td>';
                                echo '<td><b>' . $row['id'] . '</b><br>';
                                switch($row['status'])
					{
						
						case 0:
							echo '<span class="label label-primary">'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_New_Order')).'</span>';
							break;
                                                    case -1:
							echo '<span class="label label-default">'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_In_Process')).'</span>';
							break;
						case 1:
							echo '<span class="label label-success">'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_Completed')).'</span>';
							break;
						case 2:
							echo '<span class="label label-danger">'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_rejected')).'</span>';
							break;
					}
				echo '</td>';
					
                               
                                
				echo '<td><b>' . $row['service_name'] . '</b></td>';
                                 echo '<td>';
                                echo $row['f_name'];
				echo '</td>';
//				echo '<td>';
//					echo ($row['fileask'] != '') ? ('<small>' . $mysql->prints($row['fileask']) . '</small>') : '';
//					echo ($row['filerpl'] != '') ? ('<br /><small>' . $mysql->prints($row['filerpl']) . '</small>') : '';
//					echo ($row['message'] != '' ) ? ('<br /><span class="text-danger">'  . $row['message'] . '</span>')  : '';							
//				echo '</td>';
                                
                                
                                // change date and time according to time zoness
                                
                             
                                 
                                    $finaldate1 = $member->datecalculate($row['date_time'] );
                                 $finaldate2='';
                             
                    if ($row['reply_date_time'] != '0000-00-00 00:00:00' && $row['reply_date_time'] !=NULL) {
                        
                          $finaldate2 = $member->datecalculate($row['reply_date_time']);
                       
                    }


                                
                                
                                
                               
				echo '<td>';
				if (defined("DEMO"))
				{
					echo '*****Demo*****';
				}
				else
				{
					 if ($row['status'] == 1) {
                echo (($row['unlock_code'] != '' and $row['unlock_code'] != '0') ? nl2br($mysql->prints($row['unlock_code'])) : '');
            } 
            
            elseif ($row['status'] == 2 && $row['reply']=='' ) {
               echo (($row['message'] != '') ? $mysql->prints($row['message']) : '');
        }
            else {
                //echo (($row['unlock_code'] !='' and $row['unlock_code'] != '0') ? nl2br($mysql->prints($row['unlock_code'])) : '') ;
                echo (($row['reply'] != '') ? $mysql->prints($row['reply']) : '');
            }
				}
				echo '</td>';
                                 echo '<td class="text-center">' . $row['remarks'] . '</td>';
                                  echo '<td>' . $finaldate1 . '<br /><b>' .  $finaldate2  . '</b></small></td>';
				echo '<td>' . $objCredits->printCredits($row['credits'], $prefix, $suffix) . '</td>';
				echo '<td class="text-right">';
					echo '<div class="btn-group">';
					$ask = CONFIG_PATH_EXTRA_ABSOLUTE . "file_service/" . $mysql->prints($row['fileask']);
					$rpl = CONFIG_PATH_EXTRA_ABSOLUTE . "file_service/" . $mysql->prints($row['filerpl']);
					
					if($row['f_content'] != "")
					{
						echo '<a href="' . CONFIG_PATH_SITE_USER . 'download4.html?id=' . $row['id'] . '" class="btn btn-default btn-sm"> <i class="icon-download"></i> '.$admin->wordTrans($admin->getUserLang(),$lang->get('lbl_Download')).'</a>';
					}
					
					if(file_exists($rpl) and $row['filerpl'] != "")
					{
						echo '<a  href="' . CONFIG_PATH_SITE_USER . 'download3.html?type=askrpl&file_name=' . $row['filerpl'] . '" class="btn btn-default btn-sm"> <i class="icon-download"></i> '.$admin->wordTrans($admin->getUserLang(),$lang->get('lbl_rpl')).'</a>';
					}
					if($row['status'] == "0")
					{
						echo '<a href="' . CONFIG_PATH_SITE_USER . 'file_cancel.do?id=' . $row['id'] . '"  class="btn btn-danger btn-sm"> <i class="icon-remove"></i> '.$admin->wordTrans($admin->getUserLang(),$lang->get('com_cancel')).'</a>';
					}
					echo '</div>';
				echo '</td>';
				echo '</tr>';
			}
		}
		else
		{
			echo '<tr><td colspan="6" class="no_record">'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')).'</td></tr>';
		}
	?>
	</table></div>
	<?php echo $pCode;?>