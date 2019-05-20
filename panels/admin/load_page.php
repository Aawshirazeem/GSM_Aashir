<?php
if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}
if(!$_POST['page']) die("0");

$page = (string)$_POST['page'];

if (file_exists(CONFIG_PATH_ADMIN_ABSOLUTE . $page . '.php'))
        include(CONFIG_PATH_ADMIN_ABSOLUTE . $page . '.php');
//echo file_get_contents('pages/page_'.$page.'.html');

else echo 'There is no such page!';
?>
