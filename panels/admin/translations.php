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
            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_translations')); ?></li>
        </ol>
    </div>
</div>

<div class="m-t-10">
	<h4 class="m-b-20">
		<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Manage_Translation')); ?>
        <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>translations_add.html" class="btn btn-danger btn-sm pull-right btnAddTranslation">
        	<i class="fa fa-plus"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_translation')); ?>
        </a>
        <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>translations_pending.html" class="btn btn-danger btn-sm pull-right m-r-10 btnPendingTranslation">
        	<i class="fa fa-plus"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_scan_pending_translation')); ?>
        </a>
        <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>translations_pending.html" class="btn btn-danger btn-sm pull-right m-r-10">
        	<i class="fa fa-eye"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_view_pending_translation')); ?>
        </a>
    </h4>
    
	<div class="table-responsive">
	
    <table id="transTable" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th><?php echo $admin->wordTrans($admin->getUserLang(),'Sr'); ?></th>
                <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_word')); ?></th>
                <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Action')); ?></th>
            </tr>
        </thead>
        <tbody>
        	<?php
			//$sql= 'select '.TRANSLATION_MASTER.'.id as t_id,lang_code,ori_word,tran_word,language from ' . TRANSLATION_MASTER . ' JOIN '.LANG_MASTER.' ON '.LANG_MASTER.'.language_code = '.TRANSLATION_MASTER.'.lang_code' ;
			$sql= 'select * from ' . TRANSLATION_MASTER . ' GROUP BY ori_word' ;
		
			$query = $mysql->query($sql);
			$i = 1;
			if($mysql->rowCount($query) > 0){
				$rows = $mysql->fetchArray($query);
				foreach($rows as $row){
			?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo $row['ori_word']; ?></td>
                <td>
                	<a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>translations_edit.html" class="btn btn-sm btn-info btnEditTranslation" data-word="<?php echo $row['ori_word']; ?>"><i class="fa fa-pencil"></i></a>
                   	<button type="button" class="btn btn-sm btn-danger btnDeleteTranslation" data-word="<?php echo $row['ori_word']; ?>"><i class="fa fa-times"></i></button>
                </td>
            </tr>
            <?php
				}
			}
			?>
        </tbody>
    </table>
	
	</div>
	
	
</div>

