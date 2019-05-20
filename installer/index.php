







<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
		<meta name="author" content="Coderthemes">

		<link rel="shortcut icon" href="assets/images/favicon_1.ico">

		<title>GsmUnion - Installation</title>
		
		<!--Form Wizard-->
        <link rel="stylesheet" type="text/css" href="assets/plugins/jquery.steps/demo/css/jquery.steps.css" />

		<link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/core.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/components.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/pages.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/responsive.css" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

        <script src="assets/js/modernizr.min.js"></script>

	</head>

	<body class="fixed-left">

        <div class="animationload">
            <div class="loader"></div>
        </div>

		<!-- Begin page -->
		<div id="wrapper">

          
            <!-- Top Bar End -->


                <!-- ========== Left Sidebar Start ========== -->

                
                <!-- Left Sidebar End -->

			<!-- ============================================================== -->
			<!-- Start right Content here -->
			<!-- ============================================================== -->
			<div class="content-page">
				<!-- Start content -->
				<div class="content">
					<div class="container">

						

                        
      



                        <!-- Wizard with Validation -->
                        
                        <div class="row">
							<div class="col-sm-8">
								<div class="card-box">
								<!-- Page-Title -->
						<div class="row">
							<div class="col-sm-12">
								<h1 class="page-title" align="center">GsmUnion Installation Wizard</h1>
								
							</div>
						</div>	
							<?php
                                                        
                                                        if(isset($_GET['reply']))
                                                        {
                                                            $reply=$_GET['reply'];
                                                            ?>
                                                                    <label class="control-label " for="confirm"style="color:red" >Error:<?php echo $reply; ?></label>          
                                                       <?php     
                                                        }
                                                        ?>
                                                                    <form id="wizard-validation-form" action="installer_proccess.php" method="POST">
                                        <div>
                                            <h3>Step 1</h3>
                                            <section>
                                             <?php
                                             
                                    $pre_error='';
      

$extensions = get_loaded_extensions();
   
  if (phpversion() < '5.0') {
   $pre_error = 'You need to use PHP5 or above for our site!<br />';
  }
  if (ini_get('session.auto_start')) {
   $pre_error .= 'Our site will not work with session.auto_start enabled!<br />';
  }
  if (!extension_loaded('mysql')) {
   $pre_error .= 'MySQL extension needs to be loaded for our site to work!<br />';
  }
  if (!in_array('ionCube Loader', $extensions)) {
   $pre_error .= 'ionCube Loader extension needs to be loaded for our site to work!<br />';
  }
  if (!extension_loaded('gd')) {
   $pre_error .= 'GD extension needs to be loaded for our site to work!<br />';
  }
  
