<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}
$admin->checkLogin();
$admin->reject();
?>
<div class="row">

    <div class="col-xs-10">
        <h4 class="m-b-20">

            <?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Order_notification(s)')); ?>
            <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>clr_all_notifications.html" class="btn btn-sm btn-danger pull-right"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_clear_all_notification')); ?></a>


        </h4>
        <div class="table-responsive">
            <table class="table table-striped table-hover panel">   

                <tr>



                    <td><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_type')); ?></td>

                    <td><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_tool')); ?></td>

                    <td><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_orders')); ?></td>

                    <td><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_action')); ?></td>





                </tr>

                <?php
                $sql = 'select * from ' . ADMIN_MASTER . ' order by username';
                $sql = 'select a.id, "IMEI" as type, b.tool_name as tool,a.total_orders as torders from nxt_notifications a

inner join nxt_imei_tool_master b
on a.service_id=b.id

where a.display=1 and a.order_type=1

union
select a.id, "FILE" as type,  c.service_name as tool,a.total_orders as torders from nxt_notifications a

inner join nxt_file_service_master c
on a.service_id=c.id

where a.display=1 and a.order_type=2
union
select a.id, "SERVER" as type,  d.server_log_name as tool,a.total_orders as torders from nxt_notifications a

inner join nxt_server_log_master d
on a.service_id=d.id

where a.display=1 and a.order_type=3';

                $result = $mysql->getResult($sql);

                if ($result['COUNT']) {

                    foreach ($result['RESULT'] as $row) {

                        echo '<tr id="' . $row['id'] . '">';

                        // echo '<td width="16">' . $row['id'] . '</td>';

                        echo '<td>' . $mysql->prints($row['type']) . '</td>';

                        echo '<td>' . $mysql->prints($row['tool']) . '</td>';

                        echo '<td>' . $mysql->prints($row['torders']) . '</td>';

                        // echo '<td>' . $mysql->prints($row['nname']) . '</td>';

                        echo '<td><a class="btn btn-primary" onclick=" hideme(' . $row['id'] . ');return false;" href="#">Hide</a></td>';

                        echo '</tr>';
                    }
                } else {

                    echo '<tr><td colspan="7" class="no_record">' . $admin->wordTrans($admin->getUserLang(), $lang->get('com_no_record_found')) . '</td></tr>';
                }
                ?>

            </table>

        </div></div></div>
<script type="text/javascript">
    function hideme(a)
    {
        offnotificatios(a);
        $('#' + a).slideUp();


    }
</script>