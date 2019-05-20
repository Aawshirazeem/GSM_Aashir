<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}


$admin->checkLogin();
$admin->reject();
$id = $_POST['api_id'];
$databack = '';
if ($id != "") {
    $sql = 'select a.id, a.tool_name from nxt_imei_tool_master a

where a.api_id=' . $id;

    $query = $mysql->query($sql);

    if ($mysql->rowCount($query) > 0) {

        $databack.='    <table class="table table-hover">
            <thead>
                <tr>
                  <th>#</th>
                    <th>Service(s)</th>

                </tr>
            </thead>
            <tbody>';


        $rows = $mysql->fetchArray($query);

        foreach ($rows as $row) {

            $databack.= '<tr>';
            $databack.= '<td>' . $row['id'] . '</td>';
            $databack.= '<td>' . $row['tool_name'] . '</td></tr>';
        }
        $databack.= '</tbody></table>';
    } else
        $databack = "No Service Attach";
}
else {
    $databack = "Id Cannot Be Empty";
}

echo $databack;
exit;
