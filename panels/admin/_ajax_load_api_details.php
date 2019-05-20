<?php

// Set flag that this is a parent file
if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$admin->checkLogin();
$admin->reject();


$api_id = $request->getInt('parent_id');
$type = $request->getInt('type');
$type=1;
// get the server id if its ubox then skip type is query
$sql = 'select b.server_id from '.API_MASTER.' b where b.id='.$api_id;
$qrydata = $mysql->query($sql);
if ($mysql->rowCount($qrydata) > 0) {
    $rows = $mysql->fetchArray($qrydata);
    $srvid = $rows[0]['server_id'];
    if($srvid==13)
       $type=0; 
}
$perm='';
if($type==1)
{
    $perm='type in (0,' . $type . ')';
    
}
else
   $perm='type=' . $type . ''; 

$sql_api_service = 'select service_id,service_name from ' . API_DETAILS . ' where api_id=' . $mysql->getInt($api_id) . ' and  '.$perm.'  order by service_name';
$query_api_service = $mysql->query($sql_api_service);
//echo '<label>Service</label>
//	<select name="api_service_id" class="form-control">';
//echo '<option value="">--</option>';
 $emparray = array();
if ($mysql->rowCount($query_api_service) > 0) {
    $rows_api_service = $mysql->fetchArray($query_api_service);
    foreach ($rows_api_service as $row_api_service) {
         $emparray[] = $row_api_service;
     //   echo '<option value="' . base64_encode($row_api_service['service_id']) . '">' . $row_api_service['service_name'] . '</option>';
    }
    echo json_encode($emparray);

}
//echo '</select>';
?>
