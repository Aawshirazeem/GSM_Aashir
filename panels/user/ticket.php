<?php
defined("_VALID_ACCESS") or die("Restricted Access");
$type = $request->getStr('type');
?>
<div class="lock-to-top">
    <h1>
	<?php 
	if($type == "closed") {
		  $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_closed'));
	}else{
		 	$admin->wordTrans($admin->getUserLang(),$lang->get('lbl_open')); 
	
         }?>
         <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_ticket')); ?>
   </h1>
    <div class="btn-group">
        <a href="<?php echo CONFIG_PATH_SITE_USER; ?>ticket_add.html" class="btn btn-success"><i class="icon-plus"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_ticket')); ?></a>
    </div>
    <div class="btn-group pull-right">
        <a href="<?php echo CONFIG_PATH_SITE_USER; ?>ticket.html?type=open" class="btn btn-success"> <i class="icon-ok"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_open')); ?></a>
        <a href="<?php echo CONFIG_PATH_SITE_USER; ?>ticket.html?type=closed" class="btn btn-danger"> <i class="icon-remove"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_closed')); ?></a>
    </div>
</div>



<div class="clear"></div>
<div class="card-box">
<table class="MT5 table table-striped table-hover panel">
    <tr>
        <th>#</th>
        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_id')); ?></th>
         <th></th>
        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_ticket_subject')); ?></th>
        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_posts')); ?></th>
        <th width="250"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Action')); ?></th>
    </tr>
    <?php
    $paging = new paging();
    $offset = (isset($_GET["offset"])) ? $_GET["offset"] : 0;
    $limit = 40;
    $qLimit = " limit $offset,$limit";
    $extraURL = '&type=' . $type;


    $qType = '';

    if ($type == "closed") {
        $qType = ' status=0 ';
    } else {
        $qType = ' status=1 ';
    }


    $qType = ($qType == '') ? '' : ' and ' . $mysql->getStr($qType);


    $sql = 'select tm.*, 
					(select count(id) from ' . TICKET_DETAILS . ' td where tm.ticket_id=td.ticket_id) as total,
					(select user_admin from ' . TICKET_DETAILS . ' td where tm.ticket_id=td.ticket_id order by id DESC limit 1 ) as lastPostType
					from ' . TICKET_MASTER . ' tm where tm.user_id=' . $mysql->getInt($member->getUserId()) . $qType . ' order by ticket_id DESC';
    $query = $mysql->query($sql . $qLimit);
    $strReturn = "";

    $pCode = $paging->recordsetNav($sql, CONFIG_PATH_SITE_USER . 'imei.html', $offset, $limit, $extraURL);

    $i = $offset;

    if ($mysql->rowCount($query) > 0) {
        $rows = $mysql->fetchArray($query);
        foreach ($rows as $row) {
            $i++;
            echo '<tr>';
            echo '<td>' . $i . '</td>';
             echo '<td>' .$row['ticket_id']  . '</td>';
            echo '<td>';
            if ($row['lastPostType'] == '1') {
                echo '<i class="icon-asterisk"></i>';
            }
            echo '</td>';
            echo '<td>' . $row['subject'] . '</td>';
            echo '<td>' . $row['total'] . '</td>';
            echo '<td class="">
							<div class="btn-group">
								';
            if ($row['status'] == '1') {
                echo '<a href="' . CONFIG_PATH_SITE_USER . 'ticket_details.html?id=' . $row['ticket_id'] . '" class="btn btn-inverse"> <i class="ion-eye"></i>' . $admin->wordTrans($admin->getUserLang(),'View') . '</a>';
                echo '<a href="' . CONFIG_PATH_SITE_USER . 'ticket_close.html?id=' . $row['ticket_id'] . '&dt=' . $row['subject'] . '" class="btn btn-danger"> <i class="ion-close"></i> ' . $admin->wordTrans($admin->getUserLang(),'closed') . '</a>';
            } else {
                                
                echo '<a href="' . CONFIG_PATH_SITE_USER . 'ticket_details.html?id=' . $row['ticket_id'] . '" class="btn btn-inverse"> <i class="ion-eye"></i> ' . $admin->wordTrans($admin->getUserLang(),'View') . '</a>';

                echo '<a href="' . CONFIG_PATH_SITE_USER . 'ticket_reopen.html?id=' . $row['ticket_id'] . '&dt=' . $row['subject'] . '" class="btn btn-warning"> <i class="ion-arrow-return-left"></i> ' . $admin->wordTrans($admin->getUserLang(),'re-open') . '</a>';
            }
            echo '
							</div>
						</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="7" class="no_record">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')) . '!</td></tr>';
    }
    ?>
</table></div>
    <?php echo $pCode; ?>