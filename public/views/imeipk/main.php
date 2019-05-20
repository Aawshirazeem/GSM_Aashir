

<?php

defined("_VALID_ACCESS") or die("Restricted Access");

$admin = new admin();

$mysql = new mysql();
// for push

?>

<?php

/*if(isset($_GET['lang'])){

	if(isset($_COOKIE["fronLangCookie"]) && $_COOKIE["fronLangCookie"] != ""){

		$fronLangCookie = $_COOKIE["fronLangCookie"];

		if($fronLangCookie != $_GET['lang']){

			setcookie("fronLangCookie", $_GET['lang']);

			$fronLangCookie = $_COOKIE["fronLangCookie"];

		}

	}else{

		setcookie("fronLangCookie", $_GET['lang']);

		$fronLangCookie = $_COOKIE["fronLangCookie"];

	}

}*/

$pBlue = false;

$title = '';

$meta = '';

$currentUrl = '';
if($page=='home-us')
{
	$page='index';
}

if($page != ""){

	$fielPath = CONFIG_PATH_THEME_ABSOLUTE . $view . '/' . $page . '.php';         

	$themeUrl = '';
     
	if($page == 'index'){		

		if(isset($_GET['lang'])){

			$sql = 'select * from ' . CMS_PAGE_MASTER.' where is_home_page = 1 AND page_lang = "'.$_GET['lang'].'"' ;

		}else{

			$sql = 'select * from ' . CMS_PAGE_MASTER.' where is_home_page = 1' ;

		}

		$query = $mysql->query($sql);

		$rowCount = $mysql->rowCount($query);

		if($rowCount == 0){

			$sql = 'select * from ' . CMS_PAGE_MASTER.' where is_home_page = 1 AND page_lang = "en"' ;

			$query = $mysql->query($sql);

			$rowCount = $mysql->rowCount($query);

		}

		$rows = $mysql->fetchArray($query);

		$row = $rows[0];

		$themeUrl = $row['url'];
		

		if($row['url'] != 'home' && $row['url'] != 'home-blue' && $row['url'] != 'home-parrot' && $row['url'] != 'home-brown' && $row['url'] != 'home-us'&& $row['url'] != 'home6'){

			header("location:" .$row['url'].'.html?lang='.$_GET['lang']);

		}else{

			if(isset($_GET['lang'])){

				//header("location:" .CONFIG_PATH_SITE_MAIN.'/'.$_GET['lang']);

			}

		}

	}elseif(file_exists($fielPath)){

		

    }else{

		if(isset($_GET['lang'])){

			$sql= 'select * from ' . CMS_PAGE_MASTER.' where url = "'.$page.'" AND page_lang = "'.$_GET['lang'].'"' ;

		}else{

			$sql= 'select * from ' . CMS_PAGE_MASTER.' where url = "'.$page.'"' ;

		}

		

		$query = $mysql->query($sql);

		$rowCount = $mysql->rowCount($query);

		if($rowCount == 0){

			$sql= 'select * from ' . CMS_PAGE_MASTER.' where url = "'.$page.'" AND page_lang = "en"' ;

			$query = $mysql->query($sql);

			$rowCount = $mysql->rowCount($query);

		}

		$rows = $mysql->fetchArray($query);

		$row = $rows[0];

		$currentUrl = $row['url'];

	}

	

	if($rowCount > 0){

		if($row['url'] == 'home-blue' || $row['url'] == 'home-parrot' || $row['url'] == 'home-us'|| $row['url'] == 'home6'){

			$pBlue = true;

		}

		$pageContent = stripslashes($row['page_content']);

		

		$rCountry = '[[country]]';

		$rLanguage = '[[language]]';

		$rCurrency = '[[currency]]';

		$rTimezone = '[[timezone]]';



		$repCountry = '<select name="country_id" data-show-subtext="true" data-live-search="true" class="selectpicker form-control Themeone" required id="country_id">

			<option value="">Select Country</option>';

			$sql_language = 'select * from ' . COUNTRY_MASTER;

			$query_language = $mysql->query($sql_language);

			$rows_language = $mysql->fetchArray($query_language);

			foreach ($rows_language as $row_language) {

				$repCountry .= '<option value="' . $row_language['id'] . '">' . $row_language['countries_name'] . '</option>';

			}

		$repCountry .= '</select>';

		

		$respLanguage = '<select name="language" class="form-control Themeone" id="language">

			<option value="">Select Language</option>';

			$sql_language = 'select * from ' . LANGUAGE_MASTER . ' order by id';

			$query_language = $mysql->query($sql_language);

			$rows_language = $mysql->fetchArray($query_language);

			foreach ($rows_language as $row_language) {

				$respLanguage .= '<option  value="' . $row_language['id'] . '">' . $row_language['language'] . '</option>';

			}

		$respLanguage .= '</select>';

		

		$respCurrency = '<select name="currency" class="form-control Themeone" id="currency" required>

			<option value="">Select Currency</option>';

			$sql_currency = 'select * from ' . CURRENCY_MASTER . ' where `status`!=0 order by currency';

			$query_currency = $mysql->query($sql_currency);

			$rows_currency = $mysql->fetchArray($query_currency);

			foreach ($rows_currency as $row_currency) {

				$respCurrency .= '<option  value="' . $row_currency['id'] . '">' . $mysql->prints($row_currency['currency']) . '</option>';

			}

		$respCurrency .= '</select>';

		

		$respTimezone = '<select name="timezone" data-show-subtext="true" data-live-search="true" class="selectpicker form-control Themeone" id="timezone">

			<option value="">Select Time Zone</option>';

			$sql_timezone = 'select * from ' . TIMEZONE_MASTER . ' order by timezone';

			$query_timezone = $mysql->query($sql_timezone);

			$rows_timezone = $mysql->fetchArray($query_timezone);

			foreach ($rows_timezone as $row_timezone) {

				$respTimezone .= '<option value="' . $row_timezone['id'] . '">' . $row_timezone['timezone'] . '</option>';

			}

		$respTimezone .= '</select>';

		

		//check country

		$checkCountry = strpos($pageContent, $rCountry);

		if ($checkCountry !== false) {

			$pageContent = str_replace($rCountry,$repCountry,$pageContent);

		}

		

		//check language

		$checkLanguage = strpos($pageContent, $rLanguage);

		if ($checkCountry !== false) {

			//$pageContent = str_replace($rLanguage,$respLanguage,$pageContent);

		}

					$pageContent = str_replace($rLanguage,"",$pageContent);


		//check currency

		$checkCurrency = strpos($pageContent, $rCurrency);

		if ($checkCurrency !== false) {

			$pageContent = str_replace($rCurrency,$respCurrency,$pageContent);

		}

		

		//check timezone

		$checkTimzone = strpos($pageContent, $rTimezone);

		if ($checkTimzone !== false) {

			$pageContent = str_replace($rTimezone,$respTimezone,$pageContent);

		}

		

		$randKey = '[[rand]]';

		$respRandKey = rand();

		//check rand key

		$checkRandKey = strpos($pageContent, $randKey);

		if ($checkRandKey !== false) {

			$pageContent = str_replace($randKey,$respRandKey,$pageContent);

		}

		

		$sql_cms = 'select a.value,a.field,a.value_int finfo from '.CONFIG_MASTER.' a

			where a.field in ("USER_NOTES","ADMIN_NOTES","TER_CON")

			order by a.id';

		//echo $sql_2;

		$query_cms = $mysql->query($sql_cms);

		$rows_cms = $mysql->fetchArray($query_cms);

		

		$ter_con_of=$rows_cms[2]['finfo'];

		$term_cond_url=$rows_cms[2]['value'];

		

		

		$terms = '[[terms_service]]';

		$terms_div = '';

		if($ter_con_of==1){

			$terms_div = '<div class="clearfix mycustchkbox"><div class="checkbox checkbox-primary ">

                            <input id="termcon" name="termcon" value="1" type="checkbox" required="">

                            <label for="termcon">

                                I have read and agreed to <a target="_blank" href="'.$term_cond_url.'"> TERMS OF SERVICE</a></label></div></div>';

		}

		$pageContent = str_replace($terms,$terms_div,$pageContent);

		

		$sql_timezone1 = 'select * from ' . SMTP_CONFIG;

		$query_timezone1 = $mysql->query($sql_timezone1);

		$rows_timezone1 = $mysql->fetchArray($query_timezone1);

		

		$custom_div = '';

		if ($rows_timezone1[0]['is_custom'] == 1) {

            $custom_div .= '<div id="c_fields">';

            // show some custom fields now

            $sql1 = 'select * from nxt_custom_fields a where a.s_type=3 and a.s_id=3';

            $result = $mysql->getResult($sql1);

            $f_temp = 1;

            foreach ($result['RESULT'] as $row2) {

				if ($row2['f_type'] == 1) {

                    // text box

                   // echo '<br><label>' . $row2['f_name'] . '</label>';

                    $custom_div .= '<div class="col-md-6">

					<div class="form-group is-empty">		

					<input type="hidden" name="custom_name_' . $f_temp . '" value="' . $row2['f_name'] . '" />	

					<input type="text" name="custom_' . $f_temp . '"  class="form-control Themeone" ' . (($row2['f_req'] == 1) ? 'required' : '') . ' placeholder="' . $row2['f_desc'] . '' . (($row2['f_req'] == 1) ? '*' : '') . '"/>

					</div>

            		</div>

					';

					

					

					

                }

				if($row2['f_type'] == 2) {

                    $ops = $row2['f_opt'];

                    $ops = explode(',', $ops);

                    // text box

                   // echo '<br><label>' . $row2['f_name'] . '</label>';

                    $custom_div .= '

					<div class="col-md-6">

					<div class="form-group is-empty">	

					<input type="hidden" name="custom_name_' . $f_temp . '" value="' . $row2['f_name'] . '" />';

                    $custom_div .= '<select ' . (($row2['f_req'] == 1) ? 'required' : '') . ' class="form-control Themeone"  name="custom_' . $f_temp . '">';

                    $custom_div .= ' <option value="">' . $row2['f_desc'] . '' . (($row2['f_req'] == 1) ? '*' : '') . '</option>';

                    for ($a = 0; $a < count($ops); $a++) {

                        $custom_div .= ' <option value="' . $ops[$a] . '">' . $ops[$a] . '</option>';

                    }



                    $custom_div .= '</select></div></div>';

                }

				if($row2['f_type'] == 3){

					$custom_div .= '<div class="col-md-6 mycustchkbox">

					<div class="checkbox checkbox-primary">

					<input type="hidden" name="custom_name_' . $f_temp . '" value="' . $row2['f_name'] . '" />

					<input type="checkbox" ' . (($row2['f_req'] == 1) ? 'required' : '') . ' name="custom_' . $f_temp . '"/>

					<label> ' . $row2['f_name'] . '' . (($row2['f_req'] == 1) ? '*' : '') . ''. $row2['f_desc'].'</label>

					</div>

					</div>';

				}

                $f_temp++;

            }

            $custom_div .= '</div>';

        }

		$pageContent = str_replace('[[additional_fields]]',$custom_div,$pageContent);

		

		//change site title, domain & tagline

		$siteTitle = CONFIG_SITE_TITLE;

		$siteName = CONFIG_SITE_NAME;

		$siteDomain = CONFIG_DOMAIN;

		

		$cSiteTitle = '[[sitename]]';

		$cSiteName = '[[tagline]]';

		$cSiteDomain = '[[domainname]]';

		

		$checkSiteDomain = strpos($pageContent, $cSiteDomain);

		if ($checkSiteDomain !== false) {

			$pageContent = str_replace($cSiteDomain,$siteDomain,$pageContent);

		}

		

		$checkSiteTagLine = strpos($pageContent, $cSiteName);

		if ($checkSiteTagLine !== false) {

			$pageContent = str_replace($cSiteName,$siteName,$pageContent);

		}	

		

		$checkSiteTitle = strpos($pageContent, $cSiteTitle);

		if ($checkSiteTitle !== false) {

			$pageContent = str_replace($cSiteTitle,$siteTitle,$pageContent);

		}

		

		

		$title = $row['title'];

		$meta = $row['meta'];

	}else if(!file_exists($fielPath)){

		$pageContent = '<div class="errorBox">

        	<div class="container">

            	<p class="title wow fadeInDown">404 Error</p>

                <p class="description wow fadeInUp" data-wow-delay="0.1s">sorry,page not found</p>

                <p class="wow fadeInUp" data-wow-delay="0.2s">The page you are looking for might have been removed,had its name changed, or is temporarily unavailable.Please <br>try using our search box below to look for information on the website.</p>

           	</div>

       	</div>';

	}

}else{

	$pageContent = '<div class="errorBox">

        	<div class="container">

            	<p class="title wow fadeInDown">404 Error</p>

                <p class="description wow fadeInUp" data-wow-delay="0.1s">sorry,page not found</p>

                <p class="wow fadeInUp" data-wow-delay="0.2s">The page you are looking for might have been removed,had its name changed, or is temporarily unavailable.Please <br>try using our search box below to look for information on the website.</p>

           	</div>

       	</div>';

}



