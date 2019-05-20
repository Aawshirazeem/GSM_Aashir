<link rel="stylesheet" href="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets_1/bower_components/summernote/dist/summernote.css">
<script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets_1/bower_components/summernote/dist/summernote.js"></script>
<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$admin = new admin();
	$mysql = new mysql();	
?>
<div class="row m-b-20">
	<div class="col-xs-12">
    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">
            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php $lang->prints('lbl_dashboard'); ?></a></li>
            <li class="slideInDown wow animated">CMS</li>
            <li class="slideInDown wow animated active"><?php $lang->prints('lbl_Slider'); ?></li>
        </ol>
    </div>
</div>

<div class="m-t-10">
	<h4 class="m-b-20">
		<?php $lang->prints('lbl_Manage_Slider'); ?>
        <a href="#" class="btn btn-danger btn-sm pull-right btnAddNewPage"> <i class="fa fa-plus"></i> <?php $lang->prints('com_add_slider'); ?></a>
        <!--<a href="#" class="btn btn-danger btn-sm pull-right btnChangeSliderHW"> <i class="fa fa-picture-o"></i> <?php $lang->prints('com_slider_dimensions'); ?></a>-->
    </h4>
    
    <table class="table table-hover table-striped">
    	<tr>
        	<th width="16"></th>
            <th width="16"></th>
            <th><?php $lang->prints('lbl_Title'); ?></th>
            <th width=""><?php $lang->prints('lbl_image'); ?></th>
            <th width=""><?php $lang->prints('lbl_status'); ?></th>
            <th><?php $lang->prints('lbl_Action'); ?></th>
        </tr>
        <?php
		$sql= 'select * from ' . SLIDER_MASTER ;
		$query = $mysql->query($sql);
		$strReturn = "";
		$i = 1;
		if($mysql->rowCount($query) > 0){
			$rows = $mysql->fetchArray($query);
			foreach($rows as $row){
		?>
        	<tr>
            	<td><?php echo $i++; ?></td>
                <td></td>
                <td><?php echo $row['slider_title']; ?></td>
                <td style="width:330px;"><img src="<?php echo CONFIG_PATH_THEME_NEW.'slider_upload/'.$row['image']; ?>" class="img-responsive" style="width:100%;"/></td>
                <td>
                	<?php
					if($row['is_active'] == 1){
					?>
                    	<button type="button" class="btn btn-sm btn-success btnSliderStatus" data-id="<?php echo $row['id']; ?>"><i class="fa fa-check"></i></button>
                    <?php
					}else{
					?>
						<button type="button" class="btn btn-sm btn-grey btnSliderStatus" data-id="<?php echo $row['id']; ?>"><i class="fa fa-check"></i></button>
					<?php
                        }
                    ?>
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-info btnEditSlider" data-id="<?php echo $row['id']; ?>"><i class="fa fa-pencil"></i></button>
                   	<button type="button" class="btn btn-sm btn-danger btnDeletePage" data-id="<?php echo $row['id']; ?>"><i class="fa fa-times"></i></button>
                </td>
            </tr>
        <?php
			}
		}else{
		?>
        	<tr>
            	<td colspan="8" class="no_record">No record found!</td>
            </tr>
        <?php
		}
		?>
	</table>
</div>


<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
    <!-- Modal content-->
    	<div class="modal-content">
        	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Slider</h4>
           	</div>
            <form name="frmAddSlider" id="frmAddSlider" class="frmAddSlider" action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>slider_add_process.do" method="post" enctype="multipart/form-data">
            	<input type="hidden" name="hdnUpdateId" id="hdnUpdateId" class="hdnUpdateId" value="0"/>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label> <?php $lang->prints('lbl_title'); ?> </label>
                                <input name="sliderTitle" type="text" class="form-control" id="title" value="" autocomplete="off" />
                            </div>
                            <div class="form-group">
                                <label> <?php $lang->prints('lbl_image'); ?> </label>
                                <div class="row cleafix">
                                    <div class="col-md-6">
                                        <input name="sliderImage" type="file" class="form-control" id="sliderImage" value="" required />
                                    </div>
                                    <div class="col-md-6">
                                        <div class="imgResp"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                            	<label> <?php $lang->prints('lbl_notes'); ?> </label>
                            	<div id="summernote"></div>
                                <input type="hidden" name="notes" id="notes" class="notes" value=""/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btnSavePage btnBeforeClick" name="btnSavePage">Add</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
                <h4 class="modal-title">Slider Dimensions</h4>
           	</div>
            <form name="frmAddSlider" id="frmAddSlider" class="frmAddSlider" action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>slider_edit_process.do" method="post">
            	<input type="hidden" name="sHW" id="sHW" value="1"/>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                            	<div class="row cleafix">
                                    <div class="col-md-6">
                                    	<label> <?php $lang->prints('lbl_slider_width'); ?> </label>
                                        <input name="sliderWidth" type="text" class="form-control" id="sliderWidth" value="" autocomplete="off" />
                                    </div>
                                    <div class="col-md-6">
                                    	<label> <?php $lang->prints('lbl_slider_height'); ?> </label>
                                        <input name="sliderHeight" type="text" class="form-control" id="sliderHeight" value="" autocomplete="off" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btnSaveSliderHW" name="btnSaveSliderHW">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.js"></script>
