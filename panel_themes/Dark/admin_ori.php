<?php
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
?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="Mosaddek">
        <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
        <link rel="shortcut icon" href="<?php echo CONFIG_PATH_SITE; ?>favicon.ico">

        <title>Admin panel: <?php $lang->prints('doc_title'); ?></title>

        <!-- Bootstrap core CSS -->


        <link href="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/css/core.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/css/components.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/css/pages.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/css/responsive.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/plugins/morris/morris.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/plugins/sweetalert/dist/sweetalert.css" rel="stylesheet" type="text/css">
        <link href="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/plugins/switchery/dist/switchery.min.css" rel="stylesheet" />



        <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

        <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/js/modernizr.min.js"></script>

        <script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/jquery.min.js"></script>
        <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/js/init.js" ></script>
        <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/js/init_nxt_admin.js" ></script>
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
        <!--[if lt IE 9]>
          <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>js/html5shiv.js"></script>
          <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>js/respond.min.js"></script>
        <![endif]-->       
    </head>

    <body class="fixed-left">


        <div id="wrapper">
<?php
if (defined("debug")) {
    //echo '<div class="text_11" style="background-color:#FF0000;padding:2px;color:#FFFFFF;text-align:center;"><b>!!Warning!!</b> Please disable code debug mode !!</div>';
}
if (CONFIG_REPAIR_MODE == '1') {
    //echo '<a href="' . CONFIG_PATH_SITE_ADMIN . 'config_settings.html" class="text_11 animateMe" style="display:inline-block; width:100%;background-color:#FF0000;padding:2px;color:#FFFFFF;text-align:center;"><b>!!Warning!!</b> SITE IS RUNNING UNDERCONSTRUCTION MODE !!</a>';
}
if (defined("DEMO")) {
    //echo '<div class="text_11" style="background-color:#FFFF00;padding:2px;color:#444400;text-align:center;"><b>DEMO</b> mode enabled</div>';
}
if (isset($data['lang'][$reply])) {
    echo '
				<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				  <div class="modal-dialog">
					  <div class="modal-content">
						  <div class="modal-header">
							  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							  <h4 class="modal-title">' . CONFIG_SITE_NAME . '</h4>
						  </div>
						  <div class="modal-body">

							  ' . $data['lang'][$reply] . '

						  </div>
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
				</script>
				';
}

