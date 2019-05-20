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
            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_CMS_Pages')); ?></li>
        </ol>
    </div>
</div>

<div class="m-t-10" style="padding-bottom:500px;">
	<h4 class="m-b-20 clearfix">
		<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Manage_CMS_Pages')); ?>
        <a href="#" class="btn btn-danger btn-sm pull-right btnAddNewPage"> <i class="fa fa-plus"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_add_pages')); ?></a>
    </h4>
	<div class="table-responsive">
    <table class="table table-hover table-striped">
    	<tr>
        	<th width="16"></th>
            <th width="16"></th>
            <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_master_Title')); ?></th>
            <th width=""><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_URL')); ?></th>
            <?php /*?><th width=""><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_meta')); ?></th><?php */?>
            <th width=""><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_as_home')); ?></th>
            <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Action')); ?></th>
        </tr>
        <?php
		$sql= 'select * from ' . CMS_PAGE_MASTER .' GROUP BY m_title ORDER BY id';
		$sql= 'select * from ' . CMS_PAGE_MASTER.' a
		where a.title like "Home%"
		union 
		select * from ' . CMS_PAGE_MASTER.' a
		where a.title like "Footer%"
		union 
		select * from ' . CMS_PAGE_MASTER.' a
		where (a.m_title not like "Footer%") and (a.m_title not like "Home%") GROUP BY m_title' ;

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
                <td><?php echo $row['m_title']; ?></td>
                <td><?php echo $row['url']; ?></td>
                <?php /*?><td><?php echo $row['meta']; ?></td><?php */?>
                <td>
                <?php if($row['id'] == 1 || $row['id'] == 2 || $row['id'] == 16 || $row['id'] == 19 || $row['id'] == 67|| $row['id'] == 71|| $row['id'] == 72|| $row['id'] == 73){ ?>
                <?php if($row['is_home_page'] == 1){ ?>
                    <button type="button" class="btn btn-sm btn-info btnChangeHome" data-id="<?php echo $row['id']; ?>" data-mtitle="<?php echo $row['m_title']; ?>"><i class="fa fa-check"></i></button>
                <?php }else{ ?>
                    <button type="button" class="btn btn-sm btn-grey btnChangeHome" data-id="<?php echo $row['id']; ?>" data-mtitle="<?php echo $row['m_title']; ?>"><i class="fa fa-check"></i></button>
                <?php } ?>
                <?php } ?>
                </td>
                <td>
                    <?php if($row['id'] != 1 && $row['id'] != 2 && $row['id'] != 16 && $row['id'] != 19 && $row['id'] != 67 && $row['id'] != 71 && $row['id'] != 72 && $row['id'] != 73&& $row['id'] != 22 && $row['id'] != 17 && $row['id'] != 18){?>
                    <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>cms_pages_edit_process.do" oncontextmenu="return false;" class="btn btn-primary btn-sm btnChangeMaster" data-title="<?php echo $row['m_title']; ?>"><i class="fa fa-pencil"></i></a>
                    <?php }/*?><a href="<?php echo '../'.$row['url'].'.html'; ?>" target="_blank">
                        <button type="button" class="btn btn-sm btn-default"><i class="fa fa-eye"></i></button>
                    </a><?php */?>
                    <?php if($row['id'] != 1 && $row['id'] != 2 && $row['id'] != 16 && $row['id'] != 19 && $row['id'] != 67 && $row['id'] != 71 && $row['id'] != 72 && $row['id'] != 73&& $row['id'] != 22 && $row['id'] != 17 && $row['id'] != 18){ ?>
                        <button type="button" class="btn btn-sm btn-danger btnDeletePage" data-id="<?php echo $row['id']; ?>" title="Delete whole page"><i class="fa fa-times"></i></button>
                    <?php } ?>
                    <div class="dropdown" style="display:inline-block;">
                        <a class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <?php echo $admin->wordTrans($admin->getUserLang(),'Translations'); ?> </a>
                        <div class="dropdown-menu dropdown-menu-scale from-left">
                            <?php
                            $sqlForLanguage = 'select * from ' . LANG_MASTER ;
                            $query = $mysql->query($sqlForLanguage);
                            $languageList = $mysql->fetchArray($query);
                            foreach($languageList as $language){
                            ?>
                                <a class="dropdown-item pageLangDrop">
                                    <span class="flag flag-icon" style="background-image:url(<?php echo CONFIG_PATH_PANEL_ADMIN.'assets_1/language_flag/'.$language['language_flag']; ?>);top: -6px;"></span>
                                    <span class="title langTitle"><?php echo $language['language']; ?></span>
                                    <span class="title pageLangDropAction">
                                        <button class="btn btn-sm btn-primary changePageLang" data-purl="<?php echo $row['url']; ?>" data-mtitle="<?php echo $row['m_title']; ?>" data-plang="<?php echo $language['language_code']; ?>" data-url="<?php echo CONFIG_PATH_SITE_ADMIN; ?>cms_pages_edit_process.do">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        <?php
                                        $sqlForAvailPage = 'select * from ' . CMS_PAGE_MASTER.' where m_title = "'.$row['m_title'].'" AND page_lang = "'.$language['language_code'].'"' ;
                                        $queryRow = $mysql->query($sqlForAvailPage);
                                        $rowsForAvailPage = $mysql->fetchArray($queryRow);
                                        $sRowForAvailPage = $rowsForAvailPage[0];
                                        if(count($sRowForAvailPage) > 1){
                                        ?>
                                        <button class="btn btn-sm btn-info btnViewPage" data-url="<?php echo '../'.$row['url'].'.html?lang='.$language['language_code']; ?>">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-success btnPageEdit" data-url="<?php echo CONFIG_PATH_SITE_ADMIN; ?>cms_pages_edit_process.do" data-mtitle="<?php echo $row['m_title']; ?>" data-lang="<?php echo $language['language_code']; ?>" title="Change Page Content">
                                            <i class="fa fa-file-text-o"></i>
                                        </button>
                                        <?php if($language['language_code'] != "en"){ ?>
                                        <button type="button" class="btn btn-sm btn-danger btnDeletePage" data-url="<?php echo CONFIG_PATH_SITE_ADMIN; ?>cms_pages_edit_process.do" data-mtitle="<?php echo $row['m_title']; ?>" data-lang="<?php echo $language['language_code']; ?>" data-id="0"><i class="fa fa-trash-o"></i></button>
                                        <?php } ?>
                                        <?php
                                        }
                                        ?>
                                    </span>
                                </a>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </td>
            </tr>
        <?php
			if($i==9 || $i==12){
				echo '<tr><td colspan="7"><hr><td></tr>';
			}
		}
	}else{
	?>
    	<tr>
        	<td colspan="8" class="no_record"><?php echo $admin->wordTrans($admin->getUserLang(),'No record found!'); ?></td>
        </tr>
    <?php
    }
	?>
    </table>
	</div>