<script>
$(document).ready(function(e) {
	$('.frmAddSlider').validate();	
	$('#summernote').summernote({
		airMode: false,
		height: 300, // set editor height
		minHeight: null, // set minimum height of editor
		maxHeight: null, // set maximum height of editor
		focus: true,
		popover: false,
		toolbar: [
			// [groupName, [list of button]]
			['style', ['bold', 'italic', 'underline', 'clear']],
			['font', ['strikethrough', 'superscript', 'subscript']],
			['fontsize', ['fontsize']],
			//['color', ['color']],
			['para', ['ul', 'ol', 'paragraph']],
			['height', ['height']]
		]
	});
	
	$('.btnBeforeClick').click(function(e){
		e.preventDefault();
		if($('#sliderImage').val() != ""){
			var _form = $(this).closest('form');
			var textareaValue = $('#summernote').summernote('code');
			$('.notes').val(textareaValue);
			_form[0].submit();
		}else{
			if($('.imgResp').is(':empty')){
			  alert('Please select image for slider.');
			}else{
				var _form = $(this).closest('form');
				var textareaValue = $('#summernote').summernote('code');
				$('.notes').val(textareaValue);
				_form[0].submit();
			}
		}
	})
	
    $(document).on('click','.btnAddNewPage',function(e){
		e.preventDefault();
		$('#myModal').modal();
	});
	
	$(document).on('click','.btnChangeSliderHW',function(e){
		e.preventDefault();
		$('#myModalSliderDimensions').modal();
	});
	
	$(document).on('submit','.frmAddCmsPage',function(e){
		e.preventDefault();
	});
	
	$(document).on('click','.btnSliderStatus',function(e){
		e.preventDefault();
		var _url = '<?php echo CONFIG_PATH_SITE_ADMIN; ?>slider_add_process.do';
		var _id = $(this).data('id');
		$.ajax({
			url: _url,
			data: {	id: _id},
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
	
	$(document).on('click','.btnDeletePage',function(e){
		e.preventDefault();
		if(confirm('Are you sure to delete page?')){
			var _url = '<?php echo CONFIG_PATH_SITE_ADMIN; ?>slider_edit_process.do';
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
	
	$(document).on('click','.btnEditSlider',function(e){
		e.preventDefault();
		var _url = '<?php echo CONFIG_PATH_SITE_ADMIN; ?>slider_edit_process.do';
		var _id = $(this).data('id');
		var isEdit = 1;
		
		$.ajax({
			url: _url,
			data: {	id: _id,isEdit:isEdit},
			type: "POST",
			dataType : "json",
		}).done(function( resp ) {
			if(resp.status == 1){
				$('.frmAddSlider').attr('action','<?php echo CONFIG_PATH_SITE_ADMIN; ?>slider_edit_process.do');
				$('.hdnUpdateId').val(resp.id);
				$('#title').val(resp.title);
				$('.imgResp').html('<img src="<?php echo CONFIG_PATH_THEME_NEW.'slider_upload/'; ?>'+resp.image+'" class="img-responsive" style="width:100%;"/>');
				$("#summernote").summernote("code", resp.notes);
				$('#myModal').modal();
			}else{
				alert('something went wrong.');
			}
		}).fail(function( xhr, status, errorThrown ) {
		}).always(function( xhr, status ) {
		});
	});
});
</script>