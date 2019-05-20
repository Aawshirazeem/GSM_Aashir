<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$admin = new admin();
	$mysql = new mysql();	

	$pagesOpts = '';
	$sql= 'select * from ' . CMS_PAGE_MASTER ;
	$query = $mysql->query($sql);
	$strReturn = "";
	$i = 1;
	if($mysql->rowCount($query) > 0){
		$pageRows = $mysql->fetchArray($query);
		foreach($pageRows as $row){
			$pagesOpts .= '<option value="'.$row['id'].'">'.$row['title'].'</option>';
		}
	}
?>


<div class="row m-b-20">
  <div class="col-xs-12">
    <ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">
      <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html">
        <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?>
        </a></li>
      <li class="slideInDown wow animated">CMS</li>
      <li class="slideInDown wow animated active">
        <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_CMS_Menu')); ?>
      </li>
    </ol>
  </div>
</div>
<div class="m-t-10">
  <h4 class="m-b-20">
    <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Manage_CMS_Menu')); ?>
    <button class="btn btn-danger btn-sm pull-right btnSaveMenu"><?php echo $admin->wordTrans($admin->getUserLang(),'Save menu'); ?></button>
    <!-- <a href="#" class="btn btn-danger btn-sm pull-right btnSaveMenu"> Save menu </a>--> </h4>
  <hr>
  <div class="sortable">
  <div class="ajaxLoader"><img src="/panel_themes/Dark/assets/images/ajaxLoader.gif"/ ></div>
    <?php
  
	$sql= 'select * from ' . CMS_MENU_MASTER ;
	$query = $mysql->query($sql);
	if($mysql->rowCount($query) > 0){
		$rows = $mysql->fetchArray($query);
		foreach($rows as $row){ 
			$menu = ($row['json']);
			$menuArray = json_decode($menu,true);
			foreach($menuArray as $menu){
				
				?>
                 <div class="group-caption">
                  <div class="menu-parent">
                    <h4><span class="menu-title"><?php echo $menu['label'] ?></span>
                      <div class="remove"><i class="fa fa-trash" aria-hidden="true"></i></div>
                      <div class="show-opts"><i class="fa fa-angle-down" aria-hidden="true"></i></div>
                      <div class="move"><i class="fa fa-arrows" aria-hidden="true"></i></div>
                      <div class="menu-opts-drop">
                        <div class="form-group">
                          <label><?php echo $admin->wordTrans($admin->getUserLang(),'Navigation Label'); ?></label>
                          <input type="text" class="form-control label-val" value="<?php echo $menu['label'] ?>" placeholder="Label for menu">
                        </div>
                        <div class="form-group">
                          <label><?php echo $admin->wordTrans($admin->getUserLang(),'CMS Page'); ?></label>
                          <select class="page-val form-control">
                            <option value=""><?php echo $admin->wordTrans($admin->getUserLang(),'Select page'); ?></option>
                            <?php
                            foreach($pageRows as $rowin){
								echo '<option '.($rowin['id'] == $menu['page'] ? 'selected' : '').' value="'.$rowin['id'].'">'.$rowin['title'].'</option>';
							}
                            ?>
                          </select>
                        </div>
                        <div class="form-group">
                          <label><?php echo $admin->wordTrans($admin->getUserLang(),'Custom Url'); ?> <small><?php echo $admin->wordTrans($admin->getUserLang(),'( optional if cms page is not selected )'); ?></small></label>
                          <input type="text" value="<?php echo $menu['url'] ?>" class="form-control url-val"  placeholder="Custom url">
                        </div>
                        <div class="form-group">
                          <button class="btn btn-primary saveChanges"><?php echo $admin->wordTrans($admin->getUserLang(),'Save changes'); ?></button>
                          <button class="btn btn-danger cls-tp-menu"><?php echo $admin->wordTrans($admin->getUserLang(),'Cancel'); ?></button>
                        </div>
                      </div>
                    </h4>
                  </div>
                  <div class="group-items">
                  <?PHP 
				  foreach($menu['childs'] as $ch){ ?>
				  <div class="group-item"><span class="menu-title"><?php echo $ch['label']; ?></span>
                  <div class="remove"><i class="fa fa-trash" aria-hidden="true"></i></div>
                  <div class="show-opts"><i class="fa fa-angle-down" aria-hidden="true"></i></div>
                  <div class="move"><i class="fa fa-arrows" aria-hidden="true"></i></div>
                  <div class="menu-opts-drop">
                    <div class="form-group">
                      <label><?php echo $admin->wordTrans($admin->getUserLang(),'Navigation Label'); ?></label>
                      <input type="text" class="form-control label-val" placeholder="Label for menu" value="<?php echo $ch['label']; ?>">
                    </div>
                    <div class="form-group">
                      <label><?php echo $admin->wordTrans($admin->getUserLang(),'CMS Page'); ?></label>
                      <select class="page-val form-control">
                        <option value=""><?php echo $admin->wordTrans($admin->getUserLang(),'Select page'); ?></option>
                         <?php
                            foreach($pageRows as $rowin){
								echo '<option '.($rowin['id'] == $ch['page'] ? 'selected' : '').' value="'.$rowin['id'].'">'.$rowin['title'].'</option>';
							}
                            ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <label><?php echo $admin->wordTrans($admin->getUserLang(),'Custom Url'); ?> <small><?php echo $admin->wordTrans($admin->getUserLang(),'( optional if cms page is not selected )'); ?></small></label>
                      <input type="text" class="form-control url-val"  placeholder="Custom Url"  value="<?php echo $ch['url']; ?>">
                    </div>
                    <div class="form-group">
                      <button class="btn btn-primary saveChanges"><?php echo $admin->wordTrans($admin->getUserLang(),'Save changes'); ?></button>
                      <button class="btn btn-danger cls-tp-menu"><?php echo $admin->wordTrans($admin->getUserLang(),'Cancel'); ?></button>
                    </div>
                  </div>
                </div>
				  <?php } ?>
                  
                  
                  
                  
                  </div>
                  <div class="addMnuChld">
                    <button class="btn btn-primary show-opts-new btn-sm">+ <?php echo $admin->wordTrans($admin->getUserLang(),'Add new menu'); ?></button>
                    <div class="menu-opts-drop">
                      <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),'Navigation Label'); ?></label>
                        <input type="text" class="form-control label-val" placeholder="Label for menu">
                      </div>
                      <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),'CMS Page'); ?></label>
                        <select class="page-val form-control">
                          <option><?php echo $admin->wordTrans($admin->getUserLang(),'Select page'); ?></option>
                          <?PHP echo $pagesOpts; ?>
                        </select>
                      </div>
                      <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),'Custom Url'); ?> <small><?php echo $admin->wordTrans($admin->getUserLang(),'( optional if cms page is not selected )'); ?></small></label>
                        <input type="text" class="form-control url-val"  placeholder="Custom url">
                      </div>
                      <div class="form-group">
                        <button class="btn btn-primary addMenu"><?php echo $admin->wordTrans($admin->getUserLang(),'Add menu'); ?></button>
                        <button class="btn btn-danger cls-tp-menu"><?php echo $admin->wordTrans($admin->getUserLang(),'Cancel'); ?></button>
                      </div>
                    </div>
                  </div>
                </div>
                <?PHP
			}
		?>
    
    <?PHP }
	}
  
  
  ?>
  </div>
 
  <div class="addNewTpMenu">
    <button class="btn btn-primary show-opts-new">+<?php echo $admin->wordTrans($admin->getUserLang(),' Add new menu'); ?></button>
    <div class="menu-opts-drop">
      <div class="form-group">
        <label><?php echo $admin->wordTrans($admin->getUserLang(),'Navigation Label'); ?></label>
        <input type="text" class="form-control label-val" placeholder="Label for menu">
      </div>
      <div class="form-group">
        <label><?php echo $admin->wordTrans($admin->getUserLang(),'CMS Page'); ?></label>
        <select class="page-val form-control">
          <option><?php echo $admin->wordTrans($admin->getUserLang(),'Select page'); ?></option>
          <?PHP echo $pagesOpts; ?>
        </select>
      </div>
      <div class="form-group">
        <label><?php echo $admin->wordTrans($admin->getUserLang(),'Custom Url'); ?> <small><?php echo $admin->wordTrans($admin->getUserLang(),'( optional if cms page is not selected )'); ?></small></label>
        <input type="text" class="form-control url-val"  placeholder="Custom Url">
      </div>
      <div class="form-group">
        <button class="btn btn-primary addMenu" data-parent="1"><?php echo $admin->wordTrans($admin->getUserLang(),'Add menu'); ?></button>
        <button class="btn btn-danger cls-tp-menu"><?php echo $admin->wordTrans($admin->getUserLang(),'Cancel'); ?></button>
      </div>
    </div>
  </div>
  <div class="tmpls" style="display:none">
    <div class="group-item"><span class="menu-title">{{label}}</span>
      <div class="remove"><i class="fa fa-trash" aria-hidden="true"></i></div>
      <div class="show-opts"><i class="fa fa-angle-down" aria-hidden="true"></i></div>
      <div class="move"><i class="fa fa-arrows" aria-hidden="true"></i></div>
      <div class="menu-opts-drop">
        <div class="form-group">
          <label><?php echo $admin->wordTrans($admin->getUserLang(),'Navigation Label'); ?></label>
          <input type="text" class="form-control label-val" placeholder="Label for menu" value="{{label}}">
        </div>
        <div class="form-group">
          <label><?php echo $admin->wordTrans($admin->getUserLang(),'CMS Page'); ?></label>
          <select class="page-val form-control">
            <option value=""><?php echo $admin->wordTrans($admin->getUserLang(),'Select page'); ?></option>
            <?php echo $pagesOpts; ?>
          </select>
        </div>
        <div class="form-group">
          <label><?php echo $admin->wordTrans($admin->getUserLang(),'Custom Url'); ?> <small><?php echo $admin->wordTrans($admin->getUserLang(),'( optional if cms page is not selected )'); ?></small></label>
          <input type="text" class="form-control url-val"  placeholder="Custom Url"  value="{{url}}">
        </div>
        <div class="form-group">
          <button class="btn btn-primary saveChanges"><?php echo $admin->wordTrans($admin->getUserLang(),'Save changes'); ?></button>
          <button class="btn btn-danger cls-tp-menu"><?php echo $admin->wordTrans($admin->getUserLang(),'Cancel'); ?></button>
        </div>
      </div>
    </div>
    <div class="group-caption">
      <div class="menu-parent">
        <h4><span class="menu-title">{{label}}</span>
          <div class="remove"><i class="fa fa-trash" aria-hidden="true"></i></div>
          <div class="show-opts"><i class="fa fa-angle-down" aria-hidden="true"></i></div>
          <div class="move"><i class="fa fa-arrows" aria-hidden="true"></i></div>
          <div class="menu-opts-drop">
            <div class="form-group">
              <label><?php echo $admin->wordTrans($admin->getUserLang(),'Navigation Label'); ?></label>
              <input type="text" class="form-control label-val" placeholder="Label for menu">
            </div>
            <div class="form-group">
              <label><?php echo $admin->wordTrans($admin->getUserLang(),'CMS Page'); ?></label>
              <select class="page-val form-control">
                <option value=""><?php echo $admin->wordTrans($admin->getUserLang(),'Select page'); ?></option>
                <?php
				echo $pagesOpts;
				?>
              </select>
            </div>
            <div class="form-group">
              <label><?php echo $admin->wordTrans($admin->getUserLang(),'Custom Url'); ?> <small><?php echo $admin->wordTrans($admin->getUserLang(),'( optional if cms page is not selected )'); ?></small></label>
              <input type="text" class="form-control url-val"  placeholder="Custom url">
            </div>
            <div class="form-group">
              <button class="btn btn-primary saveChanges"><?php echo $admin->wordTrans($admin->getUserLang(),'Save changes');?></button>
              <button class="btn btn-danger cls-tp-menu"><?php echo $admin->wordTrans($admin->getUserLang(),'Cancel'); ?></button>
            </div>
          </div>
        </h4>
      </div>
      <div class="group-items"> </div>
      <div class="addMnuChld">
        <button class="btn btn-primary show-opts-new btn-sm">+ <?php echo $admin->wordTrans($admin->getUserLang(),'Add new menu'); ?></button>
        <div class="menu-opts-drop">
          <div class="form-group">
            <label><?php echo $admin->wordTrans($admin->getUserLang(),'Navigation Label'); ?></label>
            <input type="text" class="form-control label-val" placeholder="Label for menu">
          </div>
          <div class="form-group">
            <label><?php echo $admin->wordTrans($admin->getUserLang(),'CMS Page'); ?></label>
            <select class="page-val form-control">
              <option><?php echo $admin->wordTrans($admin->getUserLang(),'Select page'); ?></option>
              <?PHP echo $pagesOpts; ?>
            </select>
          </div>
          <div class="form-group">
            <label><?php echo $admin->wordTrans($admin->getUserLang(),'Custom Url'); ?> <small><?php echo $admin->wordTrans($admin->getUserLang(),'( optional if cms page is not selected )'); ?></small></label>
            <input type="text" class="form-control url-val"  placeholder="Custom url">
          </div>
          <div class="form-group">
            <button class="btn btn-primary addMenu"><?php echo $admin->wordTrans($admin->getUserLang(),'Add menu'); ?></button>
            <button class="btn btn-danger cls-tp-menu"><?php echo $admin->wordTrans($admin->getUserLang(),'Cancel'); ?></button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
