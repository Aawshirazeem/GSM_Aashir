

<h1 class="m-t-20 m-b-20"><?php $lang->prints('lbl_Updating........'); ?></h1>

<!-- Progress bar holder -->

<div id="progress" style="width:1568px;border:1px solid #ccc;"></div>

<!-- Progress information -->

<div id="information" style="width"></div>

<?php



$con = mysqli_connect("185.27.133.16","gsmunion_upuser","S+OXupg8lqaW","gsmunion_upload");

                                  // Check connection

//                                   if (mysqli_connect_errno())

//                                    {

//                                    echo "Failed to connect to MySQL: " . mysqli_connect_error();

//                                    }

 $qry_check='select * from tbl_users where is_update=1 and domain="'.$_SERVER['HTTP_HOST'].'"';

                                   // echo  $qry; 

                                   $queryCount = mysqli_query($con,$qry_check);

                                   // Total processes



                                  if ($queryCount->num_rows > 0) {  

                                      

                                      

               $qry_get_files="SELECT a.file_name, b.path

FROM tbl_files_log AS a

INNER JOIN nxt_tbl_paths AS b ON a.file_path = b.id

WHERE a.status =1";

                $queryCount = mysqli_query($con,$qry_get_files);

               // $results=$queryCount->fetch_assoc();

              //  echo '<pre>';

               // var_dump($results);exit;

                $total = $queryCount->num_rows;

         //   $total=5;

               // echo $total;exit;

                    $version="select name from tbl_versions where status=1";

    $result= $con->query($version);

 

    $result=$result->fetch_assoc();

    $version=$result['name'];

   // echo $version;exit;

    $db_change_check=0;

                for($i=1; $i<=$total; $i++){

                  // while ($row = mysqli_fetch_assoc($queryCount)) {

                      $row = mysqli_fetch_assoc($queryCount);

                     //echo $i."file dowload";

                         // Calculate the percentation

                         $percent = intval($i/$total * 100)."%";  

                         // Javascript for updating the progress bar and information

                       

    echo '<script language="javascript">

    document.getElementById("progress").innerHTML="<div style=\"width:'.$percent.';background-color:rgb(60, 147, 38);\">&nbsp;</div>";

  

    </script>';

  //document.getElementById("information").innerHTML="'.$i.' row(s) processed.";

    



                  // echo $row['file_name'];

                  // exit;

                 $file_name=  explode("&",$row['file_name']);

                 $file_name=$file_name[1];

                 if ($file_name == "db_changes.php") {

                     $db_change_check=1;

                 }

                 //echo $file_name;exit;

                 $path=$row['path'];

                 $remote_file = 'upload_files/'.$version.'/'.$row['file_name'];

                 //echo $remote_file

                 if($path=='root')

                 {

                  $local_file = $file_name;   

                 }        

                 else

                 {    

                 $local_file = $path.$file_name;

                 }

                  /* FTP Account */

                 $ftp_host = 'ftp.gsmunion.org'; /* host */

                 $ftp_user_name = 'hub@gsmunion.net'; /* username */

                 $ftp_user_pass = 'KgC?Np2^96~P'; /* password */

                 /* Connect using basic FTP */

                 $connect_it = ftp_connect( $ftp_host );

                 /* Login to FTP */

                 $login_result = ftp_login( $connect_it, $ftp_user_name, $ftp_user_pass );

                 /* Download $remote_file and save to $local_file */

                 if ( ftp_get( $connect_it, $local_file, $remote_file, FTP_BINARY ) ) {

   

                    }

                 else 

                    {

    

                    }

 

                  /* Close the connection */

//                  ftp_close( $connect_it );

                  // This is for the buffer achieve the minimum size in order to flush data

                  echo str_repeat(' ',1024*64);

                  // Send output to browser immediately

                  ob_flush();

                  // Sleep one second so we can see the delay

                // exit;

               //   set_time_limit(1);

                     sleep(1);



                }

                ob_clean();

                

                 $qry='update tbl_users set is_update=0 where domain="'.$_SERVER['HTTP_HOST'].'"';

                                   // echo  $qry; 

                                   $queryCount = mysqli_query($con,$qry);

                                    $qry='update '.ADMIN_MASTER.' set is_update=0 where id=1';

                                      $mysql->query($qry);

                                 // echo 'Updated......';

                                   // Tell user that the process is completed

//echo 'Congratulations Proccess Completed.';

if ($db_change_check == 1) {

    //ob_start();

    // ob_flush();

    //$path=CONFIG_PATH_SYSTEM . 'cmd/db_changes.php';

    //echo $path;

    //exit;

  //  require_once ('../../system/cmd/db_changes.php');

  //  echo 'Congratulations Proccess Completed.';

    echo("<script>location.href = '".CONFIG_PATH_SYSTEM."cmd/db_changes.php';</script>");

  //header("location:" . CONFIG_PATH_SYSTEM . "cmd/db_changes.php");

  //ob_flush();

	exit();  

}

else

    {

    //echo '';

    $lang->prints('lbl_Congratulations_Proccess_Completed');

    echo '<meta http-equiv="refresh" content="1;url='.CONFIG_PATH_SITE_ADMIN.'dashboard.html">';

    }



                                  }

 else {

     ?>

<h1 class="m-t-20"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Your_Site_is_Already_Update')); ?></h1>

<?php

 }

?>

