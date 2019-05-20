<?php

	defined("_VALID_ACCESS") or die("Restricted Access");

	$id=$request->getInt('id');

	$type=$request->GetStr('type');

	$supplier_id=$request->GetInt('supplier_id');

	$limit=$request->GetInt('limit');

	$user_id=$request->GetInt('user_id');

	$ip=$request->GetStr('ip');

	$search_tool_id=$request->GetStr('search_tool_id');

	

	$pString='';

	if($supplier_id != 0)

	{

		$pString .= (($pString != '') ? '&' : '' ) . 'supplier_id=' . $supplier_id;

	}

	if($limit != 0)

	{

		$pString .= (($pString != '') ? '&' : '' ) . 'limit=' . $limit;

	}

	if($ip != '')

	{

		$pString .= (($pString != '') ? '&' : '') . 'ip=' . $ip;

	}

	if($user_id != 0)

	{

		$pString .= (($pString != '') ? '&' : '') . 'user_id=' . $user_id;

	}

	if($search_tool_id != 0)

	{

		$pString .= (($pString != '') ? '&' : '') . 'search_tool_id=' . $search_tool_id;

	}

	$pString = trim($pString, '&');

	

	$sql = 'select im.*, im.id as imeiID,

					im.api_name, im.message,

					DATE_FORMAT(im.date_time, "%d-%b-%Y %k:%i") as dtDateTime,

					DATE_FORMAT(im.reply_date_time, "%d-%b-%Y %k:%i") as dtReplyDateTime,

					um.username as username,

					tm.tool_name as tool_name, 

					tm.tool_alias as tool_alias, 

					tm.custom_field_name, 

					cm.countries_name as country_name, 

					nm.network as network_name,

					mm.model as model_name, 

					bm.brand as brand_name,

					imm.mep ,

					sm.username as supplier,

					smd.username as supplierd,

					am.username as admin,

					amd.username as admind,

					DATE_FORMAT(im.supplier_paid_on, "%d-%b-%Y %k:%i") as dtSupplier

					from ' . ORDER_IMEI_MASTER . ' im

					left join ' . USER_MASTER . ' um on(im.user_id = um.id)

					left join ' . IMEI_TOOL_MASTER . ' tm on(im.tool_id = tm.id)

					left join ' . COUNTRY_MASTER . ' cm on(im.country_id = cm.id)

					left join ' . IMEI_NETWORK_MASTER . ' nm on(im.network_id = nm.id)

					left join ' . IMEI_MODEL_MASTER . ' mm on(im.model_id = mm.id)

					left join ' . IMEI_BRAND_MASTER . ' bm on(im.brand_id = bm.id)

					left join ' . SUPPLIER_MASTER . ' sm on(im.supplier_id = sm.id)

					left join ' . SUPPLIER_MASTER . ' smd on(im.supplier_id_done = smd.id)

					left join ' . ADMIN_MASTER . ' am on(im.admin_id = am.id)

					left join ' . ADMIN_MASTER . ' amd on(im.admin_id_done = amd.id)

					left join ' . IMEI_MEP_MASTER . ' imm on(im.mep_id = imm.id)

					where im.id=' . $id . '

					order by im.id DESC';

	$query=$mysql->query($sql);

	$rows=$mysql->fetchArray($query);

	$row = $rows[0];

?>



    

<div class="row m-b-20">

	<div class="col-xs-12">

    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>

            <li class="slideInDown wow animated"><a href="order_imei.html?type=<?php echo $type?>"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_imei_orders')); ?></a></li>           

             <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_imei_order_details')); ?></li>

        </ol>

    </div>

</div>



<?php

	switch($row['status'])

	{

		case 0:

			$status = $admin->wordTrans($admin->getUserLang(),$lang->get('com_pending'));

			break;

		case 1:

			$status = $admin->wordTrans($admin->getUserLang(),$lang->get('com_locked'));

			break;

		case 2:

			$status = $admin->wordTrans($admin->getUserLang(),$lang->get('com_available'));

			break;

		case 3:

			$status = $admin->wordTrans($admin->getUserLang(),$lang->get('com_unavailable'));

			break;

	}

?>



