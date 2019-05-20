<?php
$strMsg = $request->getStr('msg');
$reply = $request->getStr('reply');
if ($page != 'index' && $page != 'sign-up' && $page != 'terms') {
    if (!$member->isLogedIn()) {
        // header('location:' . CONFIG_PATH_SITE_USER . 'index.html?msg=' . urlencode('Your session expired!'));
        header("location:" . CONFIG_PATH_SITE . "login.html?reply=" . urlencode("session_exp"));
    }
}
$data = array();
$lang = new language($data);
$data['common']['doc_title'] = CONFIG_SITE_NAME;

$lang = 'en';
$sql = 'select * from ' . LANGUAGE_MASTER . ' where language_default=1';
$query = $mysql->query($sql);
if ($mysql->rowCount($query) > 0) {
    $rows = $mysql->fetchArray($query);
    $lang = $rows[0]['file_name'];
}

$incPathLangAbsolute = CONFIG_PATH_USER_LANG_ABSOLUTE;

$sql = 'select * from ' . LANGUAGE_MASTER . ' where id=' . $language;
$sql = 'select b.file_name from ' . USER_MASTER . ' a
left join ' . LANGUAGE_MASTER . ' b

on a.language_id=b.id

where a.id=' . $mysql->getInt($member->getUserId());
$query = $mysql->query($sql);
if ($mysql->rowCount($query) > 0) {
    $rows = $mysql->fetchArray($query);
    $language = $rows[0]['file_name'];
}



$lang = ($language != '') ? $language : $lang;

// Get default language fields before loading the page
$langfieldefault = $lang . '.php';
//echo $incPathLangAbsolute . $langfieldefault;exit;
if (file_exists($incPathLangAbsolute . $langfieldefault)) {
    include($incPathLangAbsolute . $langfieldefault);

    //echo $incPathLangAbsolute . $langfieldefault;
}
// Get language fields before loading the page
$langFile = $lang . '/' . $fileName;
//echo $incPathLangAbsolute . $langFile;exit;
if (file_exists($incPathLangAbsolute . $langFile)) {
    //echo($incPathLangAbsolute . $langFile);
    include($incPathLangAbsolute . $langFile);
}
//Load Language Object after fetting Language vals
//var_dump($data);
$lang = new language($data);
$service_imei = $service_file = $service_logs = $service_prepaid = $user_type = 0;

if ($member->isLogedIn()) {
    $sql_auth = 'select service_imei, service_file, service_logs,api_access, service_prepaid, user_type from ' . USER_MASTER . ' where id=' . $member->getUserId();
    $query_auth = $mysql->query($sql_auth);
    $rows_auth = $mysql->fetchArray($query_auth);
    $service_imei = $rows_auth[0]['service_imei'];
    $service_file = $rows_auth[0]['service_file'];
    $service_logs = $rows_auth[0]['service_logs'];
    $service_prepaid = $rows_auth[0]['service_prepaid'];
    $api_access = $rows_auth[0]['api_access'];

    $user_type = $rows_auth[0]['user_type'];


    $sqlgetmsg = 'select sum((select count(b.a_id) countms from nxt_chat_pool b where b.isview=0
                and b.entry_type="user" 
                and b.admin_id=a.id
                and b.user_id=' . $member->getUserId() . ' )) msgcount 
                from nxt_admin_master a 
                where a.`status`=1';
    $query_auth = $mysql->query($sqlgetmsg);
    $rows_auth = $mysql->fetchArray($query_auth);
    $newmsgss = $rows_auth[0]['msgcount'];
    //echo $newmsgss;exit;
}
$crM = $objCredits->getMemberCredits();
$credits = $crM['credits'];
$usercussign=  $crM['prefix'];
$creditsUsed = $crM['used'];
$creditsProcess = $crM['process'];
$ovd_c_limit = $crM['ovd_c_limit'];

$sqlnotify = 'select a.notify from ' . USER_MASTER . ' a where a.id=' . $member->getUserid();
$query_reg = $mysql->query($sqlnotify);
$notifydata = $mysql->fetchArray($query_reg);
$notifyyesno = $notifydata[0]['notify'];


