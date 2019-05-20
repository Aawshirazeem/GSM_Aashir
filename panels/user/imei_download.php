<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$member->checkLogin();
$member->reject();
//echo '<pre>';
//var_dump($_POST);
//exit;
if ($_POST['stype'] == "")
    header("location:" . CONFIG_PATH_SITE_USER . "export_data.html");
$ifimei = $_POST['imei'];
$ifcode = $_POST['ucode'];
$ifservice = $_POST['servicename'];
//$filetype=$_POST['filetype'];

if ($_POST['stype'] == 1) {
    // for imei
    $serviceid = $_POST['service_id'];
    $status = $_POST['status'];
    $fromdate = $_POST['from_date'];
    $todate = $_POST['to_date'];
    // echo $serviceid;
    $seviceee = "";
    $statusss = "";
    if ($serviceid != 0)
        $seviceee = 'and a.tool_id=' . $serviceid . ' ';
    if ($status != '-11')
        $statusss = 'and a.`status`=' . $status . '';

    if ($serviceid == "" || $status == "" || $fromdate == "" || $todate == "")
        header("location:" . CONFIG_PATH_SITE_USER . "export_data.html");

    $content = "";
//	$ids = "";
//	if(isset($_POST['chk']))
//	{
//		$chks = $_POST['chk'];
//		foreach($chks as $chk)
//		{
//			$ids .= $mysql->getInt($chk) . ',';
//		}
//	}
//	
//	$ids = rtrim($ids, ',');
//	if($ids == "")
//	{
//		$content = "Please select an IMEI";
//	}
//	else
//	{

    $sql = 'select a.id,a.imei,b.tool_name,a.reply,a.message from nxt_order_imei_master a
inner join nxt_imei_tool_master b
on a.tool_id=b.id
where a.user_id=' . $member->getUserId() . ' ' . $seviceee . ' ' . $statusss . '
and
cast(a.date_time as date) between  ' . $mysql->quote($fromdate) . ' and ' . $mysql->quote($todate).' order by b.tool_name';
    //    echo $sql;exit;
    $query = $mysql->query($sql);
    if ($mysql->rowCount($query) > 0) {
        $rows = $mysql->fetchArray($query);
        //   echo '<pre>';
        //   var_dump($rows);
        //   exit;
        $counter = 1;
        $temptoolname = "";
        foreach ($rows as $row) {



            // $content .='IMEI: ' . $row['imei'];
//				switch($row['status'])
//				{
//					case 0:
//					case 1:
//						$content .= 'Status: Pending,' . "\r\n";
//						break;
//					case 2:
//						$content .= 'Status: Available' . "\r\n";
//						$content .= 'Unlock Code:' . base64_decode($row['reply']) . "\r\n";
//						break;
//					case 3:
//						$content .=  'Status: Unavailable' . "\r\n";
//						break;
//		
//						}

            if ($ifservice == 1) {
                if ($temptoolname != $row['tool_name']) {
                    //$content .= '-----------------------------'. "\n";;
                  // $content .= "\r\n-------\n\n";
                    $content .= "\r\n"."===============================================================================\n\r";
                    $content .= "\r\n".'           Tool Name: ' . $row['tool_name'] . "           \n\r";
                    $content .= "\n"."===============================================================================\n\r";
                    $temptoolname = $row['tool_name'];
                }
            }
            if ($ifimei == 1)
                $content .= "\n IMEI: " . $row['imei'];


            //  $content .= '===> ';
            if ($ifcode == 1) {

                if ($row['reply'] == '') {
                    $content .=' ===>  MESSAGE: ' . $row['message'] . "\n\n";
                } else {
                    $content .= '  ===> Code: ' . base64_decode($row['reply']) . "\n\n";
                }


//                $content .= '  ===> Code: ' . base64_decode($row['reply']) . "\r\n";
//                $temp = strip_tags(base64_decode($row['reply']));
//                $temp = str_replace($row['imei'], '', $temp);
//                $temp = preg_replace('/\s+/', ' ', $temp);
//                if ($row['reply'] == '') {
//                    //$temp = $row['message'];
//                     $content .=' ===>MESSAGE: ' . $row['message']. "\r";
//                }
            }
            $content .= $temp . "\r\n";
            $counter++;

            //$content .= "****************************************************************";
            //$content .= "\r\n";
        }
    } else {
        $content = "No Data Found";
    }
    //}

    $filename = "imei.txt";
    $download = new download();
    $download->downloadContent($content, $filename);
}


