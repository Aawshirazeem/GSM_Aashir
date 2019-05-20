<?php
defined("_VALID_ACCESS") or die("Restricted Access");
$search_user_id = $request->GetInt('search_user_id');
$search_supplier_id = $request->GetInt('search_supplier_id');
$from_date = $request->getstr("from_date");
$to_date = $request->getstr("to_date");
?>
<div class="row m-b-20">
    <div class="col-xs-12">
        <ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">
            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_dashboard')); ?></a></li>
            <li class="slideInDown wow animated"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Reports')); ?></li>
            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Balance_Report')); ?></li>
        </ol>
    </div>
</div>

<div class="row">
    <h4 class="m-b-20">
        <?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Top_Balance_Users')); ?>
        <!--        <div class="btn-group pull-right">

                    <a href="report_order_imei_profit.html" class="btn btn-primary btn-xs"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Today')); ?></a>

                    <a href="report_order_imei_profit_7.html" class="btn btn-default btn-xs"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_LAST_7_DAYS')); ?></a>

                    <a href="report_order_imei_profit_30.html" class="btn btn-default btn-xs"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_LAST_30_DAYS')); ?></a>

                </div-->
    </h4>


    <div class="clearfix"></div> 
    <!-- Chart -->
    <div class="col-sm-12">
        <div id="report_balance_used" class="graph" ></div><br>
        <!-- End Chart -->
        <?php
        $sql = 'select a.id,a.username user,a.credits_used cuse from nxt_user_master a
where a.credits_used>0
order by a.credits_used desc';
        $result = $mysql->getResult($sql);
        // var_dump($result);
        ?>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table class="MT5 table table-striped table-hover panel">
                <tr>
                    <th><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Id')); ?></th>
                    <th><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_user')); ?></th>
                    <th><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_balance_used')); ?></th>

                </tr>
                <?php
                $sql2 = 'select a.id,a.username,a.credits_used,cm.prefix
from nxt_user_master a
inner join nxt_currency_master cm on cm.id=a.currency_id
where a.credits_used>0
order by a.credits_used desc';
                $result2 = $mysql->getResult($sql2);
                if ($result2['COUNT'] > 0) {
                    foreach ($result2['RESULT'] as $row2) {
                        ?>
                        <tr>
                            <td><?php echo $row2['id']; ?></td>
                            <td><?php echo $row2['username']; ?></td>
                            <td><?php echo $row2['credits_used'] . ' ' . $row2['prefix']; ?></td>

                        </tr>



                        <?php
                    }
                } else {
                    echo '<tr><td class="text-center" colspan="3">No Record Found</td></tr>';
                }
                ?>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    var _sfunc = function () {
        Morris.Donut({
            element: 'report_balance_used',
            data: [
<?php
$str = '';
foreach ($result['RESULT'] as $row) {

    if ($row['cuse'] == '' && (int) $row['cuse'] < 0)
        $row['cuse'] = 0.00;
    $str .= '{label: \'' . $row['user'] . '\', value: ' . $row['cuse'] . '},';
}
echo trim($str, ',');
?>
            ]
        });
    }
    $(window).load(function (e) {
        _sfunc();
    });
    if (_winLoad == true)
        _sfunc();
</script>