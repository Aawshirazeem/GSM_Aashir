<?php
$id = $_GET['id'];
if($id == "" || $id == 0){
	$page = 'nopage';
	header("location:". $page . ".html");
}

defined("_VALID_ACCESS") or die("Restricted Access");
$admin = new admin();
$mysql = new mysql();

if(isset($_GET['lang'])){
	$sql= 'select * from ' . CMS_PAGE_MASTER.' where id = '.$id.' AND page_lang = "'.$_GET['lang'].'"' ;
}else{
	$sql= 'select * from ' . CMS_PAGE_MASTER.' where id = '.$id ;
}
$query = $mysql->query($sql);
$rowCount = $mysql->rowCount($query);
$rows = $mysql->fetchArray($query);
$row = $rows[0];

/*if(isset($_GET['lang'])){
	$sqlForHome= 'select * from ' . CMS_PAGE_MASTER.' where is_home_page = 1 AND page_lang = "'.$_GET['lang'].'"' ;
}else{
	$sqlForHome= 'select * from ' . CMS_PAGE_MASTER.' where is_home_page = 1' ;
}
*/$sqlForHome= 'select * from ' . CMS_PAGE_MASTER.' where is_home_page = 1' ;
$query = $mysql->query($sqlForHome);
$rowCountHome = $mysql->rowCount($query);
$rowsHome = $mysql->fetchArray($query);
$rowHome = $rowsHome[0];
$url = $rowHome['url'];
?>
<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?php echo CONFIG_SITE_TITLE;?></title>
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:300,400,500,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Assistant:300,400,600,700|Work+Sans:300,400,500,600,700" rel="stylesheet">
    <link href="<?php echo CONFIG_PATH_THEME_NEW; ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo CONFIG_PATH_THEME_NEW; ?>assets/font-awesome/font-awesome-4.6.3/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo CONFIG_PATH_THEME_NEW; ?>assets/ionicons/css/ionicons.min.css" rel="stylesheet">
    <link href="<?php echo CONFIG_PATH_THEME_NEW; ?>assets/css/style.css" rel="stylesheet">
    <link href="<?php echo CONFIG_PATH_THEME_NEW; ?>assets/minimalist-basic/content.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo CONFIG_PATH_THEME_NEW; ?>contentbuilder/contentbuilder.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo CONFIG_PATH_THEME_NEW; ?>assets/css/full-slider.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/jquery.slick/1.6.0/slick.css"/>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/jquery.slick/1.6.0/slick-theme.css"/>
    <style>
        .is-container {  margin: 60px auto 150px; max-width: 1050px; width:100%; padding:55px 35px; box-sizing:border-box; background-color: #f7f7f7;box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);}
        @media all and (max-width: 1080px) {
            .is-container { margin:0; }
        }

        body {overflow-x: hidden;} /* give space 70px on the bottom for panel */
        #panelCms {width:100%;height:57px;border-top: #eee 1px solid;background:rgba(255,255,255,0.95);position:fixed;left:0;bottom:0;padding:10px;box-sizing:border-box;text-align:center;white-space:nowrap;z-index:10001;}
        #panelCms button {border-radius:4px;padding: 10px 15px;text-transform:uppercase;font-size: 11px;letter-spacing: 1px;line-height: 1;}
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

.SerTitleBorder, .HowTitleBorder, .useTitleBorder, .footTitleBorder, .footdownBorder, .footconBorder, .AboutTitle > h1 { border-bottom-color: <?PHP echo $cmsSettings['website_color'];
?>;
}
.FootBorderpc, .btnReadpc, .btnRead { border-color: <?PHP echo $cmsSettings['website_color'];
?>;
}
.navbar-inverse-perrot .navbar-toggle-perrot:focus, .navbar-inverse-perrot .navbar-toggle-perrot:hover, .btnReadpc, .blueBg, .btnRead ,.header-BG,.pcBg,.perrotBg{ background-color: <?PHP echo $cmsSettings['website_color'];
?>;
}
.servicesBox { background-color: rgba(0, 0, 0, 0.3); }
.WorkTitle > h1,.BusinessTitle > h1 { color:<?PHP echo $cmsSettings['website_color']; ?>;}
@media screen and (max-width: 361px) {
.navbar-nav-perrot { background-color: <?PHP echo $cmsSettings['website_color'];
?>;
}
}

@media only screen and (max-device-width: 360px) {
.navbar-inverse .navbar-toggle:focus, .navbar-inverse .navbar-toggle:hover, .navbar-nav { background-color: <?PHP echo $cmsSettings['website_color'];
?>;
}
}

<?PHP } ?>

