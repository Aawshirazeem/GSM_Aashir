
<script type="text/javascript" src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/jssor.slider.mini.js"></script>
<!-- use jssor.slider.debug.js instead for debug -->
<script>
    jQuery(document).ready(function ($) {

        var jssor_1_SlideoTransitions = [
            [{b: 5500, d: 3000, o: -1, r: 240, e: {r: 2}}],
            [{b: -1, d: 1, o: -1, c: {x: 51.0, t: -51.0}}, {b: 0, d: 1000, o: 1, c: {x: -51.0, t: 51.0}, e: {o: 7, c: {x: 7, t: 7}}}],
            [{b: -1, d: 1, o: -1, sX: 9, sY: 9}, {b: 1000, d: 1000, o: 1, sX: -9, sY: -9, e: {sX: 2, sY: 2}}],
            [{b: -1, d: 1, o: -1, r: -180, sX: 9, sY: 9}, {b: 2000, d: 1000, o: 1, r: 180, sX: -9, sY: -9, e: {r: 2, sX: 2, sY: 2}}],
            [{b: -1, d: 1, o: -1}, {b: 3000, d: 2000, y: 180, o: 1, e: {y: 16}}],
            [{b: -1, d: 1, o: -1, r: -150}, {b: 7500, d: 1600, o: 1, r: 150, e: {r: 3}}],
            [{b: 10000, d: 2000, x: -379, e: {x: 7}}],
            [{b: 10000, d: 2000, x: -379, e: {x: 7}}],
            [{b: -1, d: 1, o: -1, r: 288, sX: 9, sY: 9}, {b: 9100, d: 900, x: -1400, y: -660, o: 1, r: -288, sX: -9, sY: -9, e: {r: 6}}, {b: 10000, d: 1600, x: -200, o: -1, e: {x: 16}}]
        ];

        var jssor_1_options = {
            $AutoPlay: true,
            $SlideDuration: 800,
            $SlideEasing: $Jease$.$OutQuint,
            $CaptionSliderOptions: {
                $Class: $JssorCaptionSlideo$,
                $Transitions: jssor_1_SlideoTransitions
            },
            $ArrowNavigatorOptions: {
                $Class: $JssorArrowNavigator$
            },
            $BulletNavigatorOptions: {
                $Class: $JssorBulletNavigator$
            }
        };

        var jssor_1_slider = new $JssorSlider$("jssor_1", jssor_1_options);

        //responsive code begin
        //you can remove responsive code if you don't want the slider scales while window resizing
        function ScaleSlider() {
            var refSize = jssor_1_slider.$Elmt.parentNode.clientWidth;
            if (refSize) {
                refSize = Math.min(refSize, 1920);
                jssor_1_slider.$ScaleWidth(refSize);
            }
            else {
                window.setTimeout(ScaleSlider, 30);
            }
        }
        ScaleSlider();
        $(window).bind("load", ScaleSlider);
        $(window).bind("resize", ScaleSlider);
        $(window).bind("orientationchange", ScaleSlider);
        //responsive code end
    });
</script>
<style>


    /* jssor slider bullet navigator skin 05 css */
    /*
    .jssorb05 div           (normal)
    .jssorb05 div:hover     (normal mouseover)
    .jssorb05 .av           (active)
    .jssorb05 .av:hover     (active mouseover)
    .jssorb05 .dn           (mousedown)
    */
    .jssorb05 {
        position: absolute;
    }
    .jssorb05 div, .jssorb05 div:hover, .jssorb05 .av {
        position: absolute;
        /* size of bullet elment */
        width: 16px;
        height: 16px;
        background: url('<?php echo CONFIG_PATH_PANEL; ?>assets/images/slider/b05.png') no-repeat;
        overflow: hidden;
        cursor: pointer;
    }
    .jssorb05 div { background-position: -7px -7px; }
    .jssorb05 div:hover, .jssorb05 .av:hover { background-position: -37px -7px; }
    .jssorb05 .av { background-position: -67px -7px; }
    .jssorb05 .dn, .jssorb05 .dn:hover { background-position: -97px -7px; }

    /* jssor slider arrow navigator skin 22 css */
    /*
    .jssora22l                  (normal)
    .jssora22r                  (normal)
    .jssora22l:hover            (normal mouseover)
    .jssora22r:hover            (normal mouseover)
    .jssora22l.jssora22ldn      (mousedown)
    .jssora22r.jssora22rdn      (mousedown)
    */
    .jssora22l, .jssora22r {
        display: block;
        position: absolute;
        /* size of arrow element */
        width: 40px;
        height: 58px;
        cursor: pointer;
        background: url('<?php echo CONFIG_PATH_PANEL; ?>assets/images/slider/a22.png') center center no-repeat;
        overflow: hidden;
    }
    .jssora22l { background-position: -10px -31px; }
    .jssora22r { background-position: -70px -31px; }
    .jssora22l:hover { background-position: -130px -31px; }
    .jssora22r:hover { background-position: -190px -31px; }
    .jssora22l.jssora22ldn { background-position: -250px -31px; }
    .jssora22r.jssora22rdn { background-position: -310px -31px; }
