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

$id = $request->getInt('id');


if ($id != "") {

// send to all user
    $sql = 'select um.id,um.username,um.email,um.currency_id from ' . USER_MASTER . ' um';

    $query = $mysql->query($sql);

    $rowCount = $mysql->rowCount($query);
    if ($rowCount > 0) {
        $rows = $mysql->fetchArray($query);

        foreach ($rows as $row) {
            // get the price for one by one
            $cur_id = $row['currency_id'];
            $user_id = $row['id'];
            $user_name = $row['username'];
            $user_email = $row['email'];

            $sql2 = 'select tm.id as tid,igm.group_name,tm.tool_name, itad.amount, isc.amount splCr, iscr.amount splCre, 
	pim.amount as packageCr from nxt_imei_tool_master tm 
	left join nxt_imei_group_master igm on(tm.group_id = igm.id) 

	left join nxt_currency_master cm on(cm.id = ' . $cur_id . ') 
	left join nxt_imei_tool_amount_details itad on(itad.tool_id=tm.id and itad.currency_id =' . $cur_id . ') 
	left join nxt_imei_spl_credits isc on(isc.user_id = ' . $user_id . ' and isc.tool_id=tm.id) 
	left join nxt_imei_spl_credits_reseller iscr on(iscr.user_id = ' . $user_id . ' and iscr.tool_id=tm.id) 
	left join nxt_package_users pu on(pu.user_id = ' . $user_id . ') 
	left join nxt_package_imei_details pim 
	on(pim.package_id = pu.package_id and pim.currency_id = ' . $cur_id . ' and pim.tool_id = tm.id) 

where tm.id=' . $mysql->getInt($id);

            $query2 = $mysql->query($sql2);

            $rowCount1 = $mysql->rowCount($query2);
            if ($rowCount1 > 0) {
                $rows2 = $mysql->fetchArray($query2);
                $pricerow = $rows2[0];

                $amount = $mysql->getFloat($pricerow['amount']);
                $amountSpl = $pricerow['splCr'];
                $amountPackage = $pricerow['packageCr'];
                $amountDiscount = 0;
                $isSpl = false;
                if ($pricerow["splCre"] == "") {
                    if ($amountPackage != '') {
                        $isSpl = true;
                        $amountPackage = $mysql->getFloat($amountPackage);
                        $amountDiscount = $amount - $amountPackage;
                        $amount = $amountPackage;
                    }
                    if ($amountSpl != '') {
                        $isSpl = true;
                        $amountSpl = $mysql->getFloat($amountSpl);
                        $amountDiscount = $amount - $amountSpl;
                        $amount = $amountSpl;
                    }
                } else {
                    $isSpl = false;
                    $amount = $mysql->getFloat($pricerow["splCre"]);
                }

                $newamount = $amount;
                $grpname = $pricerow["group_name"];
                $tool_name2 = $pricerow["tool_name"];

                // send email to that user now
                $email_config = $objEmail->getEmailSettings();
                $from_admin = $email_config['system_email'];
                $admin_from_disp = $email_config['system_from'];

                $args = array(
                    'to' => $user_email,
                    'from' => $from_admin,
                    'fromDisplay' => $admin_from_disp,
                    'save' => '1',
                    'username' => $user_name,
                    'group_name' => $grpname,
                    'tool_name' => $tool_name2,
                    'credits' => $newamount,
                    'site_admin' => $admin_from_disp,
                    'send_mail' => true
                );
                $objEmail->sendEmailTemplate('imei_service_price_update', $args);
            }
        }
    }


    header("location:" . CONFIG_PATH_SITE_ADMIN . "services_imei_tools.html?reply=" . urlencode("reply_email_sent"));
    exit();
}