// get all the notify of pricess
$IS_NOTI = 0;
$sqlnotifyprice = 'select a.id, b.tool_name as tool,a.price,"IMEI" as type,cast(a.time_stamp as date) datee from nxt_price_notify a 
inner join nxt_imei_tool_master b on a.tool_id=b.id 
where a.display=1 and a.type=1 and a.user=' . $member->getUserid() . ' 
union 
select a.id, c.service_name as tool,a.price,"FILE" as type,cast(a.time_stamp as date) datee from nxt_price_notify a 
inner join nxt_file_service_master c on a.tool_id=c.id
where a.display=1 and a.type=2 and a.user=' . $member->getUserid() . '

union 
select a.id, d.server_log_name as tool,a.price,"SERVER" as type,cast(a.time_stamp as date) datee from nxt_price_notify a 
inner join nxt_server_log_master d  on a.tool_id=d.id
where a.display=1 and a.type=3 and a.user=' . $member->getUserid() . '


union 
select a.id, e.prepaid_log_name as tool,a.price,"PREPAID" as type,cast(a.time_stamp as date) datee from nxt_price_notify a 
inner join nxt_prepaid_log_master e on a.tool_id=e.id
where a.display=1 and a.type=4 and a.user=' . $member->getUserid();

$query_price_notify = $mysql->query($sqlnotifyprice);
if ($mysql->rowCount($query_price_notify) > 0) {
    $newnotifications = array();
    $IS_NOTI = 1;
    $newnotifications = $mysql->fetchArray($query_price_notify);
}

// chat code

$sqlchatcode = 'select chat_code from ' . SMTP_CONFIG;
$sqlchatcodedata = $mysql->query($sqlchatcode);
$sqlchatcodedata1 = $mysql->fetchArray($sqlchatcodedata);
$chat_window_code = $sqlchatcodedata1[0]['chat_code'];


$chat_window = FALSE;
if ($chat_window_code != "")
    $chat_window = TRUE;
