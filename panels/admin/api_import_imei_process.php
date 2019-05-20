<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$admin->checkLogin();
$admin->reject();
$validator->formValidateAdmin('service_imei_api_import_2561561');


$id_main = $request->PostInt('id');
$clear = $request->PostInt('clear');
$submit = $request->PostStr('submit');
$credits_add = $request->PostFloat('credits_add');

if ($clear == 1) {
    $sql = 'truncate table ' . IMEI_TOOL_MASTER;
    $mysql->query($sql);
    $sql = 'truncate table ' . IMEI_TOOL_AMOUNT_DETAILS;
    $mysql->query($sql);
    $sql = 'truncate table ' . IMEI_GROUP_MASTER;
    $mysql->query($sql);
}
if ($submit == 'Import All Services') {
    $sql = 'select * from ' . CURRENCY_MASTER;
    $result_currency = $mysql->getResult($sql);
    $currencies = $result_currency['RESULT'];

    $sql = 'select * from ' . API_DETAILS . ' where type=1 and api_id=' . $id_main;
    $query = $mysql->query($sql);
    if ($mysql->rowCount($query) > 0) {
        $rows = $mysql->fetchArray($query);
        foreach ($rows as $row) {
            $sql_group = 'select * from ' . IMEI_GROUP_MASTER . ' where group_name = ' . $mysql->quote($row['group_name']);
            $query_group = $mysql->query($sql_group);

            if ($mysql->rowCount($query_group) > 0) {
                $rows_group = $mysql->fetchArray($query_group);
                $row_group = $rows_group[0];
                $group_id = $row_group['id'];
            } else {
                $sql = 'insert into ' . IMEI_GROUP_MASTER . ' (group_name, status)
									values (' . $mysql->quote($row['group_name']) . ', 1)';
                $mysql->query($sql);
                $group_id = $mysql->insert_id();                
            }
            $cr = $crPur = 0;

            $crPur = $request->PostFloat('credits_org_' . $row['id']);
            $cr = $request->PostFloat('credits_' . $row['id']);

            if ($cr <= $crPur) {
                $cr += $credits_add;
            }

            $sql = 'insert into ' . IMEI_TOOL_MASTER . ' (tool_name, delivery_time, info, group_id, visible, status)
							values(
								' . $mysql->quote($row['service_name']) . ',
								' . $mysql->quote($row['delivery_time']) . ',
								' . $mysql->quote($row['info']) . ',
								' . $group_id . ',
								1,
								1
							)';
            $mysql->query($sql);
            $tool_id = $mysql->insert_id();
            
            
               $sql_a = 'insert into nxt_grp_det
						(ser,grp)
						values(
						' . $tool_id . ',
				
						' . $group_id . ')';
        $mysql->query($sql_a);
                

            foreach ($currencies as $currency) {
                $sql = 'insert into ' . IMEI_TOOL_AMOUNT_DETAILS . ' (tool_id, currency_id, amount, amount_purchase)
								values(
									' . $tool_id . ',
									' . $currency['id'] . ',
									' . $cr . ',
									' . $crPur . '
								)';
                $mysql->query($sql);
            }
        }
        header('location:' . CONFIG_PATH_SITE_ADMIN . 'api_import_imei.html?id=' . $id_main . '&reply=' . urlencode('reply_success'));
        exit();
    } else {
        header('location:' . CONFIG_PATH_SITE_ADMIN . 'api_import_imei.html?id=' . $id_main . '&reply=' . urlencode('no_service_selected'));
        exit();
    }
} else {

    if (isset($_POST['ids'])) {
        $ids = $_POST['ids'];
        $sql = 'select * from ' . CURRENCY_MASTER;
        $result_currency = $mysql->getResult($sql);
        $currencies = $result_currency['RESULT'];
        foreach ($ids as $id) {
            $sql = 'select ad.*, igm.id as group_id, igm.group_name as gname
							from ' . API_DETAILS . ' as ad
							left join ' . IMEI_GROUP_MASTER . ' igm on (igm.group_name = ad.group_name)
							where ad.id=' . $id;
            $query = $mysql->query($sql);
            $rows = $mysql->fetchArray($query);
            $row = $rows[0];

            if ($row['group_id'] != '' and $row['group_id'] != '0') {
                $group_id = $row['group_id'];
            } else {
                $sql = 'insert into ' . IMEI_GROUP_MASTER . ' (group_name, status)
									values (' . $mysql->quote($row['group_name']) . ', 1)';
                $mysql->query($sql);
                $group_id = $mysql->insert_id();
            }
            $cr = $crPur = 0;

            $crPur = $request->PostInt('credits_org_' . $id);
            $cr = $request->PostInt('credits_' . $id);

            if ($cr <= $crPur) {
                $cr += $credits_add;
            }

            $sql = 'insert into ' . IMEI_TOOL_MASTER . ' (tool_name,delivery_time, info, group_id, visible, status)
							values(
								' . $mysql->quote($row['service_name']) . ',
								
								' . $mysql->quote($row['delivery_time']) . ',
								' . $mysql->quote($row['info']) . ',
								' . $group_id . ',
								1,
                                                                1
							)';
            $mysql->query($sql);
            $tool_id = $mysql->insert_id();

            foreach ($currencies as $currency) {
                $sql = 'insert into ' . IMEI_TOOL_AMOUNT_DETAILS . ' (tool_id, currency_id, amount, amount_purchase)
								values(
									' . $tool_id . ',
									' . $currency['id'] . ',
									' . $cr . ',
									' . $crPur . '
								)';
                $mysql->query($sql);
            }
             $sql_a = 'insert into nxt_grp_det
						(ser,grp)
						values(
						' . $tool_id . ',
				
						' . $group_id . ')';
        $mysql->query($sql_a);

            //echo $sql . '<br /><br /><br /><br /><br />';
            //$mysql->query($sql);
        }
        header('location:' . CONFIG_PATH_SITE_ADMIN . 'api_import_imei.html?id=' . $id_main . '&reply=' . urlencode('reply_success'));
        exit();
    } else {
        header('location:' . CONFIG_PATH_SITE_ADMIN . 'api_import_imei.html?id=' . $id_main . '&reply=' . urlencode('no_service_selected'));
        exit();
    }
}
?>