</div>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
    <!-- Modal content-->
    	<div class="modal-content">
        	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $admin->wordTrans($admin->getUserLang(),'CMS Page'); ?></h4>
           	</div>
            <form name="frmAddCmsPage" id="frmAddCmsPage" class="frmAddCmsPage" action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>cms_pages_add_process.do" method="post">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_master_title')); ?> </label>
                                <input name="pageMasterTitle" type="text" class="form-control pageMasterTitle" id="pageMasterTitle" value="" autocomplete="off" required />
                            </div>
							<?php /*?><div class="form-group">
                                <label> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_meta_tags')); ?> </label>
                                <textarea class="form-control" rows="5" style="resize:none;" name="pageMetaKeyword"></textarea>
                            </div><?php */?>
                            <div class="form-group">
                                <label> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_page_url')); ?> </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><?php echo CONFIG_PATH_SITE;?></span>
                                    <input type="text" name="pageUrl" class="form-control" aria-describedby="basic-addon3" autocomplete="off" required>
                                    <span class="input-group-addon">.html</span>
                                </div>
                            </div>
							<?php /*?><div class="form-group">
                                <label class="c-input c-checkbox">
                                	<input type="checkbox" name="is_home" id="is_home" class="is_home" value="1">
                                    <span class="c-indicator c-indicator-success"></span> <?php echo $admin->wordTrans($admin->getUserLang(),'Set as Home'); ?>
                                </label>
                            </div><?php */?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btnSavePage"><?php echo $admin->wordTrans($admin->getUserLang(),'Add'); ?></button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $admin->wordTrans($admin->getUserLang(),'Close'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- change master title Modal -->
