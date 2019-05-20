<?php
defined("_VALID_ACCESS") or die("Restricted Access");
$id = $request->GetInt('id');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$sql ='select um.*, date_format(creation_date,"%b %Y") as userCreated,
					cm.countries_name country, cm.countries_iso_code_2
					from ' . USER_MASTER . ' um
					left join ' . COUNTRY_MASTER . ' cm on (um.country_id = cm.id)
					where um.id=' . $mysql->getInt($id);
	$query = $mysql->query($sql);
	$rowCount = $mysql->rowCount($query);
	if($rowCount == 0)
	{
		header("location:" . CONFIG_PATH_SITE_USER . "users.html?reply=");
		exit();
	}
	$rows = $mysql->fetchArray($query);
	$row = $rows[0];
        $reseller_id=$rows[0]["reseller_id"]
?>
<div class="row">
		<div class="col-lg-12">
			<ul class="breadcrumb">
				<li><a href="<?php echo CONFIG_PATH_SITE_USER; ?>dashboard.html"><i class="fa fa-home"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Dashboard')); ?></a></li>
                                <li><a href="<?php echo CONFIG_PATH_SITE_USER; ?>users.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Users')); ?></a></li>
				<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Server_Settings')); ?></li>
			</ul>
		</div>
	</div>
<section class="panel">
<!--		<header class="panel-heading tab-bg-dark-navy-blue">
			<ul class="nav nav-tabs">
				<li class="active"><a data-toggle="tab" href="#tabs-1">Imei Service</a></li>
				</ul>
		</header>-->
		<div class="panel-body">
		  <div class="tab-content">
