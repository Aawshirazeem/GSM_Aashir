<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//var_dump($_POST);
if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}
$admin->checkLogin();
$admin->reject();
$order_type = "";
$new_reason = "";

if ($_POST['order_id'] != "" && $_POST['cur_status'] != "" && $_POST['new_status'] != "" && $_POST['order_type'] != "") {
//  var_dump($_POST);
//
    $order_id = $_POST['order_id'];
    $cur_status = $_POST['cur_status'];
    $new_status = $_POST['new_status'];
    $order_type = $_POST['order_type'];
    $new_reason = $_POST['reason'];



    if ($cur_status != $new_status) {
        // here is the main game


        if ($cur_status == 0 and $new_status == -1) {
            // from new to inprocess
            $sql = 'update ' . ORDER_FILE_SERVICE_MASTER . ' set status=-1 where id in (' . $order_id . ') and status=0';
            $mysql->query($sql);

            header("location:" . CONFIG_PATH_SITE_ADMIN . "order_file.html?type=" . $order_type . "");
            exit();
        }

        if ($cur_status == -1 and $new_status == 0) {
            // from inprocess to new
            $sql = 'update ' . ORDER_FILE_SERVICE_MASTER . ' set status=0 where id in (' . $order_id . ') and status=-1';
            $mysql->query($sql);

            header("location:" . CONFIG_PATH_SITE_ADMIN . "order_file.html?type=" . $order_type . "");
            exit();
        }

        if ($cur_status == 0 and $new_status == 1) {
            // ==================================from new  to complete
            $unlock_code = $new_reason;

            if ($unlock_code != '') {
                // ----------------------------first new to inprocess
                $sql = 'update ' . ORDER_FILE_SERVICE_MASTER . ' set status=-1 where id in (' . $order_id . ') and status=0';
                $mysql->query($sql);

                // ----------------------------now from inprocess to complete
                $sql = 'update 
						' . ORDER_FILE_SERVICE_MASTER . ' im, ' . USER_MASTER . ' um
						set
						im.status=1, 
                                                im.unlock_code = ' . $mysql->quote($new_reason) . ',
						im.reply_date_time=now(),
						um.credits_inprocess = um.credits_inprocess - im.credits, um.credits_used = um.credits_used + im.credits
						where im.status=-1 and um.id = im.user_id and im.id=' . $mysql->getInt($order_id);

                $mysql->query($sql);
                $sql = 'select (c.amount-b.amount) as profit,d.reseller_id, (select credits from 
                                                                                            nxt_user_master where id=d.reseller_id) as credits 
                                                                         from ' . ORDER_FILE_SERVICE_MASTER . ' as a
                                                                         left join ' . FILE_SERVICE_AMOUNT_DETAILS . ' as b
                                                                         on a.file_service_id=b.service_id
                                                                        left join ' . FILE_SPL_CREDITS_RESELLER . ' as c
                                                                        on a.file_service_id=c.service_id
                                                                        left join ' . USER_MASTER . ' as d
                                                                        on a.user_id=d.id
                                                                        where a.id=' . $order_id . '
                                                                        and b.currency_id=d.currency_id';
                $query = $mysql->query($sql);
                $rows1 = $mysql->fetchArray($query);
                $profit = $rows1[0]["profit"];
                $reseller_id = $rows1[0]["reseller_id"];
                $credits_after = $rows1[0]["credits"];
                $credits_after = $credits_after + $profit;
                if ($reseller_id != 0) {
                    $sqlAvail .= 'update ' . USER_MASTER . ' um
									set um.credits =um.credits + ' . $profit . '
									where um.id = ' . $reseller_id . ';
                                                                            ';

                    $sqlAvail .= 'insert into ' . CREDIT_TRANSECTION_MASTER . '
									(user_id, date_time, credits,credits_after,order_id_imei,info, trans_type, ip)
								         values(
									              ' . $reseller_id . ',
											now(),
											' . $profit . ',
                                                                                        ' . $credits_after . ',
                                                                                        ' . $order_id . ',    
											' . $mysql->quote("Reseller Profit from File Order") . ',
											1,
											' . $mysql->quote($ip) . '
										);
                                                                                ';
                    $mysql->multi_query($sqlAvail);
                }

                // send mail tp user
                //   * ******************************************************** */
                $ip = $_SERVER['REMOTE_ADDR'];
                $objEmail = new email();
                $email_config = $objEmail->getEmailSettings();
                $from_admin = $email_config['system_email'];
                $admin_from_disp = $email_config['system_from'];
                $signatures = "<br /><br /><br /><br />" . nl2br($email_config['admin_signature']);


                $sqlm = 'select
							um.username, um.email,
							oim.id as oid, oim.user_id uid, oim.unlock_code,oim.f_name,
							 itm.service_name, oim.credits
						from ' . ORDER_FILE_SERVICE_MASTER . ' oim 
						left join ' . USER_MASTER . ' um on (oim.user_id = um.id)
						left join ' . FILE_SERVICE_MASTER . ' itm on (oim.file_service_id = itm.id)
					where oim.id=' . $order_id . ' and um.file_suc_noti=1
					';

                $query1 = $mysql->query($sqlm);

                if ($mysql->rowCount($query1) > 0) {
                    $rows1 = $mysql->fetchArray($query1);
                    // $argsAll = array();
                    foreach ($rows1 as $row1) {

                        $simple_email_body = '<p>Dear ' . $row1['username'] . '</p>
                                                                                <p>Your unlock code has successfully updated</p>
                                                                                <p>Orders details:</p>
                                                                                <p>============================</p>
                                                                                <p>Order ID : ' . $row1['oid'] . '</p>
                                                                                <p>Service Name : ' . $row1['service_name'] . '</p>
                                                                                <p>Unlock Code&nbsp;: ' . $row1['unlock_code'] . '</p>
                                                                                <p>Credit : ' . $row1['credits'] . '</p>
                                                                                <p>============================</p>';
                        $to_user = $row1['email'];
                        $user_id = $row1['uid'];
                        $objEmail->setTo($to_user);
                        $objEmail->setFrom($from_admin);
                        $objEmail->setFromDisplay($admin_from_disp);
                        $objEmail->setSubject("Your File Order Successfully Completed");
                        $objEmail->setBody($simple_email_body . $signatures);
                        $save = $objEmail->queue();
                    }
                }
            }
            header("location:" . CONFIG_PATH_SITE_ADMIN . "order_file.html?type=" . $order_type . "");
            exit();
        }

        if ($cur_status == -1 and $new_status == 1) {
            // ==================================from inprocess to  complete
            $unlock_code = $new_reason;

            if ($unlock_code != '') {

                // ----------------------------now from inprocess to complete

                $sql = 'update 
						' . ORDER_FILE_SERVICE_MASTER . ' im, ' . USER_MASTER . ' um
						set
						im.status=1, 
                                                im.unlock_code = ' . $mysql->quote($new_reason) . ',
						im.reply_date_time=now(),
						um.credits_inprocess = um.credits_inprocess - im.credits, um.credits_used = um.credits_used + im.credits
						where im.status=-1 and um.id = im.user_id and im.id=' . $mysql->getInt($order_id);

                $mysql->query($sql);
                $sql = 'select (c.amount-b.amount) as profit,d.reseller_id, (select credits from
                                                        nxt_user_master where id=d.reseller_id) as credits 
                                     from ' . ORDER_FILE_SERVICE_MASTER . ' as a
                                     left join ' . FILE_SERVICE_AMOUNT_DETAILS . ' as b
                                     on a.file_service_id=b.service_id
                                    left join ' . FILE_SPL_CREDITS_RESELLER . ' as c
                                    on a.file_service_id=c.service_id
                                    left join ' . USER_MASTER . ' as d
                                    on a.user_id=d.id
                                    where a.id=' . $order_id . '
                                    and b.currency_id=d.currency_id';
                $query = $mysql->query($sql);
                $rows1 = $mysql->fetchArray($query);
                $profit = $rows1[0]["profit"];
                $reseller_id = $rows1[0]["reseller_id"];
                $credits_after = $rows1[0]["credits"];
                $credits_after = $credits_after + $profit;
                if ($reseller_id != 0) {
                    $sqlAvail .= 'update ' . USER_MASTER . ' um
									set um.credits =um.credits + ' . $profit . '
									where um.id = ' . $reseller_id . ';
                                                                            ';

                    $sqlAvail .= 'insert into ' . CREDIT_TRANSECTION_MASTER . '
									(user_id, date_time, credits,credits_after,order_id_imei,info, trans_type, ip)
								         values(
									              ' . $reseller_id . ',
											now(),
											' . $profit . ',
                                                                                        ' . $credits_after . ',
                                                                                        ' . $order_id . ',    
											' . $mysql->quote("Reseller Profit from File Order") . ',
											1,
											' . $mysql->quote($ip) . '
										);
                                                                                ';
                    $mysql->multi_query($sqlAvail);
                }

                // send mail tp user
                //   * ******************************************************** */
                $ip = $_SERVER['REMOTE_ADDR'];
                $objEmail = new email();
                $email_config = $objEmail->getEmailSettings();
                $from_admin = $email_config['system_email'];
                $admin_from_disp = $email_config['system_from'];
                $signatures = "<br /><br /><br /><br />" . nl2br($email_config['admin_signature']);


                $sqlm = 'select
							um.username, um.email,
							oim.id as oid, oim.user_id uid, oim.unlock_code,oim.f_name,
							 itm.service_name, oim.credits
						from ' . ORDER_FILE_SERVICE_MASTER . ' oim 
						left join ' . USER_MASTER . ' um on (oim.user_id = um.id)
						left join ' . FILE_SERVICE_MASTER . ' itm on (oim.file_service_id = itm.id)
					where oim.id=' . $order_id . ' and um.file_suc_noti=1
					';

                $query1 = $mysql->query($sqlm);

                if ($mysql->rowCount($query1) > 0) {
                    $rows1 = $mysql->fetchArray($query1);
                    // $argsAll = array();
                    foreach ($rows1 as $row1) {

                        $simple_email_body = '<p>Dear ' . $row1['username'] . '</p>
                                                                                <p>Your unlock code has successfully updated</p>
                                                                                <p>Orders details:</p>
                                                                                <p>============================</p>
                                                                                <p>Order ID : ' . $row1['oid'] . '</p>
                                                                                <p>Service Name : ' . $row1['service_name'] . '</p>
                                                                                <p>Unlock Code&nbsp;: ' . $row1['unlock_code'] . '</p>
                                                                                <p>Credit : ' . $row1['credits'] . '</p>
                                                                                <p>============================</p>';
                        $to_user = $row1['email'];
                        $user_id = $row1['uid'];
                        $objEmail->setTo($to_user);
                        $objEmail->setFrom($from_admin);
                        $objEmail->setFromDisplay($admin_from_disp);
                        $objEmail->setSubject("Your File Order Successfully Completed");
                        $objEmail->setBody($simple_email_body . $signatures);
                        $save = $objEmail->queue();
                    }
                }
            }
            header("location:" . CONFIG_PATH_SITE_ADMIN . "order_file.html?type=" . $order_type . "");
            exit();
        }

        if ($cur_status == 1 and $new_status == -1) {
            // ==================================from complete to inprocess======================

            $sql = '
					update 
						' . ORDER_FILE_SERVICE_MASTER . ' im, ' . USER_MASTER . ' um
						set
							im.status=-1, 
                                                        im.unlock_code="",
                                                        im.reply_date_time="0000-00-00 00:00:00",
							um.credits_inprocess = um.credits_inprocess + im.credits,
											um.credits_used = um.credits_used - im.credits
						where im.status=1 and um.id=im.user_id and im.id =' . $order_id;
            $mysql->query($sql);
            header("location:" . CONFIG_PATH_SITE_ADMIN . "order_file.html?type=" . $order_type . "");
            exit();
        }

        if ($cur_status == 1 and $new_status == 0) {
            // ==================================from complete to new======================
            // ----------first comptete to inprocess
            $sql = '
					update 
						' . ORDER_FILE_SERVICE_MASTER . ' im, ' . USER_MASTER . ' um
						set
							im.status=-1, 
                                                        im.unlock_code="",
                                                        im.reply_date_time="0000-00-00 00:00:00",
							um.credits_inprocess = um.credits_inprocess + im.credits,
											um.credits_used = um.credits_used - im.credits
						where im.status=1 and um.id=im.user_id and im.id =' . $order_id;
            $mysql->query($sql);

            // ----------first inprocess to new
            $sql = 'update ' . ORDER_FILE_SERVICE_MASTER . ' set status=0 where id in (' . $order_id . ') and status=-1';
            $mysql->query($sql);

            header("location:" . CONFIG_PATH_SITE_ADMIN . "order_file.html?type=" . $order_type . "");
            exit();
        }

        if ($cur_status == -1 and $new_status == 2) {
            // ==================================from inprocess to reject======================

            $sql = 'select * from ' . ORDER_FILE_SERVICE_MASTER . ' oim where id=' . $mysql->getInt($order_id);
            $query = $mysql->query($sql);
            $rows = $mysql->fetchArray($query);
            $objCredits->returnFile($order_id, $rows[0]['user_id'], $rows[0]['credits']);

            $sql = 'update 
							' . ORDER_FILE_SERVICE_MASTER . ' im, ' . USER_MASTER . ' um
							set
							im.status=2, 
                                                        im.reply_by=' . $admin->getUserId() . ', 
							im.reply_date_time=now(),
							im.reply=' . $mysql->quote($new_reason) . ',
							um.credits = um.credits + im.credits, um.credits_inprocess = um.credits_inprocess - im.credits
							where im.status=-1 and um.id = im.user_id and im.id=' . $mysql->getInt($order_id);
            $mysql->query($sql);


            // send mail tp user
            //   * ******************************************************** */
            $ip = $_SERVER['REMOTE_ADDR'];
            $objEmail = new email();
            $email_config = $objEmail->getEmailSettings();
            $from_admin = $email_config['system_email'];
            $admin_from_disp = $email_config['system_from'];
            $signatures = "<br /><br /><br /><br />" . nl2br($email_config['admin_signature']);


            $sqlm = 'select
							um.username, um.email,oim.reply,
							oim.id as oid, oim.user_id uid, oim.unlock_code,oim.f_name,
							 itm.service_name, oim.credits
						from ' . ORDER_FILE_SERVICE_MASTER . ' oim 
						left join ' . USER_MASTER . ' um on (oim.user_id = um.id)
						left join ' . FILE_SERVICE_MASTER . ' itm on (oim.file_service_id = itm.id)
					where oim.id=' . $order_id . ' and um.file_rej_noti=1
					';

            $query1 = $mysql->query($sqlm);

            if ($mysql->rowCount($query1) > 0) {
                $rows1 = $mysql->fetchArray($query1);
                // $argsAll = array();
                foreach ($rows1 as $row1) {

                    $simple_email_body = '<p>Dear ' . $row1['username'] . '</p>
                                                                                <p>Your File service order has been cancelled!</p>
                                                                                <p>Orders details:</p>
                                                                                <p>============================</p>
                                                                                <p>Order ID : ' . $row1['oid'] . '</p>
                                                                                <p>Service Name : ' . $row1['service_name'] . '</p>
                                                                                <p>Reason: ' . $row1['reply'] . '</p>
                                                                                <p>Credit : ' . $row1['credits'] . '</p>
                                                                                <p>============================</p>';
                    $to_user = $row1['email'];
                    $user_id = $row1['uid'];
                    $objEmail->setTo($to_user);
                    $objEmail->setFrom($from_admin);
                    $objEmail->setFromDisplay($admin_from_disp);
                    $objEmail->setSubject("Your File Service Order is Rejected");
                    $objEmail->setBody($simple_email_body . $signatures);
                    $save = $objEmail->queue();
                }
            }

            header("location:" . CONFIG_PATH_SITE_ADMIN . "order_file.html?type=" . $order_type . "");
            exit();
        }




        if ($cur_status == 0 and $new_status == 2) {
            // ==================================from new to reject======================
            // first new to inprocess

            $sql = 'update ' . ORDER_FILE_SERVICE_MASTER . ' set status=-1 where id in (' . $order_id . ') and status=0';
            $mysql->query($sql);


            // then inprocess to reject
            $sql = 'select * from ' . ORDER_FILE_SERVICE_MASTER . ' oim where id=' . $mysql->getInt($order_id);
            $query = $mysql->query($sql);
            $rows = $mysql->fetchArray($query);
            $objCredits->returnFile($order_id, $rows[0]['user_id'], $rows[0]['credits']);

            $sql = 'update 
							' . ORDER_FILE_SERVICE_MASTER . ' im, ' . USER_MASTER . ' um
							set
							im.status=2, 
                                                        im.reply_by=' . $admin->getUserId() . ', 
							im.reply_date_time=now(),
							im.reply=' . $mysql->quote($new_reason) . ',
							um.credits = um.credits + im.credits, um.credits_inprocess = um.credits_inprocess - im.credits
							where im.status=-1 and um.id = im.user_id and im.id=' . $mysql->getInt($order_id);
            $mysql->query($sql);


            // send mail tp user
            //   * ******************************************************** */
            $ip = $_SERVER['REMOTE_ADDR'];
            $objEmail = new email();
            $email_config = $objEmail->getEmailSettings();
            $from_admin = $email_config['system_email'];
            $admin_from_disp = $email_config['system_from'];
            $signatures = "<br /><br /><br /><br />" . nl2br($email_config['admin_signature']);


            $sqlm = 'select
							um.username, um.email,oim.reply,
							oim.id as oid, oim.user_id uid, oim.unlock_code,oim.f_name,
							 itm.service_name, oim.credits
						from ' . ORDER_FILE_SERVICE_MASTER . ' oim 
						left join ' . USER_MASTER . ' um on (oim.user_id = um.id)
						left join ' . FILE_SERVICE_MASTER . ' itm on (oim.file_service_id = itm.id)
					where oim.id=' . $order_id . ' and um.file_rej_noti=1
					';

            $query1 = $mysql->query($sqlm);

            if ($mysql->rowCount($query1) > 0) {
                $rows1 = $mysql->fetchArray($query1);
                // $argsAll = array();
                foreach ($rows1 as $row1) {

                    $simple_email_body = '<p>Dear ' . $row1['username'] . '</p>
                                                                                <p>Your File service order has been cancelled!</p>
                                                                                <p>Orders details:</p>
                                                                                <p>============================</p>
                                                                                <p>Order ID : ' . $row1['oid'] . '</p>
                                                                                <p>Service Name : ' . $row1['service_name'] . '</p>
                                                                                <p>Reason: ' . $row1['reply'] . '</p>
                                                                                <p>Credit : ' . $row1['credits'] . '</p>
                                                                                <p>============================</p>';
                    $to_user = $row1['email'];
                    $user_id = $row1['uid'];
                    $objEmail->setTo($to_user);
                    $objEmail->setFrom($from_admin);
                    $objEmail->setFromDisplay($admin_from_disp);
                    $objEmail->setSubject("Your File Service Order is Rejected");
                    $objEmail->setBody($simple_email_body . $signatures);
                    $save = $objEmail->queue();
                }
            }

            header("location:" . CONFIG_PATH_SITE_ADMIN . "order_file.html?type=" . $order_type . "");
            exit();
        }

        if ($cur_status == 1 and $new_status == 2) {
            // ==================================from complete to reject======================
            //----first the complete to inprocess-----------

            $sql = '
					update 
						' . ORDER_FILE_SERVICE_MASTER . ' im, ' . USER_MASTER . ' um
						set
							im.status=-1, 
                                                        im.unlock_code="",
                                                        im.reply_date_time="0000-00-00 00:00:00",
							um.credits_inprocess = um.credits_inprocess + im.credits,
											um.credits_used = um.credits_used - im.credits
						where im.status=1 and um.id=im.user_id and im.id =' . $order_id;
            $mysql->query($sql);

            // ---then inproces to reject


            $sql = 'select * from ' . ORDER_FILE_SERVICE_MASTER . ' oim where id=' . $mysql->getInt($order_id);
            $query = $mysql->query($sql);
            $rows = $mysql->fetchArray($query);
            $objCredits->returnFile($order_id, $rows[0]['user_id'], $rows[0]['credits']);

            $sql = 'update 
							' . ORDER_FILE_SERVICE_MASTER . ' im, ' . USER_MASTER . ' um
							set
							im.status=2, 
                                                        im.reply_by=' . $admin->getUserId() . ', 
							im.reply_date_time=now(),
							im.reply=' . $mysql->quote($new_reason) . ',
							um.credits = um.credits + im.credits, um.credits_inprocess = um.credits_inprocess - im.credits
							where im.status=-1 and um.id = im.user_id and im.id=' . $mysql->getInt($order_id);
            $mysql->query($sql);


            // send mail tp user
            //   * ******************************************************** */
            $ip = $_SERVER['REMOTE_ADDR'];
            $objEmail = new email();
            $email_config = $objEmail->getEmailSettings();
            $from_admin = $email_config['system_email'];
            $admin_from_disp = $email_config['system_from'];
            $signatures = "<br /><br /><br /><br />" . nl2br($email_config['admin_signature']);


            $sqlm = 'select
							um.username, um.email,oim.reply,
							oim.id as oid, oim.user_id uid, oim.unlock_code,oim.f_name,
							 itm.service_name, oim.credits
						from ' . ORDER_FILE_SERVICE_MASTER . ' oim 
						left join ' . USER_MASTER . ' um on (oim.user_id = um.id)
						left join ' . FILE_SERVICE_MASTER . ' itm on (oim.file_service_id = itm.id)
					where oim.id=' . $order_id . ' and um.file_rej_noti=1
					';

            $query1 = $mysql->query($sqlm);

            if ($mysql->rowCount($query1) > 0) {
                $rows1 = $mysql->fetchArray($query1);
                // $argsAll = array();
                foreach ($rows1 as $row1) {

                    $simple_email_body = '<p>Dear ' . $row1['username'] . '</p>
                                                                                <p>Your File service order has been cancelled!</p>
                                                                                <p>Orders details:</p>
                                                                                <p>============================</p>
                                                                                <p>Order ID : ' . $row1['oid'] . '</p>
                                                                                <p>Service Name : ' . $row1['service_name'] . '</p>
                                                                                <p>Reason: ' . $row1['reply'] . '</p>
                                                                                <p>Credit : ' . $row1['credits'] . '</p>
                                                                                <p>============================</p>';
                    $to_user = $row1['email'];
                    $user_id = $row1['uid'];
                    $objEmail->setTo($to_user);
                    $objEmail->setFrom($from_admin);
                    $objEmail->setFromDisplay($admin_from_disp);
                    $objEmail->setSubject("Your File Service Order is Rejected");
                    $objEmail->setBody($simple_email_body . $signatures);
                    $save = $objEmail->queue();
                }
            }

            header("location:" . CONFIG_PATH_SITE_ADMIN . "order_file.html?type=" . $order_type . "");
            exit();
        }
        if ($cur_status == 2 && $new_status == -1) {
            // from reject to inprocess


            $sql = '
					update 
						' . ORDER_FILE_SERVICE_MASTER . ' im, ' . USER_MASTER . ' um
						set
							im.status=-1, 
                                                         im.reply="",
                                                        im.reply_date_time="0000-00-00 00:00:00",
							um.credits = um.credits - im.credits,
							um.credits_inprocess = um.credits_inprocess + im.credits
						where im.status=2 and um.id=im.user_id and im.id =' . $order_id . ';
						
					
					insert into ' . CREDIT_TRANSECTION_MASTER . '
						(user_id, date_time, credits, credits_acc, credits_acc_process, credits_acc_used,
						credits_after, credits_after_process, credits_after_used, order_id_imei, info, trans_type)
						
						select 
								oim.user_id,
								now(),
								oim.credits,
								um.credits - oim.credits,
								um.credits_inprocess + oim.credits,
								um.credits_used,
								um.credits,
								um.credits_inprocess,
								um.credits_used,
								oim.id,
								' . $mysql->quote("FILE ORDER RELOCKED") . ',
								2
							from ' . ORDER_FILE_SERVICE_MASTER . ' oim 
							left join ' . USER_MASTER . ' um on (oim.user_id = um.id)
							left join ' . FILE_SERVICE_MASTER . ' itm on (oim.file_service_id = itm.id)
						where oim.id =' . $order_id;
            $mysql->multi_query($sql);
            header("location:" . CONFIG_PATH_SITE_ADMIN . "order_file.html?type=" . $order_type . "");
            exit();
        }



        if ($cur_status == 2 && $new_status == 0) {
            // ========================from reject to new
// --------------------------first from reject to inprocess

            $sql = '
					update 
						' . ORDER_FILE_SERVICE_MASTER . ' im, ' . USER_MASTER . ' um
						set
							im.status=-1, 
                                                         im.reply="",
                                                        im.reply_date_time="0000-00-00 00:00:00",
							um.credits = um.credits - im.credits,
							um.credits_inprocess = um.credits_inprocess + im.credits
						where im.status=2 and um.id=im.user_id and im.id =' . $order_id . ';
						
					
					insert into ' . CREDIT_TRANSECTION_MASTER . '
						(user_id, date_time, credits, credits_acc, credits_acc_process, credits_acc_used,
						credits_after, credits_after_process, credits_after_used, order_id_imei, info, trans_type)
						
						select 
								oim.user_id,
								now(),
								oim.credits,
								um.credits - oim.credits,
								um.credits_inprocess + oim.credits,
								um.credits_used,
								um.credits,
								um.credits_inprocess,
								um.credits_used,
								oim.id,
								' . $mysql->quote("FILE ORDER RELOCKED") . ',
								2
							from ' . ORDER_FILE_SERVICE_MASTER . ' oim 
							left join ' . USER_MASTER . ' um on (oim.user_id = um.id)
							left join ' . FILE_SERVICE_MASTER . ' itm on (oim.file_service_id = itm.id)
						where oim.id =' . $order_id;
            $mysql->multi_query($sql);
            // then from inprocess to new
            $sql = 'update ' . ORDER_FILE_SERVICE_MASTER . ' set status=0 where id in (' . $order_id . ') and status=-1';
            $mysql->query($sql);

            header("location:" . CONFIG_PATH_SITE_ADMIN . "order_file.html?type=" . $order_type . "");
            exit();
        }




        if ($cur_status == 2 && $new_status == 1) {
            //================================ from reject to complete
            $unlock_code = $new_reason;

            if ($unlock_code != '') {
                //------------------------- first reject to inprocess

                $sql = '
					update 
						' . ORDER_FILE_SERVICE_MASTER . ' im, ' . USER_MASTER . ' um
						set
							im.status=-1, 
                                                         im.reply="",
                                                        im.reply_date_time="0000-00-00 00:00:00",
							um.credits = um.credits - im.credits,
							um.credits_inprocess = um.credits_inprocess + im.credits
						where im.status=2 and um.id=im.user_id and im.id =' . $order_id . ';
						
					
					insert into ' . CREDIT_TRANSECTION_MASTER . '
						(user_id, date_time, credits, credits_acc, credits_acc_process, credits_acc_used,
						credits_after, credits_after_process, credits_after_used, order_id_imei, info, trans_type)
						
						select 
								oim.user_id,
								now(),
								oim.credits,
								um.credits - oim.credits,
								um.credits_inprocess + oim.credits,
								um.credits_used,
								um.credits,
								um.credits_inprocess,
								um.credits_used,
								oim.id,
								' . $mysql->quote("FILE ORDER RELOCKED") . ',
								2
							from ' . ORDER_FILE_SERVICE_MASTER . ' oim 
							left join ' . USER_MASTER . ' um on (oim.user_id = um.id)
							left join ' . FILE_SERVICE_MASTER . ' itm on (oim.file_service_id = itm.id)
						where oim.id =' . $order_id;
                $mysql->multi_query($sql);



                // ----------------------------now from inprocess to complete

                $sql = 'update 
						' . ORDER_FILE_SERVICE_MASTER . ' im, ' . USER_MASTER . ' um
						set
						im.status=1, 
                                                im.unlock_code = ' . $mysql->quote($new_reason) . ',
						im.reply_date_time=now(),
						um.credits_inprocess = um.credits_inprocess - im.credits, um.credits_used = um.credits_used + im.credits
						where im.status=-1 and um.id = im.user_id and im.id=' . $mysql->getInt($order_id);

                $mysql->query($sql);
                $sql = 'select (c.amount-b.amount) as profit,d.reseller_id, (select credits from
                                                        nxt_user_master where id=d.reseller_id) as credits 
                                     from ' . ORDER_FILE_SERVICE_MASTER . ' as a
                                     left join ' . FILE_SERVICE_AMOUNT_DETAILS . ' as b
                                     on a.file_service_id=b.service_id
                                    left join ' . FILE_SPL_CREDITS_RESELLER . ' as c
                                    on a.file_service_id=c.service_id
                                    left join ' . USER_MASTER . ' as d
                                    on a.user_id=d.id
                                    where a.id=' . $order_id . '
                                    and b.currency_id=d.currency_id';
                $query = $mysql->query($sql);
                $rows1 = $mysql->fetchArray($query);
                $profit = $rows1[0]["profit"];
                $reseller_id = $rows1[0]["reseller_id"];
                $credits_after = $rows1[0]["credits"];
                $credits_after = $credits_after + $profit;
                if ($reseller_id != 0) {
                    $sqlAvail .= 'update ' . USER_MASTER . ' um
									set um.credits =um.credits + ' . $profit . '
									where um.id = ' . $reseller_id . ';
                                                                            ';

                    $sqlAvail .= 'insert into ' . CREDIT_TRANSECTION_MASTER . '
									(user_id, date_time, credits,credits_after,order_id_imei,info, trans_type, ip)
								         values(
									              ' . $reseller_id . ',
											now(),
											' . $profit . ',
                                                                                        ' . $credits_after . ',
                                                                                        ' . $order_id . ',    
											' . $mysql->quote("Reseller Profit from File Order") . ',
											1,
											' . $mysql->quote($ip) . '
										);
                                                                                ';
                    $mysql->multi_query($sqlAvail);
                }

                // send mail tp user
                //   * ******************************************************** */
                $ip = $_SERVER['REMOTE_ADDR'];
                $objEmail = new email();
                $email_config = $objEmail->getEmailSettings();
                $from_admin = $email_config['system_email'];
                $admin_from_disp = $email_config['system_from'];
                $signatures = "<br /><br /><br /><br />" . nl2br($email_config['admin_signature']);


                $sqlm = 'select
							um.username, um.email,
							oim.id as oid, oim.user_id uid, oim.unlock_code,oim.f_name,
							 itm.service_name, oim.credits
						from ' . ORDER_FILE_SERVICE_MASTER . ' oim 
						left join ' . USER_MASTER . ' um on (oim.user_id = um.id)
						left join ' . FILE_SERVICE_MASTER . ' itm on (oim.file_service_id = itm.id)
					where oim.id=' . $order_id . ' and um.file_suc_noti=1
					';

                $query1 = $mysql->query($sqlm);

                if ($mysql->rowCount($query1) > 0) {
                    $rows1 = $mysql->fetchArray($query1);
                    // $argsAll = array();
                    foreach ($rows1 as $row1) {

                        $simple_email_body = '<p>Dear ' . $row1['username'] . '</p>
                                                                                <p>Your unlock code has successfully updated</p>
                                                                                <p>Orders details:</p>
                                                                                <p>============================</p>
                                                                                <p>Order ID : ' . $row1['oid'] . '</p>
                                                                                <p>Service Name : ' . $row1['service_name'] . '</p>
                                                                                <p>Unlock Code&nbsp;: ' . $row1['unlock_code'] . '</p>
                                                                                <p>Credit : ' . $row1['credits'] . '</p>
                                                                                <p>============================</p>';
                        $to_user = $row1['email'];
                        $user_id = $row1['uid'];
                        $objEmail->setTo($to_user);
                        $objEmail->setFrom($from_admin);
                        $objEmail->setFromDisplay($admin_from_disp);
                        $objEmail->setSubject("Your File Order Successfully Completed");
                        $objEmail->setBody($simple_email_body . $signatures);
                        $save = $objEmail->queue();
                    }
                }
            }
            header("location:" . CONFIG_PATH_SITE_ADMIN . "order_file.html?type=" . $order_type . "");
            exit();
        }
    } else {
// no change

        header("location:" . CONFIG_PATH_SITE_ADMIN . "order_q_edit_2.html?status=" . $cur_status . "&id=" . $order_id . "&type=" . $order_type . "&reply=" . urlencode('No_Change'));
        exit();
    }
} else {
// echo '';
    header("location:" . CONFIG_PATH_SITE_ADMIN . "order_file.html?type=pending&reply=" . urlencode('Something_is_missing'));
    exit();
}    