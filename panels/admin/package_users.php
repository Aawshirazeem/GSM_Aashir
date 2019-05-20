<?php

	defined("_VALID_ACCESS") or die("Restricted Access");

	$validator->formSetAdmin('user_group_edit_54964566h34');

	$paging = new paging();

    

	$package_id = $request->GetInt('package_id');

	$limit= $request->GetInt('limit');

	$offset = $request->GetInt('offset');

	$limit = 100;

	$qLimit = ' limit ' . $offset . ',' . $limit;

	$extraURL = '&limit=' . $limit . '&package_id=' . $package_id ;

	

?>





<div class="row m-b-20">

	<div class="col-xs-12">

    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>

            <li class="slideInDown wow animated"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_services')); ?></li>

            <li class="slideInDown wow animated active"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>package.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_special_package')); ?></a></li>

            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_users')); ?></li>

        </ol>

    </div>

</div>



<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>package_users_process.do" method="post">

  

		<input name="package_id" type="hidden" class="form-control" id="id" value="<?php echo $package_id?>" />

	<div class="row">

		<div class="col-sm-8">

			<section class="MT10">				

				<h4 class="m-b-20">

					<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_user_list')); ?>

				</h4>

				<table class="table table-striped table-hover panel">

					<tr>

						<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_users')); ?></th>

						<th width="60"></th>

					</tr>

					<?php

						$sql = 'select um.username,um.id as uid , pu.* 

									from ' . USER_MASTER . ' um

									left join ' . PACKAGE_USERS . ' pu on(um.id=pu.user_id and pu.package_id=' . $package_id . ')

									order by um.username ';

						$query = $mysql->query($sql . $qLimit);

						$pCode = $paging->recordsetNav($sql,CONFIG_PATH_SITE_ADMIN . 'package_users.html',$offset,$limit,$extraURL);

						$i = $offset + 1;

						if($mysql->rowCount($query)>0)

						{

							$rows = $mysql->fetchArray($query);

							foreach($rows as $row)

							{	

								echo '<tr>';

									echo '<td>

												' . (($row['package_id'] == $package_id) ? '' . $row['username'] . '' : $row['username']) . '

												<input type="hidden" name="user_ids[]" value="' . $row['uid'] . '" />

										</td>';

									echo '<td><label class="c-input c-checkbox"> <input type="checkbox" ' . (($row['package_id'] == $package_id) ? 'checked="checked"' : '') . ' name="checkids[]"  value="' . $row['uid'] . '"/><span class="c-indicator c-indicator-success"></label></td>';

								echo '</tr>';

							}

						}

						else

						{

							echo '<tr><td colspan="6" class="no_record">'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')).'</td></tr>';

						}

					?>

				</table>

			</section>

				<div class="form-group">

					<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>package.html" class="btn btn-danger btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_cancel')); ?></a>

					<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_allot')); ?>" class="btn btn-success btn-sm">

				</div>

		</div>

	</div>

</form>

<div class="row m-t-20">
	<div class="col-md-6 p-l-0">
    	<div class="TA_C navigation" id="paging">
			<?php  echo $pCode;  ?>
        </div>
    </div>
    <div class="col-md-6">
    	
    </div>
</div>