</style></head>
<?php
$bClass = '';
if($url == 'home'){
	$bClass = 'themeone';
}elseif($url == 'home-blue'){
	$bClass = 'theme';
}elseif($url == 'home-parrot'){
	$bClass = 'themetwo';
}elseif($url == 'home-brown'){
	$bClass = 'themethree';
}
?>
<body class="<?php echo $bClass; ?>">
<div style="position: fixed;z-index: 11;top: 120px;">
	<button type="button" name="btnOpenPopup" class="btn btn-sm btn-info btnOpenPopup" style="border-top-left-radius: 0;border-bottom-left-radius: 0"><i class="fa fa-cog"></i></button>
</div>
<?php
$cls = '';
if($page == 'index' && $cmsSettings['header_collapsed'] == 1){
	if($themeUrl == 'home-blue'){
		$cls = 'navbar navbar-inverse navbar-fixed-top navbar-fixed-top-one';
	}elseif($themeUrl == 'home-parrot'){
		$cls = 'navbar navbar-inverse navbar-fixed-top navbar-fixed-top-perrot navbar-inverse-perrot';
	}elseif($themeUrl == 'home-brown'){
		$cls = 'navbar navbar-inverse navbar-fixed-top navbar-fixed-top-four navbar-inverse-four';
	}else{
		$cls = 'navbar navbar-inverse navbar-fixed-top';
	}
}else{
	if($url == 'home'){
		$cls = 'navbar navbar-inverse top-nav-collapse';
	}else if($url == 'home-blue'){
		$cls = 'navbar navbar-inverse top-nav-collapse top-nav-collapse-one';
	}else if($url == 'home-parrot'){
		$cls = 'navbar navbar-inverse navbar-inverse-perrot top-nav-collapse top-nav-collapse-perrot';
	}else if($url == 'home-brown'){
		$cls = 'navbar navbar-inverse navbar-inverse-four top-nav-collapse top-nav-collapse-four';
	}
}

