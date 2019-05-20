
<?php

//defined("_VALID_ACCESS") or die("Restricted Access");
if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

//$admin->checkLogin();
//$admin->reject();
$member->checkLogin();
$member->reject();
$admin_id = $request->PostStr('a_id');
$user_id = $request->PostStr('u_id');
// first get all that msg that are not read by admin from the user

$sql = "select a.a_id,a.admin_id,a.user_id,a.msg,cast(a.time_stamp as time) msgtime from dummy a
        where a.admin_id=1 and a.entry_type='admin' and a.isview=0 and a.user_id=2";

$sql = "select a.a_id,a.admin_id,a.user_id,a.msg,cast(a.time_stamp as time) msgtime,a.isview ,a.time_stamp
,b.nname adminname,c.username,c.img
from " . Chat_Box . " a
inner join " . ADMIN_MASTER . " b
on a.admin_id=b.id
inner join " . USER_MASTER . " c
on a.user_id=c.id
where a.admin_id=" . $admin_id . "
and a.entry_type='user' 
and a.isview=0 
and a.user_id=" . $user_id;
//echo $sql;

$result = $mysql->getResult($sql);
$msgid = '';
if ($result['COUNT']) {

    foreach ($result['RESULT'] as $row) {
        $msgid.=$row['a_id'] . ',';
        
        $dtDateTime = new DateTime($row['time_stamp'], new DateTimeZone($admin->timezone()));
        $dtDateTime->setTimezone(new DateTimeZone($member->timezone()));
        $finaldate = $dtDateTime->format('l  \, F j\, Y h:i A');
        
        
       // $newDateTime = date('h:i A', strtotime($row['time_stamp']));
        // echo $newDateTime;
        $chat_converstaion.='
      <li class="clearfix">
    <div class="chat-avatar" data-toggle="tooltip" data-placement="bottom" title="' . $finaldate . '">
        <img src="' . CONFIG_PATH_PANEL . 'assets/images/users/avatar-1.jpg" alt="user">
        <i></i>
    </div>
    <div class="conversation-text">
        <div class="ctext-wrap">
            <i>' . $row['adminname'] . '</i>
            <p>
                ' . $row['msg'] . '                        
            </p>
        </div>
    </div>
</li>';
    }
    $msgid = rtrim($msgid, ',');
    // make the isread yes to all $msgids

    $sql = 'update ' . Chat_Box . ' set isview=1
where a_id in (' . $msgid . ')';
    //echo $sql;
    $mysql->query($sql);
    echo $chat_converstaion;
}
?>
