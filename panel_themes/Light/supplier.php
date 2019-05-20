<?php
	$strMsg = $request->getStr('msg');
	$reply =  $request->getStr('reply');

	$langCode = array();
	$sql_lang = 'select lang_code from ' . LANGUAGE_DETAILS;
	$query_lang = $mysql->query($sql_lang);
	if($mysql->rowCount($query_lang)>0)
	{
		$rows = $mysql->fetchArray($query_lang);
		$i =1;
		foreach($rows as $row)
		{
			$langCode[$i++] = trim($row['lang_code']);
		}
	}
	//print_r($langCode);
	//print_r($data);
	//exit();
	/*$i=0;
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
	}*/

	
?><!doctype html>
<html lang="us">
<head>
	<meta charset="utf-8">
	<title>Supplier Panel</title>
	<link rel="icon" type="image/ico" href="<?php echo CONFIG_PATH_SITE; ?>favicon.ico">
	
    <!-- Bootstrap core CSS -->
    <link href="<?php echo CONFIG_PATH_PANEL; ?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo CONFIG_PATH_PANEL; ?>css/bootstrap-reset.css" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo CONFIG_PATH_THEME; ?>css/style_panel.css">
	<link rel="stylesheet" href="<?php echo CONFIG_PATH_THEME; ?>css/animate.css">
	

	
    <!--external css-->
    <link href="<?php echo CONFIG_PATH_ASSETS; ?>font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href="<?php echo CONFIG_PATH_ASSETS; ?>jquery-easy-pie-chart/jquery.easy-pie-chart.css" rel="stylesheet" type="text/css" media="screen"/>
    <link rel="stylesheet" href="<?php echo CONFIG_PATH_PANEL; ?>css/owl.carousel.css" type="text/css">
	
	
    <!-- Custom styles for this template -->
    <link href="<?php echo CONFIG_PATH_PANEL; ?>css/preset.css" rel="stylesheet">
    <link href="<?php echo CONFIG_PATH_PANEL; ?>css/style.css" rel="stylesheet">
    <link href="<?php echo CONFIG_PATH_PANEL; ?>css/style-responsive.css" rel="stylesheet" />

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
      <script src="<?php echo CONFIG_PATH_PANEL; ?>js/html5shiv.js"></script>
      <script src="<?php echo CONFIG_PATH_PANEL; ?>js/respond.min.js"></script>
    <![endif]-->
	
    <!-- js placed at the end of the document so the pages load faster -->
    <script src="<?php echo CONFIG_PATH_PANEL; ?>js/jquery.js"></script>
    <!-- <script src="<?php echo CONFIG_PATH_PANEL; ?>js/jquery-1.8.3.min.js"></script> -->
    <script src="<?php echo CONFIG_PATH_PANEL; ?>js/bootstrap.min.js"></script>
	
    <script src="<?php echo CONFIG_PATH_PANEL; ?>js/init.js" ></script>
    <script src="<?php echo CONFIG_PATH_PANEL; ?>js/init_mmember.js" ></script>
	
	<?php
		if (!defined("net_unavail"))
		{
			echo "<link href='http://fonts.googleapis.com/css?family=Open+Sans:300' rel='stylesheet' type='text/css'>";
			echo "<link href='http://fonts.googleapis.com/css?family=Lato:400,100' rel='stylesheet' type='text/css'>";
		}
	?>
	
	

	<!-- <link href="<?php echo CONFIG_PATH_ASSETS; ?>chosen/chosen.css" rel="stylesheet" type="text/css" /> -->
	<link href="<?php echo CONFIG_PATH_ASSETS; ?>chosen/chosen-bootstrap.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="<?php echo CONFIG_PATH_ASSETS; ?>chosen/chosen.jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo CONFIG_PATH_PANEL; ?>js/hover-dropdown.js"></script>



<!--[if (gt IE 9)|!(IE)]><!-->
<script src="<?php echo CONFIG_PATH_THEME; ?>js/wow/wow.js"></script>
<script src="<?php echo CONFIG_PATH_THEME; ?>js/wow/device.min.js"></script>
<script>
    $(document).ready(function () {       
      if ($('html').hasClass('desktop')) {
        new WOW().init();
      }   
    });
</script>
	

</head>