$sqlForFooter= 'select * from ' . CMS_PAGE_MASTER.' where is_home_page = 1' ;

$query = $mysql->query($sqlForFooter);

$rowCountFooter = $mysql->rowCount($query);

$rowsFooter = $mysql->fetchArray($query);

$rowFooter = $rowsFooter[0];

$url = $rowFooter['url'];

// chat code
$sqlchatcode = 'select chat_code from ' . SMTP_CONFIG;
$sqlchatcodedata = $mysql->query($sqlchatcode);
$sqlchatcodedata1 = $mysql->fetchArray($sqlchatcodedata);
$chat_window_code = $sqlchatcodedata1[0]['chat_code'];


$chat_window=FALSE;
if($chat_window_code!="")
    $chat_window=TRUE;

?>

<!DOCTYPE HTML>

<html>

<head>

<meta charset="utf-8">

<meta http-equiv="X-UA-Compatible" content="IE=edge">

<!--<meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="">

    <meta name="author" content="">-->

<?php echo $meta; ?>

<title><?php //echo (isset($title) && $title != "" ? $title.' - ' : ''); ?><?php echo CONFIG_SITE_TITLE ?></title>

<link href="https://fonts.googleapis.com/css?family=Work+Sans:300,400,500,600,700" rel="stylesheet">

