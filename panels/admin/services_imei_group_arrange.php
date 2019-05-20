<?php

	defined("_VALID_ACCESS") or die("Restricted Access");

?>

	<ul class="breadcrumb">

		<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><i class="fa fa-home"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>

		<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_services')); ?></li>

		<li class="active"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_tools.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei_service_tool_manager')); ?></a></li>

		<li class="active"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_group.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei_service_group_manager')); ?></a></li>

		<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei_service_group_sort')); ?></li>

	</ul>

<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_group_arrange_process.do" method="post">

	<div class="row">

		<div class="col-md-8">

			<div class="">

				<h4 class="panel-heading m-b-20">

					<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei_service_group_manager')); ?>

					<div class="btn-group pull-right">

						<a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_group.html" class="btn btn-danger btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),'Cancel'); ?></a>

					</div>

				</h4>

				<div class="panel-body">

			 		<div class="dd nestable_list" data-output="nestable_list_1_output">

						<textarea id="nestable_list_1_output" name="list" class="hidden col-lg-12 form-control"></textarea>

						<ol class="dd-list">

							<?php

								$sql= 'select *, (select count(id) from ' . IMEI_TOOL_MASTER . ' itm where itm.group_id=igm.id) as total from ' . IMEI_GROUP_MASTER . ' igm order by sort_order';

								$query = $mysql->query($sql);

								$strReturn = "";

								if($mysql->rowCount($query) > 0)

								{

									$rows = $mysql->fetchArray($query);

									$i = 0;

									foreach($rows as $row)

									{

										$i++;

										echo '<li class="dd-item" data-id="' . $mysql->prints($row['id']) . '">

												<div class="dd-handle">

													<div class="row">

														<div class="col-sm-11">

															' . $mysql->prints($row['group_name']) . '

														</div>

														<div class="col-sm-1 text-center">

															<span class="badge bg-important">' . $row['total'] . '</span>

														</div>

													</div>

												</div></li>';

									}

								}

								else

								{

									echo '<tr><td colspan="4" class="no_record">'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')).'</td></tr>';

								}

							?>

						</ol>

						<a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_tools.html" class="btn btn-danger">Cancel</a>

						<input type="submit" value="submit" class="btn btn-success" />

					</div>

				</div>

			</div>

		</div>

	</div>

</form>







<link rel="stylesheet" type="text/css" href="<?php echo CONFIG_PATH_ASSETS; ?>nestable/jquery.nestable.css" />

<script src="<?php echo CONFIG_PATH_ASSETS; ?>nestable/jquery.nestable.js" type="text/javascript"></script>

    <script class="include" type="text/javascript" src="<?php echo CONFIG_PATH_PANEL; ?>js/jquery.dcjqaccordion.2.7.js"></script>

    <script src="<?php echo CONFIG_PATH_PANEL; ?>js/jquery.scrollTo.min.js"></script>

    <script src="<?php echo CONFIG_PATH_PANEL; ?>js/jquery.nicescroll.js" type="text/javascript"></script>

    

<script>

var Nestable = function () {

	

    var updateOutput = function (e) {

        var list = e.length ? e : $(e.target),

            output = list.data('output');

            console.log(output);

        if (window.JSON) {

            output.val(window.JSON.stringify(list.nestable('serialize'))); //, null, 2));

        } else {

            output.val('JSON browser support required for this demo.');

        }

    };



    // activate Nestable for list 1

    $('.nestable_list').nestable({

        group: 1

    }).on('change', updateOutput);



    // output initial serialised data

    updateOutput($('.nestable_list').data('output', $('#' + $('.nestable_list').data('output'))));

    

}();

</script>



