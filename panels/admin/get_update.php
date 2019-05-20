<style>
    #myProgress {
        width: 100%;
        background-color: #ddd;
    }

    #myBar {
        width: 1%;
        height: 30px;
        background-color: #42b6d9;
    }
    
</style>

<div class="row m-b-20">
    <div class="col-lg-12" style=" background-color: rgba(0, 188, 212, 0.18);height: 150px">
        <br>
        <label> <?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_To_Enable_Site_Update_Check_Automation_Feature_To_Run,_Please_make_sure_you_setup_a_cron_job_to_run_Once_Every_Day._Create_the_following_Cron_Jobs_Using_Cpanel_of_Your_site')); ?><br> 



            <hr>
        </label>

        <?php
        //echo "<br><be>";
        $link .= '<pre>/usr/bin/wget -O - -q -t 1 ' . CONFIG_DOMAIN . CONFIG_PATH_SITE . 'system/cron/check_update.php >/dev/null 2>&1</pre>';
        // $link .= '<pre>/usr/bin/wget -O - -q -t 1 ' . CONFIG_DOMAIN . CONFIG_PATH_SITE . 'system/cron/send_imei_orders.php</pre>';
        echo $link;
        ?>
    </div>
</div>

<br class="clear">

<?php
if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$con = mysqli_connect("185.27.133.16", "gsmunion_upuser", "S+OXupg8lqaW", "gsmunion_upload");
if (mysqli_connect_errno()) {
    //echo "Failed to connect to MySQL: " . mysqli_connect_error();
    // $lang->prints('lbl_MYSQL_CONNECTION_ISSUE');
    //  echo '';
    echo '<h1 class="m-t-20" style="color:red;">Sorry Try Again Later ,Cant Connect To The Hub...</h1>';
    //$error_chk = 1;
    //sleep(2);
    //   echo '<meta http-equiv="refresh" content="1;url=' . CONFIG_PATH_SITE_ADMIN . 'dashboard.html">';
} else {
    
    $input = $_SERVER['HTTP_HOST'];
    $input = trim($input, '/');
    if (!preg_match('#^http(s)?://#', $input)) {
        $input = 'http://' . $input;
    }
    $urlParts = parse_url($input);
    $domain = preg_replace('/^www\./', '', $urlParts['host']);

    $qry_check = 'select * from tbl_users where is_update=1 and domain LIKE "%'.$domain.'%"  ';
 //   echo $qry_check;
// echo  $qry; 
    $queryCount = mysqli_query($con, $qry_check);

    if ($queryCount->num_rows > 0) {

        //   echo 'Update Available';
        // now whc version is avaliable
        //  $qry_check = 'select * from tbl_users where is_update=1 and domain="' . $_SERVER['HTTP_HOST'] . '"';
        $qry_ver = 'select * from tbl_versions v 
inner join tbl_users b
on v.id=b.latest_ver
where v.`status`=1 and b.domain="' . $domain . '" and v.cur=1
';
// echo  $qry; 
        $queryCount1 = mysqli_query($con, $qry_ver);

        if ($queryCount1->num_rows > 0) {

            // echo '';
            echo '<h2 style="color:red;">You Already Have The latest Version</h2><br>';
            $qry = 'update ' . ADMIN_MASTER . ' set is_update=0';
            $mysql->query($qry);
        } else {
            // show the button to get the update
            $qry12 = 'select * from tbl_versions v
where v.`status`=1

order by v.id desc

limit 1';
// echo  $qry; 
            $queryCount = mysqli_query($con, $qry12);
// Total processes

            $result2 = $con->query($qry12);
            $result2 = $result2->fetch_assoc();
            $lat_version = $result2['name'];
            $lat_version_desc = $result2['desc'];

            echo '<h2 style="color:red;">New Version is Available</h2><br>';
            echo 'The New Version is:<b>' . $lat_version . '</b><br>';
            echo '<br>------------The New Version Includes------------<br>' . $lat_version_desc;

            $qry = 'update ' . ADMIN_MASTER . ' set is_update=1';
            $mysql->query($qry);
            ?>
            <br><br>
            <a onclick="move()" href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>download_update.html" class="btn btn-success" style="text-align: center">Click Here To Get The Latest Version</a>
            <br><br>
            <div id="lbar">
                <div id="myProgress">
                    <div id="myBar"></div>
                </div>
                <br>
                <center> <label><b>Updating Please Do Not Close The Tab or Browser.</b></label></center>
            </div>
            <?php
        }
    } else {
        // echo '';
        echo '<h2 style="color:red;">No New Update Is Available</h2><br>';
    }

    $con->close();
}
?>
<script type="text/javascript">
    $('#lbar').css('display', 'none');


    function move() {
        $('#lbar').css('display', 'block');
        var elem = document.getElementById("myBar");
        var width = 1;
        var id = setInterval(frame, 35);
        function frame() {
            if (width >= 100) {
                clearInterval(id);
            } else {
                width++;
                elem.style.width = width + '%';
            }
        }
    }

</script>