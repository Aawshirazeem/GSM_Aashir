<?php

	defined("_VALID_ACCESS") or die("Restricted Access");

	

	if(isset($_GET['status']))

		$status = $request->GetInt('status');

	else

		$status = -1;

        $inv_uid=$_GET['id'];

?>

<div class="row m-b-20">

	<div class="col-xs-12">

    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>

            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_admin_option')); ?></li>

            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Unpaid_Invoice')); ?></li>

        </ol>

    </div>

</div>

<div class="row m-b-20">

	<div class="col-xs-12">

    	<h4 class="m-b-20">

        	<?php echo $admin->wordTrans($admin->getUserLang(),'Single User Invoice'); ?>

        	<div class="btn-group btn-group-sm pull-right">

				<a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_credit_invoices_userwise.html?status=0&id=<?php echo $inv_uid;  ?>" class="btn btn-xs <?php echo ($status == 0) ? 'btn-primary' : 'btn-default'?>"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_unpaid')); ?></a>

				<a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_credit_invoices_userwise.html?status=1&id=<?php echo $inv_uid;  ?>" class="btn btn-xs <?php echo ($status == 1) ? 'btn-primary' : 'btn-default'?>"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_paid')); ?></a>

				<a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_credit_invoices_userwise.html?status=2&id=<?php echo $inv_uid;  ?>" class="btn btn-xs <?php echo ($status == 2) ? 'btn-primary' : 'btn-default'?>"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_rejected')); ?></a>

				<a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_credit_invoices_userwise.html?status=3&id=<?php echo $inv_uid;  ?>" class="btn btn-xs <?php echo ($status == 3) ? 'btn-primary' : 'btn-default'?>"><?php echo $admin->wordTrans($admin->getUserLang(),'Refunded'); ?></a>

            </div>

        </h4>

        <table class="table table-hover table-striped" id ="mytbl">



			<tr>

				<th width="15"><?php echo $admin->wordTrans($admin->getUserLang(),'Sr'); ?>#</th>

				<th width="50"><?php echo $admin->wordTrans($admin->getUserLang(),'Status'); ?></th>

                                <?php

                                if($status !=2)

                                {

                                   ?>

				<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_txn_id')); ?></th>

                                <?php

                                }

                                else 

                                {

                                    ?>

				<th><?php echo $admin->wordTrans($admin->getUserLang(),'INV'); ?>#</th>

                                <?php  

                                }

                                ?>

				<th ><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_username')); ?></th>

				<th ><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_date')); ?></th>

                                 <th width="180"><?php echo $admin->wordTrans($admin->getUserLang(),'Currency'); ?></th>

                                  <?php

                                if($status !=2)

                                {

                                   ?>

                                 <th width="180"><?php echo $admin->wordTrans($admin->getUserLang(),'Admin Note'); ?></th>

                                 <?php

                                }

				

                                if($status==0)

                                {

                                   ?>

                                 <th width="100"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_amount')); ?></th>

                                  <th width="40"><?php echo $admin->wordTrans($admin->getUserLang(),'Edit'); ?></th>

                                  <?php

                                }

                              

                                        ?>

				<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_credits')); ?></th>

                               <?php

                                if($status==0 || $status==1)

                                {

                                   ?>

				

                                <th width="180"><?php echo $admin->wordTrans($admin->getUserLang(),'Action'); ?></th>

	<?php

                                }

                              

                                        ?>

			</tr>

			<?php

				$paging = new paging();

				$offset = (isset($_GET["offset"])) ? $_GET["offset"] : 0;

				$limit = 40;

				$qLimit = " limit $offset,$limit";

				$extraURL = '';

	

                                                                        

                                if($status==0)

                                {

                                    

                                

				$sql = 'select

								im.*,um.username, cm.prefix, gm.gateway

							from ' . INVOICE_MASTER . ' im

							left join ' . USER_MASTER . ' um on (im.user_id = um.id)

							left join ' . CURRENCY_MASTER . ' cm on (im.currency_id = cm.id)

							left join ' . GATEWAY_MASTER . ' gm on (im.gateway_id = gm.id)

							where im.status=0 ' . (($status != -1) ? ' and im.paid_status=' . $status : '') . ' and im.user_id='.$inv_uid.'

						order by im.id DESC';

                                }

                                else if($status==1)

                                {

                                    $sql='select 

                                         a.id, a.`txn_id`,um.`username`, a.`date_time_paid` as date_time,c.`prefix`,sum(b.`amount`) as amount,

      b.`credits`,a.`paid_status`,b.`gateway_id`

      from ' . INVOICE_MASTER . ' as a

      left join ' . INVOICE_LOG . ' as b

      on a.id=b.`inv_id`

      left join ' . CURRENCY_MASTER . ' as c

      on a.`currency_id`=c.`id`

      left join ' . USER_MASTER . ' um on (a.user_id = um.id)



                                       where a.`paid_status` =1 and a.user_id='.$inv_uid.'

                                       group by b.`inv_id`';

                                }

                                else   if($status==2)

                                {

                                    

                                

				$sql = 'select

								im.*,um.username, cm.prefix, gm.gateway

							from ' . INVOICE_MASTER . ' im

							left join ' . USER_MASTER . ' um on (im.user_id = um.id)

							left join ' . CURRENCY_MASTER . ' cm on (im.currency_id = cm.id)

							left join ' . GATEWAY_MASTER . ' gm on (im.gateway_id = gm.id)

							where im.status=0 ' . (($status != -1) ? ' and im.paid_status=' . $status : '') . ' and im.user_id='.$inv_uid.'

						order by im.id DESC';

                                }

                                 if($status==3)

                                {

                                    

                                

				$sql = 'select

								im.*,um.username, cm.prefix, gm.gateway

							from ' . INVOICE_MASTER . ' im

							left join ' . USER_MASTER . ' um on (im.user_id = um.id)

							left join ' . CURRENCY_MASTER . ' cm on (im.currency_id = cm.id)

							left join ' . GATEWAY_MASTER . ' gm on (im.gateway_id = gm.id)

							where im.status=0 ' . (($status != -1) ? ' and im.paid_status=' . $status : '') . ' and im.user_id='.$inv_uid.'

						order by im.id DESC';

                                }

                               // echo $sql;

				$query = $mysql->query($sql);

				$strReturn = "";

                                                                        

				$pCode = $paging->recordsetNav($sql,CONFIG_PATH_SITE_ADMIN . 'users_credit_request.html',$offset,$limit,$extraURL);

                                                                        

                                

				$i = $offset;

                                                                        

                                //get gateway

                                $get_gateway='select id,gateway from ' . GATEWAY_MASTER . ' where status =1';

                                

                                $gateway = $mysql->query($get_gateway);

                                    $gateways = $mysql->fetchArray($gateway);                                    





                                //

				if($mysql->rowCount($query) > 0)

				{

					$rows = $mysql->fetchArray($query);

                                       // echo '<pre>';

                                       // var_dump($rows);exit;

					foreach($rows as $row)

					{

						$i++;

						echo '<tr>';

							echo '<td>' . $i . '</td>';

							echo '<td>';

								switch($row['paid_status'])

								{

									case '0':

										echo '<span class="label label-info">'.$admin->wordTrans($admin->getUserLang(),'Unpaid').'</span>';

										break;

									case '1':

										echo '<span class="label label-success">'. $admin->wordTrans($admin->getUserLang(),'Paid').'</span>';

										break;

									case '2':

										echo '<span class="label label-danger">'. $admin->wordTrans($admin->getUserLang(),'Rejected').'</span>';

										break;

                                                                        case '3':

										echo '<span class="label label-danger">'. $admin->wordTrans($admin->getUserLang(),'Refunded').'</span>';

										break;    

								}

							echo '</td>';

                                                         if($row['paid_status']!=2)

                                                        { 

							echo '<td>'.$row['txn_id']. '</td>';

                                                        }

                                                        else 

                                                        {

                                                         echo '<td>'.$row['id']. '</td>';   

                                                        }

							echo '<td>'.$row['username']. '</td>';

							echo '<td width="20%">' . date("d-M Y H:i", strtotime($row['date_time'])) . '</td>';

                                                        echo '<td>'. $row['prefix'] . '</td';

                                                          echo '<td></td>';

                                                        if($row['paid_status']==0)

                                                        { 

                                                          

                                                        echo '<td><input  id="gt' .$row["id"]. '" name="gateway" class="form-control">';

                                                        echo '</td>';

                                                        }

                                                         else if($row['paid_status']==1) {

                                                             ?>

                                                        <td><?php echo $row["gateway_id"] ?> </td>

                                                        

                                                       <?php

                                                         }

                                                           

                                                            

                                                        

							

                                                        if ($row['paid_status']==0) {

                                                        echo '<td width="23%"><div class="form-group"><lable id="lbl'.$row['id'].'">'.$row["amount"].'</lable>-<input name="amount" id="amnt_inv_vo'.$row["id"]. '" maxlength="6" size="6"  value="" class="myform-control" /></div></td>';

							

                                                            echo '<td  id="'.$row['id'].'"><a href="#" id="refresh_inv_vo'.$row["id"]. '" class="glyphicon glyphicon-refresh" onclick="edit_proccess('.$row["id"].')"></lable></td>';

                                                        

                                                        }

                                                        echo ($row['paid_status']==0 ||$row['paid_status']==1 )?'<td><input type="hidden" name="credit" id="credit_inv_vo'.$row["id"]. '"  value="' . $row['credits'] . '"  />'.$row["credits"].'</td>':'<td>'.$row["credits"].'</td>';

							

                                                     //   echo '<td>' . $row['credits'] . '</td>';

                                                      

                                                       if ($row['paid_status']!=2) {

							echo ($row['paid_status']==0)?'<td width="60%"><div class="btn-group"><a href="#" onclick="edit_proccess_accept('.$row["id"].')" class="btn btn-primary btn-sm">'. $admin->wordTrans($admin->getUserLang(),'Accept').'</a><a href="' . CONFIG_PATH_SITE_ADMIN . 'users_credit_unpaid_reject_process.do?id=' . $row['id'] . '" class="btn btn-default btn-sm">'. $admin->wordTrans($admin->getUserLang(),'Reject').'</a><a href="' . CONFIG_PATH_SITE_ADMIN . 'users_credit_invoices_detail.html?id=' . $row['id'] . '&type=0" class="btn btn-primary btn-sm">'. $admin->wordTrans($admin->getUserLang(),'View').'</a></div></td>':'<td><div class="btn-group"><a href="' . CONFIG_PATH_SITE_ADMIN . 'users_credit_invoices_detail.html?id=' . $row['id'] . '&type=1" class="btn btn-primary btn-sm">'. $admin->wordTrans($admin->getUserLang(),'View').'</a> </td>';

                                                       }

                                                        // echo ($row['paid_status']==0)?'<td><div class="btn-group"><a href="#" class="btn btn-primary btn-sm">View</a></div></td>':'<td></td>';

				

			

				

						echo '</tr>';

					}

				}

				else

				{

					echo '<tr><td colspan="8" class="no_record">'. $admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')).'</td></tr>';

				}

			?>

			</table>

    </div>

