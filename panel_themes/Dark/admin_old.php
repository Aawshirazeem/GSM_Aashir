<?php


if(isset($_GET['__action']) && $_GET['__action'] == 'tab-switch'){
	if($_GET['__value'] == 'true') $tval = 1;
	else $tval = 0;
	$sql = 'update '.ADMIN_MASTER.'  SET is_tabbed = "'.$tval.'" where id=' . $admin->getUserId();
    $qrydata = $mysql->query($sql) or die(mysql_error());
    echo 1; exit;
}


$ReqType = '';
if(isset($_GET['_ajax']) && $_GET['_ajax'] == true) $ReqType = 'AJAX';
$strMsg = $request->getStr('msg');
$reply = $request->getStr('reply');
if ($page != 'index') {
    if (!$admin->isLogedIn()) {
        header('location:' . CONFIG_PATH_SITE_ADMIN . '#?reply=' . urlencode('reply_session_expired'));
    }
}


$strMsg = $request->getStr('msg');
$reply = $request->getStr('reply');
if ($page != 'index') {
    if (!$admin->isLogedIn()) {
        header('location:' . CONFIG_PATH_SITE_ADMIN . '#?reply=' . urlencode('reply_session_expired'));
    }
}

/* $langCode = array();
  $sql_lang = 'select lang_code from ' . LANGUAGE_DETAILS;
  $query_lang = $mysql->query($sql_lang);
  if($mysql->rowCount($query_lang)>0)
  {
  $rows = $mysql->fetchArray($query_lang);
  $i =1;
  foreach($rows as $row)
  {
  $langCode[$i++] = $row['lang_code'];
  }
  }
  $i=0;
  foreach($data['lang'] as $code => $values)
  {
  if (!in_array($code, $langCode))
  {
  $sql_langAdd = 'insert into ' . LANGUAGE_DETAILS . '
  (lang_id,lang_code, caption_en)
  values (1,' . $mysql->quote($code) . ', ' . $mysql->quote($values) . ')';
  $mysql->query($sql_langAdd);
  echo "*>>" . $code . '<<';
  }
  } */


/* Count number of credit request */
$sql_cr = 'select count(id) as totalRequests from ' . INVOICE_REQUEST . ' tm where status=0';
$query_cr = $mysql->query($sql_cr);
$rows_cr = $mysql->fetchArray($query_cr);
$crRequest = $rows_cr[0]['totalRequests'];
/* Get list of credit request */
$crRequestArr = array();
if ($crRequest > 0) {
    $sql_cr = 'select im.*,um.username, cm.prefix, gm.gateway
					from ' . INVOICE_REQUEST . ' im
					left join ' . USER_MASTER . ' um on (im.user_id = um.id)
					left join ' . CURRENCY_MASTER . ' cm on (im.currency_id = cm.id)
					left join ' . GATEWAY_MASTER . ' gm on (im.gateway_id = gm.id)
					where im.status=0
				order by im.id DESC';
    $query_cr = $mysql->query($sql_cr);
    $crRequestArr = $mysql->fetchArray($query_cr);
}


$sql_ticket = 'select count(ticket_id) as totalPending from ' . TICKET_MASTER . ' tm where status=1 and (select user_admin from ' . TICKET_DETAILS . ' td where tm.ticket_id=td.ticket_id order by id DESC limit 1 ) = 0';
$query_ticket = $mysql->query($sql_ticket);
$rows_ticket = $mysql->fetchArray($query_ticket);
$tickets = $rows_ticket[0]['totalPending'];

// get online and offline users list
//$adminid=$admin->getUserId();
$sql_onoffuser = 'select a.id,a.username,a.`status`,a.online,a.img,(select count(b.a_id) countms from ' . Chat_Box . ' b where b.isview=0 and b.entry_type="admin" and b.user_id=a.id and b.admin_id=' . $admin->getUserId() . ') msgcount,last_active_time from ' . USER_MASTER . ' a where a.`status`=1 order by msgcount desc';
//echo $sql_onoffuser;
$userlistt = $mysql->getResult($sql_onoffuser);
//$userlistt = $mysql->fetchArray($query_userss);
// get notification yes no
//$sql_guestmsg = 'select a.id,a.username,a.`status`,a.online,a.img,(select count(b.a_id) countms from ' . Chat_Box . ' b where b.isview=0 and b.entry_type="admin" and b.user_id=a.id and b.admin_id=' . $admin->getUserId() . ') msgcount,last_active_time from ' . USER_MASTER . ' a where a.`status`=1';
//echo $sql_onoffuser;
$sql_guestmsg = "select count(a.a_id) msgcount from nxt_chat_pool_new a where a.admin_id=" . $admin->getUserId() . " and a.entry_type='admin' and a.isview=0";
//echo $sql_guestmsg;exit;
$query_gmsg = $mysql->query($sql_guestmsg);
$rows_gmsg = $mysql->fetchArray($query_gmsg);
$totalunreadguestmsgs = $rows_gmsg[0]['msgcount'];


//$guestmsgcount = $mysql->getResult($sql_guestmsg);

/* Count number of new registration */
$sql_reg = 'select count(id) as total from ' . USER_REGISTER_MASTER;
$query_reg = $mysql->query($sql_reg);
$rows_reg = $mysql->fetchArray($query_reg);
$newReg = $rows_reg[0]['total'];
/* Get list of new registration */
$newRegArr = array();
if ($newReg > 0) {
    $sql_reg = $sql = 'select id, username from ' . USER_REGISTER_MASTER . ' limit 5';
    $query_reg = $mysql->query($sql_reg);
    $newRegArr = $mysql->fetchArray($query_reg);
}

