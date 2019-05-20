<?php
$strMsg = $request->getStr('msg');
$reply = $request->getStr('reply');
if ($page != 'index') {
    if (!$member->isLogedIn()) {
        header('location:' . CONFIG_PATH_SITE_USER . 'index.html?msg=' . urlencode('Your session expired!'));
    }
}

$service_imei = $service_file = $service_logs = $service_prepaid = $user_type = 0;

if ($member->isLogedIn()) {
    $sql_auth = 'select service_imei, service_file, service_logs, service_prepaid, user_type from ' . USER_MASTER . ' where id=' . $member->getUserId();
    $query_auth = $mysql->query($sql_auth);
    $rows_auth = $mysql->fetchArray($query_auth);
    $service_imei = $rows_auth[0]['service_imei'];
    $service_file = $rows_auth[0]['service_file'];
    $service_logs = $rows_auth[0]['service_logs'];
    $service_prepaid = $rows_auth[0]['service_prepaid'];

    $user_type = $rows_auth[0]['user_type'];
    
    
    $sqlgetmsg='select sum((select count(b.a_id) countms from nxt_chat_pool b where b.isview=0
                and b.entry_type="user" 
                and b.admin_id=a.id
                and b.user_id=' . $member->getUserId().' )) msgcount 
                from nxt_admin_master a 
                where a.`status`=1';
    $query_auth = $mysql->query($sqlgetmsg);
    $rows_auth = $mysql->fetchArray($query_auth);
    $newmsgss = $rows_auth[0]['msgcount'];
    //echo $newmsgss;exit;
}
$crM = $objCredits->getMemberCredits();
$credits = $crM['credits'];
$creditsUsed = $crM['used'];
$creditsProcess = $crM['process'];


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
?><!doctype html>
<html lang="us">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
        <meta name="author" content="Coderthemes">

        <link rel="shortcut icon" href="<?php echo CONFIG_PATH_SITE; ?>favicon.ico">

        <title><?php echo CONFIG_SITE_NAME;?>-User Panel</title>

        <!--Morris Chart CSS -->
        <link rel="stylesheet" href="<?php echo CONFIG_PATH_PANEL; ?>assets/plugins/morris/morris.css">

        <link href="<?php echo CONFIG_PATH_PANEL; ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

        <link href="<?php echo CONFIG_PATH_PANEL; ?>assets/css/core2.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo CONFIG_PATH_PANEL; ?>assets/css/components.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo CONFIG_PATH_PANEL; ?>assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo CONFIG_PATH_PANEL; ?>assets/css/pages.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo CONFIG_PATH_PANEL; ?>assets/css/responsive.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo CONFIG_PATH_PANEL; ?>assets/css/menu.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo CONFIG_PATH_PANEL; ?>assets/plugins/sweetalert/dist/sweetalert.css" rel="stylesheet" type="text/css">
        <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

        <script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/modernizr.min.js"></script>




    </head>


    <body>

        <?php
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

        /*         * ***********************************************************************
         * *************************************************************************
          In case of .frm FILE
         * *************************************************************************
         * ************************************************************************ */

        if ($request->GetInt("showInFrm") == "1") {
            if (file_exists(CONFIG_PATH_USER_ABSOLUTE . $page . '.php')) {
                include(CONFIG_PATH_USER_ABSOLUTE . $page . '.php');
            } else {
                echo '<br /><br /><h1 class="text-danger text-center">Error:404 Page Not Found!</h1><br /><br /><br /><br /><br />';
            }

            echo '</body>';
            echo '</html>';
            exit();
        }
        /*         * ***********************************************************************
         * *************************************************************************
          End: In case of .frm FILE
         * *************************************************************************
         * ************************************************************************ */

        if ($member->isLogedIn()) {
            ?>



            <header id="topnav">
                <div class="topbar-main">
                    <div class="container">

                        <!-- Logo container-->
                        <div class="logo">
                            <a href="index.html" class="logo"><span><?php echo CONFIG_SITE_NAME;?></span></a>
                        </div>
                        <!-- End Logo container-->
                        <div class="menu-item">
                            <!-- Mobile menu toggle-->
                            <a class="navbar-toggle">
                                <div class="lines">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                            </a>
                            <!-- End mobile menu toggle-->
                        </div>

                        <div class="menu-extras">

                            <ul class="nav navbar-nav navbar-right pull-right">


                                <li style="margin-top: 10px">
                                    <span class="badge badge-success small">Current Balance  <?php echo $credits; ?></span>

                                </li>
                                <li class="dropdown">
                                    <a href="" class="dropdown-toggle waves-effect waves-light profile" data-toggle="dropdown" aria-expanded="true"><img src="<?php echo CONFIG_PATH_PANEL; ?>assets/images/users/avatar-1.jpg" alt="user-img" class="img-circle"> </a>
                                    <ul class="dropdown-menu">
                                        <li><a href="<?php echo CONFIG_PATH_SITE_USER; ?>account_details.html"><i class="ti-user m-r-5"></i><?php echo $lang->get('menu_profile') ?></a></li>

                                        <?php
                                        if ($member->getAPIAccess() == 1) {
                                            ?>
                                            <li><a href="<?php echo CONFIG_PATH_SITE_USER; ?>api.html"><i class="ti-thought m-r-5"></i><?php echo $lang->get('menu_api_info') ?></a></li>
                                            <?php
                                        }
                                        ?>
                                        <li><a class="ti-comment m-r-5" href="<?php echo CONFIG_PATH_SITE_USER; ?>chat_panel.html"><?php echo $lang->get('menu_Chat') ?></a></li>

                                        <li><a class="ti-list-ol m-r-5" href="<?php echo CONFIG_PATH_SITE_USER; ?>login_history.html"><?php echo $lang->get('menu_login_log') ?></a></li>
                                       
                                        <li><a class="ti-settings m-r-5" href="<?php echo CONFIG_PATH_SITE_USER; ?>account_change_password.html"><?php echo $lang->get('menu_change_password') ?></a></li>
                                        <li><a class="ti-file m-r-5" href="<?php echo CONFIG_PATH_SITE_USER; ?>credits_invoice.html"><?php echo $lang->get('menu_invoices') ?></a></li>
                                        <li><a class="ti-list-ol m-r-5" href="<?php echo CONFIG_PATH_SITE_USER; ?>credits_history.html"><?php echo $lang->get('menu_credit_log') ?></a></li>
                                        <li><a class="ti-power-off m-r-5" href="<?php echo CONFIG_PATH_SITE_USER; ?>log_out.do"><?php echo $lang->get('link_log_out') ?></a></li>
                                    </ul>
                                </li>
                            </ul>

                        </div>

                    </div>
                </div>

                <div class="navbar-custom">
                    <div class="container">
                        <div id="navigation">
                            <!-- Navigation Menu-->
                            <ul class="navigation-menu">
                                <li class="has-submenu">
                                    <a href="<?php echo CONFIG_PATH_SITE_USER; ?>dashboard.html"><i class="md md-dashboard"></i>Dashboard</a>
                                   
                                </li>

                                <li class="has-submenu">
                                    <a href="#"><i class="md md-shopping-cart"></i>Services</a>
                                    <ul class="submenu megamenu">

                                        <?php
                                        if ($service_imei == "1") {
                                            ?>

                                            <li>
                                                <ul>
                                                    <li>
                                                        <span><?php echo $lang->get('menu_iMEI_service') ?></span>
                                                    </li>
                                                    <li><a href="<?php echo CONFIG_PATH_SITE_USER; ?>imei_submit.html"><b><?php echo $lang->get('menu_place_order') ?></b></a></li>
                                                    <li><a href="<?php echo CONFIG_PATH_SITE_USER . 'imei.html?type=all'; ?>"><?php echo $lang->get('menu_order_history') ?></a></li>
                                                </ul>
                                            </li>
                                            <?php
                                        }

                                        if ($service_file == "1") {
                                            ?>




                                            <li>
                                                <ul>
                                                    <li>
                                                        <span><?php echo $lang->get('menu_file_service') ?></span>
                                                    </li>
                                                    <li><a href="<?php echo CONFIG_PATH_SITE_USER; ?>file_submit.html"><b></i> <?php echo $lang->get('menu_place_order') ?></b></a></li>
                                                    <li><a href="<?php echo CONFIG_PATH_SITE_USER . 'files.html?type=all'; ?>"><?php echo $lang->get('menu_order_history') ?></a></li>

                                                </ul>
                                            </li>

                                            <?php
                                        }

                                        if ($service_logs == "1") {
                                            ?>


                                            <li>
                                                <ul>
                                                    <li>
                                                        <span><?php echo $lang->get('menu_log_service') ?></span>
                                                    </li>
                                                    <li><a href="<?php echo CONFIG_PATH_SITE_USER; ?>server_logs_submit.html"><b> <?php echo $lang->get('menu_place_order') ?></b></a></li>
                                                    <li><a href="<?php echo CONFIG_PATH_SITE_USER; ?>server_logs.html?type=all"><?php echo $lang->get('menu_order_history') ?></a></li>
                                                </ul>
                                            </li>
                                            <?php
                                        }
                                        ?>

                                    </ul>
                                </li> 

                                <li class="has-submenu">
                                    <a href="#"><i class="md md-attach-money"></i>Funds</a>
                                    <ul class="submenu">
                                        <li><a href="<?php echo CONFIG_PATH_SITE_USER; ?>credits_purchase.html"><b><?php echo $lang->get('menu_add_funds') ?></b></a></li>
                                        <li><a href="<?php echo CONFIG_PATH_SITE_USER; ?>credits_reqeusts.html"><?php echo $lang->get('menu_fund_requests') ?></a></li>
                                        <li class="divider"></li>

                                        <li class="divider"></li>
                                        <li><a href="<?php echo CONFIG_PATH_SITE_USER; ?>credits_transfer.html"><?php echo $lang->get('menu_fund_transfer') ?></a></li>
                                    </ul>
                                </li>
                                
                                  <li class="has-submenu">
                                    <a href="#"><i class="md md-security"></i>Support</a>
                                    <ul class="submenu">
                                        <li><a href="<?php echo CONFIG_PATH_SITE_USER; ?>chat_panel.html"><b><?php echo $lang->get('menu_Online_Chat') ?></b></a></li>
                                        
                                         <li><a href="<?php echo CONFIG_PATH_SITE_USER; ?>ticket.html"><?php echo $lang->get('menu_tickets') ?></a></li>
                                       
                                    </ul>
                                </li>
                                
                              <?php
				if($user_type != 0)
				{
			?>
                                 <li class="has-submenu">
                                    <a href="#"><i class="md md-people"></i>Users</a>
                                    <ul class="submenu">
                                        
                                        <li><a href="<?php echo CONFIG_PATH_SITE_USER;?>users.html"><?php echo $lang->get('menu_user_manage')?></a></li>
					<li><a href="<?php echo CONFIG_PATH_SITE_USER;?>user_add.html"><?php echo $lang->get('menu_user_add')?></a></li>
                                       
                                    </ul>
                                </li>
                                
                                <?php
				}
			?>
                            </ul>
                            <!-- End navigation menu        -->
                        </div>
                    </div>
                </div>
            </header>
            <div class="wrapper">
                <!-- ============================================================== -->
                <!-- Start right Content here -->
                <!-- ============================================================== -->
                <div class="container">


                    <?php
                    if (file_exists(CONFIG_PATH_USER_ABSOLUTE . $page . '.php')) {
                        include(CONFIG_PATH_USER_ABSOLUTE . $page . '.php');
                    } else {
                        echo '<br /><br /><h1 class="text-danger text-center">Error:404 Page Not Found!</h1><br /><br /><br /><br /><br />';
                    }
                    ?>



                    <footer class="footer text-right">
                        <div class="container">
                            <div class="row">
                                <div class="col-xs-6">
                                    <?php $lang->prints('lbl_copyright'); ?><?php echo CONFIG_SITE_TITLE; ?>. <?php $lang->prints('lbl_all_rights_reserved'); ?>.
                                </div>
                                <div class="col-xs-6">
                                    <ul class="pull-right list-inline m-b-0">
                                        <li>
                                            <a href="#">About</a>
                                        </li>
                                        <li>
                                            <a href="#">Help</a>
                                        </li>
                                        <li>
                                            <a href="#">Contact</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </footer>
                    <!--footer-->

                    <?php
                } else {
                    ?>
                    <div class="container">
                        <?php
                        include(CONFIG_PATH_USER_ABSOLUTE . $page . '.php');
                        ?>
                    </div>
                    <?php
                }
                ?>
            </div>

            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->
        </div>





        <!-- jQuery  -->
        <script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/jquery.min.js"></script>
        <script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/bootstrap.min.js"></script>
        <script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/detect.js"></script>
        <script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/fastclick.js"></script>

        <script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/jquery.slimscroll.js"></script>
        <script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/jquery.blockUI.js"></script>
        <script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/waves.js"></script>
        <script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/wow.min.js"></script>
         <!-- Sweet-Alert  -->
         
           <script src="<?php echo CONFIG_PATH_PANEL; ?>assets/plugins/notifyjs/dist/notify.min.js"></script>
            <script src="<?php echo CONFIG_PATH_PANEL; ?>assets/plugins/notifications/notify-metro.js"></script>
         
        <script src="<?php echo CONFIG_PATH_PANEL; ?>assets/plugins/sweetalert/dist/sweetalert.min.js"></script>
        <script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/jquery.nicescroll.js"></script>
        <script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/jquery.scrollTo.min.js"></script>
        <script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/jquery.core.js"></script>
        <script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/jquery.app2.js"></script>

        <script type="text/javascript">
            
           $(document).ready(function(){
               var totalmsg=<?php echo $newmsgss;?>;
               var pagename=location.pathname.substring(location.pathname.lastIndexOf("/") + 1);
               if(totalmsg!=0 && pagename!='chat.html' && pagename!='chat_panel.html')
                   $.Notification.notify('custom','top right','Chat Notification', 'You got some New Messages');
               // alert(totalmsg);
}); 
            
              // 
               
                  //  var pagename=location.pathname.substring(location.pathname.lastIndexOf("/") + 1);
                    //if(totalmsg!=0 && pagename!='chat.html')
                    //$.Notification.notify('custom','top right','Chat Notification', 'You got some New Messages');
            
                
            </script>

    </body>

</html>