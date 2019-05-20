<?php
	// Set flag that this is a parent file
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$admin->checkLogin();
	$admin->reject();

	
	$tool = $request->getInt('tool');
	
	$sql = 'select * from ' . IMEI_TOOL_MASTER . ' where id=' . $mysql->getInt($tool);
	$query = $mysql->query($sql);
	if($mysql->rowCount($query) == 0)
	{
?>
	          <div class="form-group hidden">
	            <label><?php echo $admin->wordTrans($admin->getUserLang(),'IMEI'); ?>*</label>
	            <input type="text" name="imei" id="imei" class="form-control" value="">
	            <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),'The serial number of the mobile phone to unlock.'); ?></p>
	          </div>
	          
	          <div class="form-group hidden">
	            <label><?php echo $admin->wordTrans($admin->getUserLang(),'IMEI'); ?>(s)</label>
	            <textarea name="imeis" id="imeis" class="form-control" rows="5" ></textarea>
	            <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),'The serial number of the mobile phone to unlock. You can enter several serial numbers (one per line) if you have several similar phones (for the same tool and the same information)'); ?> </p>
	          </div>


	          <div class="form-group hidden">
	            <label><?php echo $admin->wordTrans($admin->getUserLang(),'Brand'); ?></label>
	            <select name="brand" class="textbox_big" id="brand">
	            	<option value=""><?php echo $admin->wordTrans($admin->getUserLang(),'Select Brand'); ?></option>
	            </select>
            	<img src="<?php echo CONFIG_PATH_IMAGES; ?>wait.gif" alt="Please wait..." height="16" width="16" class="hidden" id="loadIndBrand" />
	          </div>
	          <div class="form-group hidden">
	            <label><?php echo $admin->wordTrans($admin->getUserLang(),'Model'); ?></label>
	            <select name="model" class="textbox_big" id="model">
	            	<option value=""><?php echo $admin->wordTrans($admin->getUserLang(),'Select Model'); ?></option>
	            </select>
	          </div>
	          <div class="form-group hidden">
	            <label><?php echo $admin->wordTrans($admin->getUserLang(),'Country'); ?></label>
	            <select name="country" class="textbox_big" id="country">
	            	<option value=""><?php echo $admin->wordTrans($admin->getUserLang(),'Select Country'); ?></option>
	            </select>
            	<img src="<?php echo CONFIG_PATH_IMAGES; ?>wait.gif" alt="Please wait..." height="16" width="16" class="hidden" id="loadIndCountry" />
	          </div>
	          <div class="form-group hidden">
	            <label><?php echo $admin->wordTrans($admin->getUserLang(),'Network'); ?></label>
	            <select name="network" class="textbox_big" id="network">
	            	<option value=""><?php echo $admin->wordTrans($admin->getUserLang(),'Select Network'); ?></option>
	            </select>
	          </div>