//$emailObj = new email();
$email_config = $objEmail->getEmailSettings();

if($ReqType != 'AJAX'){ /* AJAX CHK */
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Admin panel: <?php $lang->prints('doc_title'); ?></title>
<meta name="description" content="Marino, Admin theme, Dashboard theme, AngularJS Theme">
<meta name="viewport" content="width=device-width,initial-scale=1">
<!--<link rel="shortcut icon" href="favicon.ico">-->
<link rel="shortcut icon" href="<?php echo CONFIG_PATH_SITE; ?>favicon.ico">
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
<script>
var multi_tab = false;
<?PHP 
$multi_tab = false;
if($admin->getUserId() && $admin->getUserId() > 0){
 $sql = 'select a.is_tabbed,a.notify from ' . ADMIN_MASTER . ' a where a.id=' . $admin->getUserId();
        ;
        //$result = $mysql->getResult($sql);
        $qrydata = $mysql->query($sql);
        if ($mysql->rowCount($qrydata) > 0) {
            $rows = $mysql->fetchArray($qrydata);
            $adminchatstatus = $rows[0]['is_tabbed'];
			if($adminchatstatus == 1){
				 echo 'multi_tab = true';
				$multi_tab = true;
			}
        }

}
?>
</script>
<!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
<!--[if lt IE 9]>
  <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>js/html5shiv.js"></script>
  <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>js/respond.min.js"></script>
<![endif]-->


</head>

<body data-layout="empty-layout" data-palette="palette-0" data-direction="none">
<?php
}  /* AJAX CHK */
if (defined("debug")) {
    //echo '<div class="text_11" style="background-color:#FF0000;padding:2px;color:#FFFFFF;text-align:center;"><b>!!Warning!!</b> Please disable code debug mode !!</div>';
}
if (CONFIG_REPAIR_MODE == '1') {
    //echo '<a href="' . CONFIG_PATH_SITE_ADMIN . 'config_settings.html" class="text_11 animateMe" style="display:inline-block; width:100%;background-color:#FF0000;padding:2px;color:#FFFFFF;text-align:center;"><b>!!Warning!!</b> SITE IS RUNNING UNDERCONSTRUCTION MODE !!</a>';
}
if (defined("DEMO")) {
    //echo '<div class="text_11" style="background-color:#FFFF00;padding:2px;color:#444400;text-align:center;"><b>DEMO</b> mode enabled</div>';
}