<div class="row">

	<div class="col-sm-6">

		<div class="">

			<h4 class="panel-heading m-b-20"><?php echo $mysql->prints($row['imei']); ?> : <?php echo $status;?></h4>

			<table class="table table-striped table-hover">

				<?php

				if($mysql->rowCount($query)>0)

				{
                                     $order_reply=base64_decode($row['reply']);
        
         if(strstr($order_reply,"stylesheet") || strstr($order_reply,"script src") ||strstr($order_reply,"img src"))
	 $order_reply= 'Page Not found';

					echo '<tr><td width="30%">'.$admin->wordTrans($admin->getUserLang(),$lang->get('lbl_Order Number')).'</td><td>im-' . $row['id'] . '</td></tr>';

					echo '<tr><td>'.$admin->wordTrans($admin->getUserLang(),$lang->get('lbl_Unlocking Tool')).'</td><td>' . $mysql->prints($row['tool_name']) . '</td></tr>';

					echo ($row['reply'] != '') ? '<tr><td>'.$admin->wordTrans($admin->getUserLang(),$lang->get('lbl_Unlocking Code')).'</td><td>' .$order_reply . '</td></tr>' : '';	

					echo ($row['dtDateTime'] != '') ? '<tr><td>'.$admin->wordTrans($admin->getUserLang(),$lang->get('lbl_Order Date Time')).'</td><td>' . $mysql->prints($row['dtDateTime']) . '</td></tr>' : '';	

					echo '<tr><td>'.$admin->wordTrans($admin->getUserLang(),$lang->get('lbl_Accepted By')).'</td><td>';

						echo ($row['supplier']!= '') ? ('<span class="label label-info">' . $row['supplier']. '</span>') : '';

						echo ($row['admin']!= '') ? $row['admin'] : '';

					echo '</td></tr>';

					echo ($row['dtReplyDateTime'] != 0) ? '<tr><td>'.$admin->wordTrans($admin->getUserLang(),$lang->get('lbl_Reply Date Time')).'</td><td>' . $mysql->prints($row['dtReplyDateTime']) . '</td></tr>' : '';		

					echo '<tr><td>'.$admin->wordTrans($admin->getUserLang(),$lang->get('lbl_Processed By')).'</td><td>';

						echo ($row['supplierd']!= '') ? ('<span class="label label-info">' . $row['supplierd']. '</span>') : '';

						echo ($row['admind']!= '') ? $row['admind'] : '';

					echo '</td></tr>';

					echo ($row['credits'] != '') ? '<tr><td>'.$admin->wordTrans($admin->getUserLang(),$lang->get('lbl_Credits')).'</td><td>' . $mysql->prints($row['credits']) . '</td></tr>' : '';	

					echo ($row['credits_purchase'] != '') ? '<tr><td>'.$admin->wordTrans($admin->getUserLang(),$lang->get('lbl_Credits Purchase')).'</td><td>' . $mysql->prints($row['credits_purchase']) . '</td></tr>' : '';	

					echo ($row['credits_discount'] != '') ? '<tr><td>'.$admin->wordTrans($admin->getUserLang(),$lang->get('lbl_Credits Discount')).'</td><td>' . $mysql->prints($row['credits_discount']) . '</td></tr>' : '';	

					echo ($row['mep'] != '') ? '<tr><td>'.$admin->wordTrans($admin->getUserLang(),$lang->get('lbl_MEP Name')).'</td><td>' . $mysql->prints($row['mep']) . '</td></tr>' : '';	

					echo ($row['brand_name'] != '') ? '<tr><td>'.$admin->wordTrans($admin->getUserLang(),$lang->get('lbl_Brand Name')).'</td><td>' . $mysql->prints($row['brand_name']) . '</td></tr>' : '';	

					echo ($row['model_name'] != '') ? '<tr><td>'.$admin->wordTrans($admin->getUserLang(),$lang->get('lbl_Model Name')).'</td><td>' . $mysql->prints($row['model_name']) . '</td></tr>' : '';	

					echo ($row['country_name'] != '') ? '<tr><td>'.$admin->wordTrans($admin->getUserLang(),$lang->get('lbl_Country Name')).'</td><td>' . $mysql->prints($row['country_name']) . '</td></tr>' : '';	

					echo ($row['network_name'] != '') ? '<tr><td>'.$admin->wordTrans($admin->getUserLang(),$lang->get('lbl_Network Name')).'</td><td>' . $mysql->prints($row['network_name']) . '</td></tr>' : '';	

					echo ($row['custom_field_name']!='') ? '<tr><td>Customer Value[' . $mysql->prints($row['custom_field_name']) . ']</td><td>' . $mysql->prints($row['custom_value']) . '</td></tr>' : '';

					echo ($row['pin'] != '') ? '<tr><td>Pin</td><td>' .  $mysql->prints($row['pin']) . '</td></tr>':'';

					echo ($row['prd'] != '') ? '<tr><td>PRD</td><td>' .  $mysql->prints($row['prd']) . '</td></tr>':'';

					echo ($row['itype'] != '') ? '<tr><td>Type</td><td>' .  $mysql->prints($row['itype']) . '</td></tr>':'';

					echo  '<tr><td>'.$lang->get('lbl_Order_Reject_Note').'</td><td>' .(($row['message'] != '') ? $mysql->prints(base64_decode($row['message'])) : '-')  .'</td></tr>' ;	
                                        echo  '<tr><td>'.$admin->wordTrans($admin->getUserLang(),$lang->get('lbl_Admin_Note')).'</td><td>' .(($row['admin_note'] != '') ? $mysql->prints($row['admin_note']) : '-')  .'</td></tr>' ;	

				?>

			</table>

		</div>

		

	</div>

	<div class="col-sm-6">

		<?php

				if($row['api_id'] != '0')

				{

		?>

					<div class="">

						<div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_api')); ?></div>

						<table class="table table-striped table-hover">

							<?php

								echo ($row['api_name'] != '0') ? '<tr><td width="30%">'.$admin->wordTrans($admin->getUserLang(),$lang->get('lbl_API Name')).'</td><td>' . $mysql->prints($row['api_name']) . '</td></tr>' : '';

								echo ($row['extern_id'] != '0') ? '<tr><td>'.$admin->wordTrans($admin->getUserLang(),$lang->get('lbl_External Id')).'</td><td>' . $mysql->prints($row['extern_id']) . '</td></tr>' : '';

							?>

						</table>

					</div>

		<?php

				}

		?>

            <div class="panel">

					<h4 class="panel-heading m-b-20"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_Custom_Fields_Data')); ?></h4>

            

                                    <table class="table table-striped table-hover">

						<?php

                                                         if($row['custom_1']!="")

							echo '<tr><td width="40%">'.$mysql->prints(current(explode(":", $row['custom_1']))).'</td><td>' . $mysql->prints(substr($row['custom_1'], strpos($row['custom_1'], ":") + 1)) . '</td></tr>';

                                                        if($row['custom_2']!="")

                                                        echo '<tr><td width="40%">'.$mysql->prints(current(explode(":", $row['custom_2']))).'</td><td>' . $mysql->prints(substr($row['custom_2'], strpos($row['custom_2'], ":") + 1)) . '</td></tr>';

                                                        if($row['custom_3']!="")

                                                        echo '<tr><td width="40%">'.$mysql->prints(current(explode(":", $row['custom_3']))).'</td><td>' . $mysql->prints(substr($row['custom_3'], strpos($row['custom_3'], ":") + 1)) . '</td></tr>';

							 if($row['custom_4']!="")

                                                        echo '<tr><td width="40%">'.$mysql->prints(current(explode(":", $row['custom_4']))).'</td><td>' . $mysql->prints(substr($row['custom_4'], strpos($row['custom_4'], ":") + 1)) . '</td></tr>';

							 if($row['custom_5']!="")

                                                        echo '<tr><td width="40%">'.$mysql->prints(current(explode(":", $row['custom_5']))).'</td><td>' . $mysql->prints(substr($row['custom_5'], strpos($row['custom_5'], ":") + 1)) . '</td></tr>';





						?>

					</table></div>

				<div class="panel">

					<div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_user')); ?></div>

					<table class="table table-striped table-hover">

						<?php

							echo '<tr><td width="30%">'.$admin->wordTrans($admin->getUserLang(),$lang->get('lbl_Username')).'</td><td>' . $mysql->prints($row['username']) . '</td></tr>';

							echo  '<tr><td>'.$admin->wordTrans($admin->getUserLang(),$lang->get('lbl_Email')).'</td><td>' . (($row['email'] != '')? $mysql->prints($row['email']) : '-' ). '</td></tr>';

							echo '<tr><td>'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_mobile')).'</td><td>' . (($row['mobile'] != '') ? $mysql->prints($row['mobile']) : '-' ) . '</td></tr>' ;		

							echo  '<tr><td>Ip</td><td>' . (($row['ip'] != '') ?  $mysql->prints($row['ip']) : '-' ) . '</td></tr>';	

							echo  '<tr><td>'.$admin->wordTrans($admin->getUserLang(),$lang->get('lbl_Customer Note')).'</td><td>' . (($row['remarks'] != '') ?  $mysql->prints($row['remarks']) : '-' ) . '</td></tr>';	

						?>

					</table>

				</div>

				<?php

			}

		?>

	</div>
	

</div>

<a href="order_imei.html?type=<?php echo $type?>" class="btn tab-current"><< <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_back')); ?></a>