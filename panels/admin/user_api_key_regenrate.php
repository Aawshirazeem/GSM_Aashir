<?php

if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	$keyword = new keyword();
    
	$admin->checkLogin();
	$admin->reject();
      
       $keyNew = $keyword->generate(4) . '-';
       $keyNew .= $keyword->generate(4) . '-';
       $keyNew .= $keyword->generate(4) . '-';
       $keyNew .= $keyword->generate(4);
       $keyNew = strtoupper($keyNew);
       echo $keyNew;



?>
