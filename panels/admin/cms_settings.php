<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$admin = new admin();
	$mysql = new mysql();	
?>
<div class="row m-b-20">
	<div class="col-xs-12">
    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">
            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
            <li class="slideInDown wow animated"><?php echo $admin->wordTrans($admin->getUserLang(),'CMS'); ?></li>
            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Manage_cms_settings')); ?></li>
        </ol>
    </div>
</div>

<div class="m-t-10">
	<h4 class="m-b-20">
		<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Manage_cms_settings')); ?>
    </h4>
    <?PHP 
	/* Getting cms settings */
	$cmsSettings = array();
	$sql= 'select * from ' . CMS_SETTINGS ;
	$query = $mysql->query($sql);
	if($mysql->rowCount($query) > 0){
		$rows = $mysql->fetchArray($query);
		foreach($rows as $row){
			$cmsSettings[$row['config']] = $row['value'];	
		}
	}
	?>	
	
	<div class="table table-responsive">
	
    <form action="" id="setting-frm-ajax">

    <table class="table table-hover table-striped">
    	<tr>
        	<th width="16"></th>
            <th width=""><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_site_logo')); ?></th>
            <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Action')); ?></th>
        </tr>
        <?php
		$sql= 'select * from ' . CMS_MENU_MASTER ;
		$query = $mysql->query($sql);
		$strReturn = "";
		$i = 1;
		if($mysql->rowCount($query) > 0){
			$rows = $mysql->fetchArray($query);
			foreach($rows as $row){
		?>
        	<tr>
            	<td></td>
                <td>
                <?PHP if($row['logo'] != ''){ ?>
                <img src="<?php echo CONFIG_PATH_THEME_NEW.'site_logo/'.$row['logo']; ?>" class="img-responsive" style="max-width:180px; max-height:180px; height:auto; width:auto"/>
                <?PHP }else{ 
						echo 'No Logo Added';
					  }
				?>
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-info btnEditLogo" data-id="<?php echo $row['id']; ?>"><i class="fa fa-pencil"></i></button>
                    <button type="button" class="btn btn-sm btn-danger btnRmvLogo" ><i class="fa fa-trash"></i></button>
                </td>
            </tr>
        <?php
			}
		}else{
		?>
        	<tr>
            	<td colspan="8" class="no_record"><?php echo $admin->wordTrans($admin->getUserLang(),'No record found'); ?>!</td>
            </tr>
        <?php
		}
		?>
        
        <tr>
        	<th width="16"></th>
            <th width=""><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_slider_size')); ?></th>
            <th></th>
        </tr>
        <?PHP 
		$sql= 'select * from ' . SLIDER_MASTER.' limit 0,1' ;
		$query = $mysql->query($sql);
		$strReturn = "";
		$i = 1;
		$sWidth = $sHeight = 0;
		if($mysql->rowCount($query) > 0){
			$rows = $mysql->fetchArray($query);
			foreach($rows as $row){
				$sWidth = $row['s_width'];
				$sHeight = $row['s_height'];
			}
		}
		
		?>
        <?php /*?><tr>
            	<td></td>
                <?php
				if($sWidth == 0 && $sHeight == 0){
				?>
                <td>Width: Full Width</td>
                <?php
				}else{
				?>
                <td>Width: <?PHP echo $sWidth; ?>px | Height: <?PHP echo $sHeight; ?>px</td>
                <?php
				}
				?>
                <td>
                    <button type="button" class="btn btn-sm btn-info btnChangeSliderHW"><i class="fa fa-pencil"></i></button>
                </td>
            </tr><?php */?>
            
        <tr>
        	<th width="16"></th>
            <th width=""><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Top_Header_collapsed')); ?></th>
            <th></th>
        </tr>  
        <tr>
            	<td></td>
                <td>
                <div class="form-group" style="max-width:300px;">
                    <label for="st_1"><?php echo $admin->wordTrans($admin->getUserLang(),'Header Style'); ?></label>
                    <select id="header_collapsed" name="header_collapsed" class="form-control cms-setting">
                    <option value="1" <?PHP echo $cmsSettings['header_collapsed'] == 1 ? 'selected' : ''; ?>><?php echo $admin->wordTrans($admin->getUserLang(),'Fixed'); ?></option>
                    <option value="0" <?PHP echo $cmsSettings['header_collapsed'] == 0 ? 'selected' : ''; ?>><?php echo $admin->wordTrans($admin->getUserLang(),'Collapsed'); ?></option>
                    </select>
                  </div></td>
                <td>
					<div class="form-group" style="max-width:300px; display:none;">
						<label for="st_1"><?php echo $admin->wordTrans($admin->getUserLang(),'Header Background Color'); ?></label>
						<input name="header_background" value="<?PHP echo $cmsSettings['header_background'] ? $cmsSettings['header_background'] : ''; ?>" class="clr_picker form-control" />
					</div>
                </td>
            </tr>
            
            <tr>
            	<td></td>
				<td>
					<div class="form-group" style="max-width:300px;">
						<label for="st_1"><?php echo $admin->wordTrans($admin->getUserLang(),'Menu Font Color'); ?></label>
						<input name="menu_color" value="<?PHP echo $cmsSettings['menu_color'] ? $cmsSettings['menu_color'] : ''; ?>" class="clr_picker form-control" />
					</div>
                </td>
                <td>
                <div class="form-group" style="max-width:300px;">
                    <label for="st_1"><?php echo $admin->wordTrans($admin->getUserLang(),'Website Color'); ?></label>
                    <input name="website_color" value="<?PHP echo $cmsSettings['website_color'] ? $cmsSettings['website_color'] : ''; ?>" class="clr_picker form-control" />
                  </div></td>
                
            </tr>  
            
            <tr>
            	<td></td>
                <td>
                <div class="form-group" style="max-width:300px;">
                    <label for="st_1"><?php echo $admin->wordTrans($admin->getUserLang(),'Active footer'); ?></label>
                    <select id="active_footer" name="active_footer" class="form-control cms-setting">
                    <option value="17" <?PHP echo $cmsSettings['active_footer'] == 17 ? 'selected' : ''; ?>><?php echo $admin->wordTrans($admin->getUserLang(),'Style 1'); ?></option>
                    <option value="18" <?PHP echo $cmsSettings['active_footer'] == 18 ? 'selected' : ''; ?>><?php echo $admin->wordTrans($admin->getUserLang(),'Style 2'); ?></option>
                    <option value="22" <?PHP echo $cmsSettings['active_footer'] == 22 ? 'selected' : ''; ?>><?php echo $admin->wordTrans($admin->getUserLang(),'Style 3'); ?></option>
                    </select>
                  </div></td>
                <td>
               
                </td>
            </tr> 
            
            
            <tr>
            <td></td>
            <td>
            <button type="submit" class="btn btn-sm btn-primary save-settings"><?php echo $admin->wordTrans($admin->getUserLang(),'Save Changes'); ?></button>
            </td><td></td>
            </tr> 
            
	</table>

    </form>
	
	</div>
    <?php /*?><table class="table table-hover table-striped">
    	<tr>
            <th width=""><?php $lang->prints('lbl_social'); ?></th>
            <th width=""><?php $lang->prints('lbl_social_url'); ?></th>
            <th><?php $lang->prints('lbl_Action'); ?></th>
        </tr>
            
		<?php
        $sql = 'select * from ' . CMS_SOCIAL ;
        $queryForSocial = $mysql->query($sql);
        if($mysql->rowCount($queryForSocial) > 0){
            $rowsForSocial = $mysql->fetchArray($queryForSocial);
            foreach($rowsForSocial as $row){
        ?>
        <tr>
            <td>
                <?php echo $row['social_name']; ?>
            </td>
            <td>
                <?php echo $row['url']; ?>
            </td>
            <td>
                <?php if($row['is_active'] == 1){?>
                <button type="button" class="btn btn-sm btn-info setSocial" data-id="<?php echo $row['id']; ?>"><i class="fa fa-check"></i></button>
                <?php }else{ ?>
                <button type="button" class="btn btn-sm btn-grey setSocial" data-id="<?php echo $row['id']; ?>"><i class="fa fa-check"></i></button>
                <?php } ?>
                <button type="button" class="btn btn-sm btn-primary editSocial" data-id="<?php echo $row['id']; ?>"><i class="fa fa-pencil"></i></button>
            </td>
        </tr>
        <?php
            }
        }
        ?>
    </table><?php */?>
