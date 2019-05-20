<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}
//echo '<pre/>';
//var_dump($_POST);
//exit;

$id = $request->PostInt('id');
$firstC = $request->GetStr('firstC');
$limit = $request->GetInt('limit');
$offset = $request->GetInt('offset');
$username = $request->GetStr('username');
$inid = $request->PostStr('invoice_request_id');
$irid = $request->PostStr('irid');
$uid = $request->PostStr('uid');
$credits = $request->PostStr('credits');
$gateway_id = $request->PostInt('gateway_id');
$currency_id = $request->PostInt('currency_id');

$returnURL = (($irid != 0) ? 'users_edit.html' : 'users_edit.html');

if (defined("DEMO")) {
    header("location:" . CONFIG_PATH_SITE_ADMIN . $returnURL . "?id=" . $id . "&reply=" . urlencode('reply_com_demo'));
    exit();
}


$admin->checkLogin();
$admin->reject();
$validator->formValidateAdmin('user_credits_52134757d2');
$objCredits = new credits();

$email = $request->PostStr('email');
$username = $request->PostStr('username');

$creditType = $request->PostInt('creditType');
// echo $creditType;
$crAdd = number_format($request->PostFloat('crAdd'), 2,".","");
//echo '<br> add'.$crAdd;
$crRevoke = number_format($request->PostFloat('crAdd'), 2,".","");
//echo '<br> minus'.$crRevoke;
//exit;
$admin_note = $request->PostStr('admin_note');

$user_note = $admin_note;
$user_id = $request->PostInt('user_id');
$amount = $request->PostStr('amount');

$total_credits = (isset($_POST['total_credits']) && $_POST['total_credits'] != '') ? $request->PostFloat('total_credits') : 0;


if ($amount == '') {
    $amount = '0';
}
if(isset($_POST['paid_status']))
{
  $paid_status = 1;  
}
 else {
$paid_status = 0;    
}
//echo $paid_status;exit;
if (($creditType == 1 and $crAdd <= 0) or ( $creditType == 0 and $crRevoke <= 0)) {
    header("location:" . CONFIG_PATH_SITE_ADMIN . $returnURL . "?id=" . $id . "&reply=" . urlencode('reply_credit_0'));
    exit();
}



$email_config = $objEmail->getEmailSettings();
$from_admin = $email_config['system_email'];
$admin_from_disp = $email_config['system_from'];


