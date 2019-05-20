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
            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_translations')); ?></li>
        </ol>
    </div>
</div>

<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>translations_add_process.do" method="post">
	<div class="row">
    	<div class="col-md-6">
        	<h4 class="m-b-20">
				<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_translations')); ?>
            </h4>
            <div class="form-group">
                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_language')); ?></label>
                <select class="form-control langCode" name="langCode" id="langCode">
                	<option value="0"><?php echo $admin->wordTrans($admin->getUserLang(),'Select Language'); ?></option>
                    <?php
						$sql= 'select * from ' . LANG_MASTER . ' where lang_status = 1' ;
						$query = $mysql->query($sql);
						if($mysql->rowCount($query) > 0){
							$rows = $mysql->fetchArray($query);
							foreach($rows as $row){
					?>
                    	<option value="<?php echo $row['language_code']; ?>"><?php echo $row['language']; ?></option>
                    <?php
							}
						}
					?>
                </select>
            </div>
            <div class="form-group">
                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_word')); ?></label>
                <input name="oriWord" type="text" class="form-control oriWord" id="oriWord" value="" />
            </div>
            <div class="form-group">
                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_translation')); ?></label>
                <input name="tranWord" type="text" class="form-control tranWord" id="tranWord" value="" />
            </div>
            <div class="form-group">
                <a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>translations.html" class="btn btn-danger btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_cancel')); ?></a>
                <input type="submit" class="btn btn-success btn-sm" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_translation')); ?>" />
            </div>
        </div>
    </div>
</form>