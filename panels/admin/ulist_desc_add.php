<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

defined("_VALID_ACCESS") or die("Restricted Access");
//$id = $request->GetInt('id');
?>
<div class="row m-b-20">
    <div class="col-xs-12">
        <ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">
            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_dashboard')); ?></a></li>
            <li class="slideInDown wow animated active"><a href="<?php echo CONFIG_PATH_SITE_ADMIN . 'ulist_desc.html'; ?>"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_user_list')); ?></a></li>
            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Add_New_User_Email')); ?></li>
        </ol>
    </div>
</div>
<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>ulist_desc_add_process.do" method="post" name="form1">
    <div class="row">
        <div class="col-md-6">
            <h4 class="m-b-20">
                <?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Add_New')); ?>
            </h4>

            <!--            <div class="form-group">
                            <label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_name')); ?></label>
                            <input  name="name" type="text" class="form-control" required="" id="subSject" value="" />
            
                        </div>-->
            <div class="form-group">
                <label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_email')); ?></label>

                <textarea name="mail" class="form-control" id="mail" rows="10" required=""></textarea>

            </div>

            <div class="form-group">
                <a href="<?php echo CONFIG_PATH_SITE_ADMIN . 'ulist_desc.html'; ?>" class="btn btn-danger btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_cancel')); ?></a>
                <input type="submit" onclick="return ValidateEmail();" value="<?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_save')); ?>" name="submit" class="btn btn-success btn-sm" />
            </div>
        </div>
    </div>
</form>
<script type="text/javascript">
                    function ValidateEmail()

                    {
                        var chk = true;
                        var emails = [];
                        var lineno = 0;
                        var temp = 0;
                        var lineshow = "";
                        var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
                        var lines = $('#mail').val().split(/\n/);
                        if (lines[0] != "")
                        {
                            for (var i = 0; i < lines.length; i++) {
                                // alert(lines[i]);
                                if (!lines[i].match(mailformat))
                                {
                                    chk = false;
                                    emails[temp] = lines[i];
                                 //   lines[i] = lines[i]+' <--';
                                  //  $("#mail").html(lines.join("\n"));
                                    temp++;
                                    lineno = i + 1;
                                    lineshow += lineno + ',';

                                }

                            }
                          
                            lineshow = lineshow.replace(/,+$/g, "");
                            if (chk == false)
                            {
                                alert('Email Format Not Correct,Check Again on line ' + lineshow)
                            }
                            return chk;
                        }
                        else
                            alert('Enter Email List');
                    }
</script>