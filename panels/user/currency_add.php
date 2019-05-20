<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$validator->formSetAdmin('config_reseller_add_148148548');
?>
<h1><?php echo $admin->wordTrans($admin->getUserLang(),'Add Currency'); ?> </h1>
  <form action="<?php echo CONFIG_PATH_SITE_USER; ?>currency_add_process.do" method="post" name="frm_customers_edit" id="frm_customers_edit" class="formSkin">
    <fieldset>
    <legend></legend>
      <p class="field">
        <label><?php echo $admin->wordTrans($admin->getUserLang(),'Currency'); ?> </label>
        <input name="currency" type="text" class="textbox_fix" id="currency" value="" />
      </p>
	  <p class="field">
        <label><?php echo $admin->wordTrans($admin->getUserLang(),'Prefix'); ?></label>
		 <input name="prefix" type="text" class="textbox_fix" id="prefix" value="" />
      </p>
      <p class="field">
        <label><?php echo $admin->wordTrans($admin->getUserLang(),'Prefix Code'); ?> </label>
        <input name="prefix_code" type="text" class="textbox_fix" id="prefix_code" value="" />
      </p>
      <p class="field">
        <label><?php echo $admin->wordTrans($admin->getUserLang(),'Suffix'); ?></label>
        <input name="suffix" type="text" class="textbox_fix" id="suffix" value="" />
      </p>
      <p class="field">
        <label><?php echo $admin->wordTrans($admin->getUserLang(),'Rate'); ?></label>
        <input name="rate" type="text" class="textbox_fix" id="rate" value="" />
      </p>
    
      <p class="butSkin">
			<a href="<?php echo CONFIG_PATH_SITE_USER;?>currency.html" style="float:left"><img src="<?php echo CONFIG_PATH_IMAGES; ?>skin/back.png" width="10" height="10" /><?php echo $admin->wordTrans($admin->getUserLang(),'BAck To Currency'); ?></a>
			<input type="submit" value="Add Currency" />
		</p>
    </fieldset>
  </form>