if(isset($data['lang'][$reply])){
	echo '<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">' . CONFIG_SITE_NAME . '</h4>
					</div>
					<div class="modal-body">' . $data['lang'][$reply] . '</div>
					<div class="modal-footer">
						<button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
					</div>
				</div>
			</div>
		</div>
		<script>
			jQuery(document).ready(function($) {
				$("#myModal").modal();
			});
		</script>';
}
if($admin->isLogedIn()){
	if($request->GetInt("showInFrm") == 0){
?>

<?PHP if($ReqType != 'AJAX'){ /* AJAX CHK */ ?>
<nav class="navbar navbar-fixed-top navbar-1 clearfix">
	<div class="col-md-8">
    	<ul class="nav navbar-nav pull-left toggle-layout">
            <li class="nav-item">
                <a class="nav-link" data-click="toggle-layout">
                    <i class="zmdi zmdi-menu"></i>
                </a>
            </li>
        </ul>
        
        <?PHP if($multi_tab){ ?>
        <div class="multi-tabs-outer">
        	<div class="scroller scroller-left"><i class="zmdi zmdi-chevron-left"></i></div>
            <div class="scroller scroller-right"><i class="zmdi zmdi-chevron-right"></i></div>
            <div class="multi-tabs">
            	<div class="tab active" data-url="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html">
                	<div class="tab-box"></div>
                    <div class="tab-info"><span class="tab-title">Dashboard</span></div>
                </div>
            </div>
        </div>
        <?PHP } ?>
    </div>
    <div class="col-md-4">
    <!--<a class="navbar-brand logo" href="index.html"><?php echo CONFIG_SITE_NAME ?></a>-->
    
    
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
                                <i class="view-dashboard md-icon pull-left"></i> <span>Dashboards</span>
                            </a>
                            <ul class="list-unstyled">
                                <li>
                                    <a href="dashboards-analytics.html"> <span>Analytics</span> </a>
                                </li>
                                <li>
                                    <a href="dashboards-e-commerce.html"> <span>e-Commerce</span> </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div>
                    <ul class="list-unstyled">
                        <li>
                            <a class="title"> <i class="labels md-icon pull-left"></i> <span>UI</span> </a>
                            <ul class="list-unstyled">
                                <li>
                                    <a href="ui-elements-timers-and-counters.html"> <span>Timers and counters</span> </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="list-unstyled">
                        <li> <a class="title"> <i class="flash md-icon pull-left"></i> <span>Icons</span> </a>
                            <ul class="list-unstyled">
                                <li> <a href="icons-font-awesome.html"> <span>Font Awesome</span> </a> </li>
                                <li> <a href="icons-ionicons.html"> <span>Ionicons</span> </a> </li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="list-unstyled">
                        <li> <a class="title"> <i class="folder-outline md-icon pull-left"></i> <span>Forms</span> </a>
                            <ul class="list-unstyled">
                                <li> <a href="forms-basic.html"> <span>Basic forms</span> </a> </li>
                                <li> <a href="forms-sample.html"> <span>Sample forms</span> </a> </li>
                                <li> <a href="forms-validation.html"> <span>Form Validation</span> </a> </li>
                            </ul>
                        </li>
                    </ul>
                </div>
         	</div>
        </li>
    </ul>
   <!--<ul class="nav navbar-nav pull-left toggle-search">
    	<li class="nav-item">
        	<a class="nav-link" data-click="toggle-search"> <i class="zmdi zmdi-search"></i> </a>
        </li>
    </ul>-->
    <ul class="nav navbar-nav pull-right hidden-lg-down navbar-toggle-theme-selector">
    	<li class="nav-item">
        	<a class="nav-link" data-click="toggle-theme-selector">
            	<i class="zmdi zmdi-settings"></i>
            </a>
        </li>
    </ul>
    <ul class="nav navbar-nav pull-right hidden-lg-down navbar-notifications">
	<?php if($newReg > 0){ ?>
    	<li class="nav-item dropdown dropdown-menu-right">
            <a class="nav-link dropdown-toggle no-after" data-toggle="dropdown">
            	<i class="zmdi zmdi-accounts"></i>
                <span class="label label-rounded label-danger label-xs"><?php echo $newReg; ?></span>
            </a>
            <div class="dropdown-menu dropdown-menu-scale from-right dropdown-menu-right btnCustomWidth">
            	<a class="dropdown-item">
                	<span class="label label-default pull-right">New <?php echo $newReg; ?></span>Pending User(s)
                </a>
                <?php
				if($newReg > 0){
					foreach($newRegArr as $user){
						echo '<a class="dropdown-item" href="' . CONFIG_PATH_SITE_ADMIN . 'users_add.html?id=' . $user['id'] . '"><i class="zmdi zmdi-account"></i>  ' . $user['username'] . '</a>';
					}
				}
				?>
                <a class="dropdown-item" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_register.html">
                	<i class="zmdi zmdi-accounts-list"></i>  Pending User List
                </a>
            </div>
        </li>
	<?php } ?>
    </ul>
    <ul class="nav navbar-nav pull-right hidden-lg-down navbar-notifications">
	<?php if($crRequest > 0){ ?>
    	<li class="nav-item dropdown dropdown-menu-right">
        	<a class="nav-link dropdown-toggle no-after" data-toggle="dropdown">
                <i class="zmdi zmdi-money"></i>
                <span class="label label-rounded label-danger label-xs"><?php echo $crRequest; ?></span>
            </a>
            <div class="dropdown-menu dropdown-menu-scale from-right dropdown-menu-right btnCustomWidth">
            	<a class="dropdown-item">
                	<span class="label label-default pull-right">New <?php echo $crRequest; ?></span>Pending Credit(s)
                </a>
                <?php
				if($crRequest > 0){
					foreach($crRequestArr as $crReq){
						echo '<a class="dropdown-item" href="' . CONFIG_PATH_SITE_ADMIN . 'users_credit_request.html">
								<i class="zmdi zmdi-account"></i>  
								<span class="subject">
									<span class="from">' . $crReq['username'] . '</span>
									<span class="time">' . $crReq['prefix'] . ' ' . $crReq['credits'] . '</span>
								</span>
								<span class="message">' . date("d-M Y H:i", strtotime($crReq['date_time'])) . '</span></a>';
					}
				}
				?>
            </div>
        </li>
	<?php } ?>
    </ul>
    <ul class="nav navbar-nav pull-right hidden-lg-down navbar-notifications">
    	<li class="nav-item">
        	<a class="nav-link" data-click="toggle-right-sidebar">
            	<i class="zmdi zmdi-comments"></i>
            </a>
        </li>
    </ul>
    <ul class="nav navbar-nav pull-right hidden-lg-down navbar-profile">
    	<li class="nav-item dropdown">
        	<a class="nav-link dropdown-toggle no-after" data-toggle="dropdown">
            	<img class="img-circle img-fluid profile-image" src="<?php echo CONFIG_PATH_PANEL; ?>assets/images/users/avatar-2.jpg">
            </a>
            <div class="dropdown-menu dropdown-menu-scale from-right dropdown-menu-right btnCustomWidth">
            	<a class="dropdown-item animated fadeIn" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>ticket.html">
                	<?php echo ($tickets > 0) ? '<span class="label label-danger pull-right mail-info">' . $tickets . '</span>' : ''; ?><i class="fa fa-life-ring"></i>  <?php $lang->prints('navi_support_ticket'); ?>
                </a>
                <a class="dropdown-item animated fadeIn" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>password_change.html">
                	<i class="fa fa-user-secret"></i>  <?php $lang->prints('navi_change_password'); ?>
                </a>
                <a class="dropdown-item animated fadeIn" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>two_step_verify.html">
                	<i class="fa fa-hand-peace-o"></i><?php $lang->prints('navi_two_step'); ?>
                </a>
                <a class="dropdown-item animated fadeIn tab0" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>log_out.do">
                	<i class="fa fa-power-off"></i><?php $lang->prints('navi_logout'); ?>
                </a>
            </div>
        </li>
    </ul>
    <ul class="nav navbar-nav pull-right toggle-fullscreen-mode">
    	<li class="nav-item">
        	<a class="nav-link" data-click="toggle-fullscreen-mode">
            	<i class="zmdi zmdi-fullscreen"></i>
            </a>
        </li>
    </ul>
    
    </div>
</nav>
<div class="theme-selector">
	<div class="clearfix">
	<label>Select theme</label>
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
    <?php /*?><div class="clearfix">
    <label>Enable Multi tabs feature</label>
    
    <div class="animated-switch"> <input id="switch-success" name="switch-success" <?PHP if($multi_tab){ echo 'checked';} ?> type="checkbox" class="change_tab_setting"> <label for="switch-success" class="label-success chkadj"></label> </div>
	</div><?php */?>
</div>

<div class="container-fluid">
	<div class="row">
    	<div class="sidebar-placeholder"> </div>
        
        <!--left sidebar-->
        <div class="sidebar-outer-wrapper">
        	<div class="sidebar-inner-wrapper">
            	<div class="sidebar-1">
                	<div class="profile">
                        <?php /*?><h2 class="m-t-20 text-center"> <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"> <img class="img-fluid profile-image" src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets_1/logo_gsm.png" style="width:200px;"> </a></h2><?php */?>
                        <h2 class="m-t-20 text-center"> <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"> IMEI PK </a></h2>
                        <div class="navbar-drawer">
                            <form class="form-inline navbar-form searchsize">
                                <input class="form-control m-l-5" id="name" name="name" type="text" placeholder="Search"  onkeyup="return handle(event)" autocomplete="off">
                            </form>
                        </div>
                        <div class="search-result seach-resut-box" style="display:none;"></div>
                    </div>
                    
                    <div class="sidebar-nav">
                        <div class="sidebar-section">
                            <div class="section-title">Navigation</div>
                            <?php /*?><div class="newSearch">
                            	<form class="navbar-left app-search pull-left hidden-xs" role="search">
    <input type="text" id="name" name="name" class="form-control" onkeypress="return handle(event)" placeholder="Search...">
   
    <!--                                        <a href="javascript:makeAjaxRequest()"  onclick="makeAjaxRequest(event)"><i class="fa fa-search"></i></a>
                                        -->
    
  </form>
                            </div><?php */?>
                            <ul class="l1 list-unstyled section-content">
                                <li>
                                    <a class="sideline" data-id="dashboards" data-click="toggle-section">
                                        <i class="pull-right fa fa-caret-down fa-rotate-180 icon-dashboards"></i>
                                        <i class="zmdi zmdi-view-dashboard md-icon pull-left"></i>
                                        <span class="title"><?php $lang->prints('lbl_Dashboard'); ?></span>
                                    </a>
                                    <ul class="list-unstyled section-dashboards l2 <?php $admin->naviMain('dashboard', 'active', '0', $page); ?>">
                                        <li>
                                        	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_register.html">
												<?php echo ($newReg > 0) ? '<span class="label label-danger pull-right mail-info">' . $newReg . '</span>' : ''; ?>
                                                <span class="title"><?php $lang->prints('navi_new_req'); ?></span>
                                            </a>
                                        </li>
                                        <li>
                                        	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_credit_request.html">
												<?php echo ($crRequest > 0) ? '<span class="label label-danger pull-right mail-info">' . $crRequest . '</span>' : ''; ?>
												<span class="title"><?php $lang->prints('navi_credit_req'); ?></span>
                                            </a>
                                        </li>
                                        <li>
                                        	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_credit_invoices.html?status=0">
												<span class="title"><?php $lang->prints('unpaid_invoices'); ?></span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                            <ul class="l1 list-unstyled section-content">
                                <li>
                                    <a class="sideline" data-id="client" data-click="toggle-section">
                                        <i class="pull-right fa fa-caret-down icon-client"></i>
                                        <i class="zmdi zmdi-accounts-outline md-icon pull-left"></i>
                                        <span class="title"><?php $lang->prints('lbl_navi_client_supp'); ?></span></span>
                                    </a>
                                    <ul class="list-unstyled section-client l2 <?php $admin->naviMain('Clients/Suppliers', 'active', '0', $page); ?>">
                                        <li>
                                        	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_add.html"><span class="title"> +<?php $lang->prints('lbl_navi_add_client'); ?> </span></a>          
                                        </li>
                                        
                                        <li>
                                        	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users.html"><span class="title"> <?php $lang->prints('lbl_navi_view_client'); ?> </span></a>
                                        </li>
                                        
                                        <li>
                                        	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>suppliers_add.html"><span class="title"> +<?php $lang->prints('lbl_navi_add_supp'); ?> </span></a>
                                        </li>
                                        
                                        <li>
                                        	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>suppliers.html"><span class="title"> <?php $lang->prints('lbl_navi_view_supp'); ?> </span></a>
                                        </li>
                                        
                                        <li>
                                        	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>package.html"><span class="title"> <?php $lang->prints('navi_special_Groups'); ?> </span></a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                            <ul class="l1 list-unstyled section-content">
                                <li>
                                    <a class="sideline" data-id="product" data-click="toggle-section">
                                        <i class="pull-right fa fa-caret-down icon-product"></i>
                                        <i class="fa fa-shield md-icon pull-left"></i>
                                        <span class="title"><?php $lang->prints('navi_prod_serv'); ?></span>
                                    </a>
                                    <ul class="list-unstyled section-product l2 <?php $admin->naviMain('Products/Service', 'active', '0', $page); ?>">
                                        <li>
                                        	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_tools.html" > <span class="title"><?php $lang->prints('navi_imei_services'); ?></span> </a>
                                        </li>
                                        <li>
                                        	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_file.html"> <span class="title"><?php $lang->prints('navi_file_services'); ?></span> </a>
                                        </li>
                                        <li>
                                        	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>server_logs.html"> <span class="title"><?php $lang->prints('navi_server_services'); ?></span> </a>
                                        </li>
                                        <li>
                                        	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>prepaid_logs.html"> <span class="title"><?php $lang->prints('navi_prepaid_services'); ?></span> </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                            <ul class="l1 list-unstyled section-content">
                                <li>
                                    <a class="sideline" data-id="order" data-click="toggle-section">
                                        <i class="pull-right fa fa-caret-down icon-order"></i>
                                        <i class="zmdi zmdi-shopping-cart md-icon pull-left"></i>
                                        <span class="title"><?php $lang->prints('navi_orders'); ?></span>
                                    </a>
                                    <ul class="list-unstyled section-order l2 <?php $admin->naviMain('order', 'active', '0', $page); ?>">
                                    	<li>
                                        	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_imei.html?type=pending"> <span class="title"><?php $lang->prints('navi_imei_orders'); ?></span> </a>
                                        </li>                                        
                                        <li>
                                        	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>imei_bulk_reply.html"> <span class="title"><?php $lang->prints('navi_IMEI_orders_bulk_reply'); ?></span> </a>
                                        </li>
                                        <li>
                                        	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_file.html?type=pending"> <span class="title"><?php $lang->prints('navi_file_orders'); ?></span> </a>
                                        </li>
                                        <li>
                                        	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_server_log.html?type=pending"> <span class="title"><?php $lang->prints('navi_server_orders'); ?></span> </a>
                                        </li>
                                        <li>
                                        	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_prepaid_log.html?type=pending"> <span class="title"><?php $lang->prints('navi_prepaid_orders'); ?></span> </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                            <ul class="l1 list-unstyled section-content">
                                <li>
                                    <a class="sideline" data-id="cms" data-click="toggle-section">
                                        <i class="pull-right fa fa-caret-down icon-cms"></i>
                                        <i class="fa fa-files-o md-icon pull-left"></i>
                                        <span class="title"><?php $lang->prints('navi_cms'); ?></span>
                                    </a>
                                    <ul class="list-unstyled section-cms l2 <?php $admin->naviMain('cms', 'active', '0', $page); ?>">
                                    	<li>
                                        	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>config_settings.html"> <span class="title"><?php $lang->prints('navi_admin_theme'); ?></span> </a>
                                        </li>                                        
                                        <li>
                                        	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>config_news.html"> <span class="title"><?php $lang->prints('navi_manage_news_page'); ?></span> </a>
                                        </li>                                        
                                        <li>
                                        	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>email_template_list.html"> <span class="title"><?php $lang->prints('navi_manage_email'); ?></span> </a>
                                        </li>
                                        <li>
                                        	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>invoice_edit.html"> <span class="title"><?php $lang->prints('navi_invoice_edit'); ?></span> </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                            <ul class="l1 list-unstyled section-content">
                                <li>
                                    <a class="sideline" data-id="reports" data-click="toggle-section">
                                        <i class="pull-right fa fa-caret-down icon-reports"></i>
                                        <i class="fa fa-bar-chart-o md-icon pull-left"></i>
                                        <span class="title"><?php $lang->prints('navi_reports_graphs'); ?></span>
                                    </a>
                                    <ul class="list-unstyled section-reports l2 <?php $admin->naviMain('report', 'active', '0', $page); ?>">
                                    	<li>
                                        	<a class="sideline" data-id="order-report" data-click="toggle-section">
                                            	<i class="pull-right fa fa-caret-down icon-multi-level-two"></i> 
                                                <span><?php $lang->prints('navi_orders_reports'); ?></span>
                                            </a>
                                            
                                            <ul class="l3 list-unstyled section-order-report">
                                            	<li>
                                                	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_order_summary_imei.html">
                                                    	<span><?php $lang->prints('navi_anual'); ?></span>
                                                    </a>
                                                </li>
                                                <li>
                                                	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_order_imei_profit.html">
                                                    	<span><?php $lang->prints('navi_order_imei_profit'); ?></span>
                                                    </a>
                                                </li>
                                                <li>
                                                	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_balance_unpaid.html">
                                                    	<span><?php $lang->prints('navi_balance_unpaid'); ?></span>
                                                    </a>
                                                </li>
                                                <li>
                                                	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_order_imei_daywise.html">
                                                    	<span><?php $lang->prints('navi_Daywise'); ?></span>
                                                    </a>
                                                </li>
                                                <li>
                                                	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_order_userwise.html">
                                                    	<span><?php $lang->prints('navi_Userwise'); ?></span>
                                                    </a>
                                                </li>
                                                <li>
                                                	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_orders_users.html">
                                                    	<span><?php $lang->prints('navi_Users_Orders_all'); ?></span>
                                                    </a>
                                                </li>
                                                <li>
                                                	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_order_imei.html">
                                                    	<span><?php $lang->prints('navi_iMEI'); ?></span>
                                                    </a>
                                                </li>
                                                <li>
                                                	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_order_file.html">
                                                    	<span><?php $lang->prints('navi_file'); ?></span>
                                                    </a>
                                                </li>
                                                <li>
                                                	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_order_server_log.html">
                                                    	<span><?php $lang->prints('navi_server_log'); ?></span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li>
                                        	<a class="sideline" data-id="logs" data-click="toggle-section">
                                            	<i class="pull-right fa fa-caret-down icon-multi-level-two"></i> 
                                                <span><?php $lang->prints('navi_log_reports'); ?></span>
                                            </a>
                                            
                                            <ul class="l3 list-unstyled section-logs">
                                            	<li>
                                                	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_admin_login_log.html">
                                                    	<span><?php $lang->prints('navi_admin_login'); ?></span>
                                                    </a>
                                                </li>
                                                <li>
                                                	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_user_login_log.html">
                                                    	<span><?php $lang->prints('navi_user_login'); ?></span>
                                                    </a>
                                                </li>
                                                <li>
                                                	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_transections.html">
                                                    	<span><?php $lang->prints('navi_transaction'); ?></span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                            <ul class="l1 list-unstyled section-content">
                                <li>
                                    <a class="sideline" data-id="utilities" data-click="toggle-section">
                                        <i class="pull-right fa fa-caret-down icon-utilities"></i>
                                        <i class="fa fa-wrench md-icon pull-left"></i>
                                        <span class="title"><?php $lang->prints('navi_utilities'); ?></span>
                                    </a>
                                    <ul class="list-unstyled section-utilities l2 <?php $admin->naviMain('utilities', 'active', '0', $page); ?>">
                                    	<li>
                                        	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>download_update.html"> <span class="title"><?php $lang->prints('navi_chk_update'); ?></span> </a>
                                        </li>
                                        <li>
                                        	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>email_user_list.html"> <span class="title"><?php $lang->prints('navi_mass_Email'); ?></span> </a>
                                        </li>
                                        <li>
                                        	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>utl_db_backup.html"> <span class="title"><?php $lang->prints('navi_database_Backup'); ?></span> </a>
                                        </li>
                                        <li>
                                        	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_reset_password.html"> <span class="title"><?php $lang->prints('navi_reset_all_password'); ?></span> </a>
                                        </li>
                                        <li>
                                        	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>utl_system_cleanup.html"> <span class="title"><?php $lang->prints('navi_system_Cleanup'); ?></span> </a>
                                        </li>
                                        <li>
                                        	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>utl_logs_cleanup.html"> <span class="title"><?php $lang->prints('navi_logs_Cleanup'); ?></span> </a>
                                        </li>
                                        <li>
                                        	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_brands.html"> <span class="title"><?php $lang->prints('navi_brand_master'); ?></span> </a>
                                        </li>
                                        <li>
                                        	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_file_white_list.html"> <span class="title"><?php $lang->prints('navi_Files_Extensions'); ?></span> </a>
                                        </li>                                       
                                    </ul>
                                </li>
                            </ul>
                            <ul class="l1 list-unstyled section-content">
                                <li>
                                    <a class="sideline" data-id="support" data-click="toggle-section">
                                        <i class="pull-right fa fa-caret-down icon-support"></i>
                                        <i class="fa fa-support md-icon pull-left"></i>
                                        <span class="title"><?php $lang->prints('navi_support'); ?></span>
                                    </a>
                                    <ul class="list-unstyled section-support l2 <?php $admin->naviMain('Support', 'active', '0', $page); ?>">
                                    	<li>
                                        	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>chat_panel.html"> <span class="title"><?php $lang->prints('navi_p_chat'); ?></span> </a>
                                        </li>                                        
                                        <li>
                                        	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>chat_panel_guest.html"> <span class="title"><?php $lang->prints('navi_g_chat'); ?></span> </a>
                                        </li>
                                        <li>
                                        	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>ticket.html"> <span class="title"><?php $lang->prints('navi_tickets'); ?></span> </a>
                                        </li>                                         
                                    </ul>
                                </li>
                            </ul>
                            <ul class="l1 list-unstyled section-content">
                                <li>
                                    <a class="sideline" data-id="settings" data-click="toggle-section">
                                        <i class="pull-right fa fa-caret-down icon-settings"></i>
                                        <i class="fa fa-cog md-icon pull-left"></i>
                                        <span class="title"><?php $lang->prints('navi_Settings'); ?></span>
                                    </a>
                                    <ul class="list-unstyled section-settings l2 <?php $admin->naviMain('Settings', 'active', '0', $page); ?>">
                                    	<li>
                                        	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>config_settings_2.html"> <span class="title"><?php $lang->prints('navi_general_setting'); ?></span> </a>
                                        </li>
                                        <li>
                                        	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>smtp_settings.html"> <span class="title"><?php $lang->prints('navi_email_setting'); ?></span> </a>
                                        </li>
                                        <li>
                                        	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_countries.html"> <span class="title"><?php $lang->prints('navi_countries'); ?></span> </a>
                                        </li>
                                        <li>
                                        	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>currency.html"> <span class="title"><?php $lang->prints('navi_currencies'); ?></span> </a>
                                        </li>
                                        <li>
                                        	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>settings_gateway.html"> <span class="title"><?php $lang->prints('navi_payment_gateways'); ?></span> </a>
                                        </li>
                                        <li>
                                        	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>api_list.html"> <span class="title"><?php $lang->prints('navi_api_setting'); ?></span> </a>
                                        </li>
                                        <li>
                                        	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>admin.html"> <span class="title"><?php $lang->prints('navi_sys_admin'); ?></span> </a>
                                        </li>
                                        <li>
                                        	<a class="sideline" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>web_maintinance.html"> <span class="title"><?php $lang->prints('navi_web_main'); ?></span> </a>
                                        </li>                               
                                    </ul>
                                </li>
                            </ul>
                            <ul class="l1 list-unstyled section-content">
                                <li>
                                    <a class="sideline" data-id="help" data-click="toggle-section">
                                        <i class="pull-right fa fa-caret-down icon-help"></i>
                                        <i class="fa fa-question-circle md-icon pull-left"></i>
                                        <span class="title"><?php $lang->prints('navi_help'); ?></span>
                                    </a>
                                    <ul class="list-unstyled section-help l2">
                                    	<li>
                                        	<a class="sideline" href="http://gsmunion.net/" target="_blank"> <span class="title">Support GsmUnion </span> </a>
                                        </li>                          
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
           	</div>
        </div>
        
        <?php
		
		
        // get the online status
        // $sql = "select a.id,a.username,a.online from " . ADMIN_MASTER . " a where a.`status`=1";
        $sql = 'select a.online,a.notify from ' . ADMIN_MASTER . ' a where a.id=' . $admin->getUserId();
        ;
        //$result = $mysql->getResult($sql);
        $qrydata = $mysql->query($sql);
        if ($mysql->rowCount($qrydata) > 0) {
            $rows = $mysql->fetchArray($qrydata);
            $adminchatstatus = $rows[0]['online'];
            $adminnotify = $rows[0]['notify'];

            if ($adminchatstatus == 1)
                $adminchatstatus = 'checked';
            else
                $adminchatstatus = '';

            if ($adminnotify == 1)
                $adminnotify = 'checked';
            else
                $adminnotify = '';
        }
        ?>
        
        <!--Right Sidebar-->
        <div class="right-sidebar-outer">
        	<div class="right-sidebar-inner">
        		<div class="right-sidebar">
                	<div class="bs-nav-tabs nav-tabs-warning justified">
                    	<ul class="nav nav-tabs nav-animated-border-from-center">
                        	<li class="nav-item">
                            	
                                	
                                    
                                    <span class="lt0" data-toggle="tooltip" data-placement="Right" title="Click To On/Off Notofications">
                                    
                                    <div class="animated-switch"> <input  type="checkbox" id="notification" <?php echo $adminnotify; ?> data-plugin="switchery" data-color="#A0D269" data-size="small" onchange="setStatus(2);"/><label for="notification" class="label-success"></label></div>
                                    
                                    
                                    </span>
                                       
                                   <span class="rt0" data-toggle="tooltip" data-placement="bottom" title="Click To On/Off Chat">
                                    
                                    	<div class="animated-switch">
                                            <input id="onlinestatus" name="switch-success" type="checkbox" <?php echo $adminchatstatus; ?> onchange="setStatus(1);">
                                            <label for="onlinestatus" class="label-success"></label>
                                        </div>
                                        </span>
                                        <a class="nav-link active" data-toggle="tab" data-target="#rtab-center">
                                    Users
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                        	<div class="tab-pane fade in active" id="rtab-center">
                            	<input type="text" id="search" placeholder="Search Contact" class="form-control m-t-10 m-b-10">
                            	<!--<h5>Online</h5>-->
                                <div class="user-widget-8">
                                	<div class="row">
                                    	<div class="col-xs-12 bs-media">
                                        	<?php
											$current_time = time();
											if($userlistt['COUNT']){
												$totalunreadmsgs = 0;
												foreach($userlistt['RESULT'] as $row){
													//$enocdeis = urlencode($row['id']);
													$totalunreadmsgs = $totalunreadmsgs + $row["msgcount"];
													$timestamp = strtotime($row["last_active_time"]);
													$latest = $current_time - $timestamp;
													$latest = $latest / 60;
											?>
                                            <div class="media" onclick="window.open('chat.html?id=<?php echo $row['id']; ?>');">
                                            	<a class="media-left">
                                                    <?php
                                                    if($row['online'] == 1 && $latest <= 15){
														//echo '<i class="fa fa-circle icon color-success"></i>';
													}else{
														//echo '<i class="fa fa-circle icon color-danger"></i>';
													}
													?>
                                                    <?php
                                                    if($row['img'] != ''){
														if($row['online'] == 1 && $latest <= 15){
													?>
                                                    	<img class="media-object img-circle h-40 w-40 chat-online" alt="User" src="<?php echo CONFIG_PATH_SITE; ?>images/<?php echo $row['img']; ?>">
                                                    <?php
														}else{
													?>
                                                    	<img class="media-object img-circle h-40 w-40 chat-offline" alt="User" src="<?php echo CONFIG_PATH_SITE; ?>images/<?php echo $row['img']; ?>">
                                                    <?php
														}
													}else{
														if($row['online'] == 1 && $latest <= 15){
													?>
                                                    	<img class="media-object img-circle h-40 w-40 chat-online" src="<?php echo CONFIG_PATH_PANEL; ?>assets/images/users/avatar-2.jpg" alt="User">
                                                    <?php
                                                    	}else{
													?>
                                                    	<img class="media-object img-circle h-40 w-40 chat-offline" src="<?php echo CONFIG_PATH_PANEL; ?>assets/images/users/avatar-2.jpg" alt="User">
                                                    <?php
														}
													}													
													?>
                                                </a>
                                                <div class="media-body v-a-m">
                                                	<h5 class="media-heading">
                                                    	 <?php echo $row['username']; ?> 
                                                    	<span class="fa fa-envelope pull-right">  <?php echo $row['msgcount']; ?></span>
                                                    </h5>
                                                </div>
                                            </div>
                                            <?php
												}
											}
											?>
                      					</div>
                                    </div>
                                </div>                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?PHP } ?>
        <div class="col-xs-12 main" id="main">
        	<div class="container">
            <div class="multiTabContent">
            <div class="multiTabCont active" data-url="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html">
            
			<?php
            	if(file_exists(CONFIG_PATH_ADMIN_ABSOLUTE . $page . '.php')){
					include(CONFIG_PATH_ADMIN_ABSOLUTE . $page . '.php');
				}else{
					echo '<br /><br /><h1 class="text-danger text-center">Error:404 Page Not Found!</h1><br /><br /><br /><br /><br />';
				}
			?>
            </div>
            
            </div>
        	<div class="footer">
            	<div class="row">
                	<div class="col-xs-12">
                    	<!--Copyright © 2016 GSM UNION. All Rights Reserved .-->
                    </div>
                </div>
            </div>
        </div>
        <?PHP if($ReqType == 'AJAX'){ exit; /* AJAX CHK */ } ?>
        
    </div>
</div>
<p>
  <?php
}else{
	if(file_exists(CONFIG_PATH_ADMIN_ABSOLUTE . $page . '.php')){
?>
  <?php include(CONFIG_PATH_ADMIN_ABSOLUTE . $page . '.php'); ?><?php } else {
		echo '<br /><br /><h1 class="text-danger text-center">Error:404 Page Not Found!</h1><br /><br /><br /><br /><br />'; }
	}
}else{
	if(file_exists(CONFIG_PATH_ADMIN_ABSOLUTE . $page . '.php')){
?>
  <?php include(CONFIG_PATH_ADMIN_ABSOLUTE . $page . '.php'); ?>
  <?php
}else{
	echo '<br /><br /><h1 class="text-danger text-center">Error:404 Page Not Found!</h1><br /><br /><br /><br /><br />';
	}
}
?>
  
  <script>
	var resizefunc = [];