if ($admin->isLogedIn()) {
    if ($request->GetInt("showInFrm") == 0) {
        ?>
                    <div class="topbar">

                        <!-- LOGO -->
                        <div class="topbar-left">
                            <div class="text-center">
                                <a href="index.html" class="logo"><i class="icon-magnet icon-c-logo"></i><span><?php echo CONFIG_SITE_NAME ?></span></a>
                            </div>
                        </div>

                        <!-- Button mobile view to collapse sidebar menu -->
                        <div class="navbar navbar-default" role="navigation">
                            <div class="container">
                                <div class="">
                                    <div class="pull-left">
                                        <button class="button-menu-mobile open-left">
                                            <i class="ion-navicon"></i>

                                        </button>
                                        <span class="clearfix"></span>
                                    </div>

                                    <form class="navbar-left app-search pull-left hidden-xs" role="search">
                                        <input type="text" id="name" name="name" class="form-control" onkeypress="return handle(event)" placeholder="Search...">

        <!--                                        <a href="javascript:makeAjaxRequest()"  onclick="makeAjaxRequest(event)"><i class="fa fa-search"></i></a>
                                        -->

                                    </form>


        <?php
        //  echo $_SERVER['HTTP_HOST'];
        // echo $_SERVER['SERVER_ADDR'];

        $qry_check = 'select is_update from ' . ADMIN_MASTER . ' where id=1';
        $query = $mysql->query($qry_check);
        if ($mysql->rowCount($query) > 0) {
            $rows = $mysql->fetchArray($query);
            if ($rows[0]['is_update'] == "1") {
                ?>
                                            <marquee style="margin-left: 90px;" WIDTH=250 HEIGHT=50>   <a href="<?php echo CONFIG_PATH_SITE_ADMIN ?>download_update.html"><i class="fa fa-download" style="color:red"></i>Get Update</a><p>GsmUnion Latest Update Released</p></marquee>
                                            <?php
                                        }
                                    }
                                    ?>



        <!--                                    Invoice<input type="checkbox" id="invoice" name="invoice" size="20 px" style="margin: 28px 0 0" />-->


                                    <style type="text/css">
                                        #scr_results {
                                            left: 197px;
                                            min-width: 300px;
                                            position: absolute;
                                            top: 175px;
                                            z-index: 99999999;
                                        }

                                        .resultcontent {
                                            background-color: #ffffff !important;
                                            border: 1px solid #dddddd !important;
                                            border-radius: 0;
                                            color: #000000 !important;
                                            font-family: Helvetica Neue;
                                            font-size: 23px;
                                            margin: -111px 2px 13px 79px !important;
                                            overflow-y: auto;
                                            padding: 15px;
                                            white-space: nowrap;
                                            overflow-y:scroll;
                                            max-height: 350px;
                                        }
                                        .close-subpage {
                                            background: #d11010 none repeat scroll 0 0;
                                            border: 1px solid red;
                                            color: white;
                                            font-size: 16px;
                                            padding: 1px 7px 3px 8px;
                                            position: absolute;
                                            right: 10px;
                                            text-shadow: none;
                                            top: 12px;
                                            z-index: 9999;
                                            margin:  -69px -3px  6px;
                                        }
                                        #scr_results .scr_resultheader {
                                            color: black;
                                            font-size: 12px;
                                            font-weight: normal;
                                            margin-top: 15px;
                                            padding: 6px;
                                            text-align: left;
                                        }
                                        #scr_results .scr_result {
                                            border-top: 1px solid #ebebeb !important;
                                            padding: 7px;
                                        }
                                    </style>
                                    <div id="scr_results" style="display: none;">
                                        <div class="sidearrow"></div>
                                        <div class="resultcontent" style="overflow-y: scroll;">
                                            <div class="close-subpage"  id="closesubpage" style="cursor: pointer;line-height: 15px;position: absolute;right: 15px;top: 0;">x</div>
                                            <h1>Search Result</h1>


                                            <div class="src-content" style="overflow-y: scroll;">

                                            </div>
                                        </div>
                                    </div>
                                    <ul class="nav navbar-nav navbar-right pull-right">
        <?php
        if ($newReg > 0) {
            ?>
                                            <li class="dropdown hidden-xs">
                                                <a href="#" data-target="#" class="dropdown-toggle waves-effect" data-toggle="dropdown" aria-expanded="true">
                                                    <i class="fa fa-users"></i> <span class="badge badge-xs badge-danger"><?php echo $newReg; ?></span>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-lg">                                               
                                                    <li class="notifi-title"><span class="label label-default pull-right">New <?php echo $newReg; ?></span>Pending User(s)</li>


            <?php
            if ($newReg > 0) {
                foreach ($newRegArr as $user) {
                    echo '<li><a href="' . CONFIG_PATH_SITE_ADMIN . 'users_add.html?id=' . $user['id'] . '"><i class="fa fa-user fa-3"></i>  ' . $user['username'] . '</a></li>';
                }
            }
            ?>
                                                    <li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_register.html"><i class="fa fa-list"></i>  Pending User List</a></li>

                                            </li>

                                        </ul> <?php } ?>

                                    </li>



                                    <ul class="nav navbar-nav navbar-right pull-right">
        <?php
        if ($crRequest > 0) {
            ?>
                                            <li class="dropdown hidden-xs">
                                                <a href="#" data-target="#" class="dropdown-toggle waves-effect" data-toggle="dropdown" aria-expanded="true">
                                                    <i class="fa fa-usd"></i> <span class="badge badge-xs badge-danger"><?php echo $crRequest; ?></span>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-lg">                                               
                                                    <li class="notifi-title"><span class="label label-default pull-right">New <?php echo $crRequest; ?></span>Pending Credit(s)</li>


            <?php
            if ($crRequest > 0) {
                foreach ($crRequestArr as $crReq) {
                    echo '<li><a href="' . CONFIG_PATH_SITE_ADMIN . 'users_credit_request.html"><i class="fa fa-user fa-3"></i>  
												<span class="subject">
												<span class="from">' . $crReq['username'] . '</span>
												<span class="time">' . $crReq['prefix'] . ' ' . $crReq['credits'] . '</span>
												</span>
												<span class="message">
													' . date("d-M Y H:i", strtotime($crReq['date_time'])) . '
												</span>
											</a></li>';
                }
            }
            ?>

                                            </li>
                                        </ul> <?php } ?>
                                    </li>






                                    <ul class="nav navbar-nav navbar-right pull-right">
                                        <li class="dropdown hidden-xs">










                                        <li class="hidden-xs">
                                            <a href="#" id="btn-fullscreen" class="waves-effect"><i class="icon-size-fullscreen"></i></a>
                                        </li>
                                        <li class="hidden-xs">
                                            <a href="#" class="right-bar-toggle waves-effect"><i class="fa fa-comments-o"></i></a>
                                        </li>

                                        <li class="dropdown">
                                            <a href="" class="dropdown-toggle profile waves-effect" data-toggle="dropdown" aria-expanded="true"><img src="<?php echo CONFIG_PATH_PANEL; ?>assets/images/users/avatar-1.jpg" alt="user-img" class="img-circle"> </a>
                                            <ul class="dropdown-menu">

                                                <li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>ticket.html"><?php echo ($tickets > 0) ? '<span class="label label-danger pull-right mail-info">' . $tickets . '</span>' : ''; ?><i class="fa fa-life-ring"></i>  <?php $lang->prints('navi_support_ticket'); ?></a></li>

                                                <li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>password_change.html"><i class="fa fa-user-secret"></i>  <?php $lang->prints('navi_change_password'); ?></a></li>
                                                <li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>two_step_verify.html"><i class="fa fa-hand-peace-o"></i><?php $lang->prints('navi_two_step'); ?></a></li>
                                                <li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>log_out.do"><i class="fa fa-power-off"></i><?php $lang->prints('navi_logout'); ?></a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                                <!--/.nav-collapse -->
                            </div>
                        </div>
                    </div>





                    <!-- ========== Left Sidebar Start ========== -->
                    <div class="left side-menu" style="font-family:'Helvetica Neue',Arial,Helvetica,sans-serif" >
                        <div class="sidebar-inner slimscrollleft">
                            <!--- Divider -->
                            <div id="sidebar-menu">
                                <ul>

                                    <li class="text-muted menu-title">Navigation</li>

                                    <li class="has_sub">

                                    </li>
                                    <li class="has_sub">
                                        <a href="#" class="waves-effect waves-light <?php $admin->naviMain('dashboard', 'active', '0', $page); ?>"><i class=""><span class="glyphicon glyphicon-king"></span></i> <span> <?php $lang->prints('lbl_Dashboard'); ?> </span> </a>                                        <ul  style="font-size: 12px;">
                                            <!--li><a class="" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><span> <?php echo $lang->get('menu_dashboard') ?> </span></a></li-->
                                            <li class="<?php $admin->navi('users_register', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_register.html"><?php echo ($newReg > 0) ? '<span class="label label-danger pull-right mail-info">' . $newReg . '</span>' : ''; ?><?php $lang->prints('navi_new_req'); ?></a></li>
                                            <li class="<?php $admin->navi('credit_request', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_credit_request.html"><?php echo ($crRequest > 0) ? '<span class="label label-danger pull-right mail-info">' . $crRequest . '</span>' : ''; ?><?php $lang->prints('navi_credit_req'); ?></a></li>
                                            <li class="<?php $admin->navi('unpaid_invoice', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_credit_invoices.html?status=0"><?php $lang->prints('unpaid_invoices'); ?></a></li>

                                        </ul>
                                    </li>

                                    <li class="has_sub">
                                        <a href="#" class="waves-effect waves-light <?php $admin->naviMain('Clients/Suppliers', 'active', '0', $page); ?>"><i class="ti-user"></i><span><?php $lang->prints('lbl_navi_client_supp'); ?></span></a>
                                        <ul class="list-unstyled" style="font-size: 12px;">
                                            <li class="<?php $admin->navi('users_add', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_add.html">+<?php $lang->prints('lbl_navi_add_client'); ?> </a></li>
                                            <li class="<?php $admin->navi('users', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users.html"><?php $lang->prints('lbl_navi_view_client'); ?></a></li>
                                            <li class="<?php $admin->navi('suppliers_add', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>suppliers_add.html">+<?php $lang->prints('lbl_navi_add_supp'); ?> </a></li>

                                            <li class="<?php $admin->navi('suppliers_edit', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>suppliers.html"><?php $lang->prints('lbl_navi_view_supp'); ?></a></li>
                                            <li class="<?php $admin->navi('special_package_group', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>package.html"><?php $lang->prints('navi_special_Groups'); ?></a></li>

                                        </ul>
                                    </li>    
                                    <li class="has_sub">
                                        <a href="#" class="waves-effect waves-light <?php $admin->naviMain('Products/Service', 'active', '0', $page); ?>"><i class="md md-security"></i><span><?php $lang->prints('navi_prod_serv'); ?></span></a>
                                        <ul class="list-unstyled" style="font-size: 12px;">
                                            <li class="<?php $admin->navi('services_imei_group', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_tools.html" ><?php $lang->prints('navi_imei_services'); ?></a></li>
                                            <li class="<?php $admin->navi('services_file', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_file.html"><?php $lang->prints('navi_file_services'); ?></a></li>
                                            <li class="<?php $admin->navi('server_logs_group', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>server_logs.html"><?php $lang->prints('navi_server_services'); ?></a></li>
                                            <li class="<?php $admin->navi('prepaid_logs_group', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>prepaid_logs.html"><?php $lang->prints('navi_prepaid_services'); ?></a></li>
                                            <li class="divider"></li>
                                        </ul>
                                    </li>  
                                    <li class="has_sub">
                                        <a href="#" class="waves-effect waves-light <?php $admin->naviMain('order', 'active', '0', $page); ?>"><i class="ti-shopping-cart-full"></i><span><?php $lang->prints('navi_orders'); ?></span></a>
                                        <ul class="list-unstyled" style="font-size: 12px;">
                                            <li class="<?php $admin->navi('order_imei', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_imei.html?type=pending" class="PL10"><?php $lang->prints('navi_imei_orders'); ?></a></li>
                                            <li class="<?php $admin->navi('order_imei_bulk', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>imei_bulk_reply.html" class="PL10"><?php $lang->prints('navi_IMEI_orders_bulk_reply'); ?></a></li>

                                            <li class="<?php $admin->navi('order_file', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_file.html?type=pending"> <?php $lang->prints('navi_file_orders'); ?></a></li>
                                            <li class="<?php $admin->navi('order_server_log', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_server_log.html?type=pending"> <?php $lang->prints('navi_server_orders'); ?></a></li>
                                            <li class="<?php $admin->navi('order_prepaid_log', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_prepaid_log.html?type=pending"> <?php $lang->prints('navi_prepaid_orders'); ?></a></li>
                                        </ul>
                                    </li>

                                    <li class="has_sub">
                                        <a href="#" class="waves-effect waves-light <?php $admin->naviMain('cms', 'active', '0', $page); ?>"><i class="md md-content-copy"></i><span><?php $lang->prints('navi_cms'); ?></span></a>
                                        <ul class="list-unstyled" style="font-size: 12px;">
        <!--                                            <li><a href="#"><?php $lang->prints('navi_General_Settings'); ?></a></li>-->
                                            <li class="<?php $admin->navi('config_settings', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>config_settings.html" <?php $admin->navi('config_settings', $page); ?> ><?php $lang->prints('navi_admin_theme'); ?></a></li>
                                            <li class="<?php $admin->navi('config_news', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>config_news.html" <?php $admin->navi('config_news', $page); ?> ><?php $lang->prints('navi_manage_news_page'); ?></a></li>
                                            <li class="<?php $admin->navi('manage_email', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>email_template_list.html" <?php $admin->navi('manage_email', $page); ?> ><?php $lang->prints('navi_manage_email'); ?></a></li>
                                            <li class="<?php $admin->navi('invoice_edit', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>invoice_edit.html"><?php $lang->prints('navi_invoice_edit'); ?> </a></li>


                                        </ul>
                                    </li>  
                                    <li class="has_sub">
                                        <a href="javascript:void(0);" class="waves-effect waves-light <?php $admin->naviMain('report', 'active', '0', $page); ?>"><i class="ti-bar-chart"></i><span><?php $lang->prints('navi_reports_graphs'); ?></span></a>
                                        <ul>
                                            <li class="has_sub" style="font-size: 12px;">
                                                <a href="javascript:void(0);" class="waves-effect waves-light"><span><?php $lang->prints('navi_orders_reports'); ?></span> </a>
                                                <ul style="" style="font-size: 12px;">
                                                    <li class="<?php $admin->navi('report_order_summary', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_order_summary_imei.html" <?php $admin->navi('report_order_summary', $page); ?> ><?php $lang->prints('navi_anual'); ?></a></li>
                                                    <li class="<?php $admin->navi('report_order_imei_profit', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_order_imei_profit.html" <?php $admin->navi('report_order_imei_profit', $page); ?> ><?php $lang->prints('navi_order_imei_profit'); ?></a></li>
                                                    <li class="<?php $admin->navi('report_balance_unpaid', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_balance_unpaid.html" <?php $admin->navi('report_balance_unpaid', $page); ?> ><?php $lang->prints('navi_balance_unpaid'); ?></a></li>

                                                    
                                                    <li class="<?php $admin->navi('report_IMEI_order_Daywise', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_order_imei_daywise.html" <?php $admin->navi('report_IMEI_order_Daywise', $page); ?> ><?php $lang->prints('navi_Daywise'); ?></a></li>
                                                    <li class="<?php $admin->navi('report_order_userwise', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_order_userwise.html" <?php $admin->navi('report_transection', $page); ?> ><?php $lang->prints('navi_Userwise'); ?></a></li>
                                                    <li class="<?php $admin->navi('report_orders_users', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_orders_users.html" <?php $admin->navi('report_transection', $page); ?> ><?php $lang->prints('navi_Users_Orders_all'); ?></a></li>

                                                    <li class="<?php $admin->navi('rpt_order', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_order_imei.html" <?php $admin->navi('report_order_imei', $page); ?> ><?php $lang->prints('navi_iMEI'); ?> </a></li>
                                                    <li class="<?php $admin->navi('rpt_order', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_order_file.html" <?php $admin->navi('report_order_file', $page); ?> ><?php $lang->prints('navi_file'); ?> </a></li>
                                                    <li class="<?php $admin->navi('rpt_order', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_order_server_log.html" <?php $admin->navi('report_order_server_log', $page); ?> ><?php $lang->prints('navi_server_log'); ?> </a></li>


                                                </ul>
                                            </li>
                                            <li class="has_sub">
                                                <a href="javascript:void(0);" class="waves-effect waves-light <?php $admin->naviMain('report', 'active', '0', $page); ?>"><span><?php $lang->prints('navi_log_reports'); ?></span> </a>
                                                <ul style="" style="font-size: 12px;">
                                                    <li class="<?php $admin->navi('report_admin_login_log', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_admin_login_log.html" <?php $admin->navi('report_admin_login_log', $page); ?> ><?php $lang->prints('navi_admin_login'); ?></a></li>
                                                    <li class="<?php $admin->navi('report_user_login_log', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_user_login_log.html" <?php $admin->navi('report_user_login_log', $page); ?> ><?php $lang->prints('navi_user_login'); ?></a></li>
        <!--                                                    <li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_api_error_log.html" <?php $admin->navi('report_api_error_log', $page); ?> ><?php $lang->prints('navi_api_error'); ?> </a></li>-->
                                                    <li class="<?php $admin->navi('report_transection', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_transections.html" <?php $admin->navi('report_transection', $page); ?> ><?php $lang->prints('navi_transaction'); ?></a></li>

                                                </ul>
                                            </li>
                                            <li>

                                            </li>
                                        </ul>
                                    </li>



                                    <li class="has_sub">
                                        <a href="#" class="waves-effect waves-light <?php $admin->naviMain('utilities', 'active', '0', $page); ?>"><i class=""><span class="glyphicon glyphicon-wrench"></span></i><span><?php $lang->prints('navi_utilities'); ?></span></a>
                                        <ul class="list-unstyled" style="font-size: 12px;">
                                            <li class="<?php $admin->navi('download_update', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>download_update.html" ><?php $lang->prints('navi_chk_update'); ?> </a></li>
                                            <li class="<?php $admin->navi('mass_mail', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>email_user_list.html" <?php $admin->navi('mass_mail', $page); ?> ><?php $lang->prints('navi_mass_Email'); ?> </a></li>
            <!--                                            <li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>massemail_log.html">Mass Email Log </a></li>-->
                                            <li class="<?php $admin->navi('database_backup', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>utl_db_backup.html" <?php $admin->navi('database_backup', $page); ?> ><?php $lang->prints('navi_database_Backup'); ?> </a></li>
                                            <li class="<?php $admin->navi('users_reset_password', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_reset_password.html" <?php $admin->navi('mass_mail', $page); ?> ><?php $lang->prints('navi_reset_all_password'); ?> </a></li>
                                            <li class="<?php $admin->navi('system_clean_up', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>utl_system_cleanup.html" <?php $admin->navi('system_clean_up', $page); ?> ><?php $lang->prints('navi_system_Cleanup'); ?> </a></li>
                                            <li class="<?php $admin->navi('system_logs_up', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>utl_logs_cleanup.html" <?php $admin->navi('system_logs_up', $page); ?> ><?php $lang->prints('navi_logs_Cleanup'); ?> </a></li>

                                            <li class="<?php $admin->navi('services_imei_brands', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_brands.html" <?php $admin->navi('services_imei_brands', $page); ?>><?php $lang->prints('navi_brand_master'); ?></a></li>
                                            <li class="<?php $admin->navi('services_file_white_list', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_file_white_list.html" <?php $admin->navi('services_file_white_list', $page); ?>><?php $lang->prints('navi_Files_Extensions'); ?></a></li>


                                        </ul>
                                    </li>  


                                    <li class="has_sub">
                                        <a href="#" class="waves-effect waves-light <?php $admin->naviMain('Support', 'active', '0', $page); ?>"><i class="icon-support"></i><span><?php $lang->prints('navi_support'); ?></span></a>
                                        <ul class="list-unstyled" style="font-size: 12px;">

                                            <li class="<?php $admin->navi('chat_panel', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>chat_panel.html"><?php $lang->prints('navi_p_chat'); ?></a></li>
                                            <li class="<?php $admin->navi('chat_panel_guest', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>chat_panel_guest.html"><?php $lang->prints('navi_g_chat'); ?></a></li>

                                            <li class="<?php $admin->navi('ticket', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>ticket.html"><?php $lang->prints('navi_tickets'); ?></a></li>


                                        </ul>
                                    </li> 

                                    <li class="has_sub">
                                        <a href="#" class="waves-effect waves-light <?php $admin->naviMain('Settings', 'active', '0', $page); ?>"><i class="md md-settings"></i><span><?php $lang->prints('navi_Settings'); ?></span></a>
                                        <ul class="list-unstyled" style="font-size: 12px;">

                                            <li class="<?php $admin->navi('config_settings_2', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>config_settings_2.html"><?php $lang->prints('navi_general_setting'); ?></a></li>

                                            <li class="<?php $admin->navi('smtp_settings', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>smtp_settings.html" <?php $admin->navi('smtp_settings', $page); ?> ><?php $lang->prints('navi_email_setting'); ?></a></li>
                                            <li class="<?php $admin->navi('services_imei_countries', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_countries.html" <?php $admin->navi('service_imei_country', $page); ?>><?php $lang->prints('navi_countries'); ?></a></li>
                                            <li class="<?php $admin->navi('currency', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>currency.html" <?php $admin->navi('currency', $page); ?> ><?php $lang->prints('navi_currencies'); ?></a></li>
                                            <li class="<?php $admin->navi('config_gateway', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>settings_gateway.html" <?php $admin->navi('config_gateway', $page); ?> ><?php $lang->prints('navi_payment_gateways'); ?></a></li>
                                            <li class="<?php $admin->navi('services_imei_api', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>api_list.html" <?php $admin->navi('services_imei_api', $page); ?>><?php $lang->prints('navi_api_setting'); ?></a></li>

                                            <li class="<?php $admin->navi('admin', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>admin.html"><?php $lang->prints('navi_sys_admin'); ?></a></li>

                                            <li class="<?php $admin->navi('web_maintinance', $page); ?>"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>web_maintinance.html"><?php $lang->prints('navi_web_main'); ?></a></li>
                                            <!-- <li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>language.html" <?php $admin->navi('config_language', $page); ?> ><?php $lang->prints('navi_manage_language'); ?></a></li> -->
                                        </ul>
                                    </li>    


                                    <li class="has_sub">
                                        <a href="#" class="waves-effect waves-light"><i class="md md-help"></i><span><?php $lang->prints('navi_help'); ?></span></a>
                                        <ul class="list-unstyled" style="font-size: 12px;">

                                            <li><a href="http://gsmunion.net/" target="_blank">Support GsmUnion </a></li>


                                        </ul>
                                    </li> 



                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>

                    <!-- Left Sidebar End -->

                    <section id="container" class="">
                        <!--sidebar start-->

                        <!--sidebar end-->
                        <!--main content start-->
                        <div class="container">
                            <div class="content-page">
                                <!-- Start content -->
                                <div class="content">
                                    <div class="container">

        <?php
        if (file_exists(CONFIG_PATH_ADMIN_ABSOLUTE . $page . '.php')) {
            include(CONFIG_PATH_ADMIN_ABSOLUTE . $page . '.php');
        } else {
            echo '<br /><br /><h1 class="text-danger text-center">Error:404 Page Not Found!</h1><br /><br /><br /><br /><br />';
        }
        ?>



                                        <!--main content end-->
                                        <!--footer start-->
                                    </div>
                                    <footer class="footer text-right">
                                        <p class="wow fadeInUp" style=" color: black"><?php echo "Copyright  &#169; " . date('Y') . " GSM UNION"; ?>. <?php $lang->prints('lbl_all_rights_reserved'); ?>.</p>

                                    </footer>
                                </div></div>
                            <!--footer end-->
                            <!-- ============================================================== -->
                            <!-- End Right content here -->
                            <!-- ============================================================== -->

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

                            <!-- Right Sidebar -->
                            <div class="side-bar right-bar nicescroll">

                                <h4 class="text-center"><span data-toggle="tooltip" data-placement="Right" title="Click To On/Off Notofications" style="float: left"><input  type="checkbox" id="notification" <?php echo $adminnotify; ?> data-plugin="switchery" data-color="#A0D269" data-size="small" onchange="setStatus(2);"/></span><span data-toggle="tooltip" data-placement="bottom" title="Click To On/Off Chat" style="float: right"><input type="checkbox" id="onlinestatus" <?php echo $adminchatstatus; ?> data-plugin="switchery" data-color="#A0D269" data-size="small" onchange="setStatus(1);"/></span>Chat</h4>
                                <div class="contact-list nicescroll">  <input type="text" id="search" placeholder="Search Contact" class="form-control">
                                    <ul class="list-group contacts-list">





        <?php
        $current_time = time();

        if ($userlistt['COUNT']) {
            $totalunreadmsgs = 0;
            foreach ($userlistt['RESULT'] as $row) {

                //$enocdeis = urlencode($row['id']);
                $totalunreadmsgs = $totalunreadmsgs + $row["msgcount"];
                $timestamp = strtotime($row["last_active_time"]);
                $latest = $current_time - $timestamp;
                $latest = $latest / 60;
                ?>

                                                <li class="list-group-item">

                <!--                                                    <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>chat.html?id=<?php echo $row['id']; ?>" target="_blank">-->
                                                    <div class="avatar">

                <?php if ($row['img'] != '') { ?>

                                                            <img src="<?php echo CONFIG_PATH_SITE; ?>images/<?php echo $row['img']; ?>" alt="User">
                                                        <?php } else {
                                                            ?>
                                                            <img src="<?php echo CONFIG_PATH_PANEL; ?>assets/images/users/avatar-2.jpg" alt="User">
                                                            <?php
                                                        }
                                                        ?>



                                                    </div>
                                                    <span onclick="popUp('chat.html?id=<?php echo $row['id']; ?>');" class="name"><?php echo $row['username']; ?> </span>

                <?php
                if ($row['online'] == 1 && $latest <= 15) {
                    echo '<i class="fa fa-circle online"></i>';
                } else {
                    echo '<i class="fa fa-circle offline"></i>';
                }
                ?>

                                                    </a>
                                                    <span class="fa fa-envelope">  <?php echo $row['msgcount']; ?></span>
                                                </li>

                <?php
            }
        }
        ?>


                                    </ul>
                                </div>
                            </div>
                            <!-- /Right-bar -->
                    </section>

        <?php
    } else {
        if (file_exists(CONFIG_PATH_ADMIN_ABSOLUTE . $page . '.php')) {
            ?>

                        <div class="container">
                        <?php include(CONFIG_PATH_ADMIN_ABSOLUTE . $page . '.php'); ?></div><?php
                    } else {
                        echo '<br /><br /><h1 class="text-danger text-center">Error:404 Page Not Found!</h1><br /><br /><br /><br /><br />';
                    }
                }
            } else {
                if (file_exists(CONFIG_PATH_ADMIN_ABSOLUTE . $page . '.php')) {
                    ?><div class="container"><?php include(CONFIG_PATH_ADMIN_ABSOLUTE . $page . '.php'); ?></div><?php
                } else {
                    echo '<br /><br /><h1 class="text-danger text-center">Error:404 Page Not Found!</h1><br /><br /><br /><br /><br />';
                }
            }
            ?>



            <script>
                var resizefunc = [];
            </script>

            <!-- jQuery  -->
