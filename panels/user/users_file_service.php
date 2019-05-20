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
				<li><a href="<?php echo CONFIG_PATH_SITE_USER; ?>dashboard.html"><i class="fa fa-home"></i> <?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Dashboard')); ?></a></li>
                                <li><a href="<?php echo CONFIG_PATH_SITE_USER; ?>users.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Users')); ?></a></li>
				<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_File_Settings')); ?></li>
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
			<form action="<?php echo CONFIG_PATH_SITE_USER; ?>users_file_spl_credits_process.do" enctype="multipart/form-data" method="post">
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
										fsm.*,
										fsad.amount,
										fssc.amount splCr,
                                                                                fsscr.amount splres,
										pfm.amount as packageCr
									from ' . FILE_SERVICE_MASTER . ' fsm
									left join ' . CURRENCY_MASTER . ' cm on(cm.id = ' . $row['currency_id'] . ')
									left join ' . FILE_SERVICE_AMOUNT_DETAILS . ' fsad on(fsad.service_id=fsm.id and fsad.currency_id = ' . $row['currency_id'] . ')
									left join ' . FILE_SPL_CREDITS . ' fssc on(fssc.user_id = ' . $id . ' and fssc.service_id=fsm.id)
									left join ' . FILE_SPL_CREDITS_RESELLER . ' fsscr on(fsscr.user_id = ' . $id . ' and fsscr.service_id=fsm.id)
									left join ' . PACKAGE_USERS . ' pu on(pu.user_id = ' . $id . ')
									left join ' . PACKAGE_FILE_DETAILS . ' pfm on(pfm.package_id = pu.package_id and pfm.currency_id = ' . $row['currency_id'] . ' and pfm.service_id = fsm.id)
									order by fsm.service_name';

                                  
					
//echo $sql_spl_imei;exit;
                                        
         $mysql->query("SET SQL_BIG_SELECTS=1"); 
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
                                                      
							echo '<tr>';
							echo '<td>' . $i++ . '</td>';
							echo '<td>' . $mysql->prints($row_spl_imei['service_name']) . '</td>';
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
                                                                        
						<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_credits')); ?>" class="btn btn-success"/>
					
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