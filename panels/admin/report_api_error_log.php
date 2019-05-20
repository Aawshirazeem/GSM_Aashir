<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	
	$search_user_id = $request->GetStr('search_user_id');
	$search_ip = $request->GetStr('search_ip');

	$paging = new paging();
	$offset = (isset($_GET["offset"])) ? $_GET["offset"] : 0;
	$i = $offset;
	$i++;
	$limit = 100;
	$qLimit = " limit $offset,$limit";
	$extraURL = '&search_user_id=' . $search_user_id;
	$clearSearch = false;
	$from_date=$request->getstr("from_date");
	$to_date=$request->getstr("to_date");
	$qType = '';

	if($search_user_id !='')
	{
		$qType .= (($qType == '') ? ' where ' : ' and ') . ' ael.user_id=' . $search_user_id;
	$clearSearch = true;
	}
	if($search_ip !='')
	{
		$qType .= (($qType == '') ? ' where ' : ' and ') . ' ael.ip="' . $search_ip.'"';
	$clearSearch = true;
	}
	if($from_date != '' && $to_date != '')
	{
		$from_date_search=date('Ymd',strtotime($from_date));
		$to_date_search=date('Ymd',strtotime($to_date));
		$qType .= (($qType=='')? ' where ' :' and ').' date(ael.date_time) between ' . $from_date_search . ' and ' . $to_date_search;
		$clearSearch = true;
	}
?>

<div class="row">
	<div class="col-lg-12">
		<ul class="breadcrumb">
			<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><i class="fa fa-home"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
			<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),'Reports'); ?></li>
			<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),'API Error Log'); ?></li>
		</ul>
	</div>
</div>

	<div class="toolbarSkin toolbarBig text-right WD60 FC"> 
		
	</div>
	<div class="clearfix"></div>
	
	<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="searchPanel" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
				  <button aria-hidden="true" data-dismiss="modal" class="close" type="button"><i class="fa fa-remove"></i></button>
				  <h4 class="modal-title"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_search')); ?></h4>
				</div>
				<div class="modal-body">
					<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_api_error_log.html" method="get">
							<div class="form-group">
								<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_username')); ?> </label>
								<select name="search_user_id" class="form-control">
									<option value="0"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_all_users')); ?> </option>
									<?php
										$sql_usr = 'select id, username from ' . USER_MASTER . ' order by username' ;
										$query_usr = $mysql->query($sql_usr);
										$rows_usr = $mysql->fetchArray($query_usr);
										foreach($rows_usr as $row_usr)
										{
											echo '<option ' . (($row_usr["id"] == $search_user_id) ? 'selected="selected"' : '') . ' value="' . $row_usr['id'] . '">' . $mysql->prints($row_usr['username']) . '</option>';
										}
									?>
								</select>
							</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-6" data-date-format="dd-mm-yyyy">
									<label><?php echo $admin->wordTrans($admin->getUserLang(),'From Date'); ?></label>
									<input type="text" id="fdt" name="from_date" value="<?php echo $from_date; ?>" class="form-control dpd1"/>
								</div>
								<div class="col-sm-6" data-date-format="dd-mm-yyyy">
									<label><?php echo $admin->wordTrans($admin->getUserLang(),'To Date'); ?></label>
									<input type="text" id="ldt" name="to_date" value="<?php echo $to_date; ?>" class="form-control dpd2"/>
								</div>
							</div>
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
			if(trim($search_user_id) != '' or trim($search_ip) != '')
			{
				$msg=$admin->wordTrans($admin->getUserLang(),$lang->get(lbl_click_here_reset_the_search_options));
				echo $graphics->messageBox('<a href="' . CONFIG_PATH_SITE_ADMIN . 'report_api_error_log.html">'.$msg.'</a>');
			}
		?>
	</div>

		<div class="row">
			<div class="col-sm-8 col-sm-offset-2">
				<section class="panel MT10">
						<div class="panel-heading">
							<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_API_error_log')); ?>
							<div class="btn-group pull-right">
								<a href="#searchPanel" data-toggle="modal" class="btn btn-warning btn-xs"><i class="fa fa-search"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_search')); ?> </a>
								<?php if($clearSearch == true){ ?><a href="<?php echo CONFIG_PATH_SITE_ADMIN?>report_api_error_log.html" class="btn btn-danger btn-xs pull-right"><i class="fa fa-undo"></i></a> <?php } ?>
							</div>
						</div>
					<table class="table table-striped table-hover">	
						<tr>
							<th width="10"></th>
							<th width="10"></th>
							<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_date')); ?> </th>
							<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_username')); ?> </th>
							<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_message')); ?> </th>
							<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_ip')); ?> </th>
						</tr>
						<?php
		
							//$qType = ($qType == '') ? '' : ' where ' . $qType;
		
							$sql = 'select ael.*, um.username
									from ' . API_ERROR_LOG .' as ael 
									left join ' . USER_MASTER . ' um on (ael.user_id = um.id)
									'. $qType .'
									 order by id desc'; 
							$query = $mysql->query($sql. $qLimit);
		
							$pCode = $paging->recordsetNav($sql,CONFIG_PATH_SITE_ADMIN . 'report_user_login_log.html',$offset,$limit,$extraURL);
		
							if($mysql->rowCount($query) > 0)
							{
								$logins = $mysql->fetchArray($query);
								foreach($logins as $login)
								{
						?>
									<tr>
										<td><small><?php echo $i++ ?></small></td>
										<td><?php //if($login['username'] != ''){ echo '<i class="icon-arrow-left"></i>'; } ?></td>
										<td><?php echo date("d-M Y H:i", strtotime($login['date_time']));?></td>
										<td><?php echo $login['username'];?></td>
										<td><?php echo $login['message'];?></td>
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
				</section>
			</div>
		</div>
<?php echo $pCode;?>	
