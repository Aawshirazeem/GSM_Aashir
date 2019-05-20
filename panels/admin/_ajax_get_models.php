<?php

//defined("_VALID_ACCESS") or die("Restricted Access");
if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$admin->checkLogin();
$admin->reject();

$service_id = $request->PostStr('srvc');
$brand_id = $request->PostStr('brand');

// get old selected models
$sql = 'select a.model_id from '.IMEI_MODEL_MASTER_2.' a where a.imei_id=' . $service_id . '  and a.brand_id=' . $brand_id;
;
$result2 = $mysql->getResult($sql);
if ($result2['COUNT']) {

   // $sql = 'select * from nxt_imei_model_master a where a.brand=' . $brand_id;
    $sql = 'select ok.id,ok.model,c.model_id from(
select * from '.IMEI_MODEL_MASTER.' b
where b.brand=' . $brand_id . ') ok
 left join '.IMEI_MODEL_MASTER_2.' c on ok.id=c.model_id where ok.`status`=1 order by ok.id';
    $result = $mysql->getResult($sql);
    $msgid = '';
    if ($result['COUNT']) {

        $chat_converstaion = '<div class="" style="">
<table class="table table-striped table-hover panel" id="modelss">
    <tr>
        <td><input type="checkbox" id="mine" value="1" onclick="ok()"></td>
        <td>Id</td>
        <td>Model</td>
    </tr>';
        foreach ($result['RESULT'] as $row) {

            $chat_converstaion.=' 
        <tr>
        <td width="25" class="text-center" ><input type="checkbox" name="models[]" ' . (($row['id'] == $row['model_id']) ? 'checked="checked"' : '') . ' value="' . $row['id'] . '" class="subSelect"></td>
        <td>' . $row['id'] . '</td>
        <td>' . $row['model'] . '</td>
    </tr>';
        }
        $chat_converstaion.='</table></div>';
        echo $chat_converstaion;
        exit;
    } else {
        echo 'No Models Added';
        exit;
    }
} else {
    $sql = 'select * from '.IMEI_MODEL_MASTER.' a where a.`status`=1 and a.brand=' . $brand_id;
    $result = $mysql->getResult($sql);
    $msgid = '';
    if ($result['COUNT']) {

        $chat_converstaion = '<div class="">
<table class="table table-striped table-hover panel" id="modelss">
    <tr>
        <td width="25" class="text-center" ><input type="checkbox" id="mine" value="1" onclick="ok()"></td>
        <td>Id</td>
        <td>Model</td>
    </tr>';
        foreach ($result['RESULT'] as $row) {

            $chat_converstaion.=' 
        <tr>
        <td><input type="checkbox" class="subSelect" name="models[]"  value="' . $row['id'] . '"></td>
        <td>' . $row['id'] . '</td>
        <td>' . $row['model'] . '</td>
    </tr>';
        }
        $chat_converstaion.='</table></div>';
        echo $chat_converstaion;
        exit;
    } else {
        echo 'No Models Added';
        exit;
    }
}
?>