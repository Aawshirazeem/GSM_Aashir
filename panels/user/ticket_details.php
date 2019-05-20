<?php
defined("_VALID_ACCESS") or die("Restricted Access");
$id = $request->getStr('id');

$sql = 'select * from ' . TICKET_MASTER . '
				where ticket_id=' . $mysql->getInt($id) . ' and user_id=' . $mysql->getInt($member->getUserId());
$query = $mysql->query($sql);
if ($mysql->rowCount($query) == 0) {
    header("location:" . CONFIG_PATH_SITE_USER . "ticket.html?reply=" . urlencode('reply_not_authorized'));
}
$rows_ticket = $mysql->fetchArray($query);
?>
  <h1 class="text-center"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_ticket_details')); ?></h1>
<div class="card-box">

<div class="lock-to-top">
  
    <div class="btn-group pull-right">
        <a href="<?php echo CONFIG_PATH_SITE_USER; ?>ticket.html" class="btn btn-default"> <i class="icon-arrow-left"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_back_to_list')); ?></a>
    </div>
</div>

<H2 class="text-center"><?php echo $rows_ticket[0]['subject'] ?></H2>
</div>



<section class="panel">
    <header class="panel-heading tab-bg-dark-navy-blue ">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#tabs-1"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_ticket_details')); ?></a></li>
            <li><a data-toggle="tab" href="#tabs-2"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_ticket_reply')); ?></a></li>
        </ul>
    </header>
    <div class="panel-body">
        <div class="tab-content">
            <div id="tabs-1" class="tab-pane active">
                <div class="panel-heading">
                    <b><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Details')); ?></b>
                </div>
                <div class="panel-body">
                    <table class="MT5 table table-striped table-hover panel">
                        <tr>
                         
                            <th>#</th>
                           
                            <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_ticket_details')); ?></th>
                             <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_time')); ?></th>
                        </tr>
                        <?php
                        $paging = new paging();
                        $offset = (isset($_GET["offset"])) ? $_GET["offset"] : 0;
                        $limit = 10;
                        $qLimit = " limit $offset,$limit";
                       // $extraURL = "$id=".$id;
                        $extraURL = '&id=' . $id;

                        $sql = 'select * from ' . TICKET_DETAILS . ' where ticket_id=' . $mysql->getInt($id) . ' order by id DESC';
                        $query = $mysql->query($sql . $qLimit);
                        $strReturn = "";

                        $pCode = $paging->recordsetNav($sql, CONFIG_PATH_SITE_USER . 'ticket_details.html', $offset, $limit, $extraURL);

                        $i = $offset;

                        if ($mysql->rowCount($query) > 0) {
                            $rows = $mysql->fetchArray($query);
                            foreach ($rows as $row) {
                                $i++;
                                echo '<tr>';
                              //  echo '<td><img src="' . CONFIG_PATH_IMAGES . 'skin/' . (($row['user_admin'] == "0") ? 'user_64' : 'star_32') . '.png" wdith="32" height="32alt=""></td>';
                                  echo '<td>' . $i. '</td>';
                                echo '<td>' . nl2br($row['comments']) . '</pre></td>';
                                   $finaldate2 = $member->datecalculate($row['date_time']);
                                echo '<td>' . $finaldate2. '</td>';
                             
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="7" class="no_record">No record found!</td></tr>';
                        }
                        ?>
                    </table>
                    <?php echo $pCode; ?>



                </div>
            </div>
            <div id="tabs-2" class="tab-pane">
                <div class="panel-heading">
                    <b><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Reply')); ?></b>
                </div>
                <div class="panel-body">

                    <form action="<?php echo CONFIG_PATH_SITE_USER; ?>ticket_reply_process.do" method="post">
                        <div class="form-group">
                            <textarea name="details" class="form-control" id="details" rows="8"></textarea>
                            <input type="hidden" value="<?php echo $id; ?>" name="id" id="id" >
                            <input type="hidden" value="<?php echo $id; ?>" name="id" id="id" >
                        </div>
                        
                        <?php
                       
                                if($rows_ticket[0]['status']!=0)
                                {
                        ?>
                        
                        
                        <input type="submit" class="btn-success">
                        <?php
                                }
                                else
                                {
                                    echo '<b>Ticket Closed...You Cannot Reply<b>';
                                }
                                
                                ?>
                    </form>


                </div>
            </div>


        </div>

    </div>
</section>