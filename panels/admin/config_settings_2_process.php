<?php

function strafter($string, $substring) {
    $pos = strpos($string, $substring);
    if ($pos === false)
        return $string;
    else
        return(substr($string, $pos + strlen($substring)));
}

?>
<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

if (defined("DEMO")) {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "config_settings.html?reply=" . urlencode('reply_com_demo'));
    exit();
}
// echo '<pre>';
//	var_dump($_POST);exit;
$admin->checkLogin();
$admin->reject();
$validator->formValidateAdmin('config_reseller_edit_1488855448');

if ($_FILES['logo']['name'] != "") {
    // Generate File name
    $path = $_FILES['logo']['name'];
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    $imgName = 'logo.png';


    // Upload file path
    $uploadfile = CONFIG_PATH_THEME_ABSOLUTE . "images/" . $imgName;

    if (!move_uploaded_file($_FILES['logo']['tmp_name'], $uploadfile)) {
        header("location:" . CONFIG_PATH_SITE_ADMIN . "config_settings.html?reply=" . urlencode('reply_cannot_upload_licence'));
        exit();
    }
}
//Upload Licence

$CONFIG_REPAIR_MODE = $request->PostInt('CONFIG_REPAIR_MODE');
$CONFIG_THEME = $request->PostStr('CONFIG_THEME');
$CONFIG_PANEL = $request->PostStr('CONFIG_PANEL');
$chat_code = $_POST['chat_code'];
//echo $chat_code;exit;
if($chat_code=="")
    $chat_code="";
$timezone = $request->PostInt('timezone');
//$d_lang = $request->PostInt('d_lang');
//
//if ($d_lang != "") {
//
//    $sql = 'update ' . LANGUAGE_MASTER . ' set `language_default`=0';
//    $mysql->query($sql);
//    $sql = 'update ' . LANGUAGE_MASTER . ' set `language_default`=1  where id=' . $d_lang;
//    $mysql->query($sql);
//}


$sql = 'update ' . TIMEZONE_MASTER . ' set is_default=0';
$mysql->query($sql);
$sql = 'update ' . TIMEZONE_MASTER . ' set is_default=1 where id=' . $timezone;
$mysql->query($sql);
//echo $_POST['show_price'];
if ($_POST['show_price'] == "on") {
    $set_price = 1;
} else {
    $set_price = 0;
}



if ($_POST['switch-admin_notes'] == "on") {
    $a_notes = 1;
} else {
    $a_notes = 0;
}

// auto reg


if ($_POST['switch-auto_reg'] == "on") {
    $auto_reg = 1;
} else {
    $auto_reg = 0;
}

$sql = 'update  ' . CONFIG_MASTER . ' set value_int=' . $mysql->getInt($auto_reg) . ' WHERE field=\'AUTO_REGISTRATION\'';
$query = $mysql->query($sql);


if ($_POST['switch-user_notes'] == "on") {
    $u_notes = 1;
} else {
    $u_notes = 0;
}

if ($_POST['switch-ter_con'] == "on") {
    $term_cond = 1;
} else {
    $term_cond = 0;
}


//$sql1 = 'update ' . SMTP_CONFIG . ' set show_price=' . $set_price;
$sqlunotes='update ' . CONFIG_MASTER . ' set value= ' . $u_notes . ' where field="USER_NOTES"';
// echo $sql1;exit;
$mysql->query($sqlunotes);

$sqlanotes='update ' . CONFIG_MASTER . ' set value= ' . $a_notes . ' where field="ADMIN_NOTES"';
// echo $sql1;exit;
$mysql->query($sqlanotes);

if($term_cond==1)
{
     $sqlanotes='update ' . CONFIG_MASTER . ' set value_int= ' . $term_cond . ' , value=' . $mysql->quote($_POST['termcond']) . ' where field="TER_CON"';
 //echo $sqlanotes;exit;
$mysql->query($sqlanotes);
    
}
else
{
    $sqlanotes='update ' . CONFIG_MASTER . ' set value_int= ' . $term_cond . ' where field="TER_CON"';
// echo $sql1;exit;
$mysql->query($sqlanotes);
    
}
$textarea_value = $_POST['detail'];
$lines = explode("\n", $textarea_value);
  $inv_log = 'update '.INVOICE_EDIT.'  set  detail="'.$lines[0].'",detail2="'.$lines[1].'",detail3="'.$lines[2].'",detail4="'.$lines[3].'" '.$qry;
   // echo $inv_log;exit;    
    $query_inv = $mysql->query($inv_log);
 

