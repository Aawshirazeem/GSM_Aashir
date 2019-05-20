<style>
    body,td,th {
        font-family: Georgia, "Times New Roman", Times, serif;
        color: #333;
    }
    .contents{
        margin: 20px;
        padding: 20px;
        list-style: none;
        background: #F9F9F9;
        border: 1px solid #ddd;
        border-radius: 5px;
    }
    .contents li{
        margin-bottom: 10px;
    }
    .loading-div{
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.56);
        z-index: 999;
        display:none;
    }
    .loading-div img {
        margin-top: 20%;
        margin-left: 50%;
    }

    /* Pagination style */
    .pagination{margin:0;padding:0;}
    .pagination li{
        display: inline;
        padding: 6px 10px 6px 10px;
        border: 1px solid #ddd;
        margin-right: -1px;
        font: 15px/20px Arial, Helvetica, sans-serif;
        background: #FFFFFF;
        box-shadow: inset 1px 1px 5px #F4F4F4;
    }
    .pagination li a{
        text-decoration:none;
        color: rgb(89, 141, 235);
    }
    .pagination li.first {
        border-radius: 5px 0px 0px 5px;
    }
    .pagination li.last {
        border-radius: 0px 5px 5px 0px;
    }
    .pagination li:hover{
        background: #CFF;
    }
    .pagination li.active{
        background: #F0F0F0;
        color: #333;
    }
</style>
<?php
defined("_VALID_ACCESS") or die("Restricted Access");

$validator->formSetAdmin('email_user_list_78454349971255d2');






$filter = 'A';

if (($request->PostStr('filter')) && $request->PostStr('filter') != '') {

    $filter = $request->PostStr('filter');
}

//var_dump($_REQUEST);

if ($_REQUEST['id'] != '') {



    $emailidd = $_REQUEST['id'];

    $sql = 'select * from ' . MAIL_HISTORY . ' a where a.id=' . $emailidd . '   order by a.date_time desc limit 1';

    // echo $sql;exit;

    $result = $mysql->getResult($sql);

    if ($result['COUNT']) {

        foreach ($result['RESULT'] as $row) {

            $mailsub = $row['subject'];

            $mainbody = $row['content'];
        }
    }



    // $mailsub = $_REQUEST['sub'];
    // $mainbody = $_REQUEST['body'];
    //echo $mailsub.$mainbody;exit;
}
?>



<div id="startSendEmailsWait" class="TA_C hidden">

    <?php echo $graphics->messageBox("Sending emails..."); ?>

</div>





<div class="row">

    <div class="col-md-8">

        <div class="">

            <h4 class="panel-heading m-b-20"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_email_to_users')); ?></h4>

            <div class="panel-body">



                <div class="form-group">

                    <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Mail_subject')); ?></label>

                    <input type="text" name="subject" class="form-control" value="<?php echo $mailsub; ?>" />

                </div>

                <div class="form-group">

                    <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Mail_Body')); ?></label>

                    <div class="clearfix"></div>

                    <textarea id="editor1" name="editor1" class="ckeditor" ><?php echo $mainbody; ?></textarea>

                    <div class="clearfix"></div>

                </div>

            </div> <!-- / panel-body -->

            <div id="startSendEmails" class="panel-footer TA_C">

                <a href="javascript:startEmails();" class="btn btn-success"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_send')); ?></a>

                <a href="<?php echo CONFIG_PATH_SITE_ADMIN ?>massemail_log.html" class="btn btn-info"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_History')); ?></a>



            </div> <!-- / panel-footer -->

        </div> <!-- / panel -->

    </div> <!-- / col-lg-6 -->

</div> <!-- / row -->



<div align="center">

    <form name="user_filter" method="post" action="">

        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_User_Filter')); ?></label>

        <select name="filter" onchange="this.form.submit();">

            <option value="A" <?= ($filter == 'A' ? 'selected="selected"' : ''); ?>><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_All_Users')); ?></option>

            <option value="R" <?= ($filter == 'R' ? 'selected="selected"' : ''); ?>><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Registered')); ?></option>

            <option value="U" <?= ($filter == 'U' ? 'selected="selected"' : ''); ?>><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Un_Registered')); ?></option>

        </select>

    </form>

</div>

<div class="loading-div"><img src="ajax-loader.gif" ></div>
<div id="results2"><!-- content will be loaded here --></div>


<div class="FL PT5 PB5 PL5 text_11 text_black">

    <i class="fa fa-level-up fa-flip-horizontal"></i>

    <a href="#" value="EmailItems" class="selectAllBoxesLink tab0"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Check_All')); ?></a> / 

    <a href="#" value="EmailItems" class="unselectAllBoxesLink tab0"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Uncheck_All')); ?></a>

</div>



<!-- <script type="text/javascript">

    setPathsAdmin('<?php echo CONFIG_PATH_SITE_ADMIN ?>');

            document.getElementById('editor1').value = '<?php echo $mainbody; ?>';

</script>-->



<script type="text/javascript" src="<?php echo CONFIG_PATH_ASSETS; ?>ckeditor/ckeditor.js"></script>
<script type="text/javascript">
            setPathsAdmin('<?php echo CONFIG_PATH_SITE_ADMIN ?>');
            document.getElementById('editor1').value = '<?php echo $mainbody; ?>';
            </script>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.3.js"></script>
<script type="text/javascript">
            $(document).ready(function () {
                $("#results2").load("<?php echo CONFIG_PATH_SITE_ADMIN; ?>fetch_u_list.do", {"filter":'<?php echo $filter; ?>'}); //load initial records

                //executes code below when user click on pagination links
                $("#results2").on("click", ".pagination a", function (e) {
                    e.preventDefault();
                    $(".loading-div").show(); //show loading element
                    var page = $(this).attr("data-page"); //get page number from link
                    $("#results2").load("<?php echo CONFIG_PATH_SITE_ADMIN; ?>fetch_u_list.do", {"page": page, "filter":'<?php echo $filter; ?>'}, function () { //get content from PHP page
                        $(".loading-div").hide(); //once done, hide loading element
                    });

                });
            });
</script>