if ($creditType == 1) {

    if ($inid > 0) {
        $sql_in = 'update ' . INVOICE_REQUEST . ' set status=1 where id=' . $inid;
        $mysql->query($sql_in);
    }

    $sql_curr = 'select * from ' . CURRENCY_MASTER . ' where id=(select currency_id from ' . USER_MASTER . ' where id=' . $id . ')';
    $query_curr = $mysql->query($sql_curr);
    $row_curr = array();
    if ($mysql->rowCount($query_curr) > 0) {
        $rows_curr = $mysql->fetchArray($query_curr);
        $row_curr = $rows_curr[0];
    } else {
        $sql_curr = 'select * from ' . CURRENCY_MASTER . ' where `default`=1';
        $query_curr = $mysql->query($sql_curr);
        if ($mysql->rowCount($query_curr) > 0) {
            $rows_curr = $mysql->fetchArray($query_curr);
            $row_curr = $rows_curr[0];
        }
    }

     //no converstion of rate
    //$row_curr['rate']
    $amount = 1 * $crAdd;



    //$objCredits->transferAdmin(1, $admin->getUserID(), $id, $crAdd, $admin_note, $user_note);
    $txn_id = $objCredits->transferAdmin(1, $admin->getUserID(), $id, $crAdd, $admin_note, $user_note);

    $sql = 'update ' . USER_MASTER . '
						set credits=credits + ' . $mysql->getFloat($crAdd) . '
					where id=' . $mysql->getInt($id);
    $mysql->query($sql);

    if ($uid > 0) {
        $sql_u = 'update ' . INVOICE_MASTER . '
						set amount=' . $amount . ', 
							credits=' . $mysql->getFloat($crAdd) . ', 
							currency_id=' . $currency_id . ', 
							paid_status=' . $paid_status . ' where id=' . $uid;
        $mysql->query($sql_u);
        header("location:" . CONFIG_PATH_SITE_ADMIN . "users_credit_unpaid.html?reply=" . urlencode('reply_success'));
        exit();
    } else {

        //Generate Invoice Number
       // echo $paid_status;exit;
        if($paid_status == 1)
        {
         $sql_inv = 'insert into ' . INVOICE_MASTER . ' (txn_id,user_id, amount, credits,gateway_id,currency_id, date_time,date_time_paid,paid_status, status) values
		    				(' . $txn_id . ', ' . $user_id . ', ' . $amount . ', ' . $mysql->getFloat($crAdd) . ',' . $gateway_id . ',' . $currency_id . ', now(),now(), ' . $paid_status . ', 0)';   
        }    
        else if($paid_status == 0)
        {
         $sql_inv = 'insert into ' . INVOICE_MASTER . ' (txn_id,user_id, amount, credits,gateway_id,currency_id, date_time,paid_status, status) values
		    				(' . $txn_id . ', ' . $user_id . ', ' . $amount . ', ' . $mysql->getFloat($crAdd) . ',' . $gateway_id . ',' . $currency_id . ', now(),0, 0)';      
        }   
        
       // echo $sql_inv;
        $query_inv = $mysql->query($sql_inv);
        $last_id = $mysql->insert_id($query);
        // echo $last_id;exit;
        //entery into invoice log
        $inv_log = 'insert into ' . INVOICE_LOG . ' (inv_id,amount, credits, gateway_id,date_time,receiver,last_updated_by,remarks) values
		    				(' . $last_id . ', ' . $amount . ', ' . $mysql->getFloat($crAdd) . ', " ' . $amount . ' Credits Added ", now(),' . $user_id . ',' . $admin->getUserId() . ',"' . $paid_status . '")';
        //echo 	$inv_log;exit;
        $query_inv = $mysql->query($inv_log);
        //
        $credits = $request->PostFloat('crAdd');

        // echo $total_credits;exit;
        $total_credits += $credits;
$note=$admin_note;
 if($note=="")
        $note="No Note Added";
 
        $objEmail = new email();
        $args = array(
            'to' => $email,
            'from' => $from_admin,
            'fromDisplay' => $admin_from_disp,
            'user_id' => $id,
            'save' => '1',
            'username' => $username,
            'credits' => $credits,
            'note' => $note,
            'total' => $total_credits,
            'site_admin' => $admin_from_disp,
            'send_mail' => true
        );
        $objEmail->sendEmailTemplate('admin_user_credit_add', $args);
        header("location:" . CONFIG_PATH_SITE_ADMIN . $returnURL . "?id=" . $id . "&rid=" . $inid . "&reply=" . urlencode('reply_success'));
        exit();
    }
} else {
    $crAcc = 0;
    $sql_credits = 'select id, credits from ' . USER_MASTER . ' where id=' . $mysql->getInt($id);
    $query_credits = $mysql->query($sql_credits);
    $row_credits = $mysql->fetchArray($query_credits);
    $crAcc = $row_credits[0]["credits"];

    if ($crAcc < $crRevoke) {
        header('location:' . CONFIG_PATH_SITE_ADMIN . $returnURL . "?id=" . $id . "&reply=" . urlencode('reply_insuff_credits'));
        exit();
    }
    $objCredits->transferAdmin(0, $admin->getUserID(), $id, $crRevoke, $admin_note, $user_note);
    // $txn_id =  $objCredits->transferAdmin(1, $admin->getUserID(), $id, $crAdd, $admin_note, $user_note); 


    $sql = 'update ' . USER_MASTER . '
						set credits=credits - ' . $mysql->getFloat($crRevoke) . '
					where id=' . $mysql->getInt($id);
    $mysql->query($sql);

    $credits = $crRevoke;
    //echo $credits;exit;
    $note=$user_note;
    if($note=="")
        $note="No Note Added";
    $total_credits -= $credits;
    $objEmail = new email();
    $args = array(
        'to' => $email,
        'from' => $from_admin,
        'fromDisplay' => $admin_from_disp,
        'user_id' => $id,
        'save' => '1',
        'username' => $username,
        'note' => $note,
        'credits' => $credits,
        'total' => $total_credits,
        'site_admin' => $admin_from_disp,
        'send_mail' => true
    );

    $objEmail->sendEmailTemplate('admin_user_credit_revoke', $args);

    header("location:" . CONFIG_PATH_SITE_ADMIN . $returnURL . "?id=" . $id . "&irid=" . $inid . "&reply=" . urlencode('reply_credit_revokes'));
    exit();
}

exit();
?>