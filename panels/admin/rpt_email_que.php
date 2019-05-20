<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
defined("_VALID_ACCESS") or die("Restricted Access");
$paging = new paging();

$offset = (isset($_GET["offset"])) ? $_GET["offset"] : 0;

$i = $offset;

$qType = '';

$i++;

$limit = CONFIG_ORDER_PAGE_SIZE;

$qLimit = " limit $offset,$limit";
//echo ok;
?>

<div class="row m-b-20">

    <div class="col-xs-12">

        <ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>

            <li class="slideInDown wow animated"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Reports')); ?></li>           

            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Email_Que')); ?></li>

        </ol>

    </div>

</div>

<div class="row">
    <div class="col-lg-12">
        
        <h4 class="panel-heading m-b-20">

				<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Email_Que')); ?>
		</h4>
		
		<div class="table-responsive">
        
        <table class="table table-hover table-striped table-bordered">

            <tr>

                <th width="10"></th>

                <th class="TA_C"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_date')); ?> </th>

                <th class="TA_C"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_mail_to')); ?> </th>

              

                <th class="TA_C"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_mail_from')); ?> </th>

                <th class="TA_C"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_mail_subject')); ?> </th>


            </tr>

            <?php
            //$qType = ($qType == '') ? '' : ' where ' . $qType;



          


            $sql = 'select * from nxt_email_temp_queue order by id desc';

            $query = $mysql->query($sql . $qLimit);
            $pCode = $paging->recordsetNav($sql, CONFIG_PATH_SITE_ADMIN . 'rpt_email_que.html', $offset, $limit, $extraURL);


            if ($mysql->rowCount($query) > 0) {

                $logins = $mysql->fetchArray($query);

                foreach ($logins as $login) {
                    ?>

                    <tr>

                        <td><small><?php echo $i++ ?></small></td>

                        <!-- <td><?php //if($login['username'] != ''){ echo '<i class="icon-arrow-left"></i>'; }  ?></td> -->

                        <td><?php
                            echo $finaldate = $admin->datecalculate($login['time_stamp']);
                            ?>
                        </td>

                        <td><?php echo $login['mail_to']; ?></td>

                   
                        <td><?php echo $login['mail_from']; ?></td>
                        <td><?php echo $login['mail_subject']; ?></td>



                    </tr>

                    <?php
                }
            } else {

                echo '<tr><td colspan="6">Email Que Empty</td></tr>';
            }
            ?>

        </table>
		
		</div>

        <div class="row m-t-20">
            <div class="col-md-6 p-l-0">
                <div class="TA_C navigation" id="paging">
                    <?php echo $pCode;   ?>
                </div>
            </div>
            <div class="col-md-6">

            </div>
        </div>

    </div>

</div>