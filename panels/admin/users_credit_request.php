<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$inidr = $request->getStr('inidr');
	if($inidr>0)
	{
		$sql_in = 'update '.INVOICE_REQUEST	.' set status=2 where id='.$inidr;
		$mysql->query($sql_in);
	}
?>
<div class="row m-b-20">
	<div class="col-xs-12">
    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">
        	<li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
			<li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_admin_option')); ?></li>
			<li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_invoice_request')); ?></li>
        </ol>
        <h4 class="m-t-10"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_invoice_request')); ?> </h4>
        <div class="table-responsive">
        	<table class="table table-hover table-striped">
            	<tr>
                    <th width="60">#</th>
                    <th ><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_username')); ?></th>
                    <th width="250" ><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_payment_gateway')); ?></th>
                    <th ><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_date')); ?></th>
                    <th width="100"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_amount')); ?></th>
                    <th width="100"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_credits')); ?></th>
                    <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_status')); ?></th>
                    <th width="200"></th>
                </tr>
                <?php
					$paging = new paging();
					$offset = (isset($_GET["offset"])) ? $_GET["offset"] : 0;
					$limit = 40;
					$qLimit = " limit $offset,$limit";
					$extraURL = '';
					
					$sql = 'select im.*,um.username, cm.prefix,cm.suffix, gm.gateway, cmd.prefix defaultPrefix, cmd.suffix defaultSuffix
								from ' . INVOICE_REQUEST . ' im
								left join '.USER_MASTER.' um on (im.user_id = um.id)
								left join ' . CURRENCY_MASTER . ' cm on (im.currency_id = cm.id)
								left join ' . CURRENCY_MASTER . ' cmd on (cmd.is_default = 1)
								left join ' . GATEWAY_MASTER . ' gm on (im.gateway_id = gm.id)
								where im.status=0
							order by im.id DESC';
					$query = $mysql->query($sql);
					$strReturn = "";
					
					$pCode = $paging->recordsetNav($sql,CONFIG_PATH_SITE_ADMIN . 'users_credit_request.html',$offset,$limit,$extraURL);
					$i = $offset;
					if($mysql->rowCount($query) > 0){
						$rows = $mysql->fetchArray($query);
						foreach($rows as $row){
							$i++;
                                                            $finaldate = $admin->datecalculate($row['date_time']);
				?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['gateway'] . '<br />' . $row['txn_id']; ?></td>
                        <td><?php echo $finaldate; ?></td>
                        <td><?php echo $objCredits->printCredits($row['amount'], $row['prefix'], $row['suffix']); ?></td>
                        <td><?php echo $objCredits->printCredits($row['credits'], $row['prefix'], $row['suffix']); ?></td>
                        <td>
                            <?php
                                switch($row['status']){
                                    case '0':
                                        echo $admin->wordTrans($admin->getUserLang(),$lang->get('com_pending'));
                                        break;
                                    case '1':
                                        echo $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_done'));
                                        break;
                                    case '2':
                                        echo $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_canceled'));
                                        break;
                                }
                            ?>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a class="btn btn-success btn-sm" href="<?php echo CONFIG_PATH_SITE_ADMIN . 'users_credits.html?id=' . $row['user_id'] . '&rid='.$row['id'].'&credits='.$row['credits'];?>" class="active" ><?php echo $admin->wordTrans($admin->getUserLang(),'Accept'); ?></a>
                                <a class="btn btn-danger btn-sm" href="<?php echo CONFIG_PATH_SITE_ADMIN . 'users_credit_request.html?inidr='.$row['id']; ?>" ><?php echo $admin->wordTrans($admin->getUserLang(),'Reject'); ?></a>
                            </div>
                        </td>
                    </tr>
                <?php
						}
					}else{
				?>
                	<tr>
                    	<td colspan="8" class="no_record">
							<?php echo $admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')); ?>
                        </td>
                    </tr>
                <?php
					}
				?>
            </table>
        </div>
    </div>
</div>