$(document).ready(function(){
    
    // Sort the parents
    $(".sortable").sortable({
        containment: "document",
        items: "> div",
        handle: ".move",
        tolerance: "pointer",
        cursor: "move",
        opacity: 0.7,
        revert: 300,
        delay: 150,
        placeholder: "movable-placeholder",
        start: function(e, ui) {
            ui.placeholder.height(ui.helper.outerHeight());
        }
    });
    
    // Sort the children
    $(".group-items").sortable({
        items: "> div",
        tolerance: "pointer",
        containment: "parent"
//        containment: "document",
//        connectWith: '.group-items'
        // Uncomment the two above lines if you want it to go outside its own parent
    });
	
	$(document).on('click','.show-opts,.show-opts-new',function(e){
		$(this).parent().toggleClass('m-open');
	});
	
	$(document).on('click','.cls-tp-menu',function(e){
		$(this).closest('.menu-opts-drop').parent().removeClass('m-open');
	});
	
	$(document).on('click','.remove',function(e){
		var _parent = $(this).closest('.group-caption');
		if($(this).closest('.group-item').length > 0){
			_parent = $(this).closest('.group-item');
		}
		if(confirm('Are you want remove this menu ?')){
			_parent.fadeOut(500,function(){
				_parent.remove();
			});
		}
		$(this).closest('.menu-opts-drop').parent().removeClass('m-open');
	});
	
	
	$(document).on('click','.saveChanges',function(e){
		
		
		var _frm = $(this).closest('.menu-opts-drop');
		var _lblVal = _frm.find('.label-val').val();
		var _PageVal = _frm.find('.page-val').val();
		var _urlVal = _frm.find('.url-val').val();
		var _valTo;
		if($(this).closest('.menu-parent').length > 0){
			_valTo = $(this).closest('.menu-parent');
		}else{
			_valTo = $(this).closest('.group-item');
		}
		
		_valTo.find('.menu-title').html(_lblVal);
		_valTo.find('.label-val').val(_lblVal);
		_valTo.find('.page-val').val(_PageVal);
		_valTo.find('.url-val').val(_urlVal);
		_frm.parent().removeClass('m-open');
		
	});
	
	$(document).on('click','.addMenu',function(e){
		var isParent = (typeof $(this).attr('data-parent') != 'undefined' && $(this).attr('data-parent') == 1 ? true : false);
		var _frm = $(this).closest('.menu-opts-drop');
		var _cloneMenu = $('.tmpls').find('.group-item').clone();
		if(isParent){
			_cloneMenu = $('.tmpls').find('.group-caption').clone();
		}
		var _lblVal = _frm.find('.label-val').val();
		var _PageVal = _frm.find('.page-val').val();
		var _urlVal = _frm.find('.url-val').val();
		var _valTo = _cloneMenu;
		if(isParent){
			_valTo = _cloneMenu.find('.menu-parent');
		}
		
		_valTo.find('.menu-title').html(_lblVal);
		_valTo.find('.label-val').val(_lblVal);
		_valTo.find('.page-val').val(_PageVal);
		_valTo.find('.url-val').val(_urlVal);
		_frm.parent().removeClass('m-open');
		_frm.find('.label-val').val('');
		_frm.find('.page-val').val('');
		_frm.find('.url-val').val('');
		if(isParent){
			_cloneMenu.appendTo($('.sortable'));
		}else{
			_frm.closest('.group-caption').find('.group-items').sortable({
				items: "> div",
				tolerance: "pointer",
				containment: "parent"
			});
			_cloneMenu.appendTo(_frm.closest('.group-caption').find('.group-items'));
		}
	});
	
	
    
})

