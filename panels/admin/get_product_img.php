<?php
//echo 'hehe';exit;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$admin->checkLogin();
$admin->reject();
$id = $request->getStr('id');

  $sql = 'select img from ' . Product . ' where id=' . $mysql->getInt($id);
  //echo $sql;exit;
  $result = $mysql->getResult($sql);
  //$rows = $mysql->fetchArray($result);
  foreach ($result['RESULT'] as $row)
  {
      $img=$row['img'];
  }
  //$row = $result['RESULT']['img'];
  //echo $img;exit;
  header("Content-type: image/png");
  echo $img;
  exit;

