<?php

	defined("_VALID_ACCESS") or die("Restricted Access");

	

	$request = new request();

	$mysql = new mysql();

	

    

    

	$id = $request->GetInt('id');



	

	$sql ='select * from ' . NEWS_MASTER . ' where id=' . $mysql->getInt($id);

	$query = $mysql->query($sql);

	$rowCount = $mysql->rowCount($query);

	if($rowCount == 0)

	{

		header("location:" . CONFIG_PATH_SITE_ADMIN . "config_news.html?msg=" . urlencode('Invalid news ID, Please try again!') . "&msgType=" . urlencode('error'));

		exit();

	}

	$rows = $mysql->fetchArray($query);

	$row = $rows[0];

	

?>



<div class="row m-b-20">

	<div class="col-xs-12">

    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>

            <li class="slideInDown wow animated"><?php echo $admin->wordTrans($admin->getUserLang(),'Master'); ?></li>

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>config_news.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_manage_news')); ?></a></li>           

             <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_setting')); ?></li>

        </ol>

    </div>

</div>



<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>config_news_edit_process.do" method="post">

	<div class="row">

		<div class="col-md-6">

			<div class="">

				<h4 class="m-b-20"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_news')); ?></h4>

				<div class="panel-body">

					<div class="form-group">

						<label> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_news_title')); ?> </label>

						

						<input name="id" type="hidden" id="id" value="<?php echo $row['id']?>" />

					</div>

					<div class="form-group">

						<textarea cols="80" id="news" class="ckeditor form-control" name="news" rows="10"><?php echo $row['news']?></textarea>

					</div>

					<div class="form-group">

                    <div class="animated-switch"> <input type="checkbox" name="publish" value="0" <?php echo (($row['publish'] == '1') ? 'checked="checked"' : '');?> id="switch-success"/>  <label for="switch-success" class="label-success"></label><span class="m-l-10"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_publish_news')); ?></span></div>     

						

					</div>

					<div class="form-group">

						<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>config_news.html" class="btn btn-danger btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_cancel')); ?></a>

						<input type="submit" value=" <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_news')); ?>" class="btn btn-success btn-sm" />

					</div>

				</div> <!-- / panel-body -->

 <!-- / panel-footer -->

			</div> <!-- / panel -->

		</div> <!-- / col-lg-6 -->

	</div> <!-- / row -->



  </form>

