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

        <link rel="shortcut icon" href="<?php echo CONFIG_PATH_PANEL; ?>assets/images/favicon_1.ico">

        <title>Ubold - Responsive Admin Dashboard Template</title>

        <!--Morris Chart CSS -->
		<link rel="stylesheet" href="<?php echo CONFIG_PATH_PANEL; ?>assets/plugins/morris/morris.css">

        <link href="<?php echo CONFIG_PATH_PANEL; ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo CONFIG_PATH_PANEL; ?>assets/css/core.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo CONFIG_PATH_PANEL; ?>assets/css/components.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo CONFIG_PATH_PANEL; ?>assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo CONFIG_PATH_PANEL; ?>assets/css/pages.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo CONFIG_PATH_PANEL; ?>assets/css/responsive.css" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

        <script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/modernizr.min.js"></script>
        
      


    </head>


    <body class="fixed-left">

        <div class="animationload">
            <div class="loader"></div>
        </div>
        <div id="wrapper">
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

            /*             * ***********************************************************************
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
            /*             * ***********************************************************************
             * *************************************************************************
              End: In case of .frm FILE
             * *************************************************************************
             * ************************************************************************ */

            if ($member->isLogedIn()) {
                ?>

                <div class="topbar">

                    <!-- LOGO -->
                    <div class="topbar-left">
                        <div class="text-center">
                            <a href="index.html" class="logo"><i class="icon-magnet icon-c-logo"></i><span>IMEI<i class="md md-album"></i>PK</span></a>
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




                                <ul class="nav navbar-nav navbar-right pull-right">


                                    <li class="hidden-xs">
                                        <a href="#" id="btn-fullscreen" class="waves-effect"><i class="icon-size-fullscreen"></i></a>
                                    </li>

                                    <li class="dropdown">
                                        <a href="" class="dropdown-toggle profile waves-effect" data-toggle="dropdown" aria-expanded="true"><img src="<?php echo CONFIG_PATH_PANEL; ?>assets/images/users/avatar-1.jpg" alt="user-img" class="img-circle"> </a>
                                        <ul class="dropdown-menu">
                                            <li><a href="<?php echo CONFIG_PATH_SITE_USER; ?>account_details.html"><i class="ti-user m-r-5"></i><?php echo $lang->get('menu_profile') ?></a></li>

                                            <?php
                                            if ($member->getAPIAccess() == 1) {
                                                ?>
                                                <li><a href="<?php echo CONFIG_PATH_SITE_USER; ?>api.html"><i class="ti-thought m-r-5"></i><?php echo $lang->get('menu_api_info') ?></a></li>
                                                <?php
                                            }
                                            ?>
                                            <li><a class="ti-list-ol m-r-5" href="<?php echo CONFIG_PATH_SITE_USER; ?>login_history.html"><?php echo $lang->get('menu_login_log') ?></a></li>
                                            <li><a class="ti-settings m-r-5" href="<?php echo CONFIG_PATH_SITE_USER; ?>account_change_password.html"><?php echo $lang->get('menu_change_password') ?></a></li>
                                            <li><a class="ti-file m-r-5" href="<?php echo CONFIG_PATH_SITE_USER; ?>credits_invoice.html"><?php echo $lang->get('menu_invoices') ?></a></li>
                                            <li><a class="ti-list-ol m-r-5" href="<?php echo CONFIG_PATH_SITE_USER; ?>credits_history.html"><?php echo $lang->get('menu_credit_log') ?></a></li>
                                            <li><a class="ti-power-off m-r-5" href="<?php echo CONFIG_PATH_SITE_USER; ?>log_out.do"><?php echo $lang->get('link_log_out') ?></a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                            <!--/.nav-collapse -->
                        </div>
                    </div>
                </div>
               <!-- Top Bar End -->


            <!-- ========== Left Sidebar Start ========== -->
                    <div class="left side-menu">
                        <div class="sidebar-inner slimscrollleft">
                            <!--- Divider -->
                            <div id="sidebar-menu">
                                <ul>

                                    <li class="text-muted menu-title">Navigation</li>

                                    <li class="has_sub">
                                        <a class="active" href="<?php echo CONFIG_PATH_SITE_USER; ?>dashboard.html"> <i class="ti-home"></i><span> <?php echo $lang->get('menu_dashboard') ?> </span></a>
                                        
                                    </li>
                                    <?php
                                    if ($service_imei == "1") {
                                        ?>
                                        <li class="has_sub">
                                            <a href="javascript:void(0);" class="waves-effect waves-light"><i class="ti-share"></i><span>Services</span></a>
                                            <ul>
                                                <li class="has_sub">
                                                    <a href="javascript:void(0);" class="waves-effect waves-light"><span><?php echo $lang->get('menu_iMEI_service') ?></span> </a>
                                                    <ul style="">

                                                        <li><a href="<?php echo CONFIG_PATH_SITE_USER; ?>imei_submit.html"><b><?php echo $lang->get('menu_place_order') ?></b></a></li>
                                                        <li><a href="<?php echo CONFIG_PATH_SITE_USER . 'imei.html?type=all'; ?>"><?php echo $lang->get('menu_order_history') ?></a></li>
                                                    </ul>
                                                </li>
                                                <?php
                                            }
                                            if ($service_file == "1") {
                                                ?>
                                                <li class="dropdown">

                                                <li class="has_sub">
                                                    <a href="javascript:void(0);" class="waves-effect waves-light"><span><?php echo $lang->get('menu_file_service') ?></span> </a>
                                                    <ul style="">
                                                        <li><a href="<?php echo CONFIG_PATH_SITE_USER; ?>file_submit.html"><b></i> <?php echo $lang->get('menu_place_order') ?></b></a></li>
                                                        <li><a href="<?php echo CONFIG_PATH_SITE_USER . 'files.html?type=all'; ?>"><?php echo $lang->get('menu_order_history') ?></a></li>

                                                    </ul>
                                                </li>
                                        </li>
                                        <?php
                                    }
                                    if ($service_logs == "1") {
                                        ?>

                                        <li class="dropdown">

                                        <li class="has_sub">
                                            <a href="javascript:void(0);" class="waves-effect waves-light"><span><?php echo $lang->get('menu_log_service') ?></span> </a>
                                            <ul style="">
                                                <li><a href="<?php echo CONFIG_PATH_SITE_USER; ?>server_logs_submit.html"><b> <?php echo $lang->get('menu_place_order') ?></b></a></li>
                                                <li><a href="<?php echo CONFIG_PATH_SITE_USER; ?>server_logs.html?type=all"><?php echo $lang->get('menu_order_history') ?></a></li>

                                            </ul>
                                        </li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                                </li>
                                <li class="has_sub">
                                    <a href="#" class="waves-effect waves-light"><i class="ti-menu-alt"></i><span>Funds </span></a>
                                    <ul class="list-unstyled">
                                        <li><a href="<?php echo CONFIG_PATH_SITE_USER; ?>credits_purchase.html"><b><?php echo $lang->get('menu_add_funds') ?></b></a></li>
                                        <li><a href="<?php echo CONFIG_PATH_SITE_USER; ?>credits_reqeusts.html"><?php echo $lang->get('menu_fund_requests') ?></a></li>
                                        <li class="divider"></li>

                                        <li class="divider"></li>
                                        <li><a href="<?php echo CONFIG_PATH_SITE_USER; ?>credits_transfer.html"><?php echo $lang->get('menu_fund_transfer') ?></a></li>
                                    </ul>
                                </li>              
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                
 <!-- Left Sidebar End -->



            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
                <div class="container">
                    <div class="content-page">
                        <!-- Start content -->
                        <div class="content">
                            <div class="container">
                                <?php
                                if (file_exists(CONFIG_PATH_USER_ABSOLUTE . $page . '.php')) {
                                    include(CONFIG_PATH_USER_ABSOLUTE . $page . '.php');
                                } else {
                                    echo '<br /><br /><h1 class="text-danger text-center">Error:404 Page Not Found!</h1><br /><br /><br /><br /><br />';
                                }
                                ?>
                            </div>
                            <footer class="footer text-right">
                                <p class="wow fadeInUp" style=" color: black"><?php $lang->prints('lbl_copyright'); ?><?php echo CONFIG_SITE_TITLE; ?>. <?php $lang->prints('lbl_all_rights_reserved'); ?>.</p>

                            </footer>
                        </div></div>
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
        
        <script src="<?php echo CONFIG_PATH_THEME; ?>js/superfish.js"></script>
        
        
            <script>
            var resizefunc = [];
        </script>

        <!-- jQuery  -->
        <script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/jquery.min.js"></script>
        <script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/bootstrap.min.js"></script>
        <script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/detect.js"></script>
        <script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/fastclick.js"></script>

        <script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/jquery.slimscroll.js"></script>
        <script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/jquery.blockUI.js"></script>
        <script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/waves.js"></script>
        <script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/wow.min.js"></script>
        <script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/jquery.nicescroll.js"></script>
        <script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/jquery.scrollTo.min.js"></script>

       

        <script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/jquery.core.js"></script>
        <script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/jquery.app.js"></script>

        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('.counter').counterUp({
                    delay: 100,
                    time: 1200
                });

                $(".knob").knob();

            });
        </script>


        
        
    </body>

</html>