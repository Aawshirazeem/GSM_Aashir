<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


defined("_VALID_ACCESS") or die("Restricted Access");
$sql_timezone1 = 'select * from ' . SMTP_CONFIG;

$query_timezone1 = $mysql->query($sql_timezone1);

$rows_timezone1 = $mysql->fetchArray($query_timezone1);
$chat_code = $rows_timezone1[0]['chat_code'];
?>
<div class="container">
    <div class="row col-md-offset-1">
        <h4>Setup Custom Chat</h4><hr>
        <form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>custom_chat_process.do" method="post">


            <div class="form-group">

                <label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Chat_code')); ?> </label><br>

                <textarea rows="7" cols="70" name="chat_code"><?php echo $chat_code; ?></textarea>

            </div>
            <input type="submit" class="btn btn-success" value="Save"/>

        </form>
    </div>
</div>