</script>
  
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

<?php /*?><script type="text/javascript" src="<?php echo CONFIG_PATH_ASSETS; ?>ckeditor/ckeditor.js"></script><?php */?>
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
  <?php
if($page != 'index'){
?>
	
  	<script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets_1/scripts/dashboards.js"></script>
  	<script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets_1/scripts/index.js"></script>
    

  <?PHP }else{
?> 
  
<script src="<?php echo CONFIG_PATH_PANEL; ?>assets_1/scripts/components/floating-labels.js"></script>
<script src="<?php echo CONFIG_PATH_PANEL; ?>assets_1/scripts/pages-login.js"></script>  
    <?PHP } ?>
  <script type="text/javascript">
	<?php /*?>jQuery(document).ready(function ($) {
		setPathsAdmin('<?php echo CONFIG_PATH_SITE_ADMIN ?>');
		var totalmsg =<?php echo $totalunreadmsgs; ?>;
		var totalmsguest =<?php echo $totalunreadguestmsgs; ?>;
		// alert(totalmsguest);
		var noti = $('#notification').prop('checked');
		var pagename = location.pathname.substring(location.pathname.lastIndexOf("/") + 1);
		if (totalmsg != 0 && pagename != 'chat_panel.html' && pagename != 'chat.html' && noti == true)
			$.Notification.notify('custom', 'top right', 'Chat Notification', 'You got some New Messages');
			if (totalmsguest != 0 && pagename != 'chat_panel_guest.html' && noti == true)
				$.Notification.notify('warning', 'top right', 'Chat Notification', 'You got some New Messages From Guest');
				// alert("Checkbox state (method 1) = " + $('#onlinestatus').prop('checked'));
				//alert("Checkbox state (method 2) = " + $('#onlinestatus').is(':checked'));
				$('.counter').counterUp({
					delay: 100,
					time: 1200
				});
				$(".knob").knob();
			});<?php */?>