<link href="https://fonts.googleapis.com/css?family=Assistant:300,400,600,700|Work+Sans:300,400,500,600,700" rel="stylesheet">

<link href="<?php echo CONFIG_PATH_THEME_NEW; ?>assets/css/bootstrap.min.css" rel="stylesheet">

<link href="<?php echo CONFIG_PATH_THEME_NEW; ?>assets/font-awesome/font-awesome-4.6.3/css/font-awesome.min.css" rel="stylesheet">

<link href="<?php echo CONFIG_PATH_THEME_NEW; ?>assets/ionicons/css/ionicons.min.css" rel="stylesheet">

<link href="<?php echo CONFIG_PATH_THEME_NEW; ?>assets/css/style.css" rel="stylesheet">

<link href="<?php echo CONFIG_PATH_THEME_NEW; ?>assets/css/full-slider.css" rel="stylesheet">

<link href="<?php echo CONFIG_PATH_THEME_NEW; ?>assets/minimalist-basic/content.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/jquery.slick/1.6.0/slick.css"/>

<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/jquery.slick/1.6.0/slick-theme.css"/>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />

<style>

body { overflow-x: hidden; }

</style>

<?PHP 

$sql_s= 'select * from ' . CMS_SETTINGS ;

$query_s = $mysql->query($sql_s);

if($mysql->rowCount($query_s) > 0){

	$rows_s = $mysql->fetchArray($query_s);

	foreach($rows_s as $row_s){

		$cmsSettings[$row_s['config']] = $row_s['value'];	

	}

}



