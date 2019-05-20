<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$admin->checkLogin();
$admin->reject();
//$validator->formValidateAdmin('');
$id = $request->PostInt('id');
$currency = $request->PostStr('currency');
$prefix = $_POST['prefix'];
$suffix = $_POST['suffix'];
$rate = $request->PostFloat('rate');
$is_default = $request->PostCheck('is_default');

$old_rate = $request->PostFloat('old_rate');
$old_is_default = $request->PostStr('old_is_default');
//
//echo $rate.$old_rate;echo '<br>';
//echo $is_default.$old_is_default;exit;

//
//if(($rate!=$old_rate)||($is_default!=$old_is_default))
//{
//    // update all rates of all services
//  //  echo 'change';
//    //exit;
//}


$status = $request->PostInt('status');


if($status==0)
{
    // check if any user is registerd with that currency
  //  $sql = 'select * from ' . CURRENCY_MASTER . ' where id=' . $mysql->getInt($id);
    $sql='select  a.id from '.USER_REGISTER_MASTER.' a
where a.currency_id=3
union 
select  b.id from '.USER_MASTER.' b
where b.currency_id=' . $mysql->getInt($id);
    $query = $mysql->query($sql);
    $rowCount = $mysql->rowCount($query);
    if ($rowCount != 0) {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "currency.html?reply=" . urlencode('reply_currency_inuse'));
    exit();
    }
}

$sql = 'update ' . CURRENCY_MASTER . '
			set 
			currency = ' . $mysql->quote($currency) . ',
			prefix =' . $mysql->quote($prefix) . ',
			suffix = ' . $mysql->quote($suffix) . ',
			rate = ' . $mysql->quote($rate) . ',
                        status = ' . $mysql->quote($status) . '
			where id = ' . $id;
$mysql->query($sql);


if ($is_default == 1) {
    $sql = 'update ' . CURRENCY_MASTER . ' set `is_default`=0';
    $mysql->query($sql);
    $sql = 'update ' . CURRENCY_MASTER . ' set `is_default`=1, `rate`=1 where id=' . $id;
    $mysql->query($sql);
}

header("location:" . CONFIG_PATH_SITE_ADMIN . "currency.html?reply=" . urlencode('lbl_currency_edit'));
exit();
?>