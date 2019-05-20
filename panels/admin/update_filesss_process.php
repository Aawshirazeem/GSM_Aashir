<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$admin->checkLogin();
$admin->reject();
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//echo '<pre>';
//var_dump($_FILES);
$total = count($_FILES['upload']['name']);
//echo $total;
//exit;
//echo(rand(10000,100000));exit;
$file_path = $request->PostInt('fpath');
//echo $file_path;exit;
if($file_path==0)
{
   // echo 'select file path';exit;
     header("location:" . CONFIG_PATH_SITE_ADMIN . "update_filesss.html?reply=" .urlencode('reply_select_file'));
     exit;
}
   
//exit();
//echo $file_path;exit;


/* FTP Account */
$ftp_host = 'ftp.gsmunion.org'; /* host */
$ftp_user_name = 'hub@gsmunion.net'; /* username */
$ftp_user_pass = 'KgC?Np2^96~P'; /* password */


/* New file name and path for this file */
//$remote_file = 'upload_files/'.$file_name;
$local_file = $_FILES["fileToUpload"]["tmp_name"];

/* Connect using basic FTP */
$connect_it = ftp_connect($ftp_host);

/* Login to FTP */
$login_result = ftp_login($connect_it, $ftp_user_name, $ftp_user_pass);

/* Download $remote_file and save to $local_file */
/*
  if ( ftp_put( $connect_it, $local_file, $remote_file, FTP_BINARY ) ) {
  echo "WOOT! Successfully written to $local_file\n";
  } */


// db connection
$count = 0;
$sql = 'insert into tbl_files_log (file_name,file_path,status,add_by) values';
foreach ($_FILES['upload']['name'] as $filename) {
    // $temp=$target;
    // echo $filename;exit;
    $tempfilename = rand(10000, 100000) . '&' . $filename;
    $remote_file = 'upload_files/' . $tempfilename;

    $local_file = $_FILES['upload']['tmp_name'][$count];
    //  echo $local_file.'<br>'.$remote_file;exit;
    //  $local_file = $_FILES["fileToUpload"]["tmp_name"];
    $count = $count + 1;
    // $temp=$temp.basename($filename);
    // move_uploaded_file($tmp,$temp);
    //$temp='';
    //$tmp='';
     $servername = "185.27.133.17";
    $username = "gsmunion_upuser";
    $password = "S+OXupg8lqaW";
    $dbname = "gsmunion_upload";

// Create connection
    $conn2 = new mysqli($servername, $username, $password, $dbname);
    if ($filename != "") {

        $sql.='(' . $mysql->quote($tempfilename) . ',' . $mysql->quote($file_path) . ',1,' . $mysql->getInt($admin->getUserId()) . '),';
        if (ftp_put($connect_it, $remote_file, $local_file, FTP_BINARY)) {

            // add that file to hub databse
            // 
            // 
            //echo "WOOT! Successfully transfer $local_file\n";
        } else {
            //echo "Doh! There was a problem\n";
        }
    }
}

if ($sql != "insert into tbl_files_log (file_name,file_path,status,add_by) values") {
    $sql = rtrim($sql, ',');
  //  echo $sql;
    //exit;

    // add it to db
   
// Check connection
    if ($conn2->connect_error) {
        die("Connection failed: " . $conn2->connect_error);
    }
    
    $conn2->query($sql);
}


/* Send $local_file to FTP */
//if ( ftp_put( $connect_it, $remote_file, $local_file, FTP_BINARY ) ) {
//    echo "WOOT! Successfully transfer $local_file\n";
//}
//else {
//    echo "Doh! There was a problem\n";
//}

/* Close the connection */
ftp_close($connect_it);
$conn2->close();
header("location:" . CONFIG_PATH_SITE_ADMIN . "update_filesss.html");
exit();
echo 'doneee';
exit;
