<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("_init.php");
}

$req = new request();
$mysql = new mysql();
$xml = new xml();
$ok='';
// check IP validation here

if($member->getUserId()!=''){

$sql = 'select b.ip from ' . IP_POOL . ' b where b.id=' . $member->getUserId();
$query = $mysql->query($sql);
$rows = $mysql->fetchArray($query);
if ($mysql->rowCount($query) == 0) {
    // it means no ip add yet so then add the ip 
    $sql = 'insert into ' . IP_POOL . ' (id,ip) values(' . $member->getUserId() . ',' . $mysql->quote($ip) . ')';

    // echo $sql;
    $query = $mysql->query($sql);
    $check = TRUE;
} else {
    // ip added in table already,,, now check if the ip is same as in db or not
    $check = FALSE;
    foreach ($rows as $row) {

        if ($row['ip'] == $ip)
            $check = TRUE;
    }
}
}
else
{
     $ok = -1;
}

if ($ok == -1) {
    //$fail = Array('ERROR' => "Invalid Ip");
    $AccountInfo = Array('credit' => 0, 'mail' => '', 'currency' => '');
    $info = Array('message' => 'Invalid Cred', 'AccoutInfo' => $AccountInfo);
    $result = array($info);
    $error = Array('ERROR' => $result, 'apiversion' => '2.0.0');
   // $info = Array('message' => 'Invalid Ip');
   // $result = array($info);
   // $fail = Array('ERROR' => $result, 'apiversion' => '2.0.0');
    echo json_encode($error);
    return FALSE;
    exit;
}

// if check if flase then now math give error else nothing happens all good

if ($check == FALSE) {
    //$fail = Array('ERROR' => "Invalid Ip");
    $AccountInfo = Array('credit' => 0, 'mail' => '', 'currency' => '');
    $info = Array('MESSAGE' => 'Your Server IP is not allowed...', 'AccoutInfo' => $AccountInfo);
    $result = array($info);
    $error = Array('ERROR' => $result, 'apiversion' => '2.0.0');
   // $info = Array('message' => 'Invalid Ip');
   // $result = array($info);
   // $fail = Array('ERROR' => $result, 'apiversion' => '2.0.0');
    echo json_encode($error);
    return FALSE;
    exit;
}
else
{




//end here
$sql = 'select
					um.credits, um.email, um.currency_id,  cm.currency,cm.prefix
				from ' . USER_MASTER . ' as um
				left join ' . CURRENCY_MASTER . ' as cm on (um.currency_id = cm.id)
				where um.api_key=' . $mysql->quote($api_key);
$query = $mysql->query($sql);
$rows = $mysql->fetchArray($query);
$row = $rows[0];
$currency = $row['prefix'];
if ($row['currency_id'] == 0) {
    $sql_curr = 'select currency from ' . CURRENCY_MASTER . ' where `is_default`=1';
    $query_curr = $mysql->query($sql_curr);
    $rows_curr = $mysql->fetchArray($query_curr);
    $currency = $rows_curr[0]['prefix'];
}


$AccountInfo = Array('credit' => $row['credits'], 'mail' => $row['email'], 'currency' => $currency);
$info = Array('message' => 'Your Account Info', 'AccoutInfo' => $AccountInfo);
$result = array($info);
$Success = Array('SUCCESS' => $result, 'apiversion' => '2.0.0');

echo json_encode($Success);
}
?>