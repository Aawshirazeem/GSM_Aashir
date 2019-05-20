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
            <li class="slideInDown wow animated active"><?php $lang->prints('lbl_CMS_Pages'); ?></li>
        </ol>
    </div>
</div>

<div class="m-t-10">
	<h4 class="m-b-20">
		<?php $lang->prints('lbl_Manage_CMS_Pages'); ?>
        <a href="#" class="btn btn-danger btn-sm pull-right btnAddNewPage"> <i class="fa fa-plus"></i> <?php $lang->prints('com_add_pages'); ?></a>
    </h4>
    
    <table class="table table-hover table-striped">
    	<tr>
        	<th width="16"></th>
            <th width="16"></th>
            <th><?php $lang->prints('lbl_Title'); ?></th>
            <th width="" style="text-align:center"><?php $lang->prints('lbl_URL'); ?></th>
            <th width="" style="text-align:center"><?php $lang->prints('lbl_meta'); ?></th>
            <th width="" style="text-align:center"><?php $lang->prints('lbl_as_home'); ?></th>
            <th><?php $lang->prints('lbl_Action'); ?></th>
        </tr>
        <?php
		$sql= 'select * from ' . CMS_PAGE_MASTER ;
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
                <td><?php echo $row['title']; ?></td>
                <td><?php echo $row['url']; ?></td>
                <td><?php echo $row['meta']; ?></td>
                <td>
                	<?php
                    if($row['is_home_page'] == 1){
					?>
                    	<button type="button" class="btn btn-sm btn-info btnChangeHome" data-id="<?php echo $row['id']; ?>"><i class="fa fa-check"></i></button>
                    <?php
					}else{
					?>
                    	<button type="button" class="btn btn-sm btn-danger btnChangeHome" data-id="<?php echo $row['id']; ?>"><i class="fa fa-times"></i></button>
                    <?php
					}
					?>
                </td>
                <td><a href="<?php echo '../cms.html?id=' . $row['id']; ?>" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a></td>
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
                <h4 class="modal-title">CMS Page</h4>
           	</div>
            <form name="frmAddCmsPage" id="frmAddCmsPage" class="frmAddCmsPage" action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>cms_pages_add_process.do" name="post">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label> <?php $lang->prints('lbl_page_title'); ?> </label>
                                <input name="pageTitle" type="text" class="form-control" id="title" value="" autocomplete="off" />
                            </div>
                            <div class="form-group">
                                <label> <?php $lang->prints('lbl_meta_keyowrds'); ?> </label>
                                <textarea class="form-control" rows="5" style="resize:none;" name="pageMetaKeyword"></textarea>
                            </div>
                            <div class="form-group">
                                <label> <?php $lang->prints('lbl_page_url'); ?> </label>
                                <div class="input-group">
                                    <span class="input-group-addon">http://old.imei.pk/</span>
                                    <input type="text" name="pageUrl" class="form-control" aria-describedby="basic-addon3" autocomplete="off">
                                    <span class="input-group-addon">.html</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="c-input c-checkbox">
                                	<input type="checkbox" name="is_home" id="is_home" class="is_home" value="1">
                                    <span class="c-indicator c-indicator-success"></span> Set as Home
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btnSavePage">Add</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function(e) {
    $(document).on('click','.btnAddNewPage',function(e){
		e.preventDefault();
		$('#myModal').modal();
	});
	
	$(document).on('submit','.frmAddCmsPage',function(e){
		e.preventDefault();
		var _url = $(this).attr('action');
		var _formdata = $(this).serialize();
		$.ajax({
			url: _url,
			data: {	formstring: _formdata},
			type: "POST",
			dataType : "json",
		}).done(function( resp ) {
			console.log(resp);
			if(resp.status == 1){
				window.location.href = '<?php echo 'cms.html?id=' ?>'+resp.inserted_id;
			}else{
				alert('fail');
			}
		}).fail(function( xhr, status, errorThrown ) {
		}).always(function( xhr, status ) {
		});
	});
	
	$(document).on('click','.btnChangeHome',function(e){
		e.preventDefault();
		var _url = '<?php echo CONFIG_PATH_SITE_ADMIN; ?>cms_pages_add_process.do';
		var _id = $(this).data('id');
		$.ajax({
			url: _url,
			data: {	id: _id},
			type: "POST",
			dataType : "json",
		}).done(function( resp ) {
			console.log(resp);
			if(resp.status == 1){
				location.reload();
			}else{
				alert('something went wrong.');
			}
		}).fail(function( xhr, status, errorThrown ) {
		}).always(function( xhr, status ) {
		});
	});
});
</script>