<?php

defined("_VALID_ACCESS") or die("Restricted Access");

?>

<div class="row m-b-20">

	<?php

    //$paging = new paging();

    $id = $request->getInt('id');

    // echo $id;exit;

    //	$i = $offset;

    //	$i++;

    //	$limit = 100;

    //	$qLimit = " limit $offset,$limit";







    $paging = new paging();

    $offset = (isset($_GET["offset"])) ? $_GET["offset"] : 0;

    $limit = CONFIG_ORDER_PAGE_SIZE;

    $qLimit = " limit $offset,$limit";

    $extraURL = "";

    //$id="";



    $useremailll = "";

    if ($id != "") {

        $sql2 = 'select a.email,a.username from nxt_user_master a where a.id=' . $id;

        $query22 = $mysql->query($sql2);

        $rowCount2 = $mysql->rowCount($query22);

        if ($rowCount2 != 0) {

            $rowsss = $mysql->fetchArray($query22);

            $row223 = $rowsss[0];

            $useremailll = $row223['email'];

            $usernameee = $row223['username'];

        }

    }

    ?>

    <?php

    //$qType = ($qType == '') ? '' : ' where ' . $qType;

//			$qType = '';

//			if($id != 0)

//			{

//				$qType =' where  ctm.user_id = ' . $id .' or ctm.user_id2=' . $id;

//                                

// 

//

//			}

//			$sql = 'select

//					ctm.id, ctm.date_time, ctm.credits, ctm.views, ctm.trans_type,ctm.info,ctm.user_id,

//					um.username as username1, 

//					um2.username as username2,

//					date(ctm.date_time) as dt,

//					im.id invoice_id

//					from ' . CREDIT_TRANSECTION_MASTER . ' ctm

//					left join ' . INVOICE_MASTER . ' im on (im.txn_id=ctm.id)

//					left join ' . USER_MASTER . ' um on (ctm.user_id=um.id)

//					left join ' . USER_MASTER . ' um2 on (ctm.user_id2=um2.id)

//					' . $qType . '

//					order by ctm.id  DESC ';

//                        $result = $mysql->getResult($sql, false, 20, $offset, CONFIG_PATH_SITE_ADMIN . 'users_edit.html?id=3&limit=60#tabs-10', array());

//			$query = $mysql->query($sql . $qLimit);

//	

//			$pCode ='';

//			if($mysql->rowCount($query) > 0)

//			{

//				$transs = $mysql->fetchArray($query);

//			

    ?>

    <h4 class="m-b-20">

        <b><?php echo $usernameee; ?> </b>  <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Email Log')); ?>

    </h4>

    <table class="table table-bordered table-hover">

        <tr>

            <th>#</th>

            <th style="width: 500px"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Subject')); ?></th>

            <th style="width: 200px"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Date')); ?></th>

            <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Action')); ?></th>

        </tr>

        <?php

        $sql = 'select * from ' . MAIL_HISTORY . ' a where a.user_id=' . $member->getUserId() . '  order by a.date_time desc';

        $sql = 'select b.id,b.mail_subject as subject,b.mail_body as content,b.time_stamp date_time from nxt_email_queue b 

where b.mail_to=' . $mysql->quote($useremailll) . '

order by b.time_stamp desc';

//echo $sql;

        $pCode = $paging->recordsetNav($sql, CONFIG_PATH_SITE_ADMIN . 'user_mail_log.html', $offset, $limit, $extraURL);

        //  echo $sql;

        $query = $mysql->query($sql.$qLimit);

        $i=0;

        if ($mysql->rowCount($query) > 0) {

            $rows = $mysql->fetchArray($query);



            //   $result = $mysql->getResult($sql);

            //   if ($result['COUNT']) {

            foreach ($rows as $row) {

                echo '<tr>';

                echo '<td>' . $row['id'] .'</td>';

                echo '<td>' . $mysql->prints($row['subject']) . '</td>';





//                        $dtReplyDateTime = new DateTime($row['date_time'], new DateTimeZone($admin->timezone()));

//                        $dtReplyDateTime->setTimezone(new DateTimeZone($member->timezone()));

//                        $dtReplyDateTime = $dtReplyDateTime->format('d-M-Y H:i');

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

                echo '<td>' . $finaldate . '</td>';

                ?>





                <td>



                    <input type="button" class="btn btn-info" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Show Detail')); ?>" onclick="popUp('mail_history_detail.html?e_id=<?php echo $row['id']; ?>&u_id=<?php echo $id; ?>');" />

                </td>

        <?php

        echo '</tr>';

    }

} else {

    echo '<tr><td colspan="7" class="no_record">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')) . '</td></tr>';

}

?>

    </table>

     <div class="row m-t-20">
            <div class="col-md-6 p-l-0">
                <div class="TA_C navigation" id="paging">
                    <?php echo $pCode;  ?>
                </div>
            </div>
            <div class="col-md-6">
                
            </div>
        </div>

    <a href="<?php echo CONFIG_PATH_SITE_ADMIN . 'users.html'; ?>" class="btn btn-info"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Back To Userlist')); ?></a>

</div>

<script type="text/javascript">

    function popUp(url) {

        window.open(url, 'PHP Pop Up', 'width=800,height=800');

    }

</script>