<?php

	defined("_VALID_ACCESS") or die("Restricted Access");

	

	$search_user = $request->GetStr('search_user');

	$search_ip = $request->GetStr('search_ip');



	$clearSearch = false;

	$paging = new paging();

	$offset = (isset($_GET["offset"])) ? $_GET["offset"] : 0;

	$i = $offset;

	$qType = '';

	$i++;

	$limit = CONFIG_ORDER_PAGE_SIZE;

	$qLimit = " limit $offset,$limit";

	$extraURL = '&search_user=' . $search_user;

	$from_date=$request->getstr("from_date");

	$to_date=$request->getstr("to_date");

	if($search_user !='')

	{

		$qType .= (($qType == '') ? ' where ' : ' and ') . ' username like "%' . $search_user.'%"';

	$clearSearch = true;

	}

	if($from_date != '' && $to_date != '')

	{

		$from_date_search=date('Ymd',strtotime($from_date));

		$to_date_search=date('Ymd',strtotime($to_date));

		$qType .= (($qType=='')? ' where ' :' and ').' date(date_time) between ' . $from_date_search . ' and ' . $to_date_search;

	$clearSearch = true;

	}

	if($search_ip !='')

	{

		$qType .= (($qType == '') ? ' where ' : ' and ') . ' ip="' . $search_ip.'"';

	$clearSearch = true;

	}

?>

	

	<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="searchPanel" class="modal fade">

	  <div class="modal-dialog">

		  <div class="modal-content">

			  <div class="modal-header">

				  <button aria-hidden="true" data-dismiss="modal" class="close" type="button"><i class="fa fa-remove"></i></button>

				  <h4 class="modal-title"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_search')); ?></h4>

			  </div>

			  <div class="modal-body">

				  <form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_admin_login_log.html" method="get">

					<div class="form-group">

						<div class="row">

							<div class="col-sm-6" data-date-format="dd-mm-yyyy">

								<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_from')); ?></label>

								<input type="text" id="fdt" name="from_date" value="<?php echo $from_date; ?>" class="form-control dpd1"/>

							</div>

							<div class="col-sm-6" data-date-format="dd-mm-yyyy">

								<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_to')); ?></label>

								<input type="text" id="ldt" name="to_date" value="<?php echo $to_date; ?>" class="form-control dpd2"/>

							</div>

						</div>

					</div>

					<div class="form-group">

						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_username')); ?> </label>

						<input type="text" name="search_user" class="form-control" value="<?php echo $search_user; ?>">

					</div>

					<div class="form-group">

						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_ip')); ?> </label>

						<input type="text" name="search_ip" class="form-control" value="<?php echo $search_ip; ?>">

					</div>

					<div class="form-group">

						<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_search')); ?>" class="btn btn-success" />

					</div>

				  </form>

			  </div>

		  </div>

	  </div>

	</div>



	<div class="WD60 FC">

		<?php

			if(trim($search_user) != '' or trim($search_ip) != '')

			{
				$msg=$admin->wordTrans($admin->getUserLang(),$lang->get('lbl_click_here_reset_the_search_options'));
				echo $graphics->messageBox('<a href="' . CONFIG_PATH_SITE_ADMIN . 'report_api_error_log.html">'.$msg.'</a>');

			}

		?>

	</div>





<div class="row m-b-20">

	<div class="col-xs-12">

    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>

            <li class="slideInDown wow animated"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Reports')); ?></li>           

             <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Admin_Login_Log')); ?></li>

        </ol>

    </div>

</div>





<div class="row">

	<div class="col-sm-12">

		<div class="m-t-10">



			<h4 class="panel-heading m-b-20">

				<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Admin_Login_Log')); ?>

				<div class="btn-group btn-group-sm pull-right">	

				<a href="report_admin_login_log.html" class="btn btn-primary"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Admin_Login_Log')); ?></a>

				<a href="report_user_login_log.html" class="btn btn-primary"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_user_login_log')); ?></a>

				<a href="#searchPanel" data-toggle="modal" class="btn btn-warning"> <i class="fa fa-search"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_search')); ?> </a>

				<?php if($clearSearch == true){ ?><a href="<?php echo CONFIG_PATH_SITE_ADMIN?>report_admin_login_log.html" data-toggle="modal" class="btn btn-danger btn-xs pull-right"><i class="fa fa-undo"></i></a> <?php } ?>

				</div>

			</h4>

		<div class="table-responsive">

			<table class="table table-hover table-striped">

				<tr>

					<th width="10"></th>

					<th width="10"></th>

					<th class="TA_C"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_date')); ?> </th>

					<th class="TA_C"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_username')); ?> </th>

					<th class="TA_C"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_status')); ?> </th>

					<th class="TA_C"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_ip')); ?> </th>

				</tr>

				<?php

					

					

					//$qType = ($qType == '') ? '' : ' where ' . $qType;

		

					$sql = 'select *

							from ' . STATS_ADMIN_LOGIN_MASTER .' '. $qType .' 

							 order by id desc'; 

					$query = $mysql->query($sql. $qLimit);

		

					$pCode = $paging->recordsetNav($sql,CONFIG_PATH_SITE_ADMIN . 'report_admin_login_log.html',$offset,$limit,$extraURL);

		

					if($mysql->rowCount($query) > 0)

					{

						$logins = $mysql->fetchArray($query);

						foreach($logins as $login)

						{

				?>

							<tr>

								<td><small><?php echo $i++ ?></small></td>

								<td><?php //if($login['username'] != ''){ echo '<i class="icon-arrow-left"></i>'; } ?></td>

								<td><?php 
                                                                echo   $finaldate = $admin->datecalculate($login['date_time']);
                                                                
                                                                ?>
                                                                </td>

								<td><?php echo $login['username'];?></td>

								<td <?php echo ($login['success'] == 0) ? 'style="font-weight:bold"' : '';?>><?php echo (($login['success']==1)?'success':'Unsuccess');?></td>

								<td><?php echo $login['ip'];?></td>

					

							</tr>

				<?php

						}

					}

					else

					{

						echo '<tr><td colspan="6">No matching record found</td></tr>';

					}

				?>

			</table>

		</div>
		
		</div>
		
        <div class="row m-t-20">
            <div class="col-md-6 p-l-0">
                <div class="TA_C navigation" id="paging">
                    <?php  echo $pCode;  ?>
                </div>
            </div>
            <div class="col-md-6">
                
            </div>
        </div>

	</div>

</div>