// file service
if ($_POST['stype'] == 2) {
    // for imei
    $serviceid = $_POST['service_id'];
    $status = $_POST['status'];
    $fromdate = $_POST['from_date'];
    $todate = $_POST['to_date'];
    if ($serviceid == "" || $status == "" || $fromdate == "" || $todate == "")
        header("location:" . CONFIG_PATH_SITE_USER . "export_data.html");
    $seviceee = "";
    $statusss = "";
    if ($serviceid != 0)
        $seviceee = 'and a.tool_id=' . $serviceid . ' ';
    if ($status != '-11')
        $statusss = 'and a.`status`=' . $status . '';
    $temptoolname = "";
    $content = "";
//	$ids = "";
//	if(isset($_POST['chk']))
//	{
//		$chks = $_POST['chk'];
//		foreach($chks as $chk)
//		{
//			$ids .= $mysql->getInt($chk) . ',';
//		}
//	}
//	
//	$ids = rtrim($ids, ',');
//	if($ids == "")
//	{
//		$content = "Please select an IMEI";
//	}
//	else
//	{



    $sql = 'select a.id,b.service_name,a.unlock_code,a.reply from nxt_order_file_service_master a
inner join nxt_file_service_master b
on a.file_service_id=b.id
where a.user_id=' . $member->getUserId() . ' ' . $seviceee . ' ' . $statusss . '
and
cast(a.date_time as date) between  ' . $mysql->quote($fromdate) . ' and ' . $mysql->quote($todate).'  order by b.service_name';

    // echo $sql;exit;
    $query = $mysql->query($sql);
    if ($mysql->rowCount($query) > 0) {
        $rows = $mysql->fetchArray($query);
        //   echo '<pre>';
        //   var_dump($rows);
        //   exit;
        $counter = 1;
        foreach ($rows as $row) {



            //  $content .='Id: ' . $row['id'];
//				switch($row['status'])
//				{
//					case 0:
//					case 1:
//						$content .= 'Status: Pending,' . "\r\n";
//						break;
//					case 2:
//						$content .= 'Status: Available' . "\r\n";
//						$content .= 'Unlock Code:' . base64_decode($row['reply']) . "\r\n";
//						break;
//					case 3:
//						$content .=  'Status: Unavailable' . "\r\n";
//						break;
//		
//						}
            if ($ifservice == 1) {
                // $content .= '  ===> Tool Name: ' . $row['service_name'] . "\r";


                if ($temptoolname != $row['service_name']) {
                    //$content .= '-----------------------------'. "\n";;
                    //$content .= '-----------Service Name: ' . $row['service_name'] . "-----------\n\r";
                    // $content .= "\n".'------------------------------------';
               //     $temptoolname = $row['service_name'];
                    
                    $content .= "\r\n"."===============================================================================\n\r";
                    $content .= "\r\n".'           Tool Name: ' . $row['service_name'] . "           \n\r";
                    $content .= "\n"."===============================================================================\n\r";
                    $temptoolname = $row['service_name'];
                }
                
                
                
                
                
            }
            if ($ifimei == 1)
                $content .="\n ID: " . $row['id'];
            //  $content .= '===> ';
            if ($ifcode == 1)
            // $content .= '  ===> Code: ' .$row['unlock_code']. "\r\n";
            // $temp = strip_tags(base64_decode($row['reply']));
            //  $temp = str_replace($row['imei'], '', $temp);
            // $temp = preg_replace('/\s+/', ' ', $temp);
                if ($row['unlock_code'] != '') {
                    $content .= '  ===> Code: ' . $row['unlock_code'] . "\r\n";
                } else {
                    $content .= '  ===> Reply: ' . $row['reply'] . "\r\n";
                }
            $content .= $temp . "\r\n";
            $counter++;

            //$content .= "****************************************************************";
            //$content .= "\r\n";
        }
    } else {
        $content = "No Data Found";
    }
    //}

    $filename = "file.txt";
    $download = new download();
    $download->downloadContent($content, $filename);
}