</div>


<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
    <!-- Modal content-->
    	<div class="modal-content">
        	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $admin->wordTrans($admin->getUserLang(),'Logo'); ?></h4>
           	</div>
            <form name="frmUpdateLogo" id="frmUpdateLogo" class="frmUpdateLogo" action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>site_change_process.do" method="post" enctype="multipart/form-data">
            	<input type="hidden" name="hdnUpdateId" id="hdnUpdateId" class="hdnUpdateId" value="0"/>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_image')); ?> </label>
                                <div class="row cleafix">
                                    <div class="col-md-6">
                                        <input name="logoImage" type="file" class="form-control" id="logoImage" value="" required />
                                    </div>
                                    <div class="col-md-6">
                                        <div class="imgResp"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btnChangeLogo" name="btnChangeLogo"><?php echo $admin->wordTrans($admin->getUserLang(),'Change'); ?></button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $admin->wordTrans($admin->getUserLang(),'Close'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal EDIT SOCIAL -->
<div id="myModalSocialEdit" class="modal fade" role="dialog">
	<div class="modal-dialog">
    <!-- Modal content-->
    	<div class="modal-content">
        	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $admin->wordTrans($admin->getUserLang(),'Update Social'); ?></h4>
           	</div>
            <form name="frmUpdateSocial" id="frmUpdateSocial" class="frmUpdateSocial" action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>cms_settings_process.do" method="post">
            	<input type="hidden" name="hdnSocialId" id="hdnSocialId" class="hdnSocialId" value="0"/>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_social_url')); ?> </label>
                                <input name="socialUrl" type="text" class="form-control socialUrl" id="socialUrl" value="" autocomplete="off" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btnChangeSocial" name="btnChangeSocial"><?php echo $admin->wordTrans($admin->getUserLang(),'Change'); ?></button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $admin->wordTrans($admin->getUserLang(),'Close'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="myModalSliderDimensions" class="modal fade" role="dialog">
	<div class="modal-dialog">
    <!-- Modal content-->
    	<div class="modal-content">
        	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $admin->wordTrans($admin->getUserLang(),'Slider Dimensions'); ?></h4>
           	</div>
            <form name="frmAddSlider" id="frmAddSlider" class="frmAddSlider" action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>slider_edit_process.do" method="post">
            	<input type="hidden" name="sHW" id="sHW" value="1"/>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                            	<div class="row cleafix">
                                    <div class="col-md-6">
                                        <label class="c-input c-checkbox">
                                        	<input type="radio" name="setFullWidth" class="form-control setFullWidth" id="setFullWidth" value="1">
                                            <span class="c-indicator c-indicator-info"></span>
                                            <span class="c-input-text"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_set_full_width')); ?> </span>
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="c-input c-checkbox">
                                        	<input type="radio" name="setFullWidth" class="form-control setFullWidth" id="setCustomWidth" value="0">
                                            <span class="c-indicator c-indicator-info"></span>
                                            <span class="c-input-text"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_set_custom_width')); ?> </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group inputHW hide">
                            	<div class="row cleafix">
                                    <div class="col-md-6">
                                    	<label> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_slider_width')); ?> </label>
                                        <input name="sliderWidth" type="text" class="form-control" id="sliderWidth" value="" autocomplete="off" />
                                    </div>
                                    <div class="col-md-6">
                                    	<label> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_slider_height')); ?> </label>
                                        <input name="sliderHeight" type="text" class="form-control" id="sliderHeight" value="" autocomplete="off" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btnSaveSliderHW" name="btnSaveSliderHW"><?php echo $admin->wordTrans($admin->getUserLang(),'Save'); ?></button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $admin->wordTrans($admin->getUserLang(),'Close'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<link rel="stylesheet" href="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets_1/colorpicker/css/colorpicker.css">
