<?php
defined("_VALID_ACCESS") or die("Restricted Access");
$sql = 'select * from ' . PACKAGE_MASTER;
$query = $mysql->query($sql);
$check1 = 0;
$check_mailto = 1;
$check_userstatus = 2;

if ($_REQUEST['history'] != '') {
    $emailidd = $_REQUEST['history'];
    $sql = 'select * from ' . MAIL_HISTORY . ' a where a.id=' . $emailidd . '   order by a.date_time desc limit 1';
    $result = $mysql->getResult($sql);
    if ($result['COUNT']) {
        foreach ($result['RESULT'] as $row) {
            $mailsub = $row['subject'];
            $mainbody = $row['content'];
        }
    }
}
if (isset($_POST['mail_send'])) {
    //form submit
    //echo '<pre>';
    //var_dump($_POST);
    // first check user group is post or not

    if (isset($_POST['u_grp']) && $_POST['u_grp'] != -1) {
        // also check the check box value
        if (isset($_POST['res_user']))
            $check1 = 1;
        //  echo 'grp is set and check is:' . $check1;
        // now get all the emails of that grp
        $sqluser = 'select  um.username,um.email from ' . PACKAGE_USERS . ' pu
inner join ' . USER_MASTER . ' um 
on pu.user_id=um.id
where pu.package_id=' . $_POST['u_grp'];

        if ($check1 == 1)
            $sqluser.= ' and um.user_type!=1';


        //echo $sqluser;
    } else {
        // other options
        // echo 'other';

        if (isset($_POST['mail_to']))
            $check_mailto = $_POST['mail_to'];

        if (isset($_POST['user_status']))
            $check_userstatus = $_POST['user_status'];

        if ($check_mailto == 1) {
            $sqluser = 'select um.username,um.email from ' . USER_MASTER . ' um';

            if ($check_userstatus == 1)
                $sqluser.=' where um.status=1';

            if ($check_userstatus == 0)
                $sqluser.=' where um.status=0';
        }
        else if ($check_mailto == 2) {
            $sqluser = 'select um.username,um.email from ' . SUPPLIER_MASTER . ' um';
            if ($check_userstatus == 1)
                $sqluser.=' where um.status=1';

            if ($check_userstatus == 0)
                $sqluser.=' where um.status=0';
        }
        elseif ($check_mailto == 3) {
            // print_r($_POST['u_list']);
            $in = "";

            if (isset($_POST['u_list'])) {
                foreach ($_POST['u_list'] as $a)
                    $in.=$a . ',';

                $in = rtrim($in, ',');

                $sqluser = 'select um.username,um.email from ' . USER_MASTER . ' um where um.id in (' . $in . ')';
            } else
                $sqluser = 'select um.username,um.email from ' . USER_MASTER . ' um where um.id in (0)';

            if ($check_userstatus == 1)
                $sqluser.=' and um.status=1';

            if ($check_userstatus == 0)
                $sqluser.=' and um.status=0';
        }
        else {
            // new op
              $sqluser = 'select * from nxt_ulistdetail2 abc where abc.`status`=1';
        }
    }

    // get the sql query from above first
    //echo $sqluser;
    $result = $mysql->getResult($sqluser);
    if ($result['COUNT']) {
        $subject = $_REQUEST['subject'];
        $body = $_REQUEST['editor1'];
        //var body = CKEDITOR.instances['editor1'].getData();


        $sqlget = 'select * from nxt_mail_history a where a.user_id=777777 and a.subject=' . $mysql->quote($subject) . ' and a.content=' . $mysql->quote($body) . '';
        $result2 = $mysql->getResult($sqlget);
        if ($result2['COUNT'] == 0) {

            $sqlin = 'insert into ' . MAIL_HISTORY . '(user_id, date_time, subject, content, plain_mail) values(

					' . $mysql->getInt(777777) . ',

					now(),

					' . $mysql->quote($subject) . ',

					' . $mysql->quote($body) . ',

					' . $mysql->quote($body) . '

					)';

            $mysql->query($sqlin);
        }

        $emailObj = new email();
        $email_config = $emailObj->getEmailSettings();

        $from = $email_config['system_email'];
        $from_display = $email_config['system_from'];
        $signatures = "<br /><br /><br /><br />" . nl2br($email_config['admin_signature']);


        foreach ($result['RESULT'] as $umail) {

            $objEmail = new email();
            $objEmail->setTo($umail['email']);
            $objEmail->setFrom($from);
            $objEmail->setFromDisplay($from_display);
            $objEmail->setSubject($subject);
            $objEmail->setBody($body . $signatures);
            // $sent = $objEmail->sendMail();
            $objEmail->queue();
        }
    }

    echo 'Email(s) SENT';
}
?> 


<link rel="stylesheet" href="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/plugins/bootstrap-select/css/bootstrap-select.min.css">

