<?php
defined("_VALID_ACCESS") or die("Restricted Access");

$id = $request->getInt('id');
?>
<div class="lock-to-top">
    <h1><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_ticket_details')); ?></h1>
      <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>ticket.html" class="btn btn-default"> <i class="icon-arrow-left"></i><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_back')); ?></a>
</div>  

<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div id="tabs" class="text-center">
            <h2><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Details')); ?></h2>         
            <div id="tabs-1" class="card-box">

                <table class="MT5 table table-striped table-hover">
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
                    $extraURL = '';
                     $extraURL = '&id=' . $id;


                    $qType = '';



                    $qType = ($qType == '') ? '' : ' and ' . $qType;


                    $sql = 'select * from ' . TICKET_DETAILS . ' where ticket_id=' . $mysql->getInt($id) . ' order by id DESC';
                    $query = $mysql->query($sql . $qLimit);
                    $strReturn = "";

                    $pCode = $paging->recordsetNav($sql, CONFIG_PATH_SITE_ADMIN . 'ticket_details.html', $offset, $limit, $extraURL);

                    $i = $offset;

                    if ($mysql->rowCount($query) > 0) {
                        $rows = $mysql->fetchArray($query);
                        foreach ($rows as $row) {
                            $i++;
                             $finaldate = $admin->datecalculate($row['date_time']);
                            echo '<tr>';
                            //echo '<td><img src="' . CONFIG_PATH_IMAGES . 'skin/' . (($row['user_admin'] == "0") ? 'user_64' : 'star_32') . '.png" height="32" alt=""></td>';
                            echo '<td>' . $i . '</td>';
                            echo '<td>' . nl2br(html_entity_decode($row['comments'])) . '</td>';
                            echo '<td>' . $finaldate . '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="7" class="no_record">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')) . '</td></tr>';
                    }
                    ?>
                </table>
                    <?php echo $pCode; ?>

            </div>
            <div id="tabs-2" class="card-box">
                <form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>ticket_reply_process.do" method="post" name="frm_ticket_reply" id="frm_ticket_reply" class="formSkin noWidth">
                    <fieldset>
                        <legend><?php $lang->prints('lbl_post_your_reply'); ?></legend>
                        <p class="field">
                            <textarea name="details" class="textbox_fix" id="details" rows="8" style="width:100%"></textarea>
                            <input type="hidden" value="<?php echo $id; ?>" name="id" id="id" >
                        </p>
                        <p>
                            <input type="submit" class="btn btn-success" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_post')); ?>" >
                        </p>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>