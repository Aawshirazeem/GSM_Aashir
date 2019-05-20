<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	

	list($result, $message) = $member->login($_SESSION['tempUsername'],$_SESSION['tempPassword'],$_SESSION['tempPasswordSimple']);
	unset($_SESSION['tempUsername']);
	unset($_SESSION['tempPassword']);
        unset($_SESSION['tempPasswordSimple']);
	if($result == true)
	{
		header("location:" . CONFIG_PATH_SITE_USER . "dashboard.html");
		exit();
	}
	elseif($result == false)
	{
		switch($message)
		{
			case "invalid_password":
				//header("location:" . CONFIG_PATH_SITE . "user/index.html?reply=" . urlencode('reply_invalid_login'));
				header("location:" . CONFIG_PATH_SITE . "login.html?reply=" . urlencode('reply_invalid_login'));
				break;
                            case "account_blocked":
				//header("location:" . CONFIG_PATH_SITE . "user/index.html?reply=" . urlencode('reply_account_blocked'));
				header("location:" . CONFIG_PATH_SITE . "login.html?reply=" . urlencode('reply_account_blocked'));
				break;
			case "invalid_country":
				//header("location:" . CONFIG_PATH_SITE . "user/index.html?reply=" . urlencode('reply_invalid_lp'));
				header("location:" . CONFIG_PATH_SITE . "login.html?reply=" . urlencode('reply_invalid_lp'));
				break;
                            case "invalid_inactive_login":
				//header("location:" . CONFIG_PATH_SITE . "user/index.html?reply=" . urlencode('reply_inactive_login'));
				header("location:" . CONFIG_PATH_SITE . "login.html?reply=" . urlencode('reply_inactive_login'));
				break;
                        case "admin_accept_wait":
				//header("location:" . CONFIG_PATH_SITE . "user/index.html?reply=" . urlencode('reply_admin_accept_wait'));
				header("location:" . CONFIG_PATH_SITE . "login.html?reply=" . urlencode('reply_admin_accept_wait'));
				break;
                              case "admin_accept_wait_1":
				//header("location:" . CONFIG_PATH_SITE . "user/index.html?reply=" . urlencode('reply_admin_accept_wait'));
				header("location:" . CONFIG_PATH_SITE . "login.html?reply=" . urlencode('reply_admin_accept_wait_1'));
				break;
			default:
				//header("location:" . CONFIG_PATH_SITE . "user/index.html?reply=" . urlencode('reply_invalid_login'));
				header("location:" . CONFIG_PATH_SITE . "login.html?reply=" . urlencode('reply_invalid_login'));
				break;
		}
		exit();
	}
?>