<?php
$strMsg = $request->getStr('msg');
$reply = $request->getStr('reply');

$langCode = array();
$sql_lang = 'select lang_code from ' . LANGUAGE_DETAILS;
$query_lang = $mysql->query($sql_lang);
if ($mysql->rowCount($query_lang) > 0) {
    $rows = $mysql->fetchArray($query_lang);
    $i = 1;
    foreach ($rows as $row) {
        $langCode[$i++] = trim($row['lang_code']);
    }
}
//print_r($langCode);
//print_r($data);
//exit();
/* $i=0;
  foreach($data['lang'] as $code => $values)
  {
  if (!in_array($code, $langCode))
  {
  $sql_langAdd = 'insert into ' . LANGUAGE_DETAILS . '
  (lang_id,lang_code, caption_en)
  values (1,' . $mysql->quote(trim($code)) . ', ' . $mysql->quote($values) . ')';
  @$mysql->query($sql_langAdd);
  echo "*>>" . $code . '<<';
  }
  } */
?>
<!doctype html>
<html lang="us">
    <head>
    <meta charset="utf-8">
    <title>Supplier Panel</title>
    <link rel="icon" type="image/ico" href="<?php echo CONFIG_PATH_SITE; ?>favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
    <meta name="author" content="Coderthemes">

    <!--[if IE]>
    	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]--><!-- global stylesheets -->
    <link rel="stylesheet" href="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets_1/styles/components/bootstrap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,300italic,400italic,500,500italic,700,700italic,900,900italic" rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/1.0.0/css/flag-icon.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets_1/styles/main.css">
    <link rel="stylesheet" href="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets_1/styles/custom.css">
    <link rel="stylesheet" href="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/plugins/sweetalert/dist/sweetalert.css">
    <link rel="stylesheet" href="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets_1/bower_components/chartist/dist/chartist.min.css">
    <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/js/modernizr.min.js"></script>
    <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/plugins/sweetalert/dist/sweetalert.min.js"></script>
    <?php /*?><script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/jquery.min.js"></script><?php */?>
    <script src="<?php echo CONFIG_PATH_PANEL; ?>assets_1/bower_components/jquery/dist/jquery.js"></script>
    <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/js/init.js" ></script>
    <script>
		var _base_url = '<?PHP echo CONFIG_PATH_SITE_ADMIN; ?>';
    </script>
    <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets_1/custom.js" ></script>
    <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/js/init_nxt_admin.js" ></script>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
    	<script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>js/html5shiv.js"></script>
        <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>js/respond.min.js"></script>
    <![endif]-->
    </head>

    <body data-layout="horizontal-navigation-1" data-palette="palette-0" data-direction="none">
