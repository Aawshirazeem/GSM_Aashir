<?php


if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$admin->checkLogin();
$admin->reject();

$admin_id = $request->PostStr('a_id');
$status = $request->PostStr('ostat');
$type = $request->PostStr('type');
//echo $status;

if($type==1)
{

if($status=='true')
    $status=1;
else
    $status=0;

//$msg = $request->PostStr('msg');
//echo $sql;
$sql='update '.ADMIN_MASTER.' set online='.$status.' where id='.$admin_id;
$mysql->query($sql);
echo $status;
}
else
{
    if($status=='true')
    $status=1;
else
    $status=0;

//$msg = $request->PostStr('msg');
//echo $sql;
$sql='update '.ADMIN_MASTER.' set notify='.$status.' where id='.$admin_id;
$mysql->query($sql);
echo $status;
    
}
exit;