<?php
	}
	else
	{
		$rows = $mysql->fetchArray($query);
		$row = $rows[0];
		
		$sql_cr = '
						select tm.credits, uscm.credits as splCr
						from ' . IMEI_TOOL_MASTER . ' tm
						left join ' . IMEI_SPL_CREDITS . ' uscm on (tm.id = uscm.tool and uscm.user_id = ' . $mysql->getInt($admin->getUserId()) . ')
						where tm.id=' . $mysql->getInt($tool);
		$query_cr = $mysql->query($sql_cr);
		$rows_cr = $mysql->fetchArray($query_cr);
		
		$row_cr = $rows_cr[0];
		$cr = $row_cr['credits'];
		if($row_cr['splCr'] != "")
		{
			$cr = $row_cr['splCr'];
		}

	?>
			<div class="clear"></div>
				<div  style="width:80%;margin:0px auto;">
					<div class="ui-widget">
						<br />
						<div class="ui-state-default ui-corner-all text_center" style="padding:5px 5px 5px 5px;"> 
							<font style="font-weight:normal">Credits:</font> <?php echo $cr;?> &nbsp;|&nbsp; <font style="font-weight:normal"><?php echo $admin->wordTrans($admin->getUserLang(),'Time'); ?>:</font> <?php echo $row['delivery_time'];?>
						</div>
					</div>
				</div>
	          <div class="form-group<?php echo (($row['imei_type'] == '0') ? ' hidden' : ''); ?>">
	            <label><?php echo $admin->wordTrans($admin->getUserLang(),'IMEI'); ?>*</label>
	            <input type="text" name="imei" id="imei" class="form-control" value="" maxlength="15">
	            <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),'The serial number of the mobile phone to unlock.'); ?></p>
	          </div>
	          <div class="form-group<?php echo (($row['imei_type'] == '1') ? ' hidden' : ''); ?>">
	            <label><?php echo $admin->wordTrans($admin->getUserLang(),'IMEI'); ?>(s)</label>
	            <textarea name="imeis" id="imeis" class="form-control" rows="5" ></textarea>
	            <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),'The serial number of the mobile phone to unlock. You can enter several serial numbers (one per line) if you have several similar phones (for the same tool and the same information).'); ?></p>
	          </div>
				<div  style="width:80%;margin:0px auto;" class="<?php echo (($row['info'] == '') ? ' hidden' : ''); ?>">
					<div class="ui-widget">
						<br />
						<div class="ui-state-highlight ui-corner-all" style="padding:5px 5px 5px 5px;"> 
							$$$$<?php echo nl2br($row['info']);?>
						</div>
					</div>
				</div>


				
				
	          <div class="form-group<?php echo (($row['brand_id'] >= 0) ? ' hidden' : ''); ?>">
	            <label><?php echo $admin->wordTrans($admin->getUserLang(),'Brand'); ?></label>
	            <select name="brand" class="textbox_big" id="brand">
	            	<?php
	            		if($row['brand_id'] == '0')
	            		{
	            			echo '<option value="0">Select Brand</option>';
	            		}
	            		else
	            		{
	            			$where_brand = ($row['brand_id'] > 0) ? ' where id=' . $mysql->getInt($row['brand_id']) : '';
		            		$sql_brand = 'select * from ' . IMEI_BRAND_MASTER . $where_brand . ' order by brand';
		            		$query_brand = $mysql->query($sql_brand);
		            		echo ($row['brand_id'] > 0) ? '' : '<option value="">Select Brand</option>';
		            		if($mysql->rowCount($query_brand) > 0)
		            		{	
		            			$rows_brand = $mysql->fetchArray($query_brand);
		            			foreach($rows_brand as $row_brand)
		            			{
		            				echo '<option value="' . $row_brand['id'] . '">' . $row_brand['brand'] . '</option>';
		            			}
		            		}
		            	}
	            	?>
	            </select>
            	<img src="<?php echo CONFIG_PATH_IMAGES; ?>wait.gif" alt="Please wait..." height="16" width="16" class="hidden" id="loadIndBrand" />
	            <?php //echo "brand"?>
	          </div>
	          <div class="form-group<?php echo (($row['brand_id'] == '0') ? ' hidden' : ''); ?>">
	            <label><?php echo $admin->wordTrans($admin->getUserLang(),'Model'); ?></label>
	            <select name="model" class="textbox_big" id="model">
	            	<?php
	            		echo '<option value="">Select Model</option>';
	            		
	            		if($row['brand_id'] > 0)
	            		{
		            		$sql_model = 'select * from ' . IMEI_MODEL_MASTER . ' where brand=' . $mysql->getInt($row['brand_id']) . ' order by model';
		            		$query_model = $mysql->query($sql_model);
		            		if($mysql->rowCount($query_model) > 0)
		            		{
		            			$rows_model = $mysql->fetchArray($query_model);
		            			foreach($rows_model as $row_model)
		            			{
		            				echo '<option value="' . $row_model['id'] . '">' . $row_model['model'] . '</option>';
		            			}
		            		}
		            	}
	            	?>
	            </select>
	            <?php //echo "model"?>
	          </div>
	          <div class="form-group<?php echo (($row['country_id'] >= 0) ? ' hidden' : ''); ?>">
	            <label><?php echo $admin->wordTrans($admin->getUserLang(),'Country'); ?></label>
	            <select name="country" class="textbox_big" id="country">
	            	<?php
	            		if($row['country_id'] == '0')
	            		{
	            			echo '<option value="0">Select Country</option>';
	            		}
	            		else
	            		{
	            			$where_country = ($row['country_id'] > 0) ? ' where id=' . $mysql->getInt($row['country_id']) : '';
		            		$sql_country = 'select * from ' . IMEI_COUNTRY_MASTER . $where_country . ' order by country';
		            		$query_country = $mysql->query($sql_country);
		            		echo ($row['country_id'] > 0) ? '' : '<option value="">Select Brand</option>';
		            		if($mysql->rowCount($query_country) > 0)
		            		{	
		            			$rows_country = $mysql->fetchArray($query_country);
		            			foreach($rows_country as $row_country)
		            			{
		            				echo '<option value="' . $row_country['id'] . '">' . $row_country['country'] . '</option>';
		            			}
		            		}
		            	}
	            	?>
	            </select>
            	<img src="<?php echo CONFIG_PATH_IMAGES; ?>wait.gif" alt="Please wait..." height="16" width="16" class="hidden" id="loadIndCountry" />
	            <?php //echo "country"?>
	          </div>
	          <div class="form-group<?php echo (($row['country_id'] == '0') ? ' hidden' : ''); ?>">
	            <label><?php echo $admin->wordTrans($admin->getUserLang(),'Network'); ?></label>
	            <select name="network" class="textbox_big" id="network">
	            	<?php
	            		echo '<option value="">Select Network</option>';
	            		$sql_network = 'select * from ' . IMEI_NETWORK_MASTER . ' where country=' . $mysql->getInt($row['country_id']) . ' order by network';
	            		$query_network = $mysql->query($sql_network);
	            		if($row['country_id'] > 0)
	            		{
		            		if($mysql->rowCount($query_network) > 0)
		            		{	
		            			$rows_network = $mysql->fetchArray($query_network);
		            			foreach($rows_network as $row_network)
		            			{
		            				echo '<option value="' . $row_network['id'] . '">' . $row_network['network'] . '</option>';
		            			}
		            		}
		            	}
	            	?>
	            </select>
	            <?php //echo "Network"?>
	          </div>
	          
	          <div class="form-group<?php echo (($row['mep_group_id'] == '0') ? ' hidden' : ''); ?>">
	            <label><?php echo $admin->wordTrans($admin->getUserLang(),'MEP '); ?>*</label>
	            <select name="mep" class="textbox_big" id="mep">
	            	<?php
	            		echo '<option value="">Select MEP</option>';
	            		$sql_mep = 'select * from ' . IMEI_MEP_MASTER . ' where mep_group_id=' . $mysql->getInt($row['mep_group_id']) . ' order by mep';
	            		$query_mep = $mysql->query($sql_mep);
            			
	            		if($mysql->rowCount($query_mep) > 0)
	            		{	
	            			$rows_mep = $mysql->fetchArray($query_mep);
	            			foreach($rows_mep as $row_mep)
	            			{
	            				echo '<option value="' . $row_mep['id'] . '">' . $row_mep['mep'] . '</option>';
	            			}
		            	}
	            	?>

	            </select>
	          </div>
	          <div class="form-group<?php echo (($row['field_pin'] == '0') ? ' hidden' : ''); ?>">
	            <label><?php echo $admin->wordTrans($admin->getUserLang(),'PIN'); ?> *</label>
	            <input type="text" name="pin" class="form-control" id="pin" />
	          </div>
	          <div class="form-group<?php echo (($row['field_kbh'] == '0') ? ' hidden' : ''); ?>">
	            <label><?php echo $admin->wordTrans($admin->getUserLang(),'KBH/KRH '); ?>*</label>
	            <input type="text" name="pin" class="form-control" id="pin" maxlength="13" />
	          </div>
	          <div class="form-group<?php echo (($row['field_prd'] == '0') ? ' hidden' : ''); ?>">
	            <label><?php echo $admin->wordTrans($admin->getUserLang(),'PRD'); ?> *</label>
	            <input type="text" name="prd" class="form-control" id="prd" maxlength="13" value="PRD-XXXXX-XXX" />
	          </div>
	          <div class="form-group<?php echo (($row['field_type'] == '0') ? ' hidden' : ''); ?>">
	            <label><?php echo $admin->wordTrans($admin->getUserLang(),'Type'); ?> *</label>
	            <input type="text" name="vtype" class="form-control" id="vtype" value="RM-" />
	          </div>

	          
	          
	          
<?php
	}
?>
