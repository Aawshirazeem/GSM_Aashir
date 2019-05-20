<?php

// Set flag that this is a parent file
if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$member->checkLogin();
$member->reject();



$type = $request->PostInt('type');
// get the server id if its ubox then skip type is query
//$sql_api_service = 'select * from ' . API_DETAILS . ' where api_id=' . $mysql->getInt($api_id) . ' and type=' . $type . ' order by service_name';

if ($type == 1) {
    $sql_api_service = 'select a.id,a.tool_name from ' . IMEI_TOOL_MASTER . ' a where a.visible=1 order by a.tool_name';
    $query_api_service = $mysql->query($sql_api_service);
    echo '<label>Service</label>
	<select name="service_id" class="form-control">';
    echo '<option value="">Select IMEI Service</option>';
    echo '<option value="0">ALL</option>';
    if ($mysql->rowCount($query_api_service) > 0) {
        $rows_api_service = $mysql->fetchArray($query_api_service);
        foreach ($rows_api_service as $row_api_service) {
            echo '<option value="' . $row_api_service['id'] . '">' . $row_api_service['tool_name'] . '</option>';
        }
    }
    echo '</select>';
     echo '<div class="class="form-group"">
    <label>Satus</label>
    <select name="status" class="form-control" id="status">
        <option value="">Select Order Status</option>
        <option value="-11">ALL</option>
        <option value="0">New</option>
        <option value="1">Inprocess</option>
        <option value="2">Complete</option>    
        <option value="3">Rejected</option>    
    </select>
</div>';
}
else if($type == 2){
    $sql_api_service = 'select a.id,a.service_name from '.FILE_SERVICE_MASTER.' a where a.`status`=1
order by a.service_name';
    $query_api_service = $mysql->query($sql_api_service);
    echo '<label>Service</label>
	<select name="service_id" class="form-control">';
    echo '<option value="">Select File Service</option>';
    
        echo '<option value="0">ALL</option>';
    if ($mysql->rowCount($query_api_service) > 0) {
        $rows_api_service = $mysql->fetchArray($query_api_service);
        foreach ($rows_api_service as $row_api_service) {
            echo '<option value="' . $row_api_service['id'] . '">' . $row_api_service['service_name'] . '</option>';
        }
    }
    echo '</select>';
      echo '<div class="class="form-group"">
    <label>Satus</label>
    <select name="status" class="form-control" id="status">
        <option value="">Select Order Status</option>
         <option value="-11">ALL</option>
        <option value="0">New</option>
        <option value="-1">Inprocess</option>
        <option value="1">Complete</option>    
        <option value="2">Rejected</option>    
    </select>
</div>';
}
else if($type == 3){
    $sql_api_service = 'select a.id,a.server_log_name from '.SERVER_LOG_MASTER.' a where a.`status`=1
order by a.server_log_name';
    $query_api_service = $mysql->query($sql_api_service);
    echo '<label>Service</label>
	<select name="service_id" class="form-control">';
   
    echo '<option value="">Select Server Log Service</option>';
     echo '<option value="0">ALL</option>';
    if ($mysql->rowCount($query_api_service) > 0) {
        $rows_api_service = $mysql->fetchArray($query_api_service);
        foreach ($rows_api_service as $row_api_service) {
            echo '<option value="' . $row_api_service['id'] . '">' . $row_api_service['server_log_name'] . '</option>';
        }
    }
    echo '</select>';
      echo '<div class="class="form-group"">
    <label>Satus</label>
    <select name="status" class="form-control" id="status">
        <option value="">Select Order Status</option>
        <option value="-11">ALL</option>
        <option value="0">New</option>
        <option value="-1">Inprocess</option>
        <option value="1">Complete</option>    
        <option value="2">Rejected</option>    
    </select>
</div>';
}
else {
    
}
?>
