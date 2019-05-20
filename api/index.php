<?php
	define("_VALID_ACCESS",1);
	require_once("_init.php");
	//require_once("_validate_request.php");
	$mysql = new mysql();
	$xml = new xml();
	$req = new request();
	
if(!empty($_SERVER['HTTP_CLIENT_IP']))
{
        //check ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
{
        //to check ip is pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }

	
	$action = $req->PostStr('action');
	$imei_id = $req->PostStr('imei_id');
	$api_key = $req->PostStr('apiaccesskey');
        $uname=$req->PostStr('username');
	$member = new member_api($api_key,$uname);
	

	switch($mysql->prints($action))
	{
		case 'accountinfo':
			include("api_accountinfo.php");
			break;

		case 'fileservicelist':
			include("api_fileservice_list.php");
			break;
		case 'getfileorder':
			include("api_file_order_details.php");
			break;

		case 'imeiservicelist':
			include("api_imeiservice_list.php");
			break;
		case 'getimeiorder':
			include("api_imei_orders_details.php");
			break;
		case 'getimeiservicedetails':
			include("get_single_imei_service_details.php");
			break;
			
			
			
		case 'meplist':
			include("api_mep_list.php");
			break;
		case 'modellist':
			include("api_model_list.php");
			break;
		case 'providerlist':
			include("get_provider_list.php");
			break;
			
			
		case 'placeimeiorder':
			include("api_place_imei_order.php");
			break;
                    case 'placefileorder':
			include("api_place_file_order.php");
			break;
	}
?>