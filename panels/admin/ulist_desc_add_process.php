<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$admin->checkLogin();
$admin->reject();
//$ulistid = $_REQUEST['emailid'];
//$desc = $request->PostStr('desc');
//$name = $_REQUEST['name'];
//$mail = $_REQUEST['mail'];
//var_dump($_POST);
if (isset($_POST['mail']))
    $mails = $_POST['mail'];
else
    $mails = $_POST['mail'];
$mailss = explode(PHP_EOL, $mails);
//$txtImeis = "";




if ($mails == "") {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "ulist_desc_add.html?reply=" . urlencode('repl_something_missing'));
    exit();
}
foreach ($mailss as $im) {
    // echo $im;
    if (filter_var($im, FILTER_VALIDATE_EMAIL)) {
        $sql = '
							insert into nxt_ulistdetail2
								(email) 
								VALUES (
                                                                  
									
                                                                            ' . $mysql->quote($im) . '
							
								
									)';
        $mysql->query($sql);
    }
}

header('location:' . CONFIG_PATH_SITE_ADMIN . 'ulist_desc.html');
exit();



