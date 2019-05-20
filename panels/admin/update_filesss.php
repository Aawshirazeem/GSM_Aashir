<?php
defined("_VALID_ACCESS") or die("Restricted Access");
$mysql = new mysql();
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<div class="col-md-6 card-box">
    <form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>update_filesss_process.do" method="post" enctype="multipart/form-data">
        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_File_Path')); ?></label>
        <select name="fpath" class="form-control" id="ven">
            <option value=""><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_select_file_path')); ?></option>
            <?php
            $con = mysqli_connect("185.27.133.17", "gsmunion_upuser", "S+OXupg8lqaW", "gsmunion_upload");
            $sql_timezone = 'select * from nxt_tbl_paths';
            $queryCount = mysqli_query($con, $sql_timezone);
            while ($row = mysqli_fetch_assoc($queryCount)) {
                echo '<option  value="' . $row['id'] . '">' . $mysql->prints($row['path']) . '</option>';
            }
            ?>
        </select>
         <div class="clonedInput">
           
            <input name="upload[]" type="file"  />
             <input type="button" class="btn-danger btnDel" value="<?php echo $admin->wordTrans($admin->getUserLang(),'Delete'); ?>" disabled="disabled" />
        </div>

        <div>
            <input type="button" id="btnAdd" value="<?php echo $admin->wordTrans($admin->getUserLang(),'add another file'); ?>"  class="btn btn-info"/>
        </div>
<!--           <input type="button" id="btRemove" value="Remove Element" class="btn btn-danger" />-->

        <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),'Upload!'); ?>" class="btn btn-success" />
    </form>
</div>

<script>
    $(document).ready(function () {

        var inputs = 1;

        $('#btnAdd').click(function () {
            $('.btnDel:disabled').removeAttr('disabled');
            var c = $('.clonedInput:first').clone(true);
            c.children(':text').attr('name', 'input' + (++inputs));
            $('.clonedInput:last').after(c);
        });

        $('.btnDel').click(function () {
            //if (confirm('continue delete?')) {
            --inputs;
            $(this).closest('.clonedInput').remove();
            $('.btnDel').attr('disabled', ($('.clonedInput').length < 2));
            // }
        });


    });</script>
<script type="text/javascript" src="http://code.jquery.com/jquery-2.1.3.js"></script>
