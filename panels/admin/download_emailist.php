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

ob_clean();
$content = "";

$sql = 'select um.email from nxt_user_master um';
$query = $mysql->query($sql);
$i = 0;
if ($mysql->rowCount($query) > 0) {
    $rows = $mysql->fetchArray($query);
    $i = 0;
    foreach ($rows as $row) {
        $content .= $row['email'] ."\r\n";
    }
}
$file_name = "emaillist.txt";
$handle = fopen($file_name, "w");
fwrite($handle, $content);
fclose($handle);

header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=' . basename('emaillist.txt'));
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize('emaillist.txt'));
readfile('emaillist.txt');
exit;
