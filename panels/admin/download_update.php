
<?php
if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}


// for commit

$request = new request();
$mysql = new mysql();
$version = "";
$sql_eror = 0;
$sql_eror_desc = "";
$error_chk = 0;
$error_desc = "";
$is_update = 0;
$sql = 'select * from ' . ADMIN_MASTER . ' a
where a.is_update=1

limit 1';

//echo $sql;exit;
$qrydata = $mysql->query($sql);

if ($mysql->rowCount($qrydata) > 0) {
    $is_update = 1;
}

if ($is_update == 1) {
    ?>


    <h1 class="m-t-20 m-b-20"><?php $lang->prints('lbl_Updating........'); ?></h1>
    <!-- Progress bar holder -->
    <div id="progress" style="width:1568px;border:1px solid #ccc;"></div>
    <!-- Progress information -->
    <div id="information" style="width"></div>

    <?php

    function rrmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir")
                        rrmdir($dir . "/" . $object);
                    else
                        unlink($dir . "/" . $object);
                }
            }
            reset($objects);
            // rmdir($dir);
        }
    }

    $dir_zip = CONFIG_PATH_SITE_ABSOLUTE . 'update_data/';
    if (!is_dir($dir_zip)) {
        mkdir($dir_zip);
    }
    $db_change_check = 0;
    $con = mysqli_connect("sv82.ifastnet.com", "gsmunion_upuser", "S+OXupg8lqaW", "gsmunion_upload");