</style>
<!--Begin page-->
<div id="">

    <!-- Top Bar Start -->

    <!-- Top Bar End -->


    <!-- ========== Left Sidebar Start ========== -->


    <!-- Left Sidebar End --> 



    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->                      
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div id="jssor_1" style="position: relative; margin: 0 auto; top: 0px; left: 0px; width: 1300px; height: 500px; overflow: hidden; visibility: hidden;">
                <!-- Loading Screen -->
                <div data-u="loading" style="position: absolute; top: 0px; left: 0px;">
                    <div style="filter: alpha(opacity=70); opacity: 0.7; position: absolute; display: block; top: 0px; left: 0px; width: 100%; height: 100%;"></div>
                    <div style="position:absolute;display:block;background:url('<?php echo CONFIG_PATH_PANEL; ?>assets/images/slider/loading.gif') no-repeat center center;top:0px;left:0px;width:100%;height:100%;"></div>
                </div>
                <div data-u="slides" style="cursor: default; position: relative; top: 0px; left: 0px; width: 1300px; height: 500px; overflow: hidden;">
                    <div data-p="225.00" style="display: none;">
                        <img data-u="image" src="<?php echo CONFIG_PATH_PANEL; ?>assets/images/slider/picture1.jpg" />
                    </div>
                    <div data-p="225.00" style="display: none;">
                        <img data-u="image" src="<?php echo CONFIG_PATH_PANEL; ?>assets/images/slider/picture2.jpg" />
                    </div>
                    <div data-p="225.00" style="display: none;">
                        <img data-u="image" src="<?php echo CONFIG_PATH_PANEL; ?>assets/images/slider/picture3.jpg" />
                    </div>
                </div>
                <!-- Bullet Navigator -->
                <div data-u="navigator" class="jssorb05" style="bottom:16px;right:16px;" data-autocenter="1">
                    <!-- bullet navigator item prototype -->
                    <div data-u="prototype" style="width:16px;height:16px;"></div>
                </div>
                <!-- Arrow Navigator -->
                <span data-u="arrowleft" class="jssora22l" style="top:0px;left:12px;width:40px;height:58px;" data-autocenter="2"></span>
                <span data-u="arrowright" class="jssora22r" style="top:0px;right:12px;width:40px;height:58px;" data-autocenter="2"></span>
                <a href="http://www.jssor.com" style="display:none">Bootstrap Carousel</a>
            </div>
            <div class="">
                <div class="container">

                    <!-- Page-Title -->

                    <div class="row">
                        <div class="col-sm-12">
                            <h4 class="page-title">Products</h4>
                            <br>
                        </div>
                    </div>

                    <!-- SECTION FILTER
                    ================================================== -->
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 ">
                            <div class="portfolioFilter">
                                <a href="#" data-filter="*" class="current">All</a>
                                <?php
                                $sql = 'select  distinct(b.name) cat from ' . Product . ' a inner join ' . Category . ' b on a.category_id=b.id';
                                //echo $sql;exit;
                                $result = $mysql->getResult($sql);
                                if ($result['COUNT']) {
                                    foreach ($result['RESULT'] as $row) {

                                        echo '<a href="#" data-filter=".' . $row['cat'] . '">' . $row['cat'] . '</a>';
                                    }
                                }
                                ?>            


                            </div>
                        </div>
                    </div>

                    <div class="row port">
                        <div class="portfolioContainer m-b-15">
                            <?php
                            $sql = 'select b.id, b.name cat,a.name,a.description,a.cost,a.img from ' . Product . ' a
                                    inner join ' . Category . ' b
                                    on a.category_id=b.id';
                            //echo $sql;exit;
                            $result = $mysql->getResult($sql);
                            if ($result['COUNT']) {
                                foreach ($result['RESULT'] as $row) {

                                    $pathto = CONFIG_PATH_PANEL_ADMIN . 'img/product-list/' . $row['img'];
                                    ?>

                                    <div class="col-sm-6 col-lg-3 col-md-4 <?php echo $row['cat']; ?>">
                                        <div class="product-list-box thumb">
                                            <a href="<?php echo $pathto; ?>" class="image-popup" title="Screenshot-1">
                                                <img src="<?php echo $pathto; ?>" class="thumb-img" alt="work-thumbnail">
                                            </a>

                                            <div class="product-action">
                                                <a href="<?php echo '/impkk/shop/to_cart.html?id=' . $row['id']; ?>" class="btn btn-success btn-sm"> Add to Cart <i class="glyphicon glyphicon-plus-sign"></i></a>
                                                
                                            </div>

                                            <div class="detail">
                                                <h4 class="m-t-0 m-b-5"><a href="" class="text-dark"><?php echo $row['name']; ?></a> <?php echo $row['description']; ?></h4>
                                                <h5 class="m-0"><span class="text-custom"><?php echo '$ ' . $row['cost']; ?></span> <span class="text-muted m-l-15"></span></h5>
                                            </div>
                                        </div>
                                    </div>

    <?php
    }
}
?>
                        </div>
                    </div> <!-- End row -->




                </div> <!-- container -->
            </div>
        </div> <!-- content -->



    </div>


    <!-- ============================================================== -->
    <!-- End Right content here -->
    <!-- ============================================================== -->


    <!-- Right Sidebar -->

    <!-- /Right-bar -->

</div>
<!-- END wrapper -->