<?php

defined("_VALID_ACCESS") or die("Restricted Access");

$validator->formSetAdmin('user_edit_789971255d2');



$package_id = $request->getInt('package_id');



$sql_tools = 'select

							fsm.id, fsm.service_name,

							itad.amount, cm.prefix

							from ' . FILE_SERVICE_MASTER . ' fsm

							left join ' . CURRENCY_MASTER . ' cm on(cm.is_default = 1)

							left join ' . FILE_SERVICE_AMOUNT_DETAILS . ' itad on(fsm.id = itad.service_id and cm.id = itad.currency_id)';

$tools = $mysql->getResult($sql_tools);



$sql_details = 'select currency_id, service_id, amount from ' . PACKAGE_FILE_DETAILS . ' where package_id=' . $package_id . ' order by service_id, currency_id';

$packge_details = $mysql->getResult($sql_details);

$deails = array();

foreach ($packge_details['RESULT'] as $package) {

    $toolID = $package['service_id'];

    $currencyID = $package['currency_id'];

    $amount = $package['amount'];

    $details[$toolID][$currencyID] = $amount;

}



$sql_curr = 'select * from ' . CURRENCY_MASTER . ' where status=1 order by is_default DESC';

$currencies = $mysql->getResult($sql_curr);

?>

<div class="row m-b-20">

	<div class="col-xs-12">

    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>

            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_services')); ?></li>

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>package.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_special_package')); ?></a></li>

            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_file_service_list')); ?></li>

        </ol>

    </div>

</div>

<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>package_file_credit_process.do" method="post">

	<input type="hidden" name="package_id" value="<?php echo $package_id; ?>"/>

    <div class="row">

    	<div class="table-responsive">

        	<h4 class="m-b-20">

            	<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_file_service_list')); ?>

            </h4>

            <table class="table table-hover table-striped">

                <tr>

                    <th width="16"></th>

                    <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_tool')); ?></th>

    

                    <th width="120"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_org_cr')); ?></th>

                    <?php

                    foreach ($currencies['RESULT'] as $currency) {

                        echo '<th width="120">' . $currency['currency'] . '</th>';

                    }

                    ?>

                </tr>

				<?php

                if ($tools['COUNT'] > 0) {

                    $i = 1;

                    foreach ($tools['RESULT'] as $tool) {

                        echo '<tr>';

                        echo '<td>' . $i++ . '</td>';

                        echo '<td>' . $mysql->prints($tool['service_name']) . '</td>';

                        echo '<td class="text_right">

                                        ' . $tool['prefix'] . $tool['amount'] . '

                                    </td>';

                        foreach ($currencies['RESULT'] as $currency) {

                            echo '<td class="text_right">

                                        <div class="input-group">

                                            <span class="input-group-addon">' . $currency['prefix'] . '</span>

                                            <input onblur="calculaterate(this);" type="text" class="form-control" name="amount_' . $tool['id'] . '_' . $currency['id'] . '" id="amount_' . $tool['id'] . '_' . $currency['id'] . '" value="' . (isset($details[$tool['id']][$currency['id']]) ? $details[$tool['id']][$currency['id']] : '') . '" />

                                        </div>

                                        

                                      </td>';

                        }

                        echo '</tr>';

                    }

                } else {

                    echo '<tr><td colspan="6" class="no_record">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')) . '</td></tr>';

                }

                ?>

            </table>

            <div class="form-group">

                <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>package.html" class="btn btn-danger btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_cancel')); ?></a>

                <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_credits')); ?>" class="btn btn-success btn-sm" />

            </div>

        </div>

    </div>

</form>



<script type="text/javascript" src="<?php echo CONFIG_PATH_PANEL; ?>js/init_nxt_admin.js"></script>

<script type="text/javascript">

    setPathsAdmin('<?php echo CONFIG_PATH_SITE_ADMIN ?>');



    function calculaterate(e){

        var id = e.id;

        // var cur_id = id.substring(10);

        var cur_id = id.split('_')[2];

        var value = $('#' + id).val();

        var seviceid = id.split('_')[1];

        //var seviceid = id.substring(6,2);

        //alert(seviceid);

        // alert(cur_id);



        if (value != ''){

            $.getJSON(config_path_site_admin + 'service_imei_rate_calcuatios.do', {cur: cur_id, valuee: value}, function (data) {

                /* data will hold the php array as a javascript object */

                $.each(data, function (key, val) {

                    $('#amount_' + seviceid + '_' + val.id).val(val.valuee);

                    //  $('#amount_' + val.id).attr('value', val.valuee);

                    //$('#amount_' + val.id).html(val.valuee);

                });

            });

        }

    }

</script>