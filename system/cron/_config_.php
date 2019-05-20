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
	define("CONFIG_PATH_SITE", '/nxt/gsmf/');
	define("CONFIG_PATH_ROOT", $_SERVER['DOCUMENT_ROOT']);
	//define("CONFIG_PATH_ROOT", dirname(__FILE__));
	define("CONFIG_PATH_SITE_ABSOLUTE", CONFIG_PATH_ROOT . CONFIG_PATH_SITE);

	
	
	
	// Database Settings
	require_once("../../config/_db.php");
	require_once("../../config/_settings.php");
	
	
	
	// Extra Path
	define("CONFIG_PATH_EXTRA", CONFIG_PATH_SITE . "extra/");
	define("CONFIG_PATH_EXTRA_ABSOLUTE", CONFIG_PATH_SITE_ABSOLUTE . "extra/");

	// Core Paths
	define("CONFIG_PATH_CORE", CONFIG_PATH_SITE . 'core/');
	define("CONFIG_PATH_CORE_ABSOLUTE", CONFIG_PATH_ROOT . CONFIG_PATH_CORE);
	

	// Main Theme
	// (Defined in _config_user) define("CONFIG_THEME", 'fars/');
	define("CONFIG_PATH_THEME", CONFIG_PATH_SITE . 'themes/' . CONFIG_THEME);
	define("CONFIG_PATH_THEME_ABSOLUTE", CONFIG_PATH_SITE_ABSOLUTE . 'themes/' . CONFIG_THEME);
	define("CONFIG_PATH_THEME_IMAGES", CONFIG_PATH_SITE . 'themes/' . CONFIG_THEME . 'images/');
	define("CONFIG_PATH_THEME_IMAGES_ABSOLUTE", CONFIG_PATH_SITE_ABSOLUTE . 'themes/' . CONFIG_THEME . 'images/');

	
	// System Path
	define("CONFIG_PATH_SYSTEM", CONFIG_PATH_SITE . 'system/');
	define("CONFIG_PATH_SYSTEM_ABSOLUTE", CONFIG_PATH_SITE_ABSOLUTE . 'system/');

	// System Path
	define("CONFIG_PATH_EXTERNAL", CONFIG_PATH_SYSTEM . 'external/');
	define("CONFIG_PATH_EXTERNAL_ABSOLUTE", CONFIG_PATH_SYSTEM_ABSOLUTE . 'external/');

	// Main Paths
	define("CONFIG_MAIN", CONFIG_THEME . 'main/');
	define("CONFIG_PATH_MAIN", CONFIG_PATH_SITE . 'themes/' . CONFIG_MAIN);
	define("CONFIG_PATH_MAIN_ABSOLUTE", CONFIG_PATH_SITE_ABSOLUTE . 'themes/' . CONFIG_MAIN);
	define("CONFIG_PATH_MAIN_LANG", CONFIG_PATH_MAIN . CONFIG_MAIN . 'themes/' . 'lang/');
	define("CONFIG_PATH_MAIN_LANG_ABSOLUTE", CONFIG_PATH_SITE_ABSOLUTE . 'themes/' . CONFIG_MAIN . 'lang/');
	define("CONFIG_PATH_MAIN_PREFETCH", CONFIG_PATH_MAIN . CONFIG_MAIN . 'themes/' . 'prefetch/');
	define("CONFIG_PATH_MAIN_PREFETCH_ABSOLUTE", CONFIG_PATH_SITE_ABSOLUTE . 'themes/' . CONFIG_MAIN . 'prefetch/');
	
	define("CONFIG_PATH_IMAGES", CONFIG_PATH_SITE . 'images/');
	
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
	
	
	
	
	// Panels Paths
	// (Defined in _config_user) define("CONFIG_PANEL", 'gray/');   // Use '/' at end
	define("CONFIG_PATH_PANEL", CONFIG_PATH_SITE . 'panel_themes/' . CONFIG_PANEL);
	define("CONFIG_PATH_PANEL_ABSOLUTE", CONFIG_PATH_SITE_ABSOLUTE . 'panel_themes/' . CONFIG_PANEL);
	
	
	
	require_once('../../config/_db_tables.php');
?>