?>

<style>

<?php if($themeUrl == 'home-brown'){ ?>

.row img {

    margin: 0 !important;

}

<?php

}

?>

<?PHP

if(isset($cmsSettings['header_background']) && $cmsSettings['header_background'] != ''){

	$cmsSettings['header_background'] = '#'.$cmsSettings['header_background'];

?>

.top-nav-collapse-one, .top-nav-collapse-perrot, .top-nav-collapse { background-color: <?PHP echo $cmsSettings['header_background']; ?> !important; border-bottom: 1px solid <?PHP echo $cmsSettings['header_background']; ?> !important;

} <?PHP } 



if(isset($cmsSettings['menu_color']) && $cmsSettings['menu_color'] != ''){

	$cmsSettings['menu_color'] = '#'.$cmsSettings['menu_color'];

?>

.navbar-inverse .navbar-nav > li > a { color: <?PHP echo $cmsSettings['menu_color']; ?> !important;

} <?PHP }



if(isset($cmsSettings['website_color']) && $cmsSettings['website_color'] != ''){

	$cmsSettings['website_color'] = '#'.$cmsSettings['website_color']; ?>



.SerTitleBorder, .HowTitleBorder, .useTitleBorder, .footTitleBorder, .footdownBorder, .footconBorder, .AboutTitle > h1 { border-bottom-color: <?PHP echo $cmsSettings['website_color']; ?>;}

.FootBorderpc, .btnReadpc, .btnRead { border-color: <?PHP echo $cmsSettings['website_color']; ?>;}

.navbar-inverse-perrot .navbar-toggle-perrot:focus, .navbar-inverse-perrot .navbar-toggle-perrot:hover, .btnReadpc, .blueBg, .btnRead ,.header-BG,.pcBg,.perrotBg{ background-color: <?PHP echo $cmsSettings['website_color'];

?>;

}

.servicesBox { background-color: rgba(0, 0, 0, 0.3); }

.WorkTitle > h1,.BusinessTitle > h1 { color:<?PHP echo $cmsSettings['website_color']; ?>;}

@media screen and (max-width: 361px) {

	.navbar-nav-perrot { background-color: <?PHP echo $cmsSettings['website_color'];?>;}

}



@media only screen and (max-device-width: 360px) {

	.navbar-inverse .navbar-toggle:focus, .navbar-inverse .navbar-toggle:hover, .navbar-nav { background-color: <?PHP echo $cmsSettings['website_color'];?>;}

}

<?PHP } ?>

</style>

</head>

<?php

$bClass = '';

if($url == 'home'){

	$bClass = 'themeone';

}elseif($url == 'home-blue' || $url == 'home-us'  ){

	$bClass = 'theme';

}elseif($url == 'home-parrot'  || $url == 'home6'){

	$bClass = 'themetwo';

}elseif($url == 'home-brown'){

	$bClass = 'themethree';

}

?>

<body class="<?php echo $bClass; ?>">

<?php

$cls = '';

if($page == 'index' && $cmsSettings['header_collapsed'] == 1){

	if($themeUrl == 'home-blue'){

		$cls = 'navbar navbar-inverse navbar-fixed-top navbar-fixed-top-one';

	}elseif($themeUrl == 'home-parrot'){

		$cls = 'navbar navbar-inverse navbar-fixed-top navbar-fixed-top-perrot navbar-inverse-perrot';

	}elseif($themeUrl == 'home-brown'){

		$cls = 'navbar navbar-inverse navbar-fixed-top navbar-fixed-top-four navbar-inverse-four';
		
	}elseif($themeUrl == 'home-us'){

		$cls = 'navbar navbar-inverse navbar-fixed-top navbar-fixed-top-one';

	}elseif($themeUrl == 'home6'){

		$cls = 'navbar navbar-inverse navbar-fixed-top navbar-fixed-top-one';

	}else{

		$cls = 'navbar navbar-inverse navbar-fixed-top';

	}

}else{

	if($url == 'home'){

		$cls = 'navbar navbar-inverse top-nav-collapse';

	}else if($url == 'home-blue' ){

		$cls = 'navbar navbar-inverse top-nav-collapse top-nav-collapse-one';

	}else if($url == 'home-parrot'){

		$cls = 'navbar navbar-inverse navbar-inverse-perrot top-nav-collapse top-nav-collapse-perrot';

	}else if($url == 'home-brown'){

		$cls = 'navbar navbar-inverse navbar-inverse-four top-nav-collapse top-nav-collapse-four';

	}
	else if($url == 'home-us' ){

		$cls = 'navbar navbar-inverse top-nav-collapse top-nav-collapse-one top-nav-collapse-us';

	}
        else if($url == 'home6' ){

		      $cls = 'navbar navbar-inverse top-nav-collapse top-nav-collapse-one top-nav-collapse-us';

	}
        

}

