<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$admin->checkLogin();
$admin->reject();
$validator->formValidateAdmin('user_edit_789971255d2');

$id = $request->PostInt('id');
$key = $request->PostStr('key');
$ipspost = $request->PostStr('ip_pool');
$api_access = $request->PostInt('api_access');
$old_api_access = $request->PostInt('old_api_access');
$ips = explode("\n", $ipspost);
//  echo $key;exit;
$sql = 'update ' . USER_MASTER . '
			set 
                        api_access = ' . $mysql->getInt($api_access) . ',
                               api_key = ' . $mysql->quote($key) . ' 
			where id = ' . $mysql->getInt($id);
// echo $sql;exit;
$mysql->query($sql);
//var_dump($ips) ;
//echo count($ips[0]);echo $ips[0];
if ($ips[0] == "") {
    $sql = ' delete  from ' . IP_POOL . '
			where id = ' . $mysql->getInt($id);
    $mysql->query($sql);
}
$i = 0;
foreach ($ips as $un) {
    $un = trim($un);
    if ($un != "") {
        if ($i == 0) {
            // first delete all old record of that user
            $sql = ' delete  from ' . IP_POOL . '
			where id = ' . $mysql->getInt($id);
            $mysql->query($sql);
            $i++;
            // del done
        }
        // now add new ones
        $sql = 'insert into ' . IP_POOL . ' (id,ip) values(' . $mysql->getInt($id) . ',' . $mysql->quote($un) . ')';
        $mysql->query($sql);
    }
}

//$api_access = $mysql->getInt($api_access);
$old_api_access = $mysql->getInt($old_api_access);
$api_url = "http://" . CONFIG_DOMAIN . "/app";
if ($api_access == 1) {

    $sql = 'select * from ' . USER_MASTER . ' where id=' . $id;
    $query = $mysql->query($sql);
    //   echo $mysql->rowCount($query);exit;
//$rowCount = $mysql->rowCount($query);
    if ($mysql->rowCount($query) > 0) {

        $emailObj = new email();
        $email_config = $emailObj->getEmailSettings();
        $admin_email = $email_config['admin_email'];
        $from_admin = $email_config['system_email'];
        $admin_from_disp = $email_config['system_from'];
        $support_email = $email_config['support_email'];
        $signatures = "<br /><br /><br /><br />" . nl2br($email_config['admin_signature']);

        $row = $mysql->fetchArray($query);
        $keyNew = $row[0]['api_key'];
        $email = $row[0]['email'];
        $username = $row[0]['username'];
        $args = array(
            'to' => $email,
            'from' => $from_admin,
            'fromDisplay' => $admin_from_disp,
            'user_id' => $id,
            'save' => '1',
            'api_key' => $keyNew,
            'api_url' => $api_url,
            'username' => $username,
            'site_admin' => $admin_from_disp,
            'send_mail' => true
        );
        $emailObj->sendEmailTemplate('admin_user_api_reset', $args);
    }
} else if ($old_api_access == 1 && $api_access == 0) {
    //echo 'noo';exit;
}

header("location:" . CONFIG_PATH_SITE_ADMIN . "users_edit.html?id=" . $id . "&reply=" . urlencode('reply_profile_update'));
exit();
?>
