<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

defined("_VALID_ACCESS") or die("Restricted Access");
?>
<div class="clear"></div>
<div class="panel panel-default">
    <div class="panel-heading"><?PHP echo $admin->wordTrans($admin->getUserLang(),'INFO'); ?></div>
    <div class="panel-body"><?php echo phpinfo(); ?></div>
    <div class="panel-footer"></div>

</div>