<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	if (defined("DEMO"))
	{
		header("location:" . CONFIG_PATH_SITE_ADMIN . "config_settings.html?reply=" . urlencode('reply_com_demo'));
		exit();
	}
       // echo '<pre>';
//	var_dump($_POST);exit;
	$admin->checkLogin();
	$admin->reject();
	$validator->formValidateAdmin('config_reseller_edit_1488855448');
	
	if($_FILES['logo']['name'] != "")
	{
		// Generate File name
		$path = $_FILES['logo']['name'];
		$ext = pathinfo($path, PATHINFO_EXTENSION);
		$imgName = 'logo.png';
		
		
		// Upload file path
		$uploadfile = CONFIG_PATH_THEME_ABSOLUTE . "images/" . $imgName;
		
		if (!move_uploaded_file($_FILES['logo']['tmp_name'], $uploadfile)) 
		{
			header("location:" . CONFIG_PATH_SITE_ADMIN . "config_settings.html?reply=" . urlencode('reply_cannot_upload_licence'));
			exit();
		}
	}
	//Upload Licence
	
	$CONFIG_REPAIR_MODE = $request->PostInt('CONFIG_REPAIR_MODE');
        $CONFIG_REPAIR_MODE=0;
	$CONFIG_THEME = $request->PostStr('CONFIG_THEME');
	$CONFIG_PANEL = $request->PostStr('CONFIG_PANEL');
        $CONFIG_THEME='imeipk';
//	$timezone = $request->PostInt('timezone');
//	$sql = 'update ' . TIMEZONE_MASTER . ' set is_default=0';
//	$mysql->query($sql);
//	$sql = 'update ' . TIMEZONE_MASTER . ' set is_default=1 where id=' . $timezone;
//	$mysql->query($sql);
//        if ($_POST['show_price']=="1") {
//            $set_price=1;
//    
//}
//else  { $set_price=0;}
//	$sql1 = 'update ' . SMTP_CONFIG . ' set show_price='.$set_price;
//       // echo $sql1;exit;
//	$mysql->query($sql1);
	
        
	

	$GMAIL_USERNAME_ADMIN = $request->PostStr('GMAIL_USERNAME_ADMIN');
	$GMAIL_PASSWORD_ADMIN = $request->PostStr('GMAIL_PASSWORD_ADMIN');
	$GMAIL_USERNAME = $request->PostStr('GMAIL_USERNAME');
	$GMAIL_PASSWORD = $request->PostStr('GMAIL_PASSWORD');
	$GMAIL_USERNAME_CONTACT = $request->PostStr('GMAIL_USERNAME_CONTACT');
	$GMAIL_PASSWORD_CONTACT = $request->PostStr('GMAIL_PASSWORD_CONTACT');

	$CONFIG_PATH_SITE_ADMIN = $request->PostStr('CONFIG_PATH_SITE_ADMIN');
	$CONFIG_PATH_SITE_SUPPLIER = $request->PostStr('CONFIG_PATH_SITE_SUPPLIER');
	$CONFIG_PATH_SITE_USER = $request->PostStr('CONFIG_PATH_SITE_USER');
//	$CONFIG_ORDER_PAGE_SIZE = $request->PostStr('CONFIG_ORDER_PAGE_SIZE');
	$CONFIG_PATH_SITE_ADMIN='admin';
        $CONFIG_PATH_SITE_SUPPLIER='supplier';
        $CONFIG_PATH_SITE_USER='user';
	
