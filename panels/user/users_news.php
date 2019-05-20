<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	//$validator->formSetAdmin('');

	$id = $request->GetInt('id');
	
	$sql ='select * from ' . NEWS_MASTER . ' where id=' . $mysql->getInt($id);
	$query = $mysql->query($sql);
	$rowCount = $mysql->rowCount($query);
	if($rowCount>0)
	{
		$rows = $mysql->fetchArray($query);
		$row = $rows[0];
?>
<h1><?php echo $admin->wordTrans($admin->getUserLang(),'View News'); ?></h1>
<div class="search"></div>    
 <div class="butSkin">
	<a href="<?php echo CONFIG_PATH_SITE_USER;?>dashboard.html" style="float:left"><img src="<?php echo CONFIG_PATH_IMAGES; ?>skin/back.png" width="10" height="10" /><?php echo $admin->wordTrans($admin->getUserLang(),'Back To Dashboard'); ?></a>
</div>
	<fieldset>
		<legend></legend>
		  <p class="field">
			<label><?php echo $admin->wordTrans($admin->getUserLang(),'News'); ?>:<?php echo $row['news']?></label>
		  </p>
		</fieldset>
 <?php
	}
 ?>