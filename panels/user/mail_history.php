<?php
defined("_VALID_ACCESS") or die("Restricted Access");
$mysql = new mysql();
$paging = new paging();
$offset = (isset($_GET["offset"])) ? $_GET["offset"] : 0;
$limit = CONFIG_ORDER_PAGE_SIZE;
$qLimit = " limit $offset,$limit";
$extraURL = "";
?>
<div class="col-lg-10">
    <div class="panel">
        <div class="panel-heading">
            <h3><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Email_History')); ?></h3>
        </div>
        <div class="card-box">
            <table class="table table-bordered table-hover">
                <tr>
                    <th style="width: 500px"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Subject')); ?></th>
                    <th style="width: 200px"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Date')); ?></th>
                      <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Action')); ?></th>
                </tr>
                <?php
                
                
                
                 $sql2 = 'select a.email,a.username from '.USER_MASTER.'  a where a.id=' . $member->getUserId();

        $query22 = $mysql->query($sql2);

        $rowCount2 = $mysql->rowCount($query22);

        if ($rowCount2 != 0) {

            $rowsss = $mysql->fetchArray($query22);

            $row223 = $rowsss[0];

            $useremailll = $row223['email'];

            //  $usernameee = $row223['username'];
        }
                
                
                
                $sql = 'select * from ' . MAIL_HISTORY . ' a where a.user_id=' . $member->getUserId() . '  order by a.date_time desc';
                $sql = 'select b.id,b.mail_subject as subject,b.mail_body as content,b.time_stamp date_time from nxt_email_queue b 
where b.mail_to=' . $mysql->quote($useremailll) . '
order by b.time_stamp desc';
//echo $sql;exit;
                $pCode = $paging->recordsetNav($sql, CONFIG_PATH_SITE_USER . 'mail_history.html', $offset, 20, $extraURL);

                //  echo $sql;
                $query = $mysql->query($sql . $qLimit);

                if ($mysql->rowCount($query) > 0) {
                    $rows = $mysql->fetchArray($query);

                    //   $result = $mysql->getResult($sql);
                    //   if ($result['COUNT']) {
                    foreach ($rows as $row) {
                        echo '<tr>';
                        echo '<td>' . $mysql->prints($row['subject']) . '</td>';
                    //    echo '<td>' . $mysql->prints($row['content']) . '</td>';


                        
                          $finaldate2 = $member->datecalculate($row['date_time']);

                        echo '<td>' . $finaldate2 . '</td>';
                        ?>
                <td>
                        
                    <input type="button" class="btn btn-info" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Show_Detail')); ?>" onclick="popUp('mail_history_detail.html?id=<?php echo $row['id']; ?>');" />
</td>
                        <?php

                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="7" class="no_record">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')) . '</td></tr>';
                }
                ?>
            </table></div>
    </div>
    <?php echo $pCode; ?>

</div>
<script type="text/javascript">
function popUp(url) {
window.open(url,'PHP Pop Up','width=800,height=800');
}
</script>


