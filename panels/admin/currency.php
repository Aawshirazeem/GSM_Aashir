<?php
defined("_VALID_ACCESS") or die("Restricted Access");
$sql_default = 'select * from ' . CURRENCY_MASTER . ' where is_default=1';
$result = $mysql->getResult($sql_default);
$default = $result['RESULT'][0];
?>
<?php
$reply = $request->getStr('reply');
//  echo $reply;
$msg = '';
switch ($reply) {
    case 'reply_currency_inuse':
        $msg = 'Currency In Use, Cant Inactive it';
        break;
    case 'reply_edit_success':
        $msg = 'Vendor Edit Successfully';
        break;
        break;
    case 'reply_name_duplicate':
        $msg = 'Name Already Exist';
        break;
}
include("_message.php");
?>
<div class="row m-b-20">
    <div class="col-lg-12">
    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">
            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_settings')); ?></li>
            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Manage_Currency')); ?></li>
        </ol>
    </div>
</div>
<div class="row m-b-20">
    <div class="col-md-10">
        <h4 class="m-b-20">
            <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Manage_Currency')); ?>
            <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>currency_add.html" class="btn btn-info btn-sm pull-right"><i class="fa fa-plus"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_currency_add')); ?></a>
        </h4>
		<div class="table-responsive">
        <table class="table table-hover table-striped">
            <tr>
                <th width="16"></th>
                <th width="16"></th>
                <th class="text-center"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_currency')); ?></th>
                <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_rate')); ?></th>
                <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_default')); ?></th>
                <th class="text-center" colspan="4"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_sample_conversion')); ?></th>
                <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Edit')); ?></th>
            </tr>
            <?php
            $sql = 'select cm.*
                            from ' . CURRENCY_MASTER . ' cm 
                            order by currency';
            $query = $mysql->query($sql);
            $strReturn = "";
            if ($mysql->rowCount($query) > 0) {
                $rows = $mysql->fetchArray($query);
                $i = 1;
                foreach ($rows as $row) {
                    echo '<tr>';
                    echo '<td>' . $i++ . '</td>';
                      echo '<td>' . $graphics->status($row['status']) . '</td>';
                    echo '<td class="text-center">' . $row['prefix'] . '</td>';
                    echo '<td><b>' . $row['currency'] . '</b></td>';
                    echo '<td>' . $row['rate'] . '</td>';
                    echo '<td>' . (($row['is_default'] == 1) ? '<i class="fa fa-check"></i>' : '') . '</td>';
                    echo '<td class="text-right" width="150">' . $objCredits->printCredits(10, $row['prefix'], $row['suffix']) . '</td>';
                    echo '<td class="text-center" width="16">=</td>';
                    echo '<td class="text-left" width="150">' . $objCredits->printCredits(10 / $row['rate'], $default['prefix'], $default['suffix']) . '</td>';
                    echo '<td>
                                <a href="' . CONFIG_PATH_SITE_ADMIN . 'currency_edit.html?id=' . $row['id'] . '" class="btn btn-primary btn-sm">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_setting')) . '</a>';
                                   
                    if($row['is_default']!=1)
                        echo '<a href="' . CONFIG_PATH_SITE_ADMIN . 'currency_delete.html?id=' . $row['id'] . '" class="btn btn-danger btn-sm">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_delete')) . '</a>';
                    
                       echo'</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="8" class="no_record">No record found!</td></tr>';
            }
            ?>
        </table>
		</div>
    </div>
</div>
<div class="row">
    <div class="col-sm-8">
        <input type="button" class=" form-group btn btn-success" id="rate_update" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Update_Rates')); ?>" onclick="updatePrice();">
        <span id="before_done"><i class="fa fa-spinner fa-pulse"></i></span>
        <span id="after_done"><i class="fa fa-check"></i></span>
    </div>
</div>
<script>
    $('#after_done').hide();
    $('#before_done').hide();
    
function updatePrice(){
	$('#after_done').hide();
	$('#before_done').show();
	$('#rate_update').prop("disabled", true);
	$.ajax({
		type: "POST",
		//url: config_path_site_admin + "_update_rates.do",
                url: '<?php echo CONFIG_PATH_SITE_ADMIN; ?>_update_rates.do',
		// data: "&a_id=" + adminid + "&u_id=" + userid,
		error: function () {
			alert("Some Error Occur");
		},
		success: function (msg) {
			$('#before_done').hide();
			$('#after_done').show();
			$('#rate_update').prop("disabled", false);
			//alert('rates Updated');
		}
	});
}
</script>