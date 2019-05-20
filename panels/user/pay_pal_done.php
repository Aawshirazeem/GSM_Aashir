<?php
if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}
//var_dump($_GET);
if ($_GET['msg'] != '' && $_GET['msg_type'] != '') {

    $msg = $_GET['msg'];
    $msg_type = $_GET['msg_type'];
}
?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">PayPal Express</div>
            <div class="panel-body">
                <?php
                if ($msg == "done" && $msg_type == 1) {
                    ?>
                    <center><h2 class="text-success">Payment Received!</h2><br><br><br>
                        <a href="<?php echo CONFIG_PATH_SITE_USER; ?>credits_reqeusts.html" class="btn btn-inverse">Go Back To Credit Request History Page</a>
                    </center>  <?php
                }
                ?>
                <?php
                if ($msg_type == 2) {
                    ?>
                    <center><h2 class="text-danger"><?php echo $msg; ?> </h2><br><br><br>
                        <a href="<?php echo CONFIG_PATH_SITE_USER; ?>credits_reqeusts.html" class="btn btn-inverse">Go Back To Credit Request History Page</a>
                    </center>  <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>
