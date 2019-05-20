<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
?>
<div class="row m-b-20">
	<div class="col-xs-12">
    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">
            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_settings')); ?></li>
            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_manage_payment_gateways')); ?></li>
        </ol>
    </div>
</div>
<div class="row">
	<div class="col-md-12">
    	<h4 class="m-b-20"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_manage_payment_gateways')); ?></h4>
		<div class="table-responsive">
        <table class="table table-hover" style="table-layout:fixed;
width:100%;
word-wrap:break-word;">
            <tr>
                <th width="16"></th>
                <th width="16"></th>
                <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_gateway')); ?></th>
                <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_gateway_id')); ?></th>
                <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_charges')); ?></th>
                <th width="100" style="text-align:center"></th>
            </tr>
            <?php
                $sql= 'select * from ' . GATEWAY_MASTER. ' a where a.m_id in(1,2,5,6,7,8)';
                $query = $mysql->query($sql);
                $strReturn = "";
                $i = 1;
                if($mysql->rowCount($query) > 0){
                    $rows = $mysql->fetchArray($query);
                    foreach($rows as $row){
                        echo '<tr>';
                        echo '<td>' . $i++ . '</td>';
                        echo '<td>' . $graphics->status($row['status']) . '</td>';
                        echo '<td>' . $row['gateway'] . '</td>';
                        echo '<td>' . $row['gateway_id'] . '</td>';
                        echo '<td>' . $row['charges'] . '%</td>';
                        echo '<td class="text-right">
                                <div class="btn-group ">
                                <a href="' . CONFIG_PATH_SITE_ADMIN . 'settings_gateway_edit.html?id=' . $row['id'] . '" class="btn btn-primary btn-sm" >' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_setting')) . '</a>
                                </div>
                              </td>';
                        echo '</tr>';
                    }
                }else{
                    echo '<tr><td colspan="8" class="no_record">'. $admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')).'</td></tr>';
                }
            ?>
        </table>
		</div>
    </div>
</div>