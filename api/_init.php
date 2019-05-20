<?php
/**
 * @copyright Copyright (C) 2008-09 Nxt Designs. www.nxtdesigns.com. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */
 
	// no direct access
	//defined("_VALID_ACCESS") or die("Restricted Access");
 

	if (defined("debug"))
	{
		error_reporting(E_ALL);
	}
	else
	{
		error_reporting( error_reporting() & ~E_NOTICE );
	}
	require_once("../_config.php");
	
	function __autoload($className)
	{
		require_once(CONFIG_PATH_SITE_ABSOLUTE . "system/classes/" . $className . ".class.php");
	}

	if(!isset($_COOKIE["useCookies"]))
	{
		session_start();
	}
	
	$objConnection = new mysql_connection();
	$mysql_connection = $objConnection->getConnection();
	//print_r($mysql_connection);
	$mysql = new mysql();


	$mysql->query('SET SQL_BIG_SELECTS=1'); 
	
	
	//require_once("_validate_request.php");
?>