?>

	<nav class="<?php echo $cls; ?>" role="navigation">

    	<div class="container navPt">

        	<!-- Brand and toggle get grouped for better mobile display -->

        	<div class="navbar-header">

            	<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">

                	<span class="sr-only">Toggle navigation</span>

                    <span class="icon-bar"></span>

                    <span class="icon-bar"></span>

                    <span class="icon-bar"></span>

                </button>

				<?php

                $sql= 'select * from ' . CMS_MENU_MASTER.' where id = 1' ;

				$query = $mysql->query($sql);

				$rowCount = $mysql->rowCount($query);

				$rows = $mysql->fetchArray($query);

				$row = $rows[0];

				if($row['logo'] != ""){

					$logo = '<img src="'.CONFIG_PATH_THEME_NEW.'site_logo/'.$row['logo'].'" class="img-responsive" style="width:100%;"/>';

				}else{

					$logo = CONFIG_SITE_TITLE;

				}

				?>

                <a class="navbar-brand" href="/"><?php echo $logo; ?></a> </div>

            <!-- Collect the nav links, forms, and other content for toggling -->

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

                <ul class="nav navbar-nav <?php echo ($themeUrl == 'home-us') ? 'navbar-nav-one' : 'navbar-nav-one'; ?> <?php echo ($themeUrl == 'home-brown') ? 'headerFour' : ''; ?>">

                <?php

                $sql= 'select * from ' . CMS_MENU_MASTER ;

                $pagesOpts = '';

                $sql_page= 'select * from ' . CMS_PAGE_MASTER ;

                $query_page = $mysql->query($sql_page);

                $query = $mysql->query($sql);

                if($mysql->rowCount($query) > 0){

                    $rows = $mysql->fetchArray($query);

                    if($mysql->rowCount($query_page) > 0){

                        $pageRows = $mysql->fetchArray($query_page);

                    }

					

                    foreach($rows as $row){

                        $menu = ($row['json']);

                        $menuArray = json_decode($menu,true);

                        foreach($menuArray as $menu){

                            $_havingChild = $menu['childs'] != '' && is_array($menu['childs']) && count($menu['childs']) > 0;

                            $_aAttr = '';

                            if($_havingChild){

                                $_aAttr = ' class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="0" data-close-others="false" href="#" ';

                            }

                ?>

                    <li class="nav-item <?PHP echo ($_havingChild ? 'dropdown' : ''); ?>">

                    <?php
                    $_url = $menu['url'];

                    $checkLang = strpos($_url, '[[lang]]');

                    if ($checkLang !== false) {
                        $_url = str_replace('[[lang]]',$_GET['lang'],$_url);

                    }

					

                    if ($menu['url'] == ''){
                        foreach ($pageRows as $rowin) {

                            if ($rowin['id'] == $menu['page']) {

                                $_url = $rowin['url']. '.html';

								$_url = CONFIG_PATH_SITE.($_GET['lang'] == "" ? $_COOKIE["fronLangCookie"] : $_GET['lang']).'/'.$rowin['url']. '.html';

                            }
                        }

                    }

                    echo '<a '.(!$_havingChild ? 'href="' . $_url . '"' : '').$_aAttr.'>' . $admin->wordTrans($_GET['lang'],$menu['label']) . ($_havingChild ? '<i class="fa fa-angle-down"></i>':'').'</a>';

                    if($_havingChild){

                    ?>

                        <ul class="dropdown-menu">

                        <?PHP

                        foreach($menu['childs'] as $ch){

                            $_url = CONFIG_PATH_SITE.$ch['url'];

                            $checkLang = strpos($_url, '[[lang]]');

                            if ($checkLang !== false) {

                                $_url = str_replace('[[lang]]',$_GET['lang'],$_url);

                            }

							

                            if ($ch['url'] == ''){

                                foreach ($pageRows as $rowin) {

                                    if ($rowin['id'] == $ch['page']) {

                                        $_url = $rowin['url']. '.html';

                                        $_url = CONFIG_PATH_SITE.$_GET['lang'].'/'.$rowin['url']. '.html';

                                    }

                                }

                            }

                            echo '<li><a href="'.$_url.'">'. $admin->wordTrans($_GET['lang'],$ch['label']).'</a></li>';

                        }

                        ?>

                        </ul>

                        <?PHP } ?>

                    </li>

                <?PHP

                        }

                    }

                }

                ?>
				
				
				
				
				
				
				
				
				
				
				

                <li class="nav-item dropdown gsmUnion">

                	<?php

					if(isset($_GET['lang']) && $_GET['lang'] != ""){

						$defLang = $_GET['lang'];

					}elseif(isset($_COOKIE["fronLangCookie"]) && $_COOKIE["fronLangCookie"] != ""){

						$defLang = $_COOKIE["fronLangCookie"];

					}else{

						$defLang = 'en';

					}

					?>
					
					
					
					<a style="background:none" href="<?php echo $changeUrl; ?>" class="<?php echo $actFlag; ?>">
									<img src="<?php echo CONFIG_PATH_PANEL_ADMIN.'assets_1/language_flag/' ?><?php echo $defLang.'.png'; ?>" class="languageFlag" /><i style="color:#495c74; padding-left:3px;" class="fa fa-angle-down"></i>
                    </a>
					
                    <ul class="dropdown-menu">

                    	<?php

						$sql= 'select * from ' . LANG_MASTER . ' where lang_status = 1' ;

						$query = $mysql->query($sql);

						$rows = $mysql->fetchArray($query);

						if($page != 'index'){

							$dURL = $_SERVER['REQUEST_URI'];

							foreach($rows as $row){

								$changeUrl = str_replace($_GET['lang']."/",$row['language_code']."/",$dURL);
								$actFlag = '';

								if($defLang == $row['language_code']){

									$actFlag = 'actLangFlag';

								}

						?>

                            <li>
                            	<a href="<?php echo $changeUrl; ?>" class="<?php echo $actFlag; ?>">
									<img src="<?php echo CONFIG_PATH_PANEL_ADMIN.'assets_1/language_flag/'.$row['language_flag']; ?>" class="languageFlag" />
									<?php echo $row['language']; ?>
                               	</a>
                           	</li>

                        <?php

                        	}

						}else{

							foreach($rows as $row){

								$actFlag = '';

								if($defLang == $row['language_code']){

									$actFlag = 'actLangFlag';

								}

						?>
                            <li>
                            	<a href="/<?php echo $row['language_code']; ?>" class="<?php echo $actFlag; ?>">
                                	<img src="<?php echo CONFIG_PATH_PANEL_ADMIN.'assets_1/language_flag/'.$row['language_flag']; ?>" class="languageFlag" />
									<?php echo $row['language']; ?>
                                </a>
                            </li>
                        <?php
                        	}
						}
						?>

                    </ul>

                </li>
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				

                </ul>

            </div>

            <!-- /.navbar-collapse -->

        </div>

        <!-- /.container --> 

    </nav>

