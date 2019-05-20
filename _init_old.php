<?php
	/**
	* @copyright Copyright (C) 2008-13 GSM Freedom. www.gsmfreedom.com. All rights reserved.
	* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
	*/
 
	// no direct access
	defined("_VALID_ACCESS") or die("Restricted Access");
 

	require_once("_config.php");
	
	if (defined("debug"))
	{
		error_reporting(E_ALL);
	}
	else
	{
		error_reporting( error_reporting() & ~E_NOTICE );
	}
//error_reporting(E_ALL);
//ini_set('display_errors','on');
//phpinfo();
	//exit;
	include(CONFIG_PATH_THEME_ABSOLUTE . '_config.php');
	//include('_config.php');
	
	function __autoload($className)
	{
		require_once(CONFIG_PATH_SITE_ABSOLUTE . "system/classes/" . $className . ".class.php");
		//require_once("system/classes/" . $className . ".class.php");
	}
	ini_set('session.use_trans_sid', 0);
	ini_set('session.serialize_handler', 'php');
	ini_set('session.use_cookies', 1);
	ini_set('session.name', 'GSMFreedom');
	ini_set('session.cookie_lifetime', 0);
	ini_set('session.cookie_path', CONFIG_PATH_SITE);
	ini_set('session.auto_start', 0);
	ini_set('session.gc_maxlifetime',10800);

	//ini_set('session.cache_limiter','public');
	//session_cache_limiter(false);
	
	
	session_start();

	$objConnection = new mysql_connection();
	$mysql_connection = $objConnection->getConnection();
	$mysql = new mysql();


	/*$sql = 'select * from ' . TIMEZONE_MASTER . ' where is_default=1';
	$result = $mysql->getResult($sql);
	$timeZone = $result['RESULT'][0]['timezone'];

	date_default_timezone_set($timeZone);
	$sql = 'SET time_zone=' . $mysql->quote($timeZone);
	$mysql->query($sql);*/

	
	
	$cookie = new cookie();
	$request = new request();
	$objPass = new password();
	$objCredits = new credits();
	$objEmail = new email();
	$graphics = new graphics();
	$admin = new admin();
	$supplier = new supplier();
	$member = new member();
	$validator = new validator();
	$alert = new alert();
	$objHelper = new helper();
	
	/*$timezone = (isset($_SESSION['user_timezone'])&&$_SESSION['user_timezone']!=''?$_SESSION['user_timezone']:'');
	if(isset($timezone) && $timezone !='' && $timezone != NULL)
	{
		date_default_timezone_set($timezone);
		//echo date('Y-m-d H:i:s') ;
	}*/
	
?>