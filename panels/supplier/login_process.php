<?php
if(!defined("_VALID_ACCESS")){
	define("_VALID_ACCESS",1);
	require_once("../../_init.php");
}

$username = $request->PostStr('username');
$password = $request->PostStr('password');

$stay_signed_in = $request->PostCheck('stay_signed_in');

if($stay_signed_in == '1'){
	$cookie->useCookie();
}else{
	$cookie->useSession();
}

$_SESSION['tempUsername'] = $username;
$_SESSION['tempPassword'] = $objPass->generate($password);
?>

<!doctype html>
<html lang="us">
<head>
	<meta charset="utf-8">
<meta http-equiv="refresh" content="1;url=<?php echo CONFIG_PATH_SITE_SUPPLIER; ?>login_process_secure.do">
</head>
	<body><?php echo $admin->wordTrans($admin->getUserLang(),'Secure login...please wait...'); ?></body>
</html>