if($id != 17 && $id != 18 && $id != 52 && $id != 53 && $id != 54 && $id != 55 && $id != 56){
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
			$rowForLogo = $rows[0];
			if($rowForLogo['logo'] != ""){
				$logo = '<img src="'.CONFIG_PATH_THEME_NEW.'site_logo/'.$rowForLogo['logo'].'" class="img-responsive" style="width:100%;"/>';
			}else{
				$logo = CONFIG_SITE_TITLE;
			}
			?>
            <a class="navbar-brand" href="#"><?php echo $logo; ?></a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        	<ul class="nav navbar-nav <?php echo ($id == 2) ? 'navbar-nav-one' : ''; ?>">
            	<li>
                	<a href="#">HOME</a>
               	</li>
                <li>
                	<a href="#">PRODUCTS</a>
                </li>
                <li>
                	<a href="#">RESELLERS</a>
                </li>
                <li>
                	<a href="#">LOGIN</a>
                </li>
                <li>
                	<a href="#">REGISTER</a>
                </li>
                <li>
                	<a href="#">DOWNLOADS</a>
                </li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
</nav>
<?php
}
if($id == 2 || $id == 16 || $id == 39 || $id == 40 || $id == 41 ||  $id == 43 || $id == 50){
	$sql= 'select * from ' . SLIDER_MASTER.' where is_active = 1';
	$query = $mysql->query($sql);
	$rowCountForSlider = $mysql->rowCount($query);
	$rowsForSlider = $mysql->fetchArray($query);
?>
<div class="row clearfix">
	<?php /*?><div class="regular slider">
    <?php
	if($mysql->rowCount($query) > 0){
		$rows = $mysql->fetchArray($query);
		foreach($rows as $row){
	?>
    	<div>
        	<img src="<?php echo CONFIG_PATH_THEME_NEW.'slider_upload/'.$row['image']; ?>" style="margin:0;width: 100%;">
            <div class="slider-text">
            	<div class="bgTitleSlider">
                	<h1><?php echo $row['slider_title']; ?></h1>
                    <?php echo $row['notes']; ?>
                </div>
            </div>
        </div>
    <?php
		}
	}
	?>
  </div><?php */?>
  <header id="myCarousel" class="carousel slide">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <?php for($i = 0; $i<$rowCountForSlider; $i++){ ?>
            <li data-target="#myCarousel" data-slide-to="<?php echo $i; ?>" class="<?php ($i == 0) ? 'active' : ''; ?>"></li>
            <?php } ?>
        </ol>    
        <!-- Wrapper for Slides -->
        <div class="carousel-inner">
            <?php
			$j = 0;
			foreach($rowsForSlider as $rowSlider){
				$cl = '';
				if($j == 0){
					$cl = 'active';
				}
			?>
                <div class="item <?php echo $cl; ?>">
                    <!-- Set the first background image using inline CSS below. -->
                    <div class="fill">
                    	<img src="<?php echo CONFIG_PATH_THEME_NEW.'slider_upload/'.$rowSlider['image']; ?>" style="margin:0;width: 100%;">
                    </div>
                    <div class="slider-text">
                        <div class="bgTitleSlider">
                            <h1><?php echo $rowSlider['slider_title']; ?></h1>
                            <?php echo $rowSlider['notes']; ?>
                        </div>
                    </div>
                </div>
            <?php
				$j++;
			}
			?>
        </div>
    </header>
</div>
<?php
}
$fArray = array(1,2,16,17,18,19,20,21,34,35,36,37,38,39,40,41,43,50,52,53,54,55,56);
$isContainer = '';
if(!in_array($id,$fArray)){
	$isContainer = 'container';
}
?>
<div id="contentarea" class="<?php echo $isContainer; ?>">
	<?php //echo stripslashes($row['page_content']); ?>
    <?php
	
	$pageContent = stripslashes($row['page_content']);
		
	/*$rCountry = '[[country]]';
	$rLanguage = '[[language]]';
	$rCurrency = '[[currency]]';
	$rTimezone = '[[timezone]]';
	
	$repCountry = '<select name="country_id" class="form-control" id="country_id">
					<option value="0">Select Country</option>';
						$sql_language = 'select * from ' . COUNTRY_MASTER;
						$query_language = $mysql->query($sql_language);
						$rows_language = $mysql->fetchArray($query_language);
						foreach ($rows_language as $row_language) {
							$repCountry .= '<option value="' . $row_language['id'] . '">' . $row_language['countries_name'] . '</option>';
						}
	  $repCountry .= '</select>';
	  
	 $respLanguage = '<select name="language" class="form-control" id="language">
						<option value="">Select Language</option>';
						$sql_language = 'select * from ' . LANGUAGE_MASTER . ' order by id';
						$query_language = $mysql->query($sql_language);
						$rows_language = $mysql->fetchArray($query_language);
						foreach ($rows_language as $row_language) {
							$respLanguage .= '<option  value="' . $row_language['id'] . '">' . $row_language['language'] . '</option>';
						}
	$respLanguage .= '</select>';
	
	$respCurrency = '<select name="currency" class="form-control" id="currency" required>';
					$sql_currency = 'select * from ' . CURRENCY_MASTER . ' where `status`!=0 order by currency';
					$query_currency = $mysql->query($sql_currency);
					$rows_currency = $mysql->fetchArray($query_currency);
					foreach ($rows_currency as $row_currency) {
					   $respCurrency .= '<option  value="' . $row_currency['id'] . '">' . $mysql->prints($row_currency['currency']) . '</option>';
					}
	$respCurrency .= '</select>';
	
	$respTimezone = '<select name="timezone" class="form-control" id="timezone">';
					$respTimezone .= '<option value="">Select Time Zone</option>';
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
		$pageContent = str_replace($rLanguage,$respLanguage,$pageContent);
	}
	
	//check currency
	$checkCurrency = strpos($pageContent, $rCurrency);
	if ($checkCurrency !== false) {
		$pageContent = str_replace($rCurrency,$respCurrency,$pageContent);
	}
	
	//check timezone
	$checkTimzone = strpos($pageContent, $rTimezone);
	if ($checkTimzone !== false) {
		$pageContent = str_replace($rTimezone,$respTimezone,$pageContent);
	}*/
	
	/*$randKey = '[[rand]]';
	$respRandKey = rand();
	//check rand key
	$checkRandKey = strpos($pageContent, $randKey);
	if ($checkRandKey !== false) {
		$pageContent = str_replace($randKey,$respRandKey,$pageContent);
	}*/
	echo $pageContent;
	?>
</div>