</script>
  
  <script>
function setStatus(a){
	//  alert(a);
	
	//alert(config_path_site_admin)
	if (a == 1){
		var onlinestatus = $('#onlinestatus').prop('checked');
		
		var adminid =<?php echo $admin->getUserId(); ?>;
		// ajax call
		$.ajax({
			type: "POST",
			url:'<?php echo CONFIG_PATH_SITE_ADMIN; ?>_set_online_status.do',
			data: "&a_id=" + adminid + "&ostat=" + onlinestatus + "&type=" + a,
			error: function () {
				// alert("Some Error Occur");
			},success: function (msg) {
				
				// you are now offline
				//$.Notification.notify('custom','top right','Got A MESSAGE', 'New MSG')
				// alert(msg);
				if (msg == 1)
					 $.notify('success', 'right middle', 'Chat Status', 'Online')
				
				if (msg == 0)
					$.notify('error', 'right middle', 'Chat Status', 'Offline')
			}
		});
	}else{
		var onlinestatus = $('#notification').prop('checked');
		//alert(onlinestatus);
		var adminid =<?php echo $admin->getUserId(); ?>;
		// ajax call 
		$.ajax({
			type: "POST",
			url: '<?php echo CONFIG_PATH_SITE_ADMIN; ?>_set_online_status.do',
			data: "&a_id=" + adminid + "&ostat=" + onlinestatus + "&type=" + a,
			error: function () {
				//   alert("Some Error Occur");
			},success: function (msg) {
				// you are now offline
				//$.Notification.notify('custom','top right','Got A MESSAGE', 'New MSG')
				// alert(msg);
				if (msg == 1)
					$.notify('Notification Status<br>ON','success')
				if (msg == 0)
					$.notify('Notification Status<br>OFF', 'error')
			}
		});
	}
}
</script>

