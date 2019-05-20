<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$admin = new admin();
	$mysql = new mysql();	
?>
<div class="row m-b-20">
	<div class="col-xs-12">
    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">
            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
            <li class="slideInDown wow animated">CMS</li>
            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_CMS_Blocks')); ?></li>
        </ol>
    </div>
</div>

<div class="m-t-10">
	<h4 class="m-b-20">
		<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Manage_CMS_Blocks')); ?>
        <a href="#" class="btn btn-danger btn-sm pull-right btnAddNewPage"> <i class="fa fa-plus"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_add_Block')); ?></a>
    </h4>
    
    <table class="table table-hover table-striped">
    	<tr>
        	<th width="16"></th>
            <th width="16"></th>
            <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Name')); ?></th>
            <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Action')); ?></th>
        </tr>
        <?php
		$sql= 'select * from ' . CMS_BLOCKS ;
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
                
                <td>
                	<a href="<?php echo '../cms_block_update.html?id=' . $row['id']; ?>" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>
                	<button type="button" class="btn btn-sm btn-danger btnDeleteBlock" data-id="<?php echo $row['id']; ?>"><i class="fa fa-times"></i></button>
                    
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


<script>
$(document).ready(function(e) {
    
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
			if(resp.status == 1){
				location.reload(true);
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
			var _url = '<?php echo CONFIG_PATH_SITE_ADMIN; ?>cms_pages_edit_process.do';
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
});
</script>