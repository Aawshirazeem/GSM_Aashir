<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<link rel="stylesheet" href="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/plugins/bootstrap-select/css/bootstrap-select.min.css">
                    <a href="<?php echo CONFIG_PATH_SITE_ADMIN . 'mass_email.html'; ?> " class="btn btn-danger pull-right"></i> <?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_mass_mail')) ?></a>

<h4 class="panel-heading m-b-20"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_select_receptionist')); ?></h4><hr>


<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>mass_email2_process.html" method="POST">
<div class="form-group col-lg-12">
    <b><label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_mail_template')); ?></label></b>
    <input type="radio" name="mail_to" class="" value="1" /><?php echo $admin->wordTrans($admin->getUserLang(), 'Manual'); ?>
    <input type="radio" name="mail_to" class="" value="2" checked/><?php echo $admin->wordTrans($admin->getUserLang(), 'From List'); ?>
    <select class="selectpicker" style="background:red" multiple name="e_list[]"  data-live-search="true" data-style="btn-primary" multiple title="Pick Email Template">
        <?php
        $sql2 = 'select distinct(a.id),a.name from nxt_elist a
inner join nxt_elistdetail b
on a.id=b.e_id and b.`status`=1';
        $result = $mysql->getResult($sql2);
        if ($result['COUNT']) {
            foreach ($result['RESULT'] as $row2) {
                ?>
                <option value="<?php echo $row2['id']; ?>"><?php echo $row2['name']; ?></option>
                <?php
            }
        }
        ?>

    </select>
    <a href="<?php echo CONFIG_PATH_SITE_ADMIN . 'elist.html'; ?> " class="btn btn-success"> <i class="fa fa-plus"></i> <?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_add_email_list')) ?></a>


</div>
<div class="form-group col-lg-12">
    <b><label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_User_List')); ?></label></b>

    <select class="selectpicker" style="background:red" multiple name="u_list[]"  data-live-search="true" data-style="btn-primary" multiple title="Pick User List">
        <?php
        $sql2 = 'select distinct(a.id),a.name from nxt_ulist a
inner join nxt_ulistdetail b
on a.id=b.u_id and b.`status`=1';
        $result = $mysql->getResult($sql2);
        if ($result['COUNT']) {
            foreach ($result['RESULT'] as $row2) {
                ?>
                <option value="<?php echo $row2['id']; ?>"><?php echo $row2['name']; ?></option>
                <?php
            }
        }
        ?>

    </select>

    <a href="<?php echo CONFIG_PATH_SITE_ADMIN . 'ulist_desc.html'; ?> " class="btn btn-success"> <i class="fa fa-plus"></i> <?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_add_user_list')) ?></a>
    <hr>
</div>
<div class="row">

    <div class="col-md-10">

        <div class="panel panel-info">

            <h4 class="panel-heading m-b-20"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Manual_email')); ?></h4>
            <hr>
            <div class="panel-body">

                <div class="form-group col-lg-12">

                    <label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Mail_subject')); ?></label>

                    <input type="text" name="subject" class="form-control" value="<?php echo $mailsub; ?>" />

                </div>
                <div class="form-group col-lg-12">

                    <label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Mail_Body')); ?></label>

                    <div class="clearfix"></div>

                    <textarea id="editor1" name="editor1" class="ckeditor" ><?php echo $mainbody; ?></textarea>

                    <div class="clearfix"></div>

                </div>

            </div> <!-- / panel-body -->

            <div id="startSendEmails" class="panel-footer TA_C" style="padding:15px 0; margin-left:12px; float:left; clear:both">
                <input type="submit" name="mail_send" value="send" class="btn btn-primary">
            </div> <!-- / panel-footer -->

        </div> <!-- / panel -->

    </div> <!-- / col-lg-6 -->
</div> <!-- / row --></form>
<script type="text/javascript" src="<?php echo CONFIG_PATH_ASSETS; ?>ckeditor/ckeditor.js"></script>
<script type="text/javascript">
    setPathsAdmin('<?php echo CONFIG_PATH_SITE_ADMIN ?>');
    document.getElementById('editor1').value = '<?php echo $mainbody; ?>';
</script>
<script
    src="https://code.jquery.com/jquery-1.12.4.js"
    integrity="sha256-Qw82+bXyGq6MydymqBxNPYTaUXXq7c8v3CwiYwLLNXU="
    crossorigin="anonymous">
</script>

<script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/plugins/bootstrap-select/js/bootstrap-select.min.js" type="text/javascript"></script>