<!--
		****************************************************
		****************** IMEI SERVICES *******************
		****************************************************
	-->
	<div id="tabs-1" class="tab-pane active">
         
							
		<!--
			****************** IMEI LIST *******************
		-->
		
		<div id="imei_spl_credits" <?php echo (($row['service_imei'] != '1') ? 'style="display:none"' : '');?>>
			<form action="<?php echo CONFIG_PATH_SITE_USER; ?>users_server_spl_credits_process.do" enctype="multipart/form-data" method="post">
			<input type="hidden" name="user_id" value="<?php echo $id;?>" >
				<table class="MT5 table table-striped table-hover panel">
                            
                                  
				<tr>
					  <th></th>
					  <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_tool')); ?></th>
					  <th class="text-right"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_credits')); ?></th>
					    <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Adjust_Price')); ?></th>
					  <th><?php echo $admin->wordTrans($admin->getUserLang(),'Total'); ?></th>
				</tr>


				<?php
            $sql_spl_imei = 'select
												slm.*,
												slgm.group_name,
												slad.amount,
												slsc.amount splCr,
                                                                                                slscr.amount splres,
												pslm.amount as packageCr
											from ' . SERVER_LOG_MASTER . ' slm
											left join ' . SERVER_LOG_GROUP_MASTER . ' slgm on(slm.group_id = slgm.id)
											left join ' . CURRENCY_MASTER . ' cm on(cm.id = ' . $row['currency_id'] . ')
											left join ' . SERVER_LOG_AMOUNT_DETAILS . ' slad on(slad.log_id=slm.id and slad.currency_id = ' . $row['currency_id'] . ')
											left join ' . SERVER_LOG_SPL_CREDITS . ' slsc on(slsc.user_id = ' . $id . ' and slsc.log_id=slm.id)
											left join ' . SERVER_LOG_SPL_CREDITS_RESELLER . ' slscr on(slscr.user_id = ' . $id . ' and slscr.log_id=slm.id)
											left join ' . PACKAGE_USERS . ' pu on(pu.user_id = ' . $id . ')
											left join ' . PACKAGE_SERVER_LOG_DETAILS . ' pslm on(pslm.package_id = pu.package_id and pslm.currency_id = ' . $row['currency_id'] . ' and pslm.log_id = slm.id)
											order by slm.server_log_name';

                                    $query_spl_imei = $mysql->query($sql_spl_imei);
                                  
					$strReturn = "";
					$i = 1;
					$groupName = "";
					if($mysql->rowCount($query_spl_imei) > 0)
					{
						$rows_spl_imei = $mysql->fetchArray($query_spl_imei);
                                               // echo '<pre>';
                                               // var_dump($rows_spl_imei);exit;
						foreach($rows_spl_imei as $row_spl_imei)
						{
                                                         
                                                      //splcra orginal price
                                                      //splcr reseller price
                                                    if ($groupName != $row_spl_imei['group_name']) {
                                                echo '<tr><th colspan="5">' . $row_spl_imei['group_name'] . '</th></tr>';
                                                $groupName = $row_spl_imei['group_name'];
                                            }
                                                      
							echo '<tr>';
							echo '<td>' . $i++ . '</td>';
							echo '<td>' . $mysql->prints($row_spl_imei['server_log_name']) . '</td>';
                                                        if($row_spl_imei["splCr"]=="")
                                                        {
                                                             if($row_spl_imei['splres']!="")
                                                           {
                                                           $ad_price=$row_spl_imei['splres'] - $row_spl_imei['amount'];
							
                                                           }
                                                           else
                                                           {
                                                            $ad_price='';   
                                                           }  
                                                          echo '<td class="text-right">' . $row_spl_imei['amount'] . ' +</td>';
                                                          
							echo '<td class="text_right">
                                                                        <input type="text" class="form-control" style="width:80px" id="spl_' . $row_spl_imei['id'] . '" onkeyup="total(' . $row_spl_imei['id'] . ')" name="spl_' . $row_spl_imei['id'] . '" value="' . $ad_price . '" />
									
                                                                        <input type="hidden" id="org_' . $row_spl_imei['id'] . '" name="org_' . $row_spl_imei['id'] . '" value="' . $row_spl_imei['amount'] . '" />
									<input type="hidden" name="ids[]" value="' . $row_spl_imei['id'] . '" />
                                                                           
								  </td>';
                                                        }
                                                        else
                                                        {
                                                             if($row_spl_imei['splres']!="")
                                                           {
                                                           $ad_price=$row_spl_imei['splres'] - $row_spl_imei['splCr'];
							
                                                           }
                                                           else
                                                           {
                                                            $ad_price='';   
                                                           }  
                                                          echo '<td class="text-right">' . $row_spl_imei['splCr'] . ' +</td>';
                                                          echo '<td class="text_right">
                                                                        <input type="text" class="form-control" style="width:80px" id="spl_' . $row_spl_imei['id'] . '" onkeyup="total(' . $row_spl_imei['id'] . ')" name="spl_' . $row_spl_imei['id'] . '" value="' . $ad_price . '" />
									
                                                                        <input type="hidden" id="org_' . $row_spl_imei['id'] . '" name="org_' . $row_spl_imei['id'] . '" value="' . $row_spl_imei['splCra'] . '" />
									<input type="hidden" name="ids[]" value="' . $row_spl_imei['id'] . '" />
                                                                           
								  </td>';
                                                        }    
                                                          
                                                        
                                                      
                                                        echo '<td class="text_right"><input type="text" class="form-control" style="width:80px" id="total_' . $row_spl_imei['id'] . '" name="total_' . $row_spl_imei['id'] . '" value="' . $row_spl_imei['splres'] . '" />
									</td>';
							echo '</tr>';
						}
					}
					else
					{
						echo '<tr><td colspan="6" class="no_record">'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')).'</td></tr>';
					}
				?>
				</table>
					<div class="btn-group">
                                            <input type="hidden" name="reseller_id" value="<?php echo $reseller_id; ?>" />
                                                                        
						<input type="submit" value="<?php $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_credits')); ?>" class="btn btn-success"/>
					
                                        </div>
			</form>	
		</div>
	
	</div>
                  </div></div></section>
<script type="text/javascript" src="http://code.jquery.com/jquery-2.1.3.js" ></script>
<script>
function total(r)
{
    
    var org_price=$("#org_"+r).val();
    org_price=parseFloat(org_price);
 //   alert(org_price);
    var ad_price=$("#spl_"+r).val();
    ad_price=parseFloat(ad_price);
  //  alert(ad_price);
    var total=org_price+ad_price;
  //  alert(total);
  if(total != NaN)
  {
    $("#total_"+r).val(total.toFixed(2));
    }
    else
    {
     $("#total_"+r).val(org_price);   
    }
}

</script>