<?php
    	if(isset($data['lang'][$reply])){
			echo '<div id="messagebox" title="Note!">' . $data['lang'][$reply] . '</div>';
		}
		
		if($supplier->isLogedIn()){
			$sql_auth = 'select service_imei, service_file, service_logs from ' . SUPPLIER_MASTER . ' where id=' . $supplier->getUserId();
			$query_auth = $mysql->query($sql_auth);
			$rows_auth = $mysql->fetchArray($query_auth);
			$service_imei = $rows_auth[0]['service_imei'];
			$service_file = $rows_auth[0]['service_file'];
			$service_logs = $rows_auth[0]['service_logs'];
			//  if ($supplier->isLogedIn()) {
	?>
    <nav class="navbar navbar-fixed-top navbar-1">
    	<a href="index.html" class="navbar-brand">
        	<span><?php echo CONFIG_SITE_NAME; ?></span>
        </a>
        <ul class="nav navbar-nav pull-left toggle-layout">
        	<li class="nav-item">
            	<a class="nav-link" data-click="toggle-layout">
                	<i class="zmdi zmdi-menu"></i>
                </a>
            </li>
        </ul>
        <ul class="nav navbar-nav pull-left toggle-fullscreen-mode">
        	<li class="nav-item">
            	<a class="nav-link" data-click="toggle-fullscreen-mode">
                	<i class="zmdi zmdi-fullscreen"></i>
                </a>
           	</li>
        </ul>
        <ul class="nav navbar-nav pull-left navbar-dropdown">
        	<li class="nav-item dropdown mega-dropdown">
            	<a class="nav-link dropdown-toggle no-after" data-toggle="dropdown">
                	<i class="fa fa-plus"></i>
                    <span class="nav-link-text">Navigation</span>
                </a>
                <div class="dropdown-menu mega-menu-1 dropdown-menu-scale from-left">
                	<div>
                    	<ul class="list-unstyled">
                        	<li>
                            	<a class="title">
                                	<i class="view-dashboard md-icon pull-left"></i>
                                    <span><?php echo $admin->wordTrans($admin->getUserLang(),'Dashboard'); ?></span>
                                </a>
                            </li>
                        </ul>
                        <ul class="list-unstyled">
                        	<li>
                            	<a class="title">
                                	<i class="star-circle md-icon pull-left"></i>
                                    <span><?php echo $admin->wordTrans($admin->getUserLang(),'Services'); ?></span>
                                </a>
                                <ul class="list-unstyled">
                                	<?php if ($service_imei == "1") { ?>
                                    <li>
                                    	<a href="<?php echo CONFIG_PATH_SITE_SUPPLIER; ?>order_imei.html?type=pending">
                                        	<span><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('navi_imei_orders')); ?></span>
                                        </a>
                                    </li>
                                    <?php } ?>
                                    <?php if ($service_file == "1") { ?>
                                    <li>
                                    	<a href="<?php echo CONFIG_PATH_SITE_SUPPLIER; ?>order_file.html?type=pending">
                                        	<span><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('navi_file_orders')); ?></span>
                                        </a>
                                    </li>
                                    <?php } ?>
                                    <?php if ($service_logs == "1") { ?>
                                    <li>
                                    	<a href="<?php echo CONFIG_PATH_SITE_SUPPLIER; ?>order_server_log.html?type=pending">
                                        	<span><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('navi_server_logs_orders')); ?></span>
                                        </a>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </li>
                        </ul>
                        <ul class="list-unstyled">
                        	<li>
                            	<a class="title">
                                	<i class="star-circle md-icon pull-left"></i>
                                    <span><?php echo $admin->wordTrans($admin->getUserLang(),'Funds'); ?></span>
                                </a>
                                <ul class="list-unstyled">
                                    <li>
                                    	<?php echo $admin->wordTrans($admin->getUserLang(),$lang->get('menu_Payment'));?>
                                    </li>
                                    <li>
                                    	<a href="<?php echo CONFIG_PATH_SITE_SUPPLIER; ?>payment_details.html">
                                        	<span><?php echo $admin->wordTrans($admin->getUserLang(),$lang->get('menu_details')); ?></span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                <div>
            </div>
        <div>
            </div>
      </div>
      		</li>
        </ul>
        <ul class="nav navbar-nav pull-right hidden-lg-down navbar-toggle-theme-selector">
        	<li class="nav-item">
            	<a class="nav-link" data-click="toggle-theme-selector">
                	<i class="zmdi zmdi-settings"></i>
                </a>
            </li>
        </ul>
        <ul class="nav navbar-nav pull-right hidden-lg-down navbar-profile">
        	<li class="nav-item dropdown">
            	<a class="nav-link dropdown-toggle no-after" data-toggle="dropdown">
                	<img class="img-circle img-fluid profile-image" src="<?php echo CONFIG_PATH_PANEL; ?>assets/images/users/avatar-2.jpg" alt="user-img">
                </a>
                <div class="dropdown-menu dropdown-menu-scale from-right dropdown-menu-right">
                    <a class="dropdown-item animated fadeIn" href="<?php echo CONFIG_PATH_SITE_SUPPLIER; ?>log_out.do">
                    	<i class="zmdi zmdi-power"></i>
                        <span class="dropdown-text"><?php echo $admin->wordTrans($admin->getUserLang(),'Log Out'); ?></span>
                    </a>
                </div>
            </li>
        </ul>
        <?php
		$sqlForLanguage = 'select * from ' . LANG_MASTER . ' where lang_status = 1' ;
		$query = $mysql->query($sqlForLanguage);
		if($mysql->rowCount($query) > 0){
			$languageList = $mysql->fetchArray($query);
		?>
		<ul class="nav navbar-nav pull-right hidden-lg-down navbar-flags">
			<li class="nav-item dropdown dropdown-menu-right">
				<?php
				if($admin->getUserLang() != ""){
					//$fIcon = ($admin->getUserLang() == 'en' ? 'us' : $admin->getUserLang());
					$fIcon = $admin->getUserLang();
					$sql = 'select * from '.LANG_MASTER.' where language_code = "'.$fIcon.'"';
					$query = $mysql->query($sql);
					$flagData = $mysql->fetchArray($query);
					$rowCount = $mysql->rowCount($query);
					if($rowCount > 0){
						$lCurrentFlag = CONFIG_PATH_PANEL_ADMIN.'assets_1/language_flag/'.$flagData[0]['language_flag'];
					}else{
						$lCurrentFlag = CONFIG_PATH_PANEL_ADMIN.'assets_1/language_flag/default-flag.jpg';
					}
				}else{
					$lCurrentFlag = CONFIG_PATH_PANEL_ADMIN.'assets_1/language_flag/en.jpg';
					if(!file_exists($lCurrentFlag)){
						$lCurrentFlag = CONFIG_PATH_PANEL_ADMIN.'assets_1/language_flag/default-flag.jpg';
					}
				}
				?>
				<a class="nav-link dropdown-toggle no-after" data-toggle="dropdown" aria-expanded="false">
					<?php /*?><span class="flag flag-icon flag-icon-<?php echo $fIcon; ?>"></span><?php */?>
					<span class="flag flag-icon" style="background-image:url(<?php echo $lCurrentFlag; ?>);"></span>
				</a>
				<div class="dropdown-menu dropdown-menu-scale from-right dropdown-menu-right">
					<?php
					foreach($languageList as $language){
						$setFlagAct = '';
						if($admin->getUserLang() == $language['language_code']){
							$setFlagAct = 'setFlagAct';
						}
					?>
						<a class="dropdown-item setLangFlag <?php echo $setFlagAct; ?>" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>languages_edit_process.do?setLang=<?php echo $language['language_code']; ?>">
							<?php /*?><span class="flag flag-icon flag-icon-<?php echo ($language['language_code'] == 'en' ? 'us' : $language['language_code']); ?>"></span><?php */?>
							<span class="flag flag-icon" style="background-image:url(<?php echo CONFIG_PATH_PANEL_ADMIN.'assets_1/language_flag/'.$language['language_flag']; ?>);"></span>
							<span class="title"><?php echo $language['language']; ?></span>
						</a>
					<?php
					}
					?>
					<!--<a class="dropdown-item">
						<span class="flag flag-icon flag-icon-gb"></span>
						<span class="title">English</span>
					</a>
					<a class="dropdown-item">
						<span class="flag flag-icon flag-icon-de"></span>
						<span class="title">German</span>
					</a>
					<a class="dropdown-item">
						<span class="flag flag-icon flag-icon-es"></span>
						<span class="title">Spanish</span>
					</a>
					<a class="dropdown-item">
						<span class="flag flag-icon flag-icon-fr"></span>
						<span class="title">French</span>
					</a>-->
				</div>
			</li>
		</ul>
		<?php
		}
		?>
    </nav>
    <div class="theme-selector">
        <p><?php echo $admin->wordTrans($admin->getUserLang(),'Select theme'); ?></p>
        <div class="select-palette">
            <div class="color-palette" data-click="set-palette" data-id="palette-1">
                <span class="color-block" style="background: #3e3e3e"></span>
                <span class="color-block" style="background: #242424"></span>
                <span class="color-block" style="background: #5cb85c"></span>
            </div>
            <div class="color-palette" data-click="set-palette" data-id="palette-2">
                <span class="color-block" style="background: #3c414f"></span>
                <span class="color-block" style="background: #262932"></span>
                <span class="color-block" style="background: #5cb85c"></span>
            </div>
            <div class="color-palette" data-click="set-palette" data-id="palette-3">
                <span class="color-block" style="background: #1a1a1a"></span>
                <span class="color-block" style="background: #000000"></span>
                <span class="color-block" style="background: #5cb85c"></span>
            </div>
            <div class="color-palette" data-click="set-palette" data-id="palette-4">
                <span class="color-block" style="background: #1a1a1a"></span>
                <span class="color-block" style="background: #ffffff"></span>
                <span class="color-block" style="background: #d9534f"></span>
            </div>
            <div class="color-palette" data-click="set-palette" data-id="palette-5">
                <span class="color-block" style="background: #3c414f"></span>
                <span class="color-block" style="background: #ffffff"></span>
                <span class="color-block" style="background: #d9534f"></span>
            </div>
            <div class="color-palette" data-click="set-palette" data-id="palette-6">
                <span class="color-block" style="background: #3e3e3e"></span>
                <span class="color-block" style="background: #ffffff"></span>
                <span class="color-block" style="background: #d9534f"></span>
            </div>
            <div class="color-palette" data-click="set-palette" data-id="palette-7">
                <span class="color-block" style="background: #0283f1"></span>
                <span class="color-block" style="background: #0267bf"></span>
                <span class="color-block" style="background: #f0ad4e"></span>
            </div>
            <div class="color-palette" data-click="set-palette" data-id="palette-8">
                <span class="color-block" style="background: #0275d8"></span>
                <span class="color-block" style="background: #025aa5"></span>
                <span class="color-block" style="background: #f0ad4e"></span>
            </div>
            <div class="color-palette" data-click="set-palette" data-id="palette-9">
                <span class="color-block" style="background: #0267bf"></span>
                <span class="color-block" style="background: #014c8c"></span>
                <span class="color-block" style="background: #f0ad4e"></span>
            </div>
            <div class="color-palette" data-click="set-palette" data-id="palette-10">
                <span class="color-block" style="background: #190f8a"></span>
                <span class="color-block" style="background: #110a5c"></span>
                <span class="color-block" style="background: #f0ad4e"></span>
            </div>
            <div class="color-palette" data-click="set-palette" data-id="palette-11">
                <span class="color-block" style="background: #2415bf"></span>
                <span class="color-block" style="background: #1b1091"></span>
                <span class="color-block" style="background: #f0ad4e"></span>
            </div>
            <div class="color-palette" data-click="set-palette" data-id="palette-12">
                <span class="color-block" style="background: #3929e7"></span>
                <span class="color-block" style="background: #2516c7"></span>
                <span class="color-block" style="background: #f0ad4e"></span>
            </div>
            <div class="color-palette" data-click="set-palette" data-id="palette-13">
                <span class="color-block" style="background: #4b0f8a"></span>
                <span class="color-block" style="background: #320a5c"></span>
                <span class="color-block" style="background: #f0ad4e"></span>
            </div>
            <div class="color-palette" data-click="set-palette" data-id="palette-14">
                <span class="color-block" style="background: #6815bf"></span>
                <span class="color-block" style="background: #4f1091"></span>
                <span class="color-block" style="background: #f0ad4e"></span>
            </div>
            <div class="color-palette" data-click="set-palette" data-id="palette-15">
                <span class="color-block" style="background: #8529e7"></span>
                <span class="color-block" style="background: #6c16c7"></span>
                <span class="color-block" style="background: #f0ad4e"></span>
            </div>
            <div class="color-palette" data-click="set-palette" data-id="palette-16">
                <span class="color-block" style="background: #bf156b"></span>
                <span class="color-block" style="background: #911051"></span>
                <span class="color-block" style="background: #f0ad4e"></span>
            </div>
            <div class="color-palette" data-click="set-palette" data-id="palette-17">
                <span class="color-block" style="background: #e72989"></span>
                <span class="color-block" style="background: #c7166f"></span>
                <span class="color-block" style="background: #f0ad4e"></span>
            </div>
            <div class="color-palette" data-click="set-palette" data-id="palette-18">
                <span class="color-block" style="background: #bf1e15"></span>
                <span class="color-block" style="background: #911710"></span>
                <span class="color-block" style="background: #f0ad4e"></span>
            </div>
            <div class="color-palette" data-click="set-palette" data-id="palette-19">
                <span class="color-block" style="background: #e73229"></span>
                <span class="color-block" style="background: #c71f16"></span>
                <span class="color-block" style="background: #f0ad4e"></span>
            </div>
            <div class="color-palette" data-click="set-palette" data-id="palette-20">
                <span class="color-block" style="background: #0f8a20"></span>
                <span class="color-block" style="background: #0a5c15"></span>
                <span class="color-block" style="background: #f0ad4e"></span>
            </div>
            <div class="color-palette" data-click="set-palette" data-id="palette-21">
                <span class="color-block" style="background: #5d8a0f"></span>
                <span class="color-block" style="background: #3e5c0a"></span>
                <span class="color-block" style="background: #f0ad4e"></span>
            </div>
            <div class="color-palette" data-click="set-palette" data-id="palette-22">
                <span class="color-block" style="background: #734226"></span>
                <span class="color-block" style="background: #4d2c19"></span>
                <span class="color-block" style="background: #f0ad4e"></span>
            </div>
            <div class="color-palette" data-click="set-palette" data-id="palette-23">
                <span class="color-block" style="background: #9f5c35"></span>
                <span class="color-block" style="background: #794628"></span>
                <span class="color-block" style="background: #f0ad4e"></span>
            </div>
        </div>
    </div>
    <div class="horizontal-navigation horizontal-navigation-1">
    	<div class="container-fluid">
        	<div class="row">
            	<div class="col-xs-12">
                	<div class="p-t-10 p-b-20">                        
                        <ul class="list-unstyled navigation animation-delay">
                        	<li class="horizontal-navigation-dropdown wow fadeIn" style="visibility: visible;">
                            	<a href="index.html" class="btn btn-primary"> <?php echo $admin->wordTrans($admin->getUserLang(),'Dashboard'); ?> </a>
                            </li>
                            <li class="horizontal-navigation-dropdown wow fadeIn" style="visibility: visible;">
                            	<a class="btn btn-primary"> <?php echo $admin->wordTrans($admin->getUserLang(),'Services'); ?> </a>
                                <div class="horizontal-dropdown-menu" ng-class="{'horizontal-dropdown-menu-lg': item.items.length > 10}">
                                	<?php if($service_imei == "1"){ ?>
                                    <a class="btn btn-primary sideline" href="<?php echo CONFIG_PATH_SITE_SUPPLIER; ?>order_imei.html?type=pending"> <?php echo $admin->wordTrans($admin->getUserLang(),'Imei Orders'); ?> </a>
                                    <?php } ?>
                                    <?php if($service_file == "1"){ ?>
                                    <a class="btn btn-primary sideline" href="<?php echo CONFIG_PATH_SITE_SUPPLIER; ?>order_file.html?type=pending"> <?php echo $admin->wordTrans($admin->getUserLang(),'File Orders'); ?> </a>
                                    <?php } ?>
                                    <?php if($service_logs == "1"){ ?>
                                    <a class="btn btn-primary sideline" href="<?php echo CONFIG_PATH_SITE_SUPPLIER; ?>order_server_log.html?type=pending"> <?php echo $admin->wordTrans($admin->getUserLang(),'Server Logs Orders'); ?> </a>
                                    <?php } ?>
                                </div>
                            </li>
                            <li class="horizontal-navigation-dropdown wow fadeIn" style="visibility: visible;">
                            	<a class="btn btn-primary"> <?php echo $admin->wordTrans($admin->getUserLang(),'Funds');?> </a>
                                <div class="horizontal-dropdown-menu" ng-class="{'horizontal-dropdown-menu-lg': item.items.length > 10}">
                                	<a class="btn btn-primary sideline" href="#"> <?php echo $admin->wordTrans($admin->getUserLang(),'Payment'); ?> </a>
                                    <a class="btn btn-primary sideline" href="<?php echo CONFIG_PATH_SITE_SUPPLIER; ?>payment_details.html"> <?php echo $admin->wordTrans($admin->getUserLang(),'Details'); ?> </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="wrapper">
    	<!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="container">
		<?php
        	if(isset($data['lang'][$reply])){
				echo '<div class="alert alert-success">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $data['lang'][$reply] . '</div>';
					//echo '<div id="messagebox" title="Note!">' . $data['lang'][$reply] . '</div>';
			}
			if(file_exists(CONFIG_PATH_SUPPLIER_ABSOLUTE . $page . '.php')){
				include(CONFIG_PATH_SUPPLIER_ABSOLUTE . $page . '.php');
			}else{
				echo '<br /><br /><h1 class="text-danger text-center">'.$admin->wordTrans($admin->getUserLang(),'Error:404 Page Not Found!').'</h1><br /><br /><br /><br /><br />';
			}
		?>
            <div class="footer">
                <div class="row">
                    <div class="col-xs-6 text-left">
                        <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_copyright')); ?><?php echo CONFIG_SITE_TITLE; ?>.<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_all_rights_reserved')); ?>.
                    </div>
                    <div class="col-xs-6">
                        <ul class="pull-right list-inline m-b-0">
                            <li> <a href="#"><?php echo $admin->wordTrans($admin->getUserLang(),'About'); ?></a> </li>
                            <li> <a href="#"><?php echo $admin->wordTrans($admin->getUserLang(),'Help'); ?></a> </li>
                            <li> <a href="#"><?php echo $admin->wordTrans($admin->getUserLang(),'Contact'); ?></a> </li>
                        </ul>
                    </div>
                </div>
            </div>
    		<!--footer-->
        </div>
    </div>
	<?php } else { ?>
    <?php include(CONFIG_PATH_SUPPLIER_ABSOLUTE . $page . '.php'); ?>
    <?php } ?>
    
    <!-- ============================================================== --> 
    <!-- End Right content here --> 
    <!-- ============================================================== --> 
    

    <!-- jQuery  --> 
    <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets_1/bower_components/tether/dist/js/tether.js"></script> 
    <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets_1/bower_components/bootstrap/dist/js/bootstrap.js"></script> 
    <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets_1/bower_components/PACE/pace.js"></script> 
    <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets_1/bower_components/notifyjs/dist/notify.js"></script> 
    <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets_1/bower_components/notifications/notify-metro.js"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.0.0/lodash.min.js"></script> 
    <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets_1/scripts/components/jquery-fullscreen/jquery.fullscreen-min.js"></script> 
    <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets_1/bower_components/jquery-storage-api/jquery.storageapi.min.js"></script> 
    <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets_1/bower_components/wow/dist/wow.min.js"></script> 
    <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets_1/scripts/functions.js"></script> 
    <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets_1/scripts/colors.js"></script> 
    <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets_1/scripts/left-sidebar.js"></script> 
    <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets_1/scripts/navbar.js"></script> 
    <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets_1/scripts/horizontal-navigation-1.js"></script> 
    <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets_1/scripts/horizontal-navigation-2.js"></script> 
    <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets_1/scripts/horizontal-navigation-3.js"></script> 
    <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets_1/scripts/main.js"></script> 
    <script type="text/javascript" src="<?php echo CONFIG_PATH_PANEL; ?>js/init_nxt_admin.js"></script> 
    <script type="text/javascript" src="<?php echo CONFIG_PATH_ASSETS; ?>ckeditor/ckeditor.js"></script> 
    <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/plugins/raphael/raphael-min.js" type="text/javascript"></script> 
    <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/plugins/morris/morris.min.js" type="text/javascript"></script> 
    <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets_1/scripts/components/jquery.toolbar.js" type="text/javascript"></script>
    <?php /*?></script>
              
              <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets_1/bower_components/notifyjs/dist/notify.js"></script>
                <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets_1/bower_components/chartist/dist/chartist.min.js"></script>
              <script src="https://cdnjs.cloudflare.com/ajax/libs/easy-pie-chart/2.1.6/jquery.easypiechart.min.js"></script><?php */?>
    <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets_1/bower_components/d3/d3.js"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.2.1/Chart.min.js"></script> 
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/topojson/1.6.9/topojson.min.js"></script>--> 
    <!--<script src="http://bower.batchthemes.com/bower_components/datamaps/dist/datamaps.all.js"></script>-->
    <?php if($page != 'index'){ ?>
    <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets_1/scripts/dashboards.js"></script> 
    <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets_1/scripts/index.js"></script>
    <?PHP }else{ ?>
    <script src="<?php echo CONFIG_PATH_PANEL; ?>assets_1/scripts/components/floating-labels.js"></script> 
    <script src="<?php echo CONFIG_PATH_PANEL; ?>assets_1/scripts/pages-login.js"></script>
    <?PHP } ?>
    <script>
	$(document).ready(function(e) {
		$(document).on('click','.setLangFlag',function(e){
			e.preventDefault();
			var _url = $(this).attr('href');
			
			$.ajax({
				url: _url,
				data: {	},
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
	});
	</script>
</body>
</html>