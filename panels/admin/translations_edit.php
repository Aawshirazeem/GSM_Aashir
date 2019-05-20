<?php
defined("_VALID_ACCESS") or die("Restricted Access");
?>
<div class="row m-b-20">
	<div class="col-lg-12">
    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">
        	<li class="slideInDown wow animated">
            	<a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a>
            </li>
            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_settings')); ?></li>
            <li class="slideInDown wow animated">
            	<a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>translations.html"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Manage_Translations')); ?></a>
            </li>
            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_translations')); ?></li>
        </ol>
    </div>
</div>
<?php
$sql = 'select * from ' . TRANSLATION_MASTER .' where id = '.$_GET['id'] ;
$query = $mysql->query($sql);
if($mysql->rowCount($query) > 0){
	$rows = $mysql->fetchArray($query);
	$lCode = $rows[0]['lang_code'];
	$oWord = $rows[0]['ori_word'];
	$tWord = $rows[0]['tran_word'];
}else{
	$lCode = 0;
	$oWord = "";
	$tWord = "";
}
?>
<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>translations_edit_process.do" method="post" autocomplete="off">
	<input type="hidden" class="hdnUpdateId" id="hdnUpdateId" name="hdnUpdateId" value="<?php echo $rows[0]['id']; ?>"/>
	<div class="row">
    	<div class="col-md-6">
        	<h4 class="m-b-20">
				<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_translations')); ?>
            </h4>
            <div class="form-group">
                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_language')); ?></label>
                <select class="form-control langCode" name="langCode" id="langCode">
                	<option value="0"><?php echo $admin->wordTrans($admin->getUserLang(),'Select Language'); ?></option>
                    <?php
						$sql= 'select * from ' . LANG_MASTER ;
						$query = $mysql->query($sql);
						if($mysql->rowCount($query) > 0){
							$rows = $mysql->fetchArray($query);
							foreach($rows as $row){
								$selected = '';
								if($lCode == $row['language_code']){
									$selected = 'selected';
								}
					?>
                    	<option value="<?php echo $row['language_code']; ?>" <?php echo $selected; ?>><?php echo $row['language']; ?></option>
                    <?php
							}
						}
					?>
                </select>
            </div>
            <div class="form-group">
                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_word')); ?></label>
                <input name="oriWord" type="text" class="form-control oriWord" id="oriWord" value="<?php echo $oWord; ?>" />
            </div>
            <div class="form-group">
                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_translation')); ?></label>
                <input name="tranWord" type="text" class="form-control tranWord" id="tranWord" value="<?php echo $tWord; ?>" />
            </div>
            <div class="form-group">
                <a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>translations.html" class="btn btn-danger btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_cancel')); ?></a>
                <input type="submit" class="btn btn-success btn-sm" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_save_translation')); ?>" />
            </div>
        </div>
    </div>
</form>