<script type="text/javascript">
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
</script>
  
  <script type="text/javascript">
// $('.btnSearch').click(function(){
	// code goes here !
	
	$(document).on('click','.btnRemoveSearch',function(){
		$('.search-result').css("display", "none")
	});
	var _handle_ajax;
	function handle(e) {
		//if (e.keyCode == 13) {
			try { _handle_ajax.abort}catch(e){}
			
			var search_val = $('#name').val();
			var invoice = $('#invoice').is(":checked");
			console.log(search_val);
			//alert(search_val);
			if(search_val == ''){
				$('.search-result').css("display", "none");
				
				//return false;
			}
			else{
				_handle_ajax = $.ajax({
					url: '<?php echo CONFIG_PATH_ADMIN ?>search.php?val=' + search_val + '&inv=' + invoice,
					type: 'POST',
					data: {name: search_val},
					success: function (response) {
						$('.search-result').css("display", "block");
						$('.search-result').html(response);
					}
				});
			}
			//return false;
		//}
	}
	
	function makeAjaxRequest(e) {
		if (e.keyCode == 13) {
		}
	}
//});
</script>
</p>
	</div>
    <script type="text/javascript">
function popUp(url) {
	w = window.open(url, '_blank', 'width=800,height=800,menubar=no');
}
    </script>
</body>
</html>