<div class="">



    <div class="row">
        <form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>mass_email.html" method="POST">
            <div class="col-md-12">


                <div class="panel panel-success">

                    <h4 class="panel-heading m-b-20"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_select_recipient')); ?></h4><hr>

                    <div class="panel-body">
                        <div class="form-group col-lg-12">
                            <b><label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_mail_to')); ?></label></b>
                            <input type="radio" name="mail_to" class="" value="1" checked/><?php echo $admin->wordTrans($admin->getUserLang(), 'Users'); ?>
                            <input type="radio" name="mail_to" class="" value="2" /><?php echo $admin->wordTrans($admin->getUserLang(), 'Suppliers'); ?>
                            <input type="radio" name="mail_to" class="" value="4" /><?php echo $admin->wordTrans($admin->getUserLang(), 'Users + Ads Email'); ?>

                            <input type="radio" name="mail_to" class="" value="3" /><?php echo $admin->wordTrans($admin->getUserLang(), 'Picked Users'); ?>

                            <select class="selectpicker" style="background:red" multiple name="u_list[]"  data-live-search="true" data-style="btn-primary" multiple title="Pick User From List">
                                <?php
                                $sql2 = 'select um.id,um.email,um.username from ' . USER_MASTER . ' um ';
                                $result = $mysql->getResult($sql2);
                                if ($result['COUNT']) {
                                    foreach ($result['RESULT'] as $row2) {
                                        ?>
                                        <option value="<?php echo $row2['id']; ?>"><?php echo $row2['username']; ?></option>
                                        <?php
                                    }
                                }
                                ?>

                            </select>

                            <a href="<?php echo CONFIG_PATH_SITE_ADMIN . 'download_emailist.html'; ?> " class="btn btn-default pull-right" title="EXPORT EMAILS" ><i class="fa fa-download" aria-hidden="true"></i></a>
                            <a href="<?php echo CONFIG_PATH_SITE_ADMIN . 'ulist_desc.html'; ?> " class="btn btn-success pull-right"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_add_email_list')) ?></a>

                        </div>

                        <div class="form-group col-lg-12">
                            <b> <label style=""><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_user_status')); ?></label></b>
                            <input type="radio" name="user_status" class="" value="2" checked/><?php echo $admin->wordTrans($admin->getUserLang(), 'All'); ?>
                            <input type="radio" name="user_status" class="" value="1" /><?php echo $admin->wordTrans($admin->getUserLang(), 'Active'); ?>
                            <input type="radio" name="user_status" class="" value="0" /><?php echo $admin->wordTrans($admin->getUserLang(), 'InActive'); ?>
                        </div>

                        <div class="form-group col-lg-4">

                            <b> <label style=""><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_user_group')); ?></label></b>

                            <select name="u_grp" class="form-control" id="u_grp">
                                <option value="-1"><?php echo $admin->wordTrans($admin->getUserLang(), 'Select'); ?></option>
                                <?php
                                if ($mysql->rowCount($query) > 0) {
                                    $rows = $mysql->fetchArray($query);
                                    foreach ($rows as $row) {
                                        ?>
                                        <option value="<?php echo $row['id']; ?>"><?php echo $row['package_name']; ?></option>

                                        <?php
                                    }
                                }
                                ?>

                            </select>
                        </div>
                        <div class="form-group col-lg-4">
                            <b><label style="color:#ffffff;">Do not send to Reseller user</label></b>						
                            <label><?php echo $admin->wordTrans($admin->getUserLang(), 'Do not send to Reseller user'); ?></label>
                            <input type="checkbox"  name="res_user" id="res_user" value="1"/>

                        </div>

                    </div> <!-- / panel-body -->

                    <div>


                    </div> <!-- / panel-footer -->

                </div> <!-- / panel -->

            </div> <!-- / col-lg-6 -->

    </div> <!-- / row -->

    <div class="row">

        <div class="col-md-10">

            <div class="panel panel-info">

                <h4 class="panel-heading m-b-20"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_select_template')); ?></h4>
                <hr>
                <div class="panel-body">

                    <div class="form-group col-lg-6">

                        <label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Mail_subject')); ?></label>

                        <input type="text" name="subject" class="form-control" value="<?php echo $mailsub; ?>" />

                    </div>
                    <div class="col-lg-1">
                        <b><label>Or</label></b>
                    </div>

                    <!--                <div class="form-group">
                    
                                        <label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_from_mail')); ?></label>
                    
                                        <input type="text" name="frm_mail" class="form-control" value="" />
                    
                                    </div>-->

                    <div class="form-group col-lg-5">

                        <label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_history')); ?></label>

                        <select name="history" id="history" class="form-control" onchange="this.form.submit()">
                            <option value="-1">select</option>
                            <?php
                            $sql2 = 'select * from ' . MAIL_HISTORY . ' a where a.user_id=777777 order by a.date_time desc';
                            $result = $mysql->getResult($sql2);
                            if ($result['COUNT']) {
                                foreach ($result['RESULT'] as $row2) {
                                    ?>
                                    <option value="<?php echo $row2['id']; ?>"><?php echo $row2['subject']; ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>

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

<!--                <a href="javascript:startEmails();" class="btn btn-success"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_send')); ?></a>-->

    <!--                <a href="<?php echo CONFIG_PATH_SITE_ADMIN ?>massemail_log.html" class="btn btn-info"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_History')); ?></a>-->



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
crossorigin="anonymous"></script>

<script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/plugins/bootstrap-select/js/bootstrap-select.min.js" type="text/javascript"></script>