if ($_POST['switch-tab'] == "on") {
    $switch_tab = 1;
} else {
    $switch_tab = 0;
}

$oldtabval=$_POST['mtaboldval'];

//echo $set_price;exit;
$sql1 = 'update ' . SMTP_CONFIG . ' set show_price=' . $set_price;
// echo $sql1;exit;
$mysql->query($sql1);

//$sql1 = 'update ' . SMTP_CONFIG . ' set chat_code=' . $mysql->quote($chat_code);
//$mysql->query($sql1);

$sql1 = 'update ' . ADMIN_MASTER . ' set is_tabbed=' . $switch_tab.' where id='.$admin->getUserId();;
// echo $sql1;exit;
$mysql->query($sql1);


$f_count = count($_POST['f_name']);
$GMAIL_USERNAME_ADMIN = $request->PostStr('GMAIL_USERNAME_ADMIN');
$GMAIL_PASSWORD_ADMIN = $request->PostStr('GMAIL_PASSWORD_ADMIN');
$GMAIL_USERNAME = $request->PostStr('GMAIL_USERNAME');
$GMAIL_PASSWORD = $request->PostStr('GMAIL_PASSWORD');
$GMAIL_USERNAME_CONTACT = $request->PostStr('GMAIL_USERNAME_CONTACT');
$GMAIL_PASSWORD_CONTACT = $request->PostStr('GMAIL_PASSWORD_CONTACT');

$CONFIG_PATH_SITE_ADMIN = $request->PostStr('CONFIG_PATH_SITE_ADMIN');
$CONFIG_PATH_SITE_SUPPLIER = $request->PostStr('CONFIG_PATH_SITE_SUPPLIER');
$CONFIG_PATH_SITE_USER = $request->PostStr('CONFIG_PATH_SITE_USER');
$CONFIG_ORDER_PAGE_SIZE = $request->PostStr('CONFIG_ORDER_PAGE_SIZE');
$CONFIG_WRONG_PASSWORD_COUNTER = $request->PostStr('CONFIG_WRONG_PASSWORD_COUNTER');

$CONFIG_FB_LINK = $request->PostStr('CONFIG_FB_LINK');
$CONFIG_TW_LINK = $request->PostStr('CONFIG_TW_LINK');
$CONFIG_GP_LINK = $request->PostStr('CONFIG_GP_LINK');
$CONFIG_PH_NO = $request->PostStr('CONFIG_PH_NO');

$CONFIG_DOMAIN = $request->PostStr('CONFIG_DOMAIN');
$CONFIG_SITE_TITLE = $request->PostStr('CONFIG_SITE_TITLE');
$CONFIG_SITE_NAME = $request->PostStr('CONFIG_SITE_NAME');
$CONFIG_EMAIL_ADMIN = $request->PostStr('CONFIG_EMAIL_ADMIN');
$CONFIG_EMAIL = $request->PostStr('CONFIG_EMAIL');
$CONFIG_EMAIL_CONTACT = $request->PostStr('CONFIG_EMAIL_CONTACT');

$CONFIG_TRANS_GOOGLE_KEY = $request->PostStr('CONFIG_TRANS_GOOGLE_KEY');
$CONFIG_DEFAULT_LANG = $request->PostStr('CONFIG_DEFAULT_LANG');
 $new_url = $_POST['a_url'];
    $old_url = $_POST['old_a_url'];
$CONFIG_PATH_SITE_ADMIN=$new_url.'/';