<div id="addNewTranslationModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
    <!-- Modal content-->
    	<div class="modal-content">
        	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
					<?php echo $admin->wordTrans($admin->getUserLang(),'Translation'); ?>
                    <?php
					if(CONFIG_TRANS_GOOGLE_KEY != ""){
					?>
                    <button type="button" class="btn btn-sm btn-warning m-l-10 btnTransAuto" data-yedit="0"><?php echo $admin->wordTrans($admin->getUserLang(),'Auto Translate'); ?></button>
                    <?php } ?>
                </h4>
                
       	  </div>
            <div class="transBefore">
            	<input type="hidden" name="yEdit" class="yEdit" id="yEdit" value="0"/>
                <form name="frmAddTranslation" id="frmAddTranslation" class="frmAddTranslation" action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>translations_add_process.do" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_word')); ?> </label>
                                    <input name="oriWord" type="text" class="form-control oriWord" id="oriWord" value="" autocomplete="off" required />
                                </div>
								<?php
                                    $sql= 'select * from ' . LANG_MASTER ;
                                    $query = $mysql->query($sql);
                                    if($mysql->rowCount($query) > 0){
                                        $rows = $mysql->fetchArray($query);
                                        foreach($rows as $row){
                                ?>
                                    <div class="form-group">
                                        <label> <?php echo $row['language']; ?> </label>
                                        <input name="tranWord[]" type="text" class="form-control tranWord tranLang_<?php echo $row['language_code']; ?>" data-tranlang="<?php echo $row['language_code']; ?>" id="tranWord" value="" autocomplete="off" required />
                                    </div>
                                <?php
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btnSavePage btnBeforeClick" name="btnSavePage"><?php echo $admin->wordTrans($admin->getUserLang(),'Add'); ?></button>
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $admin->wordTrans($admin->getUserLang(),'Close'); ?></button>
                    </div>
                </form>
            </div>
            <div class="editTransResp"></div>
        </div>
    </div>
</div>

<div id="pendingTranslationModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
    <!-- Modal content-->
    	<div class="modal-content">
        	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
					<?php echo $admin->wordTrans($admin->getUserLang(),'Pending Translation'); ?>
                </h4>
            </div>
            <div class="modal-body">
                <div class="pendingTransResp"></div>
            </div>
            <div class="modal-footer">
            	<a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>translations_pending.html" target="_blank" class="btn btn-primary"><?php echo $admin->wordTrans($admin->getUserLang(),'View Pending Translations'); ?></a>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $admin->wordTrans($admin->getUserLang(),'Close'); ?></button>
            </div>
        </div>
    </div>
</div>

<!--<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" type="text/css"/>-->
<!--<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>-->
<!--<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/bs-3.3.5/jq-2.1.4,dt-1.10.8/datatables.min.css"/>-->

<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf-8">
	var english = {
		"sLengthMenu": "<?php echo $admin->wordTrans($admin->getUserLang(),'Display'); ?> _MENU_ <?php echo $admin->wordTrans($admin->getUserLang(),'records per page'); ?>",
		"sZeroRecords": "<?php echo $admin->wordTrans($admin->getUserLang(),'Nothing found - sorry'); ?>",
		"sInfo": "<?php echo $admin->wordTrans($admin->getUserLang(),'Showing'); ?> _START_ <?php echo $admin->wordTrans($admin->getUserLang(),'to'); ?> _END_ <?php echo $admin->wordTrans($admin->getUserLang(),'of'); ?> _TOTAL_ <?php echo $admin->wordTrans($admin->getUserLang(),'records'); ?>",
		"sInfoEmpty": "<?php echo $admin->wordTrans($admin->getUserLang(),'Showing'); ?> 0 <?php echo $admin->wordTrans($admin->getUserLang(),'to'); ?> 0 <?php echo $admin->wordTrans($admin->getUserLang(),'of'); ?> 0 <?php echo $admin->wordTrans($admin->getUserLang(),'records'); ?>",
		"sInfoFiltered": "(<?php echo $admin->wordTrans($admin->getUserLang(),'filtered from'); ?> _MAX_ <?php echo $admin->wordTrans($admin->getUserLang(),'total records'); ?>)",
		"sSearch": "<?php echo $admin->wordTrans($admin->getUserLang(),'Search:'); ?>",
	};
	
	var currentLang = english;
	$(document).ready(function() {
		var dtable = jQuery('#transTable').DataTable({
			"oLanguage": english
		});
	});
</script>
<script>
$(document).ready(function(e) {
	$(document).on('click','.btnPendingTranslation',function(e){
		e.preventDefault();
		var _this = $(this);
		var _url = '<?php echo CONFIG_PATH_SITE_ADMIN; ?>translations_add_process.do';
		var _isScan = 1;
		_this.html('<i class="fa fa-refresh fa-spin"></i> <?php echo $admin->wordTrans($admin->getUserLang(),'Scanning Files...'); ?>');
		$.ajax({
			url: _url,
			data: {	isScan: _isScan},
			type: "POST",
			dataType : "json",
		}).done(function( resp ) {
			if(resp.pWordCount == 1){
				var _strPendingWords = '<?php echo $admin->wordTrans($admin->getUserLang(),'You have'); ?> <h3>'+resp.pWordCount+'</h3> <?php echo $admin->wordTrans($admin->getUserLang(),'pending translation.'); ?>';
				$('.pendingTransResp').html(_strPendingWords);
			}else{
				var _strPendingWords = '<?php echo $admin->wordTrans($admin->getUserLang(),'You have'); ?> <h3>'+resp.pWordCount+'</h3> <?php echo $admin->wordTrans($admin->getUserLang(),'pending translations.'); ?>';
				$('.pendingTransResp').html(_strPendingWords);
			}
			
			_this.html('<i class="fa fa-plus"></i> <?php echo $admin->wordTrans($admin->getUserLang(),'Scan Pending Translation'); ?>');
			$('#pendingTranslationModal').modal();
		}).fail(function( xhr, status, errorThrown ) {
		}).always(function( xhr, status ) {
		});
		
	});	
	//################### auto translate ###################//
	$(document).on('click','.btnTransAuto',function(e){
		e.preventDefault();
		var _this = $(this);
		var _url = '<?php echo CONFIG_PATH_SITE_ADMIN; ?>translations_add_process.do';
		var _isAuto = 1;
		var _checkEdit = $('.yEdit').val();
		if(_checkEdit != 0){
			var _oWord = $('.editTransResp').find('.oriWord').val();
		}else{
			var _oWord = $('.transBefore').find('.oriWord').val();
		}
		
		if(_oWord != ""){
			_this.text('<?php echo $admin->wordTrans($admin->getUserLang(),'Translating...'); ?>');
			$.ajax({
				url: _url,
				data: {	word: _oWord,isAuto:_isAuto},
				type: "POST",
				dataType : "json",
			}).done(function( resp ) {
				var JSONString = resp;
				var JSONString = JSON.stringify(JSONString);
				var JSONObject = JSON.parse(JSONString);
				
				$.each(JSONObject, function (index, value) {
				  $('input[data-tranlang="'+value['lang']+'"]').val(value['translatedText']);
				});
				_this.text('<?php echo $admin->wordTrans($admin->getUserLang(),'Auto Translate'); ?>');
			}).fail(function( xhr, status, errorThrown ) {
			}).always(function( xhr, status ) {
			});
		}else{
			alert('<?php echo $admin->wordTrans($admin->getUserLang(),'Please fill original word.'); ?>');
		}
	});
	
	//####################### delete translation ##################//
	$(document).on('click','.btnDeleteTranslation',function(e){
		e.preventDefault();
		if(confirm('Are you sure to delete translation?')){
			var _url = '<?php echo CONFIG_PATH_SITE_ADMIN; ?>translations_edit_process.do';
			var _word = $(this).data('word');
			var isDelete = 1;
			$.ajax({
				url: _url,
				data: {	word: _word,isDelete:isDelete},
				type: "POST",
				dataType : "json",
			}).done(function( resp ) {
				if(resp.status == 1){
					location.reload();
				}else{
					alert('<?php echo $admin->wordTrans($admin->getUserLang(),'Something went wrong.'); ?>');
				}
			}).fail(function( xhr, status, errorThrown ) {
			}).always(function( xhr, status ) {
			});
		}
	});
	
	//####################### add translation ##################//
	$(document).on('click','.btnAddTranslation',function(e){
		e.preventDefault();
		$('.btnTransAuto').attr('data-yedit',0);
		$('.yEdit').val(0);
		$('.transBefore').find('.oriWord').val('');
		$('.editTransResp').html('');
		$('#addNewTranslationModal').modal();
	});
	
	$('#addNewTranslationModal').on('hidden.bs.modal', function (e) {
	  $('.frmAddTranslation')[0].reset();
	  $('.yEdit').val(0);
	  $('.editTransResp').html('');
	  $('.transBefore').show();
	})
	
	$(document).on('click','.btnEditTranslation',function(e){
		e.preventDefault();
		var _url = '<?php echo CONFIG_PATH_SITE_ADMIN; ?>translations_edit_process.do';
		var _word = $(this).data('word');
		var isEdit = 1;
		$('.yEdit').val(1);
		$.ajax({
			url: _url,
			data: {	word: _word,isEdit:isEdit},
			type: "POST",
			dataType : "json",
		}).done(function( resp ) {
			if(resp.status == 1){
				$('.transBefore').find('.oriWord').val(_word);
				$('.transBefore').hide();
				$('.editTransResp').html(resp.result);
				$('#addNewTranslationModal').modal();
			}else{
				alert('<?php echo $admin->wordTrans($admin->getUserLang(),'Something went wrong.'); ?>');
			}
		}).fail(function( xhr, status, errorThrown ) {
		}).always(function( xhr, status ) {
		});
	});
});
</script>

<!--<script type="text/javascript">
	$("#search").on("keyup", function(){
		var searchText = $(this).val();
		searchText = searchText.toUpperCase();
		//$('.contacts-list > li').each(function(){
		$('.bs-media > .media').each(function(){
			var currentLiText = $(this).text().toUpperCase();
			showCurrentLi = currentLiText.indexOf(searchText) !== -1;
			$(this).toggle(showCurrentLi);
		});
	});
</script>-->
