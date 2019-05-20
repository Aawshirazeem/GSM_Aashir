<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


include '../config/_db.php';
define("CONFIG_PATH_SITE", '/app/');

include '../config/_settings.php';

include 'email.class.php';

//echo "<pre/>";
//var_dump($_POST);
//exit;
$system_error=$_POST['system-error'];
if($system_error == '')
{
    

$host=$_POST['host'];
$uname=$_POST['uname'];
$password=$_POST['password'];
$dbname=$_POST['dbname'];
$admin_uname=$_POST['admin-uname'];
$admin_pass=$_POST['admin-pass'];

        $CONFIG_DOMAIN = $_POST['domain'];
	$CONFIG_SITE_TITLE = $_POST['title'];
	$CONFIG_SITE_NAME = $_POST['tag'];
        $admin_email=$_POST['admin-email'];



$conn=new mysqli($host,$uname,$password);

// Check connection
if ($conn->connect_error) {
   // die("Connection failed: " . $conn->connect_error);
    echo("<script>location.href = 'index.php?reply=Database Credentials are Wrong,Could not connect to Server';</script>");
    //header('location:form-wizard.php?reply=Database Credentials are Wrong,Could not connect to Server');
}
 else {
     
     $sql ="DROP DATABASE if exists  ".$dbname;
     $conn->query($sql);
     $sql="CREATE DATABASE  ".$dbname;
if ($conn->query($sql) === TRUE) {
    //echo "Database created successfully";
    
    //download latest .sql file from hub
     $remote_file = 'http://hub.gsmunion.net/latest_db/data.sql';
                 //echo $remote_file
                 $local_file = 'sql/data.sql';
                  /* FTP Account */
               
           $copy = copy( $remote_file, $local_file );
 //echo $copy;
 //exit;
if( $copy == 1 ) {
   $filename='sql/data.sql';
   import_sql($filename);
 //echo "Tables imported successfully";
   
$conn=mysqli_connect($host,$uname,$password,$dbname);
   $admin_pass=  generate($admin_pass);
  $insert_admin="insert into nxt_admin_master(username,password,email,status,timezone_id) values ('".$admin_uname."','".$admin_pass."','".$admin_email."',1,424) ";
//  echo $insert_admin;
  $conn->query($insert_admin); 

 $connect_code='<?php
     define("CONFIG_VERSION", "1.2.0");
	define("CONFIG_VERSION_ID", "6");
	
	define("CONFIG_REPAIR_MODE", "0");

	define("NEW_LINE", "\n");

	
	define("GMAIL_USERNAME_ADMIN", "");
	define("GMAIL_PASSWORD_ADMIN", "");
	define("GMAIL_USERNAME", "");
	define("GMAIL_PASSWORD", "");
	define("GMAIL_USERNAME_CONTACT", "");
	define("GMAIL_PASSWORD_CONTACT", "");
	


	define("CONFIG_THEME", "imeipk/");
	define("CONFIG_PANEL", "Dark/");
        define("CONFIG_PANEL_ADMIN", "Dark/");
        define("CONFIG_PANEL_SUPPLIER", "Dark/");
        define("CONFIG_PANEL_SHOP", "Dark/");
        define("CONFIG_PANEL_CHAT", "Dark/"); 

	define("CONFIG_PATH_SITE_ADMIN", CONFIG_PATH_SITE . "admin/");
	define("CONFIG_PATH_SITE_SUPPLIER", CONFIG_PATH_SITE . "supplier/");
	define("CONFIG_PATH_SITE_USER", CONFIG_PATH_SITE . "user/");
	define("CONFIG_ORDER_PAGE_SIZE", "500");
        define("CONFIG_DOMAIN", "' . $CONFIG_DOMAIN . '");
	define("CONFIG_SITE_TITLE", "' . $CONFIG_SITE_TITLE . '");
	define("CONFIG_SITE_NAME", "' . $CONFIG_SITE_NAME . '");

        define("CONFIG_EMAIL", "");
	define("CONFIG_EMAIL_CONTACT", "");
        define("CONFIG_FB_LINK", "");
        define("CONFIG_TW_LINK", "");
        define("CONFIG_GP_LINK", "");
        define("CONFIG_PH_NO", "");
    
  ?>';
 $connect_code_db='<?php define("CONFIG_DB_HOST", "' . $host . '");
	//define("CONFIG_DB_NAME", "blueunlo_dbunlock");
	define("CONFIG_DB_USER", "' . $uname . '");
	define("CONFIG_DB_PASS", "' . $password . '");
	define("CONFIG_DB_NAME", "' . $dbname . '");
         define("CONFIG_DB_PREFIX", "nxt_"); ?>';
         
         
 if(!is_writable("../config/_settings.php"))
	{
             echo("<script>location.href = 'index.php?reply=_Setting File Not Writeable';</script>");
		
                exit();
	}
	else
	{
		chmod("../config/_settings.php", 0777);
		$fp = fopen("../config/_settings.php", 'wb');
		fwrite($fp,$connect_code);
		fclose($fp);
		chmod("../config/_settings.php", 0666);
	}
    //db file connect
        if(!is_writable("../config/_db.php"))
	{
             echo("<script>location.href = 'index.php?reply=_db File Not Writeable';</script>");
		
                exit();
	}
	else
	{
		chmod("../config/_db.php", 0777);
		$fp = fopen("../config/_db.php", 'wb');
		fwrite($fp,$connect_code_db);
		fclose($fp);
		chmod("../config/_db.php", 0666);
	}
        
        //send email of account detail to customer
        $obj=new installer_email();
        $simple_email_body = '';
        $email_config 		= getEmailSettings();
	$from_admin 		= $email_config['system_email'];
	$admin_from_disp	= $email_config['system_from'];
	$signatures			= "<br /><br />" . nl2br($email_config['admin_signature']);
	//$simple_email_body .= '<h2>Your Unlock Codes</h2>';
																	
								
									$simple_email_body .= '
                                                                         <p><b>Your Installation is now completed.Follow the instructions below for further process.</b></p>
                                                                         <p>Your Admin Panel link:  http://'.$CONFIG_DOMAIN.'/admin</p>
                                                                         <p>Admin username: '.$admin_uname.'</p>
                                                                         <p>Admin Password: '.$_POST['admin-pass'].'</p>     
									';	
								$obj->setTo($admin_email);
								$obj->setFrom($from_admin);
								$obj->setFromDisplay($admin_from_disp);
								$obj->setSubject("Hurray! GsmUnion Installation has been Completed");
								$obj->setBody($simple_email_body.$signatures);
								 $obj->sendMail();
                                                                //   echo "email sent"; 
                                                                 //  exit;
                                                                     
        //////
      //  echo "site and db config done";
    //   exit;
     echo("<script>location.href = '../Success.php?domain=".$CONFIG_DOMAIN."';</script>");
         
    
}
else{
    echo("<script>location.href = 'index.php?reply=Database Downloading Error...';</script>");
}

    
    //end download
    
    
    
    
    
    
} else {

    echo("<script>location.href = 'index.php?reply=Error creating database';</script>");
}






}
}
else
{
    echo("<script>location.href = 'index.php?reply=Please fullfill System Requirement.. ';</script>");
}    
function import_sql($file, $delimiter = ';') {
    $host=$_POST['host'];
$uname=$_POST['uname'];
$password=$_POST['password'];
$dbname=$_POST['dbname'];

$conn=mysqli_connect($host,$uname,$password,$dbname);
	$handle = fopen($file, 'r');
	$sql = '';
 
	if($handle) {
		/*
		 * Loop through each line and build
		 * the SQL query until it detects the delimiter
		 */
		while(($line = fgets($handle, 4096)) !== false) {
			$sql .= trim(' ' . trim($line));
			if(substr($sql, -strlen($delimiter)) == $delimiter) {
				//mysqli_query($conn,$sql);
                           // echo $sql;
                            $conn->query($sql);
                           // exit;
				$sql = '';
			}
		}
 
		fclose($handle);
	}
}
 function generate($strPass)
		{
			return crypt($strPass, '$1$HF834Hu786Jufhfk484HVNFJhg8HR8RNF95456$');
		}
                 function getEmailSettings() {
$con = mysqli_connect("sv82.ifastnet.com","gsmunion_upuser","S+OXupg8lqaW","gsmunion_upload");
        $sql = 'select * from nxt_smtp_config limit 1';



      //  $mysql = new mysql();

        $query = $con->query($sql);
        
        $settings = '';

        if ($query->num_rows > 0) {

            $rows = mysqli_fetch_assoc($query);
            //echo '<pre/>';
            //var_dump($rows);exit;

            $settings['id'] = $rows['id'];

            $settings['admin_email'] = $rows['admin_email'];

            $settings['support_email'] = $rows['support_email'];

            $settings['system_email'] = $rows['system_email'];

            $settings['system_from'] = $rows['system_from'];

            $settings['admin_signature'] = $rows['admin_signature'];



            $settings['type'] = $rows['type'];

            $settings['smtp_port'] = $rows['smtp_port'];

            $settings['smtp_host'] = $rows['smtp_host'];

            $settings['smtp_user'] = $rows['username'];

            $settings['smtp_pass'] = $rows['password'];



            return $settings;
        } else {

            return false;
        }
    }
?>