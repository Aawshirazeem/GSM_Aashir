<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}
$admin->checkLogin();
$admin->reject();
//$id = $request->PostInt('id');
//$check = $request->PostInt('check');
$id = $_POST['id'];
$check = $_POST['check'];
if ($id != "" && $check != "") {

    if ($check == 1) {

        $sql = 'select ap.api_id,ap.api_service_id,ap.api_name,ap.s_priority,b_price_adj,om.b_rate_main from nxt_api_priority ap

inner join nxt_order_imei_master om

on om.api_rej_2_prio=ap.s_priority and om.tool_id=ap.s_id

where om.id=' . $id;

        $query = $mysql->query($sql);

        $rowCount = $mysql->rowCount($query);

        if ($rowCount != 0) {
            $rows = $mysql->fetchArray($query);
            $row_api_pri = $rows[0];

            $new_api_id = $row_api_pri['api_id'];
            $new_api_name = $row_api_pri['api_name'];
            $new_api_service_id = $row_api_pri['api_service_id'];
            $temp_priority = $row_api_pri['s_priority'];
            $temp_priority = $temp_priority + 1;


            //buy price adj
            $order_main_b_price = $row_api_pri['b_rate_main'];
            $api_price_adj = $row_api_pri['b_price_adj'];

            $api_price_adj = trim($api_price_adj);

            if ($api_price_adj != "") {


                $action_on_price = substr($api_price_adj, 0, 1);

                if ($action_on_price == "+" || $action_on_price == "-") {

                    $price_percent = substr($api_price_adj, 1);

                    $calculate1 = $order_main_b_price / 100;
                    $calculate2 = $calculate1 * $price_percent;
                    if ($action_on_price == "+")
                        $new_b_price = $order_main_b_price + $calculate2;
                    else if ($action_on_price == "-")
                        $new_b_price = $order_main_b_price - $calculate2;
                    else
                        $new_b_price = $order_main_b_price + $calculate2;
                } else
                    $new_b_price = $order_main_b_price;
            } else
                $new_b_price = $order_main_b_price;
            //update order api creds
            $nothing = "";

            $sql = 'update 
								' . ORDER_IMEI_MASTER . ' 
								set
                                                                status=0,
                                                                main_api=0,
                                                                api_rej_2=0,
                                                                b_rate=    ' . $mysql->getFloat($new_b_price) . ',
                                                                extern_id=' . $mysql->quote($nothing) . ',
								api_id=' . $new_api_id . ',
                                                                api_name=' . $mysql->quote($new_api_name) . ',
                                                                api_service_id=' . $new_api_service_id . ',
                                                                api_rej_2_prio=' . $temp_priority . '
							where status=1 and id=' . $id;
            $mysql->query($sql);
            echo 'done';
            exit;
        }
    }
    else if ($check == 2) {
        // reject order
        $temp_order_id = $id;
        $sql1 = 'select tm.tool_name, tm.api_rej,tm.api_rej_man_auto,oim.user_id,oim.message,  um.username,um.email,oim.credits,oim.imei,um.imei_suc_noti,um.imei_rej_noti
					from ' . ORDER_IMEI_MASTER . ' oim
					left join ' . USER_MASTER . ' as um
					on oim.user_id=um.id
				        left join ' . IMEI_TOOL_MASTER . ' as tm
					on oim.tool_id=tm.id
					where 
					oim.id=' . $mysql->getInt($temp_order_id);
        //echo $sql1;
        $query1 = $mysql->query($sql1);
        $rows1 = $mysql->fetchArray($query1);
        // data get

        $tool_name = $rows1[0]['tool_name'];

        $imei_rej_noti = $rows1[0]['imei_rej_noti'];
        $user_name = $rows1[0]['username'];
        $u_email = $rows1[0]['email'];
        $price = $rows1[0]['credits'];
        $o_imei = $rows1[0]['imei'];
        $code = base64_decode($rows1[0]['message']);


        $objCredits = new credits();
        $objCredits->returnIMEI($mysql->getInt($temp_order_id), $rows1[0]['user_id'], $rows1[0]['credits']);
        $sql = 'update 
								' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
								set
								im.status=3,
								reply_by=3,
								im.reply_date_time=now(),
								um.credits = um.credits + im.credits, um.credits_inprocess = um.credits_inprocess - im.credits
							where im.status=1 and um.id = im.user_id and im.id=' . $mysql->getInt($temp_order_id);
        $mysql->query($sql);

        //send maill code if unlock code unavailable

        $objEmail = new email();
        $email_config = $objEmail->getEmailSettings();
        $from_admin = $email_config['system_email'];
        $admin_from_disp = $email_config['system_from'];
        $signatures = "<br /><br /><br /><br />" . nl2br($email_config['admin_signature']);

        $simple_email_body = '';
        //  $simple_email_body .= '<h2>Your Unlock Code is Cancelled</h2>';
        $simple_email_body .= '
         <p><b>Service Name:</b>' . $tool_name . '<p>
         <p><b>IMEI:</b>' . $o_imei . '<p>
         <p><b>Reson:</b>' . $code . '<p>
         <p><b>Credits:</b>' . $price . '<p>
         <p><b>Order ID :</b>' . $temp_order_id . '<p>
         <p>
         <p>Complete Credits of This Order are Refunded & Added Back in Your User!
         ';

        $objEmail->setTo($u_email);
        $objEmail->setFrom($from_admin);
        $objEmail->setFromDisplay($admin_from_disp);
        $objEmail->setSubject("Unlock Code Not Found");
        $objEmail->setBody($simple_email_body);
        // $sent = $objEmail->sendMail();
        if ($imei_rej_noti == 1)
            $objEmail->queue();

        echo 'rdone';
        exit;
    } else {
        exit;
    }
}
exit;
?>