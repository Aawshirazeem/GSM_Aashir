<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}




$request = new request();
$mysql = new mysql();

$type = $request->getStr('type');
$file_name = stripslashes($request->getStr('file_name'));
$dir = CONFIG_PATH_SITE_ABSOLUTE . 'assets/file_service/';
if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            if ($file != "." && $file != '..' && $file != 'index.html') {
                if ($file == $_GET['file_name']) {
               // echo $file;exit;
                    $file_name = $file;
                    $file_url = $dir . $file_name;
                    header('Content-Type: application/force-download');
                   // header("Content-Transfer-Encoding: Binary");
                   // header('Content-Length: ' . filesize($file)); // provide file size
                    header("Content-disposition: attachment; filename=\"" . $file_name . "\"");
                    ob_clean();
                    flush();
                    readfile($file_url);
                }
            }
        }
        closedir($dh);
    }
}
exit;
switch ($type) {
    case 'database':
        $file_name = CONFIG_PATH_SITE_ABSOLUTE . "extra/db_backup/" . $file_name;
        break;
    case 'askrpl':
        //$file_name = CONFIG_PATH_SITE_ABSOLUTE . "extra/file_service/" . $file_name;
        $filename = CONFIG_PATH_EXTRA_ABSOLUTE . "file_service/" . $file_name;
        break;
}
//echo CONFIG_PATH_EXTRA_ABSOLUTE."file_service/";
// echo '<br>';
// echo $filename;exit;
// make sure it's a file before doing anything!
if (is_file($filename)) {
    
    echo $filename;
    echo $file_name;
    
    if (file_exists($filename)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($file_name).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file_name));
    readfile($file_name);
    exit;
}
    
    


    //  echo $filename;exit;
    $file_url = $filename;
    header('Content-Type: application/octet-stream');
    header("Content-Transfer-Encoding: Binary");
    header("Content-disposition: attachment; filename=\"" . $file_name . "\"");
    //   readfile($file_url);exit;
    /*
      Do any processing you'd like here:
      1.  Increment a counter
      2.  Do something with the DB
      3.  Check user permissions
      4.  Anything you want!
     */

    // required for IE
    if (ini_get('zlib.output_compression')) {
        ini_set('zlib.output_compression', 'Off');
    }

    // get the file mime type using the file extension
    switch (strtolower(substr(strrchr($file_name, '.'), 1))) {
        case 'pdf': $mime = 'application/pdf';
            break;
        case 'zip': $mime = 'application/zip';
            break;
        case 'jpeg':
        case 'jpg': $mime = 'image/jpg';
            break;
        default: $mime = 'application/force-download';
    }
    header('Pragma: public');  // required
    header('Expires: 0');  // no cache
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Cache-Control: private', false);
    header('Content-Type: ' . $mime);
    header('Content-Disposition: attachment; filename="' . basename($file_name) . '"');
    header('Content-Transfer-Encoding: binary');
   header('Content-Length: ' . filesize($filename)); // provide file size
    readfile($filename);  // push it out
    exit();
} else {
    echo "File Not Found";
    exit();
}
?>