<?php

	if($themeUrl == 'home-blue' || $themeUrl == 'home-parrot' || $themeUrl == 'home-us' || $themeUrl == 'home6'){

		$sql = 'select * from ' . SLIDER_MASTER.' where is_active = 1';

		$query = $mysql->query($sql);

		$rowCount = $mysql->rowCount($query);

		$rows = $mysql->fetchArray($query);

?>

<div class="row clearfix">

  <header id="myCarousel" class="carousel slide"> 

    <!-- Indicators -->

    <ol class="carousel-indicators">

      <?php for($i = 0; $i<$rowCount; $i++){ ?>

      <li data-target="#myCarousel" data-slide-to="<?php echo $i; ?>" class="<?php ($i == 0) ? 'active' : ''; ?>"></li>

      <?php } ?>

    </ol>

    <!-- Wrapper for Slides -->

    <div class="carousel-inner">

      <?php

			$j = 0;

			foreach($rows as $row){

				$cl = '';

				if($j == 0){

					$cl = 'active';

				}

			?>

      <div class="item <?php echo $cl; ?>"> 

        <!-- Set the first background image using inline CSS below. -->

        <div class="fill"> <img src="<?php echo CONFIG_PATH_THEME_NEW.'slider_upload/'.$row['image']; ?>" style="margin:0;width: 100%;"> </div>

        <div class="slider-text">

          <div class="bgTitleSlider">

            <h1><?php //echo $row['slider_title']; ?></h1>

            <?php //echo $row['notes']; ?> </div>

        </div>

      </div>

      <?php

				$j++;

			}

			?>

    </div>

    <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">

    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>

    <span class="sr-only">Previous</span>

  </a>

  <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">

    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>

    <span class="sr-only">Next</span>

  </a>

  </header>

</div>

<?php } ?>

<div class="content-data <?php echo ($page != 'index' && $themeUrl != 'home' && $themeUrl != 'home-blue' && $themeUrl != 'home-parrot' && $themeUrl != 'home-brown' && $currentUrl != 'login' && $currentUrl != 'register')  ? 'container' : ''; ?>">

<?php

if(isset($_GET['reply']) && $_GET['reply'] != ""){

	$reply = $_GET['reply'];

	switch ($reply) {

		case 'reply_invalid_login':

			$msg = 'Invalid login details';

			break;

		case 'reply_logut_success':

			$msg = 'Logout successfull.';

			break;

		case 'reply_invalid_lp':

			$msg = 'Invalid IP';

			break;

		case 'reply_admin_accept_wait_1':

			$msg = 'Your account not activated yet.Check your Email inbox/spam and verify your account by clicking on the link inside.<br>if '
                        . 'if you have not received any email then contact with admin to activate your account.Thanks!';

			break;
                    case 'reply_admin_accept_wait':

			$msg = 'Your account not activated yet Please Contact with Admin or Support For Your Account Activation.Thanks!';

			break;

		case 'reply_inactive_login':

			$msg = 'User Blocked - Contact With Admin';

			break;

                    

                    case 'session_exp':

			$msg = 'Your Session Expired!';

			break;

    case 'invalid_verification_code':

        $msg = 'Invalid verification code!';

        break;

    case 'name_missing':

        $msg = 'Your name is missing.';

        break;

    case 'password_missing':

        $msg = 'Password can\'t be blank';

        break;

    case 'username_missing':

        $msg = 'Username can\'t be blank';

        break;

    case 'email_missing':

        $msg = 'Email can\'t be blank';

        break;

    case 'duplicate_username':

        $msg = 'Usename is already registered with us. Please try again!';

        break;

    case 'duplicate_email':

        $msg = 'Email is already registered with us. Please try again!';

        break;

    case 'duplicate_email_username':

        $msg = 'Email and Username is already registered with us. Please try again!';

        break;

         case 'country_missing':

        $msg = 'County is Missing, Select A Your Country';

        break;

    case 'thanks':

        $msg = 'You are registered successfully';

        break;

	}

?>

<div class="row">

            <div class="modal fade" id="getCodeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">

                        <h4 class="modal-title" id="myModalLabel">Reply</h4>
                    </div>
                    <div class="modal-body" id="getCode" style="">
                        <?php echo $msg; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
<?php } ?>

  <?php

	

	if(file_exists($fielPath)){

		include $fielPath;

	}else{

		echo $pageContent;

	}

	?>