//	$admin_old = trim(str_replace(CONFIG_PATH_SITE, '', CONFIG_PATH_SITE_ADMIN), '//');
//	if(is_dir(CONFIG_PATH_SITE_ABSOLUTE . $admin_old . '/'))
//	{
//		rename(CONFIG_PATH_SITE_ABSOLUTE . $admin_old . '/', CONFIG_PATH_SITE_ABSOLUTE . $CONFIG_PATH_SITE_ADMIN . '/');
//	}
//	
//	$supplier_old = trim(str_replace(CONFIG_PATH_SITE, '', CONFIG_PATH_SITE_SUPPLIER), '//');
//	if(is_dir(CONFIG_PATH_SITE_ABSOLUTE . $supplier_old . '/'))
//	{
//		rename(CONFIG_PATH_SITE_ABSOLUTE . $supplier_old . '/', CONFIG_PATH_SITE_ABSOLUTE . $CONFIG_PATH_SITE_SUPPLIER . '/');
//	}
//	
//	$user_old = trim(str_replace(CONFIG_PATH_SITE, '', CONFIG_PATH_SITE_USER), '//');
//	if(is_dir(CONFIG_PATH_SITE_ABSOLUTE . $user_old . '/'))
//	{
//		rename(CONFIG_PATH_SITE_ABSOLUTE . $user_old . '/', CONFIG_PATH_SITE_ABSOLUTE . $CONFIG_PATH_SITE_USER . '/');
//	}
//	
//echo strlen(CONFIG_PATH_SITE);
//$CONFIG_PATH_SITE_ADMIN = substr(CONFIG_PATH_SITE_ADMIN, strlen(CONFIG_PATH_SITE));
$CONFIG_PATH_SITE_SUPPLIER = substr(CONFIG_PATH_SITE_SUPPLIER, strlen(CONFIG_PATH_SITE));
$CONFIG_PATH_SITE_USER = substr(CONFIG_PATH_SITE_USER, strlen(CONFIG_PATH_SITE));
$CONFIG_PATH_SITE_SHOP = substr(CONFIG_PATH_SITE_SHOP, strlen(CONFIG_PATH_SITE));
$CONFIG_PATH_SITE_CHAT = substr(CONFIG_PATH_SITE_CHAT, strlen(CONFIG_PATH_SITE));
// echo $CONFIG_PATH_SITE_ADMIN;exit;
$connect_code = '<?php
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
	


	define("CONFIG_THEME", "' . CONFIG_THEME . '");
	define("CONFIG_PANEL", "Dark/");
        define("CONFIG_PANEL_ADMIN", "' . CONFIG_PANEL . '");
        define("CONFIG_PANEL_SUPPLIER", "' . CONFIG_PANEL . '");
        define("CONFIG_PANEL_SHOP", "' . CONFIG_PANEL . '");
        define("CONFIG_PANEL_CHAT", "' . CONFIG_PANEL . '");
        

	define("CONFIG_PATH_SITE_ADMIN", CONFIG_PATH_SITE . "' . $CONFIG_PATH_SITE_ADMIN . '");
	define("CONFIG_PATH_SITE_SUPPLIER", CONFIG_PATH_SITE . "' . $CONFIG_PATH_SITE_SUPPLIER . '");
	define("CONFIG_PATH_SITE_USER", CONFIG_PATH_SITE . "' . $CONFIG_PATH_SITE_USER . '");
        define("CONFIG_PATH_SITE_CHAT", CONFIG_PATH_SITE . "' . $CONFIG_PATH_SITE_CHAT . '");
	define("CONFIG_ORDER_PAGE_SIZE", "' . $CONFIG_ORDER_PAGE_SIZE . '");
        define("CONFIG_WRONG_PASSWORD_COUNTER", "' . $CONFIG_WRONG_PASSWORD_COUNTER . '");
            
	
	define("CONFIG_DOMAIN", "' . $CONFIG_DOMAIN . '");
	define("CONFIG_SITE_TITLE", "' . $CONFIG_SITE_TITLE . '");
	define("CONFIG_SITE_NAME", "' . $CONFIG_SITE_NAME . '");

	define("CONFIG_EMAIL", "' . CONFIG_EMAIL . '");
	define("CONFIG_EMAIL_CONTACT", "' . CONFIG_EMAIL_CONTACT . '");
        define("CONFIG_FB_LINK", "' . $CONFIG_FB_LINK . '");
        define("CONFIG_TW_LINK", "' . $CONFIG_TW_LINK . '");
        define("CONFIG_GP_LINK", "' . $CONFIG_GP_LINK . '");
        define("CONFIG_PH_NO", "' . $CONFIG_PH_NO . '");    
	define("CONFIG_TRANS_GOOGLE_KEY", "' . $CONFIG_TRANS_GOOGLE_KEY . '");
	define("CONFIG_DEFAULT_LANG", "' . $CONFIG_DEFAULT_LANG . '");	
	