//$chat_window_code='';
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
        <meta name="description" content="Mobile Unlocking Site">
        <meta name="keywords" content="Iphone,imei">
        <meta name="author" content="GsmUnion Dev">

        <link rel="shortcut icon" href="<?php echo CONFIG_PATH_SITE; ?>favicon.ico">

        <title><?php echo CONFIG_SITE_TITLE; ?></title>

        <!--Morris Chart CSS -->
        <link rel="stylesheet" href="<?php echo CONFIG_PATH_PANEL; ?>assets/plugins/morris/morris.css">

        <link href="<?php echo CONFIG_PATH_PANEL; ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo CONFIG_PATH_PANEL; ?>assets/plugins/bootstrapvalidator/src/css/bootstrapValidator.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo CONFIG_PATH_PANEL; ?>assets/css/core2.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo CONFIG_PATH_PANEL; ?>assets/css/components.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo CONFIG_PATH_PANEL; ?>assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo CONFIG_PATH_PANEL; ?>assets/css/pages.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo CONFIG_PATH_PANEL; ?>assets/css/responsive.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo CONFIG_PATH_PANEL; ?>assets/css/menu.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo CONFIG_PATH_PANEL; ?>assets/plugins/sweetalert/dist/sweetalert.css" rel="stylesheet" type="text/css">
        <link href="<?php echo CONFIG_PATH_PANEL; ?>assets/plugins/switchery/dist/switchery.min.css" rel="stylesheet" />
        <link href="<?php echo CONFIG_PATH_PANEL; ?>assets/plugins/select2/select2.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo CONFIG_PATH_PANEL; ?>assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />

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
							  <button data-dismiss="modal" class="btn btn-default" type="button">' . $admin->wordTrans($admin->getUserLang(), 'Close') . '</button>
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

        if ($IS_NOTI == 1) {
            // echo '<pre>';
            //   var_dump($newnotifications);exit;

            $phpnotify = '  <div class="modal fade" id="myModal2" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
         
          <h4 class="modal-title">New Price Notifications</h4>
        </div>
        <div class="modal-body">
        
		   <table class="table table-striped ">
    <thead>
      <tr>
       <th>Date</th>
        <th>Service</th>
        <th>New Price</th>
      </tr>
    </thead>
    <tbody>
    ';

            $idstohide = '';
            foreach ($newnotifications as $abc) {
                $idstohide.=$abc['id'] . ',';


                $phpnotify.='
	 <tr>
          <td>' . $abc['datee'] . '</td>
        <td>' . $abc['tool'] . '</td>
        <td>'.$usercussign.' '.$abc['price'] . '</td>      
      </tr>';
            }
            $idstohide = rtrim($idstohide, ',');
            //  echo $idstohide;exit;
            $phpnotify.='
    </tbody>
  </table>
		  
		  
        </div>
        <div class="modal-footer">
          <button type="button" id="clsbtn" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>';
            echo $phpnotify;
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
                echo '<br /><br /><h1 class="text-danger text-center">' . $admin->wordTrans($admin->getUserLang(), 'Error:404 Page Not Found!') . '</h1><br /><br /><br /><br /><br />';
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
                        <?php
                        $sql = 'select * from ' . CMS_MENU_MASTER . ' where id = 1';

                        $query = $mysql->query($sql);

                        $rowCount = $mysql->rowCount($query);

                        $rows = $mysql->fetchArray($query);

                        $row = $rows[0];

                        if ($row['logo'] != "") {

                            $logo = '<img src="' . CONFIG_PATH_THEME_NEW . 'site_logo/' . $row['logo'] . '" class="" style=""/>';
                        } else {

                            $logo = CONFIG_SITE_TITLE;
                        }
                        ?>
                        <div class="logo-gsm">
                            <a href="index.html" class="logo-gsm"><span><?php echo $logo; ?></span></a>
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

                            <ul class="nav navbar-nav navbar-right pull-right ">


                                <li style="margin-top: 10px">
                                    <span class="badge badge-success small currentBalance"><?php echo $admin->wordTrans($admin->getUserLang(), 'Credits:') ?>  <?php echo $credits; ?><a class="fa fa-credit-card adFund" href="<?php echo CONFIG_PATH_SITE_USER; ?>credits_purchase.html" title="Add Fund"></a></span>

                                </li>
                                <li style="margin-top: 10px">
                                    <span class="badge badge-success small addFund hidden-sm hidden-xs"><a href="<?php echo CONFIG_PATH_SITE_USER; ?>credits_purchase.html"><i class="fa fa-plus-circle"></i> <?php echo $admin->wordTrans($admin->getUserLang(), 'Add Fund') ?></a></span>

                                </li>
                                <li style="margin-top: 10px">
                                    <span class="btn btn-primary m-r-10 m-b-10 topBtn hidden-sm hidden-xs" type="button"><a href="<?php echo CONFIG_PATH_SITE_USER; ?>dashboard.html"><?php echo $member->getUserName(); ?></a></span>
                                </li>
                                <li style="margin-top: 10px">
                                    <span class="btn btn-primary m-r-10 m-b-10 topBtn hidden-sm hidden-xs" type="button"><a href="<?php echo CONFIG_PATH_SITE_USER; ?>#"><?php echo $admin->wordTrans($admin->getUserLang(), 'Products & Services') ?></a></span>
                                </li>
                                <li style="margin-top: 10px">
                                    <span class="btn btn-primary m-r-10 m-b-10 topBtn hidden-sm hidden-xs" type="button"><a href="<?php echo CONFIG_PATH_SITE_USER; ?>log_out.do"> <?php echo $admin->wordTrans($admin->getUserLang(), 'Log Out') ?></a></span>
                                </li>
                                <?php
                                // get the online status
                                // $sql = "select a.id,a.username,a.online from " . ADMIN_MASTER . " a where a.`status`=1";
                                $sql = 'select a.online,a.notify,a.img from ' . USER_MASTER . ' a where a.id=' . $member->getUserId();

                                //$result = $mysql->getResult($sql);
                                $qrydata = $mysql->query($sql);
                                if ($mysql->rowCount($qrydata) > 0) {
                                    $rows = $mysql->fetchArray($qrydata);
                                    $adminchatstatus = $rows[0]['online'];
                                    $adminnotify = $rows[0]['notify'];
                                    $userpic = $rows[0]['img'];

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

                                <li class="dropdown" style="display:none">
                                    <a href="" class="dropdown-toggle waves-effect waves-light profile" data-toggle="dropdown" aria-expanded="true">
                                        <?php if ($userpic != '') { ?>

                                            <img src="<?php echo CONFIG_PATH_SITE; ?>images/<?php echo $userpic; ?>" alt="User" class="img-circle">
                                        <?php } else {
                                            ?>

                                            <img src="<?php echo CONFIG_PATH_PANEL; ?>assets/images/users/avatar-2.jpg" alt="user-img" class="img-circle"> 
                                            <?php
                                        }
                                        ?>


                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a href="<?php echo CONFIG_PATH_SITE_USER; ?>account_details.html"><i class="ti-user m-r-5"></i><?php echo $admin->wordTrans($admin->getUserLang(), 'Profile') ?></a></li>

                                        <?php
                                        if ($api_access == 1) {
                                            ?>
                                            <li><a href="<?php echo CONFIG_PATH_SITE_USER; ?>api.html"><i class="ti-thought m-r-5"></i><?php echo $admin->wordTrans($admin->getUserLang(), 'Api Info') ?></a></li>
                                            <?php
                                        }
                                        ?>
                                        <li><a class="ti-comment m-r-5" href="<?php echo CONFIG_PATH_SITE_USER; ?>chat_panel.html"> <?php echo $admin->wordTrans($admin->getUserLang(), 'Chat') ?></a></li>

                                        <li><a class="ti-list-ol m-r-5" href="<?php echo CONFIG_PATH_SITE_USER; ?>login_history.html"> <?php echo $admin->wordTrans($admin->getUserLang(), 'Login Log') ?></a></li>
                                        <li><a class="ti-export m-r-5" href="<?php echo CONFIG_PATH_SITE_USER; ?>export_data.html"> <?php echo $admin->wordTrans($admin->getUserLang(), 'Export Data') ?></a></li>
                                        <li><a class="ti-email m-r-5" href="<?php echo CONFIG_PATH_SITE_USER; ?>mail_history.html"> <?php echo $admin->wordTrans($admin->getUserLang(), 'Email Log') ?></a></li>
                                        <li><a class="ti-email m-r-5" href="<?php echo CONFIG_PATH_SITE_USER; ?>email_notify.html"> <?php echo $admin->wordTrans($admin->getUserLang(), 'Email Performance') ?></a></li>


                                        <li><a class="ti-settings m-r-5" href="<?php echo CONFIG_PATH_SITE_USER; ?>account_change_password.html"> <?php echo $admin->wordTrans($admin->getUserLang(), 'Change Password/Pin') ?></a></li>
                                        <li><a class="ti-file m-r-5" href="<?php echo CONFIG_PATH_SITE_USER; ?>credits_invoice.html"> <?php echo $admin->wordTrans($admin->getUserLang(), 'Invoices') ?></a></li>
                                        <li><a class="ti-list-ol m-r-5" href="<?php echo CONFIG_PATH_SITE_USER; ?>credits_history.html"> <?php echo $admin->wordTrans($admin->getUserLang(), 'Credit Log') ?></a></li>
                                        <li><a class="ti-power-off m-r-5" href="<?php echo CONFIG_PATH_SITE_USER; ?>log_out.do"> <?php echo $admin->wordTrans($admin->getUserLang(), 'Log Out') ?></a></li>
                                    </ul>
                                </li>
                                <?php
                                $sqlForLanguage = 'select * from ' . LANG_MASTER . ' where lang_status = 1';
                                $query = $mysql->query($sqlForLanguage);
                                if ($mysql->rowCount($query) > 0) {
                                    $languageList = $mysql->fetchArray($query);
                                    ?>
                                    <li class="dropdown  topFlg">
                                        <?php
                                        if ($admin->getUserLang() != "") {
                                            $cLanguage = '';
                                            //$fIcon = ($admin->getUserLang() == 'en' ? 'us' : $admin->getUserLang());
                                            $fIcon = $admin->getUserLang();
                                            $sql = 'select * from ' . LANG_MASTER . ' where language_code = "' . $fIcon . '"';
                                            $query = $mysql->query($sql);
                                            $flagData = $mysql->fetchArray($query);
                                            $rowCount = $mysql->rowCount($query);
                                            if ($rowCount > 0) {
                                                $lCurrentFlag = CONFIG_PATH_PANEL_ADMIN . 'assets_1/language_flag/' . $flagData[0]['language_flag'];
                                                $cLanguage = $flagData[0]['language_name'];
                                            } else {
                                                $lCurrentFlag = CONFIG_PATH_PANEL_ADMIN . 'assets_1/language_flag/default-flag.jpg';
                                            }
                                        } else {
                                            $lCurrentFlag = CONFIG_PATH_PANEL_ADMIN . 'assets_1/language_flag/en.jpg';
                                            $cLanguage = 'English';
                                            if (!file_exists($lCurrentFlag)) {
                                                $lCurrentFlag = CONFIG_PATH_PANEL_ADMIN . 'assets_1/language_flag/default-flag.jpg';
                                            }
                                        }
                                        ?>
                                        <a data-toggle="dropdown" class="dropdown-toggle" href="#" aria-expanded="false">
                                            <img src="<?php echo $lCurrentFlag; ?>">
                                            <span class="caret"></span>
                                        </a>
                                        <ul role="menu" class="dropdown-menu">
                                            <?php
                                            foreach ($languageList as $language) {
                                                $setFlagAct = '';
                                                if ($admin->getUserLang() == $language['language_code']) {
                                                    $setFlagAct = 'setFlagAct';
                                                }
                                                ?>
                                                <li>
                                                    <a class="dropdown-item setLangFlag <?php echo $setFlagAct; ?>" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>languages_edit_process.do?setLang=<?php echo $language['language_code']; ?>">
                                                        <img src="<?php echo CONFIG_PATH_PANEL_ADMIN . 'assets_1/language_flag/' . $language['language_flag']; ?>" style="width: 25%;margin-right: 5px;">
                                                        <?php echo $language['language']; ?>
                                                    </a>
                                                </li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                    </li>
                                    <?php
                                }
                                ?>
                            </ul>

                        </div>

                    </div>
                </div>
            </header>
            <div class="navbar-custom" id="topnav">
                <div class="container">
                    <h2 id="clientArea">Client Area Dashboard</h2>
                    <hr style="margin-top:7px" class="hidden-xs">
                    <div id="navigation">
                        <!-- Navigation Menu-->
                        <ul class="navigation-menu row">
                            <li class="has-submenu">
                                <a href="<?php echo CONFIG_PATH_SITE_USER; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(), 'Client Area') ?></a>

                            </li>

                            <li class="has-submenu">
                                <a href="#"><?php echo $admin->wordTrans($admin->getUserLang(), 'Order History') ?><span class="caret"></span></a>
                                <ul class="submenu megamenu">

                                    <?php
                                    if ($service_imei == "1") {
                                        ?>

                                        <li>
                                            <ul>
                                                <li><a href="<?php echo CONFIG_PATH_SITE_USER . 'imei.html?type=all'; ?>"><?php echo $admin->wordTrans($admin->getUserLang(), 'IMEI Service') ?></a></li>
                                                <li><a href="<?php echo CONFIG_PATH_SITE_USER . 'files.html?type=all'; ?>"><?php echo $admin->wordTrans($admin->getUserLang(), 'File Service') ?></a></li>
                                                <li><a href="<?php echo CONFIG_PATH_SITE_USER; ?>server_logs.html?type=all"><?php echo $admin->wordTrans($admin->getUserLang(), 'Server Service') ?></a></li>

                                            </ul>
                                        </li>
                                        <?php
                                    }
                                    ?>

                                </ul>
                            </li> 

                            <li class="has-submenu">
                                <a href="#"><?php echo $admin->wordTrans($admin->getUserLang(), 'Place Order') ?><span class="caret"></span></a>
                                <ul class="submenu">
                                    <li><a href="<?php echo CONFIG_PATH_SITE_USER; ?>imei_submit.html"><?php echo $admin->wordTrans($admin->getUserLang(), 'IMEI Service') ?></a></li>
                                    <li><a href="<?php echo CONFIG_PATH_SITE_USER; ?>file_submit.html"><?php echo $admin->wordTrans($admin->getUserLang(), 'File Service') ?></a></li>
                                    <li><a href="<?php echo CONFIG_PATH_SITE_USER; ?>server_logs_submit.html"><?php echo $admin->wordTrans($admin->getUserLang(), 'Server Service') ?></a></li>
                                    <li class="divider"></li>

                                    <li class="divider"></li>
    <!--                                        <li><a href="<?php echo CONFIG_PATH_SITE_USER; ?>credits_transfer.html"><?php echo $admin->wordTrans($admin->getUserLang(), 'Fund Transfer') ?></a></li>-->
                                </ul>
                            </li>

                            <li class="has-submenu">
                                <a href="#"><?php echo $admin->wordTrans($admin->getUserLang(), 'My Account') ?><span class="caret"></span></a>
                                <ul class="submenu">
                                    <li><a class="ti-user m-r-5" href="<?php echo CONFIG_PATH_SITE_USER; ?>account_details.html"> <?php echo $admin->wordTrans($admin->getUserLang(), 'Profile') ?></a></li>

                                    <?php
                                    if ($api_access == 1) {
                                        ?>
                                        <li><a class="ti-link m-r-5" href="<?php echo CONFIG_PATH_SITE_USER; ?>api.html"><?php echo $admin->wordTrans($admin->getUserLang(), 'Api Info') ?></a></li>
                                        <?php
                                    }
                                    ?>
                                    <li><a class="ti-comment m-r-5" href="<?php echo CONFIG_PATH_SITE_USER; ?>chat_panel.html"> <?php echo $admin->wordTrans($admin->getUserLang(), 'Chat') ?></a></li>

                                    <li><a class="ti-key m-r-5" href="<?php echo CONFIG_PATH_SITE_USER; ?>login_history.html"> <?php echo $admin->wordTrans($admin->getUserLang(), 'Login Log') ?></a></li>
                                    <li><a class="ti-export m-r-5" href="<?php echo CONFIG_PATH_SITE_USER; ?>export_data.html"> <?php echo $admin->wordTrans($admin->getUserLang(), 'Export Data') ?></a></li>
                                    <li><a class="ti-email m-r-5" href="<?php echo CONFIG_PATH_SITE_USER; ?>mail_history.html"> <?php echo $admin->wordTrans($admin->getUserLang(), 'Email Log') ?></a></li>
                                    <li><a class="ti-id-badge m-r-5" href="<?php echo CONFIG_PATH_SITE_USER; ?>email_notify.html"> <?php echo $admin->wordTrans($admin->getUserLang(), 'Email Performance') ?></a></li>


                                    <li><a class="ti-settings m-r-5" href="<?php echo CONFIG_PATH_SITE_USER; ?>account_change_password.html"> <?php echo $admin->wordTrans($admin->getUserLang(), 'Change Password/Pin') ?></a></li>
                                    <li><a class="ti-file m-r-5" href="<?php echo CONFIG_PATH_SITE_USER; ?>credits_invoice.html"> <?php echo $admin->wordTrans($admin->getUserLang(), 'Invoices') ?></a></li>
                                    <li><a class="ti-credit-card m-r-5" href="<?php echo CONFIG_PATH_SITE_USER; ?>credits_history.html"> <?php echo $admin->wordTrans($admin->getUserLang(), 'Credit Log') ?></a></li>
                                    <li><a class="ti-power-off m-r-5" href="<?php echo CONFIG_PATH_SITE_USER; ?>log_out.do"> <?php echo $admin->wordTrans($admin->getUserLang(), 'Log Out') ?></a></li>

                                </ul>
                            </li>
                            <li class="has-submenu">
                                <a href="#"><?php echo $admin->wordTrans($admin->getUserLang(), 'Support') ?><span class="caret"></span></a>
                                <ul class="submenu">
                                    <li><a href="<?php echo CONFIG_PATH_SITE_USER; ?>chat_panel.html"><b><?php echo $admin->wordTrans($admin->getUserLang(), 'Online Chat') ?></b></a></li>

                                    <li><a href="<?php echo CONFIG_PATH_SITE_USER; ?>ticket.html"><?php echo $admin->wordTrans($admin->getUserLang(), 'Tickets') ?></a></li>

                                </ul>
                            </li>

                            <?php
                            if ($user_type != 0) {
                                ?>
                                <li class="has-submenu">
                                    <a href="#"><?php echo $admin->wordTrans($admin->getUserLang(), 'Users') ?><span class="caret"></span></a>
                                    <ul class="submenu">

                                        <li><a href="<?php echo CONFIG_PATH_SITE_USER; ?>users.html"><?php echo $admin->wordTrans($admin->getUserLang(), 'User Manage') ?></a></li>
                                        <li><a href="<?php echo CONFIG_PATH_SITE_USER; ?>user_add.html"><?php echo $admin->wordTrans($admin->getUserLang(), 'User Add') ?></a></li>

                                    </ul>
                                </li>

                                <?php
                            }
                            ?>
                            <li class="visible-xs">
                                <a class="ti-power-off m-r-5" href="<?php echo CONFIG_PATH_SITE_USER; ?>log_out.do"> <?php echo $admin->wordTrans($admin->getUserLang(), 'Log Out') ?></a>
                            </li>
                        </ul>
                        <!-- End navigation menu        -->
                    </div>
                </div>
            </div>
            <div class="wrapper">
                <!-- ============================================================== -->
                <!-- Start right Content here -->
                <!-- ============================================================== -->
                <div class="container">


                    <?php
                    if (file_exists(CONFIG_PATH_USER_ABSOLUTE . $page . '.php')) {
                        include(CONFIG_PATH_USER_ABSOLUTE . $page . '.php');
                    } else {
                        echo '<br /><br /><h1 class="text-danger text-center">' . $admin->wordTrans($admin->getUserLang(), 'Error:404 Page Not Found!') . '</h1><br /><br /><br /><br /><br />';
                    }
                    ?>



                    <footer class="footer text-right">
                        <!--                        <div class="container">
                                                    <div class="row">
                                                        <div class="col-xs-6">
                        <?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_copyright')); ?><?php echo CONFIG_SITE_TITLE; ?>. <?php $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_all_rights_reserved')); ?>.
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
                                                </div>-->
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
        <script type="text/javascript" src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/notify.min.js"></script>
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
        <script src="<?php echo CONFIG_PATH_PANEL; ?>assets/plugins/switchery/dist/switchery.min.js"></script>
        <script src="<?php echo CONFIG_PATH_PANEL; ?>assets/plugins/bootstrap-filestyle/src/bootstrap-filestyle.min.js"></script>
        <script type="text/javascript" src="<?php echo CONFIG_PATH_PANEL; ?>assets/plugins/parsleyjs/dist/parsley.min.js"></script>
        <script src="<?php echo CONFIG_PATH_PANEL; ?>assets/plugins/select2/select2.min.js" type="text/javascript"></script>
        <script src="<?php echo CONFIG_PATH_PANEL; ?>assets/plugins/bootstrap-select/dist/js/bootstrap-select.min.js" type="text/javascript"></script>
        <script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/jquery.core.js"></script>
        <script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/jquery.app2.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo CONFIG_PATH_EXTRA; ?>bootstrap-datepicker/css/datepicker.css" />
        <script type="text/javascript" src="<?php echo CONFIG_PATH_EXTRA; ?>bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {

                $('form').parsley();
                var totalmsg =<?php echo $newmsgss; ?>;
                var noti = <?php echo $notifyyesno; ?>;

                var pagename = location.pathname.substring(location.pathname.lastIndexOf("/") + 1);
                if (totalmsg != 0 && pagename != 'chat.html' && pagename != 'chat_panel.html' && noti == 1)
                    $.Notification.notify('custom', 'top right', 'Chat Notification', 'You got some New Messages');
                // alert(totalmsg);


                var isnoti = <?php echo $IS_NOTI; ?>;
                //   alert(isnoti);
                if (isnoti == 1)
                {
                    $('#myModal2').modal('show');
                    // alert('some');
                }

            });

            $('#clsbtn').on('click', function () {
                // window.alert('hidden event fired!');
                var tempval = '<?php echo $idstohide;?>';
                //  alert(tempval);
                //hide notification
                $.ajax({
                    type: "POST",
                    url: '<?php echo CONFIG_PATH_SITE_USER; ?>' + '_ajax_off.do',
                    data: "&id=" + tempval,
                    error: function () {
                        // alert("Some Error Occur");
                    }, success: function (msg) {

                    }
                });
            });

        </script>


        <script>
            // setPathsMember('', '<?php echo CONFIG_PATH_SITE_USER; ?>');

            // setPathsMember('', '<?php echo CONFIG_PATH_SITE_USER; ?>');
            function setStatus(a)
            {
                // alert(a);
                if (a == 1)
                {
                    var onlinestatus = $('#onlinestatus').prop('checked');
                    //   alert(onlinestatus);
                    var adminid =<?php echo $member->getUserId(); ?>;
                    // ajax call 
                    $.ajax({
                        type: "POST",
                        url: config_path_site_user + "_set_online_status.do",
                        data: "&a_id=" + adminid + "&ostat=" + onlinestatus,
                        error: function () {
                            //  alert("Some Error Occur");
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
                    //  alert(onlinestatus);
                    var adminid =<?php echo $member->getUserId(); ?>;
                    // ajax call 
                    $.ajax({
                        type: "POST",
                        url: '<?php echo CONFIG_PATH_SITE_USER; ?>' + "_set_online_status.do",
                        data: "&a_id=" + adminid + "&ostat=" + onlinestatus + "&type=" + a,
                        error: function () {
                            //  alert("Some Error Occur");
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
            setInterval(settime, 15000);
            function settime()
            {
                $.ajax({
                    type: "POST",
                    url: '<?php echo CONFIG_PATH_SITE_USER; ?>' + "_update_user_time.do",
                    data: "",
                    error: function () {
                        //alert("Some Error Occur");
                    },
                    success: function (msg) {
                        //   alert(msg);
                    }
                });
            }
        </script>
        <script>
            $(document).ready(function (e) {
                $(document).on('click', '.setLangFlag', function (e) {
                    e.preventDefault();
                    var _url = $(this).attr('href');

                    $.ajax({
                        url: _url,
                        data: {},
                        type: "POST",
                        dataType: "json",
                    }).done(function (resp) {
                        if (resp.status == 1) {
                            location.reload();
                        } else {
                            alert('something went wrong.');
                        }
                    }).fail(function (xhr, status, errorThrown) {
                    }).always(function (xhr, status) {
                    });
                });
            });
        </script>
        <script>
            $(function () {
                // this will get the full URL at the address bar
                var url = window.location.href;

                // passes on every "a" tag 
                $("#navigation a").each(function () {
                    // checks if its the same on the address bar
                    if (url == (this.href)) {
                        $(this).closest("li").addClass("active");
                        $(this).closest(".has-submenu").addClass("active");
                    }
                });
            });
        </script>

        <?php
        if ($chat_window == TRUE)
// echo '<script type="text/javascript">';
            echo $chat_window_code;
//  echo '</script>';
        ?>
    </body>

</html>