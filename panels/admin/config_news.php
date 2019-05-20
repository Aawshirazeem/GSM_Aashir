<?php

	defined("_VALID_ACCESS") or die("Restricted Access");

	

	$admin = new admin();

	$mysql = new mysql();

?>

<div class="row m-b-20">

	<div class="col-xs-12">

    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>

            <li class="slideInDown wow animated">CMS</li>           

             <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Manage_News')); ?></li>

        </ol>

    </div>

</div>





	<div class="col-lg-10">

		


		<table class="table table-hover table-striped">

			<tr>

				

				<th width="16"></th>

			

				<th width="" style=""><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_News')); ?></th>

                                <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Action')); ?></th>

			</tr>

			<?php

				$sql= 'select * from ' . NEWS_MASTER . ' rm  limit 1';

				$query = $mysql->query($sql);

				$strReturn = "";

				$i = 1;

				if($mysql->rowCount($query) > 0)

				{

					$rows = $mysql->fetchArray($query);

					foreach($rows as $row)

					{

						echo '<tr>';

					

						echo '<td>' . $graphics->status($row['publish']) . '</td>';

						

						echo '</td>';

                                                echo '<td>' . $row['news'] . '';

						echo '</td>';

						echo '<td>

								<a href="' . CONFIG_PATH_SITE_ADMIN . 'config_news_edit.html?id=' . $row['id'] . '" class="btn btn-primary btn-sm">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_edit')) . '</a>

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

</div>

