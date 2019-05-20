<?php

//defined("_VALID_ACCESS") or die("Restricted Access");
if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}
$mysql = new mysql();
$admin = new admin();
$adminid = $admin->getUserId();
$userid = $request->GetStr("u_id");
$username = $request->PostStr('a_id');
$userid = $request->PostStr('u_id');
$daysold = $request->PostStr('days');
//echo $userid;
//$userid = urldecode($userid);
// get all the chat history new and old all data
//$sql = "select * from dummy a where a.entry_type='admin' and a.user_id=".$userid;

$langg = $admin->getLang();
if ($langg == "sp")
{
    $btnval = "Enviar";
    $tbval="Introduzca el texto";

}
else if ($langg == "fr")
{
    $btnval = "Envoyer";
    $tbval="Entrez votre texte";
}
else if ($langg == "ro")
{
    $btnval = "Trimite";
    $tbval="Introduceți textul";
}
else if ($langg == "cn")
{
    $btnval = "发送";
    $tbval="输入文本";
}
else if ($langg == "se")
{
    $btnval = "Skicka";
    $tbval="Ange din text";
}
else
{
    $btnval = "Send";
    $tbval="Enter your text";
}

$sql = "select a.*,cast(a.time_stamp as time) msgtime,b.nname adminname,c.username,c.img from " . Chat_Box . " a
inner join " . ADMIN_MASTER . " b
on a.admin_id=b.id
inner join " . USER_MASTER . " c
on a.user_id=c.id
where a.entry_type='admin' and cast(a.time_stamp as date) >= cast(DATE_SUB(NOW(), INTERVAL " . $daysold . " DAY) as date) and a.user_id=" . $userid . " and a.admin_id=" . $adminid;
//echo $sql;exit;
$sql2 = "update " . Chat_Box . " set isview=1
where entry_type='admin' and user_id=" . $userid . " and admin_id=" . $adminid;
//echo $sql2;exit;
$result = $mysql->getResult($sql);
$mysql->query($sql2);
$msgid = '';
$admintimezone = $admin->timezoneofadmin();
$adminname = $admin->getUserName();
$data = '';

$data.='
   
        <h4 class="m-t-0 m-b-20 header-title"><h4 class="color-warning pull-left">Chat with ' . $username . '</h4>   <span style="float: right"> <select name="days" id="days" class="form-control" onchange="chatgetonchange(' . $userid . ',\'' . $username . '\',this);">
            <option ' . (($daysold == 7) ? 'selected="selected"' : '') . ' value="7">7 Days</option>
            <option ' . (($daysold == 14) ? 'selected="selected"' : '') . ' value="14">14 Days</option>
            <option ' . (($daysold == 30) ? 'selected="selected"' : '') . ' value="30">30 Days</option>
            <option ' . (($daysold == 1000) ? 'selected="selected"' : '') . ' value="1000">All Days</option>
        </select></span></h4>
           
        <div class="chat-conversation">
            <ul class="conversation-list" style="margin-left: 30px;
	float: left;
	height: 300px;
	width: 100%;
	overflow-y: scroll;
	margin-bottom: 25px;
" tabindex="5000" >';

if ($result['COUNT']) {
    foreach ($result['RESULT'] as $row) {
        $msgid.=$row['a_id'] . ',';
        // set date and time according to time zone of the admin
        // echo $admin->timezone();
        // $admintimezone = $admin->timezoneofadmin();
        // $adminname = $admin->getUserName();
        $dtDateTime = new DateTime($row['time_stamp'], new DateTimeZone($admin->timezone()));
        $dtDateTime->setTimezone(new DateTimeZone($admin->timezoneofadmin()));
        $finaldate = $dtDateTime->format('l  \, F j\, Y h:i A');
        //$dtDateTime = '';
        if ($row['isadmin'] == 0) {



            $data.='<li class="clearfix" style="">
                                <div class="chat-avatar" data-toggle="tooltip" data-placement="bottom" title="' . $finaldate . '">
                                    <img src="' . CONFIG_PATH_PANEL . 'assets/images/users/avatar-1.jpg" alt="male">
                                    <i >
                                    </i>
                                </div>
                                <div class="conversation-text">
                                    <div class="ctext-wrap">
                                        <i>' . $mysql->prints($row['adminname']) . '</i>
                                        <p>
                                            ' . $mysql->prints($row['msg']) . '
                                        </p>
                                    </div>
                                </div>
                            </li>';
        } else {

            if ($row['img'] != '') {

                $data.='<li class="clearfix odd" style="">
                                <div class="chat-avatar" data-toggle="tooltip" data-placement="bottom" title="' . $finaldate . '">
                                    <img src="' . CONFIG_PATH_SITE . 'images/' . $row['img'] . '" alt="User">
                                    <i></i>
                                </div>
                                <div class="conversation-text">
                                    <div class="ctext-wrap">
                                        <i>' . $mysql->prints($row['username']) . '</i>
                                        <p>
                                            ' . $mysql->prints($row['msg']) . '
                                        </p>
                                    </div>
                                </div>
                            </li>';
            } else {
                $data.='<li class="clearfix odd" style="">
                                <div class="chat-avatar" data-toggle="tooltip" data-placement="bottom" title="' . $finaldate . '">
                                    <img src="' . CONFIG_PATH_PANEL . 'assets/images/users/avatar-2.jpg" alt="User">
                                    <i></i>
                                </div>
                                <div class="conversation-text">
                                    <div class="ctext-wrap">
                                        <i>' . $mysql->prints($row['username']) . '</i>
                                        <p>
                                            ' . $mysql->prints($row['msg']) . '
                                        </p>
                                    </div>
                                </div>
                            </li>';
            }
        }
    }
}
//echo 'chat is empty';
else
    $msgid = 0;
$msgid = rtrim($msgid, ',');
// make the isread yes to all $msgids

$sql = 'update ' . Chat_Box . ' set isview=1 where a_id in (' . $msgid . ')';
// echo $sql;exit
$mysql->query($sql);

$data.=' </ul>
            <div class="row">
                <div class="col-sm-9 chat-inputbar">
                    <input type="text" class="form-control chat-input" id="msgmsg" placeholder="'.$tbval.'">
                </div>
                <div class="col-sm-3 chat-sendd">
                    <button type="submit" class="btn btn-md btn-info btn-block waves-effect waves-light" onclick="addMSG();">'.$btnval.'</button>
                </div>
            </div>
        </div>';

echo $data;
exit;
