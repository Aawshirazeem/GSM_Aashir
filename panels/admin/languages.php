<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$admin = new admin();
	$mysql = new mysql();	
?>
<div class="row m-b-20">
	<div class="col-xs-12">
    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">
            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
            <li class="slideInDown wow animated"><?php echo $admin->wordTrans($admin->getUserLang(),'Settings'); ?></li>
            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_languages')); ?></li>
        </ol>
    </div>
</div>

<div class="m-t-10">
	<h4 class="m-b-20">
		<?php echo $admin->wordTrans($admin->getUserLang(),$admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Manage_Languages'))); ?>
        <a href="#" class="btn btn-danger btn-sm pull-right btnAddNewLanguage">
        	<i class="fa fa-plus"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_language')); ?>
        </a>
    </h4>
	
	<div class="table-responsive">
    
    <table class="table table-hover table-striped">
    	<tr>
        	<th width="16"></th>
            <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_language_name')); ?></th>
            <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_code')); ?></th>
            <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_status')); ?></th>
            <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Action')); ?></th>
        </tr>
        <?php
		$sql= 'select * from ' . LANG_MASTER ;
		$query = $mysql->query($sql);
		$i = 1;
		if($mysql->rowCount($query) > 0){
			$rows = $mysql->fetchArray($query);
			foreach($rows as $row){
		?>
        	<tr>
            	<td><?php echo $i++; ?></td>
                <td><?php echo $row['language']; ?></td>
                <td><?php echo $row['language_code']; ?></td>
                <td>
                	<?php if($row['lang_status'] == 1){ ?>
                    	<button type="button" class="btn btn-sm btn-info btnChangeStatus" data-status="<?php echo $row['lang_status']; ?>" data-id="<?php echo $row['id']; ?>"><i class="fa fa-check"></i></button>
                    <?php }else{ ?>
                    	<button type="button" class="btn btn-sm btn-grey btnChangeStatus" data-status="<?php echo $row['lang_status']; ?>" data-id="<?php echo $row['id']; ?>"><i class="fa fa-check"></i></button>
					<?php } ?>
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-info btnEditLanguage" data-id="<?php echo $row['id']; ?>"><i class="fa fa-pencil"></i></button>
                   	<button type="button" class="btn btn-sm btn-danger btnDeleteLanguage" data-id="<?php echo $row['id']; ?>"><i class="fa fa-times"></i></button>
                </td>
            </tr>
        <?php
			}
		}else{
		?>
        	<tr>
            	<td colspan="4" class="no_record text-center"><?php echo $admin->wordTrans($admin->getUserLang(),'No record found!'); ?></td>
            </tr>
        <?php
		}
		?>
	</table>
	</div>
</div>

<!-- Add New Language Modal -->
<div id="addNewLanguageModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
    <!-- Modal content-->
    	<div class="modal-content">
        	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal"><?php echo $admin->wordTrans($admin->getUserLang(),'&times;'); ?></button>
                <h4 class="modal-title"><?php echo $admin->wordTrans($admin->getUserLang(),'Language'); ?></h4>
           	</div>
            <form name="frmAddLanguage" id="frmAddLanguage" class="frmAddLanguage" action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>languages_add_process.do" method="post" enctype="multipart/form-data">
            	<input type="hidden" name="hdnUpdateId" id="hdnUpdateId" class="hdnUpdateId" value="0"/>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_language_name')); ?> </label>
                                <input name="languageName" type="text" class="form-control languageName" id="languageName" value="" autocomplete="off" required />
                            </div>
                            <div class="form-group">
                                <label> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_language_code')); ?> </label>
                                <input name="languageCode" type="text" class="form-control languageCode" id="languageCode" value="" autocomplete="off" required />
                            </div>
                            <div class="form-group">
                                <label> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_flag')); ?> </label>
                                <div class="row">
                                	<div class="col-md-6">
                                    	<input type="file" name="languageFlag" id="languageFlag" class="languageFlag form-control"/>
                                    </div>
                                    <div class="col-md-6">
                                    	<img src="" class="langFlag" style="width:100%;"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btnSavePage btnBeforeClick" name="btnSavePage"><?php echo $admin->wordTrans($admin->getUserLang(),'Add'); ?></button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $admin->wordTrans($admin->getUserLang(),'Close'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function(e) {
    $(document).on('click','.btnAddNewLanguage',function(e){
		e.preventDefault();
		$('.frmAddLanguage').attr('action','<?php echo CONFIG_PATH_SITE_ADMIN; ?>languages_add_process.do');
		$('.btnSavePage').text('Add');
		$('.langFlag').hide();
		$('#addNewLanguageModal').modal();
	});
	
	$('#addNewLanguageModal').on('hidden.bs.modal', function (e) {
	  $('.frmAddLanguage')[0].reset();
	})
	
	//######################### edit language ##########################//
	$(document).on('click','.btnEditLanguage',function(e){
		e.preventDefault();
		var _url = '<?php echo CONFIG_PATH_SITE_ADMIN; ?>languages_edit_process.do';
		var _id = $(this).data('id');
		var isEdit = 1;
		
		$.ajax({
			url: _url,
			data: {	id: _id,isEdit:isEdit},
			type: "POST",
			dataType : "json",
		}).done(function( resp ) {
			if(resp.status == 1){
				$('.langFlag').show();
				$('.frmAddLanguage').attr('action','<?php echo CONFIG_PATH_SITE_ADMIN; ?>languages_edit_process.do');
				$('.hdnUpdateId').val(resp.id);
				$('.languageName').val(resp.name);
				$('.languageCode').val(resp.code);
				$('.langFlag').attr('src',resp.langFlag);
				
				$('.btnSavePage').text('<?php echo $admin->wordTrans($admin->getUserLang(),'Save'); ?>');
				$('#addNewLanguageModal').modal();
			}else{
				alert('something went wrong.');
			}
		}).fail(function( xhr, status, errorThrown ) {
		}).always(function( xhr, status ) {
		});
	});
	
	//####################### delete language ##################//
	$(document).on('click','.btnDeleteLanguage',function(e){
		e.preventDefault();
		if(confirm('Are you sure to delete language?')){
			var _url = '<?php echo CONFIG_PATH_SITE_ADMIN; ?>languages_edit_process.do';
			var _id = $(this).data('id');
			var isDelete = 1;
			$.ajax({
				url: _url,
				data: {	id: _id,isDelete:isDelete},
				type: "POST",
				dataType : "json",
			}).done(function( resp ) {
				console.log(resp);
				if(resp.status == 1){
					alert(resp.msg);
					location.reload();
				}else{
					alert('something went wrong.');
				}
			}).fail(function( xhr, status, errorThrown ) {
			}).always(function( xhr, status ) {
			});
		}
	});
	
	//####################### change status ######################//
	$(document).on('click','.btnChangeStatus',function(e){
		e.preventDefault();
		var _this = $(this);
		_this.html('<i class="fa fa-refresh fa-spin"></i>');
		_this.attr('disabled',true);
		var _url = '<?php echo CONFIG_PATH_SITE_ADMIN; ?>languages_edit_process.do';
		var _id = $(this).data('id');
		var _status = $(this).data('status');
		var isChange = 1;
		$.ajax({
			url: _url,
			data: {	id: _id,isChange:isChange,status:_status},
			type: "POST",
			dataType : "json",
		}).done(function( resp ) {
			if(resp.status == 1){
				location.reload();
			}else{
				alert(resp.msg);
				_this.html('<i class="fa fa-check"></i>');
				_this.attr('disabled',false);
			}
		}).fail(function( xhr, status, errorThrown ) {
		}).always(function( xhr, status ) {
		});
	});
});
</script>