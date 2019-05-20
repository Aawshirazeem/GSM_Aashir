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
$emailid = $_REQUEST['emailid'];
//$desc = $request->PostStr('desc');
$subject = $_REQUEST['subject'];
$body = $_REQUEST['editor1'];

if ($emailid == "" || $emailid =="0" || $body=="" ) {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "elist.html?reply=" . urlencode('repl_something_missing'));
    exit();
}

$sql = '
							insert into nxt_elistdetail
								(e_id,subject,body) 
								VALUES (
                                                                    ' . $emailid . ',
									' . $mysql->quote($subject) . ',
                                                                            ' . $mysql->quote($body) . '
							
								
									)';
$mysql->query($sql);
header('location:' . CONFIG_PATH_SITE_ADMIN . 'elist_desc.html?id='.$emailid);
exit();