<body class="full-width" style="font-family:Arial;font-size: 14px">
<section id="container">
	<?php
		if(isset($data['lang'][$reply]))
		{
			echo '<div id="messagebox" title="Note!">' . $data['lang'][$reply] . '</div>';
		}
		
		if($supplier->isLogedIn())
		{
			$sql_auth = 'select service_imei, service_file, service_logs from ' . SUPPLIER_MASTER . ' where id=' . $supplier->getUserId();
			$query_auth = $mysql->query($sql_auth);
			$rows_auth = $mysql->fetchArray($query_auth);
			$service_imei = $rows_auth[0]['service_imei'];
			$service_file = $rows_auth[0]['service_file'];
			$service_logs = $rows_auth[0]['service_logs'];
		}
		
		if($supplier->isLogedIn())
		{
	?>

<header class="clearfix">
    <h1 class="navbar-brand navbar-brand_ wow fadeInLeft" data-wow-delay="0.2s"><a href="index.html"><img src="<?php echo CONFIG_PATH_THEME; ?>img/logo.png" alt="logo"></a></h1>
    <nav class="navbar navbar-default navbar-static-top tm_navbar clearfix" role="navigation">
        <ul class="nav sf-menu clearfix">
            <li><a href="<?php echo CONFIG_PATH_SITE_SUPPLIER;?>dashboard.html"><?php $lang->prints('navi_dashboard'); ?></a></li>
            
           

            <li class="sub-menu"><a href="#">Services</a><span class="fa fa-angle-down"></span>
                <ul class="submenu">
						<?php
							if($service_imei == "1")
							{
						?>
							<li class="dropdown">
                                                                 
                                                        <a href="<?php echo CONFIG_PATH_SITE_SUPPLIER;?>order_imei.html?type=pending"><?php $lang->prints('navi_imei_orders'); ?></a>
								
                                                        </li>
						<?php
							}
						
						?>
						<?php
							
							if($service_file== "1")
							{
						?>
							
						?>
							<li class="dropdown">
                                                                 
                                                        <a href="<?php echo CONFIG_PATH_SITE_SUPPLIER;?>order_file.html?type=pending"><?php $lang->prints('navi_file_orders'); ?></a>
								
                                                        </li>
						<?php
							}
							if($service_logs == "1")
							{
							//*
						?>
								<li class="dropdown">
                                                                 
                                                        <a href="<?php echo CONFIG_PATH_SITE_SUPPLIER;?>order_server_log.html?type=pending"><?php $lang->prints('navi_server_logs_orders'); ?></a>
								
                                                        </li>
						<?php
							}
						?>
                                                        
				</ul>
                                                        <li class="dropdown">
                                                                 
					  <li><a href="<?php echo CONFIG_PATH_SITE_SUPPLIER;?>log_out.do"><?php $lang->prints('com_sign_out'); ?></a></li>
								
                                                        </li>
			</li>
                        
         

        </ul>
    </nav>
</header>
<?php
		}
	?>
<!--content-->
<div class="content" style="padding-top:20px;">
	<div class="container">
	<?php
				if(isset($data['lang'][$reply]))
				{
					echo '
							<div class="alert alert-success">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								' . $data['lang'][$reply] . '
							</div>';
					//echo '<div id="messagebox" title="Note!">' . $data['lang'][$reply] . '</div>';
				}
				if(file_exists(CONFIG_PATH_SUPPLIER_ABSOLUTE . $page . '.php'))
				{
					include(CONFIG_PATH_SUPPLIER_ABSOLUTE . $page . '.php');
				}
				else
				{
					echo '<br /><br /><h1 class="text-danger text-center">Error:404 Page Not Found!</h1><br /><br /><br /><br /><br />';
				}
			?>
	</div>
</div>
 <?php
		if($supplier->isLogedIn())
		{
	?>
<!--footer-->
<footer>
    <div class="container">
        <ul class="follow_icon3 wow fadeInDown">
            <li><a href="https://plus.google.com/104591596767340646697" target="_blank" class="fa fa-google-plus"></a></li>
            <li><a href="https://twitter.com/ImeiPK" target="_blank" class="fa fa-twitter"></a></li>
            <li><a href="https://www.facebook.com/ImeiPK" target="_blank" class="fa fa-facebook"></a></li>
            <li><a href="https://www.youtube.com/NCKSPAIN" target="_blank" class="fa fa-youtube"></a></li>
        </ul>
        <div class="row">
            <div class="col-lg-10 col-lg-offset-1">
                <div class="contactForm2">
                </div>
            </div>
        </div>
        <p class="wow fadeInUp"><?php echo CONFIG_SITE_TITLE; ?> &copy; <em id="copyright-year"></em></p>
    </div>
</footer>
<?php
                }
                
                ?>
<script src="<?php echo CONFIG_PATH_THEME; ?>js/superfish.js"></script>
</body>

</html>