//Check connection
    if (mysqli_connect_errno()) {
        //echo "Failed to connect to MySQL: " . mysqli_connect_error();
        // $lang->prints('lbl_MYSQL_CONNECTION_ISSUE');
        //  echo '';
        echo '<h1 class="m-t-20" style="color:red;">Sorry Try Again Later ,Cant Connect To The Hub...</h1>';
        $error_chk = 1;
        //sleep(2);
        //   echo '<meta http-equiv="refresh" content="1;url=' . CONFIG_PATH_SITE_ADMIN . 'dashboard.html">';
    } else {

        $input = $_SERVER['HTTP_HOST'];
        $input = trim($input, '/');
        if (!preg_match('#^http(s)?://#', $input)) {
            $input = 'http://' . $input;
        }
        $urlParts = parse_url($input);
        $domain = preg_replace('/^www\./', '', $urlParts['host']);


        $qry_check = 'select * from tbl_users where is_update=1 and domain LIKE "%'.$domain.'%"  ';
// echo  $qry; 
        $queryCount = mysqli_query($con, $qry_check);
// Total processes

        $result = $con->query($qry_check);
        $result = $result->fetch_assoc();
        $lat_version_user = $result['latest_ver'];
//echo $lat_version_user;exit;
        if ($queryCount->num_rows > 0) {
            // if user update is on then iterate it from all the given updated he has


            $sql_version = "select * from tbl_versions a where a.id>" . $lat_version_user . " order by a.id";
            $result_ver = $con->query($sql_version);

            if ($result_ver->num_rows > 0) {
                // output data of each row
                while ($row22 = $result_ver->fetch_assoc()) {

                    $main_version = $row22["id"];

                    $qry_get_files = "SELECT a.file_name, b.path
FROM tbl_files_log AS a
INNER JOIN nxt_tbl_paths AS b ON a.file_path = b.id
WHERE a.status =1 and a.ver=" . $main_version;
                    $queryCount = mysqli_query($con, $qry_get_files);
                    // $results=$queryCount->fetch_assoc();
                    //  echo '<pre>';
                    // var_dump($results);exit;
                    $total = $queryCount->num_rows;
                    //   $total=5;
                    // echo $total;exit;
                    $version = "select name from tbl_versions where status=1 and id=" . $main_version;
                    $result = $con->query($version);

                    $result = $result->fetch_assoc();
                    $version = $result['name'];
                    // echo $version;exit;

                    for ($i = 1; $i <= $total; $i++) {
                        // while ($row = mysqli_fetch_assoc($queryCount)) {
                        $row = mysqli_fetch_assoc($queryCount);
                        //echo $i."file dowload";
                        // Calculate the percentation
                        $percent = intval($i / $total * 100) . "%";
                        // Javascript for updating the progress bar and information

                        echo '<script language="javascript">
    document.getElementById("progress").innerHTML="<div style=\"width:' . $percent . ';background-color:rgb(60, 147, 38);\">&nbsp;</div>";
  
    </script>';
                        //document.getElementById("information").innerHTML="'.$i.' row(s) processed.";
                        // echo $row['file_name'];
                        // exit;
                        $file_name = explode("&", $row['file_name']);
                        $file_name = $file_name[1];
                        if ($file_name == "db_changes.php") {
                            $db_change_check = 1;
                        }
                        //echo $file_name;exit;
                        $path = $row['path'];
                        $remote_file = 'upload_files/' . $version . '/' . $row['file_name'];
                        //echo $remote_file
                        if ($path == 'root') {
                            $local_file = $file_name;
                        } else {
                            $local_file = $path . $file_name;
                        }
                        /* FTP Account */
                        $ftp_host = 'ftp.gsmunion.org'; /* host */
                        $ftp_user_name = 'hub@gsmunion.net'; /* username */
                        $ftp_user_pass = 'KgC?Np2^96~P'; /* password */
                        /* Connect using basic FTP */
                        $connect_it = ftp_connect($ftp_host);
                        /* Login to FTP */
                        $login_result = ftp_login($connect_it, $ftp_user_name, $ftp_user_pass);
                        /* Download $remote_file and save to $local_file */
                        if ($login_result != FALSE) {
                            if (ftp_get($connect_it, $local_file, $remote_file, FTP_BINARY)) {

                                // if we  get the file
                                //-------------------------------- now do the stuff------------------------------------------
                                // first of all extract all the data 
                                $dir_zip = CONFIG_PATH_SITE_ABSOLUTE . 'update_data/';
                                $dir = CONFIG_PATH_SITE_ABSOLUTE . 'update_data/';
                                $zip = new ZipArchive;
                                $res = $zip->open($dir_zip . 'upload.zip');
                                if ($res === TRUE) {
                                    $zip->extractTo($dir_zip . 'upload');
                                    $zip->close();
                                    // echo 'woot!';
                                } else {
                                    echo 'isssuee!';
                                    $error_desc = "isssuee";
                                    $error_chk = 1;
                                    // exit;
                                }



// now get the direction file

                                $dir = $dir . 'upload/';
                                if (is_dir($dir)) {
                                    if ($dh = opendir($dir)) {
                                        while (($file = readdir($dh)) !== false) {
                                            if ($file != "." && $file != '..') {

                                                if ($file == "direction.txt") {
                                                    $handle = fopen($dir . $file, "r");
                                                    if ($handle) {
                                                        while (($line = fgets($handle)) !== false) {
                                                            // process the line read.
                                                            // echo $line.'<br>';
                                                            $tmp_name = explode("&", $line);
                                                            $filename = $tmp_name[0];
                                                            $filepath = $tmp_name[1];
                                                            $filepath = trim($filepath);
                                                            $filepath.='/';
                                                            //    echo 'FIle name is:' . $filename . ' and path is:' . $filepath . '<br>';
                                                            copy($dir . $filename, CONFIG_PATH_SITE_ABSOLUTE . $filepath . $filename);
                                                            //   mkdir($dir, 0777);
                                                            unlink($dir . $filename);
                                                            if ($filename == "db_changes.php") {
                                                                $db_change_check = 1;
                                                            }
                                                            //  echo 'done 1<br>';
                                                        }

                                                        fclose($handle);
                                                        unlink($dir . $file);
                                                    } else {
                                                        // echo 'error opening the file';
                                                        $error_desc = "error opening the file'";
                                                        //    $error_chk = 1;
                                                    }
                                                } else {
                                                    //  echo 'direction file not found';
                                                    //   $error_desc="direction file not found";
                                                    //  $error_chk = 1;
                                                }
                                            }
                                        }
                                        closedir($dh);
                                    }
                                }
                                rmdir($dir);
                                rrmdir($dir_zip);
                            } else {

                                //  $lang->prints('lbl_FILE_ISSUE');
                                //   echo '<br>File Download Failed';
                                //  $error_chk = 1;
                                // sleep(2);
                                //  echo '<meta http-equiv="refresh" content="1;url=' . CONFIG_PATH_SITE_ADMIN . 'dashboard.html">';
                            }
                        } else {
                            $error_chk = 1;
                            $error_desc = "Cant Connect To Hub Ftp";
                            // echo '<br>Cant Connect To Hub Ftp';
                        }

                        /* Close the connection */
//                  ftp_close( $connect_it );
                        // This is for the buffer achieve the minimum size in order to flush data
                        //  echo str_repeat(' ', 1024 * 64);
                        // Send output to browser immediately
                        //     ob_flush();
                        // Sleep one second so we can see the delay
                        // exit;
                        //   set_time_limit(1);
                        //       sleep(1);
                    }
                    //    ob_clean();
                    //----------------------------end---------------------------------------------------------

                    if ($db_change_check == 1) {
                        // echo("<script>location.href = '" . CONFIG_PATH_SYSTEM . "cmd/db_changes.php';</script>");
                        // exit();
                        // include 'http://localhost:8081/gsmlocal/system/cmd/db_changes.php';
                        include_once CONFIG_PATH_ROOT . CONFIG_PATH_SYSTEM . 'cmd/db_changes.php';
                        // $filepathofdb=CONFIG_PATH_SYSTEM . 'cmd/db_changes.php';
                        // file_get_contents($filepathofdb);
                    }
                }


                // update the local db as well as

                if ($error_chk == 0) {

                    $qry = 'update ' . ADMIN_MASTER . ' set is_update=0';
                    $mysql->query($qry);

                    // $qry = 'update ' . ADMIN_MASTER . ' set is_update=0';
                    $qry = 'update nxt_smtp_config set cur_ver="' . $version . '" where id=1';
                    $mysql->query($qry);
                    // not set is update and also last update version of the user
                    $qry = 'update tbl_users set is_update=0,latest_ver=' . $main_version . ' where domain="' . $domain. '"';
                    $queryCount = mysqli_query($con, $qry);
                    // $lang->prints('');
                    echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Update_Done'));
                    echo '<meta http-equiv="refresh" content="1;url=' . CONFIG_PATH_SITE_ADMIN . 'dashboard.html">';
                } else {

                    if ($sql_eror == 1) {
                        echo '<br><br>Some error occur while Changing The DB';
                        echo '<br>The SQL error is: ' . $sql_eror_desc;
                    } else {
                        echo '<br><br>Some error occur while downloading the new update..please try again after sometime.';
                        echo '<br>' . $error_desc;
                    }
                }
                //sleep(10);
            } else {
                ?>

                <h1 class="m-t-20"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Your_Site_is_Already_Update')); ?></h1>
                <?php
            }
        } else {
            ?>


            <h1 class="m-t-20"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Your_Site_is_Already_Update')); ?></h1>
            <?php
        }
    }
} else {
    ?> 
    <h1 class="m-t-20"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Your_Site_is_Already_Update')); ?></h1>
    <?php
}
?>