<!--            <script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/jquery.min.js"></script>-->
            <script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/bootstrap.min.js"></script>
            <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/js/detect.js"></script>
            <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/js/fastclick.js"></script>

            <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/js/jquery.slimscroll.js"></script>
            <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/js/jquery.blockUI.js"></script>
            <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/js/waves.js"></script>
            <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/js/wow.min.js"></script>
            <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/js/jquery.nicescroll.js"></script>
            <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/js/jquery.scrollTo.min.js"></script>

            <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/plugins/switchery/dist/switchery.min.js"></script>

            <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/js/jquery.core.js"></script>
            <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/js/jquery.app.js"></script>


            <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/js/init.js" ></script>
            <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/js/init_nxt_admin.js" ></script>

            <!-- Chart JS -->


            <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/plugins/notifyjs/dist/notify.min.js"></script>
            <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/plugins/notifications/notify-metro.js"></script>
            <!--script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/pages/chartjs.init.js"></script-->
            <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/plugins/waypoints/lib/jquery.waypoints.js"></script>
            <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/plugins/counterup/jquery.counterup.min.js"></script>

            <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/plugins/jquery-knob/jquery.knob.js"></script>
            <!-- Sweet-Alert  -->
            <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/plugins/sweetalert/dist/sweetalert.min.js"></script>