<?php
$t1Footer = false;
$t2Footer = false;
if($id == 1 || $id == 16){
	$t1Footer = true;
}elseif($id == 2){
	$t2Footer = true;
}else{
	if($url == 'home' || $url == 'home-parrot'){
		$t1Footer = true;
	}elseif($url = 'home-blue'){
		$t2Footer = true;
	}
}
?>

<?php 
if($id != 17 && $id != 18 && $id != 52 && $id != 53 && $id != 54 && $id != 55 && $id != 56){
if($t1Footer == true){ ?>
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
                        <a href="#"><img src="/public/views/cms/assets/images/facebook.png" alt="facebook" class="footICon"></a>
                        <a href="#"><img src="/public/views/cms/assets/images/tweet.png" alt="facebook" class="footICon"></a>
                        <a href="#"><img src="/public/views/cms/assets/images/G-plus.png" alt="facebook" class="footICon"></a>
                        <a href="#"><img src="/public/views/cms/assets/images/youtube.png" alt="facebook" class="footICon"></a>
                    </div>
                </div>
            </div>
            <hr class="FootBorder">
        </div>
        <div class="CopyRight text-center">
            <span>&nbsp;IMEI.PK © 2016</span>
        </div>
    </div>
</div>
<?php } ?>
<?php if($t2Footer == true){ ?>
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
                <li>
                  <a href="#">UNLOCKER SERVICES</a>
                </li>
                <li>
                  <a href="#"> FILE SERVICES </a>
                </li>
                <li>
                  <a href="#"> SERVER SERVICES </a>
                </li>
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
                <li>
                  <a href="#">FILE SERVICE SOFTWARES</a>
                </li>
                <li>
                  <a href="#">FREE SOFTWARES</a>
                </li>
                <li>
                  <a href="#">ADVERTISEMENT</a>
                </li>
              </ul>
            </div>
          </div>
          <div class="col-sm-4 col-md-4">
            <div class="footerPRo clearfix">
              <h1>CONTACT US</h1>
              <hr class="footconBorder">
            </div>
            <div class="footAdd">
              Lorem&nbsp;ipsum&nbsp;dolor&nbsp;sit&nbsp;amet, consectetur&nbsp;adipiscing&nbsp;elit
              <p>Mobile: 0123456789</p>
              <button type="button" class="btn_foot">LOGIN</button>
            </div>
          </div>
        </div>
        <hr class="FootBorderIN2">
      </div>
      <div class="container">
        <div class="copyIN2  pull-left">
          IMEI.PK © 2016
        </div>
        <div class="FootSocialIN2  pull-right">
        	<i class="fa fa-facebook footFb" aria-hidden="true"></i>
            <i class="fa fa-google-plus footplus" aria-hidden="true"></i>
            <i class="fa fa-twitter foottwt" aria-hidden="true"></i>
        </div>
      </div>
    </div>
</div>
<?php } } ?>

<div id="panelCms">
	<input type="hidden" name="id" class="id" id="id" value="<?php echo $id; ?>" />
    <button onclick="saveContent(0)" class="btn btn-primary"> Save </button>
    <button class="btn btn-primary" onclick="saveContent(1)"> View Preview </button>
    <?php /*?><a href="<?php echo $row['url'].'.html'; ?>" target="_blank"><button class="btn btn-primary"> View Preview </button></a><?php */?>
    <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>cms_pages.html"><button class="btn btn-primary"> Back to List </button></a>