</div>

<?php

$t1Footer = false;

$t2Footer = false;

if($page == 'index'){

	if($themeUrl == 'home' || $themeUrl == 'home-parrot'){

		$t1Footer = true;

	}else{

		$t2Footer = true;

	}

}else{

	if($url == 'home' || $url == 'home-parrot' || $url == 'home6'){

		$t1Footer = true;

	}elseif($url = 'home-blue' || $url == 'home-us'){

		$t2Footer = true;

	}

}



$sql = 'select * from '.CMS_SOCIAL;

	$query = $mysql->query($sql);

	$rowCount = $mysql->rowCount($query);

	$rows = $mysql->fetchArray($query);

	$socials = array();

	foreach($rows as $srow){

		$socials[$srow['social_name']] = array('active'=>$srow['is_active'],'url'=>$srow['url']);

	}



$footerid = $cmsSettings['active_footer'];

/*if(isset($_GET['lang'])){

	$sqlFtr= 'select * from ' . CMS_PAGE_MASTER.' where id = "'.$footerid.'" AND page_lang = "'.$_GET['lang'].'"' ;

}else{

	$sqlFtr= 'select * from ' . CMS_PAGE_MASTER.' where id = "'.$footerid.'"' ;

}*/

$sqlFtr= 'select * from ' . CMS_PAGE_MASTER.' where id = "'.$footerid.'"' ;

$queryFtr = $mysql->query($sqlFtr);

$rowsFtr = $mysql->fetchArray($queryFtr);

$rowFtr = $rowsFtr[0];

if($rowFtr['m_title'] != ""){

	$sqlFtr= 'select * from ' . CMS_PAGE_MASTER.' where m_title = "'.$rowFtr['m_title'].'" AND page_lang = "'.$_GET['lang'].'"' ;

	$queryFtr = $mysql->query($sqlFtr);

	$rowCount = $mysql->rowCount($queryFtr);

	if($rowCount == 0){

		$sqlFtr= 'select * from ' . CMS_PAGE_MASTER.' where m_title = "'.$rowFtr['m_title'].'" AND page_lang = "en"' ;

		$queryFtr = $mysql->query($sqlFtr);

	}

	$rowsFtr = $mysql->fetchArray($queryFtr);

	$rowFtr = $rowsFtr[0];

}



$footerContent = $rowFtr['page_content'];



//change site title, domain & tagline

$siteTitle = CONFIG_SITE_TITLE;



$cSiteTitle = '[[sitename]]';



$checkSiteTitle = strpos($footerContent, $cSiteTitle);

if ($checkSiteTitle !== false) {

	$footerContent = str_replace($cSiteTitle,$siteTitle,$footerContent);

}





                                    

echo $footerContent;

?>

       

<?php if($t1Footer == true &&  1==2){ 

	

?>

<div class="row clearfix">

  <div class="col-md-12 grayBG">

    <div class="row">

      <div class="container Footpt">

        <div class="col-sm-6 col-md-6 text-left">

          <div class="FootTitle">

            <h1><a href="#">IMEI<span>.PK</span></a></h1>

            <p> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quam velit, vulputate eu pharetra nec, mattis ac neque. Duis vulputate commodo lectus, ac </p>

          </div>

        </div>

        <div class="col-sm-6 col-md-offset-2 col-md-4 text-left">

          <div class="footContact">

            <h2>CONTACT US</h2>

          </div>

          <div class="bgBtnfoot">

            <button type="button" class="btn-login">LOGIN</button>

            <button type="button" class="btn-down">DOWNLOADS</button>

          </div>

          <div class="SocialFoot"> 

          <?PHP if($socials['Facebook']['active'] == 1){  ?>

          <a href="<?PHP echo $socials['Facebook']['url']; ?>"><img src="/public/views/cms/assets/images/facebook.png" alt="facebook" class="footICon"></a>

          <?PHP } ?>

          <?PHP if($socials['Twitter']['active'] == 1){  ?>

          <a href="<?PHP echo $socials['Twitter']['url']; ?>"><img src="/public/views/cms/assets/images/tweet.png" alt="facebook" class="footICon"></a>

           <?PHP } ?>

          <?PHP if($socials['Google Plus']['active'] == 1){  ?>

          <a href="<?PHP echo $socials['Google Plus']['url']; ?>"><img src="/public/views/cms/assets/images/G-plus.png" alt="facebook" class="footICon"></a> 

           <?PHP } ?>

          <?PHP if($socials['You Tube']['active'] == 1){  ?>

          <a href="<?PHP echo $socials['You Tube']['url']; ?>"><img src="/public/views/cms/assets/images/youtube.png" alt="facebook" class="footICon"></a>

          <?PHP } ?>

          </div>

        </div>

      </div>

      <hr class="FootBorder">

    </div>

    <div class="CopyRight text-center"> <span>&nbsp;IMEI.PK © 2016</span> </div>

  </div>

</div>

<?php } ?>

