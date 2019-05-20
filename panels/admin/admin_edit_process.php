<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$admin->checkLogin();
$admin->reject();
$validator->formValidateAdmin('suppliers_edit_54964566hh2');


$id = $request->PostInt('id');
$username = $request->PostStr('username');
$password = $request->PostStr('password');
$status = $request->PostInt('status');
$email = $request->PostStr('email');
$old_email = $request->PostStr('old_email');
$tz = $request->PostStr('timezone');
$allow_change_password = $request->PostCheck('allow_change_password');
$allow_manage_masters = $request->PostCheck('allow_manage_masters');
$allow_patient_manager = $request->PostCheck('allow_patient_manager');
$allow_cqi = $request->PostCheck('allow_cqi');
$allow_hr = $request->PostCheck('allow_hr');
$allow_inventory = $request->PostCheck('allow_inventory');


if ($status == 0 && $admin->getUserId() == $id) {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "admin_edit.html?id=" . $id . "&reply=" . urlencode('reply_cant_inactive_your_account'));
    exit();
}


$address = $request->PostStr('address');
//  $city = $request->PostStr('city');
$country = $request->PostInt('country');
$lang = $request->PostInt('language');
$phone = $request->PostStr('pnumber');
$nick = $request->PostStr('nick');
$fname = $request->PostStr('fname');
$lname = $request->PostStr('lname');


if($old_email!=$email)
{
// chek email existin admin table

  $sql = 'select email from ' . ADMIN_MASTER . ' where email=' . $mysql->quote($email);
  $query = $mysql->query($sql);
  if ($mysql->rowCount($query) > 0) {
  header("location:" . CONFIG_PATH_SITE_ADMIN . "admin_edit.html?id=" . $id . "&reply=" . urlencode('reply_email_duplicate'));
  exit();
  }

  // chek email existin user table

  $sql = 'select email from ' . USER_MASTER . ' where email=' . $mysql->quote($email);
  $query = $mysql->query($sql);
  if ($mysql->rowCount($query) > 0) {
  //header("location:" . CONFIG_PATH_SITE_ADMIN . "admin_add.html?reply=" . urlencode('reply_email_duplicate_with_user'));
  header("location:" . CONFIG_PATH_SITE_ADMIN . "admin_edit.html?id=" . $id . "&reply=" . urlencode('reply_email_duplicate_with_user'));
  exit();
  }
  // chek email existin user reg table

  $sql = 'select email from ' . USER_REGISTER_MASTER . ' where email=' . $mysql->quote($email);
  $query = $mysql->query($sql);
  if ($mysql->rowCount($query) > 0) {
  //header("location:" . CONFIG_PATH_SITE_ADMIN . "admin_add.html?reply=" . urlencode('reply_email_duplicate_with_user'));
  header("location:" . CONFIG_PATH_SITE_ADMIN . "admin_edit.html?id=" . $id . "&reply=" . urlencode('reply_email_duplicate_with_user'));

  exit();
  }
  $sql = 'select email from ' . SUPPLIER_MASTER . ' where email=' . $mysql->quote($email);
  $query = $mysql->query($sql);
  if ($mysql->rowCount($query) > 0) {
  //header("location:" . CONFIG_PATH_SITE_ADMIN . "admin_add.html?reply=" . urlencode('reply_email_duplicate_with_supplier'));
  header("location:" . CONFIG_PATH_SITE_ADMIN . "admin_edit.html?id=" . $id . "&reply=" . urlencode('reply_email_duplicate_with_supplier'));
  exit();
  }
  //$qPassword = (trim($password) != '') ? 'password = \'' . $objPass->generate($password) . '\',' : '';
 
}
$sql = 'update ' . ADMIN_MASTER . '
			set 
			
			
                            timezone_id = ' . $mysql->quote($tz) . ',
                         fname = ' . $mysql->quote($fname) . ',
                              lname = ' . $mysql->quote($lname) . ',
                                      nname = ' . $mysql->quote($nick) . ',
                                              lname = ' . $mysql->quote($lname) . ',
                                                      pnumber = ' . $mysql->quote($phone) . ',
                                                              address = ' . $mysql->quote($address) . ',
                                                                    language_id = ' . $mysql->getInt($lang) . ',
                                                                  country = ' . $mysql->getInt($country) . ',
                                   timezone_id = ' . $mysql->quote($tz) . ',
			status = ' . $mysql->getInt($status) . '
			where id = ' . $mysql->getInt($id);
$mysql->query($sql);
header("location:" . CONFIG_PATH_SITE_ADMIN . "admin.html?reply=" . urlencode('reply_success'));
exit();
?>