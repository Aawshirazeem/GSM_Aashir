<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$admin->checkLogin();
$admin->reject();



//var_dump($_REQUEST);exit;
$cur_id = $_REQUEST['cur'];
$cur_val = $_REQUEST['valuee'];

$sql = "select a.is_default from " . CURRENCY_MASTER . " a where a.id=" . $cur_id;
//echo $sql;exit;
$qrydata = $mysql->query($sql);
if ($mysql->rowCount($qrydata) > 0) {
    $rows = $mysql->fetchArray($qrydata);
    $temp = $rows[0]['is_default'];
}

if ($temp == 0) {
    exit;
} else {
    $sql = "select round( (a.rate*" . $cur_val . "),2) valuess,a.id,a.currency from " . CURRENCY_MASTER . " a
where a.is_default!=1";
//echo $sql;exit;
    $qrydata = $mysql->query($sql);
    $arr = array();
    if ($mysql->rowCount($qrydata) > 0) {
        $rows = $mysql->fetchArray($qrydata);

        foreach ($rows as $row) {

            $cur_id1 = $row['id'];
            $cur_id1_val = $row['valuess'];

            $arr[] = array(
                "id" => "$cur_id1",
                "valuee" => "$cur_id1_val",
            );
        }


//        $cur_id1 = $rows[0]['id'];
//        $cur_id1_val = $rows[0]['valuess'];
//        $cur_id2 = $rows[1]['id'];
//        $cur_id2_val = $rows[1]['valuess'];
    } else {
        echo 'some issue occur';
        exit;
    }
//echo $cur_id1;exit;
    header("Content-type: text/javascript");

    /* our multidimentional php array to pass back to javascript via ajax */
//    $arr = array(
//        array(
//            "id" => "$cur_id1",
//            "valuee" => "$cur_id1_val",
//        ),
//        array(
//            "id" => "$cur_id2",
//            "valuee" => "$cur_id2_val",
//        )
//    );

    /* encode the array as json. this will output [{"first_name":"Darian","last_name":"Brown","age":"28","email":"darianbr@example.com"},{"first_name":"John","last_name":"Doe","age":"47","email":"john_doe@example.com"}] */
    echo json_encode($arr);
    exit;
}
?>