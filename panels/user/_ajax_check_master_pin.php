<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$member->checkLogin();
$member->reject();

$pin = $request->GetStr('pin');
if($pin!="")
{
$pin = md5(trim($pin));

$sql = 'select pin from ' . USER_MASTER . '
				where pin=' . $mysql->quote($pin) . ' and id=' . $mysql->getInt($member->getUserId());
;
$query = $mysql->query($sql);
if ($mysql->rowCount($query) > 0) {
    echo '1';
} else {
    echo '0';
    //echo '<input name="m_pin" id="m_pin" type="text" class="form-control" value="" required="" onblur="checkpin(this)"/>';
}
exit;
}
else
{
    echo 0;
}
?>