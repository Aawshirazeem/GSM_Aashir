<!doctype html>
<html lang="us">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="Coderthemes">

        <link rel="shortcut icon" href="<?php echo CONFIG_PATH_PANEL; ?>assets/images/favicon_1.ico">

        <title>IMEI.PK- Live Chat</title>

        <!--Morris Chart CSS -->
        <link rel="stylesheet" href="<?php echo CONFIG_PATH_PANEL; ?>assets/plugins/morris/morris.css">

        <link href="<?php echo CONFIG_PATH_PANEL; ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo CONFIG_PATH_PANEL; ?>assets/css/core.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo CONFIG_PATH_PANEL; ?>assets/css/components.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo CONFIG_PATH_PANEL; ?>assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo CONFIG_PATH_PANEL; ?>assets/css/pages.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo CONFIG_PATH_PANEL; ?>assets/css/responsive.css" rel="stylesheet" type="text/css" />
        <!--venobox lightbox-->
        <link rel="stylesheet" href="<?php echo CONFIG_PATH_PANEL; ?>assets/plugins/magnific-popup/dist/magnific-popup.css"/>

        <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

        <script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/modernizr.min.js"></script>




    </head>


    <body class="" style="padding-right:15%;background-color: ">
        <div class="topbar">

            <!-- LOGO -->
            <div class="topbar-left">
                <div class="text-center">
                    <a href="index.html" class="logo"><i class="icon-magnet icon-c-logo"></i><span>IMEI<i class="md md-album"></i>LiveChat</span></a>
                </div>
            </div>

            <!-- Button mobile view to collapse sidebar menu -->
            <div class="navbar navbar-default" role="navigation">
                <div class="container">
                    <div class="">

                    </div>
                    <!--/.nav-collapse -->
                </div>
            </div>
        </div>

        <?php
         if (file_exists(CONFIG_PATH_SHOP_ABSOLUTE . $page . '.php')) {
                            include(CONFIG_PATH_SHOP_ABSOLUTE . $page . '.php');
                        }
        
      //  include(CONFIG_PATH_SHOP_ABSOLUTE . $page . '.php');
        ?>
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

        <script type="text/javascript" src="<?php echo CONFIG_PATH_PANEL; ?>assets/plugins/isotope/dist/isotope.pkgd.min.js"></script>
        <script type="text/javascript" src="<?php echo CONFIG_PATH_PANEL; ?>assets/plugins/magnific-popup/dist/jquery.magnific-popup.min.js"></script>

        <script type="text/javascript">
                $(window).load(function () {
                    var $container = $('.portfolioContainer');
                    $container.isotope({
                        filter: '*',
                        animationOptions: {
                            duration: 750,
                            easing: 'linear',
                            queue: false
                        }
                    });

                    $('.portfolioFilter a').click(function () {
                        $('.portfolioFilter .current').removeClass('current');
                        $(this).addClass('current');

                        var selector = $(this).attr('data-filter');
                        $container.isotope({
                            filter: selector,
                            animationOptions: {
                                duration: 750,
                                easing: 'linear',
                                queue: false
                            }
                        });
                        return false;
                    });
                });
                $(document).ready(function () {
                    $('.image-popup').magnificPopup({
                        type: 'image',
                        closeOnContentClick: true,
                        mainClass: 'mfp-fade',
                        gallery: {
                            enabled: true,
                            navigateByImgClick: true,
                            preload: [0, 1] // Will preload 0 - before current, and 1 after the current image
                        }
                    });
                });
        </script>   


    </body>
    <footer class="" style="padding-left: 55%">
        <div style="color: white">
            2015 © IMEI.PK SHOP
        </div>
    </footer>

</html>