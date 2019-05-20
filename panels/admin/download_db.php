<?php

//defined("_VALID_ACCESS") or die("Restricted Access");
if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//echo '<pre>';

//var_dump($_GET);

$dir = CONFIG_PATH_SITE_ABSOLUTE . 'assets/db_backup/';
if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            if ($file != "." && $file != '..' && $file != 'index.html') {
                if ($file == $_GET['file_name'])
                   // echo $file;

                $file_name = $file;
                $file_url = $dir . $file_name;
                header('Content-Type: application/octet-stream');
                header("Content-Transfer-Encoding: Binary");
                header("Content-disposition: attachment; filename=\"" . $file_name . "\"");
                readfile($file_url);
            }
        }
        closedir($dh);
    }
}