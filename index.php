<?php

/**

* @copyright Copyright (C) 2008-13 GSM Freedom. www.gsmfreedom.com. All rights reserved.

* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php

*/

ini_set('max_input_vars', 3000);

define("_VALID_ACCESS",1);

define('IOS_PHP_VERSION_REQUIRED', '5.2.0');

ini_set('display_errors',0);





if (version_compare(IOS_PHP_VERSION_REQUIRED, PHP_VERSION) >= 0) {

	die(sprintf('PHP %s required instead of %s', IOS_PHP_VERSION_REQUIRED, PHP_VERSION));

}



if (!extension_loaded('gd')) {

	if (!dl('gd.so')) {

		die("It looks like GD Lib is installed");

	}

}



if (!extension_loaded('mysqli')) {

	die("It looks like MySQLi is installed");

}



if (!extension_loaded('curl')) {

	die("It looks like cURL is installed");

}

ob_start();

require_once("_init.php");



$data = array();

$data['common']['doc_title'] = CONFIG_SITE_NAME;



$page = (isset($_GET['page'])) ? strtolower($_GET['page']) : 'index';

$view = (isset($_GET['view'])) ? strtolower($_GET['view']) : 'main';

$page = ($page == 'home') ? 'index' : $page;



if($page == 'cms'){

	$view = 'cms';

}



$incPathView = CONFIG_PATH_THEME;

$incPathViewAbsolute = CONFIG_PATH_THEME_ABSOLUTE;

$incPathPre = CONFIG_PATH_MAIN_PREFETCH;

$incPathPreAbsolute = CONFIG_PATH_MAIN_PREFETCH_ABSOLUTE;

$incPathLang = CONFIG_PATH_MAIN_LANG;

$incPathLangAbsolute = CONFIG_PATH_MAIN_LANG_ABSOLUTE;

$incPathModules = CONFIG_PATH_MODULES;

$incPathModulesAbsolute = CONFIG_PATH_MODULES_ABSOLUTE;

$statusweb = 0;



$sql = 'select a.`status` from ' . Website_Maintinance . ' a where a.id=1';

$query = $mysql->query($sql);



if($mysql->rowCount($query) > 0){

	$rows = $mysql->fetchArray($query);

	$statusweb = $rows[0]['status'];

}



switch($view){

	case 'main':

	if($statusweb == 1){

		$member->checkLogin();

		//header('location:' . CONFIG_PATH_SITE_MAIN . 'index.html');

		$incPathPre = CONFIG_PATH_MAIN_PREFETCH;

		$incPathPreAbsolute = CONFIG_PATH_MAIN_PREFETCH_ABSOLUTE;

		$incPathLang = CONFIG_PATH_MAIN_LANG;

		$incPathLangAbsolute = CONFIG_PATH_MAIN_LANG_ABSOLUTE;

	} else {

		include(CONFIG_PATH_SITE_ABSOLUTE . 'uc.php');

		exit();

	}

	break;

	case 'user':

	if($statusweb == 1){

		$member->checkLogin();

		$incPathView = CONFIG_PATH_USER;

		$incPathViewAbsolute = CONFIG_PATH_USER_ABSOLUTE;

		$incPathPre = CONFIG_PATH_USER_PREFETCH;

		$incPathPreAbsolute = CONFIG_PATH_USER_PREFETCH_ABSOLUTE;

		$incPathLang = CONFIG_PATH_USER_LANG;

		$incPathLangAbsolute = CONFIG_PATH_USER_LANG_ABSOLUTE;

	} else {

		include(CONFIG_PATH_SITE_ABSOLUTE . 'uc.php');

		exit();

	}

	break;

	case 'admin':

	$admin->checkLogin();

	$incPathView = CONFIG_PATH_ADMIN;

	$incPathViewAbsolute = CONFIG_PATH_ADMIN_ABSOLUTE;

	$incPathPre = CONFIG_PATH_ADMIN_PREFETCH;

	$incPathPreAbsolute = CONFIG_PATH_ADMIN_PREFETCH_ABSOLUTE;

	$incPathLang = CONFIG_PATH_ADMIN_LANG;

	$incPathLangAbsolute = CONFIG_PATH_ADMIN_LANG_ABSOLUTE;

	break;

	case 'supplier':

	$supplier->checkLogin();

	$incPathView = CONFIG_PATH_SUPPLIER;

	$incPathViewAbsolute = CONFIG_PATH_SUPPLIER_ABSOLUTE;

	$incPathPre = CONFIG_PATH_SUPPLIER_PREFETCH;

	$incPathPreAbsolute = CONFIG_PATH_SUPPLIER_PREFETCH_ABSOLUTE;

	$incPathLang = CONFIG_PATH_SUPPLIER_LANG;

	$incPathLangAbsolute = CONFIG_PATH_SUPPLIER_LANG_ABSOLUTE;

	break;

	case 'shop':

	//$supplier->checkLogin();

	$incPathView = CONFIG_PATH_SHOP;

	$incPathViewAbsolute = CONFIG_PATH_SHOP_ABSOLUTE;

	$incPathPre = CONFIG_PATH_SHOP_PREFETCH;

	$incPathPreAbsolute = CONFIG_PATH_SHOP_PREFETCH_ABSOLUTE;

	$incPathLang = CONFIG_PATH_SHOP_LANG;

	$incPathLangAbsolute = CONFIG_PATH_SHOP_LANG_ABSOLUTE;

	break;

	case 'chat':

	//$supplier->checkLogin();

	$incPathView = CONFIG_PATH_CHAT;

	$incPathViewAbsolute = CONFIG_PATH_CHAT_ABSOLUTE;

	$incPathPre = CONFIG_PATH_CHAT_PREFETCH;

	$incPathPreAbsolute = CONFIG_PATH_CHAT_PREFETCH_ABSOLUTE;

	$incPathLang = CONFIG_PATH_CHAT_LANG;

	$incPathLangAbsolute = CONFIG_PATH_CHAT_LANG_ABSOLUTE;

	break;

}