</div>

<link rel="stylesheet" href="<?php echo  CONFIG_PATH_PANEL_ADMIN ?>/assets/css/throbber.css" type="text/css">

<?php $url1 = CONFIG_PATH_ADMIN . 'user_credit_edit_proccess.php' ?>

<script>       

function edit(){

	$("#amnt").css("background-color", "green");

    $("#amnt").prop("readonly", false);

}



function edit_proccess(r){

	//var trid = $("#r1").

	//alert(r);

	

	var amount=$("#amnt_inv_vo"+r).val();

	var credit=$("#credit_inv_vo"+r).val();

	var gateway=$("#gt"+r).val();

	//  alert(gateway);

	// var id=

	

	amount= parseInt(amount);

	if(amount >0){

		$("#refresh_inv_vo"+r).attr('class', 'throbber-loader');

		$.ajax({

			url: '<?php echo $url1; ?>',

			data: {id:r, amnt:amount,cr:credit,gt:gateway,type:0},

			success: function (data) {

				if(data=='1'){

					window.location = "users_credit_invoices.html?status=0"

				}else{

					$("#refresh_inv_vo"+r).attr('class', 'glyphicon glyphicon-refresh');

					$("#amnt_inv_vo"+r).val('');

					$("#gt"+r).val('');

					$("#lbl"+r).text(data)

				}

			}

			//change_log_id();

		});

	}

}



function edit_proccess_accept(r){

	//var trid = $("#r1").

	//alert(r);

	var amount=$("#lbl"+r).text();

	var credit=$("#credit_inv_vo"+r).val();

	var gateway=$("#gt"+r).val();

	//  alert(gateway);

	// var id=

	

	amount= parseInt(amount);

	if(amount >0){

		$("#refresh_inv_vo"+r).attr('class', 'throbber-loader');

		$.ajax({

			url: '<?php echo $url1; ?>',

			data: {id:r, amnt:amount,cr:credit,gt:gateway,type:1},

			success: function (data) {

				window.location = "users_credit_invoices.html?status=0";

			}

		});

	}

}

</script>