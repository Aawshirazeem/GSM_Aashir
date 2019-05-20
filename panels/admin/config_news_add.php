<?php

defined("_VALID_ACCESS") or die("Restricted Access");



$request = new request();

$mysql = new mysql();



$admin = new admin();

?>

<div class="row">

    <div class="col-lg-12">

        <ul class="breadcrumb">

            <li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><i class="fa fa-home"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>

            <li class="active">CMS</li>

            <li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>config_news.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_manage_news')); ?></a></li>

            <li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_news')); ?></li>

        </ul>

    </div>

</div>



<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>config_news_add_process.do" method="post">

    <div class="row">

        <div class="col-md-6">

            <div class="">

                <h4 class="panel-heading m-b-20"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_add_news')); ?></h4>

                <div class="panel-body">

                    <div class="form-group">

                        <label> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_news_title')); ?> </label>

                        <input name="title" type="text" class="form-control" id="title" value="" />

                    </div>

                    <div class="form-group">

                        <textarea cols="80" id="news" class="ckeditor form-control" name="news" rows="10"></textarea>

                    </div>

                    <div class="form-group">

                    	<div class="animated-switch"> <input type="checkbox" name="publish" value="0" id="switch-success" /> <label for="switch-success" class="label-success"></label><span class="m-l-10"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_publish_news')); ?></span></div>     

                    </div>

                    <div class="form-group">

                        <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>config_news.html" class="btn btn-danger btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_cancel')); ?></a>

                        <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_add_news')); ?>" class="btn btn-success btn-sm" />

                    </div>



                </div> <!-- / panel-body -->

                <!-- / panel-footer -->

            </div> <!-- / panel -->

        </div> <!-- / col-lg-6 -->

    </div> <!-- / row -->

</form>

