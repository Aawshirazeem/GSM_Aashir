<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

defined("_VALID_ACCESS") or die("Restricted Access");
$type = $request->getStr('type');
$order_id = $request->GetInt('id');
$cur_status = $request->GetInt('status');

if($cur_status==2){
// if order is done get the order reply
$sql = 'select a.reply from nxt_order_imei_master a
where a.id=' . $order_id;
$query = $mysql->query($sql);
$rows1 = $mysql->fetchArray($query);
$tempreply = $rows1[0]["reply"];
$tempreply=  base64_decode($tempreply);
}
//echo $type . $order_id;
?>

<div class="row m-b-20">

    <div class="col-xs-12">

        <ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_dashboard')); ?></a></li>

            <li class="slideInDown wow animated"><a href="order_imei.html?type=<?php echo $type ?>"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_imei_orders')); ?></a></li>           

            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_imei_order_qucik_edit')); ?></li>

        </ol>

    </div>

</div>
<div class="col-md-12">
    <div class="">
        <h4 class="panel-heading"><b><?php echo $admin->wordTrans($admin->getUserLang(), 'Order Quick Edit'); ?></b>            

        </h4>
        <div class="panel-body">
            <form  action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_q_edit_process.html" method="post" class="">

                <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                <input type="hidden" name="cur_status" value="<?php echo $cur_status; ?>">
                <input type="hidden" name="order_type" value="<?php echo $type; ?>">
                <div class="col-md-6">

                    <div class="form-group">


<!--                        <select name="tool" class="form-control chosen-select" id="tool" placeholder="Select">-->
                        <select name="new_status" id="new_status" class="form-control" required="">
                            <option value=""><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_select_new_status')); ?></option>
                            <option value="0"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_New')); ?></option>

                            <option value="1"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Inprocess')); ?></option>

                            <option value="2"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Complete')); ?></option>

                            <option value="3"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Reject')); ?></option>

                        </select>

                    </div>
                    <div class="form-group">

                        <label>Enter The Success Or Reject Reason</label>
                        <input type="text" name="reason" class="form-control" value="<?php echo trim($tempreply);?>">

                    </div>
                    <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_submit')); ?>" class="btn btn-success"/>

                </div>
            </form>
        </div>
    </div>
</div>