<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$admin->checkLogin();
$admin->reject();
$imeipool="";
if (!file_exists($_FILES['fileToUpload']['tmp_name']) || !is_uploaded_file($_FILES['fileToUpload']['tmp_name'])) {

    // no file
    if (strlen(trim($_POST['imei_pool'])) == 0) {
        header("location:" . CONFIG_PATH_SITE_ADMIN . "imei_bulk_reply.html?reply=" . urlencode('reply_no_imei'));
        exit();
    }
//var_dump($_POST);
//exit;
    $rejectedword = "";
    $replyseparator = " ";

    if (isset($_POST['rsby']) && $_POST['rsby'] != "") {
        $replyseparator = $_POST['rsby'];
        $replyseparator = substr($replyseparator, 0, 1);
    }
    if (isset($_POST['rjctwords']))
        $rejectedword = $_POST['rjctwords'];

    $tool = $_POST['tool'];
    $qryat;
    if ($tool != "")
        $qryat = " and a.tool_id=" . $tool;
//exit;
//echo $qryat;
//exit;
    $IMEIS;
    $CODES;
    $AR;
    $counter = 0;
    $imeipool = explode("\n", $_POST['imei_pool']);
//echo '<pre>';
//var_dump($imeipool);
//print_r($imeipool);
    foreach ($imeipool as $a) {
        //echo 'aa is :'. $a;
        //var_dump($a);
        // echo '<pre>';
        if (strlen($a) != 1) {
            $temp = explode($replyseparator, $a);
            //var_dump($temp);
            $IMEIS[$counter] = $temp[0];
            $tempcode = trim($temp[1]);
            // echo strlen($tempcode);
            if (strlen($tempcode) != 0 && $tempcode == $rejectedword) {
                $CODES[$counter] = $rejectedword;
                $AR[$counter] = 0;
            } else {
                $CODES[$counter] = $tempcode;
                $AR[$counter] = 1;
            }

            $counter++;
        }
    }

//echo '<pre>';
//var_dump($IMEIS);exit;
//var_dump($CODES);
//var_dump($AR);
    foreach ($IMEIS as $imei) {
        //  if (is_numeric($imei)) {
        $txtImeis .= $imei . ',';
        //   }
    }
    $txtImeis = rtrim($txtImeis, ',');
//echo $txtImeis;
//
//exit;
    if (strlen(trim($txtImeis)) == 0) {
        header("location:" . CONFIG_PATH_SITE_ADMIN . "imei_bulk_reply.html?reply=" . urlencode('reply_imei_issue'));
        exit();
    }
    ?><div class="clearfix"></div>
    <div class="row" id="real_form">
        <div class="col-md-12">
            <div class="panel panel-color panel-success">
                <div class="panel-heading"><b><?php echo $admin->wordTrans($admin->getUserLang(), 'IMEI BULK REPLY'); ?></b>            
                  
                </div>

                <div class="panel-body">
                    <form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>imei_bulk_reply_process.do" method="post" class="form-horizontal tasi-form">

                        <table class="table table-bordered table-hover" style="font-size: 13px;
word-wrap:break-word;">
                            <tr><th width="16">ALL<input type="checkbox" class="autoCheckAll" data-class="autoCheckMe"  /></th>
                                <th>#</th>
                                <th>Service</th>
                                <th>Date</th>
                                <th>User</th>
                                <th>IMEI</th>
                                <th>REPLY</th>
                                <th width="16">REJECT<input type="checkbox" class="autoUnCheckAll" id="autoUnCheckAll" data-class="autoUnCheckMe"  /></th>
                            </tr>

                            <?php
                            $sql = "select a.id,b.tool_name,cm.prefix, cm.suffix,a.credits, a.date_time,c.username,a.`status`,a.imei from ".ORDER_IMEI_MASTER." a
left join ".IMEI_TOOL_MASTER." b
on a.tool_id=b.id
left join ".USER_MASTER." c
on a.user_id=c.id

left join " . CURRENCY_MASTER . " cm on cm.id = c.currency_id 


where a.imei in (" . $txtImeis . ")  and a.status=1" . $qryat;
//echo $sql;
                            $query = $mysql->query($sql);
                            $totalRows = $mysql->rowCount($query);
                            $f = 0;

                            if ($totalRows > 0) {
                                $rows = $mysql->fetchArray($query);
                                // var_dump($rows);
                                $type = 'locked';
                                foreach ($rows as $row) {
                                    $f++;


                                    //------------------get all the----------------
                                    $temimei = $row['imei'];
                                    $key = array_search($temimei, $IMEIS); // $key = 2;
                                    $tempcode = $CODES[$key];
                                    $tempaccrej = $AR[$key];

                                    //-----------------end------------------------


                                    echo '<tr>';
                                    echo '<input type="hidden" name=Ids[]" value=' . $row['id'] . '>';  // to send ids of users to processing page
                                    echo '<td>';
                                    echo '<input type="checkbox" class="autoCheckMe" checked  name="locked[]" value=' . $row['id'] . '>';
                                    echo '</td>';
                                    echo '<td>';
                                    echo $f . '<br>Order#' . $row['id'] . '<br><span class="label label-pill label-primary label-lg">' .$objCredits->printCredits($row['credits'], $row['prefix'], $row['suffix']).'</span>';
                                    echo '</td>';
                                    echo '<td>';
                                    echo $row['tool_name'];
                                    echo '</td>';
                                    echo '<td>';


                                    // time zone logic

                                    $sql = 'select a.timezone from ' . TIMEZONE_MASTER . ' as a where a.is_default=1';
                                    $query = $mysql->query($sql);
                                    $rowCount = $mysql->rowCount($query);
                                    if ($rowCount != 0) {
                                        $rows = $mysql->fetchArray($query);
                                        $row11 = $rows[0];
                                        $dftimezonewebsite = $row11['timezone'];
                                    }

                                    //get defaul timezone of admin
                                    $sql = 'select am.*,tz.timezone from ' . ADMIN_MASTER . ' as am
                                                                    inner join ' . TIMEZONE_MASTER . ' as tz
                                                                    on am.timezone_id=tz.id
                                                                    where am.id=' . $admin->getUserId();
                                    $query = $mysql->query($sql);
                                    $rowCount = $mysql->rowCount($query);
                                    if ($rowCount != 0) {
                                        $rows = $mysql->fetchArray($query);
                                        $row22 = $rows[0];
                                        $dftimezoneadmin = $row22['timezone'];
                                    }



                                    $date = new DateTime($row['date_time'], new DateTimeZone($dftimezonewebsite));
                                    $date->setTimezone(new DateTimeZone($dftimezoneadmin));
                                    $finaldate = $date->format('d-M-Y H:i');

                                    echo $finaldate;
                                    //echo $row['date_time'];
                                    echo '</td>';
                                    echo '<td>';
                                    echo $row['username'];
                                    echo '</td>';
                                    echo '<td>';
                                    echo $row['imei'];
                                    echo '</td>';
                                    echo '<td>';
                                    echo '<input name="unlock_code_' . $row['id'] . '" id="unlock_code_' . $row['id'] . '" class="form-control" style="display:inline" value="' . $tempcode . '" />';
                                    echo '</td>';
                                    echo '<td>';
                                    echo '<input type="checkbox" class="autounCheckMe" ' . $tempaccrej . '   ' . (($tempaccrej == 0) ? "checked" : '') . '  name="unavailable_' . $row['id'] . '" id="unchk"';
                                    echo '</td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo 'No Recode Found';
                            }
                            ?>
                        </table> <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_reply_selected')); ?>" class="btn btn-success"/>
                         <a href="#" class="btn btn-primary" onclick="down()">Export</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
} else {


    // if file uploaded

    $myfile = fopen($_FILES['fileToUpload']['tmp_name'], "r") or die("Unable to open file!");
    $filedata = fread($myfile, filesize($_FILES['fileToUpload']['tmp_name']));
    fclose($myfile);
    $imeipool = explode("\n", $filedata);
    $rejectedword = "";
    $replyseparator = " ";

    if (isset($_POST['rsby']) && $_POST['rsby'] != "") {
        $replyseparator = $_POST['rsby'];
        $replyseparator = substr($replyseparator, 0, 1);
    }
    if (isset($_POST['rjctwords']))
        $rejectedword = $_POST['rjctwords'];

    $tool = $_POST['tool'];
    $qryat;
    if ($tool != "")
        $qryat = " and a.tool_id=" . $tool;
//exit;
//echo $qryat;
//exit;
    $IMEIS;
    $CODES;
    $AR;
    $counter = 0;
    foreach ($imeipool as $a) {
        //echo 'aa is :'. $a;
        //var_dump($a);
        // echo '<pre>';
        if (strlen($a) != 1) {
            $temp = explode($replyseparator, $a);
            //var_dump($temp);
            $IMEIS[$counter] = $temp[0];
            $tempcode = trim($temp[1]);
            // echo strlen($tempcode);
            if (strlen($tempcode) == 0 && $tempcode == $rejectedword) {
                $CODES[$counter] = $rejectedword;
                $AR[$counter] = 0;
            } else {
                $CODES[$counter] = $tempcode;
                $AR[$counter] = 1;
            }

            $counter++;
        }
    }

//echo '<pre>';
//var_dump($IMEIS);exit;
//var_dump($CODES);
//var_dump($AR);
    foreach ($IMEIS as $imei) {
        if (is_numeric($imei)) {
            $txtImeis .= $imei . ',';
        }
    }
    $txtImeis = rtrim($txtImeis, ',');
//echo $txtImeis;
//
//exit;
    if (strlen(trim($txtImeis)) == 0) {
        header("location:" . CONFIG_PATH_SITE_ADMIN . "imei_bulk_reply.html?reply=" . urlencode('reply_check_input_file'));
        exit();
    }
    ?><div class="clearfix"></div>
    <div class="row" id="real_form">
        <div class="col-md-12">
            <div class="panel panel-color panel-success">
                <div class="panel-heading"><b><?php echo $admin->wordTrans($admin->getUserLang(), 'IMEI BULK REPLY'); ?></b>            

                </div>
                <div class="panel-body">
                    <form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>imei_bulk_reply_process.do" method="post" class="form-horizontal tasi-form">

                        <table class="table table-striped table-hover" style="font-size: 13px;
word-wrap:break-word;">
                            <tr><th width="16">ALL<input type="checkbox" class="autoCheckAll" data-class="autoCheckMe"  /></th>
                                <th>#</th>
                                <th>Service</th>
                                <th>Date</th>
                                <th>User</th>
                                <th>IMEI</th>
                                <th>REPLY</th>
                                <th width="16">REJECT<input type="checkbox" class="autoUnCheckAll" id="autoUnCheckAll" data-class="autoUnCheckMe"  /></th>
                            </tr>

                            <?php
                            $sql = "select a.id,b.tool_name,cm.prefix, cm.suffix,a.credits, a.date_time,c.username,a.`status`,a.imei from ".ORDER_IMEI_MASTER." a
left join ".IMEI_TOOL_MASTER." b
on a.tool_id=b.id
left join ".USER_MASTER." c
on a.user_id=c.id

left join " . CURRENCY_MASTER . " cm on cm.id = c.currency_id 


where a.imei in (" . $txtImeis . ")  and a.status=1" . $qryat;
//echo $sql;
                            $query = $mysql->query($sql);
                            $totalRows = $mysql->rowCount($query);

                            if ($totalRows > 0) {
                                $f = 0;
                                $rows = $mysql->fetchArray($query);
                                // var_dump($rows);
                                $type = 'locked';
                                foreach ($rows as $row) {

                                    $f++;

                                    //------------------get all the----------------
                                    $temimei = $row['imei'];
                                    $key = array_search($temimei, $IMEIS); // $key = 2;
                                    $tempcode = $CODES[$key];
                                    $tempaccrej = $AR[$key];

                                    //-----------------end------------------------


                                    echo '<tr>';
                                    echo '<input type="hidden" name=Ids[]" value=' . $row['id'] . '>';  // to send ids of users to processing page
                                    echo '<td>';
                                    echo '<input type="checkbox" class="autoCheckMe" checked  name="locked[]" value=' . $row['id'] . '>';
                                    echo '</td>';
                                    echo '<td>';
                                    echo $f . '<br>Order#' . $row['id'] . '<br><span class="label label-pill label-primary label-lg">' .$objCredits->printCredits($row['credits'], $row['prefix'], $row['suffix']).'</span>';
                                    echo '</td>';
                                    echo '<td>';
                                    echo $row['tool_name'];
                                    echo '</td>';
                                    echo '<td>';
                                    echo $row['date_time'];
                                    echo '</td>';
                                    echo '<td>';
                                    echo $row['username'];
                                    echo '</td>';
                                    echo '<td>';
                                    echo $row['imei'];
                                    echo '</td>';
                                    echo '<td>';
                                    echo '<input name="unlock_code_' . $row['id'] . '" id="unlock_code_' . $row['id'] . '" class="form-control" style="display:inline" value="' . $tempcode . '" />';
                                    echo '</td>';
                                    echo '<td>';
                                    echo '<input type="checkbox" class="autounCheckMe" ' . $tempaccrej . '   ' . (($tempaccrej == 0) ? "checked" : '') . '  name="unavailable_' . $row['id'] . '" id="unchk"';
                                    echo '</td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo 'No Recode Found';
                            }
                            ?>
                        </table> <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_reply_selected')); ?>" class="btn btn-success"/>
                       <a href="#" class="btn btn-primary" onclick="down()">Export</a>
                    </form>
                      
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>


<script type="text/javascript">
    $("#autoUnCheckAll").change(function () {
        $(".autounCheckMe").prop('checked', $(this).prop("checked"));
    });
    function down() {

        //alert('ok');
        var txtt="";
        
        var txt=<?php echo json_encode($imeipool);?>;
        for (i = 0; i < txt.length; i++) {
    //text += "The number is " + i + "<br>";
    txtt+=txt[i];
     console.log(txt[i]);
}
     
     //   var res =JSON.stringify(txt);
       //   alert(res);
        //textToWrite = $('#downloadContent').text().replace(/\n/g, "\r\n");
        textToWrite=txtt;
        var element = document.createElement('a');
        element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(textToWrite));
        element.setAttribute('download', 'download.txt');
        element.style.display = 'none';
        document.body.appendChild(element);
        element.click();
        document.body.removeChild(element);
    }
</script>