<?php if($t2Footer == true && 1 == 2){ ?>

<div class="row clearfix">

  <div class="col-md-12 Footbg">

    <div class="row">

      <div class="container footpt">

        <div class="col-sm-4 col-md-4">

          <div class="footerPRo clearfix">

            <h1>PRODUCTS &amp; SERVICES </h1>

            <hr class="footTitleBorder">

          </div>

          <div class="FootServices">

            <ul class="footersrvc">

              <li> <a href="#">UNLOCKER SERVICES</a> </li>

              <li> <a href="#"> FILE SERVICES </a> </li>

              <li> <a href="#"> SERVER SERVICES </a> </li>

            </ul>

          </div>

        </div>

        <div class="col-sm-4 col-md-4">

          <div class="footerPRo clearfix">

            <h1>DOWNLOAD </h1>

            <hr class="footdownBorder">

          </div>

          <div class="FootServices">

            <ul class="footersrvc">

              <li> <a href="#">FILE SERVICE SOFTWARES</a> </li>

              <li> <a href="#">FREE SOFTWARES</a> </li>

              <li> <a href="#">ADVERTISEMENT</a> </li>

            </ul>

          </div>

        </div>

        <div class="col-sm-4 col-md-4">

          <div class="footerPRo clearfix">

            <h1>CONTACT US</h1>

            <hr class="footconBorder">

          </div>

          <div class="footAdd"> Lorem&nbsp;ipsum&nbsp;dolor&nbsp;sit&nbsp;amet, consectetur&nbsp;adipiscing&nbsp;elit

            <p>Mobile: 0123456789</p>

            <button type="button" class="btn_foot">LOGIN</button>

          </div>

        </div>

      </div>

      <hr class="FootBorderIN2">

     

    </div>

     

    <div class="container">

      <div class="copyIN2  pull-left"> IMEI.PK © 2016 </div>

      <div class="FootSocialIN2  pull-right">

      <?PHP if($socials['Facebook']['active'] == 1){  ?>

          <a href="<?PHP echo $socials['Facebook']['url']; ?>"> <i class="fa fa-facebook footFb" aria-hidden="true"></i></a>

          <?PHP } ?>

          <?PHP if($socials['Twitter']['active'] == 1){  ?>

          <a href="<?PHP echo $socials['Twitter']['url']; ?>"><i class="fa fa-twitter foottwt" aria-hidden="true"></i></a>

           <?PHP } ?>

          <?PHP if($socials['Google Plus']['active'] == 1){  ?>

          <a href="<?PHP echo $socials['Google Plus']['url']; ?>"><i class="fa fa-google-plus footplus" aria-hidden="true"></i></a> 

           <?PHP } ?>

          <?PHP if($socials['You Tube']['active'] == 1){  ?>

          <a href="<?PHP echo $socials['You Tube']['url']; ?>"><i class="fa fa-youtube footplus" aria-hidden="true"></i></a>

          <?PHP } ?>

      

      

      

      

         </div>

    </div>

       

  </div>

  

</div>

       

<?php } 

      

                                    $is_access=0;
                                       $input = $_SERVER['HTTP_HOST'];
    $input = trim($input, '/');
    if (!preg_match('#^http(s)?://#', $input)) {
        $input = 'http://' . $input;
    }
    $urlParts = parse_url($input);
    $domain = preg_replace('/^www\./', '', $urlParts['host']);

                                    $con = mysqli_connect("sv82.ifastnet.com","gsmunion_upuser","S+OXupg8lqaW","gsmunion_upload");

                                    if ($con) {

                                        

                                        $qry_check='select * from tbl_users where  domain LIKE "%'.$domain.'%"  and white_lable=1';



  $result = $con->query($qry_check);



if ($result->num_rows > 0) { 

                                      $is_access=1;

                                  }

                                        

                                    }

                                    if ($is_access==0) {

                                        ?>

    <footer class="text-left" style="margin:0;">

                                        <p class="wow fadeInUp" style=" font-family:Arial; position:fixed;left:0;bottom:0;font-size:11px;background:#E7E7E7; color:#000000; padding:2px 5px;display:block !important;z-index:999; margin: 0;">Developed By <i><a style="color:#000;" href="http://gsmunion.net">GsmUnion Fusion</a></i></p>



                                    </footer>

                                    <?php

                                    }

                                    ?>

    

<script src="<?php echo CONFIG_PATH_THEME_NEW; ?>assets/js/jquery.js"></script> 
<script src="<?php echo CONFIG_PATH_THEME_NEW; ?>assets/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo CONFIG_PATH_THEME_NEW; ?>assets/js/validation.min.js"></script>

<script type="text/javascript" src="<?php echo CONFIG_PATH_THEME_NEW; ?>assets/js/script1.js"></script>

<script src="<?php echo CONFIG_PATH_THEME_NEW; ?>assets/js/scrolling-nav.js"></script> 

<!-- Bootstrap Core JavaScript --> 

 

<script type="text/javascript" src="//cdn.jsdelivr.net/jquery.slick/1.6.0/slick.min.js"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<script>

$(document).ready(function(e) {

    $(".regular").slick({

		dots: true,

		infinite: true,

		slidesToShow: 1,

		slidesToScroll: 1

	  });
          
          $("#captchaCode").hide();
          $("#captchaimg").hide();
          $(".Clickcode").hide();
});

$('.carousel').carousel({

	interval: 5000 //changes the speed

})

var msgg="";
msgg="<?php echo $msg;?>";
if(msgg!="")
{
     $("#getCodeModal").modal('show');
}

</script>
   <?php 
        if($chat_window==TRUE)
           // echo '<script type="text/javascript">';
            echo $chat_window_code;
            // echo '</script>';
        ?>

</body>

</html>