// Inlcude prefetch page to load any data that you want before anyting loads

$fileName = $page . '.php';



// Inlcude prefetch page to load any data that you want before anyting loads

$config_page_title = '';

$fileName = 'common.php';



if(file_exists($incPathModulesAbsolute . $fileName)){

	include($incPathModulesAbsolute . $fileName);

}



$fileName = $page . '.php';

if(file_exists($incPathModulesAbsolute . $fileName)){

	include($incPathModulesAbsolute . $fileName);

}



if(file_exists($incPathPreAbsolute . $fileName)){

	include($incPathPreAbsolute . $fileName);

}



$lang = 'en';

$sql = 'select * from ' . LANGUAGE_MASTER . ' where language_default=1';

$query = $mysql->query($sql);

if($mysql->rowCount($query) > 0){

	$rows = $mysql->fetchArray($query);

	$lang = $rows[0]['file_name'];

}



if($admin->isLogedin()){

	$lang = ($admin->getLang() != '') ? $admin->getLang() : $lang;

} else if($member->isLogedin()) {

	$lang = ($member->getLang() != '') ? $member->getLang() : $lang;

}



// Get default language fields before loading the page

$langfieldefault = $lang . '.php';

if(file_exists($incPathLangAbsolute . $langfieldefault)) {

	include($incPathLangAbsolute . $langfieldefault);

	//echo $incPathLangAbsolute . $langfieldefault;

}



// Get language fields before loading the page

$langFile = $lang . '/' . $fileName;

if(file_exists($incPathLangAbsolute . $langFile)){

	//echo($incPathLangAbsolute . $langFile);

	include($incPathLangAbsolute . $langFile);

}



//Load Language Object after fetting Language vals

$lang = new language($data); 





if(isset($_GET['lang']) && $_GET['lang'] != ""){
	$sLang = $_GET['lang'];

	if(isset($_COOKIE["fronLangCookie"]) && $_COOKIE["fronLangCookie"] != ""){

		$fronLangCookie = $_COOKIE["fronLangCookie"];

		if($fronLangCookie != $_GET['lang']){

			setcookie("fronLangCookie", $_GET['lang']);

			$fronLangCookie = $_COOKIE["fronLangCookie"];

		}

	}else{

		setcookie("fronLangCookie", $_GET['lang']);

		$fronLangCookie = $_COOKIE["fronLangCookie"];

	}

}else{
	if(isset($_COOKIE["fronLangCookie"]) && $_COOKIE["fronLangCookie"] != ""){
		/*echo 'set Cookie<br/>';

		$fronLangCookie = $_COOKIE["fronLangCookie"];

		if($fronLangCookie != $_GET['lang']){

			setcookie("fronLangCookie", $_GET['lang']);

			$fronLangCookie = $_COOKIE["fronLangCookie"];

		}*/

		$fronLangCookie = $_COOKIE["fronLangCookie"];

	}else{
		setcookie('fronLangCookie', CONFIG_DEFAULT_LANG);

		$fronLangCookie = $_COOKIE["fronLangCookie"] = CONFIG_DEFAULT_LANG;
	}

	$_GET['lang'] = $sLang = $fronLangCookie;

}


switch($view){

	case 'user':

	//case'shop':

	//case '':

	//case 'supplier':

	include(CONFIG_PATH_PANEL_ABSOLUTE . $view . '.php');

	break;

	case 'admin':

	include(CONFIG_PATH_PANEL_ABSOLUTE_ADMIN . $view . '.php');

	break;

	case 'supplier':

	include(CONFIG_PATH_PANEL_ABSOLUTE_SUPPLIER . $view . '.php');

	break;

	case 'shop':

	include(CONFIG_PATH_PANEL_ABSOLUTE_SHOP . $view . '.php');

	break;

	case 'chat':

	include(CONFIG_PATH_PANEL_ABSOLUTE_CHAT . $view . '.php');

	break;

	case 'cms':

	include(CONFIG_PATH_THEME_ABSOLUTE_NEW . 'main.php');

	break;

	default:

	include($incPathViewAbsolute . $view . '.php');

	break;

}

?>