<!--            <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/pages/jquery.chat.js"></script>-->

            <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/plugins/morris/morris.min.js" type="text/javascript"></script>
            <script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/plugins/raphael/raphael-min.js" type="text/javascript"></script>

            <!--common script for all pages-->
            <!--script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/js/common-scripts.js"></script-->
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
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

                });
            </script>


            <script>
                function setStatus(a)
                {
                    //  alert(a);
                    if (a == 1)
                    {
                        var onlinestatus = $('#onlinestatus').prop('checked');
                        //   alert(onlinestatus);
                        var adminid =<?php echo $admin->getUserId(); ?>;
                        // ajax call 
                        $.ajax({
                            type: "POST",
                            url: config_path_site_admin + "_set_online_status.do",
                            data: "&a_id=" + adminid + "&ostat=" + onlinestatus + "&type=" + a,
                            error: function () {
                                // alert("Some Error Occur");
                            },
                            success: function (msg) {
                                // you are now offline
                                //$.Notification.notify('custom','top right','Got A MESSAGE', 'New MSG')
                                // alert(msg);
                                if (msg == 1)
                                    $.Notification.notify('success', 'right middle', 'Chat Status', 'Online')
                                if (msg == 0)
                                    $.Notification.notify('error', 'right middle', 'Chat Status', 'Offline')
                            }
                        });
                    }
                    else
                    {
                        var onlinestatus = $('#notification').prop('checked');
                        //alert(onlinestatus);
                        var adminid =<?php echo $admin->getUserId(); ?>;
                        // ajax call 
                        $.ajax({
                            type: "POST",
                            url: config_path_site_admin + "_set_online_status.do",
                            data: "&a_id=" + adminid + "&ostat=" + onlinestatus + "&type=" + a,
                            error: function () {
                                //   alert("Some Error Occur");
                            },
                            success: function (msg) {
                                // you are now offline
                                //$.Notification.notify('custom','top right','Got A MESSAGE', 'New MSG')
                                // alert(msg);
                                if (msg == 1)
                                    $.Notification.notify('success', 'right middle', 'Notification Status', 'On')
                                if (msg == 0)
                                    $.Notification.notify('error', 'right middle', 'Notification Status', 'Off')
                            }
                        });
                    }
                }

            </script>
            <script type="text/javascript">
                $("#search").on("keyup", function () {
                    var searchText = $(this).val();
                    searchText = searchText.toUpperCase();

                    $('.contacts-list > li').each(function () {

                        var currentLiText = $(this).text().toUpperCase(),
                                showCurrentLi = currentLiText.indexOf(searchText) !== -1;

                        $(this).toggle(showCurrentLi);

                    });
                });
            </script>
            <script type="text/javascript">
                // $('.btnSearch').click(function(){
                // code goes here !
                $("#closesubpage").click(function () {

                    $("#scr_results").css("display", "none")
                });
                function handle(e) {
                    if (e.keyCode == 13) {
                        var search_val = $('#name').val();
                        var invoice = $('#invoice').is(":checked");

                        //alert(search_val);
                        $.ajax({
                            url: '<?php echo CONFIG_PATH_ADMIN ?>search.php?val=' + search_val + '&inv=' + invoice,
                            type: 'POST',
                            data: {name: search_val},
                            success: function (response) {
                                $('#scr_results').css("display", "block");
                                $('.src-content').html(response);
                            }
                        });
                        return false;
                    }


                }
                function makeAjaxRequest(e) {
                    if (e.keyCode == 13) {

                    }

                }
//});

            </script>
            <script type="text/javascript">
                function popUp(url) {
                    w = window.open(url, '_blank', 'width=800,height=800,menubar=no');
                }
            </script>
        </div>
    </body>
</html>
