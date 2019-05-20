<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$admin->checkLogin();
$admin->reject();
$validator->formValidateAdmin('suppliers_add_549883whh2');


$username = $request->PostStr('username');
$password = $request->PostStr('password');
$email = $request->PostStr('email');
$tz = $request->PostStr('timezone');
$status = $request->PostInt('status');
//$first_name = $request->PostStr('first_name');
//$last_name = $request->PostStr('last_name');
//$phone = $request->PostStr('phone');
//   $mobile = $request->PostStr('mobile');
$address = $request->PostStr('address');
//  $city = $request->PostStr('city');
$country = $request->PostInt('country');
$phone = $request->PostStr('pnum');
$nick = $request->PostStr('nick');
$fname = $request->PostStr('fname');
$lname = $request->PostStr('lname');
//$address = $request->PostStr('pnum');
if ($username == "") {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "admin_add.html?reply=" . urlencode('reply_engineer_missing'));
    exit();
}
if ($password == "") {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "admin_add.html?reply=" . urlencode('reply_engineer_password_missing'));
    exit();
}


$sql = 'select username from ' . ADMIN_MASTER . ' where username=' . $mysql->quote($username);
$query = $mysql->query($sql);
if ($mysql->rowCount($query) > 0) {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "admin_add.html?reply=" . urlencode('reply_username_duplicate'));
    exit();
}

// chek email existin admin table

$sql = 'select email from ' . ADMIN_MASTER . ' where email=' . $mysql->quote($email);
$query = $mysql->query($sql);
if ($mysql->rowCount($query) > 0) {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "admin_add.html?reply=" . urlencode('reply_email_duplicate'));
    exit();
}

// chek email existin user table

$sql = 'select email from ' . USER_MASTER . ' where email=' . $mysql->quote($email);
$query = $mysql->query($sql);
if ($mysql->rowCount($query) > 0) {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "admin_add.html?reply=" . urlencode('reply_email_duplicate_with_user'));
    exit();
}
// chek email existin user reg table

$sql = 'select email from ' . USER_REGISTER_MASTER . ' where email=' . $mysql->quote($email);
$query = $mysql->query($sql);
if ($mysql->rowCount($query) > 0) {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "admin_add.html?reply=" . urlencode('reply_email_duplicate_with_user'));
    exit();
}
// chek email supplier table

$sql = 'select email from ' . SUPPLIER_MASTER . ' where email=' . $mysql->quote($email);
$query = $mysql->query($sql);
if ($mysql->rowCount($query) > 0) {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "admin_add.html?reply=" . urlencode('reply_email_duplicate_with_supplier'));
    exit();
}

$keyword = new keyword();
$key = $request->GetStr('key');

$keyNew = $keyword->generate(4) . '-';
$keyNew .= $keyword->generate(4) . '-';
$keyNew .= $keyword->generate(4) . '-';
$keyNew .= $keyword->generate(4);
$keyNew = strtoupper($keyNew);

//$qPassword = (trim($password) != '') ? 'password = \'' . $objPass->generate($password) . '\',' : '';
$password=$objPass->generate($password);

$sql = 'insert into ' . ADMIN_MASTER . '
			(username, password, email, status,fname,lname,nname,pnumber,address,country,timezone_id)
			values(
			' . $mysql->quote($username) . ',
			' . $mysql->quote($password) . ',
			' . $mysql->quote($email) . ', 
			' . $mysql->getInt($status) . ', 
                       ' . $mysql->quote($fname) . ', 
                           ' . $mysql->quote($lname) . ', 
                                    ' . $mysql->quote($nick) . ', 
                            ' . $mysql->quote($phone) . ', 
                                    ' . $mysql->quote($address) . ', 
                                    ' . $mysql->getInt($country) . ', 
			' . $mysql->getInt($tz) . ')';
//echo $sql;exit;
$mysql->query($sql);

$id = $mysql->insert_id();



header("location:" . CONFIG_PATH_SITE_ADMIN . "admin.html?reply=" . urlencode('reply_success'));
?>