<div id="editMasterTitleModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
    <!-- Modal content-->
    	<div class="modal-content">
        	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $admin->wordTrans($admin->getUserLang(),'CMS Page'); ?></h4>
           	</div>
            <form name="frmEditMasterTitle" id="frmEditMasterTitle" class="frmEditMasterTitle" action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>cms_pages_edit_process.do" method="post">
            	<input type="hidden" name="hdnMasterTitle" id="hdnMasterTitle" class="hdnMasterTitle" value=""/>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_master_title')); ?> </label>
                                <input name="pageMasterTitle" type="text" class="form-control pageMasterTitle" id="pageMasterTitle" value="" autocomplete="off" required />
                            </div>
                            <div class="form-group">
                                <label> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_page_url')); ?> </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><?php echo CONFIG_PATH_SITE;?></span>
                                    <input type="text" name="pageUrl" class="form-control pageUrl" id="pageUrl" aria-describedby="basic-addon3" autocomplete="off" required>
                                    <span class="input-group-addon">.html</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><?php echo $admin->wordTrans($admin->getUserLang(),'Save'); ?></button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $admin->wordTrans($admin->getUserLang(),'Close'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- edit language vise page Modal -->
<div id="editPageModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
    <!-- Modal content-->
    	<div class="modal-content">
        	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $admin->wordTrans($admin->getUserLang(),'CMS Page'); ?></h4>
           	</div>
            <form name="frmEditCmsPage" id="frmEditCmsPage" class="frmEditCmsPage" action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>cms_pages_edit_process.do" method="post">
            	<input type="hidden" name="hdnUpdateId" id="hdnUpdateId" class="hdnUpdateId" value="0"/>
                <input type="hidden" name="hdnLangCode" id="hdnLangCode" class="hdnLangCode" value=""/>
                <input type="hidden" name="hdnmTitle" id="hdnmTitle" class="hdnmTitle" value=""/>
                <div class="modal-body">
                	<div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_page_title')); ?> </label>
                                <input name="pageTitle" type="text" class="form-control pageTitle" id="title" value="" autocomplete="off" />
                            </div>
                            <div class="form-group">
                                <label> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_meta_tags')); ?> </label>
                                <textarea class="form-control pageMetaKeyword" rows="5" style="resize:none;" name="pageMetaKeyword"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btnSavePage"><?php echo $admin->wordTrans($admin->getUserLang(),'Save'); ?></button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $admin->wordTrans($admin->getUserLang(),'Close'); ?></button>
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

	/*$(document).on('submit','.frmAddCmsPage',function(e){
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
	});*/
	
	$(document).on('click','.btnChangeMaster',function(e){
		e.preventDefault();
		var _url = $(this).attr('href');
		var _title = $(this).data('title');
		var _changeTitle = 1;
		$.ajax({
			url: _url,
			data: {	title: _title,changeTitle:_changeTitle},
			type: "POST",
			dataType : "json",
		}).done(function( resp ) {
			if(resp.status == 1){
				$('.hdnMasterTitle').val(resp.title);
				$('.pageMasterTitle').val(resp.title);
				$('.pageUrl').val(resp.mUrl);
				$('#editMasterTitleModal').modal();
			}else{
				alert('something went wrong.');
			}
		}).fail(function( xhr, status, errorThrown ) {
		}).always(function( xhr, status ) {
		});
	});
	
	$(document).on('click','.btnChangeHome',function(e){
		e.preventDefault();
		var _url = '<?php echo CONFIG_PATH_SITE_ADMIN; ?>cms_pages_add_process.do';
		var _id = $(this).data('id');
		var _mtitle = $(this).data('mtitle');
		$.ajax({
			url: _url,
			data: {	id: _id,mtitle:_mtitle},
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
			var _id = $(this).attr('data-id');
			var _title = $(this).attr('data-mtitle');
			var _lang_code = $(this).attr('data-lang');
			var isDelete = 1;
			$.ajax({
				url: _url,
				data: {	id:_id,title: _title,lang_code: _lang_code,isDelete:isDelete},
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

	$('#editPageModal').on('hidden.bs.modal', function () {
		$('.frmEditCmsPage')[0].reset();
	});

	$(document).on('click','.changePageLang',function(e){
		e.preventDefault();
		var _url = $(this).data('url');
		var _mtitle = $(this).data('mtitle');
		var _plang = $(this).data('plang');
		var pChange = 1;
		var _this = $(this);
		//_this.parent().parent().find('.dropdown-toggle').html('<i class="fa fa-refresh fa-spin"></i>');
		_this.closest('.dropdown').find('.dropdown-toggle').html('<i class="fa fa-refresh fa-spin"></i>');
		$.ajax({
			url: _url,
			data: {	mtitle:_mtitle,plang:_plang,pChange:pChange},
			type: "POST",
			dataType : "json",
		}).done(function( resp ) {
			//_this.parent().parent().find('.dropdown-toggle').html('Translations');
			_this.closest('.dropdown').find('.dropdown-toggle').html('Translations');
			$('.hdnUpdateId').val(resp.id);
			$('.pageTitle').val(resp.title);
			$('.pageMetaKeyword').val(resp.meta);
			$('.hdnLangCode').val(_plang);
			$('.hdnmTitle').val(_mtitle);
			$('#editPageModal').modal();
		}).fail(function( xhr, status, errorThrown ) {
		}).always(function( xhr, status ) {
		});
	});

	$(document).on('click','.btnViewPage',function(e){
		e.preventDefault();
		var _url = $(this).data('url');
		if(_url != ""){
			var win = window.open(_url,'_blank');
			if(win){
				//Browser has allowed it to be opened
				win.focus();
			}else{
				//Browser has blocked it
				alert('Please allow popups for this website');
			}
		}else{
			alert('something went wrong!');
		}
	});

	$(document).on('click','.btnPageEdit',function(e){
		e.preventDefault();
		var _url = $(this).data('url');
		var _mtitle = $(this).data('mtitle');
		var _plang = $(this).data('lang');
		var changePage = 1;
		$.ajax({
			url: _url,
			data: {	mtitle:_mtitle,plang:_plang,changePage:changePage},
			type: "POST",
			dataType : "json",
		}).done(function( resp ) {
			if(resp.status == 1){
				var win = window.open(resp.rUrl,'_blank');
				if(win){
					//Browser has allowed it to be opened
					win.focus();
				}else{
					//Browser has blocked it
					alert('Please allow popups for this website');
				}
			}else{
				alert('something went wrong!');
			}
		}).fail(function( xhr, status, errorThrown ) {
		}).always(function( xhr, status ) {
		});
	});
});
</script>