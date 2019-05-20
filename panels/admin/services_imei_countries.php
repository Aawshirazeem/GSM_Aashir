<?php

	defined("_VALID_ACCESS") or die("Restricted Access");

	

	$group_id = $request->getInt('group_id');

	$offset = $request->getInt('offset');

	

	$paging = new paging();

	

	$limit = 50;

	$qLimit = " limit $offset,$limit";

	$extraURL = '&';

	$sql_extra = "";

	$sql='select *, count(inm.id) as total

				from ' . IMEI_NETWORK_MASTER . ' inm

				left join ' . COUNTRY_MASTER . ' cm on (inm.country = cm.id)

				group by inm.country

				order by cm.countries_name';



				//,(select count(id) from ' . IMEI_NETWORK_MASTER . ' inm where inm.country = icm.id) as totalNetworks

	$query = $mysql->query($sql . $qLimit);

	$i = 1;

	$count = $mysql->rowCount($query);

	$pCode = $paging->recordsetNav($sql,CONFIG_PATH_SITE_ADMIN . 'services_imei_countries.html',$offset,$limit,$extraURL);

?>

<div class="row m-b-20">

    <div class="col-lg-12">

    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>

            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_settings')); ?></li>

            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_country_manager')); ?></li>

        </ol>

    </div>

</div>



<div class="row">

    <div class="col-md-12">

        <h4 class="m-b-20"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_country_manager')); ?></h4>
		
		<div class="table-responsive">

        <table class="table table-hover table-striped">

            <tr>

                <th width="16"> </th>

                <th width="16"></th>

                <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_countries')); ?> </th>

                <th width="150"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_total_networks')); ?></th>

                <th width="180"></th>

            </tr>

            <?php

                if($mysql->rowCount($query) > 0)

                {

                    $rows = $mysql->fetchArray($query);

                    $i = $offset;

                    foreach($rows as $row)

                    {

                        $i++;

                        echo '<tr>';

                        echo '<td>' . $i . '</td>';

                        echo '<td>' . $graphics->status($row['status']) . '</td>';

                        echo '<td>' . $row['countries_name'] . '</td>';

                        echo '<td>' . (($row['total'] == "0") ? '-' : $row['total']) . '</td>';

                        echo '<td class="text-right">

                                    <div class="btn-group">

                                        <a href="' . CONFIG_PATH_SITE_ADMIN . 'services_imei_networks.html?country_id=' . $row['id'] . '" class="btn btn-primary btn-sm">' . $admin->wordTrans($admin->getUserLang(),$lang->get("lbl_networks")) . '</a>

                                    </div>

                              </td>';

                        echo '</tr>';

                    }

                }

                else

                {

                    echo '<tr><td colspan="8" class="no_record">'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')).'</td></tr>';

                }

            ?>

        </table>
		
	</div>
		
    </div>

</div>