//	$CONFIG_DOMAIN = $request->PostStr('CONFIG_DOMAIN');
//	$CONFIG_SITE_TITLE = $request->PostStr('CONFIG_SITE_TITLE');
//	$CONFIG_SITE_NAME = $request->PostStr('CONFIG_SITE_NAME');
//	$CONFIG_EMAIL_ADMIN = $request->PostStr('CONFIG_EMAIL_ADMIN');
	$CONFIG_EMAIL = $request->PostStr('CONFIG_EMAIL');
	$CONFIG_EMAIL_CONTACT = $request->PostStr('CONFIG_EMAIL_CONTACT');

	$admin_old = trim(str_replace(CONFIG_PATH_SITE, '', CONFIG_PATH_SITE_ADMIN), '//');
	if(is_dir(CONFIG_PATH_SITE_ABSOLUTE . $admin_old . '/'))
	{
		rename(CONFIG_PATH_SITE_ABSOLUTE . $admin_old . '/', CONFIG_PATH_SITE_ABSOLUTE . $CONFIG_PATH_SITE_ADMIN . '/');
	}
	
	$supplier_old = trim(str_replace(CONFIG_PATH_SITE, '', CONFIG_PATH_SITE_SUPPLIER), '//');
	if(is_dir(CONFIG_PATH_SITE_ABSOLUTE . $supplier_old . '/'))
	{
		rename(CONFIG_PATH_SITE_ABSOLUTE . $supplier_old . '/', CONFIG_PATH_SITE_ABSOLUTE . $CONFIG_PATH_SITE_SUPPLIER . '/');
	}
	
	$user_old = trim(str_replace(CONFIG_PATH_SITE, '', CONFIG_PATH_SITE_USER), '//');
	if(is_dir(CONFIG_PATH_SITE_ABSOLUTE . $user_old . '/'))
	{
		rename(CONFIG_PATH_SITE_ABSOLUTE . $user_old . '/', CONFIG_PATH_SITE_ABSOLUTE . $CONFIG_PATH_SITE_USER . '/');
	}
	
	
	$connect_code='<?php
	define("CONFIG_VERSION", "' . CONFIG_VERSION . '");
	define("CONFIG_VERSION_ID", "' . CONFIG_VERSION_ID . '");
	
	define("CONFIG_REPAIR_MODE", "' . $CONFIG_REPAIR_MODE . '");

	define("NEW_LINE", "\n");

	
	define("GMAIL_USERNAME_ADMIN", "' . $GMAIL_USERNAME_ADMIN . '");
	define("GMAIL_PASSWORD_ADMIN", "' . $GMAIL_PASSWORD_ADMIN . '");
	define("GMAIL_USERNAME", "' . $GMAIL_USERNAME . '");
	define("GMAIL_PASSWORD", "' . $GMAIL_PASSWORD . '");
	define("GMAIL_USERNAME_CONTACT", "' . $GMAIL_USERNAME_CONTACT . '");
	define("GMAIL_PASSWORD_CONTACT", "' . $GMAIL_PASSWORD_CONTACT . '");
	


	define("CONFIG_THEME", "' . $CONFIG_THEME .  '/");
	define("CONFIG_PANEL", "Dark/");
        define("CONFIG_PANEL_ADMIN", "'.$CONFIG_PANEL.'/");
        define("CONFIG_PANEL_SUPPLIER", "'.$CONFIG_PANEL.'/");
        define("CONFIG_PANEL_SHOP", "'.$CONFIG_PANEL.'/");
        define("CONFIG_PANEL_CHAT", "Dark/");
         

	define("CONFIG_PATH_SITE_ADMIN", CONFIG_PATH_SITE . "' . $CONFIG_PATH_SITE_ADMIN .  '/");
	define("CONFIG_PATH_SITE_SUPPLIER", CONFIG_PATH_SITE . "' . $CONFIG_PATH_SITE_SUPPLIER .  '/");
	define("CONFIG_PATH_SITE_USER", CONFIG_PATH_SITE . "' . $CONFIG_PATH_SITE_USER .  '/");
	define("CONFIG_ORDER_PAGE_SIZE", "' . CONFIG_ORDER_PAGE_SIZE . '");

	
	define("CONFIG_DOMAIN", "' . CONFIG_DOMAIN . '");
	define("CONFIG_SITE_TITLE", "' . CONFIG_SITE_TITLE . '");
	define("CONFIG_SITE_NAME", "' . CONFIG_SITE_NAME . '");
	define("CONFIG_EMAIL_ADMIN", "' . CONFIG_EMAIL_ADMIN . '");
	define("CONFIG_EMAIL", "' . $CONFIG_EMAIL . '");
	define("CONFIG_EMAIL_CONTACT", "' . $CONFIG_EMAIL_CONTACT . '");
?>';

	if(!is_writable(CONFIG_PATH_SITE_ABSOLUTE . "config/_settings.php"))
	{
		header("location:" . CONFIG_PATH_SITE . $CONFIG_PATH_SITE_ADMIN . "/config_settings.html?reply=" . urlencode('reply_cant_change_settings'));
		exit();
	}
	else
	{
		chmod(CONFIG_PATH_SITE_ABSOLUTE . "config/_settings.php", 0777);
		$fp = fopen(CONFIG_PATH_SITE_ABSOLUTE . "config/_settings.php", 'wb');
		fwrite($fp,$connect_code);
		fclose($fp);
		chmod(CONFIG_PATH_SITE_ABSOLUTE . "config/_settings.php", 0666);
	}
	
	

	
	
	header("location:" . CONFIG_PATH_SITE . $CONFIG_PATH_SITE_ADMIN . "/config_settings.html?reply=" . urlencode('reply_successfull'));
	exit();	
?>