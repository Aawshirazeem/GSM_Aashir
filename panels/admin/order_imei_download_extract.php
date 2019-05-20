<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
                
	}
        $content=$_POST['content'];
        $file_name="imei_download.txt";
        $handle = fopen($file_name, "w");
        fwrite($handle,$content);
        fclose($handle);

    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename('imei_download.txt'));
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize('imei_download.txt'));
    readfile('imei_download.txt');
    exit;
       // $content ='';
        //$content .= '===========================';
      //  $content .= 'test';
      //  var_dump($content);exit;
       // $download = new download();
	//	$download->downloadContent($content,'imei_download.txt');
                ?>