<?php

	/**

	* @copyright Copyright (C) 2008-13 GSM Freedom. www.gsmfreedom.com. All rights reserved.

	* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php

	*/
	 

	// no direct access

	defined("_VALID_ACCESS") or die("Restricted Access");

	

	//Please comment this line before uploading to the server

	//define("debug",1);

	//define("net_unavail",1);

	//define("DEMO",1);

	

	define('CR', "\r\n");

	

	// Site Paths

	define("CONFIG_PATH_SITE", '/');

	define("CONFIG_PATH_ROOT", $_SERVER['DOCUMENT_ROOT']);

	//define("CONFIG_PATH_ROOT", dirname(__FILE__));

	define("CONFIG_PATH_SITE_ABSOLUTE", CONFIG_PATH_ROOT . CONFIG_PATH_SITE);



	

	

	

	// Database Settings

	require_once("config/_db.php");

	require_once("config/_settings.php");

	

	

	

	// Extra Path

	define("CONFIG_PATH_EXTRA", CONFIG_PATH_SITE . "assets/");

	define("CONFIG_PATH_EXTRA_ABSOLUTE", CONFIG_PATH_SITE_ABSOLUTE . "assets/");



	// Core Paths

	define("CONFIG_PATH_CORE", CONFIG_PATH_SITE . 'core/');

	define("CONFIG_PATH_CORE_ABSOLUTE", CONFIG_PATH_ROOT . CONFIG_PATH_CORE);

	

	// Assets Path

	define("CONFIG_PATH_ASSETS", CONFIG_PATH_SITE . "assets/");

	define("CONFIG_PATH_ASSETS_ABSOLUTE", CONFIG_PATH_SITE_ABSOLUTE . "assets/");

	



	// Main Theme

	define("CONFIG_PATH_THEME", CONFIG_PATH_SITE . 'public/views/' . CONFIG_THEME);

	define("CONFIG_PATH_THEME_ABSOLUTE", CONFIG_PATH_SITE_ABSOLUTE . 'public/views/' . CONFIG_THEME);
	
	//new theme
	define("CONFIG_PATH_THEME_NEW", CONFIG_PATH_SITE . 'public/views/cms/');
	define("CONFIG_PATH_THEME_ABSOLUTE_NEW", CONFIG_PATH_SITE_ABSOLUTE . 'public/views/cms/');





	define("CONFIG_PATH_LOGS_ABSOLUTE", CONFIG_PATH_SITE_ABSOLUTE . 'logs');



    //Image Path

	define("CONFIG_PATH_IMAGES", CONFIG_PATH_SITE . 'public/views/' . CONFIG_THEME . 'images/');

	define("CONFIG_PATH_IMAGES_ABSOLUTE", CONFIG_PATH_SITE_ABSOLUTE . 'public/views/' . CONFIG_THEME . 'images/');



	

	// System Path

	define("CONFIG_PATH_SYSTEM", CONFIG_PATH_SITE . 'system/');

	define("CONFIG_PATH_SYSTEM_ABSOLUTE", CONFIG_PATH_SITE_ABSOLUTE . 'system/');

	

	// Main Paths

	define("CONFIG_PATH_MODULES", CONFIG_PATH_SITE . 'public/models/');

	define("CONFIG_PATH_MODULES_ABSOLUTE", CONFIG_PATH_SITE_ABSOLUTE . 'public/models/');





	// System Path

	define("CONFIG_PATH_EXTERNAL", CONFIG_PATH_SYSTEM . 'external/');

	define("CONFIG_PATH_EXTERNAL_ABSOLUTE", CONFIG_PATH_SYSTEM_ABSOLUTE . 'external/');



	// Main Paths

	define("CONFIG_MAIN", CONFIG_THEME . 'main/');

	define("CONFIG_PATH_MAIN", CONFIG_PATH_SITE . 'public/views/' . CONFIG_MAIN);

	define("CONFIG_PATH_MAIN_ABSOLUTE", CONFIG_PATH_SITE_ABSOLUTE . 'public/views/' . CONFIG_MAIN);

	define("CONFIG_PATH_MAIN_LANG", CONFIG_PATH_MAIN . CONFIG_MAIN . 'public/views/' . 'lang/');

	define("CONFIG_PATH_MAIN_LANG_ABSOLUTE", CONFIG_PATH_SITE_ABSOLUTE . 'public/views/' . CONFIG_MAIN . 'lang/');

	define("CONFIG_PATH_MAIN_PREFETCH", CONFIG_PATH_MAIN . CONFIG_MAIN . 'public/views/' . 'prefetch/');

	define("CONFIG_PATH_MAIN_PREFETCH_ABSOLUTE", CONFIG_PATH_SITE_ABSOLUTE . 'public/views/' . CONFIG_MAIN . 'prefetch/');

	

	// Admin Paths

	define("CONFIG_ADMIN", 'panels/admin/');

	define("CONFIG_PATH_ADMIN", CONFIG_PATH_SITE . CONFIG_ADMIN);

	define("CONFIG_PATH_ADMIN_ABSOLUTE", CONFIG_PATH_SITE_ABSOLUTE . CONFIG_ADMIN);

	define("CONFIG_PATH_ADMIN_LANG", CONFIG_PATH_ADMIN . CONFIG_ADMIN . 'lang/');

	define("CONFIG_PATH_ADMIN_LANG_ABSOLUTE", CONFIG_PATH_SITE_ABSOLUTE . CONFIG_ADMIN . 'lang/');

	define("CONFIG_PATH_ADMIN_PREFETCH", CONFIG_PATH_ADMIN . CONFIG_ADMIN . 'prefetch/');

	define("CONFIG_PATH_ADMIN_PREFETCH_ABSOLUTE", CONFIG_PATH_SITE_ABSOLUTE . CONFIG_ADMIN . 'prefetch/');

	

	

	// Supplier Paths

	define("CONFIG_SUPPLIER", 'panels/supplier/');

	define("CONFIG_PATH_SUPPLIER", CONFIG_PATH_SITE . CONFIG_SUPPLIER);

	define("CONFIG_PATH_SUPPLIER_ABSOLUTE", CONFIG_PATH_SITE_ABSOLUTE . CONFIG_SUPPLIER);

	define("CONFIG_PATH_SUPPLIER_LANG", CONFIG_PATH_ADMIN . CONFIG_SUPPLIER . 'lang/');

	define("CONFIG_PATH_SUPPLIER_LANG_ABSOLUTE", CONFIG_PATH_SITE_ABSOLUTE . CONFIG_SUPPLIER . 'lang/');

	define("CONFIG_PATH_SUPPLIER_PREFETCH", CONFIG_PATH_ADMIN . CONFIG_SUPPLIER . 'prefetch/');

	define("CONFIG_PATH_SUPPLIER_PREFETCH_ABSOLUTE", CONFIG_PATH_SITE_ABSOLUTE . CONFIG_SUPPLIER . 'prefetch/');

	

	

	// User Paths

	define("CONFIG_USER", 'panels/user/');

	define("CONFIG_PATH_USER", CONFIG_PATH_SITE . CONFIG_USER);

	define("CONFIG_PATH_USER_ABSOLUTE", CONFIG_PATH_SITE_ABSOLUTE . CONFIG_USER);

	define("CONFIG_PATH_USER_LANG", CONFIG_PATH_ADMIN . CONFIG_USER . 'lang/');

	define("CONFIG_PATH_USER_LANG_ABSOLUTE", CONFIG_PATH_SITE_ABSOLUTE . CONFIG_USER . 'lang/');

	define("CONFIG_PATH_USER_PREFETCH", CONFIG_PATH_ADMIN . CONFIG_USER . 'prefetch/');

	define("CONFIG_PATH_USER_PREFETCH_ABSOLUTE", CONFIG_PATH_SITE_ABSOLUTE . CONFIG_USER . 'prefetch/');

        

        //shop paths

        define("CONFIG_SHOP", 'panels/shop/');

	define("CONFIG_PATH_SHOP", CONFIG_PATH_SITE . CONFIG_SHOP);

	define("CONFIG_PATH_SHOP_ABSOLUTE", CONFIG_PATH_SITE_ABSOLUTE . CONFIG_SHOP);

	define("CONFIG_PATH_SHOP_LANG", CONFIG_PATH_ADMIN . CONFIG_SHOP . 'lang/');

	define("CONFIG_PATH_SHOP_LANG_ABSOLUTE", CONFIG_PATH_SITE_ABSOLUTE . CONFIG_SHOP . 'lang/');

	define("CONFIG_PATH_SHOP_PREFETCH", CONFIG_PATH_ADMIN . CONFIG_SHOP . 'prefetch/');

	define("CONFIG_PATH_SHOP_PREFETCH_ABSOLUTE", CONFIG_PATH_SITE_ABSOLUTE . CONFIG_SHOP . 'prefetch/');

	

	

	 //chat paths

        define("CONFIG_CHAT", 'panels/chat/');

	define("CONFIG_PATH_CHAT", CONFIG_PATH_SITE . CONFIG_CHAT);

	define("CONFIG_PATH_CHAT_ABSOLUTE", CONFIG_PATH_SITE_ABSOLUTE . CONFIG_CHAT);

	define("CONFIG_PATH_CHAT_LANG", CONFIG_PATH_ADMIN . CONFIG_CHAT . 'lang/');

	define("CONFIG_PATH_CHAT_LANG_ABSOLUTE", CONFIG_PATH_SITE_ABSOLUTE . CONFIG_CHAT . 'lang/');

	define("CONFIG_PATH_CHAT_PREFETCH", CONFIG_PATH_ADMIN . CONFIG_CHAT . 'prefetch/');

	define("CONFIG_PATH_CHAT_PREFETCH_ABSOLUTE", CONFIG_PATH_SITE_ABSOLUTE . CONFIG_CHAT . 'prefetch/');

	

	

	// Panels Paths

	// (Defined in _config_user) define("CONFIG_PANEL", 'gray/');   // Use '/' at end

	define("CONFIG_PATH_PANEL", CONFIG_PATH_SITE . 'panel_themes/' . CONFIG_PANEL);

	define("CONFIG_PATH_PANEL_ABSOLUTE", CONFIG_PATH_SITE_ABSOLUTE . 'panel_themes/' . CONFIG_PANEL);

        

        define("CONFIG_PATH_PANEL_ADMIN", CONFIG_PATH_SITE . 'panel_themes/' . CONFIG_PANEL_ADMIN);

	define("CONFIG_PATH_PANEL_ABSOLUTE_ADMIN", CONFIG_PATH_SITE_ABSOLUTE . 'panel_themes/' . CONFIG_PANEL_ADMIN);

        

        define("CONFIG_PATH_PANEL_SUPPLIER", CONFIG_PATH_SITE . 'panel_themes/' . CONFIG_PANEL_SUPPLIER);

	define("CONFIG_PATH_PANEL_ABSOLUTE_SUPPLIER", CONFIG_PATH_SITE_ABSOLUTE . 'panel_themes/' . CONFIG_PANEL_SUPPLIER);

	

        define("CONFIG_PATH_PANEL_SHOP", CONFIG_PATH_SITE . 'panel_themes/' . CONFIG_PANEL_SHOP);

	define("CONFIG_PATH_PANEL_ABSOLUTE_SHOP", CONFIG_PATH_SITE_ABSOLUTE . 'panel_themes/' . CONFIG_PANEL_SHOP);

        

        define("CONFIG_PATH_PANEL_CHAT", CONFIG_PATH_SITE . 'panel_themes/' . CONFIG_PANEL_CHAT);

	define("CONFIG_PATH_PANEL_ABSOLUTE_CHAT", CONFIG_PATH_SITE_ABSOLUTE . 'panel_themes/' . CONFIG_PANEL_CHAT);

	

	require_once('config/_db_tables.php');

	define('IPHONE_CHECK_API','http://iphonedb.com/imei/zouni/icheck.php?imei=');

	define('IPHONE_CHECK_TOOL_ID',48);

	define('ICLOUD_CHECK_API','http://iphonedb.com/imei/zouni/icheck.php?imei=');

	define('ICLOUD_CHECK_TOOL_ID',67);

	define('MAX_CUSTOM_FIELD_LENGTH_FOR_SERVICE',20);

	define("CONFIG_HOME", 'localhost/wordpress');



//test comment

?>