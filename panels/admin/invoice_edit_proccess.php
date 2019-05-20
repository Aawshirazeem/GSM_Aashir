<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
    //  echo  $_FILES["file"]["tmp_name"] ."<br/>";
     // echo  'C:/xampp/htdocs'.CONFIG_PATH_PANEL .'img/' . $_FILES["file"]["name"];
       /// echo "<pre>";
    //  exit;
    //    $dir= getcwd();
      // echo $dir.CONFIG_PATH_PANEL .'img/';
       // exit;
       $pathto=$_SERVER['DOCUMENT_ROOT'].CONFIG_PATH_SITE.'images/' . $_FILES["file"]["name"];
     //  echo $pathto;exit;
       $textarea_value = $_POST['detail'];
       $lines = explode("\n", $textarea_value);
     //  echo "<pre>";
       
     //  var_dump($lines);exit;
       // var_dump($_FILES);exit;
       

    
   
$a=move_uploaded_file($_FILES["file"]["tmp_name"],$pathto);
//echo $a;exit;
$qry='';
 if($_FILES["file"]["name"] !="")
 {
   $qry= ',logo="'.$_FILES["file"]["name"].'"'; 
 }
    $inv_log = 'update '.INVOICE_EDIT.'  set  detail="'.$lines[0].'",detail2="'.$lines[1].'",detail3="'.$lines[2].'",detail4="'.$lines[3].'" '.$qry;
   // echo $inv_log;exit;    
    $query_inv = $mysql->query($inv_log);
 
    header("location:" . CONFIG_PATH_SITE_ADMIN . "dashboard.html");

  