<script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets_1/colorpicker/js/colorpicker.js" ></script>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.js"></script>
<script>
$(document).ready(function(e) {
	var _activeColor;
	
	$('.clr_picker').focus(function(e){
		_activeColor = $(this);
	});
	
	$('.clr_picker').ColorPicker({
		/*onSubmit: function(hsb, hex, rgb, el) {
			$(el).val(hex);
			$(el).ColorPickerHide();
		},*/
		onChange: function (hsb, hex, rgb, el) {
			_activeColor.val(hex);
		},
		onBeforeShow: function () {
			$(this).ColorPickerSetColor(this.value);
		}
		
	});
	
	$.notify.defaults( {style:'metro'} );
	$('.frmUpdateLogo').validate();	
	
	$(document).on('click','.editSocial',function(e){
		e.preventDefault();
		var _url = '<?php echo CONFIG_PATH_SITE_ADMIN; ?>cms_settings_process.do';
		var _id = $(this).data('id');
		var _isSocial = 1;
		$.ajax({
			url: _url,
			data: {	id: _id,isSocial:_isSocial},
			type: "POST",
			dataType : "json",
		}).done(function( resp ) {
			if(resp.status == 1){
				$('.socialUrl').val(resp.url);
				$('.hdnSocialId').val(resp.id);
				$('#myModalSocialEdit').modal();
			}else{
				alert('something went wrong.');
			}
		}).fail(function( xhr, status, errorThrown ) {
		}).always(function( xhr, status ) {
		});
	});
	
	$(document).on('click','.btnChangeSocial',function(e){
		e.preventDefault();
		if($('.socialUrl') == ""){
			$('.socialUrl').css('border-color','#d13400');
			return false;
		}
		var _url = $('#frmUpdateSocial').attr('action');
		var _formdata = $('#frmUpdateSocial').serialize();
		var _isSocialUpdate = 1;
		var _this = $(this);
		_this.attr('disabled','disabled');
		_this.text('Saving...');
		$.ajax({
			url: _url,
			data: {	formData: _formdata,isSocialUpdate:_isSocialUpdate},
			type: "POST",
			dataType : "json",
		}).done(function( resp ) {
			if(resp.status == 1){
				swal("Success !", "Social URL Saved Successfully.", "success")
				_this.removeAttr('disabled');
				setTimeout(function(e){
					$('#myModalSocialEdit').modal('hide');
					location.reload();
				},2000);
				
			}else{
				alert('something went wrong.');
			}
		}).fail(function( xhr, status, errorThrown ) {
		}).always(function( xhr, status ) {
		});
	});
	
	$(document).on('click','.setSocial',function(e){
		e.preventDefault();
		var _url = '<?php echo CONFIG_PATH_SITE_ADMIN; ?>cms_settings_process.do';
		var _id = $(this).data('id');
		var setSocial = 1;
		
		$.ajax({
			url: _url,
			data: {	id: _id,setSocial:setSocial},
			type: "POST",
			dataType : "json",
		}).done(function( resp ) {
			if(resp.status == 1){
				location.reload();
			}else{
				alert('something went wrong.');
			}
		}).fail(function( xhr, status, errorThrown ) {
		}).always(function( xhr, status ) {
		});
		
	});
	
	
	$(document).on('click','.btnEditLogo',function(e){
		e.preventDefault();
		var _url = '<?php echo CONFIG_PATH_SITE_ADMIN; ?>site_change_process.do';
		var _id = $(this).data('id');
		var isEdit = 1;
		
		$.ajax({
			url: _url,
			data: {	id: _id,isEdit:isEdit},
			type: "POST",
			dataType : "json",
		}).done(function( resp ) {
			if(resp.status == 1){
				$('.hdnUpdateId').val(resp.id);
				$('.imgResp').html('<img src="<?php echo CONFIG_PATH_THEME_NEW.'site_logo/'; ?>'+resp.image+'" class="img-responsive" style="width:100%;"/>');
				$('#myModal').modal();
			}else{
				alert('something went wrong.');
			}
		}).fail(function( xhr, status, errorThrown ) {
		}).always(function( xhr, status ) {
		});
	});
	
	$(document).on('click','.btnChangeSliderHW',function(e){
		e.preventDefault();
		var _url = '<?php echo CONFIG_PATH_SITE_ADMIN; ?>slider_add_process.do';
		$.ajax({
			url: _url,
			data: {	getHW: 1},
			type: "POST",
			dataType : "json",
		}).done(function( resp ) {
			if(resp.height != 0 && resp.width != 0){
				$('#sliderWidth').val(resp.width);
				$('#sliderHeight').val(resp.height);
				$('#setCustomWidth').attr('checked',true);
				$('.inputHW').removeClass('hide');
			}else{
				$('#setFullWidth').attr('checked',true);
				$('.inputHW').addClass('hide');
			}
		}).fail(function( xhr, status, errorThrown ) {
		}).always(function( xhr, status ) {
		});
		$('#myModalSliderDimensions').modal();
	});
	
	$(document).on('submit','#setting-frm-ajax',function(e){
		e.preventDefault();
		var _this = $(this).find('.save-settings');
		var _frmdata = $(this).serialize();
		var _oldTxt = _this.text();
		_this.attr('disabled','disabled');
		_this.text('Saving...');
		var _url = '<?php echo CONFIG_PATH_SITE_ADMIN; ?>cms_settings_process.do';
		$.ajax({
			url: _url,
			data: _frmdata,
			type: "POST",
			dataType : "json",
		}).done(function( resp ) {
			_this.text(_oldTxt);
			swal({
		  title: 'Success !',
		  text: 'Settings Saved Successfully.',
		  type: 'success',
		  timer: 800
		})
			_this.removeAttr('disabled');
		}).fail(function( xhr, status, errorThrown ) {
		}).always(function( xhr, status ) {
		});
		
		
		
		//$.notify("Access granted", "success");
	});
	
	$(document).on('change','.setFullWidth',function(e){
		var _checkVal = $(this).val();
		if(_checkVal == 0){
			$('.inputHW').removeClass('hide');
		}else{
			$('.inputHW').addClass('hide');
		}
	});
	
	$(document).on('click','.btnRmvLogo',function(e){ e.preventDefault();
		var _url = '<?php echo CONFIG_PATH_SITE_ADMIN; ?>cms_settings_process.do';
		if(confirm('Are you sure to remove logo ? If you remove logo it will show website title in website.')){
			$.ajax({
				url: _url,
				data: {	logoremove: 1},
				type: "POST",
				dataType : "json",
			}).done(function( resp ) {
				window.location.reload(true);
			}).fail(function( xhr, status, errorThrown ) {
			}).always(function( xhr, status ) {
			});
		}
		
	});
	
	
});
</script>