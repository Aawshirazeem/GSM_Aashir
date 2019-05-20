<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
?>
<div class="row">
	<div class="col-lg-12">
		<ul class="breadcrumb">
			<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><i class="fa fa-home"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
			<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),'Master'); ?></li>
			<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),'Manage Resellers'); ?></li>
		</ul>
	</div>
</div>

	<section class="panel MT10">
		<div class="panel-heading">
			<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_manage_resellers')); ?>
				<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>config_reseller_add.html" class="btn btn-danger btn-xs pull-right"> <i class="fa fa-plus"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_new_reseller')); ?> </a>
		</div>

		<table class="table table-hover table-striped">
	<tr>
		<th width="16"></th>
		<th width="16"></th>
		<th width="16"></th>
		<th> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_reseller')); ?> </th>
		<th> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_country')); ?> </th>
		<th> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_email')); ?> </th>
		<th width="100" style="text-align:center"></th>
	</tr>
	<?php
		$sql= 'select rm.*, cm.countries_name as CountryName
						from ' . RESELLER_MASTER . ' rm 
						left join ' . COUNTRY_MASTER . ' cm on (rm.country = cm.id)
						order by reseller';
		$query = $mysql->query($sql);
		$strReturn = "";
		$i=1;
		if($mysql->rowCount($query) > 0)
		{
			$rows = $mysql->fetchArray($query);
			foreach($rows as $row)
			{
				echo '<tr>';
				echo '<td>' . $i++ . '</td>';
				echo '<td>' . $graphics->status($row['status']) . '</td>';
				echo '<td>';
					switch($row['type'])
					{
						case '0';
							echo '<i class="fa fa-globe"></i>';
							break;
						case '1';
							echo '<i class="fa fa-user"></i>';
							break;
						case '2';
							echo '<i class="fa fa-group"></i>';
							break;
					}
				echo '</td>';
				echo '<td>' . $row['reseller'] . '';
				echo '</td>';
				echo '<td>' . $row['CountryName'] . '</td>';
				echo '<td>' . $row['email'] . '</td>';
				echo '<td>
						<a href="' . CONFIG_PATH_SITE_ADMIN . 'config_reseller_edit.html?id=' . $row['id'] . '" class="btn btn-primary btn-sm">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_setting')) . '</a>
					  </td>';
				echo '</tr>';
			}
		}
		else
		{
			echo '<tr><td colspan="8" class="no_record">No record found!</td></tr>';
		}
	?>
	</table>
</section>