// file server log
if ($_POST['stype'] == 3) {
    // for imei
    $serviceid = $_POST['service_id'];
    $status = $_POST['status'];
    $fromdate = $_POST['from_date'];
    $todate = $_POST['to_date'];
    if ($serviceid == "" || $status == "" || $fromdate == "" || $todate == "")
        header("location:" . CONFIG_PATH_SITE_USER . "export_data.html");
    $seviceee = "";
    $statusss = "";
    $temptoolname = "";
    if ($serviceid != 0)
        $seviceee = 'and a.tool_id=' . $serviceid . ' ';
    if ($status != '-11')
        $statusss = 'and a.`status`=' . $status . '';
    $content = "";
//	$ids = "";
//	if(isset($_POST['chk']))
//	{
//		$chks = $_POST['chk'];
//		foreach($chks as $chk)
//		{
//			$ids .= $mysql->getInt($chk) . ',';
//		}
//	}
//	
//	$ids = rtrim($ids, ',');
//	if($ids == "")
//	{
//		$content = "Please select an IMEI";
//	}
//	else
//	{



    $sql = 'select a.id,b.server_log_name,a.reply,a.remarks,a.message from ' . ORDER_SERVER_LOG_MASTER . ' a
inner join ' . SERVER_LOG_MASTER . ' b
on a.server_log_id=b.id
where a.user_id=' . $member->getUserId() . ' ' . $seviceee . ' ' . $statusss . '
and
cast(a.date_time as date) between  ' . $mysql->quote($fromdate) . ' and ' . $mysql->quote($todate).' order by b.server_log_name';;

    //echo $sql;exit;
    $query = $mysql->query($sql);
    if ($mysql->rowCount($query) > 0) {
        $rows = $mysql->fetchArray($query);
        //   echo '<pre>';
        //   var_dump($rows);
        //   exit;
        $counter = 1;
        foreach ($rows as $row) {



            // $content .='Id: ' . $row['id'];
//				switch($row['status'])
//				{
//					case 0:
//					case 1:
//						$content .= 'Status: Pending,' . "\r\n";
//						break;
//					case 2:
//						$content .= 'Status: Available' . "\r\n";
//						$content .= 'Unlock Code:' . base64_decode($row['reply']) . "\r\n";
//						break;
//					case 3:
//						$content .=  'Status: Unavailable' . "\r\n";
//						break;
//		
//						}
            if ($ifservice == 1) {
                // $content .= '  ===> Tool Name: ' . $row['server_log_name'] . "\r";

                if ($temptoolname != $row['server_log_name']) {
                    //$content .= '-----------------------------'. "\n";;
                   // $content .= '-----------Service Name: ' . $row['server_log_name'] . "-----------\n\r";
                    // $content .= "\n".'------------------------------------';
                  //  $temptoolname = $row['server_log_name'];
                    
                    
                    $content .= "\r\n"."===============================================================================\n\r";
                    $content .= "\r\n".'           Tool Name: ' . $row['server_log_name'] . "           \n\r";
                    $content .= "\n"."===============================================================================\n\r";
                    $temptoolname = $row['server_log_name'];
                    
                }
            }
            //  $content .= '===> ';

            if ($ifimei == 1)
                $content .="\n ID: " . $row['id'];
            if ($ifcode == 1)
            // $content .= '  ===> Code: ' .$row['unlock_code']. "\r\n";
            // $temp = strip_tags(base64_decode($row['reply']));
            //  $temp = str_replace($row['imei'], '', $temp);
            // $temp = preg_replace('/\s+/', ' ', $temp);
                if ($row['reply'] != '') {
                    $content .= '  ===> Code: ' . $row['reply'] . "\r\n";
                } else {
                    $content .= '  ===> Reply: ' . $row['message'] . "\r\n";
                }
            $content .= $temp . "\r\n";
            $counter++;

            //$content .= "****************************************************************";
            //$content .= "\r\n";
        }
    } else {
        $content = "No Data Found";
    }
    //}

    $filename = "serverlog.txt";
    $download = new download();
    $download->downloadContent($content, $filename);
}
?>