//  if (!is_writable('config.php')) {
//   $pre_error .= 'config.php needs to be writable for our site to be installed!';
//  }
   
                                             ?>
                                                <input type="hidden" name="system-error" value="<?php echo $pre_error; ?>" />
                                                <h1>System Requirments:</h1>
                                                  <table width="100%">
  <tr>
   <td>PHP Version:(<?php echo phpversion(); ?>)</td>
 
   <td class="req"><b style="color: <?php echo (phpversion() >= '5.0') ? 'green':'red' ?>;"><?php echo (phpversion() >= '5.0') ? 'Ok' : 'Not Ok'; ?></b></td>
  </tr>
  <tr>
   <td>Session Auto Start:</td>
 
   <td><b style="color: <?php echo (!ini_get('session_auto_start') >= '5.0') ? 'green':'red' ?>;"><?php echo (!ini_get('session_auto_start')) ? 'Ok' : 'Not Ok'; ?></b></td>
  </tr>
  <tr>
   <td>MySQL:</td>
 
   <td><b style="color: <?php echo extension_loaded('mysql') ? 'green':'red' ?>;"><?php echo extension_loaded('mysql') ? 'Ok' : 'Not Ok'; ?></b></td>
  </tr>
  <tr>
   <td>ionCube Loader:(<?php echo ioncube_loader_version(); ?>)</td>
 
   <td><b style="color: <?php echo in_array('ionCube Loader', $extensions) ? 'green':'red' ?>;"><?php echo in_array('ionCube Loader', $extensions) ? 'Ok' : 'Not Ok'; ?></b></td>
  </tr>
  <tr>
   <td>GD:</td>
   
   <td><b style="color: <?php echo extension_loaded('gd') ? 'green':'red' ?>;"><?php echo extension_loaded('gd') ? 'Ok' : 'Not Ok'; ?></b></td>
  </tr>

  </table>
                                                <?php echo $pre_error; ?>
                                            </section>
                                            <h3>Step 2</h3>
                                            <section>
                                                <h1>Database Configuration </h1>
                                                 <div class="col-lg-10">
                                                     <label class="col-lg-3 control-label " for="confirm">Host Name*</label>
                                                <input type="text" name="host" class="required form-control" />
                                                  </div>
                                                <div class="col-lg-10">
                                                     <label class="col-lg-3 control-label " for="confirm">User Name*</label>
                                                <input type="text" name="uname" class="required form-control" />
                                                  </div>
                                                <div class="col-lg-10">
                                                     <label class="col-lg-3 control-label " for="confirm">Password*</label>
                                                <input type="text" name="password" class="required form-control" />
                                                  </div>
                                                <div class="col-lg-10">
                                                     <label class="col-lg-4 control-label " for="confirm">Database Name*</label>
                                                <input type="text" name="dbname" class="required form-control" />
                                                  </div>
                                               
                                           
                                            </section>
                                            <h3>Step 3</h3>
                                            <section>
                                                <h1>Administrator Account </h1>
                                                 <div class="col-lg-10">
                                                     <label class="col-lg-3 control-label " for="confirm">User Name*</label>
                                                <input type="text" name="admin-uname" class="required form-control" />
                                                  </div>
                                                <div class="col-lg-10">
                                                     <label class="col-lg-3 control-label " for="confirm">Password*</label>
                                                     <input type="password" name="admin-pass" class="required form-control" />
                                                  </div>
                                                <div class="col-lg-10">
                                                     <label class="col-lg-3 control-label " for="confirm">Admin Email*</label>
                                                <input type="text" name="admin-email" class="required email form-control" />
                                                <input type="hidden" name="admin-timezone" value="424" />
                                                  </div>
                                            </section>
                                            <h3>Step Final</h3>
                                            <section>
                                                  <h1>Website General Setting </h1>
                                                 <div class="col-lg-10">
                                                     <label class="col-lg-3 control-label " for="confirm">Website Title*</label>
                                                <input type="text" name="title" class="required form-control" />
                                                  </div>
                                                <div class="col-lg-10">
                                                     <label class="col-lg-3 control-label " for="confirm">Tag Line*</label>
                                                     <input type="text" name="tag" class="required form-control" />
                                                  </div>
                                                <div class="col-lg-10">
                                                     <label class="col-lg-3 control-label " for="confirm">Domain Name*</label>
                                                <input type="text" name="domain" class="required form-control" />
                                                  </div>
                                                     

                                            </section>
                                        </div>
                                    </form>
								</div>
							</div>
						</div>
                        <!-- End row -->

                    </div> <!-- container -->
                               
                </div> <!-- content -->

                <footer class="footer">
                    2016 © GsmUnion Corporation.
                </footer>

            </div>
            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->


         


        </div>
        <!-- END wrapper -->

        <script>
            var resizefunc = [];
        </script>

        <!-- jQuery  -->
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/detect.js"></script>
        <script src="assets/js/fastclick.js"></script>
        <script src="assets/js/jquery.slimscroll.js"></script>
        <script src="assets/js/jquery.blockUI.js"></script>
        <script src="assets/js/waves.js"></script>
        <script src="assets/js/wow.min.js"></script>
        <script src="assets/js/jquery.nicescroll.js"></script>
        <script src="assets/js/jquery.scrollTo.min.js"></script>


        <script src="assets/js/jquery.core.js"></script>
        <script src="assets/js/jquery.app.js"></script>

        <!--Form Validation-->
        <script src="assets/plugins/bootstrapvalidator/dist/js/bootstrapValidator.js" type="text/javascript"></script>

        <!--Form Wizard-->
        <script src="assets/plugins/jquery.steps/build/jquery.steps.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="assets/plugins/jquery-validation/dist/jquery.validate.min.js"></script>

        <!--wizard initialization-->
        <script src="assets/pages/jquery.wizard-init.js" type="text/javascript"></script>

	
	</body>
</html>