</script> 
</div>
<script>
$(document).ready(function(e) {
    
	$(document).on('click','.btnSaveMenu',function(e){		
		e.preventDefault();	
		$(".ajaxLoader").css("display", "block");
		 $(this).attr('disabled',true);
		var _menuJson = [];
		$('.group-caption').each(function(index, element) {
            var _parent = $(this).find('.menu-parent').find('.menu-opts-drop');
			var _child = $(this).find('.group-items')
			var _lblVal = _parent.find('.label-val').val();
			if(_lblVal == '') return;
			var _PageVal = _parent.find('.page-val').val();
			var _urlVal = _parent.find('.url-val').val();
			var _childs = [];
			var _menu = {'label':_lblVal,'page':_PageVal,'url':_urlVal};
			if(_child.find('.group-item').length > 0){
				_child.find('.group-item').each(function(index, element) {
                    var _lblVal = $(this).find('.menu-opts-drop').find('.label-val').val();
					var _PageVal = $(this).find('.menu-opts-drop').find('.page-val').val();
					var _urlVal = $(this).find('.menu-opts-drop').find('.url-val').val();	
					_childs.push({'label':_lblVal,'page':_PageVal,'url':_urlVal});
                });
			}
			_menu.childs = _childs;
			_menuJson.push(_menu);
        });
		console.log(_menuJson);
		
		var _url = '<?php echo CONFIG_PATH_SITE_ADMIN; ?>cms_menu_process.do';
		$.ajax({
			url: _url,
			data: {	menu: JSON.stringify(_menuJson)},
			type: "POST",
			dataType : "json",
		}).done(function( resp ) {
			$('.btnSaveMenu').removeAttr('disabled',true);
			$(".ajaxLoader").css("display", "none");			
			console.log(resp);
			
		}).fail(function( xhr, status, errorThrown ) {
		}).always(function( xhr, status ) {
		});
	});
});
</script>