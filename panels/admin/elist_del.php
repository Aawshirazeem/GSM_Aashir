<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

defined("_VALID_ACCESS") or die("Restricted Access");
$id = $request->GetInt('id');

if ($id != "") {
    // $sql = 'delete from ' . USER_MASTER . ' where id=' . $id;
    $sql = 'delete from nxt_elist where id=' . $id;
    $mysql->query($sql);
    header('location:' . CONFIG_PATH_SITE_ADMIN . 'elist.html?reply=' . urlencode('repl_delete_done'));
    exit();
} else {
    header('location:' . CONFIG_PATH_SITE_ADMIN . 'elist.html?reply=' . urlencode('repl_error'));
    exit();
}