</div>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
    <!-- Modal content-->
    	<div class="modal-content">
        	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">CMS Page</h4>
           	</div>
            <form name="frmUpdateCmsPage" id="frmUpdateCmsPage" class="frmUpdateCmsPage" action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>cms_pages_edit_process.do" name="post">
            	<input type="hidden" name="hdnId" id="hdnId" class="hdnId" value="<?php echo $row['id']; ?>"/>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label> <?php $lang->prints('lbl_page_title'); ?> </label>
                                <input name="pageTitle" type="text" class="form-control" id="title" value="<?php echo (isset($row['title']) && $row['title']) ? stripslashes($row['title']) : ''; ?>" autocomplete="off" />
                            </div>
                            <div class="form-group">
                                <label> <?php $lang->prints('lbl_meta_tags'); ?> </label>
                                <textarea class="form-control" rows="5" style="resize:none;" name="pageMetaKeyword"><?php echo (isset($row['meta']) && $row['meta']) ? stripslashes($row['meta']) : ''; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label> <?php $lang->prints('lbl_page_url'); ?> </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><?php echo CONFIG_PATH_SITE; ?></span>
                                    <input type="text" name="pageUrl" class="form-control" aria-describedby="basic-addon3" autocomplete="off" value="<?php echo (isset($row['url']) && $row['url']) ? stripslashes($row['url']) : ''; ?>" readonly>
                                    <span class="input-group-addon">.html</span>
                                </div>
                            </div>
                            <?php if($id == 1 || $id == 2){ ?>
                            <div class="form-group">
                                <label class="c-input c-checkbox">
                                	<input type="checkbox" name="is_home" id="is_home" class="is_home" value="1" <?php echo (isset($row['is_home_page']) && $row['is_home_page']) ? 'checked' : ''; ?>>
                                    <span class="c-indicator c-indicator-success"></span> Set as Home
                                </label>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btnSavePage">Add</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?php echo CONFIG_PATH_THEME_NEW; ?>contentbuilder/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo CONFIG_PATH_THEME_NEW; ?>contentbuilder/jquery-ui.min.js" type="text/javascript"></script>
<script src="<?php echo CONFIG_PATH_THEME_NEW; ?>contentbuilder/contentbuilder.js" type="text/javascript"></script>
<script src="<?php echo CONFIG_PATH_THEME_NEW; ?>assets/js/scrolling-nav.js"></script> 
<!-- Bootstrap Core JavaScript --> 
<script src="<?php echo CONFIG_PATH_THEME_NEW; ?>assets/js/bootstrap.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/jquery.slick/1.6.0/slick.min.js"></script>
<script type="text/javascript">
jQuery(document).ready(function ($) {
	//Load saved Content
	if (localStorage.getItem('content') != null) {
		$("#contentarea").html(localStorage.getItem('content'));
	}

	$("#contentarea").contentbuilder({
		snippetFile: '<?php echo CONFIG_PATH_THEME_NEW; ?>assets/minimalist-basic/snippets.php',
		snippetOpen: false,
		toolbar: 'left',
		iconselect: '<?php echo CONFIG_PATH_THEME_NEW; ?>assets/ionicons/selecticon.html'
	});
	
	$(".regular").slick({
		dots: true,
		infinite: true,
		slidesToShow: 1,
		slidesToScroll: 1
	});
	
	$(document).on('click','.btnOpenPopup',function(e){
		$('#myModal').modal();
	});
	
	$(document).on('submit','.frmUpdateCmsPage',function(e){
		e.preventDefault();
		var _url = $(this).attr('action');
		var _formdata = $(this).serialize();
		var _isUpdate = 1;
		$.ajax({
			url: _url,
			data: {	formstring: _formdata, isUpdate:_isUpdate},
			type: "POST",
			dataType : "json",
		}).done(function( resp ) {
			if(resp.status == 1){
				alert(resp.msg);
				$('#myModal').modal('hide');
			}else{
				alert('something went wrong.');
			}
		}).fail(function( xhr, status, errorThrown ) {
		}).always(function( xhr, status ) {
		});
	});

});
	
function saveContent(previewSite){
	var _id = $('.id').val();
	var _url = '<?php echo CONFIG_PATH_SITE_ADMIN; ?>cms_pages_edit_process.do';
	var _content = $('#contentarea').data('contentbuilder').html();
	var _isPrivew = previewSite;
	
	$.ajax({
		url: _url,
		data: {	id: _id,content:_content,vPreview:_isPrivew},
		type: "POST",
		dataType : "json",
	}).done(function( resp ) {
		if(resp.status == 1){
			if(resp.isPrev == 1){
				//window.location.href = '/'+resp.url;
				var win = window.open('/'+resp.url,'_blank');
				if(win){
					//Browser has allowed it to be opened
					win.focus();
				}else{
					//Browser has blocked it
					alert('Please allow popups for this website');
				}
			}else{
				location.reload();
			}
		}else{
			alert('fail');
		}
	}).fail(function( xhr, status, errorThrown ) {
	}).always(function( xhr, status ) {
	});
}

function save() {
	var sHTML = $('#contentarea').data('contentbuilder').html(); //Get content

	localStorage.setItem('content', sHTML);
	alert('Saved Successfully');
}
</script>

</body>

<!-- Mirrored from innovastudio.com/builderdemo/example2.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 26 Sep 2016 07:01:43 GMT -->
</html>