?>';

if (!is_writable(CONFIG_PATH_SITE_ABSOLUTE . "config/_settings.php")) {
    header("location:" . CONFIG_PATH_SITE . CONFIG_PATH_SITE_ADMIN . "/config_settings.html?reply=" . urlencode('reply_cant_change_settings'));
    exit();
} else {
    chmod(CONFIG_PATH_SITE_ABSOLUTE . "config/_settings.php", 0777);
    $fp = fopen(CONFIG_PATH_SITE_ABSOLUTE . "config/_settings.php", 'wb');
    fwrite($fp, $connect_code);
    fclose($fp);
    chmod(CONFIG_PATH_SITE_ABSOLUTE . "config/_settings.php", 0666);
}

  
    /*     * **************************************************
      UPDATE CUSTOME FIELDS
     * *************************************************** */
    if ($f_count > 0) {
//			$currencies = $_POST['currency_id'];
        //$sql = 'delete from ' . SERVER_LOG_AMOUNT_DETAILS . ' where log_id = ' . $id;
        // delete old custome fields
        $sql = 'delete from nxt_custom_fields  where s_type=3';
        $mysql->query($sql);

        for ($f = 0; $f < $f_count; $f++) {

            if ($_POST['f_name'][$f] != "") {

                $id=3;
                $sql2 = 'update ' . SMTP_CONFIG . ' set is_custom=1';
                $mysql->query($sql2);
                //$amount = $request->PostFloat('amount_' . $mysql->getInt($currency_id));
                //$amount_purchase = $request->PostFloat('amount_purchase_' . $mysql->getInt($currency_id));
                if (!isset($_POST['f_opt'][$f]))
                    $opt = "";
                else {
                    $opt = $_POST['f_opt'][$f];
                }
                $sql = 'insert into nxt_custom_fields
						(s_type,s_id,f_type,f_name,f_desc,f_opt,f_valid,f_req)
						values(
                                                3,
						' . $id . ',
						' . $_POST['f_type'][$f] . ',
                                                ' . $mysql->quote($_POST['f_name'][$f]) . ',
                                                ' . $mysql->quote($_POST['f_desc'][$f]) . ',
                                                ' . $mysql->quote($opt) . ',
                                                ' . $mysql->quote($_POST['f_valid'][$f]) . ',
						' . $_POST['f_req2'][$f] . ')';

                // echo $sql;exit;
                $mysql->query($sql);
            } else {
                $sql3 = 'update ' . SMTP_CONFIG . ' set is_custom=0';
                $mysql->query($sql3);
            }
        }
    }
    /*     * ************************************************** */

    if (($_POST['a_url'] != "") && ($_POST['a_url'] != $_POST['old_a_url'])) {
    // rename the folder then update the table

        
    if (rename(CONFIG_PATH_SITE_ABSOLUTE . $old_url, CONFIG_PATH_SITE_ABSOLUTE . $new_url)) {
        $sqlurl = 'update ' . SMTP_CONFIG . ' set old_url=' . $mysql->quote(trim($new_url));
        $mysql->query($sqlurl);
        //header("location:".CONFIG_PATH_SITE_ADMIN."log_out.do");
        //header('location:' . CONFIG_PATH_SITE_ADMIN . '#?reply=' . urlencode('reply_session_expired'));
        // exit;
        header("location:" . CONFIG_PATH_SITE . $new_url . "/log_out.do?reply=" . urlencode('reply_cant_change_settings'));
        exit();
    }
}

//echo CONFIG_PATH_SITE . $CONFIG_PATH_SITE_ADMIN;exit;
    if($switch_tab!=$oldtabval)
    {
       // $admin->logout();
          header("location:".CONFIG_PATH_SITE_ADMIN."log_out.do");
        //header('location:' . CONFIG_PATH_SITE_ADMIN . '#?reply=' . urlencode('reply_session_expired'));
        exit;
    }
 else {
      header("location:" . CONFIG_PATH_SITE . $CONFIG_PATH_SITE_ADMIN . "config_settings_2.html?reply=" . urlencode('reply_successfull'));
exit();  
}


?>