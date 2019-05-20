<?php
if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("_init.php");
}

$req = new request();
$mysql = new mysql();
$sql = 'select a.id,b.amount from  ' . IMEI_TOOL_MASTER . ' a
inner join  ' . IMEI_TOOL_AMOUNT_DETAILS . ' b
on a.id=b.tool_id and b.currency_id=(select cm.id from  ' . CURRENCY_MASTER . ' cm
where cm.is_default=1)
where a.id=' . $_REQUEST['toolid'];
$query = $mysql->query($sql);

if ($mysql->rowCount($query) > 0) {
    //   $rows = $mysql->fetchArray($query);
    $rows = $mysql->fetchArray($query);
    $row = $rows[0];
    //echo $row['amount'];
   // var_dump($row);
    $RateInfo = Array('sid' => $row['id'], 'newprice' => $row['amount']);
    echo json_encode($RateInfo );
}
//echo 'you are here';

exit;
?>